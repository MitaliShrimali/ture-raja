<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tour Raja Agent')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#F0642F', // Brand Orange
                        secondary: '#4A5568',
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        .bg-pattern {
            background: linear-gradient(135deg, #F0642F 0%, #FF8A50 100%);
            position: relative;
            overflow: hidden;
        }

        .bg-pattern::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://www.transparenttextures.com/patterns/cubes.png');
            opacity: 0.1;
        }

        /* Smooth sidebar transition */
        #sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform;
        }

        /* Prevent body scroll when mobile sidebar is open */
        body.sidebar-open {
            overflow: hidden;
        }

        /* Touch-friendly horizontal scroll on tables */
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }

        /* Mobile: reduce inner padding for cards */
        @media (max-width: 640px) {
            .rounded-3xl,
            .rounded-\[2\.5rem\],
            .rounded-\[32px\],
            .rounded-\[48px\] {
                border-radius: 1rem;
            }

            .p-8 {
                padding: 1.25rem;
            }

            .p-12 {
                padding: 1.5rem;
            }

            .text-3xl {
                font-size: 1.5rem;
            }

            .text-4xl {
                font-size: 1.75rem;
            }
        }

        /* Make sure sidebar backdrop is always below sidebar but above content */
        #sidebarBackdrop {
            transition: opacity 0.3s ease;
        }

        /* Main layout shift smoothly */
        main {
            min-height: 100vh;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50">

    <div class="flex min-h-screen bg-gray-50">
        <!-- Sidebar -->
        @include('agent.components.sidebar')

        <!-- Main Content -->
        <main class="flex-grow min-w-0 ml-0 lg:ml-72 p-4 sm:p-6 lg:p-8 overflow-x-hidden transition-all duration-300">
            <!-- Navbar -->
            @include('agent.components.navbar')

            @yield('content')

            <footer class="mt-12 flex flex-col lg:flex-row items-center justify-between py-6 border-t border-gray-100">
                <p class="text-xs text-gray-400 font-medium mb-4 lg:mb-0">Copyright © 2026 Tour Raja Private Limited, India. All rights reserved.</p>
                <div class="flex space-x-6 text-xs text-gray-400 font-medium">
                    <a href="#" class="hover:text-primary">About Us</a>
                    <a href="#" class="hover:text-primary">License</a>
                    <a href="#" class="hover:text-primary">Terms of Services</a>
                    <a href="#" class="hover:text-primary">Privacy Policy</a>
                </div>
            </footer>
        </main>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery (required for Toastr) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('agent/assets/js/main.js') }}"></script>
    <script src="{{ asset('agent/assets/js/validation.js') }}"></script>
    @stack('scripts')
</body>
</html>
