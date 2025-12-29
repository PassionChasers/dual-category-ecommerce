@extends('layouts.admin.app')
@section('title', 'Admin | Medicine Order Management')

@push('styles')
<!-- add any page-specific styles here -->
@endpush

@section('contents')
<div class="flex-1 p-4 md:p-6 bg-gray-50">

    {{-- Flash Messages --}}
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
    @endif

    <div class="mb-6 flex justify-between items-center flex-wrap">
        <div class="mb-2 md:mb-0">
            <h2 class="text-2xl font-bold text-gray-800">Medicine Order Management</h2>
            <p class="text-gray-600">Medicine Order List</p>
        </div>

      
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">

            <!-- Search Form -->
            <input type="text" id="search" name="search" placeholder="Search by ProductName or CustomerName..." value="{{ request('search') }}"
                class="flex-1 min-w-[150px] border rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            <select id="orderStatus" name="orderStatus" class="flex-shrink-0 border rounded-md px-3 py-2 text-sm">
                <option value="">All Status</option>
                <option value="Completed" {{ request('orderStatus')=='Completed' ? 'selected' : '' }}>Completed</option>
                <option value="Cancelled" {{ request('orderStatus')=='Cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <div class="flex gap-2 mt-2 md:mt-0">
                <!-- New Task Button -->
                <button id="new-task-button"
                    class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 whitespace-nowrap">
                    <i class="fas fa-plus mr-1"></i> New Item
                </button>

                <!-- Export Button -->
                <a href="#"
                    class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    <i class="fas fa-file-excel mr-1"></i> Export
                </a>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden" id="tableData">
        @include('admin.orders.medicine-order.searchedProducts', ['allOrders' => $allOrders]);
    </div>
</div>

@endsection

@push('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <!-- AJAX Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        let timer;
        // Function to set input values from URL parameters
        function setInputsFromUrl(url) {
            const params = new URLSearchParams(url.split('?')[1] || '');

            if (params.has('search')) {
                document.getElementById('search').value = params.get('search');
            }

            if (params.has('orderStatus')) {
                document.getElementById('orderStatus').value = params.get('orderStatus');
            }
        }

        // Function to fetch data via AJAX
        function fetchData(url = null) {
            const search = document.getElementById('search').value;
            const orderStatus = document.getElementById('orderStatus').value;

            let fetchUrl = url 
                ? url 
                : `/medicine-order-list?search=${search}&orderStatus=${orderStatus}`;

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
            clearTimeout(timer);
            timer = setTimeout(fetchData(), 400);
        });

        //Status filter
        document.getElementById('orderStatus').addEventListener('change', function () {
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
                const blockedStatuses = ['Accepted', 'Preparing', 'Packed', 'Completed'];

                if (blockedStatuses.includes(status)) {
                    Swal.fire({
                        title: 'Action Not Allowed!',
                        text: 'This order cannot be deleted in its current status.',
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
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    });
    </script>
@endpush


    