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
        //
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
    public function allRestaurants()
    {
        $restaurants = Restaurant::with('user')->paginate(4);

        return view('admin.users.restaurants.index', compact('restaurants'));
    }
}
