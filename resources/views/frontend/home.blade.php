@extends('layouts.user.app')
@section('title',$setting->app_name . "| Home")

@push('styles')

@endpush

@section('content')
    <!-- Hero Section -->
    <section class="relative gradient-bg text-white py-20 flex items-center justify-center overflow-hidden" id="home">
        <!-- Floating background shapes -->
        <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
            <div class="absolute top-20 left-10 w-72 h-72 bg-white rounded-full animate-float"></div>
            <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full animate-float"
                style="animation-delay: 2s;"></div>
        </div>

        <!-- Centered Content -->
        <div class="max-w-3xl mx-auto px-6 text-center relative z-10">
            <h2 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                Streamline Your Workflow,
                <span class="text-primary-400">Maximize Productivity</span>
            </h2>
            <p class="text-lg md:text-xl mb-8 text-primary-100">
                {{ $setting->app_name }} helps individuals and teams organize tasks, track progress, and achieve goals
                faster with intuitive task management tools.
            </p>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('login') }}"
                    class="bg-white text-primary-600 px-6 py-4 rounded-lg font-semibold text-center shadow-lg hover:bg-gray-50 transition duration-300 flex items-center justify-center">
                    <span>Get Started</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="#demo"
                    class="bg-transparent border-2 border-white text-white px-6 py-4 rounded-lg font-semibold text-center hover:bg-white hover:bg-opacity-10 transition duration-300 flex items-center justify-center">
                    <i class="fas fa-play-circle mr-2"></i>
                    <span>Watch Demo</span>
                </a>
            </div>

        </div>
    </section>


    <!-- Stats Section -->
    <section class="py-10 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="p-6 rounded-lg hover:bg-gray-50 transition duration-300">
                    <p class="text-3xl md:text-4xl font-bold text-primary-600">{{$userCount}}+</p>
                    <p class="text-gray-600 mt-2">Active Users</p>
                </div>
                <div class="p-6 rounded-lg hover:bg-gray-50 transition duration-300">
                    <p class="text-3xl md:text-4xl font-bold text-primary-600">{{$taskCount}}+</p>
                    <p class="text-gray-600 mt-2">Tasks Completed</p>
                </div>
                <div class="p-6 rounded-lg hover:bg-gray-50 transition duration-300">
                    <p class="text-3xl md:text-4xl font-bold text-primary-600">95%</p>
                    <p class="text-gray-600 mt-2">Satisfaction Rate</p>
                </div>
                <div class="p-6 rounded-lg hover:bg-gray-50 transition duration-300">
                    <p class="text-3xl md:text-4xl font-bold text-primary-600">24/7</p>
                    <p class="text-gray-600 mt-2">Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-16 bg-gray-50" id="about">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="md:w-1/2">
                    <h3 class="text-3xl md:text-4xl font-bold mb-6">About {{ $setting->app_name }}</h3>
                    <p class="text-gray-600 mb-4">
                        {{ $setting->app_name }} was founded in 2020 with a simple mission: to help teams work more
                        efficiently by providing intuitive task management solutions.
                    </p>
                    <p class="text-gray-600 mb-6">
                        Our platform combines powerful features with an elegant interface, making it easy for teams of
                        all sizes to organize, track, and complete their projects successfully.
                    </p>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center mr-3">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="font-medium">Award-winning platform</span>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center mr-3">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="font-medium">Global team</span>
                        </div>
                    </div>
                </div>
                <div class="md:w-1/2">
                    <div class="rounded-xl overflow-hidden shadow-lg">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1000&q=80"
                            alt="Team collaborating" class="w-full h-auto">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-16 bg-white" id="services">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h3 class="text-3xl md:text-4xl font-bold mb-4">Our Services</h3>
                <p class="text-gray-600">We offer comprehensive task management solutions tailored to your team's needs
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="services-card bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div
                        class="w-14 h-14 rounded-lg bg-indigo-100 text-primary-600 flex items-center justify-center mb-4">
                        <i class="fas fa-cogs text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Implementation</h4>
                    <p class="text-gray-600">Get started quickly with our expert implementation services and onboarding
                        support.</p>
                </div>

                <div class="services-card bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div
                        class="w-14 h-14 rounded-lg bg-green-100 text-secondary-600 flex items-center justify-center mb-4">
                        <i class="fas fa-graduation-cap text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Training</h4>
                    <p class="text-gray-600">Comprehensive training programs for your team to maximize {{
                        $setting->app_name }}'s potential.</p>
                </div>

                <div class="services-card bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="w-14 h-14 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mb-4">
                        <i class="fas fa-headset text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Support</h4>
                    <p class="text-gray-600">24/7 customer support to help you resolve issues and answer questions
                        quickly.</p>
                </div>

                <div class="services-card bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div
                        class="w-14 h-14 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center mb-4">
                        <i class="fas fa-puzzle-piece text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Integration</h4>
                    <p class="text-gray-600">Seamlessly connect {{ $setting->app_name }} with your existing tools and
                        workflows.</p>
                </div>

                <div class="services-card bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div
                        class="w-14 h-14 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center mb-4">
                        <i class="fas fa-chart-pie text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Custom Analytics</h4>
                    <p class="text-gray-600">Get tailored reports and analytics to track your team's performance
                        metrics.</p>
                </div>

                <div class="services-card bg-white rounded-xl shadow-md p-6 border border-gray-100">
                    <div class="w-14 h-14 rounded-lg bg-red-100 text-red-600 flex items-center justify-center mb-4">
                        <i class="fas fa-shield-alt text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Security</h4>
                    <p class="text-gray-600">Enterprise-grade security solutions to protect your data and workflows.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-16 bg-gradient-to-br from-gray-50 to-indigo-50" id="features">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h3 class="text-3xl md:text-4xl font-bold mb-4">Powerful Features for Team Productivity</h3>
                <p class="text-gray-600">Everything you need to organize tasks, collaborate with your team, and hit
                    deadlines</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="features-card bg-white rounded-xl p-6 shadow-sm">
                    <div
                        class="w-14 h-14 rounded-lg bg-indigo-100 text-primary-600 flex items-center justify-center mb-4">
                        <i class="fas fa-tasks text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Intuitive Task Management</h4>
                    <p class="text-gray-600">Create, organize, and prioritize tasks with drag-and-drop simplicity.</p>
                </div>

                {{-- <div class="features-card bg-white rounded-xl p-6 shadow-sm">
                    <div
                        class="w-14 h-14 rounded-lg bg-green-100 text-secondary-600 flex items-center justify-center mb-4">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Team Collaboration</h4>
                    <p class="text-gray-600">Assign tasks, share files, and communicate in real-time with your team.</p>
                </div> --}}

                <div class="features-card bg-white rounded-xl p-6 shadow-sm">
                    <div class="w-14 h-14 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mb-4">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Progress Tracking</h4>
                    <p class="text-gray-600">Visualize your progress with charts and analytics to stay on target.</p>
                </div>

                <div class="features-card bg-white rounded-xl p-6 shadow-sm">
                    <div
                        class="w-14 h-14 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center mb-4">
                        <i class="fas fa-bell text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Smart Reminders</h4>
                    <p class="text-gray-600">Never miss a deadline with customizable notifications and alerts.</p>
                </div>

                {{-- <div class="features-card bg-white rounded-xl p-6 shadow-sm">
                    <div
                        class="w-14 h-14 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center mb-4">
                        <i class="fas fa-calendar-alt text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Calendar Integration</h4>
                    <p class="text-gray-600">Sync tasks with your calendar and plan your schedule efficiently.</p>
                </div> --}}

                {{-- <div class="features-card bg-white rounded-xl p-6 shadow-sm">
                    <div class="w-14 h-14 rounded-lg bg-red-100 text-red-600 flex items-center justify-center mb-4">
                        <i class="fas fa-mobile-alt text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Mobile Access</h4>
                    <p class="text-gray-600">Manage tasks on the go with our iOS and Android mobile apps.</p>
                </div> --}}
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 bg-white" id="testimonials">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h3 class="text-3xl md:text-4xl font-bold mb-4">Trusted by Teams Worldwide</h3>
                <p class="text-gray-600">See what our users say about their experience with {{ $setting->app_name }}</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="testimonial-card bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center font-bold text-primary-700 mr-4 testimonial-avatar">
                            SN</div>
                        <div>
                            <h4 class="font-bold">Shikshya Nepal</h4>
                            <p class="text-gray-500 text-sm">Project Manager</p>
                        </div>
                    </div>
                    <p class="text-gray-700">"{{ $setting->app_name }} has transformed how our team works. We're 40%
                        more productive since we started using it."</p>
                    <div class="mt-4 flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                <div class="testimonial-card bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center font-bold text-primary-700 mr-4 testimonial-avatar">
                            CS</div>
                        <div>
                            <h4 class="font-bold">Charu Shrestha</h4>
                            <p class="text-gray-500 text-sm">Marketing Director</p>
                        </div>
                    </div>
                    <p class="text-gray-700">"The collaboration features are fantastic. Our team is always on the same
                        page now."</p>
                    <div class="mt-4 flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                <div class="testimonial-card bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center mb-4">
                        <div
                            class="w-12 h-12 rounded-full bg-primary-100 flex items-center justify-center font-bold text-primary-700 mr-4 testimonial-avatar">
                            SS</div>
                        <div>
                            <h4 class="font-bold">Shibu Sharma</h4>
                            <p class="text-gray-500 text-sm">Software Engineer</p>
                        </div>
                    </div>
                    <p class="text-gray-700">"I've tried many task managers, but {{ $setting->app_name }} strikes the
                        perfect balance between simplicity and power."</p>
                    <div class="mt-4 flex text-yellow-400">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
    
@endpush