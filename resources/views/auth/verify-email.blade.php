@extends('layouts.admin.app')

@section('title', 'Verify Email')

@section('contents')
<div class="flex items-center justify-center min-h-screen bg-gray-50">
    <div class="bg-white p-6 rounded-lg shadow w-full max-w-md">

        <h2 class="text-xl font-bold mb-4 text-center">Verify Email</h2>

        @php
            function maskEmail($email) {
                [$name, $domain] = explode('@', $email);
                return substr($name, 0, 2)
                    . str_repeat('*', max(strlen($name) - 2, 0))
                    . '@' . $domain;
            }
        @endphp

        <p class="text-sm text-gray-600 mb-4 text-center">
            We sent a verification code to
            <strong>{{ maskEmail($email) }}</strong>
        </p>

        <form id="verifyEmailForm" class="space-y-4">
            @csrf

            <!-- Hidden Email -->
            <input type="hidden" id="email" value="{{ $email }}">

            <!-- OTP -->
            <div>
                <label class="block text-sm font-medium mb-1">OTP Code</label>
                <input
                    type="text"
                    id="code"
                    placeholder="Enter 6-digit OTP"
                    inputmode="numeric"
                    maxlength="6"
                    pattern="[0-9]{6}"
                    class="w-full border px-3 py-2 rounded focus:ring-indigo-500 focus:border-indigo-500"
                    required
                >
            </div>

            <button
                id="verifyBtn"
                type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700"
            >
                Verify Email
            </button>
        </form>

        <!-- Resend -->
        <div class="text-center mt-4">
            <button
                id="resendCodeBtn"
                class="text-indigo-600 text-sm hover:underline disabled:text-gray-400"
            >
                Resend Code
            </button>

            <p id="resendTimer" class="text-xs text-gray-500 mt-1 hidden">
                Resend available in <span id="seconds">30</span>s
            </p>
        </div>

    </div>
</div>
@endsection

{{-- Admin JWT --}}
<script>
const ADMIN_JWT = @json(session('jwt_token'));
</script>

{{-- Verify Email --}}
<script>
document.getElementById('verifyEmailForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const email = document.getElementById('email').value;
    const code  = document.getElementById('code').value;
    const btn   = document.getElementById('verifyBtn');

    btn.disabled = true;

    fetch('https://pcsdecom.azurewebsites.net/api/Auth/verify-email', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${ADMIN_JWT}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ email, code })
    })
    .then(async res => {
        const data = await res.json();
        if (!res.ok) throw data;
        return data;
    })
    .then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Email Verified',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            window.location.href = '/admin/dashboard';
        });
    })
    .catch(err => {
        btn.disabled = false;
        Swal.fire(
            'Error',
            err?.message || 'Invalid or expired OTP',
            'error'
        );
    });
});
</script>

{{-- Resend OTP with 30s cooldown --}}
<script>
let resendCooldown = 30;
let resendInterval;

function startResendTimer() {
    const btn = document.getElementById('resendCodeBtn');
    const timer = document.getElementById('resendTimer');
    const seconds = document.getElementById('seconds');

    btn.disabled = true;
    timer.classList.remove('hidden');

    resendInterval = setInterval(() => {
        resendCooldown--;
        seconds.textContent = resendCooldown;

        if (resendCooldown <= 0) {
            clearInterval(resendInterval);
            btn.disabled = false;
            timer.classList.add('hidden');
            resendCooldown = 30;
            seconds.textContent = 30;
        }
    }, 1000);
}

document.getElementById('resendCodeBtn').addEventListener('click', function () {
    const email = document.getElementById('email').value;

    startResendTimer();

    fetch('https://pcsdecom.azurewebsites.net/api/Auth/resend-verification', {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${ADMIN_JWT}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ email })
    })
    .then(async res => {
        if (!res.ok) throw await res.json();
        Swal.fire('Sent!', 'Verification code resent', 'success');
    })
    .catch(err => {
        Swal.fire(
            'Error',
            err?.message || 'Unable to resend code',
            'error'
        );
    });
});
</script>

{{-- Auto-focus OTP --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('code').focus();
});
</script>
