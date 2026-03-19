<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    private function getUsersByRole(Request $request, $role, $viewPath)
    {
        // $query = User::query()->where('Role', $role);

        $query = User::select('UserId','Name','Email','Phone','Role','IsActive')
        ->where('Role',$role);

        if ($role == 5) {
            $query->with('deliveryMan');
        }

        if ($role == 2) {
            $query->with('medicalstores');
        }

        if ($role == 3) {
            $query->with('restaurants');
        }

        if ($request->filled('search')) {
            $searchhash = hash('sha256', strtolower(trim($request->search)));

            $query->where(function ($q) use ($searchhash) {
                $q->where('EmailHash', $searchhash)
                ->orWhere('PhoneHash', $searchhash);
            });
        }

        if ($request->filled('onlineStatus')) {
            $query->where('IsActive', $request->onlineStatus);
        }

        $perPage = $request->get('per_page', 10);
        $allowedPerPage = [5,10,25,50];
        $perPage = in_array($perPage,$allowedPerPage) ? $perPage : 10;

        // Paginate results with query parameters
        $users = $query->latest('UserId')->paginate($perPage)->appends($request->only(['search','onlineStatus','per_page']));

        if ($request->ajax()) {
            return view("$viewPath", compact('users','perPage','allowedPerPage'))->render();
        }

        return view("$viewPath.index", compact('users','perPage','allowedPerPage'));
    } 

    public function admin(Request $request)
    {
        if($request->ajax()){
            return $this->getUsersByRole($request,User::ROLE_ADMIN,'admin.users.searchedUsers');
        }
        return $this->getUsersByRole($request,User::ROLE_ADMIN,'admin.users');
    }

    public function customers(Request $request)
    {
        if($request->ajax()){
            return $this->getUsersByRole($request,User::ROLE_CUSTOMER,'admin.users.customers.searchedCustomers');
        }
        return $this->getUsersByRole($request,User::ROLE_CUSTOMER,'admin.users.customers');
    }

    public function medicalstores(Request $request)
    {
        if($request->ajax()){
            return $this->getUsersByRole($request,User::ROLE_SUPPLIER,'admin.users.medical_stores.searchedMedicalstore');
        }
        return $this->getUsersByRole($request,User::ROLE_SUPPLIER,'admin.users.medical_stores');
    }

    public function restaurants(Request $request)
    {
        if($request->ajax()){
            return $this->getUsersByRole($request,User::ROLE_RESTAURANT,'admin.users.restaurants.searchedRestaurants');
        }
        return $this->getUsersByRole($request,User::ROLE_RESTAURANT,'admin.users.restaurants');
    }

    public function deliveryMan(Request $request)
    {
        if($request->ajax()){
            return $this->getUsersByRole($request,User::ROLE_DELIVERY,'admin.users.deliveryMan.searchedDeliveryMan');
        }
        return $this->getUsersByRole($request,User::ROLE_DELIVERY,'admin.users.deliveryMan');
    }    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find user
        $user = User::where('UserId', $id)->firstOrFail();

        // Check JWT token in session
        $token = session('jwt_token');
        if (!$token) {
            return redirect()->route('login')
                ->with('error', 'Session expired. Please login again.');
        }

        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|min:9|max:14',
        ]);

        try {

            // API call (PUT)
            $response = Http::withToken($token)
                ->acceptJson()
                ->put("https://pcsdecom.azurewebsites.net/api/admin/users/{$id}", [
                    'name' => $validated['name'],
                    'phone' => $validated['contact_number'],
                    'isActive' => $request->boolean('IsActive'),
                ]);

            // If API fails
            if ($response->failed()) {
                return back()->with(
                    'error',
                    $response->json('message') ?? 'API request failed'
                );
            }

            // Redirect based on role
            return match ($user->Role) {
                4 => redirect()->route('users.admin.index')
                        ->with('success', 'Admin updated successfully.'),

                1 => redirect()->route('users.customers.index')
                        ->with('success', 'Customer updated successfully.'),

                2 => redirect()->route('users.medicalstores.index')
                        ->with('success', 'Medical Store updated successfully.'),

                3 => redirect()->route('users.restaurants.index')
                        ->with('success', 'Restaurant updated successfully.'),

                5 => redirect()->route('users.delivery-man.index')
                        ->with('success', 'Delivery Man updated successfully.'),

                default => back()->with('success', 'User updated successfully.'),
            };

        } catch (\Exception $e) {

            return back()->with('error', 'Something went wrong: ' . $e->getMessage());

        }
    }

    // public function update(Request $request, string $id)
    // {
    //     $user = User::findOrFail($id);

    //     // $request->validate([
    //     //     'name' => 'required|string|max:255',
    //     //     'email' => [
    //     //         'required',
    //     //         'email',
    //     //         Rule::unique('Users', 'EmailHash')
    //     //             ->ignore($user->UserId, 'UserId')
    //     //     ],
    //     // ]);

    //     // 🔥 DO NOT manually set EmailHash
    //     // Mutator will handle encryption + hash automatically

    //     // $user->Name  = $request->name;
    //     // $user->Email = $request->email;

    //     $user->IsActive = $request->has('IsActive');

    //     $user->save();

    //     return match ($user->Role) {
    //         4 => redirect()->route('users.admin.index')->with('success', 'Admin updated successfully.'),
    //         1 => redirect()->route('users.customers.index')->with('success', 'Customer updated successfully.'),
    //         2 => redirect()->route('users.medicalstores.index')->with('success', 'Medical Store updated successfully.'),
    //         3 => redirect()->route('users.restaurants.index')->with('success', 'Restaurant updated successfully.'),
    //         5 => redirect()->route('users.delivery-man.index')->with('success', 'Delivery Man updated successfully.'),
    //         default => back()->with('success', 'User updated successfully.'),
    //     };
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $user = User::findOrFail($id);
        try {
            User::where('UserId',$id)->delete();
            return back()->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'User cannot be deleted.');
        }
        
    }

    public function createAdmin(Request $request)
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
            'adminName'               => 'required|string|max:100',
            'adminEmail'              => 'required|email',
            'adminPassword'           => 'required|min:8',
            'adminPhone'              => 'nullable|string|max:15|min:9',
            // 'avatar_url'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // API Call
        $response = Http::timeout(5)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->post('https://pcsdecom.azurewebsites.net/api/admin/system/system-admins', 
            [
                'name'          => $validated['adminName'],
                'email'         => $validated['adminEmail'],
                'password'      => $validated['adminPassword'],
                'phone'         => $validated['adminPhone'],
                // 'avatar_url'    => $imagePath,
            ]
        );

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
            'message' => 'Admin registered successfully',
            'redirect' => route('users.admin.index'),
        ]);
    } 
    
    public function createDeliveryMan(Request $request)
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
            // 'name'               => 'required|string|max:100',
            // 'email'              => 'required|email',
            // 'phone'              => 'required|string|max:15|min:9',
            // 'vehicleType'        => 'required|string|max:200',
            // 'vehicleNumber'      => 'required|string|max:100',
            // 'licenseNumber'      => 'required|string|max:100',
            // 'latitude'           => 'required|number|max:200',
            // 'longitude'          => 'required|number|max:200',

            'name'           => 'required|string|max:100',
            'email'          => 'required|email',
            'phone'          => 'required|string|min:9|max:15',

            'vehicleType'    => 'required|string|max:200',
            'vehicleNumber'  => 'required|string|max:100',
            'licenseNumber'  => 'required|string|max:100',

            'latitude'       => 'required|numeric|between:-90,90',
            'longitude'      => 'required|numeric|between:-180,180',

        ]);

        // API Call
        $response = Http::timeout(5)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->post('https://pcsdecom.azurewebsites.net/api/admin/register/delivery-man', 
            [
                'name'          => $validated['name'],
                'email'         => $validated['email'],
                'phone'         => $validated['phone'],
                'vehicleType'   => $validated['vehicleType'],
                'vehicleNumber' => $validated['vehicleNumber'],
                'licenseNumber' => $validated['licenseNumber'],
                'location'      => [
                    'latitude'  => (float) $validated['latitude'],
                    'longitude' => (float) $validated['longitude'],
                ],
            ]
        );

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
            'message' => 'Delivery Man registered successfully, please check your email to see your login Password.',
            'redirect' => route('users.delivery-man.index'),
        ]);
    } 
    
    // public function verifyOtp(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'otp'   => 'required|digits:6',
    //     ]);

    //     $response = Http::post('https://pcsdecom.azurewebsites.net/api/Auth/verify-email', [
    //         'email' => $request->email,
    //         'code'  => $request->otp,
    //     ]);

    //     if ($response->failed()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $response->json('message') ?? 'OTP invalid or expired'
    //         ], $response->status() ?: 422);
    //     }

    //     $data = $response->json();

    //     return response()->json([
    //         'success' => true,
    //         'message' => $data['message'] ?? 'Restaurant created successfully.',
    //         'redirect' => route('users.admin.index'),
    //     ]);
    // }

    // // Resend OTP
    // public function resendOtp(Request $request)
    // {
    //     $request->validate(['email' => 'required|email']);

    //     $response = Http::post('https://pcsdecom.azurewebsites.net/api/Auth/resend-verifivation', [
    //         'email' => $request->email,
    //     ]);

    //     if ($response->failed()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $response->json('message') ?? 'Failed to resend OTP'
    //         ], $response->status() ?: 500);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => $response->json('message') ?? 'OTP sent successfully'
    //     ]);
    // }
}
