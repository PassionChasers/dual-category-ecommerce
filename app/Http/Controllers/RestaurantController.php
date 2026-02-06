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
     * Display the specified resource.
     */
    public function show($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        return view('admin.users.restaurants.show', compact('restaurant'));
    }

    
    /***************************** 
    ********* UPDATE ***********
    ***************************/
    public function update(Request $request, $id)
    {
        // Find medical store
        $store = Restaurant::findOrFail($id);

        // Find related user
        $user = User::where('UserId', $store->UserId)->firstOrFail();

        // Validate input
        $request->validate([
            'storeName'     => 'required|string|max:255',
            'adminName'     => 'required|string|max:255',

            // Ignore current user for unique validation
            'adminEmail'    => 'required|email|unique:Users,Email,' . $user->UserId . ',UserId',
            'adminPhone'    => 'required|unique:Users,Phone,' . $user->UserId . ',UserId',

            'storeAddress'  => 'required|string|max:255',

            // Ignore current store for unique validation
            'flicNo' => 'required|unique:Restaurants,FLICNo,' . $store->RestaurantId . ',RestaurantId',
            'gstin'         => 'required|unique:Restaurants,GSTIN,' . $store->RestaurantId . ',RestaurantId',
            'pan'           => 'required|unique:Restaurants,PAN,' . $store->RestaurantId . ',RestaurantId',

            'openTime'      => 'required',
            'closeTime'     => 'required',

            'deliveryFee'   => 'nullable|numeric',
            'minOrder'      => 'nullable|numeric',
            'latitude'      => 'nullable|numeric',
            'longitude'     => 'nullable|numeric',
            'IsActive'      => 'nullable|boolean',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update User (Admin)
        |--------------------------------------------------------------------------
        */
        $user->update([
            'Name'  => $request->adminName,
            'Email' => $request->adminEmail,
            'Phone' => $request->adminPhone,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Update Medical Store
        |--------------------------------------------------------------------------
        */
        $store->update([
            'Name'          => $request->storeName,
            'Slug'          => Str::slug($request->storeName),
            'FLICNo' => $request->flicNo,
            'GSTIN'         => $request->gstin,
            'PAN'           => $request->pan,
            'Address'       => $request->storeAddress,
            'OpenTime'      => $request->openTime,
            'CloseTime'     => $request->closeTime,
            'DeliveryFee'   => $request->deliveryFee,
            'MinOrder'      => $request->minOrder,
            'Latitude'      => $request->latitude,
            'Longitude'     => $request->longitude,
            'IsActive'      => $request->has('IsActive') ? 1 : 0,
        ]);

        return redirect()->route('admin.restaurants.list')->with('success', 'Restaurant updated successfully.');
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

    /********************************
     ******** all restaurant********** 
     **********************************/
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
