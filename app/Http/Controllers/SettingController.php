<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; // Added for file copy
use Illuminate\Support\Facades\Http;


class SettingController extends Controller
{
    /**
     * Display the settings form.
     */
    public function index()
    {
        $setting = Setting::first();
        return view('admin.settings.index', compact('setting'));
    }

    /**
     * Store settings if not exists (only 1 record allowed).
     */
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'AppName' => 'nullable|string|max:255',
    //         'AppLogo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
    //         'Favicon' => 'nullable|image|mimes:png,jpg,ico|max:1024',
    //         'MetaTitle' => 'nullable|string|max:255',
    //         'MetaDescription' => 'nullable|string',

    //         'ContactEmail' => 'nullable|email|max:255',
    //         'ContactPhone' => 'nullable|string|max:20',
    //         'ContactAddress' => 'nullable|string|max:255',

    //         'SmsApiUrl' => 'nullable|string|max:255',
    //         'SmsApiKey' => 'nullable|string|max:255',
    //         'SmsSenderId' => 'nullable|string|max:100',

    //         'MailMailer' => 'nullable|string|max:50',
    //         'MailHost' => 'nullable|string|max:255',
    //         'MailPort' => 'nullable|integer',
    //         'MailUsername' => 'nullable|string|max:255',
    //         'MailPassword' => 'nullable|string|max:255',
    //         'MailEncryption' => 'nullable|string|max:50',
    //         'MailFromAddress' => 'nullable|email|max:255',
    //         'MailFromName' => 'nullable|string|max:255',

    //         'FacebookUrl' => 'nullable|url',
    //         'TwitterUrl' => 'nullable|url',
    //         'LinkedinUrl' => 'nullable|url',
    //         'InstagramUrl' => 'nullable|url',

    //         'MaintenanceMode' => 'nullable|boolean',
    //         ,



    //     ]);

    //     // Handle logo uploads
    //     if ($request->hasFile('applogo')) {
    //         $path = $request->file('app_logo')->store('settings', 'public');
    //         $validated['app_logo'] = $path;

    //         // Option 3: Copy to public/storage for servers without symlink
    //         $source = storage_path('app/public/' . $path);
    //         $destination = public_path('storage/' . $path);
    //         if (!File::exists(dirname($destination))) {
    //             File::makeDirectory(dirname($destination), 0755, true);
    //         }
    //         File::copy($source, $destination);
    //     }

    //     if ($request->hasFile('favicon')) {
    //         $path = $request->file('favicon')->store('settings', 'public');
    //         $validated['favicon'] = $path;

    //         // Copy to public/storage
    //         $source = storage_path('app/public/' . $path);
    //         $destination = public_path('storage/' . $path);
    //         if (!File::exists(dirname($destination))) {
    //             File::makeDirectory(dirname($destination), 0755, true);
    //         }
    //         File::copy($source, $destination);
    //     }

    //     Setting::create($validated);

    //     return redirect()->route('settings.general')->with('success', 'Settings saved successfully.');
    // }

    /**
     * Update the settings (since only one row allowed).
     */
    // public function update(Request $request, Setting $setting)
    // {
    //     $validated = $request->validate([
    //          'AppName' => 'nullable|string|max:255',
    //         'AppLogo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
    //         'Favicon' => 'nullable|image|mimes:png,jpg,ico|max:1024',
    //         'MetaTitle' => 'nullable|string|max:255',
    //         'MetaDescription' => 'nullable|string',

    //         'ContactEmail' => 'nullable|email|max:255',
    //         'ContactPhone' => 'nullable|string|max:20',
    //         'ContactAddress' => 'nullable|string|max:255',

    //         'SmsApiUrl' => 'nullable|string|max:255',
    //         'SmsApiKey' => 'nullable|string|max:255',
    //         'SmsSenderId' => 'nullable|string|max:100',

    //         'MailMailer' => 'nullable|string|max:50',
    //         'MailHost' => 'nullable|string|max:255',
    //         'MailPort' => 'nullable|integer',
    //         'MailUsername' => 'nullable|string|max:255',
    //         'MailPassword' => 'nullable|string|max:255',
    //         'MailEncryption' => 'nullable|string|max:50',
    //         'MailFromAddress' => 'nullable|email|max:255',
    //         'MailFromName' => 'nullable|string|max:255',

    //         'FacebookUrl' => 'nullable|url',
    //         'TwitterUrl' => 'nullable|url',
    //         'LinkedinUrl' => 'nullable|url',
    //         'InstagramUrl' => 'nullable|url',

    //         'MaintenanceMode' => 'nullable|boolean',
    //     ]);

    //     if ($request->hasFile('app_logo')) {
    //         $path = $request->file('app_logo')->store('settings', 'public');
    //         $validated['app_logo'] = $path;

    //         // Copy to public/storage
    //         $source = storage_path('app/public/' . $path);
    //         $destination = public_path('storage/' . $path);
    //         if (!File::exists(dirname($destination))) {
    //             File::makeDirectory(dirname($destination), 0755, true);
    //         }
    //         File::copy($source, $destination);
    //     }

    //     if ($request->hasFile('favicon')) {
    //         $path = $request->file('favicon')->store('settings', 'public');
    //         $validated['favicon'] = $path;

    //         // Copy to public/storage
    //         $source = storage_path('app/public/' . $path);
    //         $destination = public_path('storage/' . $path);
    //         if (!File::exists(dirname($destination))) {
    //             File::makeDirectory(dirname($destination), 0755, true);
    //         }
    //         File::copy($source, $destination);
    //     }

    //     $setting->update($validated);

    //     return redirect()->route('settings.general')->with('success', 'Settings updated successfully.');
    // }

    public function update(Request $request)
    {
        // 1️⃣ Validate using snake_case (matches API)
        $validated = $request->validate([
            'app_name' => 'nullable|string|max:255',
            'app_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'favicon' => 'nullable|image|mimes:png,jpg,jpeg,ico|max:1024',

            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',

            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string|max:255',

            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',

            'maintenance_mode' => 'nullable',
        ]);

        // 2️⃣ API endpoint
        $apiUrl = 'https://pcsdecom.azurewebsites.net/api/admin/settings';

        $http = Http::asMultipart();

        // 3️⃣ Attach files (snake_case)
        if ($request->hasFile('app_logo')) {
            $http->attach(
                'app_logo',
                file_get_contents($request->file('app_logo')->getRealPath()),
                $request->file('app_logo')->getClientOriginalName()
            );
        }

        if ($request->hasFile('favicon')) {
            $http->attach(
                'favicon',
                file_get_contents($request->file('favicon')->getRealPath()),
                $request->file('favicon')->getClientOriginalName()
            );
        }

        // 4️⃣ Normalize checkbox
        $validated['maintenance_mode'] = $request->boolean('maintenance_mode');

        // 5️⃣ Send request
        $response = $http->put(
            $apiUrl,
            collect($validated)->except(['app_logo', 'favicon'])->toArray()
        );

        // 6️⃣ Debug if API fails (IMPORTANT)
        if (!$response->successful()) {
            // TEMPORARY — remove after fixing
            dd($response->status(), $response->body());
        }

        return back()->with('success', 'Settings updated successfully.');
    }

}
