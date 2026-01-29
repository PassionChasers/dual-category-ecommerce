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

            @if($order->OrderDescription)
                <li class="mt-2">
                    <h3><b>Order Description :</b></h3> {{$order->OrderDescription}}
                </li>
            @endif
            
            @if($order->RequiresPrescription && $order->PrescriptionImageUrl)
                <li class="mt-2">
                    <a href="https://pcsdecom.azurewebsites.net{{ $order->PrescriptionImageUrl }}" data-no-loader target="_blank" rel="noopener noreferrer"
                       class="inline-block px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                        View Prescription Image
                    </a>
                </li>
            @elseif($order->RequiresPrescription && !$order->PrescriptionImageUrl)
                <li class="mt-2">
                    <p class="inline-block px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">
                        Prescription Image Not Uploaded
                    </p>
                </li>
            @endif

        </ul>
    </div>

    {{-- Add Medicines Button --}}
    @if($order->Status == 2)
        @if(($order->RequiresPrescription && $order->PrescriptionImageUrl) || ($order->OrderDescription))
            <div class="mb-4">
                <button type="button" onclick="toggleAddMedicineForm()"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    + Add Medicines
                </button>
            </div>
        @endif

        {{-- Add Medicines Form --}}
        
        @if(($order->RequiresPrescription && $order->PrescriptionImageUrl) || ($order->OrderDescription))
            <div id="addMedicineForm" class="hidden mt-6 bg-white p-6 rounded-lg border shadow">
                <h3 class="text-lg font-semibold mb-4">Add Medicines to Order</h3>

                <form method="POST" action="{{ route('order-items.storeMultiple') }}">
                    @csrf
                    <input type="hidden" name="OrderId" value="{{ $order->OrderId }}">

                    {{-- Medicines Container --}}
                    <div id="medicinesContainer">
                        <div class="medicineRow flex gap-4 mb-4">
                            <div class="flex-1">
                                <label class="block text-sm font-medium mb-1">Medicine</label>
                                <input type="text" name="MedicineName[]" class="medicineInput w-full border rounded px-3 py-2" placeholder="Type medicine name..." required>
                                <input type="hidden" name="MedicineId[]" class="medicineId">
                            </div>

                            <div class="w-24">
                                <label class="block text-sm font-medium mb-1">Qty</label>
                                <input type="number" name="Quantity[]" value="1" min="1" required class="w-full border rounded px-3 py-2">
                            </div>

                            <div class="w-32">
                                <label class="block text-sm font-medium mb-1">Unit Price</label>
                                <input type="number" name="UnitPriceAtOrder[]" step="0.01" required class="unitPrice w-full border rounded px-3 py-2">
                            </div>

                            <div class="flex items-end">
                                <button type="button" class="removeRow px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700">Remove</button>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2 mb-4">
                        <button type="button" id="addMedicineRow" class="px-2 py-1 bg-green-200 text-white rounded hover:bg-green-400">
                            + Add Another Medicine
                        </button>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Save Medicines
                        </button>

                        <button type="button" onclick="toggleAddMedicineForm()"
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        @endif
    @endif

    {{-- Ordered Items Table --}}
    <div class="bg-white rounded-md mt-6">
        <h3 class="text-lg font-semibold mb-4">Ordered Items :</h3>
        <div class="overflow-x-auto">
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

                     @php 
                        $TotalAmount = 0;
                    @endphp

                    @foreach($order->items as $key => $item)

                        @php
                            $TotalAmount += (float)$item->UnitPriceAtOrder * (float)$item->Quantity;
                        @endphp

                        <tr class="text-center border-b">
                            <td>{{ $key + 1 }}</td>
                            <td class="px-4 py-2">
                                @if($item->medicine)
                                    <img src="https://pcsdecom.azurewebsites.net{{ $item->medicine->ImageUrl }}" 
                                        alt="{{ $item->medicine->Name }}" class="w-12 h-12 object-cover rounded mx-auto">
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
                        <td colspan="7" class="px-4 py-2 font-bold">Total Amount:</td>
                        <td class="px-4 py-2 font-bold">Rs.{{ number_format($TotalAmount, 2) }}</td>
                        {{-- <td class="px-4 py-2 font-bold">Rs.{{ number_format($order->TotalAmount, 2) ?? 'N/A' }}</td> --}}
                    </tr>

                </tbody>
            </table>
        </div>
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
            { id: '{{ $medicine->MedicineId }}', label: '{{ $medicine->Name }}', value: '{{ $medicine->Name }}', price: '{{ $medicine->Price }}' },
            @endforeach
        ];

        // Initialize autocomplete on all medicine inputs
        function initAutocomplete() {
            $(".medicineInput").autocomplete({
                source: medicines,
                minLength: 1,
                select: function(event, ui) {
                    $(this).siblings(".medicineId").val(ui.item.id);
                    $(this).closest(".medicineRow").find(".unitPrice").val(ui.item.price);
                }
            });
        }

        initAutocomplete(); // for first row

        // Add new medicine row
        $("#addMedicineRow").click(function() {
            var newRow = $(".medicineRow:first").clone();
            newRow.find("input").val(""); // clear inputs
            $("#medicinesContainer").append(newRow);
            initAutocomplete();
        });

        // Remove medicine row
        $(document).on("click", ".removeRow", function() {
            if($(".medicineRow").length > 1){
                $(this).closest(".medicineRow").remove();
            }
        });

    });
</script>
@endpush
