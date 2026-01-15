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
            <th class="px-4 py-2">Prescription require</th>
            <th class="px-4 py-2">Assign Delivery Man</th>
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
                <td class="px-4 py-2 font-semibold text-center">
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
                <td class="px-4 py-2 font-semibold text-center">
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
                <td class="px-4 py-2 text-center">
                    {{ $order->TotalAmount ?? 'N/A' }}
                </td>

                {{-- Delivery Address --}}
                <td class="px-4 py-2 text-center">
                    {{ $order->DeliveryAddress ?? 'N/A' }}
                </td>

                {{-- Customer Name --}}
                <td class="px-4 py-2 text-center">
                    {{ $order->customer->Name ?? 'N/A' }}
                </td>

                {{-- prescriptions--}}
                {{-- <td class="px-4 py-2 text-gray-600 text-center">
                    <a href="#" class="text-gray-600 py-1 px-2 hover:text-gray-900 hover:bg-green-400 rounded"> 
                        {{ $order->RequiresPrescription ? ' Yes ' : ' No ' }}    
                    </a> 
                </td> --}}
                <td class="px-4 py-2 text-gray-600 text-center">
                    @if($order->RequiresPrescription || $order->PrescriptionImageUrl)
                        <a href="https://pcsdecom.azurewebsites.net{{$order->PrescriptionImageUrl}}" class="text-gray-800 py-1 px-2 bg-green-100 hover:bg-green-400 rounded"> 
                            Yes/Uploaded   
                        </a> 
                    @elseif($order->RequiresPrescription && !$order->PrescriptionImageUrl)
                        <a href="#" class="text-gray-800 py-1 px-2 bg-green-100 hover:bg-red-400 rounded"> 
                            Yes/Not Uploaded   
                        </a> 
                    @elseif(!$order->RequiresPrescription && !$order->PrescriptionImageUrl)
                        <a href="#" class="text-gray-600 py-1 px-2 bg-red-100 rounded"> 
                            Not  
                        </a>
                    @endif
                </td>

                {{-- Assign Delivery man --}}
                <td class="px-4 py-2 text-center">
                    {{-- <select class="assign-deliveryman border rounded px-2 py-1 text-sm" data-order-id="{{ $order->OrderId }}"
                        @if($order->Status != 7)
                            disabled
                        @endif    
                    >
                        <option value="">Assign Delivery Man</option>
                        @foreach($allDeliveryMan as $deliveryMan)
                            <option value="{{ $deliveryMan->DeliveryManId }}"
                                {{ $order->DeliveryManId == $deliveryMan->DeliveryManId ? 'selected' : '' }}
                            >
                                {{ $deliveryMan->user->Name ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select> --}}


                    {{-- <form action="{{ route('orders.assign-deliveryman') }}" method="POST" class="deliveryman-form">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->OrderId }}">
                        
                        <select name="delivery_man_id" class="assign-deliveryman border rounded px-2 py-1 text-sm"
                            @if($order->Status != 7) disabled @endif
                        >
                            <option value="">Assign Delivery Man</option>
                            @foreach($allDeliveryMan as $deliveryMan)
                                <option value="{{ $deliveryMan->DeliveryManId }}"
                                    {{ $order->DeliveryManId == $deliveryMan->DeliveryManId ? 'selected' : '' }}
                                >
                                    {{ $deliveryMan->user->Name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </form> --}}

                    <form method="POST" action="{{ route('orders.assign-deliveryman') }}" class="assign-delivery-form">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order->OrderId }}">
                        <select name="delivery_man_id" class="assign-deliveryman border rounded px-2 py-1 text-sm" 
                            @if($order->Status != 7) disabled @endif
                        >
                            <option value="">Assign Delivery Man</option>
                            @foreach($allDeliveryMan as $deliveryMan)
                                <option value="{{ $deliveryMan->DeliveryManId }}"
                                    {{ $order->DeliveryManId == $deliveryMan->DeliveryManId ? 'selected' : '' }}
                                >
                                    {{ $deliveryMan->user->Name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </form>

                </td>

               {{-- Status --}}
                <td class="px-4 py-2 text-center">
                    <span class="order-status-text" data-order-id="{{ $order->OrderId }}">
                        @php
                            $statuses = [10, 9, 8, 7, 6, 5, 4, 3, 1];
                        @endphp
                        @if($order->Status == 4)
                            <select class="order-status border rounded px-2 py-1 text-sm" data-order-id="{{ $order->OrderId }}">
                                <option value="4" {{ $order->Status == 4 ? 'selected' : '' }} disabled>
                                    Accepted
                                </option>
                                <option value="6" {{ $order->Status == 6 ? 'selected' : '' }}>
                                    Preparing
                                </option>
                                <option value="7" {{ $order->Status == 7 ? 'selected' : '' }}>
                                    Packed
                                </option>
                                <option value="8" {{ $order->Status == 8 ? 'selected' : '' }}>
                                    Shipping
                                </option>
                            </select>
                        @elseif($order->Status == 6)
                            <select class="order-status border rounded px-2 py-1 text-sm" data-order-id="{{ $order->OrderId }}">
                                <option value="6" {{ $order->Status == 6 ? 'selected' : '' }} disabled>
                                    Preparing
                                </option>
                                <option value="7" {{ $order->Status == 7 ? 'selected' : '' }}>
                                    Packed
                                </option>
                                <option value="8" {{ $order->Status == 8 ? 'selected' : '' }}>
                                    Shipping
                                </option>
                            </select>
                        @elseif($order->Status == 7)
                            <select class="order-status border rounded px-2 py-1 text-sm" data-order-id="{{ $order->OrderId }}">
                                <option value="7" {{ $order->Status == 7 ? 'selected' : '' }} disabled>
                                    Packed
                                </option>
                                <option value="8" {{ $order->Status == 8 ? 'selected' : '' }}>
                                    Shipping
                                </option>
                            </select>
                        @elseif($order->Status == 8)
                            <select class="order-status border rounded px-2 py-1 text-sm" data-order-id="{{ $order->OrderId }}">
                                <option value="8" {{ $order->Status == 8 ? 'selected' : '' }} disabled>
                                    Shipping
                                </option>
                            </select>
                        @elseif($order->Status == 5)
                            <p class="bg-red-200 py-2 text-center rounded">Rejected</p>
                        @elseif($order->Status == 3)
                            <p class="bg-green-200 py-2 text-center rounded">Assigned</p>
                        @elseif($order->Status == 10)
                            Completed
                        @elseif($order->Status == 9)
                            Cancelled
                        @endif
                    </span>
                </td>

                {{-- Date --}}
                <td class="px-4 py-2 text-center">
                    {{ $order->CreatedAt->format('Y-m-d') }}
                </td>

                {{-- Actions --}}
                <td class="px-4 py-2 text-center">
                    <div class="flex items-center justify-center gap-3 h-full">
                        
                        {{-- VIEW --}}
                        @php
                            // Get unique item types for this order
                            $types = $order->items->pluck('ItemType')->unique();

                            // Convert to comma-separated string
                            $typeParam = $types->implode(','); // e.g., "Menuitem,Medicine"
                        @endphp

                        <a href="{{ route('orders.showMedicineDetail', ['id' => $order->OrderId, 'type' => $typeParam]) }}"
                        class="text-gray-600 py-1 px-2 hover:text-gray-900 hover:bg-yellow-400 rounded">
                            {{-- <i class="fas fa-eye"></i> --}} view
                        </a>

                        {{-- Reject--}}
                        <form method="POST"
                            action="{{ route('orders.reject', $order->OrderId) }}"
                            class="cancel-form">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="search" id="current-search" value="{{ request('search') }}">
                            <input type="hidden" name="onlineStatus" id="current-onlineStatus" value="{{ request('onlineStatus') }}">
                            <button type="submit" class="text-red-600 py-1 px-2 hover:text-gray-900 hover:bg-red-400 rounded "
                                @if(in_array($order->Status, [10, 9, 8, 7, 6, 5, 4])) disabled @endif
                            >
                                {{-- <i class="fas fa-times"></i> --}}Reject
                            </button>
                        </form>

                        {{-- accept --}}
                        <form method="POST"
                            action="{{ route('orders.accept', $order->OrderId) }}"
                            class="cancel-form">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="search" id="current-search" value="{{ request('search') }}">
                            <input type="hidden" name="onlineStatus" id="current-onlineStatus" value="{{ request('onlineStatus') }}">
                            <button type="submit" class="text-green-600 py-1 px-2 hover:text-gray-900 hover:bg-green-400 rounded "
                                @if(in_array($order->Status, [10, 9, 8, 7, 6, 5, 4])) disabled @endif
                            >
                                {{-- <i class="fas fa-times"></i> --}}Accept
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
        
        //For DeliveryMan 
        // document.querySelectorAll('.assign-deliveryman').forEach(select => {
        //     select.addEventListener('change', function () {
        //         const form = this.closest('form');
        //         const deliveryManName = this.options[this.selectedIndex].text;

        //         if (this.value === "") return; // Skip if empty

        //         const confirmed = confirm(`Are you sure you want to assign "${deliveryManName}" as the delivery man?`);

        //         if (confirmed) {
        //             form.submit();
        //         } else {
        //             this.value = ""; // reset selection if cancelled
        //         }
        //     });
        // });

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
                            title: data.message || 'Failed to assign deliveryman'
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