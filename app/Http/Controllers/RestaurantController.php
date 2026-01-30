<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

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
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'Name' => 'required|string|max:150',
    //         'email' => 'required|email|exists:Users,Email',
    //         'Address' => 'required|string',
    //         'FLICNo' => 'required|string|max:20',
    //         'GSTIN' => 'required|string|max:15',
    //         'PAN' => 'required|string',
    //         'CuisineType' => 'nullable|string|max:100',
    //         'OpenTime' => 'required|date_format:H:i',
    //         'CloseTime' => 'required|date_format:H:i',
    //         'PrepTimeMin' => 'nullable|integer',
    //         'DeliveryFee' => 'required|numeric',
    //         'MinOrder' => 'required|numeric',
    //         'Latitude' => 'nullable|numeric',
    //         'Longitude' => 'nullable|numeric',
    //     ]);

    //     // Get UserId
    //     $user = User::where('Email', $validated['email'])->firstOrFail();

    //     // Prevent duplicate
    //     if(Restaurant::where('UserId', $user->UserId)->exists()) {
    //         return redirect()->back()->withErrors(['email' => 'This Email already has a Registered Restaurant.'])->withInput();
    //     }

    //     $lastPriority = Restaurant::max('Priority') ?? 0;

    //     $restaurant = Restaurant::create([
    //         // 'RestaurantId' => (string) Str::uuid(),
    //         'UserId' => $user->UserId,
    //         'Name' => $validated['Name'],
    //         // 'Slug' => Str::slug($validated['Name']),
    //         'Address' => $validated['Address'],
    //         'FLICNo' => $validated['FLICNo'],
    //         'GSTIN' => $validated['GSTIN'],
    //         'PAN' => $validated['PAN'] ?? null,
    //         'IsPureVeg' => $request->has('IsPureVeg') ? true : false,
    //         'CuisineType' => $validated['CuisineType'] ?? 'Nepali',
    //         'OpenTime' => $validated['OpenTime'],
    //         'CloseTime' => $validated['CloseTime'],
    //         'PrepTimeMin' => $validated['PrepTimeMin'] ?? 30,
    //         'DeliveryFee' => $validated['DeliveryFee'] ?? 0,
    //         'MinOrder' => $validated['MinOrder'] ?? 0,
    //         'Latitude' => $validated['Latitude'],
    //         'Longitude' => $validated['Longitude'],
    //         'IsActive' => $request->has('IsActive') ? true : false,
    //         'Priority' => $lastPriority + 1,
    //         'CreatedAt' => now(),
    //     ]);

    //     return redirect()->route('admin.restaurants.list')->with('success', 'Restaurant added successfully!');
    // }


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


    /***************************** 
    ********* STORE ***********
    ***************************/
    // public function store(Request $request)
    // {
    //     $token = session('jwt_token');

    //     if (!$token) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Session expired. Please login again.'
    //         ], 401);
    //     }

    //     $response = Http::withHeaders([
    //         'Authorization' => 'Bearer ' . $token,
    //         'Accept' => 'application/json',
    //     ])->post(
    //         'https://pcsdecom.azurewebsites.net/api/admin/register/restaurant',
    //         [
    //             'restaurantName' => $request->restaurantName,
    //             'adminName' => $request->adminName,
    //             'adminEmail' => $request->adminEmail,
    //             'adminPassword' => $request->adminPassword,
    //             'adminPhone' => $request->adminPhone,

    //             'restaurantAddress' => $request->restaurantAddress,
    //             'flicNo' => $request->flicNo,
    //             'gstin' => $request->gstin,
    //             'pan' => $request->pan,
    //             'cuisineType' => $request->cuisineType,
    //             'isPureVeg' => (bool) $request->isPureVeg,
    //             'priority' => 0,

    //             'openTime' => $request->openTime,
    //             'closeTime' => $request->closeTime,
    //             'prepTimeMin' => (int) $request->prepTimeMin,
    //             'deliveryFee' => (float) $request->deliveryFee,
    //             'minOrder' => (float) $request->minOrder,

    //             'location' => [
    //                 'latitude' => (float) $request->latitude,
    //                 'longitude' => (float) $request->longitude,
    //             ]
    //         ]
    //     );

    //     if ($response->failed()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $response->json()['message'] ?? 'API error'
    //         ], 500);
    //     }

    //     return response()->json([
    //         'success' => true
    //     ]);
    // }


    public function store(Request $request)
    {
        // Token check
        $token = session('jwt_token');
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please login again.'
            ], 401);
        }

        // Validation
        $validated = $request->validate([
            'restaurantName'     => 'required|string|max:100',
            'restaurantAddress'  => 'required|string|max:200',
            'flicNo'             => 'required|string|max:100',
            'gstin'              => 'required|string|max:50',
            'pan'                => 'required|string|max:50',

            'adminName'          => 'required|string|max:100',
            'adminEmail'         => 'required|email',
            'adminPassword'      => 'required|string|min:8|max:50',
            'adminPhone'         => 'required|string|min:9|max:15',

            'cuisineType'        => 'required|string',
            'openTime'           => 'required',
            'closeTime'          => 'required',
            'prepTimeMin'        => 'nullable|integer|min:0',
            'deliveryFee'        => 'required|numeric|min:0',
            'minOrder'           => 'required|numeric|min:0',
            'isPureVeg'          => 'nullable|boolean',

            'latitude'           => 'required|numeric',
            'longitude'          => 'required|numeric',
        ]);

        // API payload (MATCHES SWAGGER 100%)
        $payload = [
            'restaurantName'     => $validated['restaurantName'],
            'adminName'          => $validated['adminName'],
            'adminEmail'         => $validated['adminEmail'],
            'adminPassword'      => $validated['adminPassword'],
            'adminPhone'         => $validated['adminPhone'],

            'restaurantAddress'  => $validated['restaurantAddress'],
            'flicNo'             => $validated['flicNo'],
            'gstin'              => $validated['gstin'],
            'pan'                => $validated['pan'],

            'cuisineType'        => $validated['cuisineType'],
            'isPureVeg'          => $request->boolean('isPureVeg'),
            'openTime'           => $validated['openTime'],
            'closeTime'          => $validated['closeTime'],
            'prepTimeMin'        => $validated['prepTimeMin'],
            'deliveryFee'        => $validated['deliveryFee'],
            'minOrder'           => $validated['minOrder'],

            'location' => [
                'latitude'  => $validated['latitude'],
                'longitude' => $validated['longitude'],
            ]
        ];

        // API Call
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->post('https://pcsdecom.azurewebsites.net/api/admin/register/restaurant', $payload);

        // API error
        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'API error'
            ], $response->status());
        }
        
        // Success
        return response()->json([
            'success' => true,
            // 'message' => 'Restaurant registered successfully'
        ]);
    }


    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|digits:6',
        ]);

        $response = Http::post('https://pcsdecom.azurewebsites.net/api/Auth/verify-email', [
            'email' => $request->email,
            'code'  => $request->otp,
        ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'OTP invalid or expired'
            ], $response->status() ?: 422);
        }

        $data = $response->json();

        return response()->json([
            'success' => true,
            'message' => $data['message'] ?? 'Restaurant created successfully.',
            'redirect' => route('admin.restaurants.list'),
        ]);
    }

    // Resend OTP
    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $response = Http::post('https://pcsdecom.azurewebsites.net/api/Auth/resend-verification', [
            'email' => $request->email,
        ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => $response->json('message') ?? 'Failed to resend OTP'
            ], $response->status() ?: 500);
        }

        return response()->json([
            'success' => true,
            'message' => $response->json('message') ?? 'OTP sent successfully'
        ]);
    }
}
