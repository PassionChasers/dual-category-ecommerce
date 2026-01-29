@extends('layouts.admin.app')
@section('title', 'Admin | Audit Logs')

@section('contents')
<div class="flex-1 p-4 md:p-6 bg-gray-50 overflow-auto">
    <div class="mb-6 flex justify-between items-center flex-wrap">
        <div class="mb-2 md:mb-0">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-history text-indigo-600 mr-2"></i> Audit Logs
            </h2>
            <p class="text-gray-600">Track user activities, changes, and system operations</p>
        </div>
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2 w-full md:w-auto">
            <form method="GET" class="flex space-x-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search logs..."
                    class="flex-1 min-w-[150px] border rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <button type="submit" class="flex-shrink-0 px-3 py-2 bg-gray-100 text-sm rounded hover:bg-gray-200">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between">
            <h3 class="text-lg font-medium text-gray-900">Audit Log Records</h3>
        </div>
        <div class="overflow-x-auto">
            <table id="taskTable" class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">#</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">User</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Action</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Model</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">IP Address</th>
                        {{-- <th class="px-4 py-2 text-left font-semibold text-gray-700">Location</th> --}}
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Date</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Changes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($logs as $index => $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $logs->firstItem() + $index }}</td>
                        <td class="px-4 py-2">
                            {{ $log->user->name ?? 'System' }}
                            <p class="text-xs text-gray-500">{{ $log->user->Email ?? '' }}</p>
                        </td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs font-medium 
                                @if($log->Action === 'create') bg-green-100 text-green-700 
                                @elseif($log->Action === 'update') bg-yellow-100 text-yellow-700 
                                @elseif($log->Action === 'delete') bg-red-100 text-red-700 
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst($log->Action) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-gray-600">
                            {{ class_basename($log->AuditableType) }}
                            {{-- (ID: {{ $log->auditable_id }}) --}}
                        </td>
                        <td class="px-4 py-2 text-gray-600">{{ $log->IpAddress ?? '-' }}</td>
                        {{-- <td class="px-4 py-2 text-gray-600">{{ $log->location ?? '-' }}</td> --}}
                        <td class="px-4 py-2 text-gray-600">{{ $log->CreatedAt->format('d M Y, H:i') }}</td>
                        {{-- <td class="px-4 py-2">
                            @if($log->old_values || $log->new_values)
                            <button data-id="{{ $log->id }}" class="view-btn text-indigo-600 hover:underline text-sm">
                                View Changes
                            </button>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td> --}}
                        <td class="px-4 py-2">
                            @if($log->OldValues || $log->NewValues)
                            <button data-id="{{ $log->id }}" data-user="{{ $log->user->Name ?? 'System' }}"
                                data-action="{{ ucfirst($log->Action) }}"
                                data-table="{{ class_basename($log->AuditableType) }}" {{--
                                data-timestamp="{{ $log->created_at->format('d M Y, H:i') }}" --}}
                                data-timestamp="{{ optional($log->CreatedAt)->format('d M Y, H:i') }}"
                                data-old='@json($log->OldValues)' data-new='@json($log->NewValues)'
                                class="view-btn text-indigo-600 hover:underline text-sm">
                                View Changes
                            </button>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-4 text-center text-gray-500">No audit logs found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- <div class="flex justify-between items-center mt-4 px-4 py-2 bg-gray-50 border-t border-gray-200 rounded">
            <!-- Left: Results info -->
            <div id="resultsInfo" class="text-gray-700 text-sm">
                Showing 1 to 10 of 20 results
            </div>

            <!-- Right: Pagination buttons -->
            <div class="flex space-x-3">
                <button id="prevPageBtn"
                    class="px-3 py-1 border rounded border-gray-600 text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                    Previous
                </button>
                <button id="nextPageBtn"
                    class="px-3 py-1 border rounded border-gray-600 text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                    Next
                </button>
            </div>
        </div> --}}
        <div class="mt-4 px-4 py-4">
            {{ $logs->links() }}
        </div>

    </div>
</div>

<!-- Modal -->
<div id="audit-modal" class="fixed z-50 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div id="modalOverlay" class="fixed inset-0 bg-blue-950/40 bg-opacity-50 transition-opacity backdrop-blur-[2px]"></div>
        <div class="bg-white rounded-lg shadow-2xl transform transition-all max-w-4xl w-full  relative z-20">
            <div class="bg-indigo-600 flex items-center justify-between rounded-t-lg px-6 py-4">
                 <h3 class="text-2xl font-semibold text-white ">Audit Log Details</h3>
                <button type="button" id="close-audit-modal"
                    class=" text-white hover:text-red-500  transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
    
               
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6 text-sm text-gray-700 px-6 mt-4">
                <div>
                    <label class="font-medium text-gray-500">Username</label>
                    <input type="text" id="audit-username"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100" readonly>
                </div>
                <div>
                    <label class="font-medium text-gray-500">Action</label>
                    <input type="text" id="audit-action"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100" readonly>
                </div>
                <div>
                    <label class="font-medium text-gray-500">Audit Table</label>
                    <input type="text" id="audit-table"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100" readonly>
                </div>
                <div>
                    <label class="font-medium text-gray-500">Modified At</label>
                    <input type="text" id="audit-timestamp"
                        class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100" readonly>
                </div>
            </div>

            <!-- Side-by-side old/new JSON -->
            <div id="audit-changes" class="grid grid-cols-2 gap-4 max-h-96 overflow-y-auto  px-6">
                <!-- dynamically injected old/new JSON -->
            </div>

            <div class="flex justify-end px-6 py-4">
                <button type="button" id="audit-cancel-btn"
                    class="px-5 py-2 bg-gray-200 rounded-lg text-black hover:bg-red-500 hover:text-white transition font-medium">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

{{-- <script>
    document.querySelectorAll('.view-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const auditModal = document.getElementById('audit-modal');
        const auditUsername = document.getElementById('audit-username');
        const auditAction = document.getElementById('audit-action');
        const auditTable = document.getElementById('audit-table');
        const auditTimestamp = document.getElementById('audit-timestamp');
        const auditChanges = document.getElementById('audit-changes');

        auditUsername.value = btn.dataset.user;
        auditAction.value = btn.dataset.action;
        auditTable.value = btn.dataset.table;
        auditTimestamp.value = btn.dataset.timestamp;

        const oldValues = JSON.stringify(JSON.parse(btn.dataset.old || '{}'), null, 2);
        const newValues = JSON.stringify(JSON.parse(btn.dataset.new || '{}'), null, 2);

        auditChanges.innerHTML = `
            <div>
                <label class="font-medium text-gray-500">Old Values</label>
                <pre class="p-2 border rounded bg-gray-50 text-xs overflow-auto max-h-96">${oldValues}</pre>
            </div>
            <div>
                <label class="font-medium text-gray-500">New Values</label>
                <pre class="p-2 border rounded bg-gray-50 text-xs overflow-auto max-h-96">${newValues}</pre>
            </div>
        `;

        auditModal.classList.remove('hidden');
    });
});

// Close modal
document.getElementById('close-audit-modal').addEventListener('click', () => {
    document.getElementById('audit-modal').classList.add('hidden');
});
document.getElementById('audit-cancel-btn').addEventListener('click', () => {
    document.getElementById('audit-modal').classList.add('hidden');
});
</script> --}}
<script>
    document.querySelectorAll('.view-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const auditModal = document.getElementById('audit-modal');
        const auditUsername = document.getElementById('audit-username');
        const auditAction = document.getElementById('audit-action');
        const auditTable = document.getElementById('audit-table');
        const auditTimestamp = document.getElementById('audit-timestamp');
        const auditChanges = document.getElementById('audit-changes');

        auditUsername.value = btn.dataset.user;
        auditAction.value = btn.dataset.action;
        auditTable.value = btn.dataset.table;
        auditTimestamp.value = btn.dataset.timestamp;

        const oldValues = JSON.parse(btn.dataset.old || '{}');
        const newValues = JSON.parse(btn.dataset.new || '{}');

        // function formatJSON(obj) {
        //     return Object.entries(obj).map(([k, v]) => `"${k}": "${v}"`).join(', ');
        // }

        auditChanges.innerHTML = `
            <div class="mb-4">
                <label class="font-medium text-gray-500">Old Values</label>
                <pre class="p-2 border rounded bg-gray-50 text-xs whitespace-pre-wrap break-words">
${oldValues}
                </pre>
            </div>
            <div class="mb-4">
                <label class="font-medium text-gray-500">New Values</label>
                <pre class="p-2 border rounded bg-gray-50 text-xs whitespace-pre-wrap break-words">
${newValues}
                </pre>
            </div>
        `;

        auditModal.classList.remove('hidden');
    });
});
document.getElementById('audit-cancel-btn').addEventListener('click', () => {
    document.getElementById('audit-modal').classList.add('hidden');
});
const modalOverlay = document.getElementById('modalOverlay');
modalOverlay.addEventListener('click',()=>{
     document.getElementById('audit-modal').classList.add('hidden');
})


// Close modal
document.getElementById('close-audit-modal').addEventListener('click', () => {
    document.getElementById('audit-modal').classList.add('hidden');
});

</script>
@endsection