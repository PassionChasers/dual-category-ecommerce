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
                    <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>Pending</option>
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
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="font-semibold text-gray-800">Orders List</h2>
        </div>
        <div class="overflow-x-auto" id="tableData">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2">SN</th>
                    <th class="px-4 py-2">Product Name</th>
                    <th class="px-4 py-2">Quantity</th>
                    <th class="px-4 py-2">Product Type</th>
                    <th class="px-4 py-2">Total Amount</th>
                    <th class="px-4 py-2">Delivery Address</th>
                    <th class="px-4 py-2">Customer Name</th>
                    {{-- <th class="px-4 py-2">Contact No.</th> --}}
                    <th class="px-4 py-2">Assign Delivery Man</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200" id="orderTableBody">
                                
            </tbody>
        </table> 
        </div>

        {{-- PAGINATION --}}
        <div class="flex flex-col md:flex-row items-center justify-between px-6 py-4 bg-gray-50 border-t">
            <div class="text-sm text-gray-600">
                Showing <strong>{{ $allOrders->firstItem() ?? 0 }}</strong> to <strong>{{ $allOrders->lastItem() ?? 0 }}</strong> of <strong>{{ $allOrders->total() }}</strong> results
            </div>
            <div class="mt-3 md:mt-0" id="pageLink">
                {{ $allOrders->links() }}
            </div>
        </div>


        @include('admin.orders.BusinessViewOrder.restaurant.searchedProducts', ['allOrders' => $allOrders])
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        //For Delivery Man
            document.querySelectorAll('.assign-deliveryman').forEach(select => {
                select.addEventListener('change', function () {
                    const form = this.closest('.assign-delivery-form');
                    const selectedName = this.options[this.selectedIndex].text;

                    if (!this.value) return;

                    Swal.fire({
                        title: 'Assign Delivery Man?',
                        text: `Are you sure you want to assign "${selectedName}" to this order?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, assign!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Submit the form
                        } else {
                            this.value = ''; // Reset selection if canceled
                        }
                    });
                });
            });


        // Update Order Status
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        document.querySelectorAll('.order-status').forEach(select => {
            select.addEventListener('change', function () {
                const orderId = this.dataset.orderId;
                const status = this.value;

                fetch("{{ route('orders.update-status') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        status: status
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: data.message || 'Failed to update status'
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong'
                    });
                });
            });
        });
    });
</script>
@endpush


    