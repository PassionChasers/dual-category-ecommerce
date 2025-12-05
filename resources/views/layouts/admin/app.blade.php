<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @if ($setting && $setting->favicon)
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/' . $setting->favicon) }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $setting->favicon) }}">
    @else
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/passionchasers.png') }}">
    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">

    <!-- Dummy test toast (no DB) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: "Static datas are used in this admin panel demo.",
                showConfirmButton: false,
                timer: 2000
            });
        });
    </script>

    <div class="md:flex relative md:h-screen overflow-hidden">

        @include('layouts.admin.partials.sidebar')

        <div class="flex flex-col flex-1">

            @include('layouts.admin.partials.topbar')

            @yield('contents')

            @stack('scripts')

        </div>
    </div>

    <!-- Table Pagination Script (kept same) -->
    <script>
        const rowsPerPage = 6;
        let currentPage = 1;

        const table = document.querySelector('table tbody');
        const rows = table ? Array.from(table.querySelectorAll('tr')) : [];
        const totalRows = rows.length;
        const totalPages = Math.ceil(totalRows / rowsPerPage);

        const resultsInfo = document.getElementById('resultsInfo');
        const prevBtn = document.getElementById('prevPageBtn');
        const nextBtn = document.getElementById('nextPageBtn');

        function updateButtonColors() {
            if (!prevBtn || !nextBtn) return;

            prevBtn.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600');
            nextBtn.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600');

            prevBtn.classList.add('border-gray-600', 'text-gray-800');
            nextBtn.classList.add('border-gray-600', 'text-gray-800');

            if (currentPage === 1 && totalPages > 1) {
                nextBtn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600');
            } else if (currentPage === totalPages && totalPages > 1) {
                prevBtn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600');
            }
        }

        function showPage(page) {
            if (!rows.length) return;

            const start = (page - 1) * rowsPerPage;
            const end = Math.min(start + rowsPerPage, totalRows);

            rows.forEach((row, i) => {
                row.style.display = i >= start && i < end ? '' : 'none';
            });

            if (resultsInfo) {
                resultsInfo.textContent = `Showing ${start + 1} to ${end} of ${totalRows} results`;
            }

            if (prevBtn) prevBtn.disabled = page === 1;
            if (nextBtn) nextBtn.disabled = page === totalPages;

            updateButtonColors();
        }

        if (rows.length) showPage(currentPage);
    </script>

</body>

</html>