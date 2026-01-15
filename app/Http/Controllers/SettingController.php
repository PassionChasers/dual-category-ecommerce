<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    /**
     * Show settings page
     */
    public function index()
    {
        $setting = Setting::first();
        return view('admin.settings.index', compact('setting'));
    }

    /**
     * Update settings (single row only)
     */
    public function update(Request $request, Setting $setting)
    {
        $request->validate([
            'app_name' => 'nullable|string|max:255',
            'app_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'favicon'  => 'nullable|image|mimes:png,jpg,ico|max:1024',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',

            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string|max:255',

            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',

            'maintenance_mode' => 'nullable|boolean',
        ]);

        /** =========================
         *  MAP INPUT → DB COLUMNS
         *  ========================= */
        $data = [
            'AppName'          => $request->app_name,
            'MetaTitle'        => $request->meta_title,
            'MetaDescription' => $request->meta_description,
            'ContactEmail'     => $request->contact_email,
            'ContactPhone'     => $request->contact_phone,
            'ContactAddress'   => $request->contact_address,
            'FacebookUrl'      => $request->facebook_url,
            'TwitterUrl'       => $request->twitter_url,
            'LinkedInUrl'      => $request->linkedin_url,
            'InstagramUrl'     => $request->instagram_url,
            'MaintenanceMode'  => $request->boolean('maintenance_mode'),
        ];

        /** =========================
         *  FILE UPLOADS
         *  ========================= */
        if ($request->hasFile('app_logo')) {
            $path = $request->file('app_logo')->store('settings', 'public');
            $data['AppLogo'] = 'storage/' . $path;
        }

        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('settings', 'public');
            $data['Favicon'] = 'storage/' . $path;
        }

        /** =========================
         *  UPDATE
         *  ========================= */
        $setting->update(array_filter($data));

        return redirect()
            ->route('settings.general')
            ->with('success', 'Settings updated successfully.');
    }
}
