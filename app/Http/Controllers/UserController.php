<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    
    public function admin(Request $request)
    {
        $query = User::query()->where('Role', 4);

        // Search by user name
        if ($request->filled('search')) {

            $search = $request->search;
            // $search = ucfirst(strtolower($request->search)); // First letter uppercase

            $query->where('Name', 'ilike', '%' . $search . '%');
        }

        //Filter by online status
        if ($request->filled('onlineStatus')) {
            $query->where('IsActive', $request->onlineStatus);
        }

        // Paginate results with query parameters
        $users = $query->latest()->paginate(5)->appends($request->all());
        // $allOrders = $medicineOrders;

        //AJAX response
        if($request->ajax()){
            return view('admin.users.searchedUsers', compact('users'))->render();
        }
        //Normal load
        return view('admin.users.index', compact('users'));
    }

    public function customers(Request $request)
    {
        $query = User::query()->where('Role', 1);

        // Search by user name
        if ($request->filled('search')) {

            $search = $request->search;
            // $search = ucfirst(strtolower($request->search)); // First letter uppercase

            $query->where('Name', 'ilike', '%' . $search . '%');
        }

        //Filter by online status
        if ($request->filled('onlineStatus')) {
            $query->where('IsActive', $request->onlineStatus);
        }

        // Paginate results with query parameters
        $users = $query->latest()->paginate(5)->appends($request->all());
        // $allOrders = $medicineOrders;

        //AJAX response
        if($request->ajax()){
            return view('admin.users.customers.searchedCustomers', compact('users'))->render();
        }
        //Normal load
        return view('admin.users.customers.index', compact('users'));
    }

    public function medicalstores(Request $request)
    {
        $query = User::query()->where('Role', 2);

        // Search by user name
        if ($request->filled('search')) {

            $search = $request->search;
            // $search = ucfirst(strtolower($request->search)); // First letter uppercase

            $query->where('Name', 'ilike', '%' . $search . '%');
        }

        //Filter by online status
        if ($request->filled('onlineStatus')) {
            $query->where('IsActive', $request->onlineStatus);
        }

        // Paginate results with query parameters
        $users = $query->latest()->paginate(5)->appends($request->all());
        // $allOrders = $medicineOrders;

        //AJAX response
        if($request->ajax()){
            return view('admin.users.medical_stores.searchedMedicalstore', compact('users'))->render();
        }
        //Normal load
        return view('admin.users.medical_stores.index', compact('users'));
    }

    public function restaurants(Request $request)
    {
        $query = User::query()->where('Role', 3);

        // Search by user name
        if ($request->filled('search')) {

            $search = $request->search;
            // $search = ucfirst(strtolower($request->search)); // First letter uppercase

            $query->where('Name', 'ilike', '%' . $search . '%');
        }

        //Filter by online status
        if ($request->filled('onlineStatus')) {
            $query->where('IsActive', $request->onlineStatus);
        }

        // Paginate results with query parameters
        $users = $query->latest()->paginate(5)->appends($request->all());
        // $allOrders = $medicineOrders;

        //AJAX response
        if($request->ajax()){
            return view('admin.users.restaurants.searchedRestaurants', compact('users'))->render();
        }
        //Normal load
        return view('admin.users.restaurants.index', compact('users'));
    }

    public function deliveryMan(Request $request)
    {
        $query = User::query()->where('Role', 5);

        // Search by user name
        if ($request->filled('search')) {

            $search = $request->search;
            // $search = ucfirst(strtolower($request->search)); // First letter uppercase

            $query->where('Name', 'ilike', '%' . $search . '%');
        }

        //Filter by online status
        if ($request->filled('onlineStatus')) {
            $query->where('IsActive', $request->onlineStatus);
        }

        // Paginate results with query parameters
        $users = $query->latest()->paginate(5)->appends($request->all());
        // $allOrders = $medicineOrders;

        //AJAX response
        if($request->ajax()){
            return view('admin.users.deliveryMan.searchedDeliveryMan', compact('users'))->render();
        }
        //Normal load
        return view('admin.users.deliveryMan.index', compact('users'));
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
        // Validation
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'required|email|unique:Users,Email',
            'password'           => 'required|min:8',
            'phone'              => 'nullable|string|max:20',
            'avatar_url'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role'               => 'required|string|in:Admin,Customer,Supplier,Restaurant',
        ]);

        // Map role string to numeric value
        $roleMap = [
            'Customer' => 1,
            'Supplier' => 2,
            'Restaurant' => 3,
            'Admin' => 4,
        ];

        // Avatar Upload
        $imagePath = null;
        if ($request->hasFile('avatar_url')) {
            $imagePath = $request->file('avatar_url')->store('uploads/users', 'public');
        }

        // Create User
        User::create([
            'Name'              => $validated['name'],
            'Email'             => $validated['email'],
            'PasswordHash'      => Hash::make($validated['password']),
            'Phone'             => $validated['phone'] ?? null,
            'AvatarUrl'         => $imagePath,
            'Role'              => $roleMap[$validated['role']],
            'IsActive'          => true,
            'IsEmailVerified'   => false,
            'IsBusinessAdmin'   => false,
        ]);

        return redirect()
            ->back()
            ->with('success', $validated['role'] . ' created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->Name = $request->input('name');
        $user->Email = $request->input('email');
        $user->IsActive = $request->input('IsActive') ? true : false;
        // Update other fields as necessary
        $user->save();

        return redirect()->route('users.admin.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.admin.index')->with('success', 'User deleted successfully.');
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
        $response = Http::withHeaders([
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
