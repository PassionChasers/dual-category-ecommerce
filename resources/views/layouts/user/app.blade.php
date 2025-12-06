<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ $settings->app_name ?? 'Ecommerce' }} - Modern Task Management System</title>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @if($setting && $setting->favicon)
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/' . $setting->favicon) }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $setting->favicon) }}">
    @else
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/passionchasers.png') }}">
    @endif

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        secondary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        dark: '#1f2937',
                        light: '#f9fafb'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #4F46E5 0%, #7E22CE 100%);
        }

        /* Services Section Styles */
        .services-card {
            transition: all 0.3s ease;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            z-index: 1;
        }

        .services-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 0;
            background: linear-gradient(135deg, #4F46E5 0%, #7E22CE 100%);
            opacity: 0;
            transition: all 0.4s ease;
            z-index: -1;
        }

        .services-card:hover::before {
            height: 100%;
            opacity: 0.05;
        }

        .services-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(79, 70, 229, 0.25);
        }

        /* Features Section Styles */
        .features-card {
            transition: transform 0.35s ease, box-shadow 0.35s ease;
            background: linear-gradient(to bottom right, #ffffff, #f9fafb);
            border-radius: 1rem;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .features-card::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.08) 0%, transparent 70%);
            transform: rotate(25deg);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .features-card:hover::before {
            opacity: 1;
        }

        .features-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 15px 25px -5px rgba(0, 0, 0, 0.15);
        }

        /* Testimonials Section Styles */
        .testimonial-card {
            transition: all 0.3s ease;
            background: white;
            position: relative;
            overflow: hidden;
        }

        .testimonial-card::before {
            content: "";
            position: absolute;
            top: -20px;
            left: 10px;
            font-size: 120px;
            color: #4F46E5;
            opacity: 0.1;
            font-family: Arial;
            line-height: 1;
        }

        .testimonial-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .testimonial-avatar {
            transition: all 0.3s ease;
        }

        .testimonial-card:hover .testimonial-avatar {
            transform: scale(1.1);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.2);
        }
    </style>
</head>

<body class="bg-light text-dark font-sans antialiased">
        <script>
        @if(session('error'))
    Swal.fire({
    toast: true,
    position: 'top-end',
    icon: 'error',
    title: "{{ session('error') }}",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    background: '#fff',
    color: '#333',
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
    });
    @endif
    </script>

    {{-- <div class="flex h-screen overflow-hidden"> --}}
        {{-- @include('layouts.user.partials.sidebar') --}}
        <div class="flex flex-col flex-1">
            <!-- Top navigation -->

            @include('layouts.user.partials.topbar')
            @yield('content')
            @stack('scripts')
            @include('layouts.user.partials.footer')
        </div>
    {{-- </div> --}}
</body>

</html>