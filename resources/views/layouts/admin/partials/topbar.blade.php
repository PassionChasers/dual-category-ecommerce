@php
    $user = auth()->user();
@endphp

<div class="flex items-center justify-end py-[18px] px-4 bg-white border-b border-gray-200 relative">
    <div class="flex items-center space-x-4 relative">

        <!-- Date & Time -->
        <div id="datetimeDisplay" class="flex flex-col text-right leading-tight">
            <span class="text-sm font-medium text-gray-700" id="currentDay"></span>
            <span class="text-xs text-gray-500" id="currentDateTime"></span>
        </div>

        <!-- Quick Actions -->
        <div x-data="{ open:false }" class="relative">
            <button @click="open=!open" class="flex items-center text-sm text-gray-500">
                <span class="hidden md:inline">Quick Actions</span>
                <i class="ml-1 fas" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            </button>

            <div x-show="open" @click.outside="open=false"
                 class="absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg z-50">
                <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>
<<<<<<< Updated upstream

                <button onclick="openChangePasswordModal()" class="w-full text-left block px-4 py-2 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-key mr-2"></i> Change Password
                </button>

                <a href="{{ route('logout') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
=======
                <a href="{{ route('logout') }}" class="block px-4 py-2 hover:bg-gray-100">
>>>>>>> Stashed changes
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateDateTime() {
        const now = new Date().toLocaleString("en-US", { timeZone: "Asia/Kathmandu" });
        const date = new Date(now);

        document.getElementById('currentDay').textContent =
            date.toLocaleDateString('en-US', { weekday: 'long' });

        document.getElementById('currentDateTime').textContent =
            date.toLocaleDateString('en-US', {
                year: 'numeric', month: 'long', day: 'numeric',
                hour: 'numeric', minute: '2-digit'
            });
    }

    updateDateTime();
<<<<<<< Updated upstream

    // Change Password Modal
    function openChangePasswordModal() {
        const modal = document.getElementById('changePasswordModal');
        modal.classList.remove('hidden');
        document.getElementById('quickActionsDropdown').classList.add('hidden');
    }

    function closeChangePasswordModal() {
        const modal = document.getElementById('changePasswordModal');
        modal.classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('changePasswordModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeChangePasswordModal();
        }
    });
=======
    setInterval(updateDateTime, 60000); // optimized (1 min)
>>>>>>> Stashed changes
</script>
@endpush

<!-- Change Password Modal -->
<div id="changePasswordModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
        <!-- Modal Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Change Password</h3>
            <button onclick="closeChangePasswordModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="changePasswordForm" class="px-6 py-4">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                <input type="password" name="current_password" placeholder="Enter your current password" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                <input type="password" name="password" placeholder="Enter new password" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" placeholder="Confirm new password" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3 border-t border-gray-200 pt-4">
                <button type="button" onclick="closeChangePasswordModal()" 
                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                    Cancel
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>
