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
        @include('admin.orders.BusinessViewOrder.medicalstore.searchedProducts', ['allOrders' => $allOrders])
    </div>

    <!-- Modal -->
    {{-- <div id="editModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">

        <div class="bg-white p-6 rounded shadow-lg w-full max-w-3xl">
            <h2 class="text-xl font-semibold mb-4">Edit Order</h2>

            <form id="editOrderForm" method="POST" action="{{ route('orders.update') }}">
                @csrf
                @method('PUT')

                <input type="hidden" name="order_id" id="order_id">

                <!-- Order Items -->
                <div class="mb-4">
                    <h3 class="font-semibold mb-2">Order Items</h3>

                    <table class="w-full text-sm border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 text-left">Item</th>
                                <th class="p-2">Qty</th>
                                <th class="p-2">Type</th>
                                <th class="p-2">Action</th>
                            </tr>
                        </thead>
                        <tbody id="orderItemsContainer">
                            <!-- Items injected via JS -->
                        </tbody>
                    </table>

                    <button type="button"
                            onclick="addEmptyItem()"
                            class="mt-2 px-3 py-1 bg-green-600 text-white rounded">
                        + Add Item
                    </button>
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-2">
                    <button type="button"
                            onclick="closeModal()"
                            class="px-4 py-2 bg-gray-300 rounded">
                        Cancel
                    </button>

                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div> --}}
</div>

@endsection

@push('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    {{-- <script>
                function openEditModal(order) {
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('order_id').value = order.OrderId;

            const container = document.getElementById('orderItemsContainer');
            container.innerHTML = '';

            order.items.forEach((item, index) => {
                container.insertAdjacentHTML('beforeend', itemRow(item, index));
            });
        }

        function itemRow(item = {}, index) {

            const isExisting = !!item.OrderItemId;

            return `
                <tr class="border-t">

                    <!-- OrderItemId -->
                    <input type="hidden"
                        name="items[${index}][order_item_id]"
                        value="${item.OrderItemId ?? ''}">

                    <!-- Item Name -->
                    <td class="p-2">
                        <input type="text"
                            name="items[${index}][name]"
                            value="${item.ItemName ?? ''}"
                            class="w-full border px-2 py-1 ${isExisting ? 'bg-gray-100' : ''}"
                            ${isExisting ? 'readonly' : ''}>
                    </td>

                    <!-- Quantity (ALWAYS EDITABLE) -->
                    <td class="p-2 text-center">
                        <div class="flex justify-center items-center gap-1">
                            <button type="button" onclick="changeQty(this,-1)">−</button>

                            <input type="number"
                                name="items[${index}][qty]"
                                value="${item.Quantity ?? 1}"
                                min="1"
                                class="w-14 text-center border">

                            <button type="button" onclick="changeQty(this,1)">+</button>
                        </div>
                    </td>

                    <!-- Item Type -->
                    <td class="p-2">
                        <select name="items[${index}][type]"
                                class="border px-2 py-1 w-full ${isExisting ? 'bg-gray-100' : ''}"
                                ${isExisting ? 'disabled' : ''}>
                            <option value="MenuItem" ${item.ItemType==='MenuItem'?'selected':''}>MenuItem</option>
                            <option value="Medicine" ${item.ItemType==='Medicine'?'selected':''}>Medicine</option>
                        </select>

                        <!-- Disabled select won't submit → add hidden input -->
                        ${isExisting ? `
                            <input type="hidden"
                                name="items[${index}][type]"
                                value="${item.ItemType}">
                        ` : ''}
                    </td>

                    <td class="p-2 text-center text-gray-400">
                        ${isExisting ? 'Existing' : 'New'}
                    </td>
                </tr>
            `;
        }

        function addEmptyItem() {
            const container = document.getElementById('orderItemsContainer');
            const index = container.children.length;
            container.insertAdjacentHTML('beforeend', itemRow({}, index));
        }

        function changeQty(btn, delta) {
            const input = btn.parentElement.querySelector('input[type="number"]');
            let value = parseInt(input.value) || 1;
            value = Math.max(1, value + delta);
            input.value = value;
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script> --}}
@endpush


    