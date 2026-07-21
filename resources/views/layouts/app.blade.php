<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth antialiased">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'TourRaja — Premium Global Travel Experiences')</title>
    <meta name="description"
        content="@yield('description', 'Discover curated luxury travel packages with TourRaja. Your premium global travel partner.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Styles & Scripts -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <!-- <script src="https://unpkg.com/@tailwindcss/browser@4"></script> -->

    <!-- Lordicon for animated icons -->
    <script src="https://cdn.lordicon.com/xdjxvujz.js"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body x-data="{ 
        isScrolled: false, 
        isPastTransits: false,
        isMobileMenuOpen: false, 
        isHome: {{ request()->is('/') ? 'true' : 'false' }}, 
        showLoginModal: false, 
        showChefModal: false, 
        globalShowGallery: false, 
        globalSlides: [], 
        globalGalleryTitle: '',
        globalLightboxOpen: false,
        globalLightboxIndex: 0,
        openLightbox(index) {
            this.globalLightboxIndex = index;
            this.globalLightboxOpen = true;
        },
        nextImage() {
            this.globalLightboxIndex = (this.globalLightboxIndex + 1) % this.globalSlides.length;
        },
        prevImage() {
            this.globalLightboxIndex = (this.globalLightboxIndex - 1 + this.globalSlides.length) % this.globalSlides.length;
        }
    }" @scroll.window="
        isScrolled = window.pageYOffset > 50;
        let pt = document.getElementById('popular-transits-section');
        isPastTransits = pt ? (window.pageYOffset > (pt.offsetTop + pt.offsetHeight - 80)) : isScrolled;
    " @open-login-modal.window="showLoginModal = true"
    @open-chef-modal.window="showChefModal = true; setTimeout(() => { showChefModal = false }, 5000)"
    @open-gallery.window="globalShowGallery = true; globalLightboxOpen = false; globalSlides = $event.detail.slides; globalGalleryTitle = $event.detail.title"
    class="min-h-full flex flex-col font-body bg-background text-text-main"
    :class="{ 'overflow-hidden': isMobileMenuOpen || globalShowGallery }">
    <!-- Navbar Component -->
    <div x-show="!globalShowGallery">
        <x-navbar />
    </div>

    <!-- Mobile Menu Component -->
    <div x-show="!globalShowGallery">
        <x-mobile-menu />
    </div>

    <main class="relative flex-grow" :style="!isHome ? 'padding-top: 80px;' : ''">
        @yield('content')
    </main>

    <!-- Footer Component -->
    <x-footer />

    <!-- Login Modal -->
    <x-login-modal />

    <!-- Chef & Tour Manager Modal -->
    {{-- <x-chef-manager-modal /> --}}



    <style>
        .wishlist-btn.active svg {
            fill: currentColor !important;
        }

        [x-cloak] {
            display: none !important;
        }

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

        .search-suggestions-item:hover,
        .search-suggestions-item.active {
            background-color: #f3f4f6 !important;
            color: #e85d26 !important;
        }

        .search-suggestions-item:hover span,
        .search-suggestions-item.active span {
            color: #e85d26 !important;
        }

        .search-suggestions-item:hover strong,
        .search-suggestions-item.active strong {
            color: #e85d26 !important;
        }

        .search-suggestions-item .suggest-icon {
            color: #9ca3af !important;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .search-suggestions-item:hover .suggest-icon,
        .search-suggestions-item.active .suggest-icon {
            color: #e85d26 !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            updateWishlistUI();
        });

        // ── Wishlist Logic ───────────────────────────────────────────────────
        window.toggleWishlist = function (e, pkg) {
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
                        package_id: pkg.slug,
                        package_title: pkg.title,
                        package_image: pkg.image,
                        package_price: pkg.price,
                    })
                }).catch(() => { }); // silent fail — localStorage is still the source of truth
            }
        };

        window.updateWishlistUI = function () {
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
                                <p class="text-[10px] text-primary font-bold">${item.currency || '₹'}${Number(item.price).toLocaleString()}</p>
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
                                <p class="text-[10px] text-primary font-bold">${item.currency || '₹'}${Number(item.price).toLocaleString()}</p>
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
                return function (...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/confirm-interceptor.js') }}"></script>
    <style>
        .custom-swal-popup {
            border-radius: 24px !important;
            padding: 32px 24px !important;
            border: none !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
        }

        .custom-swal-confirm {
            background-color: #e85d26 !important;
            color: white !important;
            padding: 12px 32px !important;
            border-radius: 12px !important;
            font-weight: 800 !important;
            margin: 0 8px !important;
            border: none !important;
            cursor: pointer !important;
            font-size: 16px !important;
            transition: all 0.2s ease !important;
        }

        .custom-swal-confirm:hover {
            background-color: #d45020 !important;
            transform: translateY(-1px);
        }

        .custom-swal-cancel {
            background-color: white !important;
            color: #e85d26 !important;
            padding: 10px 32px !important;
            border-radius: 12px !important;
            font-weight: 800 !important;
            margin: 0 8px !important;
            border: 2px solid #e85d26 !important;
            cursor: pointer !important;
            font-size: 16px !important;
            transition: all 0.2s ease !important;
        }

        .custom-swal-cancel:hover {
            background-color: #fff7f5 !important;
        }

        .custom-swal-actions {
            margin-top: 32px !important;
            gap: 16px !important;
        }
    </style>
    <script>
        (function () {
            const originalConfirm = window.confirm;

            window.confirm = function (message) {
                // Find the event that triggered this
                const e = window.event;
                let target = null;

                if (e && e.target) {
                    target = e.target.closest('a, button, form');
                    if (e.type === 'submit') {
                        target = e.target;
                    }
                    // Try to stop default action if possible
                    if (e.preventDefault) e.preventDefault();
                    if (e.stopImmediatePropagation) e.stopImmediatePropagation();
                }

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: '<h2 style="font-size: 1.75rem; font-weight: 800; color: #1f2937; margin-bottom: 12px;">Are you sure?</h2>',
                        html: `<p style="font-size: 1rem; color: #6b7280; font-weight: 500;">${message || 'This action cannot be undone.'}</p>`,
                        showCancelButton: true,
                        confirmButtonText: 'Yes, proceed',
                        cancelButtonText: 'Cancel',
                        buttonsStyling: false,
                        customClass: {
                            popup: 'custom-swal-popup',
                            confirmButton: 'custom-swal-confirm',
                            cancelButton: 'custom-swal-cancel',
                            actions: 'custom-swal-actions'
                        },
                        width: '450px',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (target) {
                                // Handle links
                                if (target.tagName === 'A' && target.href && target.href !== 'javascript:void(0);') {
                                    window.location.href = target.href;
                                }
                                // Handle forms and submit buttons
                                else if (target.tagName === 'FORM' || (target.tagName === 'BUTTON' && target.type === 'submit')) {
                                    let form = target.tagName === 'FORM' ? target : target.closest('form');
                                    if (form) {
                                        // Remove onsubmit to prevent loop if it uses confirm
                                        form.removeAttribute('onsubmit');
                                        form.submit();
                                    }
                                } else {
                                    console.warn('Unhandled target for confirm:', target);
                                }
                            }
                        }
                    });
                } else {
                    // Fallback if Swal not loaded
                    return originalConfirm(message);
                }

                // Return false to prevent the default synchronous execution
                return false;
            };
        })();
    </script>

    {{-- Global Lightbox / Gallery Modal (White Paged Masonry Gallery) --}}
    <div x-show="globalShowGallery" x-cloak class="fixed inset-0 overflow-y-auto"
        style="z-index: 2147483647 !important; background-color: #ffffff !important;"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-250"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4"
        @keydown.escape.window="if(globalLightboxOpen) { globalLightboxOpen = false; } else { globalShowGallery = false; }">

        {{-- Close Button & Top Bar --}}
        <div
            class="sticky top-0 bg-white/95 backdrop-blur-md border-b border-gray-150 px-6 py-4 flex items-center justify-between z-50 shadow-sm">
            <div>
                <span class="text-[10px] font-black text-orange-500 uppercase tracking-widest"
                    style="letter-spacing: 0.1em;">Photo Gallery</span>
                <h3 class="font-black text-gray-900 text-lg md:text-xl leading-tight mt-0.5"
                    style="font-family: 'Poppins', sans-serif;" x-text="globalGalleryTitle"></h3>
            </div>
            <button @click="globalShowGallery = false"
                class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Gallery Grid Content --}}
        <div class="container mx-auto px-6 py-8 max-w-7xl">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                <template x-for="(slide, idx) in globalSlides" :key="idx">
                    <div @click="openLightbox(idx)"
                        class="relative aspect-[4/3] rounded-2xl overflow-hidden group shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-150 bg-gray-50 cursor-pointer">
                        <img :src="slide"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            :alt="'Gallery Photo ' + (idx + 1)">
                        <div
                            class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 drop-shadow-md"
                                fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                            </svg>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Fullscreen Lightbox Slider --}}
        <div x-show="globalLightboxOpen" x-cloak class="fixed inset-0 flex items-center justify-center"
            style="z-index: 2147483647 !important; background-color: #ffffff !important;"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @keydown.right.window="if(globalLightboxOpen) nextImage()"
            @keydown.left.window="if(globalLightboxOpen) prevImage()">

            {{-- Lightbox Close Button --}}
            <button @click.stop="globalLightboxOpen = false"
                class="absolute top-6 right-6 text-gray-500 hover:text-gray-900 transition-colors bg-gray-100 hover:bg-gray-200 rounded-full p-2 border border-gray-200 shadow-sm"
                style="z-index: 2147483647 !important;">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Prev Button --}}
            <button @click.stop="prevImage()"
                class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-900 transition-colors bg-gray-100 hover:bg-gray-200 rounded-full p-3 md:p-4 border border-gray-200 shadow-sm"
                style="z-index: 2147483647 !important;">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            {{-- Next Button --}}
            <button @click.stop="nextImage()"
                class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-900 transition-colors bg-gray-100 hover:bg-gray-200 rounded-full p-3 md:p-4 border border-gray-200 shadow-sm"
                style="z-index: 2147483647 !important;">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            {{-- Main Image Display --}}
            <div class="relative max-w-5xl max-h-[85vh] w-full px-4 md:px-16 flex items-center justify-center">
                <template x-for="(slide, idx) in globalSlides" :key="'lightbox-'+idx">
                    <img x-show="globalLightboxIndex === idx" :src="slide"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl"
                        :alt="'Fullscreen Image ' + (idx + 1)">
                </template>
            </div>

            {{-- Image Counter --}}
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-gray-700 font-bold tracking-wide text-sm bg-gray-100 border border-gray-200 shadow-sm px-4 py-1.5 rounded-full"
                style="z-index: 2147483647 !important;">
                <span x-text="globalLightboxIndex + 1"></span> / <span x-text="globalSlides.length"></span>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <button 
        x-show="isScrolled" 
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        @click="window.scrollTo({top: 0, behavior: 'smooth'})"
        class="fixed bottom-6 right-6 z-50 w-12 h-12 flex items-center justify-center rounded-full bg-orange-50 border border-orange-200 text-primary shadow-lg hover:bg-orange-100 hover:-translate-y-1 transition-all duration-300 group"
        aria-label="Scroll to top"
    >
        <i data-lucide="chevrons-up" class="w-6 h-6 group-hover:animate-bounce"></i>
    </button>

    @stack('scripts')
    <script>

        document.addEventListener('DOMContentLoaded', () => {
            const links = document.querySelectorAll('a[href]');
            links.forEach(link => {
                const href = link.getAttribute('href');
                if (href && (href.includes('/package') || href.includes('/discover'))) {
                    link.setAttribute('target', '_blank');
                }
            });
        });
    </script>
</body>

</html>