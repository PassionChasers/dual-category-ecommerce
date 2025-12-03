    <!-- Navigation -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <div
                        class="w-10 h-10 rounded-lg gradient-bg flex items-center justify-center text-white font-bold mr-3 shadow-md">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h1 class="text-xl font-bold text-transparent bg-clip-text gradient-bg">{{ $setting->app_name }}
                    </h1>
                </div>

                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}"
                        class="text-gray-600 hover:text-primary-600 transition duration-300 font-medium">Home</a>
                    <a href="#about"
                        class="text-gray-600 hover:text-primary-600 transition duration-300 font-medium">About</a>
                    <a href="#services"
                        class="text-gray-600 hover:text-primary-600 transition duration-300 font-medium">Services</a>
                    <a href="#features"
                        class="text-gray-600 hover:text-primary-600 transition duration-300 font-medium">Features</a>
                    <a href="#testimonials"
                        class="text-gray-600 hover:text-primary-600 transition duration-300 font-medium">Testimonials</a>
                </nav>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}"
                        class="hidden md:flex gradient-bg text-white px-4 py-2 rounded-lg font-medium hover:bg-primary-700 transition duration-300 shadow-md hover:shadow-lg">Login</a>

                    {{-- <a href="#"
                        class="hidden md:flex gradient-bg text-white px-4 py-2 rounded-lg font-medium hover:bg-primary-700 transition duration-300 shadow-md hover:shadow-lg">Admin</a>
                    --}}

                    <!-- Mobile menu button -->
                    <button id="mobile-menu-button" class="md:hidden text-gray-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t py-4 px-4">
            <div class="flex flex-col space-y-4">
                <a href="{{ route('home') }}"
                    class="text-gray-600 hover:text-primary-600 transition duration-300 font-medium">Home</a>
                <a href="#about"
                    class="text-gray-600 hover:text-primary-600 transition duration-300 font-medium">About</a>
                <a href="#services"
                    class="text-gray-600 hover:text-primary-600 transition duration-300 font-medium">Services</a>
                <a href="#features"
                    class="text-gray-600 hover:text-primary-600 transition duration-300 font-medium">Features</a>
                <a href="#testimonials"
                    class="text-gray-600 hover:text-primary-600 transition duration-300 font-medium">Testimonials</a>
                <a href="{{ route('login') }}"
                    class="text-gray-600 hover:text-primary-600 transition duration-300 font-medium">Login</a>
            </div>
        </div>
    </header>
