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
                    </select>
                @elseif($order->Status == 6)
                    <select class="order-status border rounded px-2 py-1 text-sm" data-order-id="{{ $order->OrderId }}">
                        <option value="6" {{ $order->Status == 6 ? 'selected' : '' }} disabled>
                            Preparing
                        </option>
                        <option value="7" {{ $order->Status == 7 ? 'selected' : '' }}>
                            Packed
                        </option>
                    </select>
                @elseif($order->Status == 7)
                    <select class="order-status border rounded px-2 py-1 text-sm" data-order-id="{{ $order->OrderId }}">
                        <option value="7" {{ $order->Status == 7 ? 'selected' : '' }} disabled>
                            Packed
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
