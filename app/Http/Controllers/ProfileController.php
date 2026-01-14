<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\MedicalStore;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('admin.profile');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        $business = null;
        
        // Check if user has associated business (Restaurant or Medical Store)
        if ($user->Role == 3) { // Restaurant owner
            $business = Restaurant::where('UserId', $user->UserId)->first();
        } elseif ($user->Role == 2) { // Medical store owner (Supplier)
            $business = MedicalStore::where('UserId', $user->UserId)->first();
        }
        
        return view('admin.profile', ['user' => $user, 'business' => $business]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:Users,Email,' . $user->UserId . ',UserId',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update basic user info
        $user->Name = $validated['name'];
        $user->Email = $validated['email'];
        $user->Phone = $validated['phone'] ?? $user->Phone;

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->AvatarUrl && Storage::exists('public/' . $user->AvatarUrl)) {
                Storage::delete('public/' . $user->AvatarUrl);
            }

            // Upload new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->AvatarUrl = $path;
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully');
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
    public function show(Request $request)
    {
        return view('admin.profile', ['user' => $request->user()]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
