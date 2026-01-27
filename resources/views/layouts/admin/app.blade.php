<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>@yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @if ($setting && $setting->Favicon)
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset($setting->Favicon) }}">
        <link rel="icon" type="image/x-icon" href="{{ asset($setting->Favicon) }}">
    @else
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/passionchasers.png') }}">
    @endif


    @stack('charts')
    {{-- <script src="{{ asset('js/loader.js') }}"></script> --}}

    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
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

        /* CSS for loader */
        #global-loader {
            backdrop-filter: blur(3px);
        }
    </style>


</head>

<body class="bg-gray-50 font-sans">
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
    @endif
<<<<<<< HEAD


    {{-- <!-- GLOBAL NETWORK LOADER -->
    <div id="global-loader"
        class="fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-[9999] hidden">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-600 border-solid"></div>
    </div> --}}

       <!-- GLOBAL NETWORK LOADER -->
    <div id="global-loader"
        class="fixed inset-0 bg-white-200 bg-opacity-75 flex items-center justify-center z-[9999] hidden">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-600 border-solid"></div>
    </div>

    <!-- PLACE SCRIPT HERE (VERY IMPORTANT) -->
    <script>
        const loader = document.getElementById("global-loader");

        function showLoader() {
            loader.classList.remove("hidden");
        }

        function hideLoader() {
            loader.classList.add("hidden");
        }

        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll("form").forEach(form => {
                form.addEventListener("submit", () => showLoader());
            });

            document.querySelectorAll("a[href]").forEach(link => {
                link.addEventListener("click", () => {
                    const url = link.getAttribute("href");
                    if (url && !url.startsWith("#") && !link.hasAttribute("data-no-loader")) {
                        showLoader();
                    }
                });
            });
        });

        const originalFetch = window.fetch;
        window.fetch = async (...args) => {
            showLoader();
            try {
                const res = await originalFetch(...args);
                hideLoader();
                return res;
            } catch (e) {
                hideLoader();
                throw e;
            }
        };

        if (window.axios) {
            axios.interceptors.request.use((config) => {
                showLoader();
                return config;
            }, (error) => {
                hideLoader();
                return Promise.reject(error);
            });

            axios.interceptors.response.use((response) => {
                hideLoader();
                return response;
            }, (error) => {
                hideLoader();
                return Promise.reject(error);
            });
        }
    </script>

</body>
</html>

=======
    
>>>>>>> 10f3fe543aef0e67cb6358505c65435ae58063f9
    <div class="md:flex relative md:h-screen overflow-hidden">

        @include('layouts.admin.partials.sidebar')

        <div class="flex flex-col flex-1">

            @include('layouts.admin.partials.topbar')

            @yield('contents')

            @stack('scripts')

        </div>
    </div>
</body>

</html>
