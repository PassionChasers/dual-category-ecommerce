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

    @if(session('delete_error'))
        <script>
            Swal.fire('Warning!', '{{ session("delete_error") }}', 'error');
        </script>
    @endif

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

    @if (session('error'))
    <script>
         document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: "{{ session('error') }}",
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
            });
    </script>
    @endif

    {{-- @if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops!',
            html: `{!! implode('<br>', $errors->all()) !!}`,
        });
    </script>
    @endif --}}


    {{-- <!-- GLOBAL NETWORK LOADER -->
    <div id="global-loader"
        class="fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-[9999] hidden">
        <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-600 border-solid"></div>
    </div> --}}

    <!-- GLOBAL NETWORK LOADER -->
    <div id="global-loader" class="fixed inset-0 bg-white-100 bg-opacity-75 flex items-center justify-center z-[9999] hidden">
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

            // document.querySelectorAll("form").forEach(form => {
            //     form.addEventListener("submit", () => showLoader());
            // });

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

        //for image modal to show image on click of image
        function showImage(url) {
            Swal.fire({
                imageUrl: url,
                imageAlt: 'Medicine Image',
                showConfirmButton: false,
                showCloseButton: true,
                width: '400px'
            });
        }

    </script>

    <script>
        function openChangePasswordModal() {
            document.getElementById('changePasswordModal').classList.remove('hidden');
        }

        function closeChangePasswordModal() {
            document.getElementById('changePasswordModal').classList.add('hidden');
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeChangePasswordModal();
            }
        });
    </script>

</body>
</html>



    <div class="md:flex relative md:h-screen overflow-hidden">

        <!-- Change Password Modal -->
        <div id="changePasswordModal"
            class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">

            <div class="bg-white rounded-lg w-full max-w-md p-6 relative">
                <h3 class="text-lg font-semibold mb-4">Change Password</h3>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="text-sm font-medium">Current Password</label>
                        <input type="password" name="oldPassword"
                            class="w-full border rounded px-3 py-2 mt-1" required>
                    </div>

                    <div class="mb-3">
                        <label class="text-sm font-medium">New Password</label>
                        <input type="password" name="newPassword"
                            class="w-full border rounded px-3 py-2 mt-1" required>
                    </div>

                    <div class="mb-4">
                        <label class="text-sm font-medium">Confirm New Password</label>
                        <input type="password" name="confirmNewPassword"
                            class="w-full border rounded px-3 py-2 mt-1" required>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button"
                            onclick="closeChangePasswordModal()"
                            class="px-4 py-2 bg-gray-200 rounded">
                            Cancel
                        </button>

                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>


        @include('layouts.admin.partials.sidebar')

        <div class="flex flex-col flex-1">

            @include('layouts.admin.partials.topbar')

            @yield('contents')

            @stack('scripts')

        </div>
    </div>
</body>

</html>