<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | {{ $setting->app_name }} </title>
     @if($setting && $setting->favicon)
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/' . $setting->favicon) }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $setting->favicon) }}">
    @else
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/passionchasers.png') }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
<div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
    <h2 class="text-2xl font-semibold text-center text-gray-800 mb-4">Reset Password</h2>
    <p class="text-gray-600 text-sm text-center mb-6">Enter your new password below.</p>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-2 rounded mb-4">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <label class="block text-sm font-medium mb-1">New Password</label>
        <input type="password" name="password" required class="w-full border rounded-lg p-3 mb-4 focus:ring-2 focus:ring-indigo-500" placeholder="New Password">

        <label class="block text-sm font-medium mb-1">Confirm Password</label>
        <input type="password" name="password_confirmation" required class="w-full border rounded-lg p-3 mb-4 focus:ring-2 focus:ring-indigo-500" placeholder="Confirm Password">

        <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition">Reset Password</button>
    </form>
</div>
</body>
</html>
