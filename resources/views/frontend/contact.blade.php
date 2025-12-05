@extends('layouts.user.app')

<<<<<<< Updated upstream
@section('title', 'Contact Us | ' . $setting->app_name)

@section('content')
<section class="bg-gray-50 min-h-screen py-8 px-6 md:px-20">
    <!-- Header -->
    <div class="max-w-4xl mx-auto text-center mb-6">
        <h1 class="text-4xl font-bold mb-3 text-primary-600">Contact Us</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">
            We’d love to hear from you! Fill out the form below or reach out to our support team — we’ll get back to you as soon as possible.
        </p>
    </div>

    <!-- Contact Form -->
    <form action="#" method="POST" class="max-w-2xl mx-auto bg-white p-10 rounded-2xl shadow-md border border-gray-100">
        @csrf
        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" placeholder="Your full name" required
                    class="w-full p-3 rounded-md border border-gray-300 focus:border-primary-500 focus:ring-primary-500 focus:ring-1 outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" placeholder="you@example.com" required
                    class="w-full p-3 rounded-md border border-gray-300 focus:border-primary-500 focus:ring-primary-500 focus:ring-1 outline-none transition">
            </div>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
            <input type="text" name="subject" placeholder="Subject"
                class="w-full p-3 rounded-md border border-gray-300 focus:border-primary-500 focus:ring-primary-500 focus:ring-1 outline-none transition">
        </div>
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
            <textarea name="message" rows="5" placeholder="Write your message..." required
                class="w-full p-3 rounded-md border border-gray-300 focus:border-primary-500 focus:ring-primary-500 focus:ring-1 outline-none transition"></textarea>
        </div>
        <button type="submit"
            class="w-full bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold py-3 rounded-md shadow-md hover:opacity-90 transition duration-300">
            Send Message
        </button>
=======
@section('title', 'Contact Us | YourAppName')

@section('content')
<section class="bg-gradient-to-br from-gray-900 to-gray-800 text-white min-h-screen py-16 px-6 md:px-20">
    <div class="max-w-4xl mx-auto text-center mb-12">
        <h1 class="text-4xl font-bold mb-4 text-primary-400">Contact Us</h1>
        <p class="text-gray-400">We’d love to hear from you! Fill out the form below or reach out via email.</p>
    </div>

    <form action="#" method="POST" class="max-w-2xl mx-auto bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-lg">
        @csrf
        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <input type="text" name="name" placeholder="Full Name" required class="w-full p-3 rounded-md bg-gray-900 border border-gray-700 text-white focus:border-primary-500 focus:ring-primary-500">
            <input type="email" name="email" placeholder="Email Address" required class="w-full p-3 rounded-md bg-gray-900 border border-gray-700 text-white focus:border-primary-500 focus:ring-primary-500">
        </div>
        <input type="text" name="subject" placeholder="Subject" class="w-full p-3 mb-4 rounded-md bg-gray-900 border border-gray-700 text-white focus:border-primary-500 focus:ring-primary-500">
        <textarea name="message" rows="5" placeholder="Your Message" required class="w-full p-3 mb-4 rounded-md bg-gray-900 border border-gray-700 text-white focus:border-primary-500 focus:ring-primary-500"></textarea>
        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 py-3 rounded-md font-semibold transition">Send Message</button>
>>>>>>> Stashed changes
    </form>
</section>
@endsection
