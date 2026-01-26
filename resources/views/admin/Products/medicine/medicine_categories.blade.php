@extends('layouts.admin.app')

@section('title', 'Admin | Medicine Categories')

@push('styles')
<style>
    /* Custom class to handle the arrow */
.custom-select {
    appearance: none; /* Removes default arrow */
    -webkit-appearance: none;
    -moz-appearance: none;
    
    /* Add your custom chevron as a background image */
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1rem;
    padding-right: 2.5rem; /* Ensure text doesn't overlap the icon */
}
</style>
    
@endpush

@section('contents')
    <div class="flex-1 p-4 md:p-6 bg-gray-50">
        <div class="mb-6 flex justify-between items-center flex-wrap">
            <div class="mb-2 md:mb-0">
                <h1 class="text-2xl font-semibold text-gray-800">Medicine Category Management</h1>
                <p class="text-gray-500 mt-1">Create, update and manage medicine categories</p>
            </div>

            <div class="flex flex-col md:flex-row md:items-center gap-2 w-full md:w-auto">
                <form id="filter-form" class="flex flex-wrap gap-2 w-full md:w-auto">
                    <input
                        type="text"
                        id="search-input"
                        name="search"
                        placeholder="Search categories..."
                        value="{{ request('search') }}"
                        class="flex-1 min-w-[150px] px-3 py-2 border rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                    />

                    <select id="status-filter" name="status" class=" custom-select pl-2  border rounded-md text-sm">
                        <option value="">Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </form>

                <button id="open-create-modal" class="w-full md:w-[240px] inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                    <i class="fas fa-plus mr-1"></i> New Category
                </button>
            </div>
        </div>

        <div id="categories-container" class="bg-white shadow rounded-lg overflow-hidden">
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
        <div id="model-overlay" class="fixed inset-0 bg-blue-950/40 backdrop-blur-[2px]"></div>
        <div class="bg-white rounded-lg shadow-lg max-w-2xl w-full z-10 overflow-hidden">
            <div class="px-6 py-4 bg-indigo-600 flex items-center justify-between">
                <h3 id="modal-title" class="text-lg font-medium text-white">New Category</h3>
                <button id="close-modal" class="text-white hover:text-red-500 text-xl"><i class="fas fa-times"></i></button>
            </div>

            <form id="category-form" method="POST" class="space-y-4 px-6 py-6">
                @csrf
                <input type="hidden" id="category-id" name="id" value="">
                <input type="hidden" id="method-field" name="_method" value="POST">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Category Name</label>
                    <input type="text" id="field-name" name="Name" placeholder="eg. Category 1" required
                        class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2 ">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="field-description" name="Description" rows="4" placeholder="Description of Category..."
                        class="px-3 mt-1 block w-full border border-gray-400 rounded-md"></textarea>
                </div>

                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" id="field-isactive" name="IsActive" value="1" class="h-4 w-4">
                        <span>Active</span>
                    </label>

                    <div class="ml-auto flex gap-2">
                        <button type="button" id="modal-cancel" class="px-4 py-2 bg-gray-200 rounded hover:bg-red-500 hover:text-white">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('category-modal');
            const overlay = document.getElementById('model-overlay');
            const openCreate = document.getElementById('open-create-modal');
            const closeModalBtns = [document.getElementById('close-modal'), document.getElementById('modal-cancel')];
            const modalTitle = document.getElementById('modal-title');
            const form = document.getElementById('category-form');
            const methodField = document.getElementById('method-field');
            const categoryIdField = document.getElementById('category-id');
            const nameField = document.getElementById('field-name');
            const descField = document.getElementById('field-description');
            const isActiveField = document.getElementById('field-isactive');

            // AJAX SEARCH
            const filterForm = document.getElementById('filter-form');
            const searchInput = document.getElementById('search-input');
            const statusFilter = document.getElementById('status-filter');
            const categoriesContainer = document.getElementById('categories-container');

            const performSearch = () => {
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);

                fetch(`{{ url('admin/medicine-categories') }}?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const newDoc = parser.parseFromString(html, 'text/html');
                    const newContainer = newDoc.getElementById('categories-container');
                    if (newContainer) {
                        categoriesContainer.innerHTML = newContainer.innerHTML;
                        reattachEventListeners();
                    }
                })
                .catch(error => console.error('Error:', error));
            };

            // Debounce for search input
            let searchTimeout;
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(performSearch, 500);
            });

            // Immediate search for status filter
            statusFilter.addEventListener('change', performSearch);

            const reattachEventListeners = () => {
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

                // Delete forms
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
            };

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

            // Close modal when clicking outside (overlay)
            overlay.addEventListener('click', closeModal);

            // Initial attachment of event listeners
            reattachEventListeners();

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
