@extends('layouts.user.app')

<<<<<<< Updated upstream
@section('title', 'Privacy Policy | ' . $setting->app_name)

@section('content')
<section class="bg-gray-50 min-h-screen py-16 px-6 md:px-20">
    <!-- Header -->
    <div class="max-w-5xl mx-auto text-center mb-6">
        <h1 class="text-4xl font-bold mb-4 text-primary-600">Privacy Policy</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">
            Your privacy matters to us. This policy explains how we collect, use, and protect your personal information while using our services.
        </p>
    </div>

    <!-- Policy Content -->
    <div class="max-w-4xl mx-auto bg-white p-10 rounded-2xl shadow-md border border-gray-100 space-y-10 text-gray-700 leading-relaxed">
        <!-- Section 1 -->
        <div>
            <h2 class="text-xl font-semibold mb-3">1. Information We Collect</h2>
            <p>
                We may collect personal information such as your name, email address, phone number, and usage activity to enhance your experience with our platform.
                Additionally, we gather non-personal data like browser type and device information to improve our website performance.
            </p>
        </div>

        <!-- Section 2 -->
        <div>
            <h2 class="text-xl font-semibold mb-3">2. How We Use Your Data</h2>
            <p>
                Your data is used to personalize your experience, provide customer support, process transactions, and send important updates. 
                We never sell or share your personal information with third parties without your consent.
            </p>
        </div>

        <!-- Section 3 -->
        <div>
            <h2 class="text-xl font-semibold mb-3">3. Data Protection</h2>
            <p>
                We employ advanced security measures — including encryption, firewalls, and secure server protocols — to protect your information from unauthorized access, alteration, or disclosure.
            </p>
        </div>

        <!-- Section 4 -->
        <div>
            <h2 class="text-xl font-semibold mb-3">4. Cookies</h2>
            <p>
                Our site uses cookies to improve user experience and analyze website traffic. You can control cookie preferences through your browser settings.
            </p>
        </div>

        <!-- Section 5 -->
        <div>
            <h2 class="text-xl font-semibold mb-3">5. Updates to This Policy</h2>
            <p>
                We may update our Privacy Policy from time to time to reflect changes in our practices. Any modifications will be posted on this page with the revised date.
            </p>
        </div>

        <!-- Section 6 -->
        <div>
            <h2 class="text-xl font-semibold mb-3">6. Contact Us</h2>
            <p>
                If you have any questions or concerns about our Privacy Policy, please contact us at:
            </p>
            <div class="mt-3 space-y-1">
                <p><i class="fas fa-envelope text-primary-500 mr-2"></i> support@yourapp.com</p>
                <p><i class="fas fa-phone text-primary-500 mr-2"></i> +977-1234567890</p>
            </div>
=======
@section('title', 'Privacy Policy | YourAppName')

@section('content')
<section class="bg-gradient-to-br from-gray-900 to-gray-800 text-white min-h-screen py-16 px-6 md:px-20">
    <div class="max-w-5xl mx-auto text-center mb-12">
        <h1 class="text-4xl font-bold mb-4 text-primary-400">Privacy Policy</h1>
        <p class="text-gray-400">Your privacy matters. This policy outlines how we collect, use, and protect your information.</p>
    </div>

    <div class="max-w-4xl mx-auto space-y-8 text-gray-400 leading-relaxed">
        <div>
            <h2 class="text-xl font-semibold text-primary-400 mb-2">1. Information We Collect</h2>
            <p>We collect data such as name, email address, and usage activity to improve our services.</p>
        </div>
        <div>
            <h2 class="text-xl font-semibold text-primary-400 mb-2">2. How We Use Your Data</h2>
            <p>Your data helps us personalize your experience, process transactions, and provide customer support.</p>
        </div>
        <div>
            <h2 class="text-xl font-semibold text-primary-400 mb-2">3. Data Protection</h2>
            <p>We implement strict security measures to prevent unauthorized access or disclosure.</p>
        </div>
        <div>
            <h2 class="text-xl font-semibold text-primary-400 mb-2">4. Updates to This Policy</h2>
            <p>We may update our policy occasionally. Please review this page periodically for changes.</p>
>>>>>>> Stashed changes
        </div>
    </div>
</section>
@endsection
