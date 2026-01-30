@extends('layouts.admin.app')
@section('title', 'Admin | Profile')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('contents')
<div class="flex-1 overflow-auto p-4 md:p-6 bg-gray-50">
    <div class="mb-6 flex justify-between items-center flex-wrap">
        <div class="mb-2 md:mb-0">
            <h2 class="text-2xl font-bold text-gray-800">My Profile</h2>
            <p class="text-gray-600">Update your personal information and account settings</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <h3 class="font-semibold text-red-800 mb-2">Please fix the following errors:</h3>
            <ul class="text-red-700 text-sm">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Profile Form -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" id="profileForm" autocomplete="off">
            @csrf
            @method('PUT')

            <!-- Profile Picture -->
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Profile Picture</h3>
            <div class="flex flex-col items-center pb-6 border-b border-gray-200 mb-6">
                <div class="relative w-24 h-24 rounded-full overflow-hidden border-2 border-gray-300 bg-gray-100 flex items-center justify-center cursor-pointer hover:border-indigo-500 transition" id="profile-picture-wrapper">
                    @if($user->AvatarUrl)
                        <img src="{{$user->AvatarUrl}}" onclick="showImage('{{ $user->AvatarUrl }}')" class="thumb cursor-pointer w-full h-full object-cover">
                    {{-- @else
                        <img src="{{ asset('storage/images/default-user.png') }}" class="w-full h-full object-cover" id="profile-picture-preview"> --}}
                    @endif
                    <div class="absolute inset-0 bg-black/50 flex justify-center items-center opacity-0 hover:opacity-100 transition">
                        <span class="text-xs text-white">Change Photo</span>
                    </div>
                </div>
                <input type="file" name="avatar" id="profile-picture-input" class="hidden" accept="image/*">
                <div class="flex gap-2 mt-3">
                    <button type="button" id="change-picture-btn" class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-100">Change</button>
                </div>
            </div>

            <!-- Personal Information -->
            <h3 class="text-lg font-semibold text-blue-700 mb-4">Personal Information</h3>
            <div class="grid grid-cols-1 border-b border-gray-200 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->Name) }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->Email) }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->Phone) }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">User Role</label>
                    <input type="text" value="{{ $user->Role == 4 ? 'Admin' : ($user->Role == 2 ? 'Medical Store' : ($user->Role == 3 ? 'Restaurant' : ($user->Role == 1 ? 'Customer' : 'Delivery'))) }}" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100" disabled>
                </div>
            </div>
            <hr class="mb-6 mt-2 border-gray-300">

            <!-- Password  -->
            {{-- <h3 class="text-lg font-semibold text-blue-700 mb-4">Change Password</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Old Password<span class="text-red-500">*</span></label>
                    <input type="password" name="oldPassword" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">New Password <span class="text-red-500">*</span></label>
                    <input type="password" name="newPassword" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Confirm New Password<span class="text-red-500">*</span></label>
                    <input type="password" name="confirmNewPassword" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <a href="#">Forgot Password</a>
                </div>
            </div>
            <hr class="mb-6 mt-2 border-gray-300"> --}}

            <!-- Organization Information (Only for store admins) -->
            @if($business)
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">
                        {{ $user->Role == 2 ? 'Medical Store Details' : 'Restaurant Details' }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 p-4 bg-blue-50 rounded-lg">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Business Name</label>
                            <p class="mt-1 text-gray-900 font-medium">{{ $business->Name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">PAN</label>
                            <p class="mt-1 text-gray-900 font-mono text-sm">{{ $business->PAN ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">License Number</label>
                            <p class="mt-1 text-gray-900 font-mono text-sm">{{ $business->LicenseNumber ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">GSTIN</label>
                            <p class="mt-1 text-gray-900 font-mono text-sm">{{ $business->GSTIN ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <p class="mt-1">
                                @if($business->IsActive)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-sm font-medium">Active</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-sm font-medium">Inactive</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($user->Role == 4)
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">System Administrator</h3>
                    <div class="p-4 bg-indigo-50 rounded-lg">
                        <p class="text-gray-700">You are the main system administrator. You manage all stores, restaurants, and platform operations.</p>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="flex justify-end space-x-2 pt-6 border-t border-gray-200">
                <a href="javascript:history.back()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Cancel</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const profileInput = document.getElementById("profile-picture-input");
        const profilePreview = document.getElementById("profile-picture-preview");
        const changeBtn = document.getElementById("change-picture-btn");
        const profileWrapper = document.getElementById("profile-picture-wrapper");

        changeBtn.addEventListener("click", () => profileInput.click());
        profileWrapper.addEventListener("click", () => profileInput.click());

        profileInput.addEventListener("change", (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    profilePreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Clear password fields on page load for security
        document.querySelectorAll('input[type="password"]').forEach(input => {
            input.value = '';
        });

        // SweetAlert for success message
        @if(session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if(session('error'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 3000
            });
        @endif
    });
</script>
@endpush
