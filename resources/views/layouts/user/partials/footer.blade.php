    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center mb-4">
                        <div
                            class="w-10 h-10 rounded-lg gradient-bg flex items-center justify-center text-white font-bold mr-3">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h2 class="text-white text-xl font-bold">{{ $setting->app_name }}</h2>
                    </div>
                    <p class="mb-6">The modern task management platform for teams who want to achieve more.</p>
                    
                    <div class="flex space-x-4">
                        {{-- Twitter (X) --}}
                        @if(!empty($setting->twitter_url))
                        <a href="{{ $setting->twitter_url }}" target="_blank"
                            class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:text-white hover:bg-primary-600 transition duration-300"
                            title="Twitter (X)">
                            <span class="text-lg font-bold">𝕏</span>
                        </a>
                        @endif

                        {{-- Facebook --}}
                        @if(!empty($setting->facebook_url))
                        <a href="{{ $setting->facebook_url }}" target="_blank"
                            class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:text-white hover:bg-primary-600 transition duration-300"
                            title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        @endif

                        {{-- LinkedIn --}}
                        @if(!empty($setting->linkedin_url))
                        <a href="{{ $setting->linkedin_url }}" target="_blank"
                            class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:text-white hover:bg-primary-600 transition duration-300"
                            title="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        @endif

                        {{-- Instagram --}}
                        @if(!empty($setting->instagram_url))
                        <a href="{{ $setting->instagram_url }}" target="_blank"
                            class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:text-white hover:bg-primary-600 transition duration-300"
                            title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        @endif
                    </div>


                </div>

                <div>
                    <h3 class="text-white font-semibold mb-6 text-lg">Product</h3>
                    <ul class="space-y-4">
                        <li><a href="#home" class="hover:text-white transition duration-300 flex items-center">
                                <i class="fas fa-chevron-right text-xs text-primary-600 mr-2"></i>Home</a></li>
                        <li><a href="#features" class="hover:text-white transition duration-300 flex items-center">
                                <i class="fas fa-chevron-right text-xs text-primary-600 mr-2"></i>Features</a></li>
                        {{-- <li><a href="#pricing" class="hover:text-white transition duration-300 flex items-center">
                                <i class="fas fa-chevron-right text-xs text-primary-600 mr-2"></i>Pricing</a></li> --}}
                        <li><a href="#services" class="hover:text-white transition duration-300 flex items-center">
                                <i class="fas fa-chevron-right text-xs text-primary-600 mr-2"></i>Services</a></li>
                        <li><a href="#testimonials" class="hover:text-white transition duration-300 flex items-center">
                                <i class="fas fa-chevron-right text-xs text-primary-600 mr-2"></i>Testimonials</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-6 text-lg">Resources</h3>
                    <ul class="space-y-4">
                        {{-- <li><a href="#" class="hover:text-white transition duration-300 flex items-center">
                                <i class="fas fa-chevron-right text-xs text-primary-600 mr-2"></i>Blog</a></li> --}}
                        {{-- <li><a href="#" class="hover:text-white transition duration-300 flex items-center">
                                <i class="fas fa-chevron-right text-xs text-primary-600 mr-2"></i>Webinars</a></li> --}}
                        <li><a href="{{ route('faq') }}" class="hover:text-white transition duration-300 flex items-center">
                                <i class="fas fa-chevron-right text-xs text-primary-600 mr-2"></i>FAQ</a></li>
                        <li><a href="{{ route('support') }}" class="hover:text-white transition duration-300 flex items-center">
                                <i class="fas fa-chevron-right text-xs text-primary-600 mr-2"></i>Support</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-white font-semibold mb-6 text-lg">Company</h3>
                    <ul class="space-y-4">
                        <li><a href="{{ route('about')}}" class="hover:text-white transition duration-300 flex items-center">
                                <i class="fas fa-chevron-right text-xs text-primary-600 mr-2"></i>About Us</a></li>
                        {{-- <li><a href="#" class="hover:text-white transition duration-300 flex items-center">
                                <i class="fas fa-chevron-right text-xs text-primary-600 mr-2"></i>Careers</a></li> --}}
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition duration-300 flex items-center">
                                <i class="fas fa-chevron-right text-xs text-primary-600 mr-2"></i>Contact</a></li>
                        <li><a href="{{ route('privacy') }}" class="hover:text-white transition duration-300 flex items-center">
                                <i class="fas fa-chevron-right text-xs text-primary-600 mr-2"></i>Privacy Policy</a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Newsletter Subscription -->
            <div class="mt-16 pt-8 border-t border-gray-800 hidden">
                <div class="md:flex md:items-center md:justify-between">
                    <div class="md:w-1/2 mb-6 md:mb-0">
                        <h3 class="text-white text-lg font-semibold mb-2">Stay Updated</h3>
                        <p class="text-gray-500">Subscribe to our newsletter for the latest updates and features.</p>
                    </div>
                    <div class="md:w-1/2">
                        <form class="flex flex-col sm:flex-row gap-3">
                            <input type="email" placeholder="Your email address"
                                class="flex-grow px-4 py-3 rounded-lg bg-gray-800 border border-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <button type="submit"
                                class="gradient-bg text-white px-6 py-3 rounded-lg font-medium hover:opacity-90 transition duration-300 shadow-md">
                                Subscribe
                            </button>
                        </form>
                    </div>
                </div>
            </div>


            <!-- Copyright -->
            <div class="mt-8 pt-8 border-t border-gray-800 text-sm text-center">
                <p>&copy; {{ date('Y') }} {{ $setting->app_name }}. All rights reserved.</p>
                <p class="mt-2">Designed & Developed by
                    <a href="https://passionchasers.com/"> Passion Chasers.</a>
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu if open
                    const mobileMenu = document.getElementById('mobile-menu');
                    if (!mobileMenu.classList.contains('hidden')) {
                        mobileMenu.classList.add('hidden');
                    }
                }
            });
        });
    </script>
</body>

</html>