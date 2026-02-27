<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;

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
        // Log::info('Login process started');

        // Validate request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Log::info('Validation passed', ['email' => $request->email]);

        // Call external API
        // Log::info('Calling login API');

        $response = Http::post(
            'https://pcsdecom.azurewebsites.net/api/Auth/login',
            [
                'email' => $request->email,
                'password' => $request->password,
            ]
        );

        // Log::info('API response received', [
        //     'status' => $response->status(),
        //     'body' => $response->json(),
        // ]);

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
            // Log::error('Invalid API response structure', $data);

            return back()->withErrors([
                'email' => 'Invalid login response from server.',
            ]);
        }

        $apiUser = $data['user'];
        // Log::info('API user extracted', $apiUser);

        // Find local user (PostgreSQL case-sensitive)
        // $user = User::where('Email', $apiUser['email'])->first();

        $emailHash = strtolower(hash('sha256', $apiUser['email']));

        $user = User::where('EmailHash', $emailHash)->first();

        if (!$user) {
            // Log::error('Local user not found', [
            //     'email' => $apiUser['email'],
            // ]);

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

    /**
     * Forgot password form
     */
    public function forgotPasswordForm()
    {
        $setting = Setting::first();
        return view('auth.reset-password', compact('setting'));
    }

    /**
     * handle forgot password form data 
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'        => 'required|email',
        ]);

        try {
            // Make API request
            $response = Http::post('https://pcsdecom.azurewebsites.net/api/Auth/forgot-password', [
                'email' => $request->email,
            ]);

            // Check status code
            if ($response->successful()) {
                // Status 200 OK
                return view('auth.set-newPassword')->with('success', 'Password reset code sent to your email successfully.');
            } else {
                // Any other status code
                $message = $response->json('message') ?? 'Something went wrong. Please try again.';
                return back()->withErrors(['email' => $message])->withInput();
            }
        } catch (\Exception $e) {
            // Handle network or other errors
            return back()->withErrors(['email' => 'Unable to send reset email. Please try again later.'])->withInput();
        }
    }

    
    // public function setNewPassword(Request $request)
    // {
    //     $request->validate([
    //         'resetCode'        => 'required|string|min:4',
    //         'newPassword'        => 'required|string|min:8',
    //     ]);

    //     try {
    //         // Make API request
    //         $response = Http::timeout(15)->post('https://pcsdecom.azurewebsites.net/api/Auth/reset-password', [
    //             'code' => $request->resetCode,
    //             'newPassword' => $request->newPassword,
    //         ]);

    //         // Check status code
    //         if ($response->successful()) {
    //             // Status 200 OK
    //             return view('auth.login')->with('success', 'Password reset successfully.');
    //         } else {
    //             // Any other status code
    //             $message = $response->json('message') ?? 'Something went wrong. Please try again.';
    //             return back()->withErrors(['resetCode' => $message])->withInput();
    //         }
    //     } catch (\Exception $e) {
    //         // Handle network or other errors
    //         return back()->withErrors(['resetCode' => 'Unable to send reset password. Please try again later.'])->withInput();
    //     }
    // }


    /**
     * handle  setNewPassword form data 
     */
    public function setNewPassword(Request $request)
    {
        $request->validate([
            'resetCode'   => 'required|string|min:4',
            'newPassword' => 'required|string|min:8|confirmed',
        ]);

        try {
            $response = Http::timeout(15)->post(
                'https://pcsdecom.azurewebsites.net/api/Auth/reset-password',
                [
                    'code'        => $request->resetCode,
                    'newPassword' => $request->newPassword,
                ]
            );

            if ($response->successful()) {
                return redirect()
                    ->route('login')
                    ->with('success', 'Password reset successfully.');
            }

            // Handle API validation errors
            $message =
                $response->json('message') ??
                $response->json('title') ??
                'Something went wrong. Please try again.';

            return back()
                ->withErrors(['resetCode' => $message])
                ->withInput();

        } catch (\Exception $e) {
            Log::error('Reset password failed: ' . $e->getMessage());

            return back()
                ->withErrors(['resetCode' => 'Unable to reset password. Please try again later.'])
                ->withInput();
        }
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


    /**
     * handle change password form data 
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'oldPassword'        => 'required|string',
            'newPassword'        => 'required|string|min:6',
            'confirmNewPassword' => 'required|same:newPassword',
        ]);

        $token = session('jwt_token'); // or auth token source

        if (!$token) {
            return back()->with('error', 'Session expired. Please login again.');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ])->post(
                'https://pcsdecom.azurewebsites.net/api/Auth/change-password',
                [
                    'currentPassword' => $request->oldPassword,
                    'newPassword' => $request->newPassword,
                ]
            );

            if ($response->failed()) {
                \Log::error('Password change failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return back()->with(
                    'error',
                    $response->json('message') ?? 'Password update failed'
                );
            }

            return back()->with('success', 'Password updated successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Server error. Please try again later.');
        }
    }


}
