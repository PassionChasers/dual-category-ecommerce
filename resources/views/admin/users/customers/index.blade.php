@extends('layouts.admin.app')
@section('title', 'Admin | User Management')

@push('styles')
@endpush

@section('contents')
<div class="flex-1 p-4 md:p-6 bg-gray-50">
    <div class="mb-6 flex justify-between items-center flex-wrap">
        <div class="mb-2 md:mb-0">
            <h2 class="text-2xl font-bold text-gray-800">Customer Management</h2>
            <p class="text-gray-600">Manage all Customers</p>
        </div>
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2 w-full md:w-auto">
            <form method="GET" class="flex flex-wrap w-full gap-2">
                <input type="text" name="search" 
                {{-- value="{{ $search }}"  --}}
                placeholder="Search users..."
                    class="flex-1 min-w-[150px] border rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <button type="submit" class="flex-shrink-0 px-3 py-2 bg-gray-100 text-sm rounded hover:bg-gray-200">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <button id="new-user-button" class="iw-full md:w-[240px] inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none" disabled>
                <i class="fas fa-plus mr-1"></i> New Customer
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-medium text-gray-900">Customer List</h3>
        </div>
        <div class="overflow-x-auto">
            <table id="taskTable" class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">#id</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Name</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Email</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Contact</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Address</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody id="userTableBody" class="divide-y divide-gray-200">
                    @forelse($users as $user)
                    <tr>
                        <td class="px-4 py-2">
                            {{$user->id}}
                        </td>
                        <td class="px-4 py-2 font-semibold text-gray-800">
                            {{ $user->name }}
                        </td>
                        <td class="px-4 py-2 text-gray-600">
                            {{ $user->email }}
                        </td>
                        <td class="px-4 py-2 text-gray-600">
                            {{ $user->contact_number?? '-' }}
                        </td>
                        <td class="px-4 py-2 text-gray-600">
                            {{ $user->address ?? '-' }}
                        </td>
                        <td class="px-4 py-2 flex space-x-2">
                            <button class="edit-btn text-indigo-600 hover:text-indigo-800" data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}" data-email="{{ $user->email }}"
                                data-contact_number="{{ $user->contact_number }}" data-address="{{ $user->address }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('customers.destroy', $user->id) }}" class="inline delete-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">No users found.</td>
                    </tr>
                    @endforelse
                    
                </tbody>
            </table>
        </div>
        <div class="flex flex-col md:flex-row items-center justify-between px-6 py-4 bg-gray-50 border-t">

            <!-- Left: Results info -->
            <div id="resultsInfo" class="text-gray-700 text-sm">
                Showing <strong>{{ $users->firstItem() ?? 0 }}</strong> to <strong>{{ $users->lastItem() ?? 0 }}</strong> of <strong>{{ $users->total() }}</strong> results
            </div>
        
            <!-- Right: Pagination buttons -->
           <div class="mt-3 px-4">
                {{ $users->links() }}
            </div>
            
        </div>
    </div>
</div>


{{--------------------MODAL----------------
--------------------------------------- --}}

<div id="customer-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-500 opacity-75"></div>

        <!-- Modal content with border -->
        <div class="bg-white rounded-lg shadow-xl border border-gray-300 transform transition-all max-w-lg w-full p-6 relative">
            <!-- Close Button -->
            <button type="button" id="close-modal-btn" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-lg"></i>
            </button>

            <!-- Modal Title -->
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="modal-title"></h3>

            <!-- Form -->
            <form id="customer-form" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" id="form-method" name="_method" value="POST">

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="customer-name"  value=" "
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="customer-email" value=" "
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="customer-password" placeholder="Enter Password or While updating keep blank for no change"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <!-- Contact -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                    {{-- <select name="designation" id="user-designation"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                        <option value="">Select Designation</option>
                        @foreach($users as $user)
                        <option value="{{ $user->designation }}">{{ $user->designation }}</option>
                        @endforeach
                    </select> --}}
                    <input type="text" name="contact_number" id="customer_contact_number" value=" "
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    {{-- <select name="department" id="user-department"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        >
                        <option value="">Select Department</option>
                        @foreach($users as $user)
                        <option value="{{ $user->department }}">{{ $user->department }}</option>
                        @endforeach
                    </select> --}}
                    <input type="text" name="address" id="customer_address" value=" "
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-2">
                    <button type="button" id="cancel-btn"
                        class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('customer-modal');
    const newBtn = document.getElementById('new-user-button');
    const cancelBtn = document.getElementById('cancel-btn');
    const closeBtn = document.getElementById('close-modal-btn');
    const form = document.getElementById('customer-form');
    const modalTitle = document.getElementById('modal-title');
    const methodInput = document.getElementById('form-method');
    const nameInput = document.getElementById('customer-name');
    const contactNumberInput = document.getElementById('customer_contact_number');
    const addressInput = document.getElementById('customer_address');
    const emailInput = document.getElementById('customer-email');
    const passwordInput = document.getElementById('customer-password');


    // Open modal for create
    newBtn.addEventListener('click', () => {
        modalTitle.innerText = 'New Customer';
        form.action = "{{ route('customers.store') }}";
        methodInput.value = 'POST';
        nameInput.value = '';
        addressInput.value='';
        contactNumberInput.value = '';
        emailInput.value = '';
        passwordInput.value = '';
        // designationInput.value = '';
        modal.classList.remove('hidden');
    });

    // Cancel and close
    cancelBtn.addEventListener('click', () => modal.classList.add('hidden'));
    closeBtn.addEventListener('click', () => modal.classList.add('hidden'));

    // Open modal for edit
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            modalTitle.innerText = 'Edit User';
            form.action = `/customers/update/${btn.dataset.id}`;
            methodInput.value = 'PUT';
            nameInput.value = btn.dataset.name;
            addressInput.value = btn.dataset.address;
            contactNumberInput.value = btn.dataset.contact_number;
            emailInput.value = btn.dataset.email;
            passwordInput.value = ''; // leave blank
            // designationInput.value = btn.dataset.designation;
            // departmentInput.value = btn.dataset.department;
            modal.classList.remove('hidden');
        });
    });

    // SweetAlert for delete confirmation
    document.querySelectorAll('.delete-form').forEach(f => {
        f.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) f.submit();
            });
        });
    });
});


// Toast alerts
@if(session('success'))
Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: "{{ session('success') }}", showConfirmButton: false, timer: 3000, timerProgressBar: true });
@endif

@if(session('error'))
Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: "{{ session('error') }}", showConfirmButton: false, timer: 3000, timerProgressBar: true });
@endif

@if($errors->any())
Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: "{{ $errors->first() }}", showConfirmButton: false, timer: 3000, timerProgressBar: true });
@endif
</script>
@endpush