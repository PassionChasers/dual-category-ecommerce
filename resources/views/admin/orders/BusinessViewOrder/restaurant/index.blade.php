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
                    <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Accepted" {{ request('status') === 'Accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="Preparing" {{ request('status') === 'Preparing' ? 'selected' : '' }}>Prepring</option>
                    <option value="Assigned" {{ request('status') === 'Assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
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
                <!-- New Task Button -->
                {{-- <button id="new-task-button"
                    class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 whitespace-nowrap">
                    <i class="fas fa-plus mr-1"></i> New Item
                </button> --}}

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
        @include('admin.orders.BusinessViewOrder.restaurant.searchedProducts', ['allOrders' => $allOrders])
    </div>

</div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush


    