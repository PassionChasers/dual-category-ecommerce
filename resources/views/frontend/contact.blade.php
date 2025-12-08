@extends('layouts.user.app')

@section('title', 'Contact Us | ' . ($settings->app_name ?? 'Ecommerce'))

@section('content')
<section class="bg-gray-50 min-h-screen py-16 px-6 md:px-20">
    <!-- Header -->
    <div class="max-w-4xl mx-auto text-center mb-12">
        <h1 class="text-4xl font-bold mb-4 text-primary-600">Contact Us</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">
            We’d love to hear from you! Fill out the form below and our team at
            {{ $settings->app_name ?? 'Ecommerce' }} will get back to you as soon as possible.
        </p>
    </div>

    <div class="grid lg:grid-cols-3 gap-10 max-w-6xl mx-auto">
        <!-- Contact Form -->
        <div class="lg:col-span-2">
            <form
                action="#"
                method="POST"
                class="bg-white p-8 md:p-10 rounded-2xl border border-gray-100 shadow-lg"
            >
                @csrf

                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name
                        </label>
                        <input
                            type="text"
                            name="name"
                            placeholder="Your full name"
                            required
                            class="w-full p-3 rounded-md border border-gray-300 text-gray-800 placeholder-gray-400
                                   focus:border-primary-500 focus:ring-primary-500 focus:ring-1 outline-none transition"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address
                        </label>
                        <input
                            type="email"
                            name="email"
                            placeholder="you@example.com"
                            required
                            class="w-full p-3 rounded-md border border-gray-300 text-gray-800 placeholder-gray-400
                                   focus:border-primary-500 focus:ring-primary-500 focus:ring-1 outline-none transition"
                        >
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Subject
                    </label>
                    <input
                        type="text"
                        name="subject"
                        placeholder="How can we help you?"
                        class="w-full p-3 rounded-md border border-gray-300 text-gray-800 placeholder-gray-400
                               focus:border-primary-500 focus:ring-primary-500 focus:ring-1 outline-none transition"
                    >
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Message
                    </label>
                    <textarea
                        name="message"
                        rows="5"
                        placeholder="Write your message..."
                        required
                        class="w-full p-3 rounded-md border border-gray-300 text-gray-800 placeholder-gray-400
                               focus:border-primary-500 focus:ring-primary-500 focus:ring-1 outline-none transition resize-none"
                    ></textarea>
                </div>

                <button
                    type="submit"
                    class="w-full bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold py-3 rounded-md
                           shadow-md hover:opacity-90 transition duration-300"
                >
                    Send Message
                </button>
            </form>
        </div>

        <!-- Contact Info -->
        <div class="space-y-6">
            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-md">
                <h2 class="text-lg font-semibold mb-3 text-primary-600">Get in Touch</h2>
                <p class="text-gray-600 text-sm mb-4">
                    Have questions about orders, products, or your account?
                    Our support team is ready to assist you.
                </p>

                <div class="space-y-3 text-sm">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-envelope mt-1 text-primary-500"></i>
                        <div>
                            <p class="text-gray-800 font-medium">Email</p>
                            <p class="text-gray-600">
                                {{ $settings->support_email ?? 'support@example.com' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <i class="fas fa-phone-alt mt-1 text-primary-500"></i>
                        <div>
                            <p class="text-gray-800 font-medium">Phone</p>
                            <p class="text-gray-600">
                                {{ $settings->support_phone ?? '+977-0000000000' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <i class="fas fa-clock mt-1 text-primary-500"></i>
                        <div>
                            <p class="text-gray-800 font-medium">Support Hours</p>
                            <p class="text-gray-600">Sunday – Friday, 9:00 AM – 6:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Help -->
            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-md">
                <h2 class="text-lg font-semibold mb-3 text-primary-600">Need faster help?</h2>
                <p class="text-gray-600 text-sm mb-4">
                    You can also check our FAQ or Support Center for instant answers to common questions.
                </p>
                <div class="flex flex-col gap-3">
                    <a
                        href="{{ route('faq') }}"
                        class="inline-flex items-center justify-center px-4 py-2 rounded-md border border-primary-500
                               text-primary-600 text-sm font-medium hover:bg-primary-50 transition"
                    >
                        <i class="fas fa-question-circle mr-2"></i> View FAQs
                    </a>
                    <a
                        href="{{ route('support') }}"
                        class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-primary-500
                               text-white text-sm font-medium hover:bg-primary-600 transition"
                    >
                        <i class="fas fa-headset mr-2"></i> Go to Support Center
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
