<div class="px-6 py-4 border-b">
    <h2 class="font-semibold text-gray-800">Orders List</h2>
</div>

<div class="overflow-x-auto" id="tableData">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left">SN</th>
                <th class="px-4 py-2 text-left">Product Name</th>
                <th class="px-4 py-2 text-left">Qty</th>
                {{-- <th class="px-4 py-2">Product Type</th> --}}
                <!-- <th class="px-4 py-2">Total Amount</th> -->
                {{-- <th class="px-4 py-2">Delivery Address</th> --}}
                {{-- <th class="px-4 py-2">Customer Name</th> --}}
                {{-- <th class="px-4 py-2">Contact No.</th> --}}
                <!-- <th class="px-4 py-2">Prescription require</th> -->
                <th class="px-4 py-2 text-left">Assign Store</th>
                <th class="px-4 py-2 text-left">Assign Delivery Man</th>
                <th class="px-4 py-2">Status</th>
                <!-- <th class="px-4 py-2">Date</th> -->
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
                            @if($order->Status == 2)
                                <div>
                                    @if($order->RequiresPrescription && $order->PrescriptionImageUrl && $order->OrderDescription)
                                        <a href="{{$order->PrescriptionImageUrl}}" data-no-loader target="_blank" rel="noopener noreferrer"  class="text-sm bg-green-200 text-gray-400 ">
                                            <i>Prescription Review is Pending(Image+Notes)Uploaded</i>
                                        </a>
                                    @elseif($order->RequiresPrescription && $order->PrescriptionImageUrl && !$order->OrderDescription)
                                        <a href="{{$order->PrescriptionImageUrl}}" data-no-loader target="_blank" rel="noopener noreferrer" class="text-sm bg-green-200 text-gray-400 ">
                                            <i>Prescription Review is Pending(Image)Uploaded</i>
                                        </a>
                                    @elseif(!$order->RequiresPrescription && !$order->PrescriptionImageUrl && $order->OrderDescription)
                                        <a href="#" class="text-sm bg-green-200 text-gray-400 ">
                                            <i>Review is Pending(Notes)Uploaded</i>
                                        </a>
                                    {{-- @elseif($order->RequiresPrescription && !$order->PrescriptionImageUrl && !$order->OrderDescription)
                                        <a href="#" class="text-sm bg-green-200 text-gray-400 ">
                                            <i>Prescription Review is Pending(Not Uploaded)</i>
                                        </a> --}}
                                    {{-- @elseif(!$order->RequiresPrescription)
                                        <a href="#" class="text-sm bg-red-100 text-gray-400 ">
                                            <i>Wrong Order for Medicine</i>
                                        </a> --}}
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
                    {{-- <td class="px-4 py-2 text-center">
                        {{ $order->TotalAmount ?? 'N/A' }}
                    </td>  --}}

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
                    {{-- <td class="px-4 py-2 text-gray-600 text-center">
                        @if($order->RequiresPrescription && $order->PrescriptionImageUrl && $order->OrderDescription)
                            <a href="https://pcsdecom.azurewebsites.net{{$order->PrescriptionImageUrl}}" class="text-gray-800 py-1 px-2 bg-green-100 hover:bg-green-400 rounded"> 
                                Yes/Uploaded   
                            </a> 
                        @elseif($order->RequiresPrescription && $order->PrescriptionImageUrl && !$order->OrderDescription)
                            <a href="https://pcsdecom.azurewebsites.net{{$order->PrescriptionImageUrl}}" class="text-gray-800 py-1 px-2 bg-green-100 hover:bg-green-400 rounded"> 
                                Yes/Uploaded   
                            </a> 
                        @elseif(!$order->RequiresPrescription && !$order->PrescriptionImageUrl && $order->OrderDescription)
                            <a href="#" class="text-gray-600 py-1 px-2 bg-red-100 rounded"> 
                                Not   
                            </a> 
                        @elseif(!$order->RequiresPrescription && !$order->PrescriptionImageUrl && !$order->OrderDescription)
                            <a href="#" class="text-gray-600 py-1 px-2 bg-red-100 rounded"> 
                                Not  
                            </a>
                        @endif
                    </td> --}}

                    {{-- Assign Stores --}}
                    {{-- <td class="px-4 py-2 text-center">
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
                    </td> --}}

                    <td class="px-4 py-2">
                        <select
                            class="assign-store px-2 py-1 text-sm bg-transparent
                            {{ in_array($order->Status, [5])
                                ? 'border rounded cursor-pointer appearance-auto'
                                : 'border-0 pointer-events-none text-gray-600 appearance-none' }}"
                            data-order-id="{{ $order->OrderId }}"
                            {{ !in_array($order->Status, [5]) ? 'disabled' : '' }}
                        >
                            <option value="">Assign Store</option>

                            @foreach($allMedicalStores as $store)
                                <option value="{{ $store->MedicalStoreId }}"
                                    {{ $order->BusinessId == $store->MedicalStoreId ? 'selected' : '' }}>
                                    {{ $store->Name }}
                                </option>
                            @endforeach
                        </select>
                    </td>

                    {{-- Assign Delivery man --}}
                    <td class="px-4 py-2">

                        {{-- <form method="POST" action="{{ route('orders.assign-deliveryman') }}" class="assign-delivery-form">
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

                            <select
                                name="delivery_man_id"
                                class="assign-deliveryman px-2 py-1 text-sm bg-transparent
                                {{ $order->Status == 7
                                    ? 'border rounded cursor-pointer appearance-auto'
                                    : 'border-0 pointer-events-none text-gray-600 appearance-none' }}"
                                {{ $order->Status != 7 ? 'disabled' : '' }}
                            >
                                <option value="">Assign Delivery Man</option>

                                @foreach($allDeliveryMan as $deliveryMan)
                                    <option value="{{ $deliveryMan->DeliveryManId }}"
                                        {{ $order->DeliveryManId == $deliveryMan->DeliveryManId ? 'selected' : '' }}>
                                        {{ $deliveryMan->user->Name ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                        </form>

                    </td>

                    {{-- Status --}}
                    <td class="px-4 py-2 text-center">
                        <span class="order-status-text" data-order-id="{{ $order->OrderId }}">
                            @if($order->Status == 1)
                                <p class="py-1 px-2 bg-yellow-300 rounded" >
                                    Pending
                                </p>
                            @elseif($order->Status == 2)
                                <p class="py-1 px-2 bg-yellow-100 rounded" >
                                    Pending Review
                                </p>
                            @elseif($order->Status == 3)
                                <p class="py-1 px-2 bg-gray-300 rounded" >
                                    Assigned
                                </p>
                            @elseif($order->Status == 4)
                                <p class="py-1 px-2 bg-blue-500 text-white rounded" >
                                    Accepted
                                </p>
                            @elseif($order->Status == 5)
                                <p class="py-1 px-2 bg-black-200 text-white rounded" >
                                    Rejected
                                </p>
                            @elseif($order->Status == 6)
                                <p class="py-1 px-2 bg-green-200 rounded" >
                                    Preparing
                                </p>
                            @elseif($order->Status == 7)
                                <p class="py-1 px-2 bg-green-200 rounded" >
                                    Packed
                                </p>
                            @elseif($order->Status == 8)
                                <p class="py-1 px-2 bg-green-200 rounded" >
                                    Shipping
                                </p>
                            @elseif($order->Status == 9)
                                <p class="py-1 px-2 text-red-500 rounded" >
                                    Cancelled
                                </p>
                            @elseif($order->Status == 10)
                                <p class="py-1 px-2 bg-green-600 text-white rounded" >
                                    Completed
                                </p>
                            @endif
                        </span>
                    </td>

                    {{-- Date --}}
                    {{-- <td class="px-4 py-2 text-center">
                        {{ $order->CreatedAt->format('Y-m-d') }}
                    </td>  --}}

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
                            class="text-gray-600 py-1 px-2  hover:bg-indigo-600 hover:text-white rounded">
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
                                <button type="submit" class="text-red-500 py-1 px-2 hover:bg-red-500 hover:text-white rounded-lg disabled:opacity-50 disabled:cursor-not-allowed" 
                                    {{ in_array($order->Status, [3, 4, 6, 7, 8, 9, 10]) ? 'disabled' : '' }}
                                >
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