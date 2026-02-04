@extends('layouts.admin.app')
@section('title', 'Business Admin | Medicine Order Management')

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
            <h2 class="text-2xl font-bold text-gray-800">Medicine Order Management</h2>
            <p class="text-gray-600">Medicine Order List</p>
        </div><br>

      
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">

            <form method="GET" action="{{ route('orders.medicalstore-medicine.index') }}" class="flex gap-2 items-center">
                <div class=" group border b rounded-lg focus-within:border-2 ">
                    <input type="text" name="search" placeholder="Search by product name..."
                    value="{{ request('search') }}"
                    class="border border-none focus:outline-none px-2 py-2 "
                    />
                    <button type="submit" class="px-3 py-2 rounded-r-lg bg-gray-200 hover:bg-gray-400 hover:text-lg">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <select name="status" onchange="this.form.submit()" class="px-3 py-2 border rounded-md cursor-pointer">
                    <option value="">All Status</option>
                    {{-- <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>Pending</option>
                    <option value="2" {{ request('status') == 2 ? 'selected' : '' }}>Pending Review</option> --}}
                    <option value="3" {{ request('status') == 3 ? 'selected' : '' }}>Assigned</option>
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
        @include('admin.orders.BusinessViewOrder.medicalstore.searchedProducts', ['allOrders' => $allOrders])
    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // reject forms
    document.querySelectorAll('.reject-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            

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
                    
                }

            });
        });
    });

    // accept forms
    document.querySelectorAll('.accept-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const status = parseInt(form.dataset.status);
            
            const blockedStatuses = [10, 9, 8, 7, 6, 5, 4, 2, 1];

            if (blockedStatuses.includes(status)) {
                Swal.fire({
                    title: 'Action Not Allowed!',
                    text: 'This order cannot be accepted in its current status.',
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
                confirmButtonText: 'Yes, accept it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();  // normal submit  auto  page refresh
                } else {
                    
                }

            });
        });
    });


    // Order status update via AJAX
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    document.querySelectorAll('.order-status').forEach(select => {
        select.addEventListener('change', function () {

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
                
            });
        });
    });  

});
</script>

@endpush



    