@extends('layouts.admin.app')
@section('title', 'Admin | Medicalstores Management')

@section('contents')
<div class="flex-1 p-4 md:p-6 bg-gray-50">

    {{-- HEADER --}}
    <div class="mb-6 flex justify-between items-center flex-wrap">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Medicalstores Management</h2>
            <p class="text-gray-600">Manage all Medical Stores</p>
        </div>

         <div class="flex flex-col md:flex-row gap-3 items-stretch">
            <form method="GET" action="{{ route('admin.medicalstores.index') }}" class="flex gap-2 items-center">
                <input name="search" placeholder="Search name, license, gstin or pan" value="{{ request('search') }}" class="px-3 py-2 border rounded-md" />
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 border rounded-md">
                    <option value="">All status</option>
                    <option value="active" {{ request('status')=='active' ? 'selected':'' }}>Active</option>
                    <option value="inactive" {{ request('status')=='inactive' ? 'selected':'' }}>Inactive</option>
                </select>
                <select name="per_page" onchange="this.form.submit()" class="px-3 py-2 border rounded-md">
                    @foreach([5,10,25,50] as $p)
                        <option value="{{ $p }}" {{ (int)request('per_page',10)===$p ? 'selected':'' }}>{{ $p }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-3 py-2 bg-gray-100 rounded-md hover:bg-gray-200"><i class="fas fa-search"></i></button>
            </form>

            <button id="open-create-modal" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                <i class="fas fa-plus"></i> New Store
            </button>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
         <div class="px-6 py-4 border-b">
            <h2 class="font-semibold text-gray-800">Medicalstores List</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">ID</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Store Name</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Owner</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">License</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Radius (KM)</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Delivery Fee</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Min Order</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Featured</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                @forelse($medicalstores as $store)
                    <tr>
                        <td class="px-4 py-2">{{ $store->MedicalStoreId }}</td>
                        <td class="px-4 py-2 font-semibold text-gray-800">{{ $store->Name }}</td>
                        <td class="px-4 py-2 font-semibold text-gray-800">{{ $store->user->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $store->LicenseNumber ?? '-' }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $store->RadiusKm }}</td>
                        <td class="px-4 py-2 text-gray-600">Rs {{ $store->DeliveryFee }}</td>
                        <td class="px-4 py-2 text-gray-600">Rs {{ $store->MinOrder }}</td>

                        <td class="px-4 py-2 text-gray-600">
                            @if($store->IsActive)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded">Inactive</span>
                            @endif
                        </td>

                        <td class="px-4 py-2 text-gray-600">
                            @if($store->IsFeatured)
                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded">Yes</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-gray-100 rounded">No</span>
                            @endif
                        </td>

                        <td class="px-4 py-4 flex text-gray-600">
                             <div class="mx-2 my-2">
                                <a href="{{ route('admin.medicalstores.show', $store->MedicalStoreId) }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                             </div>

                            <div class="mx-2 my-2">
                                <button class="edit-btn text-indigo-600"
                                    data-id="{{ $store->MedicalStoreId }}"
                                    data-name="{{ $store->Name }}"
                                    data-license="{{ $store->LicenseNumber }}"
                                    data-gstin="{{ $store->GSTIN }}"
                                    data-pan="{{ $store->PAN }}"
                                    data-open="{{ $store->OpenTime }}"
                                    data-close="{{ $store->CloseTime }}"
                                    data-delivery="{{ $store->DeliveryFee }}"
                                    data-min="{{ $store->MinOrder }}"
                                    data-lat="{{ $store->Latitude }}"
                                    data-lng="{{ $store->Longitude }}"
                                    data-priority="{{ $store->Priority }}"
                                    data-active="{{ $store->IsActive }}"
                                    data-featured="{{ $store->IsFeatured }}"
                                    data-image="{{ $store->image }}"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>


                            <div class="mx-2 my-2">
                                <form method="POST" action="{{ route('admin.medicalstores.destroy', $store->MedicalStoreId) }}" class="inline delete-form">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-6 px-6">No medical stores found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="flex flex-col md:flex-row items-center justify-between px-6 py-4 bg-gray-50 border-t">
            <div class="text-sm text-gray-600">
                Showing <strong>{{ $medicalstores->firstItem() ?? 0 }}</strong> to <strong>{{ $medicalstores->lastItem() ?? 0 }}</strong> of <strong>{{ $medicalstores->total() }}</strong> results
            </div>
            <div class="mt-3 md:mt-0">{{ $medicalstores->links() }}</div>
        </div>
    </div>
</div>

{{-- MODAL FOR EDIT --}}
<div id="medicalstore-modal" class="fixed inset-0 hidden z-50">
    <div class="flex items-center justify-center min-h-screen bg-black bg-opacity-40">
        <div class="bg-white p-6 rounded-lg w-full max-w-3xl relative">

            <button id="close-modal-btn" class="absolute top-3 right-3 text-gray-500">
                <i class="fas fa-times"></i>
            </button>

            <h3 class="text-lg font-semibold mb-4">Edit Medical Store</h3>

            <form id="medicalstore-form" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" id="method-field" name="_method" value="PUT">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-medium">Name</label>
                        <input id="field-name" name="Name" type="text" class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">License Number</label>
                        <input id="field-license" name="LicenseNumber" class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">GSTIN</label>
                        <input id="field-gstin" name="GSTIN" class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">PAN</label>
                        <input id="field-pan" name="PAN" class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Open Time</label>
                        <input id="field-open" name="OpenTime" type="time" class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Close Time</label>
                        <input id="field-close" name="CloseTime" type="time" class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Delivery Fee</label>
                        <input id="field-delivery" name="DeliveryFee" type="number" step="0.01" class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Min Order</label>
                        <input id="field-minorder" name="MinOrder" type="number" step="0.01" class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Latitude</label>
                        <input id="field-lat" name="Latitude" class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Longitude</label>
                        <input id="field-lng" name="Longitude" class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Priority</label>
                        <input id="field-priority" name="Priority" type="number" value="0" class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Image</label>
                        <input id="field-image" name="image" type="file" accept="image/*">
                        <img id="image-preview" class="mt-2 w-28 h-28 rounded object-cover hidden">
                    </div>
                </div>

                <div class="flex gap-6 mt-2">
                    <label class="flex items-center gap-2">
                        <input id="field-isactive" name="IsActive" type="checkbox"> Active
                    </label>

                    <label class="flex items-center gap-2">
                        <input id="field-isfeatured" name="IsFeatured" type="checkbox"> Featured
                    </label>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" id="modal-cancel" class="px-4 py-2 bg-gray-200 rounded">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script>
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.onclick = () => {

            const form = document.getElementById('medicalstore-form');
            form.action = `/admin/medical-stores/${btn.dataset.id}`;

            document.getElementById('field-name').value = btn.dataset.name || '';
            document.getElementById('field-license').value = btn.dataset.license || '';
            document.getElementById('field-gstin').value = btn.dataset.gstin || '';
            document.getElementById('field-pan').value = btn.dataset.pan || '';
            document.getElementById('field-open').value = btn.dataset.open || '';
            document.getElementById('field-close').value = btn.dataset.close || '';
            document.getElementById('field-delivery').value = btn.dataset.delivery || '';
            document.getElementById('field-minorder').value = btn.dataset.min || '';
            document.getElementById('field-lat').value = btn.dataset.lat || '';
            document.getElementById('field-lng').value = btn.dataset.lng || '';
            document.getElementById('field-priority').value = btn.dataset.priority || 0;

            document.getElementById('field-isactive').checked = btn.dataset.active == 1;
            document.getElementById('field-isfeatured').checked = btn.dataset.featured == 1;

            const preview = document.getElementById('image-preview');
            if (btn.dataset.image) {
                preview.src = `/storage/${btn.dataset.image}`;
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }

            document.getElementById('medicalstore-modal').classList.remove('hidden');
        };
    });

    document.getElementById('modal-cancel').onclick =
    document.getElementById('close-modal-btn').onclick = () => {
        document.getElementById('medicalstore-modal').classList.add('hidden');
    };

    // Image preview on select
    document.getElementById('field-image').addEventListener('change', e => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = () => {
                const preview = document.getElementById('image-preview');
                preview.src = reader.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });


    // SweetAlert for delete confirmation
    document.querySelectorAll('.delete-form').forEach(f => {
        f.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) f.submit();
            });
        });
    });
</script>
@endsection
