@extends('layouts.admin.app')
@section('title', 'Admin | DeliveryMan Management')

@push('styles')
@endpush

@section('contents')
<div class="flex-1 p-4 md:p-6 bg-gray-50">
    <div class="mb-6 flex justify-between items-center flex-wrap">
        <div class="mb-2 md:mb-0">
            <h2 class="text-2xl font-bold text-gray-800">DeliveryMan Management</h2>
            <p class="text-gray-600">Manage all DeliveryMan</p>
        </div>

        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2 w-full md:w-auto">
            <!-- Search Form -->
            <div class=" group border b rounded-lg focus-within:border-2 ">
                <input type="text" id="search" name="search" placeholder="Search by Email or Phone..." 
                    value="{{ request('search') }}"
                    class="border border-none focus:outline-none px-2 py-2 "
                >
                <button type="submit" id="search_icon" class="px-3 py-2 rounded-r-lg bg-gray-200 hover:bg-gray-400 ">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <select id="onlineStatus" name="onlineStatus" class="px-3 py-2 border rounded-md text-sm">
                <option value="">All Status</option>
                <option value="true" {{ request('onlineStatus')=='true' ? 'selected' : '' }}>Active</option>
                <option value="false" {{ request('onlineStatus')=='false' ? 'selected' : '' }}>InActive</option>
            </select>

            <select name="per_page" id="per-page-filter" class="px-3 py-2 border rounded-md text-sm">
                @foreach($allowedPerPage as $pp)
                    <option value="{{ $pp }}" {{ $perPage == $pp ? 'selected' : '' }}>{{ $pp }} per page</option>
                @endforeach
            </select>

            {{-- <button id="openAdminModal" class="w-full md:w-[240px] inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                <i class="fas fa-plus mr-1"></i> Add DeliveryMan
            </button> --}}

            <button onclick="openDeliveryManModal()"
                class="w-full md:w-[240px] inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                <i class="fas fa-plus mr-1"></i> Register New DeliveryMan
            </button>
        </div>
    </div>

    <!-- Table -->
    <div id="tableData" class="bg-white shadow rounded-lg overflow-hidden">
        @include('admin.users.deliveryMan.searchedDeliveryMan', ['users' => $users])
    </div>
</div>


<!-- Add User Modal -->
<div id="DeliveryManModal"
     class="fixed inset-0  hidden items-center justify-center z-50">
     <div id="addOverlay" class="fixed inset-0 bg-blue-950/40 backdrop-blur-[2px]"></div>

    <div class="bg-white w-full max-w-2xl rounded-lg shadow-lg overflow-y-auto max-h-[90vh] relative">
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 bg-indigo-600 ">
            <h3 class="text-lg font-semibold text-white">Add Delivery Man</h3>
            {{-- <button id="add-close-btn" class="text-white hover:text-red-500 text-3xl">&times;</button> --}}
            <button onclick="closeDeliveryManModal()" class="text-white hover:text-red-500 text-3xl">&times;</button>
        </div>

        <!-- Form -->
        <form id="delivery-man-form" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6">

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium">
                        Name<span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" placeholder="Enter Your Name" class="input border border-gray-400 rounded p-2 w-full" required>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium">
                        Email<span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" placeholder="example@gmail.com" class="input border border-gray-400 rounded p-2 w-full" required>
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium">
                        Phone<span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="phone" placeholder="+977 98XXXXXXXX" class="input border border-gray-400 rounded p-2 w-full" required>
                </div>

                <!-- Vehicle Type -->
                <div>
                    <label class="block text-sm font-medium">
                        Vehicle Type<span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="vehicleType" placeholder="Vechicle Type" class="input border border-gray-400 rounded  p-2 w-full" required>
                </div>

                <!-- Vehicle Number -->
                <div>
                    <label class="block text-sm font-medium">
                        Vehicle Number<span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="vehicleNumber" placeholder="Vechicle Number"  class="input border border-gray-400 rounded p-2 w-full" required>
                </div>

                <!-- License Number -->
                <div>
                    <label class="block text-sm font-medium">
                        License Number<span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="licenseNumber" placeholder="License Number"  class="input border border-gray-400 rounded p-2 w-full" required>
                </div>

                <!-- Latitude -->
                <div>
                    <label class="block text-sm font-medium">
                        Latitude<span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="latitude" placeholder="Latitude"  class="input border border-gray-400 rounded p-2 w-full" required>
                </div>

                <!-- Longitude -->
                <div>
                    <label class="block text-sm font-medium">
                        Longitude<span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="longitude" placeholder="Longitude"  class="input border border-gray-400 rounded p-2 w-full" required>
                </div>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-2 px-6 py-4">
                {{-- <button type="button" id="add-cancel-btn"
                        class="px-4 py-2 bg-gray-200 hover:text-white hover:bg-red-500 rounded-lg">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg">
                    Save User
                </button> --}}

                <button type="button" onclick="closeDeliveryManModal()" class="px-4 py-2 rounded-lg text-black bg-gray-300 hover:bg-red-500 hover:text-white">
                    Cancel
                </button>
                
                <button
                    type="submit"
                    id="SubmitBtn"
                    class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-lg flex items-center justify-center gap-2"
                >
                    <span id="btnText">Save DeliveryMAn</span>
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


{{-- Modal --}}
<div id="edit-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen ">
        <!-- Overlay -->
        <div id="editOverlay" class="fixed inset-0 bg-blue-950/40 "></div>

        <!-- Modal content -->
        <div class="bg-white rounded-lg shadow-xl  transform transition-all max-w-lg w-full  relative">
            <div class="flex items-center justify-between bg-indigo-600 rounded-t-lg px-6 py-2">
                 <h3 class="text-lg font-medium text-white " id="modal-title"></h3>
                <button type="button" id="edit-close-btn" class=" text-white hover:text-red-500 text-3xl ">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form id="customer-form" method="POST" class="space-y-4 px-6 py-4">
                @csrf
                <input type="hidden" id="form-method" name="_method" value="POST">
                <input type="hidden" name="search" id="current-search" value="{{ request('search') }}">
                <input type="hidden" name="onlineStatus" id="current-onlineStatus" value="{{ request('onlineStatus') }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="customer-name" placeholder="Enter Your Name"
                        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="customer-email" placeholder="example@gamil.com"
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

                <div class="flex justify-end space-x-2">
                    <button type="button" id="edit-cancel-btn" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-red-500 hover:text-white">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

     // ADD MODAL
    const addModal = document.getElementById('AdminModal');
    const openAddBtn = document.getElementById('openAdminModal');
    const addCloseBtn = document.getElementById('add-close-btn');
    const addCancelBtn = document.getElementById('add-cancel-btn');
    const addOverlay = document.getElementById('addOverlay');

    openAddBtn?.addEventListener('click', () => {
        addModal.classList.remove('hidden');
        addModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    });

    [addCloseBtn, addCancelBtn, addOverlay].forEach(btn => {
        btn?.addEventListener('click', () => {
            addModal.classList.add('hidden');
            addModal.classList.remove('flex');
        });
    });


     // EDIT MODAL
    const editModal = document.getElementById('edit-modal');
    const editCloseBtn = document.getElementById('edit-close-btn');
    const editCancelBtn = document.getElementById('edit-cancel-btn');
    const editOverlay = document.getElementById('editOverlay');

    [editCloseBtn, editCancelBtn,editOverlay].forEach(btn => {
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
    // const passwordInput = document.getElementById('customer-password');

    // Event delegation for edit buttons (works after AJAX too)
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.edit-btn');
        if (btn) {
            modalTitle.innerText = 'Edit Customer';
            form.action = `/users/update/${btn.dataset.id}`;
            methodInput.value = 'PUT';
            nameInput.value = btn.dataset.name;
            // addressInput.value = btn.dataset.address;
            IsActiveInput.checked = btn.dataset.isactive === '1';
            contactNumberInput.value = btn.dataset.contact_number;
            emailInput.value = btn.dataset.email;
            // passwordInput.value = '';

            // Set hidden fields for current search/filter
            document.getElementById('current-search').value = document.getElementById('search').value;
            document.getElementById('current-onlineStatus').value = document.getElementById('onlineStatus').value;

            editmodal.classList.remove('hidden');
        }
    });

    // AJAX search/filter/pagination
    // Set input values from URL parameters
    function setInputsFromUrl(url) {
        const params = new URLSearchParams(url.split('?')[1] || '');
        if (params.has('search')) document.getElementById('search').value = params.get('search');
        if (params.has('onlineStatus')) document.getElementById('onlineStatus').value = params.get('onlineStatus');
    }
    
    // Fetch and update table data
    // function fetchData(url = null) {
    //     const search = document.getElementById('search').value;
    //     const onlineStatus = document.getElementById('onlineStatus').value;
    //     let fetchUrl = url ? url : `?search=${search}&onlineStatus=${onlineStatus}`;

    //     if (url) setInputsFromUrl(url);

    //     fetch(fetchUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    //         .then(res => res.text())
    //         .then(data => {
    //             document.getElementById('tableData').innerHTML = data;
    //             if (url) setInputsFromUrl(url);
    //         });
    // }

    function fetchData(url = null) {
        const search = document.getElementById('search').value;
        const onlineStatus = document.getElementById('onlineStatus').value;
        const perPage = document.getElementById('per-page-filter').value;

        let fetchUrl;

        if (url) {
            fetchUrl = url;
            setInputsFromUrl(url);
        } else {
            fetchUrl = `?search=${encodeURIComponent(search)}&onlineStatus=${encodeURIComponent(onlineStatus)}&per_page=${perPage}`;
        }

        fetch(fetchUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(data => {
            document.getElementById('tableData').innerHTML = data;
            if (url) setInputsFromUrl(url);
        });
    }
    
    // Trigger fetch on search and filter change
    // document.getElementById('search').addEventListener('keyup', () => fetchData());
    document.getElementById('search').addEventListener('keyup', function (e) {
        if (e.key === 'Enter') {
            fetchData();
        }
    });
    document.getElementById('onlineStatus').addEventListener('change', () => fetchData());
    document.getElementById('per-page-filter').addEventListener('change', () => fetchData());
    document.getElementById('search_icon').addEventListener('click', function (e) {fetchData();});

    // AJAX pagination
    document.addEventListener('click', function(e) {
        const pageLink = e.target.closest('.pagination a');
        if (pageLink) {
            e.preventDefault();
            fetchData(pageLink.getAttribute('href'));
        }

        // Delete confirmation
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
});

    // Delivery MAn Form Submission
    function openDeliveryManModal() {
        const modal = document.getElementById('DeliveryManModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeliveryManModal() {
        const modal = document.getElementById('DeliveryManModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }


    document.getElementById('delivery-man-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = e.target;
        const btn = document.getElementById('SubmitBtn');
        const btnText = document.getElementById('btnText');
        const spinner = document.getElementById('btnSpinner');

        // Disable button
        btn.disabled = true;
        btn.classList.add('opacity-70', 'cursor-not-allowed');
        btnText.textContent = 'Saving...';
        spinner.classList.remove('hidden');

        //CORRECT CAMEL CASE FOR KEYS
        const data = {
            name: form.name.value,
            email: form.email.value,
            phone: form.phone.value,
            vehicleType: form.vehicleType.value,
            vehicleNumber: form.vehicleNumber.value,
            licenseNumber: form.licenseNumber.value,
            latitude: form.latitude.value,
            longitude: form.longitude.value,
            _token: '{{ csrf_token() }}'
        };

        fetch('{{ route("users.create-delivery-man") }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(response => {
            if (response.success) {
                
                closeDeliveryManModal();

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message || 'Registration successful.'
                })
                .then(() => {
                    window.location.href = response.redirect;
                });

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'DeliveryMan Registration failed'
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
            btn.disabled = false;
            btn.classList.remove('opacity-70', 'cursor-not-allowed');
            btnText.textContent = 'Save DeliveryMAn';
            spinner.classList.add('hidden');
        });
    });
</script>
@endpush
