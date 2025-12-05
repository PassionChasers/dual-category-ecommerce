@extends('layouts.admin.app')

@section('title', 'Admin | Task Management')

@section('contents')
<div class="flex-1 overflow-auto p-3 md:p-6 bg-gray-50">
    <div class="max-w-5xl mx-auto">
        
        {{-- Header --}}
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-bold text-gray-800">Task Detail</h1>
            <a href="{{ route('task.index') }}" 
               class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-xs font-medium hover:bg-indigo-700 transition">
               ← Back
            </a>
        </div>

        {{-- Task Card --}}
        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden">
            
            @php
                $priorityName = $task->priority->name ?? 'Low';
                $priorityColor = match($priorityName) {
                    'High' => 'bg-red-500',
                    'Medium' => 'bg-yellow-500',
                    default => 'bg-blue-500',
                };
                
                $statusLabels = ['Pending','In Progress','Completed'];
                $statusColors = ['bg-yellow-100 text-yellow-800','bg-blue-100 text-blue-800','bg-green-100 text-green-800'];
                $currentStatusLabel = $statusLabels[$task->status] ?? 'Unknown';
                $currentStatusColor = $statusColors[$task->status] ?? 'bg-gray-100 text-gray-700';
            @endphp

            {{-- Overview --}}
            <div class="p-4 border-b bg-gray-50 flex justify-between items-start flex-wrap gap-2">
                <h2 class="text-2xl font-bold text-gray-900 leading-tight">{{ $task->name }}</h2>
                <div class="flex space-x-2">
                    <span class="text-xs font-semibold uppercase text-white px-2 py-0.5 rounded-full {{ $priorityColor }}">{{ $priorityName }}</span>
                    <span class="text-xs font-semibold uppercase {{ $currentStatusColor }} px-2 py-0.5 rounded-full border border-current">{{ $currentStatusLabel }}</span>
                </div>
            </div>

            {{-- Description --}}
            <div class="p-4">
                <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Description</p>
                <div class="text-sm text-gray-700 bg-gray-50 p-2 rounded border border-gray-200 leading-relaxed">
                    {{ $task->description ?? 'No detailed description has been provided.' }}
                </div>
            </div>

            {{-- People & Metadata --}}
            <div class="grid lg:grid-cols-3 divide-y lg:divide-y-0 lg:divide-x border-t border-gray-200">
                
                {{-- People --}}
                <div class="p-4 space-y-4 lg:col-span-1">
                    <h3 class="text-sm font-semibold text-gray-800 border-b pb-1 mb-2">People</h3>
                    
                    {{-- Assigned To --}}
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Assigned To</p>
                        <div class="flex items-center gap-2">
                            <img src="{{ $task->assignee->profile_image ?? asset('images/default-user.png') }}" 
                                 class="w-8 h-8 rounded-full border object-cover">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $task->assignee->name ?? 'Unassigned' }}</p>
                                <p class="text-xs text-gray-500">{{ $task->assignee->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Created By --}}
                    <div>
                        <p class="text-xs text-gray-500 uppercase mb-1">Created By</p>
                        <div class="flex items-center gap-2">
                            <img src="{{ $task->requester->profile_image ?? asset('images/default-user.png') }}" 
                                 class="w-8 h-8 rounded-full border object-cover">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $task->requester->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $task->requester->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Metadata --}}
                <div class="p-4 space-y-2 text-xs lg:col-span-2">
                    <h3 class="text-sm font-semibold text-gray-800 border-b pb-1 mb-2">Details</h3>
                    
                    {{-- Due Date --}}
                    <div class="flex justify-between items-center py-1 px-2 bg-red-50 rounded border border-red-200">
                        <span class="font-semibold text-red-700">Due Date:</span> 
                        <span class="font-bold text-red-600">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M j, Y') : 'None' }}</span>
                    </div>

                    {{-- Other Metadata --}}
                    <div class="grid sm:grid-cols-2 gap-2">
                        <div class="flex gap-10"><span class="text-gray-500 font-medium">Category:</span> <span>{{ $task->category->name ?? 'N/A' }}</span></div>
                        <div class="flex gap-10"><span class="text-gray-500 font-medium">Approval:</span> <span class="{{ $task->is_approved ? 'text-green-600 font-bold' : 'text-yellow-600' }}">{{ $task->is_approved ? 'Approved' : 'Pending' }}</span></div>
                        <div class="flex gap-6"><span class="text-gray-500 font-medium">Request Type:</span> <span>{{ $task->is_requested ? 'User Requested' : 'Admin Assigned' }}</span></div>
                        <div class="flex gap-10"><span class="text-gray-500 font-medium">Created:</span> <span>{{ $task->created_at->format('M j, Y h:i A') }}</span></div>
                        <div class="flex gap-6"><span class="text-gray-500 font-medium">Last Updated:</span> <span>{{ $task->updated_at->format('M j, Y h:i A') }}</span></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection