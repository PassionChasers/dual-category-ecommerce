<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP | {{ $setting->app_name }} </title>
     @if($setting && $setting->favicon)
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/' . $setting->favicon) }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $setting->favicon) }}">
    @else
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/passionchasers.png') }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        let timer = 120; // 2 minutes
        const countdown = setInterval(() => {
            const btn = document.getElementById('resendBtn');
            if (timer <= 0) {
                btn.disabled = false;
                btn.innerText = 'Resend OTP';
                clearInterval(countdown);
            } else {
                btn.innerText = 'Resend in ' + timer + 's';
                btn.disabled = true;
            }
            timer--;
        }, 1000);
    </script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
<div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
    <h2 class="text-2xl font-semibold text-center text-gray-800 mb-4">Verify OTP</h2>
    <p class="text-gray-600 text-sm text-center mb-6">Enter the 6-digit OTP sent to your email.</p>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-2 rounded mb-4">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.otp.verify') }}">
        @csrf
        <label class="block text-sm font-medium mb-1">OTP</label>
        <input type="text" name="otp" maxlength="6" required class="w-full border rounded-lg p-3 mb-4 focus:ring-2 focus:ring-indigo-500" placeholder="Enter OTP">

        <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 transition mb-3">Verify OTP</button>
    </form>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <input type="hidden" name="email" value="{{ session('otp_email') }}">
        <button id="resendBtn" type="submit" class="w-full bg-gray-200 text-gray-700 py-3 rounded-lg transition">Resend OTP</button>
    </form>
</div>
</body>
</html>
