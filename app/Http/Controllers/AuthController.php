<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.login');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    // Authenticate user login
    public function authenticate(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Check if "Remember Me" checkbox is ticked
        // This will return true if the request has 'remember'
        $remember = $request->filled('remember');

        /**
         * Attempt login with remember option
         * - If $remember is true, Laravel will create a long-lived "remember me" cookie
         * - This cookie is linked to the `remember_token` column in the users table
         * - If the session expires, Laravel can still auto-login the user using that cookie
         */
        if (Auth::attempt($credentials, $remember)) {
            // Regenerate session ID to prevent fixation attacks
            $request->session()->regenerate();
            $user = Auth::user();


            // Redirect unverified users to verify-email page
            // if (!$user->hasVerifiedEmail()) {
            //     // Keep user logged in temporarily so middleware can work
            //     return redirect()->route('verification.notice')
            //         ->with('status', 'Please verify your email to access the system.');
            // }

            // Redirect to dashboard (or intended page)
            return redirect()->route('admin.dashboard');
        }

        // On failure, return back with error message
        return back()->withErrors([
            'email' => 'Invalid credentials provided.',
        ])->onlyInput('email');
    }


    // Logout user
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
