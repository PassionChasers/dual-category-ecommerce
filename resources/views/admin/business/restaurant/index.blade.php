

@extends('layouts.admin.app')
@section('title', 'Admin | Restaurant Business Management')

@push('styles')
@endpush

@section('contents')
<div class="flex-1 overflow-auto bg-gray-50 p-4 md:p-6">
    <div class="mb-6 flex justify-between items-center flex-wrap">
        <div class="mb-2 md:mb-0">
            <h2 class="text-2xl font-bold text-gray-800">Restaurant Business Management</h2>
            <p class="text-gray-600">Manage all Restaurant Businesses</p>
        </div>

        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2 w-full md:w-auto">
            <!-- Search Form -->
                <input type="text" id="search" name="search" placeholder="Search by Name, PAN, GSTIN, FLICNo..." 
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

            <button id="open-register-form-modal" class="w-full md:w-[240px] inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                <i class="fas fa-plus mr-1"></i> New Restaurant Business
            </button>
        </div>
    </div>

    <!-- Table -->
    <div id="tableData" class="bg-white shadow rounded-lg overflow-hidden">
        @include('admin.business.restaurant.searchedRestaurants', ['users' => $users])
    </div>
</div>


<!-- Add Restaurant Modal -->
<div id="Add-Restaurant-Modal" class="fixed inset-0 bg-indigo-100 bg-opacity-50 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg overflow-y-auto max-h-[90vh]">

        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Add New Restaurant</h3>
            <button id="add-close-btn" class="text-gray-500 hover:text-red-600 text-xl">&times;</button>
        </div>

        <!-- Form -->
        <form id="restaurantForm" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Error Messages -->
                {{-- @if($errors->any())
                    <div class="md:col-span-2 p-4 bg-red-100 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif --}}

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium">
                        Restaurant Name
                        <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="Name" value="{{ old('Name') }}" class="input border rounded p-2 w-full" required>
                </div>

                {{-- Admin Name --}}
                <div>
                    <label class="block text-sm font-medium">
                        Admin Name
                        <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="AdminName" value="{{ old('AdminName') }}" class="input border rounded p-2 w-full" required>
                </div>
                

                <!-- Email (to link user) -->
                <div>
                    <label class="block text-sm font-medium">
                        Email
                        <span class="text-red-400">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input border rounded p-2 w-full" required>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium">
                        Password
                        <span class="text-red-400">*</span>
                    </label>
                    <input type="password" name="Password" value="{{ old('Password') }}" class="input border rounded p-2 w-full" required>
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-medium">
                        Phone
                        <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="Phone" value="{{ old('Phone') }}" class="input border rounded p-2 w-full" required>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-medium">
                        Address
                        <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="Address" value="{{old('Address')}}" class="input border rounded p-2 w-full" required>
                </div>

                <!-- FLICNo -->
                <div>
                    <label class="block text-sm font-medium">
                        FLIC Number
                        <span class="text-red-400">*</span></label>
                    <input type="text" name="FLICNo" value="{{ old('FLICNo') }}" class="input border rounded p-2 w-full" required>
                </div>

                <!-- GSTIN -->
                <div>
                    <label class="block text-sm font-medium">
                        GSTIN
                        <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="GSTIN" value="{{ old('GSTIN') }}" class="input border rounded p-2 w-full" required>
                </div>

                <!-- PAN -->
                <div>
                    <label class="block text-sm font-medium">
                        PAN
                        <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="PAN" value="{{ old('PAN') }}" class="input border rounded p-2 w-full" required>
                </div>

               <!-- CuisineType -->
                <div>
                    {{-- <label class="block text-sm font-medium">Cuisine Type</label> --}}
                    {{-- <input type="text" name="cuisineType" value="{{ old('CuisineType') }}" class="input border rounded p-2 w-full"> --}}
                    <select name="CuisineType" id="cuisineType">
                        <option value=" ">...Select Cuisine Type... </option>
                        <option value="Nepali">Nepali</option>
                        <option value="Indian">Indian</option>
                        <option value="Chinese">Chinese</option>
                    </select>
                </div>

                <!-- OpenTime -->
                <div>
                    <label class="block text-sm font-medium">
                        Open Time
                        <span class="text-red-400">*</span>
                    </label>
                    <input type="time" name="OpenTime" value="{{ old('OpenTime') }}" class="input border rounded p-2 w-full" required>
                </div>

                <!-- CloseTime -->
                <div>
                    <label class="block text-sm font-medium">
                        Close Time
                        <span class="text-red-400">*</span>
                    </label>
                    <input type="time" name="CloseTime" value="{{ old('CloseTime') }}" class="input border rounded p-2 w-full" required>
                </div>

                <!-- PrepTimeMin -->
                <div>
                    <label class="block text-sm font-medium">
                        Preparation Time (Min)
                        <span class="text-red-200">(optional)</span>
                    </label>
                    <input type="number" name="PrepTimeMin" value="{{ old('PrepTimeMin', 30) }}" class="input border rounded p-2 w-full">
                </div>

                <!-- DeliveryFee -->
                <div>
                    <label class="block text-sm font-medium">
                        Delivery Fee
                        <span class="text-red-400">*</span>
                    </label>
                    <input type="number" step="0.01" name="DeliveryFee" value="{{ old('DeliveryFee', 0) }}" class="input border rounded p-2 w-full" required>
                </div>

                <!-- MinOrder -->
                <div>
                    <label class="block text-sm font-medium">
                        Minimum Order
                        <span class="text-red-400">*</span>
                    </label>
                    <input type="number" step="0.01" name="MinOrder" value="{{ old('MinOrder', 0) }}" class="input border rounded p-2 w-full" required>
                </div>

                <!-- Latitude -->
                <div>
                    <label class="block text-sm font-medium">
                        Latitude
                        <span class="text-red-400">*</span>
                    </label>
                    <input type="number" step="0.000001" name="Latitude" value="{{ old('Latitude') }}" class="input border rounded p-2 w-full" required>
                </div>

                <!-- Longitude -->
                <div>
                    <label class="block text-sm font-medium">
                        Longitude
                        <span class="text-red-400">*</span>
                    </label>
                    <input type="number" step="0.000001" name="Longitude" value="{{ old('Longitude') }}" class="input border rounded p-2 w-full" required>
                </div>

                <!-- IsActive -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="IsActive" value="1" {{ old('IsActive', 1) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label class="text-sm font-medium">Active</label>
                </div>

                <!-- IsPureVeg -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="IsPureVeg" value="1" {{ old('IsPureVeg') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label class="text-sm font-medium">Pure Veg</label>
                </div>

            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-2 px-6 py-4 border-t">
                <button type="button" id="add-cancel-btn"
                        class="px-4 py-2 bg-gray-200 rounded">
                    Cancel
                </button>
                {{-- <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded">
                    Save Restaurant
                </button> --}}

                <button
                    type="submit"
                    id="restaurantSubmitBtn"
                    class="bg-green-600 text-white px-4 py-2 rounded flex items-center justify-center gap-2"
                >
                    <span id="btnText">Save Restaurant</span>
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

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addModal = document.getElementById('Add-Restaurant-Modal');
        addModal.classList.remove('hidden');
        addModal.classList.add('flex');
    });
</script>
@endif



{{-- Modal --}}
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
            modalTitle.innerText = 'Edit Restaurant';
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

<script>

    // ADD MODAL
    const addModal = document.getElementById('Add-Restaurant-Modal');
    const openAddBtn = document.getElementById('open-register-form-modal');
    const addCloseBtn = document.getElementById('add-close-btn');
    const addCancelBtn = document.getElementById('add-cancel-btn');

    openAddBtn?.addEventListener('click', () => {
        addModal.classList.remove('hidden');
        addModal.classList.add('flex');
    });

    [addCloseBtn, addCancelBtn].forEach(btn => {
        btn?.addEventListener('click', () => {
            addModal.classList.add('hidden');
            addModal.classList.remove('flex');
        });
    });



    document.getElementById('restaurantForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = e.target;
        const btn = document.getElementById('restaurantSubmitBtn');
        const btnText = document.getElementById('btnText');
        const spinner = document.getElementById('btnSpinner');

        // Disable button & show loading
        btn.disabled = true;
        btn.classList.add('opacity-70', 'cursor-not-allowed');
        btnText.textContent = 'Saving...';
        spinner.classList.remove('hidden');

        // const payload = {
        const data = new URLSearchParams({
            restaurantName: form.Name.value,
            adminName: form.AdminName.value,
            adminEmail: form.email.value,
            adminPassword: form.Password.value, // API requires it
            adminPhone: form.Phone.value,

            restaurantAddress: form.Address.value,
            flicNo: form.FLICNo.value,
            gstin: form.GSTIN.value,
            pan: form.PAN.value,

            cuisineType: form.CuisineType.value,
            isPureVeg: form.IsPureVeg.checked,
            priority: 0,

            openTime: form.OpenTime.value,
            closeTime: form.CloseTime.value,
            prepTimeMin: form.PrepTimeMin.value || 10,
            deliveryFee: form.DeliveryFee.value,
            minOrder: form.MinOrder.value,
            latitude: form.Latitude.value,
            longitude: form.Longitude.value,
            _token: '{{ csrf_token() }}'
        });
        // };

        fetch('{{ route("admin.restaurants.store") }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
            },
            body: data
        })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Restaurant registered successfully',
                    timer: 2000,
                    showConfirmButton: false
                });

                form.reset();
                document.getElementById('Add-Restaurant-Modal').classList.add('hidden');
                // closeStoreModal();
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
            btnText.textContent = 'Save Restaurant';
            spinner.classList.add('hidden');
        });



        // .then(res => {
        //     if (!res.ok) throw res;
        //     return res.json();
        // })
        // .then(() => {
        //     Swal.fire({
        //         icon: 'success',
        //         title: 'Success',
        //         text: 'Restaurant registered successfully',
        //         timer: 2000,
        //         showConfirmButton: false
        //     });

        //     form.reset();
        //     document.getElementById('Add-Restaurant-Modal').classList.add('hidden');
        // })
        // .catch(async err => {
        //     let msg = 'Server error';
        //     try {
        //         const data = await err.json();
        //         msg = data.message || msg;
        //     } catch {}

        //     Swal.fire({
        //         icon: 'error',
        //         title: 'Error',
        //         text: msg
        //     });
        // })
        // .finally(() => {
        //     btn.disabled = false;
        //     btn.innerText = 'Save Restaurant';
        // });


    });
</script>

@endpush
