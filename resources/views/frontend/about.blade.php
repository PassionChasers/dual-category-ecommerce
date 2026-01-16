@extends('layouts.user.app')

@section('title', 'About Us | ' . ($settings->app_name ?? 'Ecommerce'))

@section('content')
    <!-- Custom Styles for Floating Animation -->
    <style>
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>

    <!-- Hero / Intro Section -->
    <section class="relative bg-gradient-to-br from-orange-600 via-orange-500 to-yellow-400 text-white py-24 lg:py-32 overflow-hidden">
        <!-- Floating shapes -->
            <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
            <div class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full animate-float"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full animate-float"
            style="animation-delay: 2s;"></div>
            </div>
        <!-- Decorative Elements -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none opacity-20">
            <div class="absolute -top-24 -left-24 w-64 h-64 bg-white rounded-full blur-3xl animate-float"></div>
            <div class="absolute top-1/2 -right-20 w-80 h-80 bg-yellow-200 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
        </div>

        <div class="max-w-5xl mx-auto px-6 relative z-10 text-center">
            
            <h1 class="text-4xl md:text-6xl font-extrabold mb-6 leading-tight">
                About <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-200 to-white">{{ $settings->app_name ?? 'Ecommerce' }}</span>
            </h1>
            <p class="text-lg md:text-xl text-orange-50 font-light leading-relaxed max-w-3xl mx-auto">
                We’re building a modern online shopping experience — fast, secure, and customer-first — so you can discover and buy the products you love with complete confidence.
            </p>
        </div>
    </section>

    <!-- Stats Section (Added for Social Proof) -->
    <section class="relative -mt-12 z-20 px-6">
        <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 bg-white p-8 rounded-2xl shadow-2xl">
            <div class="text-center border-r border-gray-100 last:border-0">
                <div class="text-3xl md:text-4xl font-bold text-orange-600">10k+</div>
                <div class="text-sm text-gray-500 uppercase tracking-wide mt-1">Customers</div>
            </div>
            <div class="text-center md:border-r border-gray-100 last:border-0">
                <div class="text-3xl md:text-4xl font-bold text-orange-600">500+</div>
                <div class="text-sm text-gray-500 uppercase tracking-wide mt-1">Brands</div>
            </div>
            <div class="text-center border-r border-gray-100 last:border-0">
                <div class="text-3xl md:text-4xl font-bold text-orange-600">24/7</div>
                <div class="text-sm text-gray-500 uppercase tracking-wide mt-1">Support</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-orange-600">100%</div>
                <div class="text-sm text-gray-500 uppercase tracking-wide mt-1">Secure</div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-16 items-center">
            <div class="order-2 md:order-1">
                <div class="inline-block px-4 py-1 rounded-full bg-orange-100 text-orange-700 text-sm font-bold mb-4">
                    Why We Exist
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Our Mission</h2>
                <p class="text-gray-600 text-lg leading-relaxed mb-8 border-l-4 border-orange-500 pl-6">
                    Our mission is to make online shopping effortless and enjoyable — offering a curated catalog,
                    transparent pricing, and a smooth checkout so you can spend less time searching and more time enjoying
                    what you buy.
                </p>

                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Our Vision</h2>
                <p class="text-gray-600 text-lg leading-relaxed mb-6">
                    We envision a trusted ecommerce platform where customers feel confident with every order, brands can
                    grow, and technology quietly powers a seamless experience from browse to delivery.
                </p>
            </div>
            <div class="order-1 md:order-2 relative">
                <!-- Decorative background for image -->
                <div class="absolute -bottom-6 -right-6 w-full h-full bg-orange-100 rounded-2xl -z-10"></div>
                <img
                    src="{{ !empty($settings->favicon) ? asset('storage/' . $settings->favicon) : 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=800' }}"
                    alt="About Us"
                    class="rounded-2xl shadow-xl w-full object-cover h-[400px] transform hover:-translate-y-2 transition duration-500"
                >
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6 text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Core Values</h2>
            <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                These values guide how we build our platform, serve our customers, and grow our community.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto px-6">
            <!-- Value Card 1 -->
            <div class="group bg-white p-10 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-orange-100 text-orange-600 flex items-center justify-center rounded-2xl mb-6 group-hover:scale-110 group-hover:bg-orange-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-lightbulb text-3xl"></i>
                </div>
                <h4 class="text-2xl font-bold text-gray-900 mb-3">Customer Focus</h4>
                <p class="text-gray-600 leading-relaxed">
                    Every feature, page, and policy is designed with you in mind — to make your shopping journey simple and delightful.
                </p>
            </div>

            <!-- Value Card 2 -->
            <div class="group bg-white p-10 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-green-100 text-green-600 flex items-center justify-center rounded-2xl mb-6 group-hover:scale-110 group-hover:bg-green-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-shield-alt text-3xl"></i>
                </div>
                <h4 class="text-2xl font-bold text-gray-900 mb-3">Trust & Integrity</h4>
                <p class="text-gray-600 leading-relaxed">
                    We value transparency, secure transactions, and honest communication — so you always know what to expect.
                </p>
            </div>

            <!-- Value Card 3 -->
            <div class="group bg-white p-10 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                <div class="w-16 h-16 bg-blue-100 text-blue-600 flex items-center justify-center rounded-2xl mb-6 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                    <i class="fas fa-truck-moving text-3xl"></i>
                </div>
                <h4 class="text-2xl font-bold text-gray-900 mb-3">Reliability</h4>
                <p class="text-gray-600 leading-relaxed">
                    From product availability to delivery, we work hard to ensure a consistent, dependable experience every time.
                </p>
            </div>
        </div>
    </section>

    <!-- Join / CTA Section -->
    <section class="py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-gray-900">
            <!-- Subtle background image for the CTA -->
            <img src="https://plus.unsplash.com/premium_photo-1661492455085-365d5bee82a3?q=80&w=1507&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="w-full h-full object-cover opacity-20" alt="background">
        </div>
        <div class="max-w-4xl mx-auto px-6 relative z-10 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Grow With {{ $settings->app_name ?? 'Us' }}</h2>
            <p class="text-gray-300 text-lg mb-10 max-w-2xl mx-auto">
                Whether you’re a shopper looking for great deals or a brand wanting to reach more customers,
                {{ $settings->app_name ?? 'our platform' }} is here to help you every step of the way.
            </p>
            <a href="{{ route('contact') }}"
               class="inline-flex items-center justify-center bg-orange-500 hover:bg-orange-600 text-white font-bold py-4 px-10 rounded-full shadow-lg hover:shadow-orange-500/50 transform hover:-translate-y-1 transition-all duration-300">
                <span class="mr-2">Get In Touch</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </section>
@endsection