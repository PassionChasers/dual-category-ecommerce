@extends('layouts.admin.app')
@section('title', 'Business Admin | Food Order Management')

@push('styles')
<!-- add any page-specific styles here -->
@endpush

@section('contents')
<div class="flex-1 overflow-auto bg-gray-50 p-4 md:p-6">

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
            <h2 class="text-2xl font-bold text-gray-800">Food Order Management</h2>
            <p class="text-gray-600">Food Order List</p>
        </div><br>

      
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">

            <form method="GET" action="{{ route('orders.restaurant-food.index') }}" class="flex gap-2 items-center">
                {{-- <div class="px-3 py-2 rounded-md hover:bg-gray-200"> --}}
                    <input type="text" name="search" placeholder="Search by product name..."
                    value="{{ request('search') }}"
                    class="px-3 py-2 border rounded-md bg-white focus:ring-indigo-500 focus:border-indigo-500" 
                    />
                    <button type="submit" class="px-3 py-2 rounded-md hover:bg-gray-100 cursor-pointer transition">
                        <i class="fas fa-search"></i>
                    </button>
                {{-- </div><br> --}}

                <select name="status" onchange="this.form.submit()" class="px-3 py-2 border rounded-md cursor-pointer">
                    <option value="">All Status</option>
                    {{-- <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>Pending</option>
                    <option value="3" {{ request('status') == 3 ? 'selected' : '' }}>Assigned</option> --}}
                    <option value="4" {{ request('status') == 4 ? 'selected' : '' }}>Accepted</option>
                    <option value="5" {{ request('status') == 5 ? 'selected' : '' }}>Rejected</option>
                    <option value="6" {{ request('status') == 6 ? 'selected' : '' }}>Preparing</option>
                    <option value="7" {{ request('status') == 7 ? 'selected' : '' }}>Packed</option>
                    <option value="8" {{ request('status') == 8 ? 'selected' : '' }}>Shipping</option>
                    <option value="9" {{ request('status') == 9 ? 'selected' : '' }}>Cancelled</option>
                    <option value="10" {{ request('status') == 10 ? 'selected' : '' }}>Completed</option>
                </select>

                <select name="sort_by" onchange="this.form.submit()" class="px-3 py-2 border rounded-md cursor-pointer">
                    <option value="CreatedAt" {{ request('sort_by')==='CreatedAt' ? 'selected' : '' }}>Sort by Newest</option>
                    <option value="TotalAmount" {{ request('sort_by')==='TotalAmount' ? 'selected' : '' }}>Sort by Amount</option>
                </select>

                <select name="per_page" onchange="this.form.submit()" class="px-3 py-2 border rounded-md cursor-pointer">
                    @foreach([5,10,25,50] as $p)
                        <option value="{{ $p }}" {{ request('per_page',10)==$p ? 'selected':'' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </form>

            <div class="flex gap-2 mt-2 md:mt-0">
                <!-- Export Button -->
                <a href="#"
                    class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    <i class="fas fa-file-excel mr-1"></i> Export
                </a>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden" id="orderTable">
        @include('admin.orders.BusinessViewOrder.restaurant.searchedProducts', ['allOrders' => $allOrders])  
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    let interval = null;           // Regular AJAX interval
    let tableLoaderPaused = false;
    let refreshPending = false;

    let inactivityTimeout = null;  // Timer to resume after inactivity
    const INACTIVITY_DELAY = 20000; // 20 seconds

    function bindOrderEvents() {

        // reject forms
        document.querySelectorAll('.reject-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                pauseTableUpdate();//

                const status = parseInt(form.dataset.status);
                // const blockedStatuses = [3, 4, 6, 7, 8, 10];
                const blockedStatuses = [10, 9, 8, 7, 6, 5, 4, 2, 1];

                if (blockedStatuses.includes(status)) {
                    Swal.fire({
                        title: 'Action Not Allowed!',
                        text: 'This order cannot be rejected in its current status.',
                        icon: 'warning',
                        confirmButtonColor: '#6c757d'
                    });

                    resumeTableUpdate();
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, reject it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();  // normal submit  auto  page refresh
                    } else {
                        resumeTableUpdate();
                    }

                });
            });
        });


        // accept forms
        document.querySelectorAll('.accept-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                pauseTableUpdate();//

                const status = parseInt(form.dataset.status);
                // const blockedStatuses = [3, 4, 6, 7, 8, 10];
                const blockedStatuses = [10, 9, 8, 7, 6, 5, 4, 2, 1];

                if (blockedStatuses.includes(status)) {
                    Swal.fire({
                        title: 'Action Not Allowed!',
                        text: 'This order cannot be accepted in its current status.',
                        icon: 'warning',
                        confirmButtonColor: '#6c757d'
                    });

                    resumeTableUpdate();
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, accept it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();  // normal submit  auto  page refresh
                    } else {
                        resumeTableUpdate();
                    }

                });
            });
        });

        // Pause AJAX while interacting with selects or inputs
        const interactiveElements = document.querySelectorAll('.assign-deliveryman, .order-status, input[name="search"], select[name="status"], select[name="sort_by"], select[name="per_page"]');

        interactiveElements.forEach(el => {
            // Any activity stops AJAX and resets the inactivity timer
            el.addEventListener('focus', pauseTableUpdate);
            el.addEventListener('input', pauseTableUpdate); // typing in input
            el.addEventListener('change', pauseTableUpdate);
            el.addEventListener('click', pauseTableUpdate);

            // When user leaves element, start inactivity timer
            el.addEventListener('blur', startInactivityTimer);
        });

        // Deliveryman assignment confirmation
        // document.querySelectorAll('.assign-deliveryman').forEach(select => {
        //     select.addEventListener('change', function () {
        //         const form = this.closest('.assign-delivery-form');
        //         const selectedName = this.options[this.selectedIndex].text;

        //         if (!this.value) return;

        //         pauseTableUpdate();

        //         Swal.fire({
        //             title: 'Assign Delivery Man?',
        //             text: `Are you sure you want to assign "${selectedName}" to this order?`,
        //             icon: 'question',
        //             showCancelButton: true,
        //             confirmButtonColor: '#3085d6',
        //             cancelButtonColor: '#d33',
        //             confirmButtonText: 'Yes, assign!',
        //             cancelButtonText: 'Cancel'
        //         }).then(result => {
        //             if (result.isConfirmed){
        //                 // resumeTableUpdate();
        //                 form.submit();
        //             } 
        //             else {
        //                 this.value = '';
        //                 resumeTableUpdate();
        //             }
        //         });
        //     });
        // });

        // Order status update via AJAX
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        document.querySelectorAll('.order-status').forEach(select => {
            select.addEventListener('change', function () {

                pauseTableUpdate();

                fetch("{{ route('orders.update-status') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrf
                    },
                    body: JSON.stringify({
                        order_id: this.dataset.orderId,
                        status: this.value
                    })
                })
                .then(r => r.json())
                .then(res => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: res.success ? 'success' : 'error',
                        title: res.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong'
                    });
                })
                .finally(() => {
                    resumeTableUpdate();
                });
            });
        });

    }

    function loadOrders(force = false) {

        if (tableLoaderPaused && !force) {
            refreshPending = true; // remember to refresh later
            return;
        }

        const params = new URLSearchParams(window.location.search);

        fetch("{{ route('orders.restaurant-food.index') }}?" + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.text())
        .then(html => {
            document.getElementById('orderTable').innerHTML = html;
            bindOrderEvents(); // re-bind events for new DOM elements
        });
    }

    function startAutoRefresh() {
        if (!interval) {
            interval = setInterval(() => loadOrders(), 10000);
        }
    }

    function pauseTableUpdate() {
        tableLoaderPaused = true;
        if (interval) { 
            clearInterval(interval);
            interval = null; 
        }
    }

    // function pauseRefreshOnActivity() {
    //     stopAutoRefresh();
    //     clearTimeout(inactivityTimeout);
    //     inactivityTimeout = setTimeout(() => {
    //         loadOrders();      // One refresh after inactivity
    //         startAutoRefresh(); // Resume 5s interval
    //     }, INACTIVITY_DELAY);
    // }

    // Called when user leaves input/select
    function startInactivityTimer() {
        clearTimeout(inactivityTimeout);
        inactivityTimeout = setTimeout(() => {
            // loadOrders();
            // startAutoRefresh();
            resumeTableUpdate()
        }, INACTIVITY_DELAY);
    }

    // Resume + instant refresh
    function resumeTableUpdate() {
        tableLoaderPaused = false;

        if (refreshPending) {
            refreshPending = false;
            loadOrders(true); // force refresh immediately
        }
        
        startAutoRefresh();

    }

    // Pause AJAX if tab is hidden
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) pauseTableUpdate();
        else resumeTableUpdate();
    });

    //Initial Load
    bindOrderEvents();
    loadOrders();
    startAutoRefresh();

});
</script>
@endpush
