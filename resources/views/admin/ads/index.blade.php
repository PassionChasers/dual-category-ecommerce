@extends('layouts.admin.app')

@section('title', 'Admin | Ads Management')

@section('contents')
<div class="p-6 bg-gray-50 min-h-screen">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <i class="fas fa-ad text-indigo-600"></i>
            Ads Management
        </h2>

        <button onclick="openAdModal()"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
            <i class="fas fa-plus mr-1"></i> Add Ad
        </button>
    </div>

    {{-- Flash message --}}
    @if(session('success'))
        <div id="flash-message"
            class="mb-4 px-4 py-2 bg-green-100 text-green-700 rounded transition-opacity duration-500">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full text-sm divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left">Image</th>
                    <th class="px-4 py-3 text-left">Title</th>
                    <th class="px-4 py-3 text-left">Advertiser</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Budget</th>
                    <th class="px-4 py-3 text-left">Stats</th>
                    <th class="px-4 py-3 text-left">Start Date</th>
                    <th class="px-4 py-3 text-left">End Date</th> 
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($ads as $ad)
                    <tr>
                        {{-- Image --}}
                        <td class="px-4 py-2">
                            @if($ad->ImageUrl)
                                <img src="https://pcsdecom.azurewebsites.net{{$ad->ImageUrl}}" 
                                    class="h-12 w-24 object-cover rounded">
                            @else
                                <span class="text-gray-400">No Image</span>
                            @endif
                        </td>

                        {{-- Title --}}
                        <td class="px-4 py-2 font-semibold">
                            {{ $ad->Title }}
                        </td>

                        {{-- Advertiser --}}
                        <td class="px-4 py-2">
                            {{ $ad->AdvertiserName ?? '—' }}
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 text-xs rounded
                                {{ $ad->IsActive ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $ad->IsActive ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                        {{-- Budget --}}
                        <td class="px-4 py-2">
                            {{ $ad->TotalBudget ? 'Rs. '.$ad->TotalBudget : '—' }}
                        </td>

                        {{-- Stats --}}
                        <td class="px-4 py-2 text-xs text-gray-600">
                            👁 {{ $ad->TotalImpressions }} <br>
                            🖱 {{ $ad->TotalClicks }}
                        </td>

                        {{-- Start Date --}}
                        <td class="px-4 py-2">
                            {{ $ad->StartDate ? \Carbon\Carbon::parse($ad->StartDate)->format('d M, Y') : '—' }}
                        </td>

                        {{-- End Date --}}
                        <td class="px-4 py-2">
                            {{ $ad->EndDate ? \Carbon\Carbon::parse($ad->EndDate)->format('d M, Y') : '—' }}
                        </td>

                        {{-- Actions --}}
                        <td class="px-4 py-2 text-center">
                            <div class="flex justify-center gap-3">

                                {{-- Toggle --}}
                                <form method="POST" action="{{ route('admin.ads.toggle', $ad->AdId) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="Toggle Status">
                                        @if($ad->IsActive)
                                            <i class="fas fa-toggle-on text-green-600"></i>
                                        @else
                                            <i class="fas fa-toggle-off text-gray-500"></i>
                                        @endif
                                    </button>
                                </form>

                                {{-- <button onclick="toggleAd('{{ $ad->AdId }}')" title="Toggle">
                                    <i class="fas fa-toggle-{{ $ad->IsActive ? 'on text-green-600' : 'off text-gray-500' }} text-xl"></i>
                                </button> --}}

                                {{-- Edit --}}
                                <button onclick='openEditModal(@json($ad))'>
                                    <i class="fas fa-edit"></i>
                                </button>

                                {{-- Delete --}}
                                <form method="POST" action="{{ route('admin.ads.destroy', $ad->AdId) }}"
                                    onsubmit="return confirm('Delete this ad?')">
                                    @csrf
                                    @method('DELETE')
                                    <button title="Delete">
                                        <i class="fas fa-trash text-red-600"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                            No ads found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $ads->links() }}
    </div>
</div>

{{--Edit Modal --}}
{{-- <div id="editModal" class="fixed inset-0 hidden bg-black bg-opacity-40 flex items-center justify-center">
    <div class="bg-white w-full max-w-lg rounded shadow p-6">
        <h3 class="text-lg font-semibold mb-4">Add Advertisement</h3>

        <form id="editForm" method="POST" action="{{ route('admin.ads.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="text-sm">Title</label>
                <input id="editTitle" type="text" name="Title" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-3">
                <label class="text-sm">Advertiser Name</label>
                <input type="text" name="AdvertiserName" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="text-sm">Image</label>
                <input type="file" name="ImageUrl" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="text-sm">Redirect URL</label>
                <input type="url" name="RedirectUrl" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-3">
                <label class="text-sm">Description</label>
                <textarea name="Description" class="w-full border rounded px-3 py-2"></textarea>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeEditModal()" 
                    class="px-4 py-2 border rounded">
                    Cancel
                </button>
                <button class="px-4 py-2 bg-indigo-600 text-white rounded">
                    Save Ad
                </button>
            </div>
        </form>
    </div>
</div> --}}

{{-- Add / Edit Modal --}}
<div id="editModal" class="fixed inset-0 hidden bg-black bg-opacity-40 flex items-center justify-center z-50">

    <div class="bg-white w-full max-w-lg rounded shadow p-6 relative">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-4">
            <h3 id="modalTitle" class="text-lg font-semibold">
                Add Advertisement
            </h3>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-black">
                ✕
            </button>
        </div>

        {{-- Form --}}
        <form id="editForm"
              method="POST"
              action="{{ route('admin.ads.store') }}"
              enctype="multipart/form-data">

            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            {{-- Title --}}
            <div class="mb-3">
                <label class="text-sm font-medium">Title</label>
                <input id="editTitle" type="text" name="Title"
                       class="w-full border rounded px-3 py-2" required>
            </div>

            {{-- Advertiser --}}
            <div class="mb-3">
                <label class="text-sm font-medium">Advertiser Name</label>
                <input id="editAdvertiser" type="text" name="AdvertiserName"
                       class="w-full border rounded px-3 py-2">
            </div>


            {{-- Existing Image Preview --}}
            <div id="imagePreviewWrapper" class="mb-3 hidden">
                <label class="text-sm font-medium">Current Image</label>
                <img id="imagePreview"
                    src=""
                    class="mt-2 h-32 w-full object-cover rounded border">
            </div>

            {{-- Image --}}
            <div class="mb-3">
                <label class="text-sm font-medium">Image</label>
                <input type="file" name="ImageUrl"
                       class="w-full border rounded px-3 py-2">
            </div>

            {{-- Redirect URL --}}
            <div class="mb-3">
                <label class="text-sm font-medium">Redirect URL</label>
                <input id="editRedirect" type="url" name="RedirectUrl"
                       class="w-full border rounded px-3 py-2">
            </div>

            {{-- Description --}}
            <div class="mb-3">
                <label class="text-sm font-medium">Description</label>
                <textarea id="editDescription" name="Description"
                          class="w-full border rounded px-3 py-2"></textarea>
            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-2 mt-4">
                <button type="button"
                        onclick="closeEditModal()"
                        class="px-4 py-2 border rounded">
                    Cancel
                </button>

                <button id="submitBtn"
                        class="px-4 py-2 bg-indigo-600 text-white rounded">
                    Save Ad
                </button>
            </div>

        </form>
    </div>
</div>


{{-- JS --}}
<script>

    /* ==========================
    OPEN ADD MODAL
    ========================== */
    function openAdModal() {

        document.getElementById('modalTitle').innerText = 'Add Advertisement';
        document.getElementById('submitBtn').innerText = 'Create Ad';

        const form = document.getElementById('editForm');
        form.action = "{{ route('admin.ads.store') }}";
        form.reset();

        document.getElementById('formMethod').value = 'POST';

        document.getElementById('editModal').classList.remove('hidden');
    }

    //open edit modal
    function openEditModal(ad) {

    document.getElementById('modalTitle').innerText = 'Edit Advertisement';
    document.getElementById('submitBtn').innerText = 'Update Ad';

    document.getElementById('editTitle').value = ad.Title ?? '';
    document.getElementById('editAdvertiser').value = ad.AdvertiserName ?? '';
    document.getElementById('editRedirect').value = ad.RedirectUrl ?? '';
    document.getElementById('editDescription').value = ad.Description ?? '';

    // Image preview logic
    const previewWrapper = document.getElementById('imagePreviewWrapper');
    const previewImage = document.getElementById('imagePreview');

    if (ad.ImageUrl) {
        previewImage.src = 'https://pcsdecom.azurewebsites.net' + ad.ImageUrl;
        previewWrapper.classList.remove('hidden');
    } else {
        previewWrapper.classList.add('hidden');
        previewImage.src = '';
    }

    const form = document.getElementById('editForm');
    form.action = `/admin/ads/${ad.AdId}`;
    document.getElementById('formMethod').value = 'PUT';

    document.getElementById('editModal').classList.remove('hidden');
}

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    //Ad toogle Ajjax
    // function toggleAd(adId) {
    //     fetch(`/admin/ads/${adId}/toggle`, {
    //         method: 'PATCH',
    //         headers: {
    //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    //             'Accept': 'application/json'
    //         }
    //     })
    //     .then(() => location.reload());
    // }

    //ads msge 
     setTimeout(() => {
            const msg = document.getElementById('flash-message');
            if (msg) {
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 500); // remove after fade
            }
        }, 3000);
</script>

@endsection
