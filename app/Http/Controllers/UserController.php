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
    // public function index()
    // {
        
    //     $users = User::whereIn('role', ['admin', 'sub_admin'])->paginate(8);
    //     return view('admin.users.index', compact('users'));
    // }

    public function index(Request $request)
    {
        $query = User::query()->where('role', 'admin');

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
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'designation' => 'required|string',
            'department' => 'required|string',
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'designation' => $request->designation,
            'department' => $request->department,
        ]);

        // Optional: flash a success message
        return redirect()->route('users.index')->with('success', 'User created successfully!');
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
