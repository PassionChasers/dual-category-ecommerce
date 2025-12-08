<!-- Navigation -->
<header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md shadow-sm">
    @php
        $appName   = $settings->app_name ?? 'Ecommerce';
        $appLogo   = !empty($settings->favicon) ? asset('storage/' . $settings->favicon) : null;
        $onHome    = request()->routeIs('home');
        $linkBase  = 'text-sm font-medium transition duration-300';
        $linkBaseDesktop = 'text-gray-600 hover:text-primary-600 ' . $linkBase;
        $linkBaseMobile  = 'text-gray-700 hover:text-primary-600 ' . $linkBase;
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-3 md:py-4">
            {{-- Brand / Logo --}}
            <a href="{{ route('home') }}" class="flex items-center group">
                @if($appLogo)
                    <div class="w-10 h-10 rounded-lg overflow-hidden mr-3 shadow-md border border-gray-100">
                        <img src="{{ $appLogo }}" alt="{{ $appName }} Logo" class="w-full h-full object-cover">
                    </div>
                @else
                    <div
                        class="w-10 h-10 rounded-lg gradient-bg flex items-center justify-center text-white font-bold mr-3 shadow-md">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                @endif
                <div class="flex flex-col">
                    <span
                        class="text-xl font-bold text-indigo-800 leading-tight group-hover:opacity-90">
                        {{-- {{ $appName }} --}}
                        {{ $setting->app_name }}
                    </span>
                    <span class="text-[11px] uppercase tracking-[0.16em] text-gray-400 hidden sm:block">
                        Online Store
                    </span>
                </div>
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden md:flex items-center space-x-6 lg:space-x-8">
                {{-- Home --}}
                <a href="{{ route('home') }}"
                   class="{{ request()->routeIs('home') ? 'text-primary-600 font-semibold' : $linkBaseDesktop }}">
                    Home
                </a>

                {{-- About (section on home) --}}
                <a href="{{ $onHome ? '#about' : route('home') . '#about' }}"
                   class="{{ $linkBaseDesktop }}">
                    About
                </a>

                {{-- Features (section on home) --}}
                <a href="{{ $onHome ? '#features' : route('home') . '#features' }}"
                   class="{{ $linkBaseDesktop }}">
                    Features
                </a>

                {{-- Testimonials (section on home) --}}
                <a href="{{ $onHome ? '#testimonials' : route('home') . '#testimonials' }}"
                   class="{{ $linkBaseDesktop }}">
                    Testimonials
                </a>

                {{-- Support & FAQ as separate pages --}}
                <a href="{{ route('support') }}"
                   class="{{ request()->routeIs('support') ? 'text-primary-600 font-semibold' : $linkBaseDesktop }}">
                    Support
                </a>
                <a href="{{ route('faq') }}"
                   class="{{ request()->routeIs('faq') ? 'text-primary-600 font-semibold' : $linkBaseDesktop }}">
                    FAQ
                </a>
            </nav>

            {{-- Right side: Auth / CTA --}}
            <div class="flex items-center space-x-3">
                <a href="{{ route('login') }}"
                   class="hidden md:inline-flex items-center gradient-bg text-white px-4 py-2 rounded-lg text-sm font-semibold
                          hover:opacity-90 transition duration-300 shadow-md hover:shadow-lg">
                    <i class="fas fa-user mr-2 text-xs"></i> Login
                </a>

                {{-- Optional: Contact CTA icon on desktop --}}
                <a href="{{ route('contact') }}"
                   class="hidden md:inline-flex items-center justify-center w-9 h-9 rounded-full border border-gray-200 text-gray-500 hover:text-primary-600 hover:border-primary-200 transition">
                    <i class="fas fa-envelope text-xs"></i>
                </a>

                <!-- Mobile menu button -->
                <button id="mobile-menu-button"
                        class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded-md border border-gray-200 text-gray-600 hover:text-primary-600 hover:border-primary-300 transition">
                    <i class="fas fa-bars text-lg"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 py-4 px-4">
        <div class="flex flex-col space-y-3">
            <a href="{{ route('home') }}"
               class="{{ request()->routeIs('home') ? 'text-primary-600 font-semibold' : $linkBaseMobile }}">
                Home
            </a>
            <a href="{{ $onHome ? '#about' : route('home') . '#about' }}"
               class="{{ $linkBaseMobile }}">
                About
            </a>
            <a href="{{ $onHome ? '#features' : route('home') . '#features' }}"
               class="{{ $linkBaseMobile }}">
                Features
            </a>
            <a href="{{ $onHome ? '#testimonials' : route('home') . '#testimonials' }}"
               class="{{ $linkBaseMobile }}">
                Testimonials
            </a>
            <a href="{{ route('support') }}"
               class="{{ request()->routeIs('support') ? 'text-primary-600 font-semibold' : $linkBaseMobile }}">
                Support
            </a>
            <a href="{{ route('faq') }}"
               class="{{ request()->routeIs('faq') ? 'text-primary-600 font-semibold' : $linkBaseMobile }}">
                FAQ
            </a>
            <a href="{{ route('contact') }}"
               class="{{ request()->routeIs('contact') ? 'text-primary-600 font-semibold' : $linkBaseMobile }}">
                Contact
            </a>
            <a href="{{ route('login') }}"
               class="mt-2 inline-flex justify-center items-center bg-gradient-to-r from-primary-500 to-primary-600 text-white text-sm font-semibold py-2.5 rounded-lg shadow-md hover:opacity-90 transition">
                <i class="fas fa-user mr-2 text-xs"></i> Login
            </a>
        </div>
    </div>
</header>
