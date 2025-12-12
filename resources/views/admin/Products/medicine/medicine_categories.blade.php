@extends('layouts.admin.app')
@section('title', 'Admin | Medicine Categories')

@push('styles')
<!-- Extra styles if needed -->
@endpush

@section('contents')
<div class="flex-1 overflow-auto bg-gray-50 p-4 md:p-6">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Medicine Category Management</h1>
            <p class="text-gray-500 mt-1">Create, update and manage medicine categories</p>
        </div>

        <div class="flex flex-col md:flex-row gap-3 items-stretch">
            <form method="GET" action="{{ url('admin/medicine-categories') }}" class="flex gap-2 items-center">
                <input
                    type="text"
                    name="search"
                    placeholder="Search name or description..."
                    value="{{ request('search') }}"
                    class="px-3 py-2 border rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                />
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 border rounded-md">
                    <option value="">All status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <select name="sort_by" onchange="this.form.submit()" class="px-3 py-2 border rounded-md">
                    <option value="CreatedAt" {{ request('sort_by') === 'CreatedAt' ? 'selected' : '' }}>Sort: Newest</option>
                    <option value="Name" {{ request('sort_by') === 'Name' ? 'selected' : '' }}>Sort: Name</option>
                </select>

                <select name="sort_order" onchange="this.form.submit()" class="px-3 py-2 border rounded-md">
                    <option value="desc" {{ request('sort_order') === 'desc' ? 'selected' : '' }}>Desc</option>
                    <option value="asc" {{ request('sort_order') === 'asc' ? 'selected' : '' }}>Asc</option>
                </select>

                <select name="per_page" onchange="this.form.submit()" class="px-3 py-2 border rounded-md">
                    @foreach([5,10,25,50] as $p)
                        <option value="{{ $p }}" {{ (int)request('per_page', 10) === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-3 py-2 bg-gray-100 rounded-md hover:bg-gray-200">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <button id="open-create-modal"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                <i class="fas fa-plus"></i> New Category
            </button>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b">
            <h2 class="font-semibold text-gray-800">Category List</h2>
            <p class="text-sm text-gray-500 mt-1">Showing results filtered by your search and filters.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium text-gray-600">#</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-600">Category</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-600">Description</th>
                        <th class="px-6 py-3 text-left font-medium text-gray-600">Status</th>
                        <th class="px-6 py-3 text-right font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($categories as $index => $c)
                        <tr class="font-medium text-gray-800">
                            <td class="px-6 py-4">
                                {{ $categories->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-800">{{ $c->Name }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $c->MedicineCategoryId }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                <div class="line-clamp-2">{{ \Illuminate\Support\Str::limit($c->Description, 120) }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <button
                                    data-id="{{ $c->MedicineCategoryId }}"
                                    class="toggle-active inline-flex items-center px-2 py-1 text-xs font-medium rounded-full border"
                                    title="Toggle active">
                                    @if($c->IsActive)
                                        <span class="text-green-700">Active</span>
                                    @else
                                        <span class="text-red-700">Inactive</span>
                                    @endif
                                </button>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <button class="edit-btn px-3 py-1 text-sm bg-indigo-50 rounded hover:bg-indigo-100"
                                    data-id="{{ $c->MedicineCategoryId }}"
                                    data-name="{{ e($c->Name) }}"
                                    data-description="{{ e($c->Description) }}"
                                    data-isactive="{{ $c->IsActive ? '1' : '0' }}">
                                    Edit
                                </button>

                                <form action="{{ route('admin.medicine-categories.destroy', $c->MedicineCategoryId) }}" method="POST" class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 text-sm bg-red-50 rounded hover:bg-red-100">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                No categories found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col md:flex-row items-center justify-between px-6 py-4 bg-gray-50 border-t">
            <div class="text-gray-600 text-sm">
                Showing <strong>{{ $categories->firstItem() ?? 0 }}</strong> to <strong>{{ $categories->lastItem() ?? 0 }}</strong> of <strong>{{ $categories->total() }}</strong> results
            </div>

            <div class="mt-3 md:mt-0">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal: create / edit -->
<div id="category-modal" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
    <div class="fixed inset-0 bg-black bg-opacity-40"></div>
    <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full z-10 overflow-hidden">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h3 id="modal-title" class="text-lg font-medium text-gray-800">New Category</h3>
            <button id="close-modal" class="text-gray-600 hover:text-gray-800"><i class="fas fa-times"></i></button>
        </div>

        <form id="category-form" method="POST" class="space-y-4 px-6 py-6">
            @csrf
            <input type="hidden" id="category-id" name="id" value="">
            <input type="hidden" id="method-field" name="_method" value="POST">

            <div>
                <label class="block text-sm font-medium text-gray-700">Category Name</label>
                <input type="text" id="field-name" name="Name" required
                    class="mt-1 block w-full border rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="field-description" name="Description" rows="4"
                    class="mt-1 block w-full border rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" id="field-isactive" name="IsActive" value="1" class="h-4 w-4">
                    <span>Active</span>
                </label>

                <div class="ml-auto flex gap-2">
                    <button type="button" id="modal-cancel" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('category-modal');
    const openCreate = document.getElementById('open-create-modal');
    const closeModalBtns = [document.getElementById('close-modal'), document.getElementById('modal-cancel')];
    const modalTitle = document.getElementById('modal-title');
    const form = document.getElementById('category-form');
    const methodField = document.getElementById('method-field');
    const categoryIdField = document.getElementById('category-id');
    const nameField = document.getElementById('field-name');
    const descField = document.getElementById('field-description');
    const isActiveField = document.getElementById('field-isactive');

    function openModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        form.reset();
        methodField.value = 'POST';
        categoryIdField.value = '';
        form.action = "{{ route('admin.medicine-categories.store') }}";
    }

    // Create
    openCreate.addEventListener('click', () => {
        modalTitle.innerText = 'New Category';
        methodField.value = 'POST';
        categoryIdField.value = '';
        nameField.value = '';
        descField.value = '';
        isActiveField.checked = true;
        form.action = "{{ route('admin.medicine-categories.store') }}";
        openModal();
    });

    // Close buttons
    closeModalBtns.forEach(btn => btn && btn.addEventListener('click', closeModal));

    // Edit buttons
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const description = this.dataset.description;
            const isActive = this.dataset.isactive === '1';

            modalTitle.innerText = 'Edit Category';
            methodField.value = 'PUT';
            categoryIdField.value = id;
            nameField.value = name;
            descField.value = description;
            isActiveField.checked = isActive;

            form.action = `/admin/medicine-categories/${id}`;
            openModal();
        });
    });

    // Delete confirmation (permanent delete)
    document.querySelectorAll('.delete-form').forEach(formEl => {
        formEl.addEventListener('submit', function (e) {
            e.preventDefault();
            const f = this;
            Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the category.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete'
            }).then(result => {
                if (result.isConfirmed) {
                    f.submit();
                }
            });
        });
    });

    // Toggle active (AJAX)
    document.querySelectorAll('.toggle-active').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            fetch(`/admin/medicine-categories/${id}/toggle-active`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            }).then(r => r.json()).then(json => {
                if (json.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Status updated',
                        showConfirmButton: false,
                        timer: 1200
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error','Could not update status','error');
                }
            }).catch(() => {
                Swal.fire('Error','Could not update status','error');
            });
        });
    });

});
</script>
@endpush
