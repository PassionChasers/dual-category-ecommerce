<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password | {{ $setting->app_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @if($setting && $setting->favicon)
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/' . $setting->favicon) }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $setting->favicon) }}">
    @else
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/passionchasers.png') }}">
    @endif
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">

<div class="relative bg-white p-8 rounded-xl shadow-md w-full max-w-md">

    <!-- Cancel / Close Icon -->
    <a href="{{ route('login') }}" 
       class="absolute top-4 right-4 text-gray-400 hover:text-indigo-600 transition"
       title="Cancel">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
             stroke-width="2" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" 
                  d="M6 18L18 6M6 6l12 12" />
        </svg>
    </a>

    <!-- Heading -->
    <h2 class="text-2xl font-semibold text-center text-gray-800 mb-4">Forgot Password</h2>
    <p class="text-gray-600 text-sm text-center mb-6">Enter your registered email to receive a 6-digit OTP.</p>

    <!-- Alerts -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-2 rounded mb-4">{{ $errors->first() }}</div>
    @endif

    <!-- Form -->
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <label class="block text-sm font-medium mb-1 text-left">Email Address</label>
        <input type="email" name="email" required 
               class="w-full border rounded-lg p-3 mb-4 focus:ring-2 focus:ring-indigo-500"
               placeholder="your@email.com">

        <button type="submit" 
                class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition">
            Send OTP
        </button>
    </form>
</div>

</body>
</html>
