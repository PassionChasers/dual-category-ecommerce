@extends('layouts.user.app')

@section('title', 'FAQ | ' . ($settings->app_name ?? 'Ecommerce'))

@section('content')
<section class="bg-gradient-to-br from-gray-900 to-gray-800 text-white min-h-screen py-16 px-6 md:px-20">
    <!-- Header -->
    <div class="max-w-5xl mx-auto text-center mb-12">
        <h1 class="text-4xl font-bold mb-4 text-primary-400">Frequently Asked Questions</h1>
        <p class="text-gray-400">
            Find quick answers to the most common questions about {{ $settings->app_name ?? 'our online store' }}.
        </p>
    </div>

    <!-- FAQ Section -->
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Question 1 -->
        <details
            class="bg-gray-800 border border-gray-700 rounded-2xl shadow-md hover:shadow-xl hover:border-primary-500 transition overflow-hidden group">
            <summary
                class="cursor-pointer select-none flex justify-between items-center px-6 py-5 font-semibold text-lg text-gray-100 group-hover:text-primary-400">
                How do I create an account?
                <span class="text-primary-400 group-open:rotate-180 transition-transform">
                    <i class="fas fa-chevron-down"></i>
                </span>
            </summary>
            <div class="px-6 pb-5 text-gray-300 leading-relaxed border-t border-gray-700">
                You can easily create an account by clicking on the
                <span class="font-medium text-primary-400">“Sign Up”</span> button at the top right corner and
                filling in your details. With an account, you can track orders, save addresses, and view your history.
            </div>
        </details>

        <!-- Question 2 -->
        <details
            class="bg-gray-800 border border-gray-700 rounded-2xl shadow-md hover:shadow-xl hover:border-primary-500 transition overflow-hidden group">
            <summary
                class="cursor-pointer select-none flex justify-between items-center px-6 py-5 font-semibold text-lg text-gray-100 group-hover:text-primary-400">
                How can I reset my password?
                <span class="text-primary-400 group-open:rotate-180 transition-transform">
                    <i class="fas fa-chevron-down"></i>
                </span>
            </summary>
            <div class="px-6 pb-5 text-gray-300 leading-relaxed border-t border-gray-700">
                Go to the login page and click
                <span class="font-medium text-primary-400">“Forgot Password?”</span>.
                Enter your registered email address and follow the instructions sent to your inbox to securely reset
                your password.
            </div>
        </details>

        <!-- Question 3 -->
        <details
            class="bg-gray-800 border border-gray-700 rounded-2xl shadow-md hover:shadow-xl hover:border-primary-500 transition overflow-hidden group">
            <summary
                class="cursor-pointer select-none flex justify-between items-center px-6 py-5 font-semibold text-lg text-gray-100 group-hover:text-primary-400">
                Is my personal and payment data safe?
                <span class="text-primary-400 group-open:rotate-180 transition-transform">
                    <i class="fas fa-chevron-down"></i>
                </span>
            </summary>
            <div class="px-6 pb-5 text-gray-300 leading-relaxed border-t border-gray-700">
                Yes. We take security very seriously. We use industry-standard encryption and follow best practices to
                protect your personal information and payment details. We never store full card information on our servers.
            </div>
        </details>

        <!-- Question 4 (optional ecommerce-specific) -->
        <details
            class="bg-gray-800 border border-gray-700 rounded-2xl shadow-md hover:shadow-xl hover:border-primary-500 transition overflow-hidden group">
            <summary
                class="cursor-pointer select-none flex justify-between items-center px-6 py-5 font-semibold text-lg text-gray-100 group-hover:text-primary-400">
                How can I track my order?
                <span class="text-primary-400 group-open:rotate-180 transition-transform">
                    <i class="fas fa-chevron-down"></i>
                </span>
            </summary>
            <div class="px-6 pb-5 text-gray-300 leading-relaxed border-t border-gray-700">
                Once your order is confirmed, you can track it from the
                <span class="font-medium text-primary-400">“My Orders”</span> section in your account.
                We’ll also send updates via email or SMS as your order is processed and shipped.
            </div>
        </details>
    </div>
</section>
@endsection
