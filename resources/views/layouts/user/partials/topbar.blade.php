<!-- Navigation -->
<header id="main-nav" class="sticky top-0 z-50 bg-white/70 backdrop-blur-md border-b border-white/20 transition-all duration-300">
    @php
        $appName   = $settings->app_name ?? 'Ecommerce';
        $appLogo   = !empty($settings->favicon) ? asset('storage/' . $settings->favicon) : null;
        $onHome    = request()->routeIs('home');
        
        // Link Styling
        $linkBase = "nav-link relative py-2 text-sm font-semibold transition-all duration-300 group";
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 md:h-20">
            
            {{-- Brand / Logo --}}
            <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                @if($appLogo)
                    <div class="w-10 h-10 rounded-xl overflow-hidden shadow-sm border border-gray-100 group-hover:scale-110 transition-transform">
                        <img src="{{ $appLogo }}" alt="{{ $appName }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-yellow-500 flex items-center justify-center text-white shadow-lg group-hover:rotate-12 transition-transform">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                @endif
                <div class="flex flex-col">
                    <span class="text-xl font-black tracking-tight text-gray-800 leading-none">
                       SD Mart
                    </span>
                    
                </div>
            </a>

            {{-- Desktop Nav --}}
            <nav class="hidden md:flex items-center space-x-8">
                @foreach([
                    ['name' => 'Home', 'route' => 'home', 'url' => route('home'), 'id' => 'home'],
                    ['name' => 'About', 'url' => $onHome ? '#about' : route('home').'#about', 'id' => 'about'],
                    ['name' => 'Features', 'url' => $onHome ? '#features' : route('home').'#features', 'id' => 'features'],
                    ['name' => 'Testimonials', 'url'=> $onHome ? '#testimonials': route('home').'#testimonials', 'id' => 'testimonials'],
                    ['name' => 'Support', 'route' => 'support', 'url' => route('support'), 'id' => 'support'],
                    ['name' => 'FAQ', 'route' => 'faq', 'url' => route('faq'), 'id' => 'faq']
                ] as $item)
                    @php $isActive = isset($item['route']) && request()->routeIs($item['route']); @endphp
                    
                    <a href="{{ $item['url'] }}" 
                       data-section="{{ $item['id'] }}"
                       class="{{ $linkBase }} {{ $isActive ? 'text-orange-600 active-nav' : 'text-gray-600 hover:text-orange-500' }}">
                        {{ $item['name'] }}
                        <span class="underline-bar absolute bottom-0 left-0 h-0.5 bg-gradient-to-r from-orange-500 to-yellow-500 transition-all duration-300 
                            {{ $isActive ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                    </a>
                @endforeach
            </nav>

            {{-- Right side: Auth & Toggle --}}
            <div class="flex items-center space-x-3">
                <a href="{{ route('login') }}"
                   class="hidden md:flex items-center px-6 py-2 rounded-full bg-gradient-to-r from-orange-500 to-yellow-500 text-white text-sm font-bold shadow-md hover:shadow-orange-200 hover:-translate-y-0.5 transition-all active:scale-95">
                    Login
                </a>

                {{-- MOBILE MENU TOGGLE --}}
                <button id="mobile-menu-btn" class="md:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors focus:outline-none">
                    <i class="fas fa-bars text-xl" id="menu-icon"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu Container --}}
    <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 bg-white/95 backdrop-blur-xl transition-all duration-300">
        <nav class="flex flex-col p-4 space-y-1">
            <a href="{{ route('home') }}" class="p-3 rounded-xl font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-600">Home</a>
            <a href="{{ $onHome ? '#about' : route('home').'#about' }}" class="p-3 rounded-xl font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-600">About</a>
            <a href="{{ route('support') }}" class="p-3 rounded-xl font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-600">Support</a>
            <a href="{{ route('faq') }}" class="p-3 rounded-xl font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-600">FAQ</a>
            
            <div class="pt-4 mt-2 border-t border-gray-100">
                <a href="{{ route('login') }}" class="w-full flex justify-center items-center py-3 rounded-xl bg-gradient-to-r from-orange-500 to-yellow-500 text-white font-bold">
                    Login
                </a>
            </div>
        </nav>
    </div>
</header>

<script>
    // 1. Fix Response Menu Toggle
    const menuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('menu-icon');

    menuBtn.addEventListener('click', () => {
        const isHidden = mobileMenu.classList.contains('hidden');
        if(isHidden) {
            mobileMenu.classList.remove('hidden');
            menuIcon.classList.replace('fa-bars', 'fa-times');
        } else {
            mobileMenu.classList.add('hidden');
            menuIcon.classList.replace('fa-times', 'fa-bars');
        }
    });

    // Close mobile menu on resize if screen becomes desktop
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            mobileMenu.classList.add('hidden');
            menuIcon.classList.replace('fa-times', 'fa-bars');
        }
    });

    // 2. Active Section Scrollspy Logic
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link');

    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (pageYOffset >= (sectionTop - 150)) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('text-orange-600');
            link.classList.add('text-gray-600');
            const underline = link.querySelector('.underline-bar');
            underline.classList.remove('w-full');
            underline.classList.add('w-0');

            if (link.getAttribute('data-section') === current) {
                link.classList.add('text-orange-600');
                link.classList.remove('text-gray-600');
                underline.classList.add('w-full');
                underline.classList.remove('w-0');
            }
        });
    });
</script>