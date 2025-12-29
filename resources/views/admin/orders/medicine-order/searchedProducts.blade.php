<div class="px-6 py-4 border-b">
            <h2 class="font-semibold text-gray-800">Orders List</h2>
        </div>
<div class="overflow-x-auto" id="tableData">
<table class="min-w-full divide-y divide-gray-200 text-sm">
    <thead class="bg-gray-100">
        <tr>
            <th class="px-4 py-2">SN</th>
            <th class="px-4 py-2">Product Name</th>
            <th class="px-4 py-2">Quantity</th>
            <th class="px-4 py-2">Product Type</th>
            <th class="px-4 py-2">Total Amount</th>
            <th class="px-4 py-2">Delivery Address</th>
            <th class="px-4 py-2">Customer Name</th>
            <th class="px-4 py-2">Contact No.</th>
            {{-- <th class="px-4 py-2">Assign Delivery Man</th> --}}
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
                                {{ $item->ItemName }} 
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
                <td class="px-4 py-2 font-semibold">
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
                </td>

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
                <td class="px-4 py-2 text-gray-600">
                    {{ $order->customer->user->Phone ?? 'N/A' }}
                </td>

                {{-- Delivery Man --}}
                {{-- <td class="px-4 py-2 text-gray-600">
                    {{ $order-> ?? 'N/A' }}
                </td> --}}
                {{-- Assign Rider --}}
                {{-- <td class="px-4 py-2">
                    <select class="border rounded px-2 py-1 text-sm">
                        <option value="">Select Rider</option> --}}
                        {{-- loop riders here --}}
                    {{-- </select>
                </td> --}}

                {{-- Status --}}
                <td class="px-4 py-2">
                    <span class="px-2 py-1 rounded text-xs
                        @switch($order->Status)
                            @case('pending') bg-yellow-100 text-yellow-800 @break
                            @case('accepted') bg-blue-100 text-blue-800 @break
                            @case('preparing') bg-orange-100 text-orange-800 @break
                            @case('packed') bg-purple-100 text-purple-800 @break
                            @case('out_for_delivery') bg-indigo-100 text-indigo-800 @break
                            @case('Completed') bg-green-100 text-green-800 @break
                            @case('Cancelled') bg-red-100 text-red-800 @break
                        @endswitch
                    ">
                        {{ ucfirst(str_replace('_',' ', $order->Status)) }}
                    </span>
                </td>

                {{-- Date --}}
                <td class="px-4 py-2">
                    {{ $order->CreatedAt->format('Y-m-d') }}
                </td>

                {{-- Actions --}}
                <td class="px-4 py-2">
                    <div class="flex items-center justify-center gap-3 h-full">

                        {{-- VIEW --}}
                        <a href="{{ route('orders.show', $order->OrderId) }}"
                        class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-eye"></i>
                        </a>

                        {{-- EDIT --}}
                        <a href="javascript:void(0)"
                        class="text-indigo-600 hover:text-indigo-800 edit-btn"
                        data-id="{{ $order->OrderId }}">
                            <i class="fas fa-edit"></i>
                        </a>

                        {{-- DELETE --}}
                        <form method="POST"
                            action="{{ route('orders.destroy', $order->OrderId) }}"
                            class="delete-form"
                            data-status="{{ $order->Status }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="search" id="current-search" value="{{ request('search') }}">
                            <input type="hidden" name="onlineStatus" id="current-onlineStatus" value="{{ request('onlineStatus') }}">
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
    });
</script>