@extends('layouts.admin.app')
@section('title', 'Admin | Customer Management')

@push('styles')
@endpush

@section('contents')
<div class="flex-1 p-4 md:p-6 bg-gray-50 overflow-auto">
    <div class="mb-6 flex justify-between items-center flex-wrap">
        <div class="mb-2 md:mb-0">
            <h2 class="text-2xl font-bold text-gray-800">Customer Management</h2>
            <p class="text-gray-600">Manage all Customers</p>
        </div>

        <div class="flex flex-wrap gap-2 w-full md:w-auto">
            <!-- Search Form -->
            <div class=" group border b rounded-lg focus-within:border-2 ">
                <input type="text" id="search" name="search" placeholder="Search by Name or Email or Phone..." 
                    value="{{ request('search') }}"
                    {{-- class="flex-1 min-w-[250px] border rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500" --}}
                    class="border border-none focus:outline-none px-2 py-2 " 
                >
                <button type="submit" id="search_icon" class="px-3 py-2 rounded-r-lg bg-gray-200 hover:bg-gray-400 ">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <select id="onlineStatus" name="onlineStatus" class="px-3 py-2 border rounded-md text-sm">
                <option value="">All Status</option>
                <option value="true" {{ request('onlineStatus')=='true' ? 'selected' : '' }}>Online</option>
                <option value="false" {{ request('onlineStatus')=='false' ? 'selected' : '' }}>Offline</option>
            </select>

            <select name="per_page" id="per-page-filter" class="px-3 py-2 border rounded-md text-sm">
                @foreach($allowedPerPage as $pp)
                    <option value="{{ $pp }}" {{ $perPage == $pp ? 'selected' : '' }}>{{ $pp }} per page</option>
                @endforeach
            </select>

            {{-- <button id="openAdminModal" class="w-full md:w-[240px] inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                <i class="fas fa-plus mr-1"></i> New Customer
            </button> --}}
            
        </div>
    </div>

    <!-- Table -->
    <div id="tableData" class="bg-white shadow rounded-lg overflow-hidden">
        @include('admin.users.customers.searchedCustomers', ['users' => $users])
    </div>
</div>


{{--Edit Modal --}}
<div id="edit-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen ">
        <!-- Overlay -->
        <div id="editOverlay" class="fixed inset-0 bg-blue-950/40"></div>

        <!-- Modal content -->
        <div class="bg-white rounded-lg shadow-xl transform transition-all max-w-lg w-full  relative">
            <div class="flex justify-between items-center bg-indigo-600 rounded-t-lg px-6 pt-4 mb-2">
                  <h3 class="text-lg font-medium text-white mb-4" id="modal-title"></h3>
                <button type="button" id="edit-close-btn" class=" text-white hover:text-red-500">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <form id="customer-form" method="POST" class="space-y-4 px-6">
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
                    <input type="email" name="email" id="customer-email" placeholder="example@gmail.com"
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
                    <input type="text" name="contact_number" id="customer_contact_number" placeholder="+977 98XXXXXXXX"
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
                    <input type="checkbox" name="IsActive" id="IsActive" value="1" class="rounded  text-indigo-600">
                    <label for="IsActive" class="text-sm font-medium text-gray-700">Active</label>
                </div>

                <div class="flex justify-end space-x-2 mb-4">
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

     // EDIT MODAL
    const editModal = document.getElementById('edit-modal');
    const editCloseBtn = document.getElementById('edit-close-btn');
    const editCancelBtn = document.getElementById('edit-cancel-btn');
    const editOverlay = document.getElementById('editOverlay');

    [editCloseBtn, editCancelBtn, editOverlay].forEach(btn => {
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
            // document.getElementById('per-page-filte').value= 

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

    function fetchData(url = null) {
        const search = document.getElementById('search').value;
        const onlineStatus = document.getElementById('onlineStatus').value;
        const perPage = document.getElementById('per-page-filter').value;

        let fetchUrl;

        if (url) {
            fetchUrl = url;
        } else {
            fetchUrl = `?search=${encodeURIComponent(search)}&onlineStatus=${encodeURIComponent(onlineStatus)}&per_page=${perPage}`;
        }

        fetch(fetchUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(data => {
            document.getElementById('tableData').innerHTML = data;
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
</script>
@endpush
