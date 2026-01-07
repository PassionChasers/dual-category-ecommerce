<style>
    /* Hide scrollbar */
    .sidebar-scroll::-webkit-scrollbar {
        display: none !important;
    }

    /* --- MOBILE SIDEBAR --- */
    #mobile-sidebar-toggle {
        display: none;
    }

    /* Overlay for mobile when sidebar is open */
    #mobile-sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 40;
    }

    /* Sidebar container for mobile */
    .mobile-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 16rem;
        background: white;
        border-right: 1px solid #e5e7eb;
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        z-index: 50;
    }

    /* Show sidebar and overlay when checkbox checked */
    #mobile-sidebar-toggle:checked~#mobile-sidebar-overlay {
        display: block;
    }

    #mobile-sidebar-toggle:checked~.mobile-sidebar {
        transform: translateX(0);
    }

    /* Close button inside mobile sidebar */
    .close-sidebar {
        cursor: pointer;
        font-size: 1.5rem;
        font-weight: bold;
    }

    /* Desktop sidebar always visible */
    @media (min-width: 768px) {

        .mobile-sidebar,
        #mobile-sidebar-overlay,
        #mobile-sidebar-toggle {
            display: none;
        }

        .desktop-sidebar {
            display: flex;
            flex-shrink: 0;
        }
    }
</style>

<!-- MOBILE TOGGLE -->
<input type="checkbox" id="mobile-sidebar-toggle">
<div class="absolute top-4 left-4 md:hidden z-50">
    <label for="mobile-sidebar-toggle" class="text-gray-600 cursor-pointer">
        <i class="fas fa-bars text-2xl"></i>
    </label>
</div>

<!-- Overlay -->
<label for="mobile-sidebar-toggle" id="mobile-sidebar-overlay" class="md:hidden"></label>

<!-- Mobile Sidebar -->
<div class="mobile-sidebar flex flex-shrink-0 md:hidden">
    <div class="flex flex-col w-64 bg-white border-r border-gray-200">
        <!-- Logo -->
        <div class="flex items-center justify-center py-5 px-4 bg-indigo-600">
            <a href="{{ route('admin.dashboard') }}">
                <h1 class="text-white font-bold text-xl">{{ $setting->app_name}}</h1>
            </a>
        </div>

        <!-- Navigation -->
        <div class="flex flex-col flex-grow px-4 py-4 overflow-y-auto sidebar-scroll">
            <div class="space-y-1">

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link flex items-center px-2 py-3 text-sm font-medium rounded-md
                        {{ request()->routeIs('admin.dashboard') ? 'text-indigo-500 bg-indigo-100' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-tachometer-alt mr-3 text-indigo-600
                        {{ request()->routeIs('dashboard') ? 'text-indigo-500' : 'text-gray-600' }}"></i>
                    Dashboard
                </a>

                <!-- Tasks Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('product.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-2 py-3 text-sm font-medium rounded-md
                    {{ request()->routeIs('product.*') ? 'text-indigo-700 bg-indigo-100' : 'text-gray-600 hover:bg-gray-100' }}">
                        <div class="flex items-center">
                            <i class="fas fa-tasks mr-3 
                                {{ request()->routeIs('product.*') ? 'text-indigo-500' : 'text-gray-600' }}"></i>
                            <span>Products</span>
                        </div>
                        <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="text-xs"></i>
                    </button>

                    <div x-show="open" class="ml-6 mt-2 space-y-1">

                        
                        <!--Dropdown for Medicines -->
                        <div x-data="{ open: {{ request()->routeIs('product.medicine.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-2 py-3 text-sm font-medium rounded-md
                                {{ request()->routeIs('product.medicine.*') ? 'text-indigo-700 bg-indigo-100' : 'text-gray-600 hover:bg-gray-100' }}">
                                <div class="flex items-center">
                                    <i
                                        class="fa-solid fa-capsules text-green-500
                                    mr-3 {{ request()->routeIs('product.medicine.*') ? 'text-indigo-500' : 'text-gray-600' }}">
                                    </i>
                                    <span>Medicine</span>
                                </div>
                                <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="text-xs"></i>
                            </button>

                            <div x-show="open" class="ml-6 mt-2 space-y-1">
                                <a href="{{route('product.medicine.index')}}"
                                    class="flex items-center px-2 py-2 text-sm rounded-md
                                        {{ request()->routeIs('medicine.allmedicine') ? 'text-indigo-700 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <i class="fas fa-list mr-2 text-green-700"></i> All Medicines
                                </a>
                                <a href="{{ route('product.medicine.category') }}"
                                    class="flex items-center px-2 py-2 text-sm rounded-md
                                    {{ request()->routeIs('product.medicine.category') ? 'text-indigo-700 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <i class="fas fa-tags mr-2 text-orange-500"></i> Medicine Category
                                </a>
                            </div>
                        </div>

                        <!-- Dropdown for food -->
                        <div x-data="{ open: {{ request()->routeIs('product.food.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-2 py-3 text-sm font-medium rounded-md
                                {{ request()->routeIs('product.food.*') ? 'text-indigo-700 bg-indigo-100' : 'text-gray-600 hover:bg-gray-100' }}">
                                <div class="flex items-center">
                                    <i
                                        class="fa-solid fa-utensils text-yellow-500
                                    mr-3 {{ request()->routeIs('product.food.*') ? 'text-indigo-500' : 'text-gray-600' }}">
                                    </i>
                                    <span>Food</span>
                                </div>
                                <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="text-xs"></i>
                            </button>

                            <div x-show="open" class="ml-6 mt-2 space-y-1">
                                <a href="{{route('product.food.index')}}"
                                    class="flex items-center px-2 py-2 text-sm rounded-md
                                        {{ request()->routeIs('product.food.index') ? 'text-indigo-700 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <i class="fas fa-list mr-2 text-green-700"></i> All Foods
                                </a>

                                <a href="{{ route('product.food.category') }}"
                                    class="flex items-center px-2 py-2 text-sm rounded-md
                                    {{ request()->routeIs('product.food.category') ? 'text-indigo-700 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <i class="fas fa-tags mr-2 text-orange-500"></i> Food Category
                                </a>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- ORDERS Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('orders.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-2 py-3 text-sm font-medium rounded-md
                    {{ request()->routeIs('orders.*') ? 'text-indigo-700 bg-indigo-100' : 'text-gray-600 hover:bg-gray-100' }}">
                        <div class="flex items-center">
                            <i class="fas fa-tasks mr-3 text-pink-600
                                {{ request()->routeIs('orders.*') ? 'text-indigo-500' : 'text-gray-600' }}"></i>
                            <span>Orders</span>
                        </div>
                        <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="text-xs"></i>
                    </button>

                    <div x-show="open" class="ml-6 mt-2 space-y-1">

                        <a href="{{ route('orders.index') }}"
                            class="flex items-center px-2 py-2 text-sm rounded-md
                                {{ request()->routeIs('orders.index') ? 'text-indigo-700 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fa-solid fa-clipboard-list mr-2 text-pink-500"></i> All orders
                        </a>
                        <a href="{{ route('orders.medicine.index') }}"
                            class="flex items-center px-2 py-2 text-sm rounded-md
                                {{ request()->routeIs('orders.medicine.index') ? 'text-indigo-700 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fa-solid fa-prescription-bottle-medical mr-2 text-green-500"></i> Medicine Orders
                        </a>
                        <a href="{{ route('orders.food.index') }}"
                            class="flex items-center px-2 py-2 text-sm rounded-md
                                {{ request()->routeIs('orders.food.index') ? 'text-indigo-700 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fa-solid fa-utensils mr-2 text-yellow-500"></i> Food Orders
                        </a>
                    </div>
                </div>


                <!-- Settings Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('settings.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-2 py-3 text-sm font-medium rounded-md
                        {{ request()->routeIs('settings.*') ? 'text-indigo-700 bg-indigo-100' : 'text-gray-600 hover:bg-gray-100' }}">
                        <div class="flex items-center">
                            <i class="fas fa-cog mr-3 text-gray-500
                                {{ request()->routeIs('settings.*') ? 'text-indigo-500' : 'text-gray-600' }}">
                            </i>
                            <span>Settings</span>
                        </div>
                        <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="text-xs"></i>
                    </button>

                    <div x-show="open" class="ml-6 mt-2 space-y-1">
                        <a href="{{ route('settings.general') }}"
                            class="flex items-center px-2 py-2 text-sm rounded-md
                                {{ request()->routeIs('settings.general') ? 'text-indigo-700 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fas fa-sliders-h mr-2">
                            </i>
                            General Setting
                        </a>
                        <a href="{{ route('settings.institutions') }}"
                            class="hidden items-center px-2 py-2 text-sm rounded-md
                            {{ request()->routeIs('settings.institutions') ? 'text-indigo-700 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fas fa-university mr-2"></i> Institution Setup
                        </a>
                    </div>
                </div>

                {{-- ADS Settings --}}
                <div>
                    <a href="{{route('admin.ads.index')}}"
                        class="flex items-center px-2 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 
                            {{ request()->routeIs('admin.ads.*') ? 'text-indigo-500 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fas fa-ad mr-2 text-green-500"></i>Manage Ads
                    </a>
                </div>

                <!-- Admin Section -->
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Administration
                    </h3>

                    <div x-data="{ open: {{ request()->routeIs('users.*') || request()->routeIs('designations.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-2 py-3 text-sm font-medium
                                {{ request()->routeIs('users.*') || request()->routeIs('designations.*') || request()->routeIs('departments.*') ? 'text-indigo-500 bg-indigo-100 rounded-md' : 'text-gray-600 hover:bg-gray-100' }}">
                            <span class="flex items-center">
                                <i
                                    class="fas fa-users-cog mr-3 text-purple-500
                                        {{ request()->routeIs('users.*') || request()->routeIs('designations.*') || request()->routeIs('departments.*') ? 'text-indigo-500' : 'text-gray-600' }}">
                                </i>
                                User Management
                            </span>
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>

                        <div x-show="open" class="ml-6 mt-2 space-y-1" x-cloak>
                            <a href="{{ route('users.admin.index') }}"
                                class="flex items-center px-2 py-2 text-sm rounded-md {{ request()->routeIs('users.admin.index') ? 'text-indigo-500 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                <i class="fas fa-user-shield mr-2 text-indigo-600"></i> Admin
                            </a>
                            <a href="{{ route('users.medicalstores.index') }}"
                                class="flex items-center px-2 py-2 text-sm rounded-md {{ request()->routeIs('users.medicalstores.index') ? 'text-indigo-500 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                <i class="fas fa-clinic-medical mr-2 text-red-600"></i> Medicalstore
                            </a>
                            <a href="{{ route('users.restaurants.index') }}"
                                class="flex items-center px-2 py-2 text-sm rounded-md {{ request()->routeIs('users.restaurants.index') ? 'text-indigo-500 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                <i class="fas fa-utensils mr-2 text-yellow-500"></i> Restaurants
                            </a>
                            <a href="{{ route('users.customers.index') }}"
                                class="flex items-center px-2 py-2 text-sm rounded-md {{ request()->routeIs('users.customers.index') ? 'text-indigo-500 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                <i class="fas fa-users mr-2 text-purple-500"></i> Customer
                            </a>
                        </div>
                    </div>

                    {{-- Register Business --}}
                    <div x-data="{ open: {{ request()->routeIs('admin.medicalstores.*') || request()->routeIs('admin.restaurants.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-2 py-3 text-sm font-medium
                                {{ request()->routeIs('admin.medicalstores.*') || request()->routeIs('admin.restaurants.*') ? 'text-indigo-500 bg-indigo-100 rounded-md' : 'text-gray-600 hover:bg-gray-100' }}">
                            <span class="flex items-center">
                                <i
                                    class="fas fa-briefcase mr-3 text-blue-600
                                        {{ request()->routeIs('admin.medicalstores.*') || request()->routeIs('admin.restaurants.*') ? 'text-indigo-500' : 'text-gray-600' }}">
                                </i>
                                Register Business
                            </span>
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>

                        <div x-show="open" class="ml-6 mt-2 space-y-1" x-cloak>
                            <a href="{{route('admin.restaurants.list')}}"
                                class="flex items-center px-2 py-2 text-sm rounded-md {{ request()->routeIs('admin.restaurants.*') ? 'text-indigo-500 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                <i class="fas fa-utensils mr-2"></i> Restaurant Business
                            </a>
                            <a href="{{route('admin.medicalstores.list')}}"
                                class="flex items-center px-2 py-2 text-sm rounded-md {{ request()->routeIs('admin.medicalstores.*') ? 'text-indigo-500 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                <i class="fas fa-clinic-medical mr-2"></i> Medicalstore Business
                            </a>
                             <a href="#"
                                class="flex items-center px-2 py-2 text-sm rounded-md {{ request()->routeIs('admin.add-business.*') ? 'text-indigo-500 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                <i class="fas fa-add mr-2 text-blue-600"></i> Add New Business
                            </a>
                        </div>
                    </div>


                    <a href="{{route('auditlog.index')}}"
                        class="flex items-center px-2 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 
                            {{ request()->routeIs('auditlog.index') ? 'text-indigo-500 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fas fa-history mr-2"></i> Audit Logs
                    </a>
                </div>
            </div>
        </div>

        <!-- User Info -->
        <div class="p-4 border-t border-gray-200 bg-white">
            <div class="flex items-center justify-between">
                <!-- Profile Section -->
                <div class="flex items-center space-x-3">
                    <a href="#" class="group relative">

                        <img class="w-11 h-11 rounded-full ring-2 ring-indigo-100 group-hover:ring-indigo-300 transition"
                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&q=80&w=100&h=100&fit=crop"
                            alt="Default user">
                    </a>

                    <div>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ auth()->user()->name }}

                        </p>
                        <span
                            class="text-xs font-medium bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-full inline-block mt-1">
                            {{ auth()->user()->name }}

                        </span>
                    </div>
                </div>

                <!-- Logout Icon -->
                <form action="{{route('logout')}}" class="ml-2">
                    @csrf
                    <button type="submit" class="text-gray-500 hover:text-red-600 transition" title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>






<!-- Desktop Sidebar -->
<div class="hidden md:flex md:flex-shrink-0 desktop-sidebar">
    <div class="flex flex-col w-64 bg-white border-r border-gray-200">
        <!-- Logo -->
        <div class="flex items-center justify-center py-5 px-4 bg-indigo-600">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                <!-- Logo -->
                <img src="https://pcsdecom.azurewebsites.net{{  $setting->Favicon }}" alt="{{ $setting->AppName }} Logo"
                    class="w-8 h-8 object-cover rounded-md shadow-md border border-white/20">
                <!-- App Name -->
                <span class="text-white font-bold text-xl">
                    {{ $setting->AppName }}
                </span>
            </a>
        </div>

        <!-- Navigation -->
        <div class="flex flex-col flex-grow px-4 py-4 overflow-y-auto sidebar-scroll">
            <div class="space-y-1">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard')}}"
                    class="sidebar-link flex items-center px-2 py-3 text-sm font-medium rounded-md
                    {{ request()->routeIs('admin.dashboard') ? 'text-indigo-500 bg-indigo-100' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i
                        class="fas fa-tachometer-alt mr-3 text-indigo-600 {{ request()->routeIs('dashboard') ? 'text-indigo-500' : 'text-gray-600' }}"></i>
                    Dashboard
                </a>

                <!-- Products Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('product.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-2 py-3 text-sm font-medium rounded-md
                        {{ request()->routeIs('product.*') ? 'text-indigo-700 bg-indigo-100' : 'text-gray-600 hover:bg-gray-100' }}">
                        <div class="flex items-center">
                            <i
                                class="fas fa-tasks mr-3 {{ request()->routeIs('product.*') ? 'text-indigo-500' : 'text-gray-600' }}"></i>
                            <span>Products</span>
                        </div>
                        <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="text-xs"></i>
                    </button>

                    <div x-show="open" class="ml-6 mt-2 space-y-1">

                        
                        <!-- Dropdown for Medicine -->
                        <div x-data="{ open: {{ request()->routeIs('product.medicine.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-2 py-3 text-sm font-medium rounded-md
                            {{ request()->routeIs('product.medicine.*') ? 'text-indigo-700 bg-indigo-100' : 'text-gray-600 hover:bg-gray-100' }}">
                                <div class="flex items-center">
                                    <i
                                        class="fa-solid fa-capsules text-green-500
                                    mr-3 {{ request()->routeIs('product.medicine.*') ? 'text-indigo-500' : 'text-gray-600' }}"></i>
                                    <span>Medicine</span>
                                </div>
                                <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="text-xs"></i>
                            </button>

                            <div x-show="open" class="ml-6 mt-2 space-y-1">
                                <a href="{{route('product.medicine.index')}}"
                                    class="flex items-center px-2 py-2 text-sm rounded-md
                                        {{ request()->routeIs('product.medicine.index') ? 'text-indigo-700 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <i class="fas fa-list mr-2 text-green-700"></i> All Medicines
                                </a>
                                

                                <a href="{{ route('product.medicine.category') }}"
                                    class="flex items-center px-2 py-2 text-sm rounded-md
                                    {{ request()->routeIs('product.medicine.category') ? 'text-indigo-700 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <i class="fas fa-tags mr-2 text-orange-500"></i> Medicine Category
                                </a>
                            </div>
                        </div>

                        <!--Dropdown for Food -->
                        <div x-data="{ open: {{ request()->routeIs('product.food.*') ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                class="w-full flex items-center justify-between px-2 py-3 text-sm font-medium rounded-md
                            {{ request()->routeIs('product.food.*') ? 'text-indigo-700 bg-indigo-100' : 'text-gray-600 hover:bg-gray-100' }}">
                                <div class="flex items-center">
                                    <i
                                        class=" fa-solid fa-utensils text-yellow-500
                                    mr-3 {{ request()->routeIs('product.food.*') ? 'text-indigo-500' : 'text-gray-600' }}"></i>
                                    <span>Food</span>
                                </div>
                                <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="text-xs"></i>
                            </button>

                            <div x-show="open" class="ml-6 mt-2 space-y-1">
                                <a href="{{route('product.food.index')}}"
                                    class="flex items-center px-2 py-2 text-sm rounded-md
                                        {{ request()->routeIs('product.food.index') ? 'text-indigo-700 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <i class="fas fa-list mr-2 text-green-700"></i> All Foods
                                </a>
                                <a href="{{ route('product.food.category') }}"
                                    class="flex items-center px-2 py-2 text-sm rounded-md
                                    {{ request()->routeIs('product.food.category') ? 'text-indigo-700 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                    <i class="fas fa-tags mr-2 text-orange-500"></i> Food Category
                                </a>
                            </div>
                        </div>
                    </div>
                </div>


                {{-- // Orders Link --}}
                <div x-data="{ open: {{ request()->routeIs('orders.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-2 py-3 text-sm font-medium rounded-md
                        {{ request()->routeIs('orders.*') ? 'text-indigo-700 bg-indigo-100' : 'text-gray-600 hover:bg-gray-100' }}">
                        <div class="flex items-center">
                            <i
                                class="fa-solid fa-cart-plus mr-3 text-pink-600 {{ request()->routeIs('orders.*') ? 'text-indigo-500' : 'text-gray-600' }}"></i>
                            <span>Orders</span>
                        </div>
                        <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="text-xs"></i>
                    </button>

                    <div x-show="open" class="ml-6 mt-2 space-y-1">

                        <a href="{{ route('orders.index') }}"
                            class="flex items-center px-2 py-2 text-sm rounded-md
                                {{ request()->routeIs('orders.index') ? 'text-indigo-700 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fa-solid fa-clipboard-list mr-2 text-pink-500"></i> All orders
                        </a>
                        <a href="{{ route('orders.medicine.index') }}"
                            class="flex items-center px-2 py-2 text-sm rounded-md
                                {{ request()->routeIs('orders.medicine.index') ? 'text-indigo-700 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fa-solid fa-prescription-bottle-medical mr-2 text-green-500"></i> Medicine Orders
                        </a>
                        <a href="{{ route('orders.food.index') }}"
                            class="flex items-center px-2 py-2 text-sm rounded-md
                                {{ request()->routeIs('orders.food.index') ? 'text-indigo-700 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fa-solid fa-utensils mr-2 text-yellow-500"></i> Food Orders
                        </a>
                    </div>
                </div>


                <!-- Settings Dropdown -->
                <div x-data="{ open: {{ request()->routeIs('settings.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-2 py-3 text-sm font-medium rounded-md
                    {{ request()->routeIs('settings.*') ? 'text-indigo-700 bg-indigo-100' : 'text-gray-600 hover:bg-gray-100' }}">
                        <div class="flex items-center">
                            <i
                                class="fas fa-cog mr-3 text-gray-500 {{ request()->routeIs('settings.*') ? 'text-indigo-500' : 'text-gray-600' }}"></i>
                            <span>Settings</span>
                        </div>
                        <i :class="open ? 'fas fa-chevron-up' : 'fas fa-chevron-down'" class="text-xs"></i>
                    </button>

                    <div x-show="open" class="ml-6 mt-2 space-y-1">
                        <a href="{{ route('settings.general') }}"
                            class="flex items-center px-2 py-2 text-sm rounded-md
                                {{ request()->routeIs('settings.general') ? 'text-indigo-700 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fas fa-sliders-h mr-2"></i> General Setting
                        </a>
                        <a href="{{ route('settings.institutions') }}"
                            class="hidden items-center px-2 py-2 text-sm rounded-md
                            {{ request()->routeIs('settings.institutions') ? 'text-indigo-700 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                            <i class="fas fa-university mr-2"></i> Institution Setup
                        </a>
                    </div>
                </div>

                {{-- ADS Settings --}}
                <div>
                    <a href="{{route('admin.ads.index')}}"
                        class="flex items-center px-2 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 
                            {{ request()->routeIs('admin.ads.*') ? 'text-indigo-500 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fas fa-ad mr-2 text-green-500"></i> Manage Ads
                    </a>
                </div>


                <!-- Admin Section -->
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Administration
                    </h3>

                    <div x-data="{ open: {{ request()->routeIs('users.*') || request()->routeIs('customers.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-2 py-3 text-sm font-medium
                                {{ request()->routeIs('users.*') || request()->routeIs('customers.*') ? 'text-indigo-500 bg-indigo-100 rounded-md' : 'text-gray-600 hover:bg-gray-100' }}">
                            <span class="flex items-center">
                                <i class="fas fa-users-cog mr-3 text-purple-500 {{ request()->routeIs('users.*') || request()->routeIs('customers.*') ? 'text-indigo-500' : 'text-gray-600' }}"></i>
                                User Management
                            </span>
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>

                        <div x-show="open" class="ml-6 mt-2 space-y-1" x-cloak>
                            <a href="{{ route('users.admin.index') }}"
                                class="flex items-center px-2 py-2 text-sm rounded-md {{ request()->routeIs('users.admin.index') ? 'text-indigo-500 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                <i class="fas fa-user-shield mr-2 text-indigo-600"></i> Admin
                            </a>
                            <a href="{{ route('users.medicalstores.index') }}"
                                class="flex items-center px-2 py-2 text-sm rounded-md {{ request()->routeIs('users.medicalstores.index') ? 'text-indigo-500 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                <i class="fas fa-clinic-medical mr-2 text-red-600"></i> Medicalstore
                            </a>
                            <a href="{{ route('users.restaurants.index') }}"
                                class="flex items-center px-2 py-2 text-sm rounded-md {{ request()->routeIs('users.restaurants.index') ? 'text-indigo-500 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                <i class="fas fa-utensils mr-2 text-yellow-500"></i> Restaurants
                            </a>
                            <a href="{{ route('users.customers.index') }}"
                                class="flex items-center px-2 py-2 text-sm rounded-md {{ request()->routeIs('users.customers.index') ? 'text-indigo-500 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                <i class="fas fa-users mr-2 text-purple-500"></i> Customer
                            </a>

                        </div>
                    </div>

                    
                    {{-- Register the business --}}
                    <div x-data="{ open: {{ request()->routeIs('admin.medicalstores.*') || request()->routeIs('admin.restaurants.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-2 py-3 text-sm font-medium
                                {{ request()->routeIs('admin.medicalstores.*') || request()->routeIs('admin.restaurants.*') ? 'text-indigo-500 bg-indigo-100 rounded-md' : 'text-gray-600 hover:bg-gray-100' }}">
                            <span class="flex items-center">
                                <i
                                    class="fas fa-briefcase mr-3 text-blue-600"></i>
                                Register Business
                            </span>
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>

                        <div x-show="open" class="ml-6 mt-2 space-y-1" x-cloak>
                            <a href="{{route('admin.restaurants.list')}}"
                                class="flex items-center px-2 py-2 text-sm rounded-md {{ request()->routeIs('admin.restaurants.*') ? 'text-indigo-500 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                <i class="fas fa-utensils mr-2 text-yellow-500"></i> Restaurant Business
                            </a>
                            <a href="{{route('admin.medicalstores.list')}}"
                                class="flex items-center px-2 py-2 text-sm rounded-md {{ request()->routeIs('admin.medicalstores.*') ? 'text-indigo-500 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                <i class="fas fa-clinic-medical mr-2 text-red-600"></i> Medicalstore Business
                            </a>
                            <a href="#"
                                class="flex items-center px-2 py-2 text-sm rounded-md {{ request()->routeIs('admin.add-business.*') ? 'text-indigo-500 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                                <i class="fas fa-add mr-2 text-blue-600"></i> Add New Business
                            </a>

                        </div>
                    </div>

                    <a href="{{route('auditlog.index')}}"
                        class="flex items-center px-2 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100
                        {{ request()->routeIs('auditlog.index') ? 'text-indigo-500 bg-indigo-100 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                        <i class="fas fa-history mr-2"></i> Audit Logs
                    </a>
                </div>
            </div>
        </div>

        <!-- User Info -->
        <div class="p-4 border-t border-gray-200 bg-white">
            <div class="flex items-center justify-between">
                <!-- Profile Section -->
                <div class="flex items-center space-x-3">
                    <a href="{{route('admin.profile.edit')}}" class="group relative">

                        <img class="w-11 h-11 rounded-full ring-2 ring-indigo-100 group-hover:ring-indigo-300 transition"
                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&q=80&w=100&h=100&fit=crop"
                            alt="Default user">

                    </a>

                    <div>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ auth()->user()->name }}
                        </p>
                        <span
                            class="text-xs font-medium bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-full inline-block mt-1">
                            {{ auth()->user()->name }}
                        </span>
                    </div>
                </div>

                <!-- Logout Icon -->
                <form action="{{route('logout')}}" class="ml-2">
                    @csrf
                    <button type="submit" class="text-gray-500 hover:text-red-600 transition" title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>