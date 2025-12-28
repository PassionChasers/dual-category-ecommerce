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
             <!-- Search Form -->
            <input type="text" id="search" name="search" placeholder="Search Orders by ProductName or CustomerName..." value="{{ request('search') }}"
                class="flex-1 min-w-[150px] border rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            <select id="onlineStatus" name="onlineStatus" class="flex-shrink-0 border rounded-md px-3 py-2 text-sm">
                <option value="">All Status</option>
                <option value="true" {{ request('onlineStatus')=='true' ? 'selected' : '' }}>Online</option>
                <option value="false" {{ request('onlineStatus')=='false' ? 'selected' : '' }}>Offline</option>
            </select>
            {{-- <form method="GET" action="{{ route('customers.index') }}"
                class="flex flex-col md:flex-row md:items-center gap-2 w-full md:w-auto">

                <input type="text"
                    name="search"
                    placeholder="Search by Customer Name..."
                    value="{{ request('search') }}"
                    class="flex-1 min-w-[150px] border rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">

                <select name="onlineStatus"
                        class="border rounded-md px-3 py-2 text-sm">
                    <option value="">All Status</option>
                    <option value="true" {{ request('onlineStatus') == 'online' ? 'selected' : '' }}>Online</option>
                    <option value="false" {{ request('onlineStatus') == 'offline' ? 'selected' : '' }}>Offline</option>
                </select>

                <!-- Search Button -->
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    <i class="fas fa-search"></i>
                </button>
            </form> --}}

            <button id="new-user-button" class="iw-full md:w-[240px] inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none" disabled>
                <i class="fas fa-plus mr-1"></i> New Customer
            </button>
        </div>
    </div>

    <!-- Table -->
    <div  id="tableData" class="bg-white shadow rounded-lg overflow-hidden">
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
                                data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-isactive="{{ $user->IsActive ? '1' : '0' }}"
                                data-contact_number="{{ $user->contact_number }}" data-address="{{ $user->address }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('customers.destroy', $user->id) }}" class="inline delete-form" data-status="{{ $user->role }}">
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
                    <input type="text" name="contact_number" id="customer_contact_number" value=" "
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" name="address" id="customer_address" value=" "
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="IsActive" id="IsActive" value="1"  
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="IsActive" class="text-sm font-medium text-gray-700">
                        Active
                    </label>
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
        document.addEventListener('click', function () {
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
            const IsActiveInput = document.getElementById('IsActive');
            const emailInput = document.getElementById('customer-email');
            const passwordInput = document.getElementById('customer-password');


            // Open modal for create
            newBtn.addEventListener('click', () => {
                modalTitle.innerText = 'New Customer';
                form.action = "{{ route('customers.store') }}";
                methodInput.value = 'POST';
                nameInput.value = '';
                addressInput.value='';
                IsActiveInput.checked = false;
                contactNumberInput.value = '';
                emailInput.value = '';
                passwordInput.value = '';
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
                    IsActiveInput.checked = btn.dataset.isactive === '1';
                    contactNumberInput.value = btn.dataset.contact_number;
                    emailInput.value = btn.dataset.email;
                    passwordInput.value = ''; // leave blank
                    modal.classList.remove('hidden');
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


    <!-- AJAX Script -->
    <script>

        // Function to set input values from URL parameters
        function setInputsFromUrl(url) {
            const params = new URLSearchParams(url.split('?')[1] || '');

            if (params.has('search')) {
                document.getElementById('search').value = params.get('search');
            }

            if (params.has('onlineStatus')) {
                document.getElementById('onlineStatus').value = params.get('onlineStatus');
            }
        }

        // Function to fetch data via AJAX
        function fetchData(url = null) {
            const search = document.getElementById('search').value;
            const onlineStatus = document.getElementById('onlineStatus').value;

            let fetchUrl = url 
                ? url 
                : `?search=${search}&onlineStatus=${onlineStatus}`;

            //Keep inputs filled when clicking pagination
            if (url) {
                setInputsFromUrl(url);
            }

            fetch(fetchUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(data => {
                document.getElementById('tableData').innerHTML = data;

                // Restore input values after replacing table (important)
                if (url) setInputsFromUrl(url);
            });
        }

        //Live search
        document.getElementById('search').addEventListener('keyup', function () {
            fetchData();
        });

        //Status filter
        document.getElementById('onlineStatus').addEventListener('change', function () {
            fetchData();
        });

        //AJAX pagination (IMPORTANT)
        document.addEventListener('click', function (e) {
            const pageLink = e.target.closest('.pagination a');
            if (pageLink) {
                e.preventDefault();
                fetchData(pageLink.getAttribute('href'));
            }
            
            //For Delete Confirmation
            const form = e.target.closest('.delete-form');
            if (form) {
                e.preventDefault();

                const status = form.dataset.status;
                const blockedStatuses = ['admin'];

                if (blockedStatuses.includes(status)) {
                    Swal.fire({
                        title: 'Action Not Allowed!',
                        text: 'This user cannot be deleted in its current role.',
                        icon: 'warning',
                        confirmButtonColor: '#6c757d'
                    });
                    return;
                }
                Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete this User!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    </script>
@endpush