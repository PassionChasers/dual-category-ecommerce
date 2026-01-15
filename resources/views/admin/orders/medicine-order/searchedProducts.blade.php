@forelse($allOrders as $order)
    <tr>
        {{-- Serial --}}
        <td class="px-4 py-2 text-center">
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
                @if($order->Status == 2)
                    <div>
                        @if($order->RequiresPrescription && $order->PrescriptionImageUrl && $order->OrderDescription)
                            <a href="https://pcsdecom.azurewebsites.net{{$order->PrescriptionImageUrl}}" class="text-sm bg-green-200 text-gray-400 ">
                                <i>Prescription Review is Pending(Image+Notes)Uploaded</i>
                            </a>
                        @elseif($order->RequiresPrescription && $order->PrescriptionImageUrl && !$order->OrderDescription)
                            <a href="https://pcsdecom.azurewebsites.net{{$order->PrescriptionImageUrl}}" class="text-sm bg-green-200 text-gray-400 ">
                                <i>Prescription Review is Pending(Image)Uploaded</i>
                            </a>
                        @elseif($order->RequiresPrescription && !$order->PrescriptionImageUrl && $order->OrderDescription)
                            <a href="#" class="text-sm bg-green-200 text-gray-400 ">
                                <i>Prescription Review is Pending(Notes)Uploaded</i>
                            </a>
                        @elseif($order->RequiresPrescription && !$order->PrescriptionImageUrl && !$order->OrderDescription)
                            <a href="#" class="text-sm bg-green-200 text-gray-400 ">
                                <i>Prescription Review is Pending(Not Uploaded)</i>
                            </a>
                        @elseif(!$order->RequiresPrescription)
                            <a href="#" class="text-sm bg-red-100 text-gray-400 ">
                                <i>Wrong Order for Medicine</i>
                            </a>
                        @endif
                    </div>
                @elseif ($order->Status != 2) 
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
                @endif
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
                @if($order->Status == 2)
                    <div class="text-sm text-gray-500">
                        xxx
                    </div>
                @elseif ($order->Status != 2)
                    @foreach($order->items as $item)
                        <div class="text-sm">
                            {{ $item->Quantity }}  
                        </div>
                    @endforeach
                @endif
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
        {{-- <td class="px-4 py-2">
            {{ $order->DeliveryAddress ?? 'N/A' }}
        </td> --}}

        {{-- Customer Name --}}
        {{-- <td class="px-4 py-2">
            {{ $order->customer->Name ?? 'N/A' }}
        </td> --}}

        {{-- Contact --}}
        {{-- <td class="px-4 py-2 text-gray-600">
            {{ $order->customer->user->Phone ?? 'N/A' }}
        </td> --}}

        {{-- prescriptions--}}
        <td class="px-4 py-2 text-gray-600 text-center">
            @if($order->RequiresPrescription && $order->PrescriptionImageUrl && $order->OrderDescription)
                <a href="https://pcsdecom.azurewebsites.net{{$order->PrescriptionImageUrl}}" class="text-gray-800 py-1 px-2 bg-green-100 hover:bg-green-400 rounded"> 
                    Yes/Uploaded   
                </a> 
            @elseif($order->RequiresPrescription && !$order->PrescriptionImageUrl && $order->OrderDescription)
                <a href="#" class="text-gray-800 py-1 px-2 bg-green-100 hover:bg-green-400 rounded"> 
                    Yes/Notes Uploaded   
                </a> 
            @elseif($order->RequiresPrescription && !$order->PrescriptionImageUrl && !$order->OrderDescription)
                <a href="#" class="text-gray-800 py-1 px-2 bg-green-100 hover:bg-red-400 rounded"> 
                    Yes/Not Uploaded   
                </a> 
            @elseif(!$order->RequiresPrescription)
                <a href="#" class="text-gray-600 py-1 px-2 bg-red-100 rounded"> 
                    Not  
                </a>
            @endif
        </td>

        {{-- Assign Stores --}}
        <td class="px-4 py-2 text-center">
            <select class="assign-store border rounded px-2 py-1 text-sm" data-order-id="{{ $order->OrderId }}"     
                @if($order->Status == 10 || $order->Status == 9 || $order->Status == 8 || $order->Status == 7 || $order->Status == 6 || $order->Status == 4 || $order->Status == 3 || $order->Status == 2 )
                    disabled
                @endif
            >
                <option value="">Assign Store</option>
                @foreach($allMedicalStores as $store)
                    <option value="{{ $store->MedicalStoreId }}" {{ $order->BusinessId == $store->MedicalStoreId ? 'selected' : '' }}>
                        {{ $store->Name }}
                    </option>
                @endforeach
            </select>
        </td>

        {{-- Status --}}
        <td class="px-4 py-2 text-center">
            <span class="order-status-text" data-order-id="{{ $order->OrderId }}">
                @if($order->Status == 1)
                    Pending
                @elseif($order->Status == 2)
                    Pending Review
                @elseif($order->Status == 3)
                    Assigned
                @elseif($order->Status == 4)
                    Accepted
                @elseif($order->Status == 5)
                    Rejected
                @elseif($order->Status == 6)
                    Preparing
                @elseif($order->Status == 7)
                    Packed
                @elseif($order->Status == 8)
                    Shipping
                @elseif($order->Status == 9)
                    Cancelled
                @elseif($order->Status == 10)
                    Completed
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
                class="text-gray-600 py-1 px-2 hover:text-gray-900 hover:bg-green-400 rounded">
                    {{-- <i class="fas fa-eye"></i> --}} view
                </a>

                {{-- Cancel --}}
                <form method="POST"
                    action="{{ route('orders.cancel', $order->OrderId) }}"
                    class="cancel-form" data-status="{{ $order->Status }}">
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