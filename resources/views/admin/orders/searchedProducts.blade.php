<div class="px-6 py-4 border-b">
            <h2 class="font-semibold text-gray-800">Orders List</h2>
        </div>
<div class="overflow-x-auto" id="tableData">
<table class="min-w-full divide-y divide-gray-200 text-sm">
    <thead class="bg-gray-100">
        <tr>
            <th class="px-4 py-2">SN</th>
            {{-- <th class="px-4 py-2">Order Id</th> --}}
            <th class="px-4 py-2">Products</th>
            <th class="px-4 py-2">Customer</th>
            <th class="px-4 py-2">Contact</th>
            <th class="px-4 py-2">Type</th>
            <th class="px-4 py-2">Store</th>
            {{-- <th class="px-4 py-2">Total</th> --}}
            <th class="px-4 py-2">Payment</th>
            <th class="px-4 py-2">Status</th>
            <th class="px-4 py-2">Date</th>
            <th class="px-4 py-2">Assign Rider</th>
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

                {{-- Order Number --}}
                {{-- <td class="px-4 py-2 font-semibold">
                    {{ $order->order_number }}
                </td> --}}

                {{-- Products --}}
                {{-- <td class="px-4 py-2 font-semibold">
                    <div class="max-h-20 overflow-y-auto space-y-1 pr-1">
                    @foreach($order->items as $index => $item)
                        {{ $item->product_name }}
                    @endforeach
                    </div>
                </td> --}}
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
                    {{ $order->medicalstore->Name ?? $order->restaurant->Name ?? 'N/A' }}
                </td>

                {{-- Total --}}
                {{-- <td class="px-4 py-2 font-semibold">
                    Rs. {{ number_format($order->total_amount, 2) }}
                </td> --}}

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