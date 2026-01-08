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
            {{-- <th class="px-4 py-2">Product Type</th> --}}
            <th class="px-4 py-2">Total Amount</th>
            <th class="px-4 py-2">Delivery Address</th>
            <th class="px-4 py-2">Customer Name</th>
            {{-- <th class="px-4 py-2">Contact No.</th> --}}
            <th class="px-4 py-2">Assign Store</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Date</th>
            <th class="px-4 py-2">Actions</th>
        </tr>
    </thead>

    <tbody class="divide-y divide-gray-200">
        @forelse($allOrders as $order)
            <tr>
                {{-- Serial --}}
                <td class="px-4 py-2">
                    {{ ($allOrders->currentPage() - 1) * $allOrders->perPage() + $loop->iteration }}
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
                                {{-- {{ $item->ItemName }}  --}}
                                @if($item->MedicineId)
                                    {{$item->medicine->Name}}
                                @elseif ($item->MenuItemId) 
                                    {{$item->food->Name}}
                                @endif
                            </div>
                        @endforeach
                    </div>
                </td>

                {{-- Quantity --}}
                <td class="px-4 py-2 font-semibold">
                    <div
                        class="max-h-20 overflow-y-auto space-y-1
                            [&::-webkit-scrollbar]:hidden
                            [-ms-overflow-style:none]
                            [scrollbar-width:none]"
                    >
                        @foreach($order->items as $item)
                            <div class="text-sm">
                                {{ $item->Quantity }}  
                            </div>
                        @endforeach
                    </div>
                </td>

                {{-- Product Type --}}
                {{-- <td class="px-4 py-2 font-semibold">
                    <div
                        class="max-h-20 overflow-y-auto space-y-1
                            [&::-webkit-scrollbar]:hidden
                            [-ms-overflow-style:none]
                            [scrollbar-width:none]"
                    >
                        @foreach($order->items as $item)
                            <div class="text-sm">
                                {{ $item->ItemType }}
                            </div>
                        @endforeach
                    </div>
                </td> --}}

                {{-- Total Amount --}}
                <td class="px-4 py-2">
                    {{ $order->TotalAmount ?? 'N/A' }}
                </td>

                {{-- Delivery Address --}}
                <td class="px-4 py-2">
                    {{ $order->DeliveryAddress ?? 'N/A' }}
                </td>

                {{-- Customer Name --}}
                <td class="px-4 py-2">
                    {{ $order->customer->Name ?? 'N/A' }}
                </td>

                {{-- Contact --}}
                {{-- <td class="px-4 py-2 text-gray-600">
                    {{ $order->customer->user->Phone ?? 'N/A' }}
                </td> --}}

                {{-- Assign Stores --}}
                <td class="px-4 py-2">
                    <select class="assign-store border rounded px-2 py-1 text-sm" data-order-id="{{ $order->OrderId }}">
                        <option value="">Assign Store</option>
                        @foreach($allMedicalStores as $store)
                            <option value="{{ $store->MedicalStoreId }}"
                                {{ $order->items->first() && $order->items->first()->BusinessId == $store->MedicalStoreId ? 'selected' : '' }}>
                                {{ $store->Name }}
                            </option>
                        @endforeach
                    </select>
                </td>

               {{-- Status --}}
                <td class="px-4 py-2">
                    @php
                        $statuses = ['Pending', 'Accepted', 'Preparing', 'Packed', 'Completed', 'Cancelled'];
                    @endphp

                    <select class="order-status border rounded px-2 py-1 text-sm" 
                            data-order-id="{{ $order->OrderId }}">
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ $order->Status === $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </td>

                {{-- Date --}}
                <td class="px-4 py-2">
                    {{ $order->CreatedAt->format('Y-m-d') }}
                </td>

                {{-- Actions --}}
                <td class="px-4 py-2">
                    <div class="flex items-center justify-center gap-3 h-full">
                        
                        {{-- VIEW --}}
                        @php
                            // Get unique item types for this order
                            $types = $order->items->pluck('ItemType')->unique();

                            // Convert to comma-separated string
                            $typeParam = $types->implode(','); // e.g., "Menuitem,Medicine"
                        @endphp

                        <a href="{{ route('orders.showMedicineDetail', ['id' => $order->OrderId, 'type' => $typeParam]) }}"
                        class="text-gray-600 py-1 px-2 hover:text-gray-900 hover:bg-green-400 rounded">
                            {{-- <i class="fas fa-eye"></i> --}} view
                        </a>

                        {{-- EDIT --}}
                        {{-- <button id="editBtn"
                        onclick='openEditModal(@json($order))'
                        class="text-indigo-600 hover:text-indigo-800 edit-btn"><i class="fas fa-edit"></i></button> --}}

                        {{-- Cancel --}}
                        <form method="POST"
                            action="{{ route('orders.cancel', $order->OrderId) }}"
                            class="cancel-form">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="search" id="current-search" value="{{ request('search') }}">
                            <input type="hidden" name="onlineStatus" id="current-onlineStatus" value="{{ request('onlineStatus') }}">
                            <button type="submit" class="text-red-600 py-1 px-2 hover:text-gray-900 hover:bg-red-400 rounded ">
                                {{-- <i class="fas fa-times"></i> --}}cancel
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
        Showing <strong>{{ $allOrders->firstItem() ?? 0 }}</strong> to <strong>{{ $allOrders->lastItem() ?? 0 }}</strong> of <strong>{{ $allOrders->total() }}</strong> results
    </div>
    <div class="mt-3 md:mt-0" id="pageLink">
        {{ $allOrders->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // Delete confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
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
            });
        }); 
        
        // Assign Store
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.querySelectorAll('.assign-store').forEach(select => {
            select.addEventListener('change', function () {

                const medicalStoreId = this.value;
                const orderId = this.dataset.orderId;

                if (!medicalStoreId || !orderId) return;

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
                    console.log(data); // Debug: see what server returns
                    if (data.success) {
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
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong'
                    });
                });

            });
        });

        // Update Order Status
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