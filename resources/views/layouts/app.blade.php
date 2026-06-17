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
    x-data="{ isScrolled: false, isMobileMenuOpen: false, isHome: {{ request()->is('/') ? 'true' : 'false' }}, showLoginModal: false, showChefModal: false }" 
    @scroll.window="isScrolled = window.pageYOffset > 50"
    @open-login-modal.window="showLoginModal = true"
    @open-chef-modal.window="showChefModal = true; setTimeout(() => { showChefModal = false }, 5000)"
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

    <!-- Chef & Tour Manager Modal -->
    <x-chef-manager-modal />



    <style>
        .wishlist-btn.active svg {
            fill: currentColor !important;
        }
        [x-cloak] { display: none !important; }

        /* Search Autocomplete Suggestions Styling */
        .search-suggestions-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #ffffff !important;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            z-index: 99999;
            margin-top: 4px;
            max-height: 280px;
            overflow-y: auto;
        }
        .search-suggestions-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 400;
            color: #1f2937 !important;
            transition: all 0.2s;
            text-align: left;
        }
        .search-suggestions-item span {
            color: #1f2937 !important;
        }
        .search-suggestions-item strong {
            font-weight: 700;
            color: #000000 !important;
        }
        .search-suggestions-item:hover, .search-suggestions-item.active {
            background-color: #f3f4f6 !important;
            color: #e85d26 !important;
        }
        .search-suggestions-item:hover span, .search-suggestions-item.active span {
            color: #e85d26 !important;
        }
        .search-suggestions-item:hover strong, .search-suggestions-item.active strong {
            color: #e85d26 !important;
        }
        .search-suggestions-item .suggest-icon {
            color: #9ca3af !important;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .search-suggestions-item:hover .suggest-icon, .search-suggestions-item.active .suggest-icon {
            color: #e85d26 !important;
        }
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

        // ── Search suggestions autocomplete initialization ────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('input[name="destination"], input[name="search"], input[name="from_city"], input[name="city"], input[name="company"]');
            
            inputs.forEach(input => {
                const parent = input.closest('div');
                if (parent) {
                    parent.style.position = 'relative';
                }
                
                const dropdown = document.createElement('div');
                dropdown.className = 'search-suggestions-dropdown';
                dropdown.style.display = 'none';
                if (parent) {
                    parent.appendChild(dropdown);
                }
                
                let activeIndex = -1;
                let suggestionItems = [];
                
                let typeParam = 'destination';
                if (input.name === 'from_city' || input.name === 'city') {
                    typeParam = 'agent_location';
                } else if (input.name === 'company') {
                    typeParam = 'company';
                }
                
                const fetchSuggestions = debounce(async (query) => {
                    if (query.trim().length < 1) {
                        dropdown.innerHTML = '';
                        dropdown.style.display = 'none';
                        return;
                    }
                    
                    try {
                        const res = await fetch(`{{ url('/api/search-suggestions') }}?q=${encodeURIComponent(query)}&type=${typeParam}`);
                        const data = await res.json();
                        
                        if (data.length === 0) {
                            dropdown.innerHTML = '';
                            dropdown.style.display = 'none';
                            return;
                        }
                        

                                                suggestionItems = data;
                        activeIndex = -1;
                        
                        dropdown.innerHTML = data.map((item, idx) => {
                            const typedText = query.toLowerCase();
                            const itemText = item.text;
                            let displayHtml = itemText;
                            
                            const matchIdx = itemText.toLowerCase().indexOf(typedText);
                            if (matchIdx !== -1) {
                                const before = itemText.substring(0, matchIdx);
                                const match = itemText.substring(matchIdx, matchIdx + typedText.length);
                                const after = itemText.substring(matchIdx + typedText.length);
                                
                                displayHtml = '';
                                if (before) {
                                    displayHtml += `<strong>${before}</strong>`;
                                }
                                displayHtml += `<span style="font-weight: 400;">${match}</span>`;
                                if (after) {
                                    displayHtml += `<strong>${after}</strong>`;
                                }
                            } else {
                                displayHtml = `<strong>${itemText}</strong>`;
                            }
                            
                            const iconSvg = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="suggest-icon"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>`;
                                
                            return `
                                <div class="search-suggestions-item" data-index="${idx}">
                                    ${iconSvg}
                                    <span>${displayHtml}</span>
                                </div>
                            `;
                        }).join('');
                        
                        dropdown.style.display = 'block';
                        
                        dropdown.querySelectorAll('.search-suggestions-item').forEach(itemEl => {
                            itemEl.addEventListener('click', () => {
                                const index = itemEl.getAttribute('data-index');
                                selectSuggestion(suggestionItems[index]);
                            });
                        });
                        
                    } catch (err) {
                        console.error('Error fetching search suggestions:', err);
                    }
                }, 150);
                
                input.addEventListener('input', (e) => {
                    fetchSuggestions(e.target.value);
                });
                
                input.addEventListener('keydown', (e) => {
                    const items = dropdown.querySelectorAll('.search-suggestions-item');
                    if (dropdown.style.display === 'none' || items.length === 0) return;
                    
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        if (activeIndex < items.length - 1) {
                            activeIndex++;
                            updateActiveItem(items);
                        }
                    } else if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        if (activeIndex > 0) {
                            activeIndex--;
                            updateActiveItem(items);
                        }
                    } else if (e.key === 'Enter') {
                        if (activeIndex > -1) {
                            e.preventDefault();
                            selectSuggestion(suggestionItems[activeIndex]);
                        }
                    } else if (e.key === 'Escape') {
                        dropdown.style.display = 'none';
                    }
                });
                
                document.addEventListener('click', (e) => {
                    if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.style.display = 'none';
                    }
                });
                
                input.addEventListener('focus', () => {
                    if (input.value.length > 0) {
                        fetchSuggestions(input.value);
                    }
                });
                
                function updateActiveItem(items) {
                    items.forEach((item, idx) => {
                        if (idx === activeIndex) {
                            item.classList.add('active');
                            item.scrollIntoView({ block: 'nearest' });
                        } else {
                            item.classList.remove('active');
                        }
                    });
                }
                
                function selectSuggestion(item) {
                    input.value = item.text;
                    dropdown.style.display = 'none';
                    
                    // Dispatch input event so AJAX filter listener triggers correctly
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    
                    const form = input.closest('form');
                    if (form) {
                        const submitEvent = new Event('submit', { cancelable: true, bubbles: true });
                        form.dispatchEvent(submitEvent);
                        if (!submitEvent.defaultPrevented) {
                            form.submit();
                        }
                    }
                }
            });
            
            function debounce(func, wait) {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
