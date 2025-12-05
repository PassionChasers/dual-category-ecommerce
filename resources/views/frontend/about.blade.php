@extends('layouts.user.app')

<<<<<<< Updated upstream
@section('title', 'About Us | ' . $setting->app_name)

@section('content')
<!-- Hero / Intro Section -->
<section class="relative gradient-bg text-white py-20 text-center overflow-hidden">
    <!-- Floating shapes -->
    <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full animate-float"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full animate-float" style="animation-delay: 2s;"></div>
    </div>

    <div class="max-w-3xl mx-auto px-6 relative z-10">
        <h1 class="text-5xl font-bold mb-4">About <span class="text-primary-200">{{ $setting->app_name }}</span></h1>
        <p class="text-lg text-primary-100 leading-relaxed">
            Empowering teams and individuals with seamless task management and collaboration tools built for performance and simplicity.
        </p>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-20 bg-white">
    <div class="max-w-6xl mx-auto px-6 flex justify-between items-center gap-12 md:flex-row flex-col md:space-y-0 space-y-12">
        <div>
            <h2 class="text-3xl font-bold text-primary-600 mb-4">Our Mission</h2>
            <p class="text-gray-600 leading-relaxed mb-6">
                Our mission is to simplify task management for everyone — helping teams organize, prioritize, and complete their goals efficiently through a beautiful and intuitive platform.
            </p>

            <h2 class="text-3xl font-bold text-primary-600 mb-4">Our Vision</h2>
            <p class="text-gray-600 leading-relaxed">
                We envision a world where productivity tools work seamlessly for all — making collaboration effortless and empowering organizations to achieve more together.
            </p>
        </div>
        <div class="">
            {{-- <div class="absolute -inset-1 bg-gradient-to-r from-primary-300/30 to-primary-500/30 rounded-2xl blur opacity-60 group-hover:opacity-80 transition"></div> --}}
            <img src="{{ asset('storage/' . $setting->favicon) }}" alt="About Us" class="relative rounded-2xl shadow-xl transform group-hover:scale-105 transition duration-500">
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-6 text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-800 mb-4">Our Core Values</h2>
        <p class="text-gray-600">These values shape how we build, work, and connect with our community.</p>
    </div>

    <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto px-6">
        <div class="bg-white p-8 rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition">
            <div class="w-14 h-14 bg-primary-100 text-primary-600 flex items-center justify-center rounded-lg mb-4 mx-auto">
                <i class="fas fa-lightbulb text-2xl"></i>
            </div>
            <h4 class="text-xl font-semibold mb-2">Innovation</h4>
            <p class="text-gray-600">We constantly evolve to bring fresh ideas and smarter solutions to our users.</p>
        </div>

        <div class="bg-white p-8 rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition">
            <div class="w-14 h-14 bg-green-100 text-green-600 flex items-center justify-center rounded-lg mb-4 mx-auto">
                <i class="fas fa-handshake text-2xl"></i>
            </div>
            <h4 class="text-xl font-semibold mb-2">Integrity</h4>
            <p class="text-gray-600">We believe in honesty, transparency, and building trust with every interaction.</p>
        </div>

        <div class="bg-white p-8 rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition">
            <div class="w-14 h-14 bg-yellow-100 text-yellow-600 flex items-center justify-center rounded-lg mb-4 mx-auto">
                <i class="fas fa-users text-2xl"></i>
            </div>
            <h4 class="text-xl font-semibold mb-2">Teamwork</h4>
            <p class="text-gray-600">Collaboration is at the heart of everything we do — we grow better together.</p>
        </div>
    </div>
</section>

<!-- Join Section -->
{{-- <section class="py-20 bg-gradient-to-r from-primary-500 to-primary-600 text-white text-center">
    <div class="max-w-4xl mx-auto px-6">
        <h2 class="text-4xl font-bold mb-4">Join Us on Our Journey</h2>
        <p class="text-primary-100 mb-8">
            Together, let’s redefine productivity with innovative tools that bring clarity, creativity, and collaboration to life.
        </p>
        <a href="{{ route('contact') }}" class="inline-block bg-white text-primary-600 font-semibold py-3 px-8 rounded-full shadow-lg hover:bg-gray-100 transition duration-300">
            Contact Us
        </a>
    </div>
</section> --}}
=======
@section('title', 'About Us | YourAppName')

@section('content')
<section class="from-gray-900 to-gray-800 text-white min-h-screen py-16 px-6 md:px-20">
    <div class="max-w-5xl mx-auto text-center mb-12">
        <h1 class="text-4xl font-bold mb-4 text-primary-400">About Us</h1>
        <p class="text-gray-400">We are dedicated to delivering innovative digital experiences with passion and precision.</p>
    </div>

    <div class="grid md:grid-cols-2 gap-10 items-center">
        <div class="space-y-4">
            <h2 class="text-2xl font-semibold text-primary-400">Our Mission</h2>
            <p class="text-gray-400 leading-relaxed">
                Our mission is to empower users with technology that simplifies their lives — intuitive, reliable, and built for performance.
            </p>
            <h2 class="text-2xl font-semibold text-primary-400 mt-6">Our Vision</h2>
            <p class="text-gray-400 leading-relaxed">
                We aim to be a leading platform in digital innovation, driven by creativity, transparency, and excellence.
            </p>
        </div>
        <div>
            <img src="{{ asset('storage/' . $setting->favicon) }}" alt="About Us" class="rounded-2xl shadow-lg">
        </div>
    </div>
</section>
>>>>>>> Stashed changes
@endsection
