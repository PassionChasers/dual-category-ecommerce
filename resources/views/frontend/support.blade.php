@extends('layouts.user.app')

@section('title', 'Support | ' . ($settings->app_name ?? 'Ecommerce'))

@section('content')
<section class="bg-gradient-to-br from-gray-900 to-gray-800 text-white min-h-screen py-16 px-6 md:px-20">
    <div class="max-w-4xl mx-auto text-center mb-12">
        <h1 class="text-4xl font-bold mb-4 text-primary-400">Support Center</h1>
        <p class="text-gray-400">
            We’re here to help with anything related to your {{ $settings->app_name ?? 'Ecommerce' }} shopping experience.
            Choose a category below or contact us directly for assistance.
        </p>
    </div>

    <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto mb-16">
        <!-- Account & Orders -->
        <div class="bg-gray-800 border border-gray-700 p-8 rounded-2xl shadow-md hover:shadow-xl hover:border-primary-500 transition text-center">
            <div class="w-14 h-14 bg-primary-100 text-primary-600 flex items-center justify-center rounded-full mx-auto mb-4">
                <i class="fas fa-user-circle text-2xl"></i>
            </div>
            <h3 class="font-semibold text-xl mb-2">Account &amp; Orders</h3>
            <p class="text-gray-400 text-sm mb-3">
                Help with login, signup, profile updates, and viewing or managing your orders.
            </p>
            <a href="#" class="text-primary-400 font-medium hover:underline">Get Help</a>
        </div>

        <!-- Billing & Payments -->
        <div class="bg-gray-800 border border-gray-700 p-8 rounded-2xl shadow-md hover:shadow-xl hover:border-primary-500 transition text-center">
            <div class="w-14 h-14 bg-green-100 text-green-600 flex items-center justify-center rounded-full mx-auto mb-4">
                <i class="fas fa-credit-card text-2xl"></i>
            </div>
            <h3 class="font-semibold text-xl mb-2">Billing &amp; Payments</h3>
            <p class="text-gray-400 text-sm mb-3">
                Questions about payments, refunds, invoices, or failed transactions.
            </p>
            <a href="#" class="text-primary-400 font-medium hover:underline">Contact Billing</a>
        </div>

        <!-- Technical Support -->
        <div class="bg-gray-800 border border-gray-700 p-8 rounded-2xl shadow-md hover:shadow-xl hover:border-primary-500 transition text-center">
            <div class="w-14 h-14 bg-yellow-100 text-yellow-600 flex items-center justify-center rounded-full mx-auto mb-4">
                <i class="fas fa-cogs text-2xl"></i>
            </div>
            <h3 class="font-semibold text-xl mb-2">Technical Support</h3>
            <p class="text-gray-400 text-sm mb-3">
                Having trouble with the website, checkout, or any feature? We’ll help you fix it.
            </p>
            <a href="#" class="text-primary-400 font-medium hover:underline">Troubleshoot</a>
        </div>
    </div>

    <!-- Extra quick info (optional but nice UX) -->
    <div class="max-w-4xl mx-auto grid md:grid-cols-3 gap-6 text-center mb-16">
        <div class="bg-gray-800 border border-gray-700 rounded-2xl p-5">
            <h4 class="font-semibold mb-1 text-primary-300 text-sm uppercase tracking-wide">Response Time</h4>
            <p class="text-gray-300 text-sm">We usually respond within 24 hours on business days.</p>
        </div>
        <div class="bg-gray-800 border border-gray-700 rounded-2xl p-5">
            <h4 class="font-semibold mb-1 text-primary-300 text-sm uppercase tracking-wide">Order Help</h4>
            <p class="text-gray-300 text-sm">Keep your order ID handy so we can assist you faster.</p>
        </div>
        <div class="bg-gray-800 border border-gray-700 rounded-2xl p-5">
            <h4 class="font-semibold mb-1 text-primary-300 text-sm uppercase tracking-wide">Secure Support</h4>
            <p class="text-gray-300 text-sm">Your data is protected when you contact our support team.</p>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="text-center max-w-xl mx-auto">
        <h2 class="text-3xl font-bold text-white mb-4">Still Need Help?</h2>
        <p class="text-gray-400 mb-8">
            Can’t find what you’re looking for in the support categories?
            Reach out to our team directly and we’ll be happy to assist.
        </p>
        <a href="{{ route('contact') }}"
           class="inline-block bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold py-3 px-10 rounded-full shadow-lg hover:opacity-90 transition duration-300">
            Contact Us
        </a>
    </div>
</section>
@endsection
