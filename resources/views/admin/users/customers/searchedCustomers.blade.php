<div class="px-6 py-4 border-b">
    <h3 class="text-lg font-medium text-gray-900">Customer List</h3>
</div>
<div class="overflow-x-auto">
    <table id="taskTable" class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left font-semibold text-gray-700">#id</th>
                <th class="px-4 py-2 text-left font-semibold text-gray-700">Name</th>
                <th class="px-4 py-2 text-left font-semibold text-gray-700">Email</th>
                <th class="px-4 py-2 text-left font-semibold text-gray-700">Contact</th>
                <th class="px-4 py-2 text-left font-semibold text-gray-700">Address</th>
                <th class="px-4 py-2 text-left font-semibold text-gray-700">IsActive</th>
                <th class="px-4 py-2 text-left font-semibold text-gray-700">Actions</th>
            </tr>
        </thead>
        <tbody id="userTableBody" class="divide-y divide-gray-200">
            @forelse($users as $user)
            <tr>
                <td class="px-4 py-2">
                    {{$user->id}}
                </td>
                <td class="px-4 py-2 font-semibold text-gray-800">
                    {{ $user->name }}
                </td>
                <td class="px-4 py-2 text-gray-600">
                    {{ $user->email }}
                </td>
                <td class="px-4 py-2 text-gray-600">
                    {{ $user->contact_number?? '-' }}
                </td>
                <td class="px-4 py-2 text-gray-600">
                    {{ $user->address ?? '-' }}
                </td>
                <td class="px-4 py-2 text-gray-600">
                    {{-- {{ $user->IsActive ?? '-' }} --}}
                    {{ $user->IsActive ? 'Active': 'Inactive' }}
                </td>
                <td class="px-4 py-2 flex space-x-2">
                    <button class="edit-btn text-indigo-600 hover:text-indigo-800" data-id="{{ $user->id }}"
                        data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-isactive="{{ $user->IsActive ? '1' : '0' }}"
                        data-contact_number="{{ $user->contact_number }}" data-address="{{ $user->address }}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form method="POST" action="#" class="inline delete-form">
                        @csrf @method('DELETE')
                        <input type="hidden" name="search" id="current-search" value="{{ request('search') }}">
                        <input type="hidden" name="onlineStatus" id="current-onlineStatus" value="{{ request('onlineStatus') }}">
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-4 text-center text-gray-500">No users found.</td>
            </tr>
            @endforelse
            
        </tbody>
    </table>
</div>

<div class="flex flex-col md:flex-row items-center justify-between px-6 py-4 bg-gray-50 border-t">

    <!-- Left: Results info -->
    <div id="resultsInfo" class="text-gray-700 text-sm">
        Showing <strong>{{ $users->firstItem() ?? 0 }}</strong> to <strong>{{ $users->lastItem() ?? 0 }}</strong> of <strong>{{ $users->total() }}</strong> results
    </div>

    <!-- Right: Pagination buttons -->
    <div class="mt-3 px-4">
        {{ $users->links() }}
    </div>

</div>