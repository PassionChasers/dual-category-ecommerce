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
    <div class="flex-1 overflow-auto bg-gray-50 p-4 md:p-6">

        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-800">#Order Number : {{ $order->OrderNumber }}</h2>

            <div class="mb-6 flex items-center gap-2 justify-between">
                <a href="{{ route('orders.medicine.index') }}"
                    class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 rounded hover:bg-gray-200 text-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <button id="printBtn" class="no-print px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    <i class="fas fa-print mr-2"></i> Print Order
                </button>
            </div>
        </div>
        {{-- Header --}}
        <div class="mb-6 flex items-center justify-between">
            <ul>
                <h3><b>Customer Information :</b></h3>
                <h4>Date : {{$order->CreatedAt}}</h4>
                <li>
                    Name : {{ $order->customer->Name ?? 'N/A' }}
                </li>
                <li>
                    Contact : {{ $order->customer->user->Phone ?? 'N/A' }}
                </li>
                <li>
                    Delivery Address : {{ $order->DeliveryAddress ?? 'N/A' }}
                </li>
                @if($order->RequiresPrescription && $order->PrescriptionImageUrl)
                    <li class="mt-2">
                        <button
                            type="button"
                            onclick="togglePrescription()"
                            class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700"
                        >
                            View Prescription
                        </button>

                        {{-- Prescription Image (Hidden by default) --}}
                        <div id="prescriptionBox" class="hidden mt-3">
                            <img
                                src="https://pcsdecom.azurewebsites.net{{$order->PrescriptionImageUrl}}"
                                alt="Prescription Image"
                                class="w-64 h-auto border rounded shadow"
                            >
                        </div>
                    </li>
                @endif
            </ul>
        </div>
        
        {{-- Order Items Table --}}
        <div class="bg-white rounded-md">
            <h3 class="text-lg font-semibold mb-4">Ordered Items :</h3>
        </div>

        <div class="overflow-x-auto">
            @if($order->RequiresPrescription && $order->PrescriptionImageUrl)
                <div class="mb-4">
                    <button
                        {{-- onclick="toggleAddMedicineForm()" --}}
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                    >
                        + Add Medicine
                    </button>
                </div>
            @endif

            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="px-4 py-2 border-b">#</th>
                        <th class="px-4 py-2 border-b">Product Image</th>
                        <th class="px-4 py-2 border-b">Product Name</th>
                        <th class="px-4 py-2 border-b">Quantity</th>
                        <th class="px-4 py-2 border-b">Product Type</th>
                        <th class="px-4 py-2 border-b">RequirePrescriptions</th>
                        <th class="px-4 py-2 border-b">Unit Price</th>
                        <th class="px-4 py-2 border-b">Total(qty*unit)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $key => $item)
                    <tr class="text-center border-b">
                        <td>{{ $key + 1 }}</td>
                        <td class="px-4 py-2">
                            {{-- <img src="{{ asset('storage/products/' . $item->ItemImageUrl) }}" alt="{{ $item->ItemName }}" class="thumb-lg mx-auto" /> --}}
                            <img src="https://pcsdecom.azurewebsites.net{{$item->medicine->ImageUrl}}" alt="{{ $item->ItemName }}" class="w-12 h-12 object-cover rounded mx-auto">
                        </td>
                        <td class="px-4 py-2 font-semibold">{{ $item->medicine->Name ?? 'N/A' }}</td>
                        <td class="px-4 py-2 font-semibold">{{ $item->Quantity ?? 'N/A' }}</td>
                        <td class="px-4 py-2 font-semibold">{{ $item->ItemType ?? 'N/A' }}</td>
                        <td class="px-4 py-2 font-semibold">{{ $item->medicine->PrescriptionRequired ? 'Yes' : 'No'}}</td>
                        <td class="px-4 py-2 font-semibold">Rs.{{ number_format((float)$item->UnitPriceAtOrder, 2) }}</td>
                        <td class="px-4 py-2 font-semibold">Rs.{{ number_format((float)$item->UnitPriceAtOrder * (float)$item->Quantity, 2) }}</td>
                    </tr>
                    @endforeach
                    <tr class="text-center">
                        <td colspan="6" class="px-4 py-2 font-bold">Total Amount:</td>
                        <td class="px-4 py-2 font-bold">Rs.{{ number_format($order->TotalAmount, 2) ?? 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>

        //for print button 
        document.getElementById('printBtn')?.addEventListener('click', () => {
            window.print();
        });

        //for view prescription image 
        function togglePrescription() {
            const box = document.getElementById('prescriptionBox');
            box.classList.toggle('hidden');
        }

        //Form for Add medicine 
        //  function toggleAddMedicineForm() {
        //     document.getElementById('addMedicineForm')
        //         .classList.toggle('hidden');
        // }
    </script>
@endpush
