@extends('layouts.user.app')

@section('title', 'About Us | ' . ($settings->app_name ?? 'Ecommerce'))

@section('content')
    <!-- Hero / Intro Section -->
    <section class="relative bg-gradient-to-br from-gray-900 to-gray-800 text-white py-20 text-center overflow-hidden">
        <!-- Floating shapes -->
        <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
            <div class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full animate-float"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full animate-float"
                 style="animation-delay: 2s;"></div>
        </div>

        <div class="max-w-3xl mx-auto px-6 relative z-10">
            <h1 class="text-5xl font-bold mb-4">
                About
                <span class="text-primary-200">{{ $settings->app_name ?? 'Ecommerce' }}</span>
            </h1>
            <p class="text-lg text-primary-100 leading-relaxed">
                We’re building a modern online shopping experience — fast, secure, and customer-first — so you can
                discover and buy the products you love with complete confidence.
            </p>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6 flex justify-between items-center gap-12 md:flex-row flex-col md:space-y-0 space-y-12">
            <div>
                <h2 class="text-3xl font-bold text-primary-600 mb-4">Our Mission</h2>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Our mission is to make online shopping effortless and enjoyable — offering a curated catalog,
                    transparent pricing, and a smooth checkout so you can spend less time searching and more time enjoying
                    what you buy.
                </p>

                <h2 class="text-3xl font-bold text-primary-600 mb-4">Our Vision</h2>
                <p class="text-gray-600 leading-relaxed">
                    We envision a trusted ecommerce platform where customers feel confident with every order, brands can
                    grow, and technology quietly powers a seamless experience from browse to delivery.
                </p>
            </div>
            <div>
                <img
                    src="{{ !empty($settings->favicon) ? asset('storage/' . $settings->favicon) : asset('images/about-placeholder.jpg') }}"
                    alt="About {{ $settings->app_name ?? 'Ecommerce' }}"
                    class="rounded-2xl shadow-xl transform hover:scale-105 transition duration-500"
                >
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6 text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Our Core Values</h2>
            <p class="text-gray-600">
                These values guide how we build our platform, serve our customers, and grow our community.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto px-6">
            <div class="bg-white p-8 rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition">
                <div
                    class="w-14 h-14 bg-primary-100 text-primary-600 flex items-center justify-center rounded-lg mb-4 mx-auto">
                    <i class="fas fa-lightbulb text-2xl"></i>
                </div>
                <h4 class="text-xl font-semibold mb-2">Customer Focus</h4>
                <p class="text-gray-600">
                    Every feature, page, and policy is designed with you in mind — to make your shopping journey simple
                    and delightful.
                </p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition">
                <div
                    class="w-14 h-14 bg-green-100 text-green-600 flex items-center justify-center rounded-lg mb-4 mx-auto">
                    <i class="fas fa-shield-alt text-2xl"></i>
                </div>
                <h4 class="text-xl font-semibold mb-2">Trust & Integrity</h4>
                <p class="text-gray-600">
                    We value transparency, secure transactions, and honest communication — so you always know what to
                    expect.
                </p>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition">
                <div
                    class="w-14 h-14 bg-yellow-100 text-yellow-600 flex items-center justify-center rounded-lg mb-4 mx-auto">
                    <i class="fas fa-truck-moving text-2xl"></i>
                </div>
                <h4 class="text-xl font-semibold mb-2">Reliability</h4>
                <p class="text-gray-600">
                    From product availability to delivery, we work hard to ensure a consistent, dependable experience
                    every time you shop.
                </p>
            </div>
        </div>
    </section>

    <!-- Join / CTA Section -->
    <section class="py-20 bg-gradient-to-r from-primary-500 to-primary-600 text-white text-center">
        <div class="max-w-4xl mx-auto px-6">
            <h2 class="text-4xl font-bold mb-4">Grow With {{ $settings->app_name ?? 'Us' }}</h2>
            <p class="text-primary-100 mb-8">
                Whether you’re a shopper looking for great deals or a brand wanting to reach more customers,
                {{ $settings->app_name ?? 'our platform' }} is here to help you every step of the way.
            </p>
            <a href="{{ route('contact') }}"
               class="inline-block bg-white text-primary-600 font-semibold py-3 px-8 rounded-full shadow-lg hover:bg-gray-100 transition duration-300">
                Contact Us
            </a>
        </div>
    </section>
@endsection
