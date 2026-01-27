@extends('layouts.admin.app')

@section('title', 'Admin | Medicine Categories')

@push('styles')
<style>
.custom-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1rem;
    padding-right: 2.5rem;
}
</style>
@endpush

@section('contents')
<div class="flex-1 p-4 md:p-6 bg-gray-50">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center flex-wrap ">
        <div class="mb-2 md:mb-0">
            <h1 class="text-2xl font-semibold text-gray-800">Medicine Category Management</h1>
            <p class="text-gray-500 mt-1">Create, update and manage medicine categories</p>
        </div>

        <div class="flex flex-col md:flex-row md:items-center gap-2 w-full md:w-auto">
            <form id="filter-form" class="flex flex-wrap gap-2 w-full md:w-auto">
                <input type="text" id="search-input" name="search" placeholder="Search categories..."
                       value="{{ request('search') }}"
                       class="flex-1 min-w-[150px] px-3 py-2 border rounded-md focus:ring-indigo-500 focus:border-indigo-500" />

                <select id="status-filter" name="status" class="custom-select pl-2 border rounded-md text-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>

                <select name="per_page" id="per-page-filter" class="custom-select pl-2 border rounded-md text-sm">
                    @foreach($allowedPerPage as $pp)
                        <option value="{{ $pp }}" {{ $perPage == $pp ? 'selected' : '' }}>{{ $pp }} per page</option>
                    @endforeach
                </select>
            </form>

            <button id="open-create-modal"
                    class="w-full md:w-[240px] inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                <i class="fas fa-plus mr-1"></i> New Category
            </button>
        </div>
    </div>

    <!-- Table container -->
    <div id="categories-container" class="bg-white shadow rounded-lg overflow-hidden">
        @include('admin.products.medicine.categories_table', ['categories' => $categories])
    </div>
    
</div>

<!-- Modal -->
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
                       class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="field-description" name="Description" rows="4" placeholder="Description of Category..."
                          class="px-3 mt-1 block w-full border border-gray-400 rounded-md" required></textarea>
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
    const filterForm = document.getElementById('filter-form');
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');
    const perPageFilter = document.getElementById('per-page-filter');
    const categoriesContainer = document.getElementById('categories-container');

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

    // Perform AJAX search/filter
    const performSearch = () => {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);

        // Show spinner while loading
        categoriesContainer.innerHTML = `
            <div class="overflow-x-auto">
                <div class="px-6 py-4 border-b">
                    <h2 class="font-semibold text-gray-800">Category List</h2>
                </div>
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">#</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">Category</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">Description</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">Status</th>
                            <th class="px-6 py-3 text-center font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">

                    </tbody>
                </table>
                <div class="flex justify-center items-center py-16">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-600"></div>
                </div>
            </div> 
        `;

        fetch(`{{ url('admin/medicine-categories') }}?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            categoriesContainer.innerHTML = html; // replace spinner with data
            reattachEventListeners(); // reattach edit/delete buttons
        })
        .catch(() => {
            categoriesContainer.innerHTML = `
                <div class="text-center py-16 text-red-600">Error loading data.</div>
            `;
        });
    };


    let searchTimeout;
    // Search input
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 500);
    });

    // Status filter
    statusFilter.addEventListener('change', performSearch);

    // Per page filter
    perPageFilter.addEventListener('change', performSearch);

    // Pagination links inside container
    const attachPaginationLinks = () => {
        categoriesContainer.querySelectorAll('.pagination a').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const url = link.getAttribute('href');
                
                // Show spinner
                categoriesContainer.innerHTML = `
                    <div class="overflow-x-auto">
                        <div class="px-6 py-4 border-b">
                            <h2 class="font-semibold text-gray-800">Category List</h2>
                        </div>
                        <table class="min-w-full text-sm divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left font-medium text-gray-600">#</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-600">Category</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-600">Description</th>
                                    <th class="px-6 py-3 text-left font-medium text-gray-600">Status</th>
                                    <th class="px-6 py-3 text-center font-medium text-gray-600">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">

                            </tbody>
                        </table>
                        <div class="flex justify-center items-center py-16">
                            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-600"></div>
                        </div>
                    </div>   
                `;

                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.text())
                .then(html => {
                    categoriesContainer.innerHTML = html;
                    reattachEventListeners();
                });
            });
        });
    };

    const openModal = () => { modal.classList.remove('hidden'); modal.classList.add('flex'); };
    const closeModal = () => {
        modal.classList.remove('flex'); modal.classList.add('hidden');
        form.reset(); methodField.value = 'POST'; categoryIdField.value = '';
        form.action = "{{ route('admin.medicine-categories.store') }}";
    };

    openCreate.addEventListener('click', () => {
        modalTitle.innerText = 'New Category'; methodField.value = 'POST';
        categoryIdField.value = ''; nameField.value = ''; descField.value = '';
        isActiveField.checked = true; form.action = "{{ route('admin.medicine-categories.store') }}";
        openModal();
    });

    closeModalBtns.forEach(btn => btn && btn.addEventListener('click', closeModal));
    overlay.addEventListener('click', closeModal);

    const reattachEventListeners = () => {
        categoriesContainer.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;
                modalTitle.innerText = 'Edit Category';
                methodField.value = 'PUT'; categoryIdField.value = id;
                nameField.value = this.dataset.name;
                descField.value = this.dataset.description;
                isActiveField.checked = this.dataset.isactive === '1';
                form.action = `/admin/medicine-categories/${id}`;
                openModal();
            });
        });
    };

    // Initial
    reattachEventListeners();
    interceptPaginationLinks();
});
</script>
@endpush
