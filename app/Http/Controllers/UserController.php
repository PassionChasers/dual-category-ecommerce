<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
        $query = User::query()->where('Role', 'Admin');

        // Search by user name
        if ($request->filled('search')) {

            $search = $request->search;
            // $search = ucfirst(strtolower($request->search)); // First letter uppercase

            $query->where('name', 'like', '%' . $search . '%');
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
        $query = User::query()->where('Role', 'Customer');

        // Search by user name
        if ($request->filled('search')) {

            $search = $request->search;
            // $search = ucfirst(strtolower($request->search)); // First letter uppercase

            $query->where('name', 'like', '%' . $search . '%');
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
            return view('admin.users.searchedCustomers', compact('users'))->render();
        }
        //Normal load
        return view('admin.users.customers.index', compact('users'));
    }

    public function medicalstores(Request $request)
    {
        $query = User::query()->where('Role', 'Supplier');

        // Search by user name
        if ($request->filled('search')) {

            $search = $request->search;
            // $search = ucfirst(strtolower($request->search)); // First letter uppercase

            $query->where('name', 'like', '%' . $search . '%');
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
            return view('admin.users.searchedMedicalstore', compact('users'))->render();
        }
        //Normal load
        return view('admin.users.medical_stores.index', compact('users'));
    }

    public function restaurants(Request $request)
    {
        $query = User::query()->where('Role', 'Restaurant');

        // Search by user name
        if ($request->filled('search')) {

            $search = $request->search;
            // $search = ucfirst(strtolower($request->search)); // First letter uppercase

            $query->where('name', 'like', '%' . $search . '%');
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
            return view('admin.users.searchedRestaurants', compact('users'))->render();
        }
        //Normal load
        return view('admin.users.restaurants.index', compact('users'));
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
            'email'              => 'required|email|unique:users,Email',
            'password'           => 'required|min:6',
            'phone'              => 'required|string|max:20',
            'avatar_url'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role'               => 'required|string|in:Supplier,Admin,Restaurant','Customer',
            // 'is_active'          => 'required|boolean',
            // 'is_email_verified'  => 'required|boolean',
            // 'is_business_admin'  => 'required|boolean',
        ]);

        // Avatar Upload
        $imagePath = null;
        if ($request->hasFile('avatar_url')) {
            $imagePath = $request->file('avatar_url')
                ->store('uploads/users', 'public');
        }

        // Create User
        // User::create([
        //     'Name'              => $validated['name'],
        //     'Email'             => $validated['email'],
        //     'Password'          => Hash::make($validated['password']),
        //     'Phone'             => $validated['phone'] ?? null,
        //     'AvatarUrl'         => $avatarPath,
        //     'Role'              => $validated['role'],
        //     'IsActive'          => $validated['is_active'],
        //     'IsEmailVerified'   => $validated['is_email_verified'],
        //     'IsBusinessAdmin'   => $validated['is_business_admin'],
        //     'remember_token'    => Str::random(60),
        //     'CreatedAt'         => now(),
        // ]);

        return redirect()
            ->back()
            ->with('success', 'Created successfully.');
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
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        // Update other fields as necessary
        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
