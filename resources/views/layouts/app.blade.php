<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'TourRaja — Premium Global Travel Experiences')</title>
    <meta name="description" content="@yield('description', 'Discover curated luxury travel packages with TourRaja. Your premium global travel partner.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    x-data="{ isScrolled: false, isMobileMenuOpen: false, isHome: {{ request()->is('/') ? 'true' : 'false' }}, showLoginModal: false }" 
    @scroll.window="isScrolled = window.pageYOffset > 50"
    @open-login-modal.window="showLoginModal = true"
    class="min-h-full flex flex-col font-body bg-background text-text-main"
    :class="{ 'overflow-hidden': isMobileMenuOpen }"
>
    <!-- Navbar Component -->
    <x-navbar />

    <!-- Mobile Menu Component -->
    <x-mobile-menu />

    <main class="relative flex-grow" :class="(isHome) ? '' : 'pt-[120px] lg:pt-[140px]'">
        @yield('content')
    </main>

    <!-- Footer Component -->
    <x-footer />

    <!-- Login Modal -->
    <x-login-modal />

    <style>
        .wishlist-btn.active svg {
            fill: currentColor !important;
        }
        [x-cloak] { display: none !important; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            updateWishlistUI();
        });

        // ── Wishlist Logic ───────────────────────────────────────────────────
        window.toggleWishlist = function(e, pkg) {
            e.preventDefault();
            e.stopPropagation();

            let wishlist = JSON.parse(localStorage.getItem('tourraja_wishlist') || '[]');
            const index = wishlist.findIndex(item => item.slug === pkg.slug);

            if (index > -1) {
                wishlist.splice(index, 1);
            } else {
                wishlist.push(pkg);
            }

            localStorage.setItem('tourraja_wishlist', JSON.stringify(wishlist));
            updateWishlistUI();

            // ── Also persist to DB so profile wishlist tab shows items ──
            const csrf = document.querySelector('meta[name="csrf-token"]');
            if (csrf) {
                fetch('/wishlist/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf.getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        package_id:    pkg.slug,
                        package_title: pkg.title,
                        package_image: pkg.image,
                        package_price: pkg.price,
                    })
                }).catch(() => {}); // silent fail — localStorage is still the source of truth
            }
        };

        window.updateWishlistUI = function() {
            const wishlist = JSON.parse(localStorage.getItem('tourraja_wishlist') || '[]');
            const countEl = document.getElementById('wishlist-count');
            const itemsEl = document.getElementById('wishlist-items');
            
            const countElMobile = document.getElementById('wishlist-count-mobile');
            const itemsElMobile = document.getElementById('wishlist-items-mobile');
            
            // Update Counts
            if (countEl) {
                countEl.textContent = wishlist.length;
                countEl.classList.toggle('hidden', wishlist.length === 0);
            }
            if (countElMobile) {
                countElMobile.textContent = wishlist.length;
            }
            
            // Update Dropdown Items (Desktop)
            if (itemsEl) {
                if (wishlist.length === 0) {
                    itemsEl.innerHTML = '<p class="text-center text-text-muted text-xs py-8 font-bold">Your wishlist is empty</p>';
                } else {
                    itemsEl.innerHTML = wishlist.map(item => `
                        <a href="/packages/${item.slug}" class="flex items-center gap-3 group/item" style="text-decoration:none;color:inherit">
                            <img src="${item.image}" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <h5 class="text-xs font-black text-foreground truncate">${item.title}</h5>
                                <p class="text-[10px] text-primary font-bold">₹${Number(item.price).toLocaleString()}</p>
                            </div>
                            <button onclick="event.preventDefault();event.stopPropagation();toggleWishlist(event,{slug:'${item.slug}'})" class="text-gray-300 hover:text-primary transition-colors flex-shrink-0">
                                <i data-lucide="trash-2" size="14"></i>
                            </button>
                        </a>
                    `).join('');
                }
            }

            // Update Items (Mobile)
            if (itemsElMobile) {
                if (wishlist.length === 0) {
                    itemsElMobile.innerHTML = '<p class="text-xs text-text-muted font-bold italic">No items yet</p>';
                } else {
                    itemsElMobile.innerHTML = wishlist.map(item => `
                        <a href="/packages/${item.slug}" class="flex items-center gap-3 bg-white p-2 rounded-xl shadow-soft" style="text-decoration:none;color:inherit">
                            <img src="${item.image}" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <h5 class="text-[10px] font-black text-foreground truncate">${item.title}</h5>
                                <p class="text-[10px] text-primary font-bold">₹${Number(item.price).toLocaleString()}</p>
                            </div>
                            <button onclick="event.preventDefault();event.stopPropagation();toggleWishlist(event,{slug:'${item.slug}'})" class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-all flex-shrink-0">
                                <i data-lucide="trash-2" size="14"></i>
                            </button>
                        </a>
                    `).join('');
                }
            }

            lucide.createIcons();
            
            // Update Button States
            document.querySelectorAll('.wishlist-btn').forEach(btn => {
                const slug = btn.getAttribute('data-wishlist-slug');
                const isInWishlist = wishlist.some(item => item.slug === slug);
                
                if (isInWishlist) {
                    btn.classList.add('active', 'bg-white', 'text-primary');
                    btn.classList.remove('bg-white/20', 'text-white');
                } else {
                    btn.classList.remove('active', 'bg-white', 'text-primary');
                    btn.classList.add('bg-white/20', 'text-white');
                }
            });
        };
    </script>
    @stack('scripts')
</body>
</html>
