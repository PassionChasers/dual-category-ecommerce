@extends('layouts.user.app')

@section('title', 'FAQ | ' . ($settings->app_name ?? 'Ecommerce'))

@section('content')
<section class="bg-gray-50 min-h-screen py-16 px-6 md:px-20">
    <!-- Header -->
    <div class="max-w-5xl mx-auto text-center mb-12">
        <h1 class="text-4xl font-bold mb-4 text-primary-600">Frequently Asked Questions</h1>
        <p class="text-gray-600">
            Find quick answers to the most common questions about {{ $settings->app_name ?? 'our online store' }}.
        </p>
    </div>

    <!-- FAQ Section -->
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Question 1 -->
        <details
            class="bg-white border border-gray-100 rounded-2xl shadow-md hover:shadow-xl hover:border-primary-400 transition overflow-hidden group">
            <summary
                class="cursor-pointer select-none flex justify-between items-center px-6 py-5 font-semibold text-lg text-gray-800 group-hover:text-primary-600">
                How do I create an account?
                <span class="text-primary-500 group-open:rotate-180 transition-transform">
                    <i class="fas fa-chevron-down"></i>
                </span>
            </summary>
            <div class="px-6 pb-5 text-gray-600 leading-relaxed border-t border-gray-100">
                You can easily create an account by clicking on the
                <span class="font-medium text-primary-600">“Sign Up”</span> button at the top right corner and
                filling in your details. With an account, you can track orders, save addresses, and view your history.
            </div>
        </details>

        <!-- Question 2 -->
        <details
            class="bg-white border border-gray-100 rounded-2xl shadow-md hover:shadow-xl hover:border-primary-400 transition overflow-hidden group">
            <summary
                class="cursor-pointer select-none flex justify-between items-center px-6 py-5 font-semibold text-lg text-gray-800 group-hover:text-primary-600">
                How can I reset my password?
                <span class="text-primary-500 group-open:rotate-180 transition-transform">
                    <i class="fas fa-chevron-down"></i>
                </span>
            </summary>
            <div class="px-6 pb-5 text-gray-600 leading-relaxed border-t border-gray-100">
                Go to the login page and click
                <span class="font-medium text-primary-600">“Forgot Password?”</span>.
                Enter your registered email address and follow the instructions sent to your inbox to securely reset
                your password.
            </div>
        </details>

        <!-- Question 3 -->
        <details
            class="bg-white border border-gray-100 rounded-2xl shadow-md hover:shadow-xl hover:border-primary-400 transition overflow-hidden group">
            <summary
                class="cursor-pointer select-none flex justify-between items-center px-6 py-5 font-semibold text-lg text-gray-800 group-hover:text-primary-600">
                Is my personal and payment data safe?
                <span class="text-primary-500 group-open:rotate-180 transition-transform">
                    <i class="fas fa-chevron-down"></i>
                </span>
            </summary>
            <div class="px-6 pb-5 text-gray-600 leading-relaxed border-t border-gray-100">
                Yes. We take security very seriously. We use industry-standard encryption and follow best practices to
                protect your personal information and payment details. We never store full card information on our servers.
            </div>
        </details>

        <!-- Question 4 -->
        <details
            class="bg-white border border-gray-100 rounded-2xl shadow-md hover:shadow-xl hover:border-primary-400 transition overflow-hidden group">
            <summary
                class="cursor-pointer select-none flex justify-between items-center px-6 py-5 font-semibold text-lg text-gray-800 group-hover:text-primary-600">
                How can I track my order?
                <span class="text-primary-500 group-open:rotate-180 transition-transform">
                    <i class="fas fa-chevron-down"></i>
                </span>
            </summary>
            <div class="px-6 pb-5 text-gray-600 leading-relaxed border-t border-gray-100">
                Once your order is confirmed, you can track it from the
                <span class="font-medium text-primary-600">“My Orders”</span> section in your account.
                We’ll also send updates via email or SMS as your order is processed and shipped.
            </div>
        </details>
    </div>
</section>
@endsection
