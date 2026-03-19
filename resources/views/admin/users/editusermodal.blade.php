{{-- Modal --}}
<div id="edit-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <!-- Overlay -->
        <div id="editOverlay" class="fixed inset-0 bg-blue-950/40"></div>

        <!-- Modal content -->
        <div class=" bg-white rounded-lg shadow-xl  transform transition-all max-w-lg w-full relative">
            <div class="flex items-center justify-between bg-indigo-600 px-6 py-4 rounded-t-lg">
                <h3 class="text-lg font-medium text-white" id="modal-title"></h3>
                <button type="button" id="edit-close-btn" class=" text-white hover:text-red-500">
                    <i class="fas fa-times text-lg"></i>
                </button>
    
                
            </div>

            <form id="customer-form" method="POST" class="space-y-4 px-6 mt-2">
                @csrf
                <input type="hidden" id="form-method" name="_method" value="POST">
                <input type="hidden" name="search" id="current-search" value="{{ request('search') }}">
                <input type="hidden" name="onlineStatus" id="current-onlineStatus" value="{{ request('onlineStatus') }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="customer-name"
                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="customer-email"
                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>

                {{-- <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="customer-password" placeholder="Leave blank to keep unchanged"
                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div> --}}

                <div>
                    <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                    <input type="text" name="contact_number" id="customer_contact_number"
                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>

                {{-- <div>
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" name="address" id="customer_address"
                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div> --}}

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="IsActive" id="IsActive" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="IsActive" class="text-sm font-medium text-gray-700">Active</label>
                </div>

                <div class="flex justify-end space-x-2 mb-4">
                    <button type="button" id="edit-cancel-btn" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-red-500 hover:text-white">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>