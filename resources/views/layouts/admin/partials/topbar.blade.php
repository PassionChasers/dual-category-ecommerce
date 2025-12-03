<div x-data="{ isSidebarOpen: false }"
    class="flex items-center justify-end py-[18px] px-4 bg-white border-b border-gray-200 relative">

    <!-- Right Side -->
    <div class="flex items-center space-x-4 relative">

        <!-- Live Date & Time -->
        <div id="datetimeDisplay" class="flex flex-col text-right leading-tight">
            <span class="text-sm font-medium text-gray-700" id="currentDay">Monday</span>
            <span class="text-xs text-gray-500" id="currentDateTime">January 1, 2025 | 12:00 PM</span>
        </div>

        <!-- Notifications (Dummy Static Badge) -->
        <a href="#"
            class="p-1 text-gray-400 rounded-full hover:text-gray-500 relative">
            <i class="fas fa-bell text-gray-600 text-lg"></i>

            <!-- Static dummy count -->
            <span class="absolute -top-1 -right-1.5 bg-red-500 text-white text-[10px] font-semibold
                   rounded-full min-w-[18px] h-[18px] flex items-center justify-center
                   px-[4px] leading-none shadow-md ring-2 ring-white">
                5
            </span>
        </a>

        <!-- Quick Actions -->
        <div class="relative">
            <button id="quickActionsBtn" class="flex items-center text-sm text-gray-500 focus:outline-none">
                <span class="hidden md:inline-block">Quick Actions</span>
                <i class="ml-1 fas fa-chevron-down"></i>
            </button>

            <div id="quickActionsDropdown"
                class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">

                <a href="{{route('admin.profile')}}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>

                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Quick Action Dropdown
    const btn = document.getElementById('quickActionsBtn');
    const dropdown = document.getElementById('quickActionsDropdown');
    const chevron = btn.querySelector('i');

    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdown.classList.toggle('hidden');
        chevron.classList.toggle('fa-chevron-down');
        chevron.classList.toggle('fa-chevron-up');
    });

    window.addEventListener('click', (e) => {
        if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.add('hidden');
            chevron.classList.remove('fa-chevron-up');
            chevron.classList.add('fa-chevron-down');
        }
    });

    // 🔥 REAL LIVE Dynamic Date & Time (Nepal Time – Asia/Kathmandu)
    function updateDateTime() {
        const now = new Date();

        // Convert to Nepal time zone (UTC + 5:45)
        const nepalTime = new Date(now.toLocaleString("en-US", { timeZone: "Asia/Kathmandu" }));

        const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        const months = [
            "January","February","March","April","May","June",
            "July","August","September","October","November","December"
        ];

        let dayName = days[nepalTime.getDay()];
        let monthName = months[nepalTime.getMonth()];
        let day = nepalTime.getDate();
        let year = nepalTime.getFullYear();

        // Format time
        let hours = nepalTime.getHours();
        let minutes = nepalTime.getMinutes().toString().padStart(2, "0");
        let ampm = hours >= 12 ? "PM" : "AM";
        hours = hours % 12 || 12;

        let formattedTime = `${monthName} ${day}, ${year} | ${hours}:${minutes} ${ampm}`;

        document.getElementById('currentDay').textContent = dayName;
        document.getElementById('currentDateTime').textContent = formattedTime;
    }

    // Update every second
    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>
@endpush
