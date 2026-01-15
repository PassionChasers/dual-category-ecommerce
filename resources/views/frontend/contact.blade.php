@extends('layouts.user.app')

@section('title', 'Contact Us | ' . ($settings->app_name ?? 'Ecommerce'))

@section('content')
<section class="bg-gray-50 min-h-screen py-16 px-6 md:px-20">
    <!-- Header -->
    <div class="max-w-4xl mx-auto text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-6 text-gray-900 tracking-tight">
                How can we <span class="text-primary-600">help you?</span>
            </h1>
        <p class="text-gray-600 text-lg max-w-2xl mx-auto">
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
                class="bg-white/80 backdrop:blur-md p-8 md:p-10 rounded-2xl border border-gray-100 shadow-lg"
            >
                @csrf

                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Full Name
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 ">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                            <input
                                type="text"
                                name="name"
                                placeholder="Enter Your Name"
                                required
                                class="w-full py-3 pl-10 rounded-lg border bg-gray-50 border-gray-300 text-gray-800 placeholder-gray-400
                                       focus:border-primary-500 focus:ring-primary-500 focus:ring-1 outline-none transition"
                            >
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address
                        </label>
                        <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                            <input
                                type="email"
                                name="email"
                                placeholder="you@example.com"
                                required
                                class="w-full py-3 pl-10 rounded-lg border border-gray-300 bg-gray-50 text-gray-800 placeholder-gray-400
                                         focus:ring-1 outline-none transition"
                            >
                        </div>
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
                        class="w-full p-3 bg-gray-50 rounded-lg border border-gray-300 text-gray-800 placeholder-gray-400
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
                        class="w-full p-3 bg-gray-50 rounded-lg border border-gray-300 text-gray-800 placeholder-gray-400
                               focus:border-primary-500 focus:ring-primary-500 focus:ring-1 outline-none transition resize-none"
                    ></textarea>
                </div>
                    <button
                        type="submit"
                        class="w-full bg-orange-500 hover:bg-orange-600 hover:-translate-y-1 text-white font-semibold py-3.5 rounded-lg
                               shadow-md hover:opacity-90 transition duration-300"
                    >
                        <span class="pr-1">Send Message </span>
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                    
              
            </form>
        </div>

        <!-- Contact Info -->
        <div class="space-y-6">
            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-md">
                <h2 class="text-lg font-bold mb-3 text-primary-600">Get in Touch</h2>
                <p class="text-gray-600 text-sm mb-4">
                    Have questions about orders, products, or your account?
                    Our support team is ready to assist you.
                </p>

                <div class="space-y-3 text-sm">
                    <div class="flex items-center gap-5">
                        
                            <i class="fas fa-envelope mt-1 text-xl"></i>
                        
                        <div>
                            <p class="text-gray-500 font-medium uppercase">Email us</p>
                            <p class="text-gray-800 font-medium">
                                {{ $settings->support_email ?? 'support@example.com' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-5">
                        <i class="fa-solid fa-phone text-xl"></i>
                        <div>
                            <p class="text-gray-500 uppercase font-medium">Phone</p>
                            <p class="text-gray-800 font-medium">
                                {{ $settings->support_phone ?? '+977-0000000000' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-5">
                        <i class="fas fa-clock mt-1 text-xl"></i>
                        <div>
                            <p class="text-gray-500 uppercase font-medium">Support Hours</p>
                            <p class="text-gray-800 font-medium">Sunday – Friday, 9:00 AM – 6:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Help -->
            <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-md">
                <h2 class="text-lg font-semibold mb-3 text-primary-600">Need faster help?</h2>
                <p class="text-gray-600 text-sm mb-4">
                    You can also check our FAQ or Support Center for instant answers to common questions.
                </p>
                <div class="flex flex-col gap-3">
                    <a
                        href="{{ route('faq') }}"
                        class="inline-flex items-center justify-center px-4 py-3 rounded-xl border hover:border-orange-600
                                text-sm font-medium bg-white hover:bg-orange-500 hover:text-white  transition mb-3"
                    > 
                        <i class="fas fa-question-circle mr-2"></i> View FAQs
                    </a>
                    <!-- <a
                        href="{{ route('support') }}"
                        class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-primary-500
                               text-white text-sm font-medium hover:bg-primary-600 transition"
                    >
                        <i class="fas fa-headset mr-2"></i> Go to Support Center
                    </a> -->
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
