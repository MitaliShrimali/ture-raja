<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'TourRaja — Premium Global Travel Experiences')</title>
    <meta name="description" content="@yield('description', 'Discover curated luxury travel packages with TourRaja. Your premium global travel partner.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Lordicon for animated icons -->
    <script src="https://cdn.lordicon.com/xdjxvujz.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body 
    x-data="{ isScrolled: false, isMobileMenuOpen: false, isHome: {{ request()->is('/') ? 'true' : 'false' }} }" 
    @scroll.window="isScrolled = window.pageYOffset > 50"
    class="min-h-full flex flex-col font-body bg-background text-text-main"
>
    <!-- Navbar Component -->
    <x-navbar />

    <!-- Mobile Menu Component -->
    <x-mobile-menu />

    <main class="relative flex-grow" :class="!isHome ? 'pt-[96px]' : ''">
        @yield('content')
    </main>

    <!-- Footer Component -->
    <x-footer />

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
    @stack('scripts')
</body>
</html>
