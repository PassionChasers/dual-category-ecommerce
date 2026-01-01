@extends('layouts.admin.app')

@section('title', 'Admin | Medicine Categories')

@push('styles')
    <!-- Extra styles if needed -->
@endpush

@section('contents')
    <div class="flex-1 p-4 md:p-6 bg-gray-50">
        <div class="mb-6 flex justify-between items-center flex-wrap">
            <div class="mb-2 md:mb-0">
                <h1 class="text-2xl font-semibold text-gray-800">Medicine Category Management</h1>
                <p class="text-gray-500 mt-1">Create, update and manage medicine categories</p>
            </div>

            <div class="flex flex-col md:flex-row md:items-center gap-2 w-full md:w-auto">
                <form method="GET" action="{{ url('admin/medicine-categories') }}" class="flex flex-wrap gap-2 w-full md:w-auto">
                    <input
                        type="text"
                        name="search"
                        placeholder="Search categories..."
                        value="{{ request('search') }}"
                        class="flex-1 min-w-[150px] px-3 py-2 border rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                    />
                    <button type="submit" class="flex-shrink-0 px-3 py-2 bg-gray-100 text-sm rounded hover:bg-gray-200">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <button id="open-create-modal" class="w-full md:w-[240px] inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                    <i class="fas fa-plus mr-1"></i> New Category
                </button>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h2 class="font-semibold text-gray-800">Category List</h2>
                <!-- <p class="text-sm text-gray-500 mt-1">Showing results filtered by your search and filters.</p> -->
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
                                    <span class="px-2 py-1 rounded text-xs {{ $c->IsActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $c->IsActive ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-3">
                                        <button class="edit-btn text-indigo-600 hover:text-indigo-800"
                                            data-id="{{ $c->MedicineCategoryId }}"
                                            data-name="{{ e($c->Name) }}"
                                            data-description="{{ e($c->Description) }}"
                                            data-isactive="{{ $c->IsActive ? '1' : '0' }}">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form action="{{ route('admin.medicine-categories.destroy', $c->MedicineCategoryId) }}" method="POST" class="delete-form inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-6 text-center text-gray-500">
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
