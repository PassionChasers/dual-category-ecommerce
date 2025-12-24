@extends('layouts.admin.app')
@section('title', 'Admin | Medicine Order Details')

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
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">{{ $store->Name }}</h1>
            <p class="text-gray-500 mt-1">Store details and metadata</p>
        </div>

        <div class="no-print flex gap-2 items-center">
            <a href="{{ route('admin.medicalstores.list') }}" class="px-3 py-2 bg-gray-100 rounded hover:bg-gray-200">Back</a>
            <button id="printBtn" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Print</button>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-6 flex gap-6">
            <div>
                @if($store->ImageUrl)
                    <img src="{{ asset('storage/'.$store->ImageUrl) }}" class="thumb-lg" alt="{{ $store->Name }}">
                @else
                    <div class="w-40 h-40 bg-gray-100 rounded flex items-center justify-center text-gray-400">No image</div>
                @endif
            </div>

            <div class="flex-1">
                <div class="flex justify-between">
                    <div>
                        <div class="text-sm text-gray-500">License</div>
                        <div class="text-lg font-medium">{{ $store->LicenseNumber ?: '-' }}</div>

                        <div class="mt-3 text-sm text-gray-500">GSTIN</div>
                        <div class="text-sm">{{ $store->GSTIN ?: '-' }}</div>
                    </div>

                    <div class="text-right">
                        <div class="text-sm text-gray-500">Delivery Fee</div>
                        <div class="text-lg font-medium">{{ $store->DeliveryFee ? '৳'.number_format($store->DeliveryFee,2) : '-' }}</div>

                        <div class="mt-3 text-sm text-gray-500">Min Order</div>
                        <div class="text-sm">{{ $store->MinOrder ? '৳'.number_format($store->MinOrder,2) : '-' }}</div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="text-sm text-gray-500">Open Time</div>
                        <div class="text-sm">{{ $store->OpenTime ?: '-' }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-500">Close Time</div>
                        <div class="text-sm">{{ $store->CloseTime ?: '-' }}</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">Radius (km)</div>
                        <div class="text-sm">{{ $store->RadiusKm ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">Priority</div>
                        <div class="text-sm">{{ $store->Priority ?? 0 }}</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">Coordinates</div>
                        <div class="text-sm">{{ $store->Latitude ?? '-' }}, {{ $store->Longitude ?? '-' }}</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">Status</div>
                        <div class="text-sm">{!! $store->IsActive ? '<span class="text-green-700">Active</span>' : '<span class="text-red-700">Inactive</span>' !!}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 border-t flex justify-between items-center">
            <div class="text-sm text-gray-600">
                Created: {{ optional($store->CreatedAt ?? $store->created_at)->format('Y-m-d H:i') }}
            </div>

            <div class="no-print">
                <a href="{{ route('admin.medicalstores.list') }}" class="px-3 py-2 bg-gray-100 rounded hover:bg-gray-200">Back</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('printBtn')?.addEventListener('click', ()=>{
    window.print();
});
</script>
@endpush
