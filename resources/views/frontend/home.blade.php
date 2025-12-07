@extends('layouts.user.app')
@section('title', ($settings->app_name ?? 'Unified Mobile App') . ' | Home')

@push('styles')
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="relative gradient-bg text-white py-20 flex items-center justify-center overflow-hidden" id="home">
        <!-- Floating background shapes -->
        <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
            <div class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full animate-float"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full animate-float"
                 style="animation-delay: 2s;"></div>
        </div>

        <!-- Centered Content -->
        <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
            <h2 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                One Unified App for
                <span class="text-primary-300">Medicine &amp; Food Delivery</span>
            </h2>
            <p class="text-lg md:text-xl mb-8 text-primary-100">
                {{ $settings->app_name ?? 'Unified Mobile App' }} lets customers order medicines or food from a single
                mobile experience, with GPS-based discovery, reward coins, and a powerful web admin portal for
                configuration, reporting, and ads management.
            </p>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('login') }}"
                   class="bg-white text-primary-600 px-6 py-4 rounded-lg font-semibold text-center shadow-lg hover:bg-gray-50 transition duration-300 flex items-center justify-center">
                    <span>Sign In to Continue</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="#modules"
                   class="bg-transparent border-2 border-white text-white px-6 py-4 rounded-lg font-semibold text-center hover:bg-white hover:bg-opacity-10 transition duration-300 flex items-center justify-center">
                    <i class="fas fa-mobile-alt mr-2"></i>
                    <span>Explore Modules</span>
                </a>
            </div>

            <p class="mt-6 text-sm text-primary-100">
                Customers use one module at a time — Medical Supplier or Food Delivery — ensuring a focused, streamlined ordering flow.
            </p>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-10 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="p-6 rounded-lg hover:bg-gray-50 transition duration-300">
                    <p class="text-3xl md:text-4xl font-bold text-primary-600">
                        {{-- {{$userCount}}+ --}}
                        1K+
                    </p>
                    <p class="text-gray-600 mt-2">Active Users</p>
                </div>
                <div class="p-6 rounded-lg hover:bg-gray-50 transition duration-300">
                    <p class="text-3xl md:text-4xl font-bold text-primary-600">
                        {{-- {{$medicineRequestCount}}+ --}}
                        8K+
                    </p>
                    <p class="text-gray-600 mt-2">Medicine Requests &amp; Food Orders</p>
                </div>
                <div class="p-6 rounded-lg hover:bg-gray-50 transition duration-300">
                    <p class="text-3xl md:text-4xl font-bold text-primary-600">99.9%</p>
                    <p class="text-gray-600 mt-2">Platform Uptime</p>
                </div>
                <div class="p-6 rounded-lg hover:bg-gray-50 transition duration-300">
                    <p class="text-3xl md:text-4xl font-bold text-primary-600">24/7</p>
                    <p class="text-gray-600 mt-2">Order &amp; Notification Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-16 bg-gray-50" id="about">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="md:w-1/2">
                    <h3 class="text-3xl md:text-4xl font-bold mb-6">
                        About {{ $settings->app_name ?? 'Unified Mobile App' }}
                    </h3>
                    <p class="text-gray-600 mb-4">
                        {{ $settings->app_name ?? 'Unified Mobile App' }} is designed as a unified experience for
                        retail customers who need reliable medicine supply and on-demand food delivery from one app.
                        The system is powered by a cloud-hosted backend, a modern mobile interface, and a feature-rich
                        web admin portal.
                    </p>
                    <p class="text-gray-600 mb-6">
                        Customers can select either the Medical Supplier or Food Delivery module, place COD orders, earn
                        reward coins, and receive real-time notifications. Admins configure menus, suppliers, ads,
                        reports, and payment settings from a central dashboard.
                    </p>
                    <div class="flex items-center flex-wrap gap-4">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center mr-3">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <span class="font-medium">GPS-based supplier &amp; restaurant discovery</span>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center mr-3">
                                <i class="fas fa-coins"></i>
                            </div>
                            <span class="font-medium">Unified reward coin system</span>
                        </div>
                    </div>
                </div>
                <div class="md:w-1/2">
                    <div class="rounded-xl overflow-hidden shadow-lg">
                        <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1000&q=80"
                             alt="Delivery and medical services" class="w-full h-auto">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modules / Services Section -->
    <section class="py-16 bg-white" id="modules">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h3 class="text-3xl md:text-4xl font-bold mb-4">Modules &amp; Services</h3>
                <p class="text-gray-600">
                    A modular platform combining a Medical Supplier module, a Food Delivery module, and a web admin
                    portal — all connected through secure APIs.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Medical Supplier -->
                <div class="services-card bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div
                        class="w-14 h-14 rounded-lg bg-indigo-100 text-primary-600 flex items-center justify-center mb-4">
                        <i class="fas fa-prescription-bottle-alt text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Medical Supplier Module</h4>
                    <p class="text-gray-600">
                        Customers request medicines, upload prescriptions, and connect with nearby suppliers using
                        real-time GPS filters and manual location selection.
                    </p>
                </div>

                <!-- Food Delivery -->
                <div class="services-card bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div
                        class="w-14 h-14 rounded-lg bg-green-100 text-secondary-600 flex items-center justify-center mb-4">
                        <i class="fas fa-hamburger text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Food Delivery Module</h4>
                    <p class="text-gray-600">
                        A streamlined food ordering flow with menus, cart management, COD payments, and location-based
                        restaurant discovery.
                    </p>
                </div>

                <!-- Web Admin Portal -->
                <div class="services-card bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="w-14 h-14 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mb-4">
                        <i class="fas fa-desktop text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Web Admin Portal</h4>
                    <p class="text-gray-600">
                        Admins manage users, menus, orders, rewards, popup ads, and reports from a modern browser-based
                        dashboard.
                    </p>
                </div>

                <!-- Reward & Ads -->
                <div class="services-card bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div
                        class="w-14 h-14 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center mb-4">
                        <i class="fas fa-bullhorn text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Rewards &amp; Popup Ads</h4>
                    <p class="text-gray-600">
                        Provide L/I coins on completed orders and configure targeted popup ads to boost engagement
                        across both modules.
                    </p>
                </div>

                <!-- Analytics -->
                <div class="services-card bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div
                        class="w-14 h-14 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center mb-4">
                        <i class="fas fa-chart-pie text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Reports &amp; Analytics</h4>
                    <p class="text-gray-600">
                        Generate customer and supplier reports, performance summaries, and export insights in CSV/PDF
                        formats.
                    </p>
                </div>

                <!-- Security -->
                <div class="services-card bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="w-14 h-14 rounded-lg bg-red-100 text-red-600 flex items-center justify-center mb-4">
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Secure by Design</h4>
                    <p class="text-gray-600">
                        Encrypted data, role-based access control, and JWT authentication ensure that every interaction
                        is secure and auditable.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-16 bg-gradient-to-br from-gray-50 to-indigo-50" id="features">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h3 class="text-3xl md:text-4xl font-bold mb-4">Core Features</h3>
                <p class="text-gray-600">
                    Shared functionality across Medical and Food modules, designed for performance, usability, and
                    scalability.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Module Selection -->
                <div class="features-card bg-white rounded-xl p-6 shadow-sm">
                    <div
                        class="w-14 h-14 rounded-lg bg-indigo-100 text-primary-600 flex items-center justify-center mb-4">
                        <i class="fas fa-exchange-alt text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Single-Module Ordering</h4>
                    <p class="text-gray-600">
                        Users can switch between Medical and Food modules, with the system enforcing one active order at
                        a time for clarity and safety.
                    </p>
                </div>

                <!-- Location Services -->
                <div class="features-card bg-white rounded-xl p-6 shadow-sm">
                    <div class="w-14 h-14 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mb-4">
                        <i class="fas fa-location-arrow text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Location-Based Discovery</h4>
                    <p class="text-gray-600">
                        Use GPS or manual location selection to instantly discover nearby suppliers and restaurants with
                        available items.
                    </p>
                </div>

                <!-- Notifications -->
                <div class="features-card bg-white rounded-xl p-6 shadow-sm">
                    <div
                        class="w-14 h-14 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center mb-4">
                        <i class="fas fa-bell text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Smart Notifications</h4>
                    <p class="text-gray-600">
                        Receive push and in-app alerts for request approvals, delivery updates, reward coins, and admin
                        announcements.
                    </p>
                </div>

                <!-- COD -->
                <div class="features-card bg-white rounded-xl p-6 shadow-sm">
                    <div
                        class="w-14 h-14 rounded-lg bg-green-100 text-secondary-600 flex items-center justify-center mb-4">
                        <i class="fas fa-hand-holding-usd text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Cash on Delivery First</h4>
                    <p class="text-gray-600">
                        Both modules initially support Cash on Delivery, with a clean architecture prepared for future
                        online payment gateways.
                    </p>
                </div>

                <!-- Chat -->
                <div class="features-card bg-white rounded-xl p-6 shadow-sm">
                    <div
                        class="w-14 h-14 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center mb-4">
                        <i class="fas fa-comments text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">In-App Communication</h4>
                    <p class="text-gray-600">
                        Real-time chat between customers and suppliers/restaurants helps clarify orders and delivery
                        details quickly.
                    </p>
                </div>

                <!-- Invoices -->
                <div class="features-card bg-white rounded-xl p-6 shadow-sm">
                    <div class="w-14 h-14 rounded-lg bg-red-100 text-red-600 flex items-center justify-center mb-4">
                        <i class="fas fa-file-invoice-dollar text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Automatic Invoices</h4>
                    <p class="text-gray-600">
                        Generate digital invoices for approved and completed orders, shared via email/SMS and accessible
                        from the user profile.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 bg-white" id="testimonials">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h3 class="text-3xl md:text-4xl font-bold mb-4">Trusted by Healthcare &amp; Food Businesses</h3>
                <p class="text-gray-600">
                    Teams across clinics, pharmacies, and restaurants rely on
                    {{ $settings->app_name ?? 'Unified Mobile App' }} to streamline their operations.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="testimonial-card bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center font-bold text-primary-700 mr-4 testimonial-avatar">
                            CN
                        </div>
                        <div>
                            <h4 class="font-bold">Care Nepal Pharmacy</h4>
                            <p class="text-gray-500 text-sm">Medical Supplier</p>
                        </div>
                    </div>
                    <p class="text-gray-700">
                        "{{ $settings->app_name ?? 'Unified Mobile App' }} made handling prescription requests and
                        nearby branches effortless. Our response time dropped and customer satisfaction went up."
                    </p>
                    <div class="mt-4 flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                <div class="testimonial-card bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center font-bold text-primary-700 mr-4 testimonial-avatar">
                            UR
                        </div>
                        <div>
                            <h4 class="font-bold">Urban Restaurants</h4>
                            <p class="text-gray-500 text-sm">Restaurant Chain</p>
                        </div>
                    </div>
                    <p class="text-gray-700">
                        "The Food module with cart management, COD, and invoices fits perfectly with our operations.
                        Our customers love the smooth ordering experience."
                    </p>
                    <div class="mt-4 flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                <div class="testimonial-card bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center font-bold text-primary-700 mr-4 testimonial-avatar">
                            AD
                        </div>
                        <div>
                            <h4 class="font-bold">Admin Dashboard User</h4>
                            <p class="text-gray-500 text-sm">Platform Admin</p>
                        </div>
                    </div>
                    <p class="text-gray-700">
                        "From a single portal we manage users, menus, ads, and reports. The role-based access and
                        monitoring tools keep everything under control."
                    </p>
                    <div class="mt-4 flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
@endpush
