@extends('layouts.admin.app')
@section('title', 'Admin | Medicine Order Management')

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

            <form method="GET" action="{{ route('orders.medicine.index') }}" class="flex gap-2 flex-wrap items-center">
                <div class=" group border b rounded-lg focus-within:border-2 ">

                    {{-- <div class="px-3 py-2 rounded-md hover:bg-gray-200"> --}}
                        <input type="text" name="search" class="border border-none focus:outline-none px-2 py-2 " placeholder="Search by product name..."
                        value="{{ request('search') }}" 
                        />
                        <button type="submit" onclick="showLoader()" class="px-3 py-2 rounded-r-lg bg-gray-200 hover:bg-gray-400 hover:text-lg">
                            <i class="fas fa-search"></i>
                        </button>
                    {{-- </div><br> --}}
                </div>

                <select name="status" onchange="showLoader(); this.form.submit()" class="px-3 py-2 border rounded-md cursor-pointer">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>Pending</option>
                    <option value="2" {{ request('status') == 2 ? 'selected' : '' }}>Pending Review</option>
                    <option value="3" {{ request('status') == 3 ? 'selected' : '' }}>Assigned</option>
                    <option value="4" {{ request('status') == 4 ? 'selected' : '' }}>Accepted</option>
                    <option value="5" {{ request('status') == 5 ? 'selected' : '' }}>Rejected</option>
                    <option value="6" {{ request('status') == 6 ? 'selected' : '' }}>Preparing</option>
                    <option value="7" {{ request('status') == 7 ? 'selected' : '' }}>Packed</option>
                    <option value="8" {{ request('status') == 8 ? 'selected' : '' }}>Shipping</option>
                    <option value="9" {{ request('status') == 9 ? 'selected' : '' }}>Cancelled</option>
                    <option value="10" {{ request('status') == 10 ? 'selected' : '' }}>Completed</option>
                </select>

                <select name="sort_by" onchange="showLoader(); this.form.submit()" class="px-3 py-2 border rounded-md cursor-pointer">
                    <option value="CreatedAt" {{ request('sort_by')==='CreatedAt' ? 'selected' : '' }}>Sort by Newest</option>
                    <option value="TotalAmount" {{ request('sort_by')==='TotalAmount' ? 'selected' : '' }}>Sort by Amount</option>
                </select>

                <select name="per_page" onchange="showLoader(); this.form.submit()" class="px-3 py-2 border rounded-md cursor-pointer">
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
        @include('admin.orders.medicine-order.searchedProducts', ['allOrders' => $allOrders]) 
    </div>
    
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    // Cancel forms
    document.querySelectorAll('.cancel-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            // pauseTableUpdate();//

            const status = parseInt(form.dataset.status);
            const blockedStatuses = [3, 4, 6, 7, 8, 10];

            if (blockedStatuses.includes(status)) {
                Swal.fire({
                    title: 'Action Not Allowed!',
                    text: 'This order cannot be canceled in its current status.',
                    icon: 'warning',
                    confirmButtonColor: '#6c757d'
                });

                // resumeTableUpdate();
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();  // normal submit  auto  page refresh
                } else {
                    // resumeTableUpdate();
                }

            });
        });
    });

    // Assign Store
    document.querySelectorAll('.assign-store').forEach(select => {
        select.addEventListener('change', function () {
            const medicalStoreId = this.value;
            const orderId = this.dataset.orderId;
            const selectedName = this.options[this.selectedIndex].text;

            if (!medicalStoreId || !orderId) return;

            // pauseTableUpdate();

            Swal.fire({
                title: 'Assign Store?',
                text: `Are you sure you want to assign "${selectedName}" to this order?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, assign!',
                cancelButtonText: 'Cancel'
            }).then((result) => {

                if (!result.isConfirmed) {
                    this.value = '';
                    // resumeTableUpdate();
                    return;
                }

                fetch("{{ route('orders.assign-store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        medical_store_id: medicalStoreId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const statusText = document.querySelector(
                            `.order-status-text[data-order-id="${orderId}"]`
                        );
                        if (statusText) statusText.textContent = 'Assigned';

                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            title: data.message,
                            timer: 1500,
                            position: 'top-end',
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: data.message || 'Failed to assign store'
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong'
                    });
                })
                .finally(() => {
                    // resumeTableUpdate();
                });

            });
        });
    });

    // Deliveryman assignment confirmation
    document.querySelectorAll('.assign-deliveryman').forEach(select => {
        select.addEventListener('change', function () {
            const form = this.closest('.assign-delivery-form');
            const selectedName = this.options[this.selectedIndex].text;

            if (!this.value) return;

            // pauseTableUpdate();

            Swal.fire({
                title: 'Assign Delivery Man?',
                text: `Are you sure you want to assign "${selectedName}" to this order?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, assign!',
                cancelButtonText: 'Cancel'
            }).then(result => {
                if (result.isConfirmed){
                    // resumeTableUpdate();
                    form.submit();
                } 
                else {
                    this.value = '';
                    // resumeTableUpdate();
                }
                
            });
        });
    });

});
</script>
@endpush
