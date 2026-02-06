<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | {{ $setting->AppName ?? 'App' }} </title>
     @if($setting && $setting->Favicon)
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/' . $setting->Favicon) }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $setting->Favicon) }}">
    @else
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/passionchasers.png') }}">
    @endif
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-md bg-transparent">

        <!-- Logo Header -->
        <div class="text-center mb-8">
            <div
                class="mx-auto w-16 h-16 rounded-lg border border-blue-200 flex items-center justify-center mb-4 overflow-hidden">
                @if($setting && $setting->AppLogo)
                <img src="{{ asset('storage/' . $setting->AppLogo) }}" alt="{{ $setting->AppName }}"
                    class="w-full h-full object-contain">
                @else
                <img src="{{ asset('storage/images/passionchasers.png') }}" alt="Default Logo"
                    class="w-full h-full object-contain">
                @endif
            </div>

            <h1 class="text-3xl font-bold text-gray-800">
                {{ $setting->AppName }}
                {{-- Ecommerce --}}
            </h1>  
        </div>

        <!-- Form -->
        <div class=" px-8 py-6 bg-orange-50 rounded-xl login-card overflow-hidden border border-gray-200">

            <h2 class="text-2xl font-semibold text-center text-gray-800 mb-4">Reset Password</h2>
            {{-- <p class="text-gray-600 text-sm text-center mb-6">Enter your email below.</p> --}}

            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-2 rounded mb-4">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('set-new-password') }}" id="resetPasswordForm">
                @csrf
                <div>
                    <label for="reset-code" class="block text-sm font-medium text-gray-700 mb-1">Reset Code</label>
                    <div class="relative">
                        {{-- <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div> --}}
                        <input id="reset-code" name="resetCode" type="text" value="{{ old('resetCode') }}" required
                            class="pl-10 pr-10 w-full p-3 border rounded-lg   outline-none transition input-focus"
                            placeholder="23******">
                    </div>
                    @if($errors->has('resetCode'))
                        <p class="mt-1 text-sm text-red-600">{{ $errors->first('resetCode') }}</p>
                    @endif
                </div>

                <div class="mt-4">
                    <label for="new-password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="new-password" name="newPassword" type="password" value="{{ old('newPassword') }}" required
                            class="pl-10 pr-10 w-full p-3 border rounded-lg   outline-none transition input-focus"
                            placeholder="New password">
                        <!-- Eye toggle button -->
                        {{-- <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                            <i class="fas fa-eye"></i>
                        </button> --}}
                    </div>
                    @if($errors->has('newPassword'))
                        <p class="mt-1 text-sm text-red-600">
                            {{ $errors->first('newPassword') }}
                        </p>
                    @endif
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1"> Confirm Password </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" name="newPassword_confirmation"
                            class="pl-10 pr-10 w-full p-3 border rounded-lg   outline-none transition input-focus"
                            placeholder="Confirm password"
                        >
                    </div>
                    @if($errors->has('newPassword_confirmation'))
                        <p class="mt-1 text-sm text-red-600">
                            {{ $errors->first('newPassword_confirmation') }}
                        </p>
                    @endif
                </div>

                <button type="submit" id="submitBtn"
                    class="w-full mt-4 py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 transition flex items-center justify-center gap-2"
                >
                    <span id="btnText">
                        Submit
                    </span>
                    <svg id="btnSpinner" class="w-5 h-5 animate-spin hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                </button>
            </form>

            <div class="text-center m-2 p-2">
                <a href="{{route('login')}}" class="p-2 text-lg text-red-600 rounded">Cancel</a>
            </div>

        </div>

        <!-- Copyright Notice -->
        <div class="mt-8 text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} {{ $setting->AppName}} . All rights reserved.
        </div>
    </div>

    <script>
        const form = document.getElementById('resetPasswordForm');
        const btn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const spinner = document.getElementById('btnSpinner');

        form.addEventListener('submit', function(e) {
            // Show spinner and disable button
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            btnText.textContent = 'Submitting...';
            spinner.classList.remove('hidden');
        });
    </script>

</body>
</html>
