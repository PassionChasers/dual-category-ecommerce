@extends('layouts.admin.app')
@section('title', 'Admin | Food Order Management')

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
            <h2 class="text-2xl font-bold text-gray-800">Food Order Management</h2>
            <p class="text-gray-600">Manage Food Orders List</p>
        </div>

        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
            <!-- Search Form -->
            <form method="GET" class="flex flex-wrap w-full gap-2">
                <input type="text" name="search" value="" placeholder="Search Orders by ID..."
                    class="flex-1 min-w-[150px] border rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <select name="status" class="flex-shrink-0 border rounded-md px-3 py-2 text-sm">
                    <option value="">All status</option>
                    <option value="0" >Pending</option>
                    <option value="1" >In Progress</option>
                    <option value="2" >Completed</option>
                </select>
                <button type="submit" class="flex-shrink-0 px-3 py-2 bg-gray-100 text-sm rounded hover:bg-gray-200">
                    <i class="fas fa-search"></i>
                </button>
            </form>

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

    <div class="bg-white shadow rounded-lg overflow-hidden">
         <div class="px-6 py-4 border-b">
            <h2 class="font-semibold text-gray-800">Orders List</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">SN</th>
                        <th class="px-4 py-2">Products</th>
                        <th class="px-4 py-2">Customer</th>
                        <th class="px-4 py-2">Contact</th>
                        <th class="px-4 py-2">Type</th>
                        <th class="px-4 py-2">Store</th>
                        <th class="px-4 py-2">Payment</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Assign Rider</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse($foodOrders as $order)
                        <tr>
                            {{-- Serial --}}
                            <td class="px-4 py-2">
                                {{ ($foodOrders->currentPage() - 1) * $foodOrders->perPage() + $loop->iteration }}
                            </td>
                            
                            {{-- Products --}}
                            <td class="px-4 py-2 font-semibold">
                                <div
                                    class="max-h-20 overflow-y-auto space-y-1
                                        [&::-webkit-scrollbar]:hidden
                                        [-ms-overflow-style:none]
                                        [scrollbar-width:none]"
                                >
                                    @foreach($order->items as $item)
                                        <div class="text-sm">
                                            {{ $item->product_name }}
                                        </div>
                                    @endforeach
                                </div>
                            </td>

                            

                            {{-- Customer --}}
                            <td class="px-4 py-2">
                                {{ $order->user->name ?? 'N/A' }}
                            </td>

                            {{-- Contact --}}
                            <td class="px-4 py-2 text-gray-600">
                                {{ $order->user->contact_number ?? 'N/A' }}
                            </td>

                            {{-- Order Type --}}
                            <td class="px-4 py-2">
                                @if($order->order_type === 'food')
                                    <span class="px-2 py-1 text-xs rounded bg-orange-100 text-orange-800">
                                        🍔 Food
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">
                                        💊 Medicine
                                    </span>
                                @endif
                            </td>

                            {{-- Store --}}
                            <td class="px-4 py-2">
                                {{ $order->restaurant->Name ?? 'N/A' }}
                            </td>

                            {{-- Payment --}}
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-xs
                                    {{ $order->payment_status === 'paid'
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ strtoupper($order->payment_method) }} -
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-xs
                                    @switch($order->order_status)
                                        @case('pending') bg-yellow-100 text-yellow-800 @break
                                        @case('accepted') bg-blue-100 text-blue-800 @break
                                        @case('preparing') bg-orange-100 text-orange-800 @break
                                        @case('packed') bg-purple-100 text-purple-800 @break
                                        @case('out_for_delivery') bg-indigo-100 text-indigo-800 @break
                                        @case('delivered') bg-green-100 text-green-800 @break
                                        @case('cancelled') bg-red-100 text-red-800 @break
                                    @endswitch
                                ">
                                    {{ ucfirst(str_replace('_',' ', $order->order_status)) }}
                                </span>
                            </td>

                            {{-- Date --}}
                            <td class="px-4 py-2">
                                {{ $order->created_at->format('Y-m-d') }}
                            </td>

                            {{-- Assign Rider --}}
                            <td class="px-4 py-2">
                                <select class="border rounded px-2 py-1 text-sm">
                                    <option value="">Select Rider</option>
                                    {{-- loop riders here --}}
                                </select>
                            </td>

                            {{-- Actions --}}
                            <td class="px-4 py-2 flex space-x-3">
                                <a href="{{ route('orders.show', $order->id) }}"
                                class="text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="#"
                                class="text-indigo-600 hover:text-indigo-800">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- ------------
                                DELETE ORDER 
                                ----------------}}
                                <form method="POST" action="{{ route('orders.destroy', $order->id) }}" class="inline delete-form" data-status="{{ $order->order_status }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="px-4 py-4 text-center text-gray-500">
                                No orders found.
                            </td>
                        </tr>       
                    @endforelse                
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="flex flex-col md:flex-row items-center justify-between px-6 py-4 bg-gray-50 border-t">
            <div class="text-sm text-gray-600">
                Showing <strong>{{ $foodOrders->firstItem() ?? 0 }}</strong> to <strong>{{ $foodOrders->lastItem() ?? 0 }}</strong> of <strong>{{ $foodOrders->total() }}</strong> results
            </div>
            <div class="mt-3 md:mt-0">{{ $foodOrders->links() }}</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // Delete confirmation
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const status = form.dataset.status;
                    const blockedStatuses = ['accepted', 'preparing', 'packed'];

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
                });
            });  
        });

        
    </script>
@endpush



