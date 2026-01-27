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
