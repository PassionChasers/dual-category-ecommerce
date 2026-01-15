@extends('layouts.user.app')

@section('title', 'Support | ' . ($settings->app_name ?? 'Ecommerce'))

@section('content')
<section class="bg-slate-50 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <!-- Hero Header -->
    <div class="max-w-4xl mx-auto text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-6 text-gray-900 tracking-tight">
            How can we help you today?
        </h1>
        <p class="text-lg text-gray-600 leading-relaxed">
            We’re here to help with anything related to your {{ $settings->app_name ?? 'Ecommerce' }} shopping experience.
            Choose a category below or contact us directly for assistance.
        </p>
    </div>

    <!-- Category Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 max-w-7xl mx-auto mb-20">
        <!-- Account & Orders -->
        <div class="group bg-white border border-gray-200 p-8 rounded-3xl shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 text-center">
            <div class="w-16 h-16 bg-primary-50 text-primary-600 flex items-center justify-center rounded-2xl mx-auto mb-6 group-hover:scale-110 transition-transform">
                <i class="fas fa-user-circle text-3xl"></i>
            </div>
            <h3 class="font-bold text-xl mb-3 text-gray-900">Account &amp; Orders</h3>
            <p class="text-gray-500 text-sm leading-relaxed mb-6">
                Help with login, signup, profile updates, and viewing or managing your orders.
            </p>
            <a href="#" class="inline-flex items-center  font-semibold hover:text-primary-700 transition-colors">
                Get Help <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </a>
        </div>

        <!-- Billing & Payments -->
        <div class="group bg-white border border-gray-200 p-8 rounded-3xl shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 text-center">
            <div class="w-16 h-16 bg-emerald-50 text-emerald-600 flex items-center justify-center rounded-2xl mx-auto mb-6 group-hover:scale-110 transition-transform">
                <i class="fas fa-credit-card text-3xl"></i>
            </div>
            <h3 class="font-bold text-xl mb-3 text-gray-900">Billing &amp; Payments</h3>
            <p class="text-gray-500 text-sm leading-relaxed mb-6">
                Questions about payments, refunds, invoices, or failed transactions.
            </p>
            <a href="#" class="inline-flex items-center text-primary-600 font-semibold hover:text-primary-700 transition-colors">
                Contact Billing <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </a>
        </div>

        <!-- Technical Support -->
        <div class="group bg-white border border-gray-200 p-8 rounded-3xl shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 text-center sm:col-span-2 lg:col-span-1">
            <div class="w-16 h-16 bg-amber-50 text-amber-600 flex items-center justify-center rounded-2xl mx-auto mb-6 group-hover:scale-110 transition-transform">
                <i class="fas fa-cogs text-3xl"></i>
            </div>
            <h3 class="font-bold text-xl mb-3 text-gray-900">Technical Support</h3>
            <p class="text-gray-500 text-sm leading-relaxed mb-6">
                Having trouble with the website, checkout, or any feature? We’ll help you fix it.
            </p>
            <a href="#" class="inline-flex items-center text-primary-600 font-semibold hover:text-primary-700 transition-colors">
                Troubleshoot <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </a>
        </div>
    </div>

    <!-- Quick Info Grid -->
    <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-4 mb-20">
        <div class="bg-white/50 backdrop-blur-sm border border-gray-100 rounded-2xl p-6 text-center shadow-sm">
            <h4 class="font-bold mb-2 text-primary-600 text-xs uppercase tracking-widest">Response Time</h4>
            <p class="text-gray-600 text-sm">We usually respond within 24 hours on business days.</p>
        </div>
        <div class="bg-white/50 backdrop-blur-sm border border-gray-100 rounded-2xl p-6 text-center shadow-sm">
            <h4 class="font-bold mb-2 text-primary-600 text-xs uppercase tracking-widest">Order Help</h4>
            <p class="text-gray-600 text-sm">Keep your order ID handy so we can assist you faster.</p>
        </div>
        <div class="bg-white/50 backdrop-blur-sm border border-gray-100 rounded-2xl p-6 text-center shadow-sm">
            <h4 class="font-bold mb-2 text-primary-600 text-xs uppercase tracking-widest">Secure Support</h4>
            <p class="text-gray-600 text-sm">Your data is protected when you contact our support team.</p>
        </div>
    </div>

    <!-- Contact CTA -->
    <div class="relative bg-white border border-gray-200 rounded-[2.5rem] p-8 md:p-12 max-w-4xl mx-auto text-center shadow-xl overflow-hidden">
        <!-- Decorative background element -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary-50 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-primary-50 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Still Need Help?</h2>
            <p class="text-gray-600 mb-10 max-w-lg mx-auto">
                Can’t find what you’re looking for in the support categories?
                Reach out to our team directly and we’ll be happy to assist.
            </p>
            <a href="{{ route('contact') }}"
               class="inline-flex items-center justify-center text-orange-700 hover:bg-orange-500 hover:text-white border font-bold py-4 px-12 rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                Contact Us
            </a>
        </div>
    </div>
</section>
@endsection