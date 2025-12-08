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

    // Authenticate user login
    public function authenticate(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $remember = $request->filled('remember');

        
        if (Auth::attempt($credentials, $remember)) {
            // Regenerate session ID to prevent fixation attacks
            $request->session()->regenerate();
            $user = Auth::user();

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
