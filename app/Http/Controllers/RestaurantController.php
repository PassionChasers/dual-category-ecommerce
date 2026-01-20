<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Name' => 'required|string|max:150',
            'email' => 'required|email|exists:Users,Email',
            'Address' => 'required|string',
            'FLICNo' => 'required|string|max:20',
            'GSTIN' => 'required|string|max:15',
            'PAN' => 'required|string',
            'CuisineType' => 'nullable|string|max:100',
            'OpenTime' => 'required|date_format:H:i',
            'CloseTime' => 'required|date_format:H:i',
            'PrepTimeMin' => 'nullable|integer',
            'DeliveryFee' => 'required|numeric',
            'MinOrder' => 'required|numeric',
            'Latitude' => 'nullable|numeric',
            'Longitude' => 'nullable|numeric',
        ]);

        // Get UserId
        $user = User::where('Email', $validated['email'])->firstOrFail();

        // Prevent duplicate
        if(Restaurant::where('UserId', $user->UserId)->exists()) {
            return redirect()->back()->withErrors(['email' => 'This Email already has a Registered Restaurant.'])->withInput();
        }

        $lastPriority = Restaurant::max('Priority') ?? 0;

        $restaurant = Restaurant::create([
            // 'RestaurantId' => (string) Str::uuid(),
            'UserId' => $user->UserId,
            'Name' => $validated['Name'],
            // 'Slug' => Str::slug($validated['Name']),
            'Address' => $validated['Address'],
            'FLICNo' => $validated['FLICNo'],
            'GSTIN' => $validated['GSTIN'],
            'PAN' => $validated['PAN'] ?? null,
            'IsPureVeg' => $request->has('IsPureVeg') ? true : false,
            'CuisineType' => $validated['CuisineType'] ?? 'Nepali',
            'OpenTime' => $validated['OpenTime'],
            'CloseTime' => $validated['CloseTime'],
            'PrepTimeMin' => $validated['PrepTimeMin'] ?? 30,
            'DeliveryFee' => $validated['DeliveryFee'] ?? 0,
            'MinOrder' => $validated['MinOrder'] ?? 0,
            'Latitude' => $validated['Latitude'],
            'Longitude' => $validated['Longitude'],
            'IsActive' => $request->has('IsActive') ? true : false,
            'Priority' => $lastPriority + 1,
            'CreatedAt' => now(),
        ]);

        return redirect()->route('admin.restaurants.list')->with('success', 'Restaurant added successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        return view('admin.users.restaurants.show', compact('restaurant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Restaurant $restaurant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $data = $request->only([
            'Name','Slug','LicenseNumber','GSTIN','PAN','IsActive','IsFeatured',
            'OpenTime','CloseTime','RadiusKm','DeliveryFee','MinOrder',
            'Latitude','Longitude','Priority'
        ]);

        $data['IsActive'] = $request->has('IsActive') ? (bool)$request->get('IsActive') : $restaurant->IsActive;
        $data['IsFeatured'] = $request->has('IsFeatured') ? (bool)$request->get('IsFeatured') : $restaurant->IsFeatured;

        if ($request->hasFile('image')) {
            // delete old
            if ($restaurant->ImageUrl && Storage::disk('public')->exists($restaurant->ImageUrl)) {
                Storage::disk('public')->delete($restaurant->ImageUrl);
            }
            $file = $request->file('image');
            $filename = Str::slug($data['Name'] ?? $restaurant->Name) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('restaurants', $filename, 'public');
            $data['ImageUrl'] = $path;
        }

        $restaurant->update($data);

        return redirect()->route('admin.restaurants.list')->with('success','Restaurants updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        if ($restaurant->ImageUrl && Storage::disk('public')->exists($restaurant->ImageUrl)) {
            Storage::disk('public')->delete($restaurant->ImageUrl);
        }
        $restaurant->delete();

        return redirect()->route('admin.restaurants.list')->with('success', 'Restaurant deleted.');
    }

    /**
     * all restaurant 
     */
    public function allRestaurants(Request $request)
    {
        $query = Restaurant::whereHas('user', function ($q) {
            $q->where('Role', 3);
        })->with('user');

        // search by name, license, gstin, pan
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('Name', 'ilike', "%{$search}%")
                    ->orWhere('FLICNo', 'ilike', "%{$search}%")
                    ->orWhere('GSTIN', 'ilike', "%{$search}%")
                    ->orWhere('PAN', 'ilike', "%{$search}%");
            });
        }

        /* Online / Offline filter (from select box) */
        if ($request->filled('onlineStatus')) {
            $query->where('IsActive', $request->onlineStatus === 'true');
        }

        $perPage = (int) $request->get('per_page', 10);
        $perPage = in_array($perPage, [5,10,25,50]) ? $perPage : 10;

        $users = $query->paginate($perPage)->appends($request->except('page'));

        if ($request->ajax()) {
            return view(
                'admin.business.restaurant.searchedRestaurants',
                compact('users')
            );
        }

        return view('admin.business.restaurant.index', compact('users'));
    }
}
