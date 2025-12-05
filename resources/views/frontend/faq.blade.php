@extends('layouts.user.app')
<<<<<<< Updated upstream

@section('title', 'FAQ | ' . $setting->app_name)

@section('content')
<section class="bg-gray-50 min-h-screen py-16 px-6 md:px-10">
    <!-- Header -->
    <div class="max-w-5xl mx-auto text-center mb-16">
        <h1 class="text-4xl font-bold mb-4 text-primary-600">Frequently Asked Questions</h1>
        <p class="text-gray-600">Find quick answers to common questions about our platform and services.</p>
    </div>

    <!-- FAQ Section -->
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Question 1 -->
        <details class="bg-white border border-gray-100 rounded-2xl shadow-md hover:shadow-lg transition overflow-hidden group">
            <summary class="cursor-pointer select-none flex justify-between items-center px-6 py-5 font-semibold text-gray-800 text-lg group-hover:text-primary-600">
                How do I create an account?
                <span class="text-primary-500 group-open:rotate-180 transition-transform">
                    <i class="fas fa-chevron-down"></i>
                </span>
            </summary>
            <div class="px-6 pb-5 text-gray-600 leading-relaxed border-t border-gray-100">
                You can easily create an account by clicking on the <span class="font-medium text-primary-600">“Sign Up”</span> button at the top right corner and filling in your details.
            </div>
        </details>

        <!-- Question 2 -->
        <details class="bg-white border border-gray-100 rounded-2xl shadow-md hover:shadow-lg transition overflow-hidden group">
            <summary class="cursor-pointer select-none flex justify-between items-center px-6 py-5 font-semibold text-gray-800 text-lg group-hover:text-primary-600">
                How can I reset my password?
                <span class="text-primary-500 group-open:rotate-180 transition-transform">
                    <i class="fas fa-chevron-down"></i>
                </span>
            </summary>
            <div class="px-6 pb-5 text-gray-600 leading-relaxed border-t border-gray-100">
                Go to the login page, click <span class="font-medium text-primary-600">“Forgot Password?”</span>, and follow the instructions sent to your email to securely reset your password.
            </div>
        </details>

        <!-- Question 3 -->
        <details class="bg-white border border-gray-100 rounded-2xl shadow-md hover:shadow-lg transition overflow-hidden group">
            <summary class="cursor-pointer select-none flex justify-between items-center px-6 py-5 font-semibold text-gray-800 text-lg group-hover:text-primary-600">
                Is my personal data safe?
                <span class="text-primary-500 group-open:rotate-180 transition-transform">
                    <i class="fas fa-chevron-down"></i>
                </span>
            </summary>
            <div class="px-6 pb-5 text-gray-600 leading-relaxed border-t border-gray-100">
                Absolutely. We take data security seriously, using encryption and following best practices to keep your information safe and private.
            </div>
        </details>
=======
@section('title', 'FAQ | YourAppName')

@section('content')
<section class="bg-gradient-to-br from-gray-900 to-gray-800 text-white min-h-screen py-16 px-6 md:px-20">
    <div class="max-w-5xl mx-auto text-center mb-12">
        <h1 class="text-4xl font-bold mb-4 text-primary-400">Frequently Asked Questions</h1>
        <p class="text-gray-400">Find quick answers to the most common questions about our platform.</p>
    </div>

    <div class="space-y-6 max-w-4xl mx-auto">
        <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 hover:border-primary-500 transition">
            <h3 class="text-lg font-semibold mb-2 text-primary-400">How do I create an account?</h3>
            <p class="text-gray-400 leading-relaxed">
                You can easily create an account by clicking on the “Sign Up” button at the top right corner and filling out your details.
            </p>
        </div>
        <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 hover:border-primary-500 transition">
            <h3 class="text-lg font-semibold mb-2 text-primary-400">How can I reset my password?</h3>
            <p class="text-gray-400 leading-relaxed">
                Go to the login page, click “Forgot Password?”, and follow the email instructions to reset your password securely.
            </p>
        </div>
        <div class="bg-gray-800 border border-gray-700 rounded-2xl p-6 hover:border-primary-500 transition">
            <h3 class="text-lg font-semibold mb-2 text-primary-400">Is my personal data safe?</h3>
            <p class="text-gray-400 leading-relaxed">
                Absolutely. We prioritize security with encryption and data protection best practices.
            </p>
        </div>
>>>>>>> Stashed changes
    </div>
</section>
@endsection
