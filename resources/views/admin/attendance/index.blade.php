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

            <!-- AJAX Filter form -->
            <form id="attendance-filter-form" class="flex space-x-2 items-center">
                <select id="range" name="range" class="appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-8 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="this_week">This Week</option>
                    <option value="last_week">Last Week</option>
                    <option value="this_month">This Month</option>
                    <option value="last_month">Last Month</option>
                    <option value="custom">Custom Range</option>
                </select>

                <input type="text" id="start_date" name="start_date" value="" class="hidden border rounded px-2 py-2 text-sm w-36" placeholder="Start date">
                <input type="text" id="end_date" name="end_date" value="" class="hidden border rounded px-2 py-2 text-sm w-36" placeholder="End date">
            </form>
        </div>

        <!-- Attendance Content -->
        <div id="attendance-result">
            @include('admin.attendance.partials.data'
            // ,
            //  [
            //     'checkedIn' => $checkedIn,
            //     'checkedOut' => $checkedOut,
            //     'checkInFormatted' => $checkInFormatted,
            //     'checkOutFormatted' => $checkOutFormatted,
            //     'attendanceHistory' => $attendanceHistory,
            //     'labels' => $labels,
            //     'values' => $values,
            //     'range' => $range,
            //     'start' => $start,
            //     'end' => $end
            // ]
            )
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    document.addEventListener("DOMContentLoaded", function () {
        const rangeSelect = document.getElementById('range');
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');
        const form = document.getElementById('attendance-filter-form');
        const resultContainer = document.getElementById('attendance-result');

        flatpickr(startInput, { dateFormat: "Y-m-d" });
        flatpickr(endInput, { dateFormat: "Y-m-d" });

        function toggleCustom() {
            const isCustom = rangeSelect.value === 'custom';
            startInput.classList.toggle('hidden', !isCustom);
            endInput.classList.toggle('hidden', !isCustom);
        }

        toggleCustom();

        rangeSelect.addEventListener('change', () => {
            toggleCustom();
            if (rangeSelect.value !== 'custom') {
                applyFilter();
            }
        });

        startInput.addEventListener('change', () => {
            if (rangeSelect.value === 'custom') applyFilter();
        });

        endInput.addEventListener('change', () => {
            if (rangeSelect.value === 'custom') applyFilter();
        });

        function applyFilter() {
            const formData = new FormData(form);
            fetch("#", {
                method: "GET",
                headers: { "X-Requested-With": "XMLHttpRequest" },
                body: null,
            });

            const query = new URLSearchParams(new FormData(form)).toString();

            fetch(`#`, {
                headers: { "X-Requested-With": "XMLHttpRequest" },
            })
                .then((res) => res.text())
                .then((html) => {
                    resultContainer.innerHTML = html;
                    initChart(); // reinit chart
                })
                .catch((err) => console.error(err));
        }

        function initChart() {
            const ctx = document.getElementById('attendanceTrendsChart');
            if (ctx && ctx.dataset.labels) {
                const labels = JSON.parse(ctx.dataset.labels);
                const values = JSON.parse(ctx.dataset.values);
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Check-ins',
                            data: values,
                            backgroundColor: 'rgba(99,102,241,0.8)',
                            borderColor: 'rgba(79,70,229,1)',
                            borderWidth: 1,
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true, suggestedMax: 10 },
                            x: { title: { display: true, text: 'Date' } }
                        },
                        plugins: { legend: { display: false } }
                    }
                });
            }
        }

        initChart();

});

</script>
@endpush
