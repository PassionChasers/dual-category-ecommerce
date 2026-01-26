@extends('layouts.admin.app')
@section('title', 'Admin | Medicalstore Business Management')

@push('styles')
@endpush

@section('contents')

    <div class="flex-1 overflow-auto bg-gray-50 p-4 md:p-6">
        <div class="mb-6 flex justify-between items-center flex-wrap">
            <div class="mb-2 md:mb-0">
                <h2 class="text-2xl font-bold text-gray-800">Medicalstore Business Management</h2>
                <p class="text-gray-600">Manage all Medicalstore Businesses</p>
            </div>

            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2 w-full md:w-auto">
                <!-- Search Form -->
                <input type="text" id="search" name="search" placeholder="Search by Name, PAN, LicenseNumber, GSTIN..." 
                    value="{{ request('search') }}"
                    class="flex-1 min-w-[150px] border rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">

                <select id="onlineStatus" name="onlineStatus" class="flex-shrink-0 border rounded-md px-3 py-2 text-sm">
                    <option value="">All Status</option>
                    <option value="true" {{ request('onlineStatus')=='true' ? 'selected' : '' }}>Online</option>
                    <option value="false" {{ request('onlineStatus')=='false' ? 'selected' : '' }}>Offline</option>
                </select>

                <select id="per_page" name="per_page" class="px-3 py-2 border rounded-md cursor-pointer">
                    @foreach([5,10,25,50] as $p)
                        <option value="{{ $p }}" {{ request('per_page',10)==$p ? 'selected':'' }}>{{ $p }}</option>
                    @endforeach
                </select>

                {{-- This button for normal modal --}}
                {{-- <button id="openAdminModal" class="w-full md:w-[240px] inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <i class="fas fa-plus mr-1"></i> New Medicalstore Business
                </button> --}}

                {{-- This button for modal which work through api form submit --}}
                <button onclick="openStoreModal()"
                    class="w-full md:w-[240px] inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <i class="fas fa-plus mr-1"></i> Register Medical Store
                </button>
            </div>
        </div>

        <!-- Table -->
        <div id="tableData" class="bg-white shadow rounded-lg overflow-hidden">
            @include('admin.business.medicalstore.searchedMedicalstore', ['users' => $users])
        </div>
    </div>


    {{-- Normal modal  --}}
    <!-- Add Business Modal -->
    {{-- <div id="Add-Business-Modal" class="fixed inset-0 bg-indigo-100 bg-opacity-50 hidden items-center justify-center z-50 p-4">

        <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg overflow-hidden max-h-[90vh] flex flex-col">

            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800">Add New Medicalstore</h3>
                <button id="add-close-btn" class="text-gray-500 hover:text-red-600 text-2xl">&times;</button>
            </div>

            <!-- Form Body -->
            <div class="p-6 overflow-y-auto flex-1">
                <form action="{{ route('medicalStores.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Display errors -->
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium">
                                Medicalstore Name
                                <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" class="input border rounded p-2 w-full" required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium">
                                Email
                                <span class="text-red-400">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" class="input border rounded p-2 w-full" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">
                                Address
                                <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="Address" value="{{ old('Address') }}" class="input border rounded p-2 w-full" required>
                        </div>

                        <!-- Additional Fields (optional) -->
                        <div>
                            <label class="block text-sm font-medium">
                                License Number
                                <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="LicenseNumber" value="{{ old('LicenseNumber') }}" class="input border rounded p-2 w-full" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">
                                GSTIN
                                <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="GSTIN" value="{{ old('GSTIN') }}" class="input border rounded p-2 w-full" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">
                                PAN
                                <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="PAN" value="{{ old('PAN') }}" class="input border rounded p-2 w-full" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">
                                Open Time
                                <span class="text-red-400">*</span>
                            </label>
                            <input type="time" name="OpenTime" value="{{ old('OpenTime') }}" class="input border rounded p-2 w-full" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">
                                Close Time
                                <span class="text-red-400">*</span>
                            </label>
                            <input type="time" name="CloseTime" value="{{ old('CloseTime') }}" class="input border rounded p-2 w-full" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">
                                Delivery Radius (Km)
                                <span class="text-red-400">*</span>
                            </label>
                            <input type="number" step="0.01" name="RadiusKm" value="{{ old('RadiusKm') }}" class="input border rounded p-2 w-full" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">
                                Delivery Fee
                                <span class="text-red-400">*</span>
                            </label>
                            <input type="number" step="0.01" name="DeliveryFee" value="{{ old('DeliveryFee') }}" class="input border rounded p-2 w-full" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">
                                Minimum Order
                                <span class="text-red-400">*</span>
                            </label>
                            <input type="number" step="0.01" name="MinOrder" value="{{ old('MinOrder') }}" class="input border rounded p-2 w-full" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">
                                Latitude
                                <span class="text-red-400">*</span>
                            </label>
                            <input type="number" step="0.000001" value="{{ old('Lattitude') }}" name="Latitude" class="input border rounded p-2 w-full" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">
                                Longitude
                                <span class="text-red-400">*<span>
                            </label>
                            <input type="number" step="0.000001" name="Longitude" value="{{ old('Longitude') }}" class="input border rounded p-2 w-full" required>
                        </div>

                        <!-- IsActive -->
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="IsActive" value="1" {{ old('IsActive', 1) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label class="text-sm font-medium">Active</label>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end gap-2 mt-4 border-t pt-4">
                        <button type="button" id="add-cancel-btn" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                            Save Business
                        </button>
                    </div>

                </form>
            </div>

        </div>

    </div>

    @if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addModal = document.getElementById('Add-Business-Modal');
            addModal.classList.remove('hidden');
            addModal.classList.add('flex');
        });
    </script>
    @endif --}}


    {{-- Register new store Modal which submit form in api  --}}
    <div id="storeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white w-full max-w-2xl rounded-lg p-6">
            <h2 class="text-xl font-bold mb-4">Register Medical Store</h2>

            <form id="storeForm" class="space-y-3">
                @csrf

                <input type="text" name="storeName" placeholder="Store Name" class="w-full border p-2 rounded" required>
                <input type="text" name="Name" placeholder="Admin Name" class="w-full border p-2 rounded" required>
                <input type="email" name="Email" placeholder="Admin Email" class="w-full border p-2 rounded" required>
                <input type="password" name="Password" placeholder="Password" class="w-full border p-2 rounded" required>
                <input type="text" name="Phone" placeholder="Phone" class="w-full border p-2 rounded" required>

                <input type="text" name="storeAddress" placeholder="Store Address" class="w-full border p-2 rounded">
                <input type="text" name="licenseNumber" placeholder="License Number" class="w-full border p-2 rounded">
                <input type="text" name="gstin" placeholder="GSTIN">
                <input type="text" name="pan" placeholder="PAN">

                <div class="grid grid-cols-2 gap-2">
                    <input type="time" name="openTime" class="border p-2 rounded">
                    <input type="time" name="closeTime" class="border p-2 rounded">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <input type="number" name="deliveryFee" placeholder="Delivery Fee" class="border p-2 rounded">
                    <input type="number" name="minOrder" placeholder="Minimum Order" class="border p-2 rounded">
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <input type="number" step="any" name="latitude" placeholder="Latitude" class="border p-2 rounded">
                    <input type="number" step="any" name="longitude" placeholder="Longitude" class="border p-2 rounded">
                </div>

                <div class="flex justify-end gap-2 pt-3">
                    <button type="button" onclick="closeStoreModal()" class="px-4 py-2 border rounded">
                        Cancel
                    </button>
                    {{-- <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                        Save Store
                    </button> --}}

                    <button
                        type="submit"
                        id="storeSubmitBtn"
                        class="bg-green-600 text-white px-4 py-2 rounded flex items-center justify-center gap-2"
                    >
                        <span id="btnText">Save Store</span>
                        <svg id="btnSpinner" class="w-5 h-5 animate-spin hidden"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- OTP MODAL -->
    <div id="otpModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-sm p-6 relative">

            <button onclick="closeOtpModal()" class="absolute top-2 right-3 text-gray-500 text-xl">&times;</button>

            <h3 class="text-lg font-semibold mb-3 text-center">Verify Email</h3>

            <p class="text-sm text-gray-600 text-center mb-3">
                Verification code sent to<br>
                <strong id="maskedEmail"></strong>
            </p>

            <input type="email" id="otpEmail" required>

            <input
                type="text"
                id="otpCode"
                maxlength="6"
                class="w-full border px-3 py-2 rounded text-center tracking-widest text-lg"
                placeholder="Enter OTP"
            >

            <button
                onclick="verifyOtp()"
                class="w-full mt-4 bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700"
            >
                Verify
            </button>

            <!-- Resend -->
            {{-- <div class="text-center mt-4">
                <button
                    id="resendOtpBtn"
                    onclick="resendOtp()"
                    class="text-sm text-indigo-600 hover:underline disabled:text-gray-400"
                >
                    Resend Code
                </button>

                <p id="otpTimer" class="text-xs text-gray-500 mt-1 hidden">
                    Resend available in <span id="otpSeconds">30</span>s
                </p>
            </div> --}}
        </div>
    </div>





    {{-- Edit Modal --}}
    <div id="edit-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">

        <div class="flex items-center justify-center min-h-screen px-4">

            <!-- Overlay -->
            <div class="fixed inset-0 bg-gray-500 opacity-75"></div>

            <!-- Modal content -->
            <div class="bg-white rounded-lg shadow-xl border border-gray-300 transform transition-all max-w-lg w-full p-6 relative">
                <button type="button" id="edit-close-btn" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-lg"></i>
                </button>

                <h3 class="text-lg font-medium text-gray-900 mb-4" id="modal-title"></h3>

                <form id="customer-form" method="POST" class="space-y-4">
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

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="customer-password" placeholder="Leave blank to keep unchanged"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

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

                    <div class="flex justify-end space-x-2">
                        <button type="button" id="edit-cancel-btn" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            //For normal modal 
            // ADD MODAL
            // const addModal = document.getElementById('Add-Business-Modal');
            // const openAddBtn = document.getElementById('openAdminModal');
            // const addCloseBtn = document.getElementById('add-close-btn');
            // const addCancelBtn = document.getElementById('add-cancel-btn');

            // openAddBtn?.addEventListener('click', () => {
            //     addModal.classList.remove('hidden');
            //     addModal.classList.add('flex');
            // });

            // [addCloseBtn, addCancelBtn].forEach(btn => {
            //     btn?.addEventListener('click', () => {
            //         addModal.classList.add('hidden');
            //         addModal.classList.remove('flex');
            //     });
            // });



            // EDIT MODAL
            const editModal = document.getElementById('edit-modal');
            const editCloseBtn = document.getElementById('edit-close-btn');
            const editCancelBtn = document.getElementById('edit-cancel-btn');

            [editCloseBtn, editCancelBtn].forEach(btn => {
                btn?.addEventListener('click', () => {
                    editModal.classList.add('hidden');
                });
            });

            //for edit modal
            const editmodal = document.getElementById('edit-modal');
            const form = document.getElementById('customer-form');
            const modalTitle = document.getElementById('modal-title');
            const methodInput = document.getElementById('form-method');
            const nameInput = document.getElementById('customer-name');
            const IsActiveInput = document.getElementById('IsActive');
            const contactNumberInput = document.getElementById('customer_contact_number');
            const emailInput = document.getElementById('customer-email');
            const passwordInput = document.getElementById('customer-password');

            // Event delegation for edit buttons (works after AJAX too)
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.edit-btn');
                if (btn) {
                    modalTitle.innerText = 'Edit Medicalstore';
                    form.action = `/users/update/${btn.dataset.id}`;
                    methodInput.value = 'PUT';
                    nameInput.value = btn.dataset.name;
                    // addressInput.value = btn.dataset.address;
                    IsActiveInput.checked = btn.dataset.isactive === '1';
                    contactNumberInput.value = btn.dataset.contact_number;
                    emailInput.value = btn.dataset.email;
                    passwordInput.value = '';

                    // Set hidden fields for current search/filter
                    document.getElementById('current-search').value = document.getElementById('search').value;
                    document.getElementById('current-onlineStatus').value = document.getElementById('onlineStatus').value;

                    editmodal.classList.remove('hidden');
                }
            });


            // Delete confirmation
            document.addEventListener('click', function(e) {
                const form = e.target.closest('.delete-form');
                if (form) {
                    e.preventDefault();
                    const status = form.dataset.status;
                    if (['admin'].includes(status)) {
                        Swal.fire({ title: 'Action Not Allowed!', text: 'This user cannot be deleted.', icon: 'warning', confirmButtonColor: '#6c757d' });
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
                    }).then((result) => { if (result.isConfirmed) form.submit(); });
                }
            });

            ///for ajjax filter
            const searchInput = document.getElementById('search');
            const statusSelect = document.getElementById('onlineStatus');
            const perPageSelect = document.getElementById('per_page');

            function fetchData(url = null) {
                const search = searchInput.value;
                const onlineStatus = statusSelect.value;
                const perPage = perPageSelect.value;

                let fetchUrl = url 
                    ? url 
                    : `?search=${encodeURIComponent(search)}&onlineStatus=${onlineStatus}&per_page=${perPage}`;

                fetch(fetchUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.text())
                .then(html => {
                    document.getElementById('tableData').innerHTML = html;
                });
            }

            /*Live search */
            searchInput.addEventListener('keyup', () => fetchData());

            /*Status filter */
            statusSelect.addEventListener('change', () => fetchData());

            /*pages filter */
            perPageSelect.addEventListener('change', () => fetchData());

            /*AJAX pagination */
            document.addEventListener('click', function(e) {
                const link = e.target.closest('.pagination a');
                if (link) {
                    e.preventDefault();
                    fetchData(link.getAttribute('href'));
                }
            });

        });

    </script>


    {{-- Api form submit --}}
    <script>
        function openStoreModal() {
            const modal = document.getElementById('storeModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeStoreModal() {
            const modal = document.getElementById('storeModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        document.getElementById('storeForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const form = e.target;
            const btn = document.getElementById('storeSubmitBtn');
            const btnText = document.getElementById('btnText');
            const spinner = document.getElementById('btnSpinner');

            // Disable button & show loading
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            btnText.textContent = 'Saving...';
            spinner.classList.remove('hidden');

            const data = new URLSearchParams({
                storeName: form.storeName.value,
                Name: form.Name.value,
                Email: form.Email.value,
                Password: form.Password.value,
                Phone: form.Phone.value,
                storeAddress: form.storeAddress.value,
                licenseNumber: form.licenseNumber.value,
                gstin: form.gstin.value,
                pan: form.pan.value,
                openTime: form.openTime.value,
                closeTime: form.closeTime.value,
                deliveryFee: form.deliveryFee.value,
                minOrder: form.minOrder.value,
                latitude: form.latitude.value,
                longitude: form.longitude.value,
                _token: '{{ csrf_token() }}'
            });

            fetch('{{ route("medicalStores.store") }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                },
                body: data
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    // Swal.fire({
                    //     icon: 'success',
                    //     title: 'Success',
                    //     text: 'Medical store registered successfully',
                    //     timer: 2000,
                    //     showConfirmButton: false
                    // });

                    // form.reset();
                    // closeStoreModal();
                    
                    closeStoreModal();

                    const email = form.Email.value;

                    document.getElementById('otpEmail').value = email;
                    document.getElementById('maskedEmail').innerText = maskEmail(email);

                    openOtpModal();
                    startOtpTimer();


                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Something went wrong'
                    });
                }
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'Please try again later'
                });
            })
            .finally(() => {
                // Re-enable button
                btn.disabled = false;
                btn.classList.remove('opacity-70', 'cursor-not-allowed');
                btnText.textContent = 'Save Store';
                spinner.classList.add('hidden');
            });
        });
    </script>

    {{-- OTP MODAL JS --}}
    <script>
    let otpCooldown = 30;
    let otpInterval;

    function maskEmail(email) {
        const [name, domain] = email.split('@');
        return name.substring(0, 2) + '*'.repeat(name.length - 2) + '@' + domain;
    }

    function openOtpModal() {
        const modal = document.getElementById('otpModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            document.getElementById('otpCode').focus();
        }, 3000);
    }

    function closeOtpModal() {
        document.getElementById('otpModal').classList.add('hidden');
    }
    </script>


    {{-- ajax api call for otp verification --}}
    <script>
    // function verifyOtp() {
    //     const email = document.getElementById('otpEmail').value;
    //     const code = document.getElementById('otpCode').value;

    //     if (code.length !== 6) {
    //         return Swal.fire('Error', 'Enter valid 6-digit OTP', 'error');
    //     }

    //     fetch('https://pcsdecom.azurewebsites.net/api/Auth/verify-email', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json',
    //             'Authorization': 'Bearer {{ session('jwt_token') }}'
    //         },
    //         body: JSON.stringify({ email, code })
    //     })
    //     .then(res => res.json())
    //     .then(() => {
    //         Swal.fire({
    //             icon: 'success',
    //             title: 'Email Verified',
    //             timer: 2000,
    //             showConfirmButton: false
    //         }).then(() => {
    //             location.reload(); // reload table
    //         });
    //     })
    //     .catch(() => {
    //         Swal.fire('Error', 'Invalid or expired OTP', 'error');
    //     });
    // }

    // function verifyOtp() {
    //     const email = document.getElementById('otpEmail').value;
    //     const code = document.getElementById('otpCode').value;

    //     if (code.length !== 6) {
    //         return Swal.fire('Error', 'Enter valid 6-digit OTP', 'error');
    //     }

    //     fetch('https://pcsdecom.azurewebsites.net/api/Auth/verify-email', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json'
    //         },
    //         body: JSON.stringify({ email, code })
    //     })
    //     .then(res => res.json())
    //     .then(res => {
    //         if (!res.success) {
    //             Swal.fire('Error', res.message || 'OTP expired or invalid', 'error');
    //         } else {
    //             Swal.fire({
    //                 icon: 'success',
    //                 title: 'Email Verified',
    //                 text: 'Medical store registered successfully',
    //                 timer: 2000,
    //                 showConfirmButton: false
    //             }).then(() => location.reload());
    //         }
    //     })
    //     .catch(() => {
    //         Swal.fire('Error', 'Verification failed', 'error');
    //     });
    // }
    </script>


{{-- RESEND OTP --}}
    {{-- <script>
        let otpInterval = null;
        let otpCooldown = 30;

        function startOtpTimer() {
            const btn = document.getElementById('resendOtpBtn');
            const timer = document.getElementById('otpTimer');
            const seconds = document.getElementById('otpSeconds');

            btn.disabled = true;
            timer.classList.remove('hidden');
            seconds.innerText = otpCooldown;

            otpInterval = setInterval(() => {
                otpCooldown--;
                seconds.innerText = otpCooldown;

                if (otpCooldown <= 0) {
                    clearInterval(otpInterval);
                    btn.disabled = false;
                    timer.classList.add('hidden');
                }
            }, 1000);
        }

        function resetOtpTimer() {
            clearInterval(otpInterval);
            otpCooldown = 30;
            startOtpTimer();
        }


        function resendOtp() {
            startOtpTimer();

            fetch('https://pcsdecom.azurewebsites.net/api/Auth/resend-verification', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer {{ session('jwt_token') }}'
            },
            body: JSON.stringify({
                email: document.getElementById('otpEmail').value
            })
            })
            .then(() => {
                Swal.fire('Sent', 'OTP resent successfully', 'success');
            })
            .catch(() => {
                Swal.fire('Error', 'Unable to resend OTP', 'error');
            });
        }

        function resendOtp() {
            startOtpTimer();

            fetch('https://pcsdecom.azurewebsites.net/api/Auth/resend-verification', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    email: document.getElementById('otpEmail').value
                })
            })
            .then(res => res.json())
            .then(res => {
                if (!res.success) {
                    Swal.fire('Error', res.message || 'Unable to resend OTP', 'error');
                } else {
                    Swal.fire('Sent', 'OTP resent successfully', 'success');
                }
            })
            .catch(() => {
                Swal.fire('Error', 'Server error while resending OTP', 'error');
            });
        }
    </script> --}}




@endpush
