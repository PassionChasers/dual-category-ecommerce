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
             <input type="text" id="search" name="search" placeholder="Search Orders by ProductName or CustomerName..." value="{{ request('search') }}"
                class="flex-1 min-w-[150px] border rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            <select id="orderStatus" name="orderStatus" class="flex-shrink-0 border rounded-md px-3 py-2 text-sm">
                <option value="">All Status</option>
                <option value="pending" {{ request('orderStatus')=='pending' ? 'selected' : '' }}>Pending</option>
                <option value="accepted" {{ request('orderStatus')=='accepted' ? 'selected' : '' }}>Accepted</option>
                <option value="preparing" {{ request('orderStatus')=='preparing' ? 'selected' : '' }}>Preparing</option>
                <option value="packed" {{ request('orderStatus')=='packed' ? 'selected' : '' }}>Packed</option>
                <option value="out_for_delivery" {{ request('orderStatus')=='out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                <option value="delivered" {{ request('orderStatus')=='delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('orderStatus')=='cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                        <th class="px-4 py-2">MedicalStore/Restaurant</th>
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
                            <td class="px-4 py-2">
                                <div class="flex items-center justify-center gap-3 h-full">

                                    {{-- VIEW --}}
                                    <a href="{{ route('orders.show', $order->id) }}"
                                    class="text-gray-600 hover:text-gray-900">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- EDIT --}}
                                    <a href="javascript:void(0)"
                                    class="text-indigo-600 hover:text-indigo-800 edit-btn"
                                    data-id="{{ $order->id }}">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- DELETE --}}
                                    <form method="POST"
                                        action="{{ route('orders.destroy', $order->id) }}"
                                        class="delete-form"
                                        data-status="{{ $order->order_status }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                </div>
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
<<<<<<< HEAD
@endsection

@push('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <!-- AJAX Script -->
    <script>
=======

<!-- Modal -->
<div id="task-modal" class="fixed z-50 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

        <!-- Modal content -->
        <div class="bg-white rounded-2xl shadow-2xl transform transition-all max-w-2xl w-full p-8 relative z-20">
            <!-- Close Button -->
            <button type="button" id="close-modal-btn"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 transition">
                <i class="fas fa-times text-xl"></i>
            </button>

            <!-- Modal Title -->
            <h3 class="text-2xl font-semibold text-gray-900 mb-6" id="modal-title">New Product</h3>

            <!-- Form -->
            <form id="task-form" method="get" class="space-y-6" action="#">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">
                <input type="hidden" name="task_id" id="task-id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="task_category_id" id="task_category_id" class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2
                                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                            <option value="">Select Category</option>
                            {{-- @foreach($categories as $c) --}}
                            <option value="">aaaa</option>
                            {{-- @endforeach --}}
                            <option value="">bbbb</option>
                            <option value="">cccc</option>
                            
                        </select>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <select name="priority_id" id="priority_id" class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2
                                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                            <option value="">Select Priority</option>
                            
                            <option value="">High</option>
                            <option value="">medium</option>
                            <option value="">Low</option>
                        </select>
                    </div>

                    <!-- Task Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                        <input type="text" name="name" id="task-name" class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2
                             focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2
                                    focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition"
                            min="{{ date('Y-m-d') }}">
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="task-desc" rows="4"
                            class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2
                            focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition"></textarea>
                    </div>

                    <!-- Assignee Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assign To</label>
                        <input type="hidden" name="assigned_to" id="assigned_to">
                        <input type="text" id="assigned_to_search" placeholder="Search user by name or email" class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2
                                      focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                        <div id="assigned_to_suggestions"
                            class="bg-white border mt-1 rounded-lg shadow max-h-48 overflow-auto hidden"></div>
                    </div>

                    <!-- Assigned By -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assigned By</label>
                        <input type="text" value=""
                            class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100" readonly>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="cancel-btn"
                        class="px-5 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition font-medium">
                        Cancel
                    </button>
                    <button type="submit" id="save-btn"
                        class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('task-modal');
    const newBtn = document.getElementById('new-task-button');
    const closeBtn = document.getElementById('close-modal-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const form = document.getElementById('task-form');
    const modalTitle = document.getElementById('modal-title');
    const methodInput = document.getElementById('form-method');
    const taskIdInput = document.getElementById('task-id');
>>>>>>> c0fc83ddb31d95b5044bff30f32d0e4e962de7ca

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
                : `/food-order-list?search=${search}&orderStatus=${orderStatus}`;

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
            fetchData();
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
            }
        });
    </script>
@endpush



