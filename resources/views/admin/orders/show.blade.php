@extends('layouts.admin.app')

@section('title', 'Order Details')

@push('styles')
    <style>
        .thumb-lg { width:160px; height:160px; object-fit:cover; border-radius:8px; }

        @media print {
            @page { size: A4; margin:18mm; }
            .no-print { display:none !important; }
        }
    </style>
@endpush

@section('contents')
    <div class="flex-1 p-6 bg-gray-50 min-h-screen">

        {{-- Header --}}
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Order #{{ $order->order_number }}</h1>
                <p class="text-gray-500 mt-1">Details of this order</p>
            </div>

            <div class="no-print flex gap-2 items-center">
                <a href="{{ route('orders.index') }}" class="px-3 py-2 bg-gray-100 rounded hover:bg-gray-200">Back</a>
                <button id="printBtn" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Print</button>
            </div>
        </div>

        <div class="overflow-x-scroll overflow-y-scroll h-[500px]">
            {{-- Customer & Store Info --}}
            <div class="bg-white rounded-lg shadow overflow-hidden px-6 py-6 grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                {{-- Customer Info --}}
                <div>
                    <h2 class="font-semibold text-gray-700 mb-2">Customer Info</h2>
                    <p class="text-gray-600">{{ $order->user->name ?? '-' }}</p>
                    <p class="text-gray-600">{{ $order->user->contact_number ?? '-' }}</p>
                    <p class="text-gray-600">{{ $order->delivery_address ?? '-' }}</p>
                </div>

                {{-- Store / Restaurant Info --}}
                <div>
                    <h2 class="font-semibold text-gray-700 mb-2">Store / Restaurant</h2>
                    <p class="text-gray-600">{{ $order->medicalstore?->Name ?? $order->restaurant?->Name ?? '-' }}</p>
                    <p class="text-gray-600 text-sm">{{ ucfirst($order->order_type) }}</p>
                </div>

                {{-- Payment Info --}}
                <div>
                    <h2 class="font-semibold text-gray-700 mb-2">Payment Info</h2>
                    <p class="text-gray-600">Method: {{ strtoupper($order->payment_method) }}</p>
                    <p class="text-gray-600">
                        Status: 
                        @if($order->payment_status == 'paid')
                            <span class="text-green-700">Paid</span>
                        @elseif($order->payment_status == 'pending')
                            <span class="text-yellow-700">Pending</span>
                        @else
                            <span class="text-red-700">Failed</span>
                        @endif
                    </p>
                    <p class="text-gray-600">Subtotal: ₹{{ number_format($order->subtotal,2) }}</p>
                    <p class="text-gray-600">Delivery: ₹{{ number_format($order->delivery_charge,2) }}</p>
                    <p class="text-gray-600">Tax: ₹{{ number_format($order->tax,2) }}</p>
                    <p class="text-gray-600">Discount: ₹{{ number_format($order->discount,2) }}</p>
                    <p class="text-gray-800 font-semibold">Total: ₹{{ number_format($order->total_amount,2) }}</p>
                </div>

                {{-- Order Status & Notes --}}
                <div>
                    <h2 class="font-semibold text-gray-700 mb-2">Order Status & Notes</h2>
                    <p class="text-gray-600">
                        Status: 
                        @switch($order->order_status)
                            @case('pending') <span class="text-yellow-700">Pending</span> @break
                            @case('accepted') <span class="text-blue-700">Accepted</span> @break
                            @case('preparing') <span class="text-orange-700">Preparing</span> @break
                            @case('packed') <span class="text-purple-700">Packed</span> @break
                            @case('out_for_delivery') <span class="text-indigo-700">Out for Delivery</span> @break
                            @case('delivered') <span class="text-green-700">Delivered</span> @break
                            @case('cancelled') <span class="text-red-700">Cancelled</span> @break
                            @default <span class="text-gray-700">Unknown</span>
                        @endswitch
                    </p>
                    <p class="text-gray-600 mt-2">Notes: {{ $order->notes ?? '-' }}</p>
                </div>

                {{-- Prescription (if medicine) --}}
                @if($order->order_type === 'medicine')
                <div>
                    <h2 class="font-semibold text-gray-700 mb-2">Prescription</h2>
                    @if($order->prescription_image)
                        <img src="{{ asset('storage/'.$order->prescription_image) }}" alt="Prescription" class="thumb-lg mb-2">
                        <p class="text-sm">
                            Verified: {!! $order->prescription_verified ? '<span class="text-green-700">Yes</span>' : '<span class="text-red-700">No</span>' !!}
                        </p>
                    @else
                        <p class="text-gray-500">No prescription uploaded</p>
                    @endif
                </div>
                @endif
            </div>

            {{-- Order Items Table --}}
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 overflow-x-scroll overflow-y-scroll h-[500px]">
                    <h2 class="font-semibold text-gray-700 mb-4">Order Items</h2>

                    @if($order->items && $order->items->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2">#</th>
                                    <th class="px-4 py-2">Product / Medicine</th>
                                    <th class="px-4 py-2">Quantity</th>
                                    <th class="px-4 py-2">Price (₹)</th>
                                    <th class="px-4 py-2">Total (₹)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($order->items as $index => $item)
                                <tr>
                                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2">{{ $item->product_name }}</td>
                                    <td class="px-4 py-2">{{ $item->quantity }}</td>
                                    <td class="px-4 py-2">{{ number_format($item->price, 2) }}</td>
                                    <td class="px-4 py-2">{{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-100">
                                <tr>
                                    <th colspan="4" class="px-4 py-2 text-right font-semibold">Subtotal</th>
                                    <th class="px-4 py-2 font-semibold">₹{{ number_format($order->subtotal, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="px-4 py-2 text-right font-semibold">Delivery Charge</th>
                                    <th class="px-4 py-2 font-semibold">₹{{ number_format($order->delivery_charge, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="px-4 py-2 text-right font-semibold">Tax</th>
                                    <th class="px-4 py-2 font-semibold">₹{{ number_format($order->tax, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="px-4 py-2 text-right font-semibold">Discount</th>
                                    <th class="px-4 py-2 font-semibold">₹{{ number_format($order->discount, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="4" class="px-4 py-2 text-right font-bold text-lg">Total</th>
                                    <th class="px-4 py-2 font-bold text-lg">₹{{ number_format($order->total_amount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    @else
                        <p class="text-gray-500">No items found for this order.</p>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t text-sm text-gray-600 flex justify-between">
                    <div>Order Created: {{ $order->created_at->format('Y-m-d H:i') }}</div>
                    <div class="no-print">
                        <a href="{{ route('orders.index') }}" class="px-3 py-2 bg-gray-100 rounded hover:bg-gray-200">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('printBtn')?.addEventListener('click', () => {
            window.print();
        });
    </script>
@endpush
