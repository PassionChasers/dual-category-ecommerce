<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            // General settings
            'app_name' => 'My Application',
            'app_logo' => 'uploads/logo.png',
            'favicon' => 'uploads/favicon.ico',
            'meta_title' => 'My Application',
            'meta_description' => 'This is a demo application description',

            // Contact details
            'contact_email' => 'info@example.com',
            'contact_phone' => '+977-9800000000',
            'contact_address' => 'Biratnagar, Nepal',

            // SMS API
            'sms_api_url' => 'https://sms.example.com/api',
            'sms_api_key' => 'your-sms-api-key',
            'sms_sender_id' => 'MYAPP',

            // SMTP Mail Settings
            'mail_mailer' => 'smtp',
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => 587,
            'mail_username' => 'your-email@gmail.com',
            'mail_password' => 'your-email-password',
            'mail_encryption' => 'tls',
            'mail_from_address' => 'no-reply@example.com',
            'mail_from_name' => 'My Application',

            // Social Media
            'facebook_url' => 'https://facebook.com/myapp',
            'twitter_url' => 'https://twitter.com/myapp',
            'linkedin_url' => 'https://linkedin.com/company/myapp',
            'instagram_url' => 'https://instagram.com/myapp',

            // Other
            'maintenance_mode' => false,
            'custom_script_head' => null,
            'custom_script_body' => null,

            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
