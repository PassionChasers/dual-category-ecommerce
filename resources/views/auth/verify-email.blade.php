@extends('layouts.admin.app')
@section('title', 'Verify Email | Passion Chasers')

@push('styles')
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> --}}
@endpush
@section('contents')
{{-- <div class="min-h-screen flex items-center justify-center p-4 bg-gray-50">
    <div class="bg-white p-8 rounded-xl shadow-md max-w-md w-full text-center">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Verify Your Email</h1>
        <p class="text-gray-600 mb-6">
            Before accessing tasks, please verify your email address. Check your inbox for the verification link.
        </p>

        @if(session('resent'))
            <div class="mb-4 text-green-600 font-medium">
                A fresh verification link has been sent to your email address.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                class="py-2 px-4 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit"
                class="py-2 px-4 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Logout
            </button>
        </form>
    </div>
</div> --}}
<div class="min-h-screen flex items-center justify-center bg-gray-100 p-6">
    <div class="bg-white shadow-xl rounded-xl px-8 py-6 max-w-md w-full text-center border border-gray-200">
  
      <!-- Header Icon -->
      <div class="flex justify-center mb-3">
        <div class="w-16 h-16 bg-indigo-600 text-white flex items-center justify-center rounded-full shadow-md">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M16 12H8m8-4H8m8 8H8m13-9v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h3l2-2h4l2 2h3a2 2 0 012 2z" />
          </svg>
        </div>
      </div>
  
      <!-- Title & Description -->
      <h1 class="text-2xl font-bold text-gray-800 mb-3">Verify Your Email</h1>
      <p class="text-gray-600 text-sm leading-relaxed mb-4">
        Before proceeding, please check your inbox for a verification link. You must verify your email to access your account.
      </p>
  
      <!-- Messages -->
      @if(session('status'))
        <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 border border-green-200 rounded-md py-2">
          {{ session('status') }}
        </div>
      @endif
  
      @if(session('resent'))
        <div class="mb-4 text-sm font-medium text-green-600 bg-green-50 border border-green-200 rounded-md py-2">
          A new verification link has been sent to your email address.
        </div>
      @endif
  
      <!-- Resend Email Button -->
      <form method="POST" action="{{ route('verification.send') }}" class="mb-3">
        @csrf
        <button type="submit"
          class="w-full py-2.5 bg-indigo-600 text-white font-medium rounded-lg shadow-sm hover:bg-indigo-700 transition-all">
          Resend Verification Email
        </button>
      </form>
  
      <!-- Logout -->
      <form action="{{ route('logout') }}">
        @csrf
        <button type="submit"
          class="w-full py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-all">
          Logout
        </button>
      </form>
    </div>
</div>
@endsection
@push('scripts')
@endpush