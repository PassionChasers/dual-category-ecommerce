<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login page
     */
    public function index(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return view('auth.login');
    }

    /**
     * Authenticate user via external API
     */
    // public function authenticate(Request $request)
    // {
    //     Log::info('Login process started');

    //     // Validate request
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|string|min:8',
    //     ]);

    //     Log::info('Validation passed', ['email' => $request->email]);

    //     // Call external API
    //     Log::info('Calling login API');

    //     $response = Http::post(
    //         'https://pcsdecom.azurewebsites.net/api/Auth/login',
    //         [
    //             'email' => $request->email,
    //             'password' => $request->password,
    //         ]
    //     );

    //     Log::info('API response received', [
    //         'status' => $response->status(),
    //         'body' => $response->json(),
    //     ]);

    //     // Handle API failure
    //     if (!$response->successful()) {
    //         Log::warning('API authentication failed');

    //         return back()->withErrors([
    //             'email' => 'Invalid email or password.',
    //         ])->onlyInput('email');
    //     }

    //     // Decode response
    //     $data = $response->json();

    //     if (!isset($data['user']) || !isset($data['user']['email'])) 
    //     {
    //         Log::error('Invalid API response structure', $data);

    //         return back()->withErrors([
    //             'email' => 'Invalid login response from server.',
    //         ]);
    //     }

    //     $apiUser = $data['user'];
    //     Log::info('API user extracted', $apiUser);

    //     // Find local user (PostgreSQL case-sensitive)
    //     $user = User::where('Email', $apiUser['email'])->first();

    //     if (!$user) {
    //         Log::error('Local user not found', [
    //             'email' => $apiUser['email'],
    //         ]);

    //         return back()->withErrors([
    //             'email' => 'User not found in local system.',
    //         ]);
    //     }

    //     // Login user using WEB guard
    //     Auth::guard('web')->login($user, $request->filled('remember'));
    //     $request->session()->regenerate();

    //     // dd(auth()->check());
    //     // Log::info('🎉 User logged in successfully', [
    //     //     'auth_id'   => auth()->id(),
    //     //     'auth_name' => auth()->user()->name,
    //     // ]);

    //     // //Store API tokens (optional)
    //     // session([
    //     //     'api_token'     => $data['token'] ?? null,
    //     //     'refresh_token' => $data['refreshToken'] ?? null,
    //     // ]);

    //     // STORE JWT TOKEN (THIS IS THE KEY)
    //     session([
    //         'jwt_token' => $data['token'],
    //         'refresh_token' => $data['refreshToken'] ?? null,
    //     ]);

    //     //Redirect properly (middleware will run)
    //     return redirect()
    //         ->route('admin.dashboard')
    //         ->with('success', 'Welcome back, ' . auth()->user()->name . '!');
    //     // }
    //     // } catch (\Throwable $e) {

    //     //     Log::error('🔥 Login exception', [
    //     //         'message' => $e->getMessage(),
    //     //         'file'    => $e->getFile(),
    //     //         'line'    => $e->getLine(),
    //     //     ]);

    //     //     return back()->withErrors([
    //     //         'email' => 'Something went wrong. Please try again.',
    //     //     ]);
    //     // }
    // }



    public function authenticate(Request $request)
    {
        Log::info('Login process started');

        // Validate request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        Log::info('Validation passed', ['email' => $request->email]);

        // Call external API
        Log::info('Calling login API');

        $response = Http::post(
            'https://pcsdecom.azurewebsites.net/api/Auth/login',
            [
                'email' => $request->email,
                'password' => $request->password,
            ]
        );

        Log::info('API response received', [
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        // Handle API failure
        if (!$response->successful()) {
            Log::warning('API authentication failed');

            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ])->onlyInput('email');
        }

        // Decode response
        $data = $response->json();

        if (!isset($data['user']) || !isset($data['user']['email'])) 
        {
            Log::error('Invalid API response structure', $data);

            return back()->withErrors([
                'email' => 'Invalid login response from server.',
            ]);
        }

        $apiUser = $data['user'];
        Log::info('API user extracted', $apiUser);

        // Find local user (PostgreSQL case-sensitive)
        $user = User::where('Email', $apiUser['email'])->first();

        if (!$user) {
            Log::error('Local user not found', [
                'email' => $apiUser['email'],
            ]);

            return back()->withErrors([
                'email' => 'User not found in local system.',
            ]);
        }

        // Login user using WEB guard
        Auth::guard('web')->login($user, $request->filled('remember'));
        $request->session()->regenerate();

        // STORE JWT TOKEN (THIS IS THE KEY)
        session([
            'jwt_token' => $data['token'],
            'refresh_token' => $data['refreshToken'] ?? null,
        ]);

        //Redirect properly (middleware will run)
        return redirect()
        ->route('admin.dashboard')
        ->with('success', 'Welcome back, ' . auth()->user()->name . '!');
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'You have been logged out successfully.');
    }


    public function verifyEmailForm(Request $request)
    {
        if (!session('jwt_token')) {
            return redirect()->route('login');
        }

        return view('auth.verify-email', [
            'email' => $request->email
        ]);
    }


}
