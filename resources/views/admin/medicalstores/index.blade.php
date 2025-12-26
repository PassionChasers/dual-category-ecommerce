@extends('layouts.admin.app')
@section('title', 'Admin | Medical Stores')

@push('styles')
<style>
    .thumb { width:48px; height:48px; object-fit:cover; border-radius:6px; }
</style>
@endpush

@section('contents')
<div class="flex-1 overflow-auto bg-gray-50 p-4 md:p-6">

    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Medical Stores</h1>
            <p class="text-gray-500 mt-1">Create, view and manage medical stores</p>
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

    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b"><h2 class="font-semibold text-gray-800">Store List</h2></div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Image</th>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">License / GSTIN</th>
                        <th class="px-4 py-3 text-left">Delivery</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-100">
                @forelse($stores as $i => $s)
                    <tr>
                        <td class="px-4 py-3">{{ $stores->firstItem() + $i }}</td>
                        <td class="px-4 py-3">
                            @if($s->ImageUrl)
                                <img src="{{ asset('storage/'.$s->ImageUrl) }}" class="thumb" alt="{{ $s->Name }}">
                            @else
                                <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center text-gray-400 text-xs">No</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900">{{ $s->Name }}</div>
                            <div class="text-xs text-gray-500">ID: {{ $s->MedicalStoreId }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm">{{ $s->LicenseNumber ?: '-' }}</div>
                            <div class="text-xs text-gray-500">GST: {{ $s->GSTIN ?: '-' }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-sm">Fee: {{ isset($s->DeliveryFee) ? '৳'.number_format($s->DeliveryFee,2) : '-' }}</div>
                            <div class="text-xs text-gray-500">Min: {{ isset($s->MinOrder) ? '৳'.number_format($s->MinOrder,2) : '-' }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <button data-id="{{ $s->MedicalStoreId }}" class="toggle-active px-2 py-1 text-xs rounded-full border font-medium">
                                {!! $s->IsActive ? '<span class="text-green-700">Active</span>' : '<span class="text-red-700">Inactive</span>' !!}
                            </button>
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.medicalstores.show', $s->MedicalStoreId) }}" class="px-3 py-1 text-sm bg-blue-50 rounded hover:bg-blue-100">View</a>

                            <button class="edit-btn px-3 py-1 text-sm bg-indigo-50 rounded hover:bg-indigo-100"
                                data-id="{{ $s->MedicalStoreId }}"
                                data-name="{{ e($s->Name) }}"
                                data-license="{{ e($s->LicenseNumber) }}"
                                data-gstin="{{ e($s->GSTIN) }}"
                                data-pan="{{ e($s->PAN) }}"
                                data-delivery="{{ $s->DeliveryFee }}"
                                data-minorder="{{ $s->MinOrder }}"
                                data-opentime="{{ $s->OpenTime }}"
                                data-closetime="{{ $s->CloseTime }}"
                                data-lat="{{ $s->Latitude }}"
                                data-lng="{{ $s->Longitude }}"
                                data-priority="{{ $s->Priority }}"
                                data-image="{{ $s->ImageUrl ? asset('storage/'.$s->ImageUrl) : '' }}">
                                Edit
                            </button>

                            <form action="{{ route('admin.medicalstores.destroy', $s->MedicalStoreId) }}" method="POST" class="inline delete-form">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1 text-sm bg-red-50 rounded hover:bg-red-100">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-6 text-center text-gray-500">No stores found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col md:flex-row items-center justify-between px-6 py-4 bg-gray-50 border-t">
            <div class="text-sm text-gray-600">
                Showing <strong>{{ $stores->firstItem() ?? 0 }}</strong> to <strong>{{ $stores->lastItem() ?? 0 }}</strong> of <strong>{{ $stores->total() }}</strong> results
            </div>
            <div class="mt-3 md:mt-0">{{ $stores->links() }}</div>
        </div>
    </div>
</div>

{{-- Modal for create/edit --}}
<div id="medicalstore-modal" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
    <div class="fixed inset-0 bg-black/40"></div>
    <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full z-10">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h3 id="modal-title" class="text-lg font-semibold text-gray-800">New Medical Store</h3>
            <button id="close-modal" class="text-gray-600 hover:text-gray-800"><i class="fas fa-times"></i></button>
        </div>

        <form id="medicalstore-form" method="POST" enctype="multipart/form-data" class="px-6 py-6 space-y-4">
            @csrf
            <input type="hidden" id="store-id" name="id" value="">
            <input type="hidden" id="method-field" name="_method" value="POST">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium">Name</label>
                    <input id="field-name" name="Name" type="text" required class="mt-1 block w-full border rounded-md px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium">License Number</label>
                    <input id="field-license" name="LicenseNumber" class="mt-1 block w-full border rounded-md px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium">GSTIN</label>
                    <input id="field-gstin" name="GSTIN" class="mt-1 block w-full border rounded-md px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium">PAN</label>
                    <input id="field-pan" name="PAN" class="mt-1 block w-full border rounded-md px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium">Open Time</label>
                    <input id="field-open" name="OpenTime" type="time" class="mt-1 block w-full border rounded-md px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium">Close Time</label>
                    <input id="field-close" name="CloseTime" type="time" class="mt-1 block w-full border rounded-md px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium">Delivery Fee</label>
                    <input id="field-delivery" name="DeliveryFee" type="number" step="0.01" class="mt-1 block w-full border rounded-md px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium">Min Order</label>
                    <input id="field-minorder" name="MinOrder" type="number" step="0.01" class="mt-1 block w-full border rounded-md px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium">Latitude</label>
                    <input id="field-lat" name="Latitude" class="mt-1 block w-full border rounded-md px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium">Longitude</label>
                    <input id="field-lng" name="Longitude" class="mt-1 block w-full border rounded-md px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium">Priority</label>
                    <input id="field-priority" name="Priority" type="number" class="mt-1 block w-full border rounded-md px-3 py-2" value="0">
                </div>

                <div>
                    <label class="block text-sm font-medium">Image</label>
                    <input id="field-image" name="image" type="file" accept="image/*" class="mt-1 block w-full">
                    <img id="image-preview" class="mt-2 w-28 h-28 object-cover rounded-md hidden" alt="preview">
                </div>
            </div>

            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2"><input id="field-isactive" name="IsActive" type="checkbox" class="h-4 w-4"> Active</label>
                <label class="flex items-center gap-2"><input id="field-isfeatured" name="IsFeatured" type="checkbox" class="h-4 w-4"> Featured</label>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" id="modal-cancel" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')


<script>
document.addEventListener('DOMContentLoaded', ()=> {
    const modal = document.getElementById('medicalstore-modal');
    const openCreate = document.getElementById('open-create-modal');
    const closeModal = document.getElementById('close-modal');
    const cancelBtn = document.getElementById('modal-cancel');
    const form = document.getElementById('medicalstore-form');
    const methodField = document.getElementById('method-field');
    const storeIdField = document.getElementById('store-id');

    // fields
    const nameField = document.getElementById('field-name');
    const licenseField = document.getElementById('field-license');
    const gstinField = document.getElementById('field-gstin');
    const panField = document.getElementById('field-pan');
    const openField = document.getElementById('field-open');
    const closeField = document.getElementById('field-close');
    const deliveryField = document.getElementById('field-delivery');
    const minOrderField = document.getElementById('field-minorder');
    const latField = document.getElementById('field-lat');
    const lngField = document.getElementById('field-lng');
    const priorityField = document.getElementById('field-priority');
    const imageInput = document.getElementById('field-image');
    const imagePreview = document.getElementById('image-preview');
    const isActiveField = document.getElementById('field-isactive');
    const isFeaturedField = document.getElementById('field-isfeatured');

    function openModalForCreate(){
        methodField.value = 'POST';
        storeIdField.value = '';
        form.action = "{{ route('admin.medicalstores.store') }}";
        form.reset();
        imagePreview.classList.add('hidden');
        isActiveField.checked = true;
        document.getElementById('modal-title').innerText = 'New Medical Store';
        modal.classList.remove('hidden'); modal.classList.add('flex');
    }

    openCreate.addEventListener('click', openModalForCreate);

    function closeModalHandler(){
        modal.classList.add('hidden');
        form.reset();
        imagePreview.classList.add('hidden');
    }

    [closeModal, cancelBtn].forEach(b => b && b.addEventListener('click', closeModalHandler));

    // edit buttons handler
    document.querySelectorAll('.edit-btn').forEach(btn=>{
        btn.addEventListener('click', function(){
            const id = this.dataset.id;
            methodField.value = 'PUT';
            storeIdField.value = id;
            document.getElementById('modal-title').innerText = 'Edit Medical Store';

            nameField.value = this.dataset.name || '';
            licenseField.value = this.dataset.license || '';
            gstinField.value = this.dataset.gstin || '';
            panField.value = this.dataset.pan || '';
            openField.value = this.dataset.opentime || '';
            closeField.value = this.dataset.closetime || '';
            deliveryField.value = this.dataset.delivery || '';
            minOrderField.value = this.dataset.minorder || '';
            latField.value = this.dataset.lat || '';
            lngField.value = this.dataset.lng || '';
            priorityField.value = this.dataset.priority || 0;
            isActiveField.checked = this.dataset.isactive === '1';
            isFeaturedField.checked = this.dataset.isfeatured === '1';

            if(this.dataset.image){
                imagePreview.src = this.dataset.image;
                imagePreview.classList.remove('hidden');
            } else {
                imagePreview.classList.add('hidden');
            }

            form.action = `/admin/medical-stores/${id}`;
            modal.classList.remove('hidden'); modal.classList.add('flex');
        });
    });

    // image preview
    imageInput.addEventListener('change', ()=> {
        if(imageInput.files && imageInput.files[0]){
            imagePreview.src = URL.createObjectURL(imageInput.files[0]);
            imagePreview.classList.remove('hidden');
        }
    });

    // delete confirmation
    document.querySelectorAll('.delete-form').forEach(frm=>{
        frm.addEventListener('submit', function(e){
            e.preventDefault();
            const f = this;
            Swal.fire({
                title:'Are you sure?',
                text:'This will permanently delete the store.',
                icon:'warning',
                showCancelButton:true,
                confirmButtonColor:'#e3342f',
                cancelButtonColor:'#6c757d',
                confirmButtonText:'Yes, delete'
            }).then(r=> {
                if(r.isConfirmed) f.submit();
            });
        });
    });

    // toggle active
    document.querySelectorAll('.toggle-active').forEach(btn => {
        btn.addEventListener('click', function(){
            const id = this.dataset.id;
            fetch(`/admin/medical-stores/${id}/toggle-active`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(r => r.json()).then(j => {
                if(j.success){
                    Swal.fire({ toast:true, position:'top-end', icon:'success', title:'Status updated', showConfirmButton:false, timer:1200 })
                        .then(()=> location.reload());
                } else {
                    Swal.fire('Error', 'Could not update status', 'error');
                }
            }).catch(()=> Swal.fire('Error','Could not update status','error'));
        });
    });

});
</script>
@endpush
