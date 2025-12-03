<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Left/Main -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Current Status -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Current Status</h3>
            </div>
            <div class="p-6 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Today's Status</p>
                    {{-- @if (isset($checkedIn) && $checkedIn && (!isset($checkedOut) || !$checkedOut))
                        <p class="text-lg font-semibold text-gray-900">Checked In: {{ $checkInFormatted }}</p>
                    @elseif (isset($checkedOut) && $checkedOut && isset($checkedIn) && $checkedIn)
                        <p class="text-lg font-semibold text-gray-900">Checked Out: {{ $checkOutFormatted }}</p>
                    @else --}}
                        <p class="text-lg font-semibold text-gray-900">Checked In: --:-- --</p>
                    {{-- @endif --}}
                </div>
                <div class="flex items-center space-x-3">
                    {{-- @if (!($checkedIn ?? false))
                        <form action="{{ route('attendance.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="in">
                            <button type="submit" class="px-4 py-2 text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">Check In</button>
                        </form>
                    @elseif(($checkedIn ?? false) && !($checkedOut ?? false))
                        <form action="{{ route('attendance.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="out">
                            <button type="submit" class="px-4 py-2 text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">Check Out</button>
                        </form>
                    @else --}}
                        <button disabled class="px-3 py-1 text-sm font-medium rounded-md text-white bg-gray-400">Attendance Marked</button>
                    {{-- @endif --}}
                </div>
            </div>
        </div>

        <!-- Attendance History -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Attendance History</h3>
            </div>
            <div class="p-6 overflow-x-auto">
                <table id="taskTable" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check In</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check Out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours Worked</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        {{-- @foreach ($attendanceHistory as $attendance) --}}
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900">xx</td>
                            <td class="px-6 py-4 text-sm text-gray-500">xx</td>
                            <td class="px-6 py-4 text-sm text-gray-500">xx</td>
                            <td class="px-6 py-4 text-sm text-gray-500">xx</td>
                            <td class="px-6 py-4">
                                {{-- @php
                                    $colorClass = match ($attendance['status_color']) {
                                        'green' => 'bg-green-100 text-green-800',
                                        'yellow' => 'bg-yellow-100 text-yellow-800',
                                        'red' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">{{ $attendance['status'] }}</span> --}}

                                xxx
                            </td>
                        </tr>
                        {{-- @endforeach --}}
                    </tbody>
                </table>
                <div class="flex justify-between items-center mt-4 px-4 py-2 bg-gray-50 border-t border-gray-200 rounded">
                    <!-- Left: Results info -->
                    <div id="resultsInfo" class="text-gray-700 text-sm">
                        Showing 1 to 10 of 20 results
                    </div>
                
                    <!-- Right: Pagination buttons -->
                    <div class="flex space-x-3">
                        <button id="prevPageBtn" class="px-3 py-1 border rounded border-gray-600 text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                            Previous
                        </button>
                        <button id="nextPageBtn" class="px-3 py-1 border rounded border-gray-600 text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                            Next
                        </button>                
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right column -->
    <div class="space-y-6">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Weekly Summary</h3>
            </div>
            <div class="p-6 grid grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm font-medium text-gray-500">Days Present</p>
                    <p class="text-2xl font-semibold text-gray-900">5</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-sm font-medium text-gray-500">Days Absent</p>
                    <p class="text-2xl font-semibold text-gray-900">2</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Attendance Trends</h3>
            </div>
            <div class="p-6">
                <div class="h-64">
                    <canvas id="attendanceTrendsChart"
                        {{-- data-labels='@json($labels)'
                        data-values='@json($values)' --}}
                        >
                    </canvas>
                </div>
            </div>
        </div>
    </div>
</div>
