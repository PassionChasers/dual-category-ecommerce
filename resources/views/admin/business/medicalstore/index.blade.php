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
                <div class=" group border b rounded-lg focus-within:border-2 ">
                    <input type="text" id="search" name="search"
                        placeholder="Search by Name, PAN, LicenseNumber, GSTIN..." value="{{ request('search') }}"
                        class="border border-none focus:outline-none px-2 py-2 ">
                    <button type="button" id="search_icon" class="px-3 py-2 rounded-r-lg bg-gray-200 hover:bg-gray-400 ">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <select id="onlineStatus" name="onlineStatus" class="px-3 py-2 border rounded-md text-sm">
                    <option value="">All Status</option>
                    <option value="true" {{ request('onlineStatus') == 'true' ? 'selected' : '' }}>Online</option>
                    <option value="false" {{ request('onlineStatus') == 'false' ? 'selected' : '' }}>Offline</option>
                </select>

                <select id="per_page" name="per_page" class="px-3 py-2 border rounded-md text-sm">
                    @foreach ([5, 10, 25, 50] as $p)
                        <option value="{{ $p }}" {{ request('per_page', 10) == $p ? 'selected' : '' }}>
                            {{ $p }}</option>
                    @endforeach
                </select>

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


    {{-- Register new store Modal which submit form in api  --}}
    <div id="storeModal" class="fixed inset-0  hidden items-center justify-center z-50">
        <!-- Overlay -->
        <div id="editOverlay" class="fixed inset-0 bg-blue-950/40 backdrop-blur-[2px] "></div>

        <div class="bg-white w-full max-w-2xl overflow-y-auto max-h-[90vh] rounded-lg relative">

            <div class="flex items-center justify-between rounded-t-lg bg-indigo-600 px-6 py-4 mb-4">
                <h2 class="text-xl font-bold text-white">Register Medical Store</h2>
                <button onclick="closeStoreModal()" class="text-white hover:text-red-500 text-3xl">&times;</button>
            </div>

            <form id="storeForm" class="space-y-3">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 px-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Name<span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="storeName" placeholder="Enter Store Name"
                            class="w-full border border-gray-400 p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Admin Name<span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="adminName" placeholder="Enter Admin Name"
                            class="w-full border border-gray-400 p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email<span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="adminEmail" placeholder="example@gmail.com"
                            class="w-full border border-gray-400 p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Password<span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="adminPassword" placeholder="Enter Password"
                            class="w-full border border-gray-400 p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Phone<span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="adminPhone" placeholder="+977 98XXXXXXXX"
                            class="w-full border border-gray-400 p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Store Address<span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="storeAddress" placeholder="Enter Store Address"
                            class="w-full border border-gray-400 p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            License Number<span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="licenseNumber" placeholder="License Number"
                            class="w-full border border-gray-400 p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            GSTIN<span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="gstin" placeholder="GSTIN"
                            class="w-full border border-gray-400 p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            PAN<span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="pan" placeholder="PAN"
                            class="w-full border border-gray-400 p-2 rounded" required>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Open Time<span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="openTime" class="w-full border border-gray-400 p-2 rounded"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Close Time<span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="closeTime" class="w-full border border-gray-400 p-2 rounded"
                                required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Fee (Rs.)</label>
                            <input type="number" name="deliveryFee" placeholder="Delivery Fee"
                                class="w-full border border-gray-400 p-2 rounded">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Order (Rs.)</label>
                            <input type="number" name="minOrder" placeholder="Minimum Order"
                                class="w-full border border-gray-400 p-2 rounded">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                            <input type="number" step="any" name="latitude" placeholder="Latitude"
                                class="w-full border border-gray-400 p-2 rounded">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                            <input type="number" step="any" name="longitude" placeholder="Longitude"
                                class="w-full border border-gray-400 p-2 rounded">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-3 mb-3 px-6">
                    <button type="button" onclick="closeStoreModal()"
                        class="px-4 py-2 rounded-lg text-black bg-gray-300 hover:bg-red-500 hover:text-white">
                        Cancel
                    </button>
                    <button type="submit" id="storeSubmitBtn"
                        class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded flex items-center justify-center gap-2">
                        <span id="btnText">Save Store</span>
                        <svg id="btnSpinner" class="w-5 h-5 animate-spin hidden" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- OTP MODAL -->
    <div id="otpModal"
        class="fixed inset-0 bg-indigo-950/40 backdrop-blur-[2px] hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-sm p-6 relative">
            <button onclick="closeOtpModal()" class="absolute top-2 right-3 text-gray-500 text-xl">&times;</button>
            <h3 class="text-lg font-semibold mb-3 text-center">Verify Email</h3>

            <p class="text-sm text-gray-600 text-center mb-3">
                Verification code sent to<br>
                <strong id="maskedEmail"></strong>
            </p>

            <input type="hidden" id="otpEmail">
            <input type="text" id="otpCode" maxlength="6"
                class="w-full border px-3 py-2 rounded text-center tracking-widest text-lg" placeholder="Enter OTP">

            <button id="verifyOtpBtn" onclick="verifyOtp()"
                class="w-full mt-4 bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
                Verify
            </button>

            <button id="resendOtpBtn" onclick="resendOtp()"
                class="w-full mt-2 bg-gray-500 text-white py-2 rounded hover:bg-gray-600">
                Resend OTP (<span id="resendTimer">30</span>s)
            </button>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="edit-modal" class="fixed inset-0  hidden items-center justify-center z-50">

        <!-- Overlay -->
        <div id="editOverlay" class="fixed inset-0 bg-blue-950/40 backdrop-blur-[2px] ">
        </div>

        <div class="bg-white w-full max-w-2xl overflow-y-auto max-h-[90vh] rounded-lg relative">

            <div class="flex items-center justify-between rounded-t-lg bg-indigo-600 px-6 py-4 mb-4">
                <h3 class="text-xl font-bold text-white" id="modal-title"></h3>
                <button type="button" id="edit-close-btn"
                    class="text-white hover:text-red-500 text-3xl">
                    &times;
                </button>
            </div>

            <form id="customer-form" method="POST" class="space-y-4">
                @csrf

                <input type="hidden" id="form-method" name="_method" value="POST">
                <input type="hidden" name="search" id="current-search" value="{{ request('search') }}">
                <input type="hidden" name="onlineStatus" id="current-onlineStatus" value="{{ request('onlineStatus') }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 px-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Business Name<span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="storeName" id="businessName" placeholder="Enter Store Name"
                            class="w-full border border-gray-400 p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Admin Name<span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="adminName" id="businessAdminName" placeholder="Enter Admin Name"
                            class="w-full border border-gray-400 p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email<span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="adminEmail" id="businessAdminEmail" placeholder="example@gmail.com"
                            class="w-full border border-gray-400 p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Phone<span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="adminPhone" id="businessAdminContact"
                            placeholder="+977 98XXXXXXXX" class="w-full border border-gray-400 p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Store Address<span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="storeAddress" id="businessAddress" placeholder="Enter Store Address"
                            class="w-full border border-gray-400 p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            License Number<span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="licenseNumber" id="licenseNumber" placeholder="License Number"
                            class="w-full border border-gray-400 p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            GSTIN<span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="gstin" id="gstin" placeholder="GSTIN"
                            class="w-full border border-gray-400 p-2 rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            PAN<span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="pan" id="pan" placeholder="PAN"
                            class="w-full border border-gray-400 p-2 rounded" required>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Open Time<span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="openTime" id="openTime" class="w-full border border-gray-400 p-2 rounded"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Close Time<span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="closeTime" id="closeTime" class="w-full border border-gray-400 p-2 rounded"
                                required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Fee (Rs.)</label>
                        <input type="number" name="deliveryFee" id="deliveryFee" placeholder="Delivery Fee"
                            class="w-full border border-gray-400 p-2 rounded">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Order (Rs.)</label>
                        <input type="number" name="minOrder" id="minimumOrder" placeholder="Minimum Order"
                            class="w-full border border-gray-400 p-2 rounded">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                        <input type="number" step="any" name="latitude" id="latitude" placeholder="Latitude"
                            class="w-full border border-gray-400 p-2 rounded">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                        <input type="number" step="any" name="longitude" id="longitude" placeholder="Longitude"
                            class="w-full border border-gray-400 p-2 rounded">
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="IsActive" id="isActive" value="1"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="IsActive" class="text-sm font-medium text-gray-700">Active</label>
                    </div>

                </div>

                <div class="flex justify-end gap-2 pt-3 mb-3 px-6">
                    <button type="button" id="edit-cancel-btn"
                        class="px-4 py-2 rounded-lg text-black bg-gray-300 hover:bg-red-500 hover:text-white">Cancel</button>
                    <button type="submit" id="storeSubmitBtn"
                        class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded flex items-center justify-center gap-2">
                        <span id="btnText">Save Store</span>
                        <svg id="btnSpinner" class="w-5 h-5 animate-spin hidden" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

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

            // Event delegation for edit buttons (works after AJAX too)
            document.addEventListener('click', function(e) {

                const btn = e.target.closest('.edit-btn');
                if (!btn) return;

                modalTitle.innerText = 'Edit Medical Store';
                form.action = `/users/update/${btn.dataset.id}`;
                methodInput.value = 'PUT';

                // Auto-fill all inputs based on ID
                form.querySelectorAll('input, select, textarea').forEach(input => {

                const id = input.id;
                if (!id) return;

                const value = btn.dataset[id];

                if (value !== undefined) {
                    if (input.type === 'checkbox') {
                        input.checked = value == '1' || value === true;
                    } else {
                        input.value = value;
                    }
                }
            });

                editModal.classList.remove('hidden');
                editModal.classList.add('flex');

            });


            // Delete confirmation
            document.addEventListener('click', function(e) {
                const form = e.target.closest('.delete-form');
                if (form) {
                    e.preventDefault();
                    const status = form.dataset.status;
                    if (['admin'].includes(status)) {
                        Swal.fire({
                            title: 'Action Not Allowed!',
                            text: 'This user cannot be deleted.',
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
                        if (result.isConfirmed) form.submit();
                    });
                }
            });

            ///for ajjax filter
            const searchInput = document.getElementById('search');
            const statusSelect = document.getElementById('onlineStatus');
            const perPageSelect = document.getElementById('per_page');
            const searchIcon = document.getElementById('search_icon');

            function fetchData(url = null) {
                const search = searchInput.value;
                const onlineStatus = statusSelect.value;
                const perPage = perPageSelect.value;

                let fetchUrl = url ?
                    url :
                    `?search=${encodeURIComponent(search)}&onlineStatus=${onlineStatus}&per_page=${perPage}`;

                fetch(fetchUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.text())
                    .then(html => {
                        document.getElementById('tableData').innerHTML = html;
                    });
            }

            /*Live search */
            // searchInput.addEventListener('keyup', () => fetchData());

            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    fetchData();
                }
            });

            // search icon click
            searchIcon.addEventListener('click', function(e) {
                fetchData();
            });

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
        document.getElementById('storeForm').addEventListener('submit', function(e) {
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

            const data = {
                storeName: form.storeName.value,
                adminName: form.adminName.value,
                adminEmail: form.adminEmail.value,
                adminPassword: form.adminPassword.value,
                adminPhone: form.adminPhone.value,
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
            };

            fetch('{{ route('medicalStores.store') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    // body: data
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(response => {
                    if (response.success) {

                        closeStoreModal();

                        const email = form.adminEmail.value;
                        openOtpModal(email);

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Registration failed. OTP not sent.'
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

        // OTP Modal Logic
        let resendCountdown = 60;
        let resendInterval;

        function maskEmail(email) {
            const [name, domain] = email.split('@');
            return name.substring(0, 2) + '*'.repeat(name.length - 2) + '@' + domain;
        }

        function openOtpModal(email) {
            const modal = document.getElementById('otpModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.getElementById('otpEmail').value = email;
            document.getElementById('maskedEmail').innerText = maskEmail(email);
            document.getElementById('otpCode').focus();

            startOtpTimer();
        }

        function closeOtpModal() {
            const modal = document.getElementById('otpModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            clearInterval(resendInterval);
            resendCountdown = 60;
            document.getElementById('resendTimer').innerText = resendCountdown;
            document.getElementById('resendOtpBtn').disabled = false;
        }

        // Timer for resend button
        function startOtpTimer() {
            const resendBtn = document.getElementById('resendOtpBtn');
            resendBtn.disabled = true;
            resendCountdown = 60;
            document.getElementById('resendTimer').innerText = resendCountdown;

            resendInterval = setInterval(() => {
                resendCountdown--;
                document.getElementById('resendTimer').innerText = resendCountdown;
                if (resendCountdown <= 0) {
                    clearInterval(resendInterval);
                    resendBtn.disabled = false;
                    document.getElementById('resendTimer').innerText = '0';
                }
            }, 1000);
        }

        // Verify OTP
        function verifyOtp() {
            const email = document.getElementById('otpEmail').value;
            const code = document.getElementById('otpCode').value;

            if (code.length !== 6) return Swal.fire('Error', 'Enter valid 6-digit OTP', 'error');

            fetch('{{ route('medicalStores.verifyOtp') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email,
                        otp: code
                    })
                })
                .then(res => res.json())
                .then(res => {
                    if (!res.success) return Swal.fire('Error', res.message, 'error');
                    closeOtpModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Medicalstore created successfully',
                        text: res.message
                    }).then(() => {
                        window.location.href = res.redirect;
                    });
                })
                .catch(() => Swal.fire('Error', 'Verification failed', 'error'));
        }

        // Resend OTP
        function resendOtp() {
            const email = document.getElementById('otpEmail').value;
            const btn = document.getElementById('resendOtpBtn');

            btn.disabled = true;

            fetch('{{ route('medicalStores.resendOtp') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email
                    })
                })
                .then(res => res.json())
                .then(res => {
                    Swal.fire(res.success ? 'Success' : 'Error', res.message, res.success ? 'success' : 'error');
                    if (res.success) startOtpTimer();
                })
                .catch(() => {
                    Swal.fire('Error', 'Could not resend OTP', 'error');
                    btn.disabled = false;
                });
        }
    </script>
@endpush
