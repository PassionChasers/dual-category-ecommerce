@extends('layouts.admin.app')

@section('title', 'Order Details')

@push('styles')
<style>
    .thumb-lg { width:160px; height:160px; object-fit:cover; border-radius:8px; }

    @media print {
        @page { size: A4; margin:18mm; }
        .no-print, #addMedicineForm { display: none !important; }
    }
</style>

<!-- jQuery UI CSS for autocomplete -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
@endpush

@section('contents')
<div class="flex-1 overflow-auto bg-gray-50 p-4 md:p-6">

    {{-- Header --}}
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

    {{-- Customer Info --}}
    <div class="mb-6 flex items-start justify-between">
        <ul>
            <h3><b>Customer Information :</b></h3>
            <h4>Date : {{$order->CreatedAt}}</h4>
            <li>Name : {{ $order->customer->Name ?? 'N/A' }}</li>
            <li>Contact : {{ $order->customer->user->Phone ?? 'N/A' }}</li>
            <li>Delivery Address : {{ $order->DeliveryAddress ?? 'N/A' }}</li>

            @if($order->RequiresPrescription && $order->PrescriptionImageUrl)
                <li class="mt-2">
                    <a
                        href="https://pcsdecom.azurewebsites.net{{ $order->PrescriptionImageUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-block px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700"
                    >
                        View Prescription
                    </a>
                </li>
            @endif
        </ul>
    </div>

    

    {{-- Order Items Table --}}
    <div class="bg-white rounded-md">
        <h3 class="text-lg font-semibold mb-4">Ordered Items :</h3>
    </div>

    <div class="overflow-x-auto">

        {{-- Add Medicine Button --}}
        @if($order->RequiresPrescription && $order->PrescriptionImageUrl && $order->Status !== 'Completed' && $order->Status === 'PendingReview')
        <div class="mb-4">
            <button type="button" onclick="toggleAddMedicineForm()"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                + Add Medicine
            </button>
        </div>
        @endif

        {{-- Add Medicine Form --}}
        @if($order->RequiresPrescription && $order->PrescriptionImageUrl && $order->Status !== 'Completed' && $order->Status === 'PendingReview')
        <div id="addMedicineForm" class="hidden mt-6 bg-white p-6 rounded-lg border shadow">
            <h3 class="text-lg font-semibold mb-4">Add Medicine to Order</h3>

            <form method="POST" action="{{ route('order-items.store') }}">
                @csrf

                {{-- Hidden OrderId --}}
                <input type="hidden" name="OrderId" value="{{ $order->OrderId }}">

                {{-- Medicine Input --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Medicine</label>
                    <input type="text" id="medicineInput" placeholder="Type medicine name..." 
                        class="w-full border rounded px-3 py-2">
                    <input type="hidden" name="MedicineId" id="medicineId">
                </div>

                {{-- Quantity --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Quantity</label>
                    <input type="number" name="Quantity" value="1" min="1" required 
                        class="w-24 border rounded px-3 py-2">
                </div>

                {{-- Unit Price --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Unit Price</label>
                    <input type="number" step="0.01" name="UnitPriceAtOrder" id="unitPrice" required 
                        class="w-full border rounded px-3 py-2">
                </div>

                {{-- Submit --}}
                <div class="flex gap-2">
                    <button type="submit"
                        onclick="this.disabled=true;this.form.submit();"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Save Medicine
                    </button>

                    <button type="button" onclick="toggleAddMedicineForm()"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
        @endif

        {{-- Items Table --}}
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="px-4 py-2 border-b">#</th>
                    <th class="px-4 py-2 border-b">Product Image</th>
                    <th class="px-4 py-2 border-b">Product Name</th>
                    <th class="px-4 py-2 border-b">Quantity</th>
                    <th class="px-4 py-2 border-b">Product Type</th>
                    <th class="px-4 py-2 border-b">Require Prescriptions</th>
                    <th class="px-4 py-2 border-b">Unit Price</th>
                    <th class="px-4 py-2 border-b">Total (qty*unit)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $key => $item)
                <tr class="text-center border-b">
                    <td>{{ $key + 1 }}</td>
                    <td class="px-4 py-2">
                        @if($item->medicine)
                            <img src="https://pcsdecom.azurewebsites.net{{ $item->medicine->ImageUrl }}" 
                                alt="{{ $item->medicine->Name }}" 
                                class="w-12 h-12 object-cover rounded mx-auto">
                        @endif
                    </td>
                    <td class="px-4 py-2 font-semibold">{{ $item->medicine->Name ?? 'N/A' }}</td>
                    <td class="px-4 py-2 font-semibold">{{ $item->Quantity ?? 'N/A' }}</td>
                    <td class="px-4 py-2 font-semibold">{{ $item->ItemType ?? 'N/A' }}</td>
                    <td class="px-4 py-2 font-semibold">{{ $item->medicine->PrescriptionRequired ? 'Yes' : 'No' }}</td>
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
<!-- jQuery and jQuery UI -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
    // Toggle Add Medicine Form
    function toggleAddMedicineForm() {
        document.getElementById('addMedicineForm').classList.toggle('hidden');
    }

    // Print button
    document.getElementById('printBtn')?.addEventListener('click', () => {
        window.print();
    });

    $(document).ready(function() {

        // Prepare medicines array
        var medicines = [
            @foreach($medicines as $medicine)
            { 
                id: '{{ $medicine->MedicineId }}', 
                label: '{{ $medicine->Name }}', 
                value: '{{ $medicine->Name }}', 
                price: '{{ $medicine->Price }}' 
            },
            @endforeach
        ];

        // Initialize autocomplete
        $("#medicineInput").autocomplete({
            source: medicines,
            minLength: 1, // start showing suggestions after 1 character
            select: function(event, ui) {
                // Fill hidden fields
                $("#medicineId").val(ui.item.id);
                $("#unitPrice").val(ui.item.price);
            }
        });
    });
</script>
@endpush
