@extends('layouts.user.app')

<<<<<<< Updated upstream
@section('title', 'Support | ' . $setting->app_name)

@section('content')
<section class="bg-gray-50 min-h-screen py-16 px-6 md:px-20">
    <div class="max-w-4xl mx-auto text-center mb-16">
        <h1 class="text-4xl font-bold mb-4">Support Center</h1>
        <p class="text-gray-600">We’re here to help you with any questions or issues. Choose a category below or contact us directly for assistance.</p>
    </div>

    <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
        <!-- Account Issues -->
        <div class="bg-white border border-gray-100 p-8 rounded-2xl shadow-md hover:shadow-lg hover:border-primary-400 transition text-center">
            <div class="w-14 h-14 bg-primary-100 text-primary-600 flex items-center justify-center rounded-full mx-auto mb-4">
                <i class="fas fa-user-circle text-2xl"></i>
            </div>
            <h3 class="font-semibold text-xl mb-2 text-gray-800">Account Issues</h3>
            <p class="text-gray-600 text-sm mb-4">Need help with login, signup, or account security? We’ve got you covered.</p>
            <a href="#" class="inline-block text-primary-600 font-medium hover:underline">Get Help</a>
        </div>

        <!-- Billing Support -->
        <div class="bg-white border border-gray-100 p-8 rounded-2xl shadow-md hover:shadow-lg hover:border-primary-400 transition text-center">
            <div class="w-14 h-14 bg-green-100 text-green-600 flex items-center justify-center rounded-full mx-auto mb-4">
                <i class="fas fa-shopping-cart text-2xl"></i>
            </div>
            <h3 class="font-semibold text-xl mb-2 text-gray-800">Billing Support</h3>
            <p class="text-gray-600 text-sm mb-4">Need assistance with payments, refunds, or invoices? Our team can help.</p>
            <a href="#" class="inline-block text-primary-600 font-medium hover:underline">Contact Billing</a>
        </div>

        <!-- Technical Help -->
        <div class="bg-white border border-gray-100 p-8 rounded-2xl shadow-md hover:shadow-lg hover:border-primary-400 transition text-center">
            <div class="w-14 h-14 bg-yellow-100 text-yellow-600 flex items-center justify-center rounded-full mx-auto mb-4">
                <i class="fas fa-cogs text-2xl"></i>
            </div>
            <h3 class="font-semibold text-xl mb-2 text-gray-800">Technical Help</h3>
            <p class="text-gray-600 text-sm mb-4">Having trouble using features or the website? Let us help you fix it.</p>
            <a href="#" class="inline-block text-primary-600 font-medium hover:underline">Troubleshoot</a>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="mt-20 text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">Still Need Help?</h2>
        <p class="text-gray-600 mb-8">Can’t find what you’re looking for? Reach out to our support team directly.</p>
        <a href="{{ route('contact') }}" class="inline-block bg-gradient-to-r from-primary-500 to-primary-600 text-white font-semibold py-3 px-8 rounded-full shadow-lg hover:opacity-90 transition duration-300">
            Contact Us
        </a>
    </div>
=======
@section('title', 'Support | YourAppName')

@section('content')
<section class="bg-gradient-to-br from-gray-900 to-gray-800 text-white min-h-screen py-16 px-6 md:px-20">
    <div class="max-w-4xl mx-auto text-center mb-12">
        <h1 class="text-4xl font-bold mb-4 text-primary-400">Support Center</h1>
        <p class="text-gray-400">We’re here to help. Choose a category or contact us directly for assistance.</p>
    </div>

    <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-gray-800 border border-gray-700 p-6 rounded-2xl hover:border-primary-500 transition text-center">
            <i class="fas fa-user-circle text-3xl text-primary-400 mb-3"></i>
            <h3 class="font-semibold text-lg mb-2">Account Issues</h3>
            <p class="text-gray-400 text-sm mb-3">Need help with login, signup, or security?</p>
            <a href="#" class="text-primary-400 hover:underline">Get Help</a>
        </div>
        <div class="bg-gray-800 border border-gray-700 p-6 rounded-2xl hover:border-primary-500 transition text-center">
            <i class="fas fa-shopping-cart text-3xl text-primary-400 mb-3"></i>
            <h3 class="font-semibold text-lg mb-2">Billing Support</h3>
            <p class="text-gray-400 text-sm mb-3">Issues with payments, refunds, or invoices?</p>
            <a href="#" class="text-primary-400 hover:underline">Contact Billing</a>
        </div>
        <div class="bg-gray-800 border border-gray-700 p-6 rounded-2xl hover:border-primary-500 transition text-center">
            <i class="fas fa-cogs text-3xl text-primary-400 mb-3"></i>
            <h3 class="font-semibold text-lg mb-2">Technical Help</h3>
            <p class="text-gray-400 text-sm mb-3">Having trouble using features or the website?</p>
            <a href="#" class="text-primary-400 hover:underline">Troubleshoot</a>
        </div>
    </div>
>>>>>>> Stashed changes
</section>
@endsection
