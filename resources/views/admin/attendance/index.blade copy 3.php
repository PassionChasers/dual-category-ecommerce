@extends('layouts.admin.app')
@section('title', 'Passion Chasers | Attendance')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@section('contents')
<div class="flex-1 overflow-auto p-4 md:p-6 bg-gray-50">
    <div id="attendance-content" class="content-page">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Attendance</h2>
                <p class="text-gray-600">Track your check-in/check-out times and attendance history</p>
            </div>

            <!-- Filter form (normal submit) -->
            <form method="GET" action="{{ route('attendance.index') }}" class="flex space-x-2 items-center">
                <select id="range" name="range" class="appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="this_week" {{ ($range ?? 'this_week') === 'this_week' ? 'selected' : '' }}>This Week</option>
                    <option value="last_week" {{ ($range ?? '') === 'last_week' ? 'selected' : '' }}>Last Week</option>
                    <option value="this_month" {{ ($range ?? '') === 'this_month' ? 'selected' : '' }}>This Month</option>
                    <option value="last_month" {{ ($range ?? '') === 'last_month' ? 'selected' : '' }}>Last Month</option>
                    <option value="custom" {{ ($range ?? '') === 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>

                <input type="text" id="start_date" name="start_date" value="{{ isset($start) ? $start->toDateString() : '' }}" class="hidden border rounded px-2 py-2 text-sm w-36" placeholder="Start date">
                <input type="text" id="end_date" name="end_date" value="{{ isset($end) ? $end->toDateString() : '' }}" class="hidden border rounded px-2 py-2 text-sm w-36" placeholder="End date">

                <button type="submit" class="px-4 py-2 text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">Apply</button>
            </form>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Left / Main column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Current Status -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Current Status</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Today's Status</p>

                                @if (isset($checkedIn) && $checkedIn && (!isset($checkedOut) || !$checkedOut))
                                    <p id="attendance-status-page" class="text-lg font-semibold text-gray-900">Checked In: {{ $checkInFormatted }}</p>
                                @elseif (isset($checkedOut) && $checkedOut && isset($checkedIn) && $checkedIn)
                                    <p id="attendance-status-page" class="text-lg font-semibold text-gray-900">Checked Out: {{ $checkOutFormatted }}</p>
                                @else
                                    <p id="attendance-status-page" class="text-lg font-semibold text-gray-900">Checked In: --:-- --</p>
                                @endif
                            </div>

                            <div class="flex items-center space-x-3">
                                @if (!($checkedIn ?? false))
                                    <form action="{{ route('attendance.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="type" value="in">
                                        <button type="submit" class="px-4 py-2 text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none">
                                            <i class="fas fa-sign-in-alt mr-1"></i> Check In
                                        </button>
                                    </form>
                                @elseif(($checkedIn ?? false) && !($checkedOut ?? false))
                                    <form action="{{ route('attendance.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="type" value="out">
                                        <button type="submit" class="px-4 py-2 text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                                            <i class="fas fa-sign-out-alt mr-1"></i> Check Out
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="px-3 py-1 text-sm font-medium rounded-md text-white bg-gray-400 cursor-not-allowed">Attendance Marked</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance History -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Attendance History</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col">
                            <div class="py-2 align-middle inline-block min-w-full">
                                <div class="shadow border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
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
                                            @foreach ($attendanceHistory as $attendance)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $attendance['date'] }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance['check_in'] }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance['check_out'] }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance['hours_worked'] }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $colorClass = match ($attendance['status_color']) {
                                                            'green' => 'bg-green-100 text-green-800',
                                                            'yellow' => 'bg-yellow-100 text-yellow-800',
                                                            'red' => 'bg-red-100 text-red-800',
                                                            default => 'bg-gray-100 text-gray-800',
                                                        };
                                                    @endphp
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">{{ $attendance['status'] }}</span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="px-4 py-4 bg-gray-50">
                            {{ $attendanceHistory->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Weekly Summary -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Weekly Summary</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm font-medium text-gray-500">Days Present</p>
                                {{-- If you want these values dynamic, compute and pass from controller --}}
                                <p class="text-2xl font-semibold text-gray-900">{{ collect($attendanceHistory->items())->filter(fn($d) => $d['status'] === 'Present')->count() }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm font-medium text-gray-500">Days Absent</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ collect($attendanceHistory->items())->filter(fn($d) => $d['status'] === 'Absent')->count() }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">This Range</h4>
                            @php
                                $days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                                $todayIndex = now()->dayOfWeek;
                            @endphp
                            <div class="grid grid-cols-7 gap-1 text-center">
                                @foreach ($days as $index => $day)
                                <div class="py-1">
                                    <p class="text-xs text-gray-500">{{ $day }}</p>
                                    @if ($index < $todayIndex)
                                        <div class="w-6 h-6 mx-auto mt-1 rounded-full bg-green-100 flex items-center justify-center"><i class="fas fa-check text-green-600 text-xs"></i></div>
                                    @elseif ($index === $todayIndex)
                                        <div class="w-6 h-6 mx-auto mt-1 rounded-full bg-yellow-100 flex items-center justify-center"><i class="fas fa-clock text-yellow-600 text-xs"></i></div>
                                    @else
                                        <div class="w-6 h-6 mx-auto mt-1 rounded-full bg-gray-100 flex items-center justify-center"><i class="fas fa-minus text-gray-500 text-xs"></i></div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Trends Chart -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Attendance Trends</h3>
                    </div>
                    <div class="p-6">
                        <div class="h-64">
                            <canvas id="attendanceTrendsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /content -->
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // range custom date toggles
    const rangeSelect = document.getElementById('range');
    const startInput = document.getElementById('start_date');
    const endInput = document.getElementById('end_date');

    flatpickr(startInput, { dateFormat: "Y-m-d" });
    flatpickr(endInput, { dateFormat: "Y-m-d" });

    function toggleCustom() {
        const isCustom = rangeSelect.value === 'custom';
        startInput.classList.toggle('hidden', !isCustom);
        endInput.classList.toggle('hidden', !isCustom);
    }

    rangeSelect.addEventListener('change', toggleCustom);
    toggleCustom(); // init

    // Chart
    const ctx = document.getElementById('attendanceTrendsChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Check-ins',
                    data: @json($values),
                    backgroundColor: 'rgba(99, 102, 241, 0.8)',
                    borderColor: 'rgba(79, 70, 229,1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, suggestedMax: 10, title: { display: true, text: 'Check-ins' } },
                    x: { title: { display: true, text: 'Date' } }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true }
                }
            }
        });
    }
});
</script>
@endpush
