<div class="overflow-x-auto">
    
    <div class="px-6 py-4 border-b">
        <h2 class="font-semibold text-gray-800">Category List</h2>
        <!-- <p class="text-sm text-gray-500 mt-1">Showing results filtered by your search and filters.</p> -->
    </div>

    <table class="min-w-full text-sm divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left font-medium text-gray-600">#</th>
                <th class="px-6 py-3 text-left font-medium text-gray-600">Image</th>
                <th class="px-6 py-3 text-left font-medium text-gray-600">Category</th>
                <th class="px-6 py-3 text-left font-medium text-gray-600">Description</th>
                <th class="px-6 py-3 text-left font-medium text-gray-600">Status</th>
                <th class="px-6 py-3 text-center font-medium text-gray-600">Actions</th>
            </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-100">
            @forelse($categories as $index => $c)
                <tr class="font-medium text-gray-800">
                    <td class="px-6 py-4">
                        {{ $categories->firstItem() + $index }}
                    </td>
                    <td class="px-4 py-3">
                        @if($c->ImageUrl)
                            <img 
                                src="{{ $c->ImageUrl }}"
                                class="w-16 h-12 object-cover rounded cursor-pointer"
                                onclick="showImage('{{ $c->ImageUrl }}')"
                            >
                        @else
                            <div class="w-16 h-12 bg-gray-100 rounded flex items-center justify-center text-gray-400 text-xs">
                                No
                            </div>
                        @endif
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
                                data-isactive="{{ $c->IsActive ? '1' : '0' }}"
                                data-image="{{ $c->ImageUrl ? $c->ImageUrl : '' }}">
                                <i class="fas fa-edit"></i>
                            </button>

                            {{-- Optional Delete Form --}}
                            <form action="{{ route('admin.medicine-categories.destroy', $c->MedicineCategoryId) }}" method="POST" class="delete-form inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 delete-category-btn" data-name="{{ $c->Name }}">
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
