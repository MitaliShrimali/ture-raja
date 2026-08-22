<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ \Illuminate\Support\Facades\DB::table('settings')->where('key', 'favicon')->value('value') ?? asset('favicon.ico') }}">
    <title>Admin Dashboard - Tour Raja</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>

<body class="bg-background text-foreground font-body h-full overflow-hidden antialiased" x-data="{ 
        sidebarOpen: false, 
        sidebarDropdowns: {},
        showAddModal: false, 
        showEditModal: false, 
        addPreviewUrl: '', 
        editPreviewUrl: '', 
        editPkg: { id: '', title: '', location: '', price: '', old_price: '', rating: '4.8', reviews: '10', duration: '', group_size: '4-6 guest', image: '', stock: '', status: '', category: '', badge: '', brochure: '', included: [], excluded: [] },
        editHotel: { id: '', name: '', category: '', location: '', rating: '', status: '' },
        editItem: { id: '', name: '', icon: '', category: '', status: '' },
        editCategory: { id: '', name: '', description: '', icon: 'bed' },
        addIcon: 'bed',
        addAmenityIcon: 'waves'
    }">
    <!-- Root Layout Wrapper -->
    <div class="flex h-full overflow-hidden w-screen max-w-full relative">

        <!-- Sidebar Backdrop (Mobile) -->
        <template x-if="sidebarOpen">
            <div @click="sidebarOpen = false"
                class="fixed inset-0 bg-black/60 z-[60] backdrop-blur-sm lg:hidden transition-opacity duration-300"
                x-transition:enter="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="opacity-100"
                x-transition:leave-end="opacity-0"></div>
        </template>

        <!-- Sidebar -->
        <aside :class="{
                'translate-x-0 w-72 md:w-80': sidebarOpen,
                '-translate-x-full lg:translate-x-0 lg:w-72 xl:w-80': !sidebarOpen
            }"
            class="bg-white border-r border-border-soft flex flex-col fixed inset-y-0 left-0 z-[70] transition-all duration-300 transform lg:static shadow-xl lg:shadow-none h-full">
            <!-- Logo Area -->
            <div class="h-24 px-8 flex items-center justify-between border-b border-border-soft shrink-0">
                <div class="flex items-center">
                    <x-logo class="h-10 w-auto text-foreground" />
                </div>
                <!-- Close Button (Mobile) -->
                <button @click="sidebarOpen = false" class="p-2 hover:bg-gray-50 rounded-xl lg:hidden text-text-muted">
                    <i data-lucide="x" size="24"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <nav id="sidebar-nav" class="flex-1 overflow-y-auto px-3 py-3 space-y-2 hide-scrollbar">
                @php
                    $menuGroups = [
                        [
                            'label' => 'ADMIN CENTRAL',
                            'items' => [
                                ['name' => 'Dashboard', 'icon' => 'layout-dashboard', 'href' => '/admin/dashboard'],
                                ['name' => 'Admin User', 'icon' => 'user-round', 'href' => '/admin/users'],
                            ]
                        ],
                        [
                            'label' => 'AGENT MANAGEMENT',
                            'items' => [
                                ['name' => 'Paid User', 'icon' => 'user-plus', 'href' => '/admin/registered-agents'],
                                ['name' => 'Gallery', 'icon' => 'image', 'href' => '/admin/gallery'],
                                [
                                    'name' => 'Tour Packages',
                                    'icon' => 'package',
                                    'href' => '/admin/packages',
                                    'children' => [
                                        ['name' => 'All Packages', 'href' => '/admin/packages'],
                                        ['name' => 'Pending Approvals', 'href' => '/admin/packages/pending', 'badge_count' => true],
                                        ['name' => 'Add New Package', 'href' => '/admin/packages/create'],
                                        ['name' => 'International Packages', 'href' => '/admin/packages/international'],
                                        ['name' => 'Domestic Packages', 'href' => '/admin/packages/domestic'],
                                    ]
                                ],
                            ]
                        ],
                        [
                            'label' => 'SUBSCRIPTION OVERSIGHT',
                            'items' => [
                                ['name' => 'Advertisement', 'icon' => 'megaphone', 'href' => '/admin/ads'],
                                ['name' => 'Reviews', 'icon' => 'message-square', 'href' => '/admin/reviews'],
                                ['name' => 'Lead Management', 'icon' => 'target', 'href' => '/admin/leads'],
                                ['name' => 'Plan', 'icon' => 'clipboard-list', 'href' => '/admin/plans'],
                            ]
                        ],
                        [
                            'label' => 'FINANCIAL REPORTS',
                            'items' => [
                                ['name' => 'Payment', 'icon' => 'credit-card', 'href' => '/admin/payments'],
                                ['name' => 'Payment Pricing', 'icon' => 'tag', 'href' => '/admin/payment-pricing'],
                            ]
                        ],
                        [
                            'label' => 'PLATFORM SETTINGS',
                            'items' => [
                                [
                                    'name' => 'Home Page',
                                    'icon' => 'home',
                                    'href' => '/admin/home-editor',
                                    'children' => [
                                        ['name' => 'Home Editor', 'href' => '/admin/home-editor'],
                                        ['name' => 'Offer Stickers', 'href' => '/admin/offer-stickers'],
                                    ]
                                ],
                                ['name' => 'Notification', 'icon' => 'bell', 'href' => '/admin/notifications'],
                                ['name' => 'Pages', 'icon' => 'file-text', 'href' => '/admin/cms'],
                                ['name' => 'Contact US', 'icon' => 'message-square', 'href' => '/admin/contact'],
                                ['name' => 'Subscriber', 'icon' => 'users', 'href' => '/admin/subscribers'],
                                ['name' => 'Careers', 'icon' => 'briefcase', 'href' => '/admin/careers'],
                                ['name' => 'Settings', 'icon' => 'settings', 'href' => '/admin/settings'],
                            ]
                        ],
                    ];
                @endphp

                @foreach($menuGroups as $group)
                    <div class="">
                        <div class="px-3 pt-4 pb-2 font-black text-text-muted uppercase tracking-[0.25em] opacity-60"
                            style="font-size: 9px !important; letter-spacing: 0.25em !important; line-height: 1.2 !important;">
                            {{ $group['label'] }}
                        </div>
                        <div class="space-y-1">
                            @foreach($group['items'] as $item)
                                @if(isset($item['children']))
                                    @php
                                        $hasActiveChild = false;
                                        foreach ($item['children'] as $child) {
                                            if (request()->is(ltrim($child['href'], '/'))) {
                                                $hasActiveChild = true;
                                            }
                                        }
                                    @endphp
                                    <div x-data="{ open: {{ $hasActiveChild ? 'true' : 'false' }} }" class="space-y-0.5">
                                        <button @click="open = !open"
                                            class="w-full flex items-center justify-between px-3 py-2 rounded-xl transition-all duration-300 {{ $hasActiveChild ? 'bg-primary/5 text-primary' : 'text-text-muted hover:bg-gray-50 hover:text-foreground' }}">
                                            <div class="flex items-center gap-3">
                                                <i data-lucide="{{ $item['icon'] }}" size="18"></i>
                                                <span class="text-sm font-black tracking-tight">{{ $item['name'] }}</span>
                                            </div>
                                            <i data-lucide="chevron-down" size="14" class="transition-transform duration-300"
                                                :class="open ? 'rotate-180' : ''"></i>
                                        </button>
                                        <div x-show="open" x-collapse class="space-y-0.5 pt-0.5"
                                            style="display: none; padding-left: 2.2rem !important;">
                                            @foreach($item['children'] as $child)
                                                @php
                                                    $isChildActive = request()->is(ltrim($child['href'], '/'));
                                                    $childBadgeCount = null;
                                                    if (!empty($child['badge_count'])) {
                                                        try {
                                                            $childBadgeCount = DB::table('packages')->where('status', 'Pending')->count();
                                                        } catch (\Exception $e) {
                                                            $childBadgeCount = 0;
                                                        }
                                                    }
                                                @endphp
                                                <a href="{{ url($child['href']) }}"
                                                    class="flex items-center justify-between py-1.5 px-3 rounded-lg text-xs font-bold transition-all {{ $isChildActive ? 'text-primary bg-primary/5 font-black border-l-2 border-primary pl-2' : 'text-text-muted hover:text-foreground hover:bg-gray-50/50' }}">
                                                    <span>{{ $child['name'] }}</span>
                                                    @if($childBadgeCount !== null && $childBadgeCount > 0)
                                                        <span
                                                            class="ml-1 bg-orange-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full leading-none">{{ $childBadgeCount }}</span>
                                                    @endif
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    @php
                                        $isActive = request()->is(ltrim($item['href'], '/')) || request()->is(ltrim($item['href'], '/') . '/*');
                                        // Highlight Settings for all settings sub-pages
                                        if ($item['href'] === '/admin/settings') {
                                            $isActive = $isActive || request()->is('admin/settings/*');
                                        }
                                    @endphp
                                    <a href="{{ url($item['href']) }}"
                                        class="group flex items-center gap-3 px-3 py-2 rounded-xl transition-all duration-300 {{ $isActive ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-text-muted hover:bg-gray-50 hover:text-foreground' }}">
                                        <i data-lucide="{{ $item['icon'] }}" size="18"></i>
                                        <span class="text-sm font-black tracking-tight">{{ $item['name'] }}</span>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </nav>

            <!-- Bottom Actions -->
            <div class="p-6 border-t border-border-soft space-y-4 shrink-0">
                <a href="{{ url('/logout') }}"
                    class="flex items-center gap-4 px-4 py-3.5 text-red-500 hover:bg-red-50 rounded-2xl transition-all text-sm font-black">
                    <i data-lucide="log-out" size="20"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="flex-1 flex flex-col h-full min-w-0 overflow-hidden relative">
            <!-- Header -->
            <header
                class="h-24 bg-white/90 backdrop-blur-md border-b border-border-soft flex items-center justify-between px-6 lg:px-10 sticky top-0 z-50 shrink-0">
                <div class="flex items-center gap-4">
                    <!-- Mobile Menu Button -->
                    <button @click="sidebarOpen = true"
                        class="p-2.5 hover:bg-gray-50 rounded-xl text-text-muted lg:hidden">
                        <i data-lucide="menu" size="24"></i>
                    </button>

                    <div class="hidden sm:flex flex-col">
                        <h2 class="text-xl font-black text-foreground tracking-tight">@yield('admin_title', 'Dashboard')</h2>
                    </div>
                </div>

                <div class="flex items-center gap-4">

                    <!-- Session Notifications -->
                    @if(session('success') || session('error'))
                        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
                            x-transition:enter="transition ease-out duration-300 transform"
                            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed top-8 right-8 z-[100] max-w-sm w-full bg-white rounded-3xl shadow-premium border {{ session('success') ? 'border-green-100' : 'border-red-100' }} p-6 flex items-start gap-4">
                            <div
                                class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 {{ session('success') ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-500' }}">
                                <i data-lucide="{{ session('success') ? 'check-circle-2' : 'alert-circle' }}" size="20"></i>
                            </div>
                            <div class="flex-1 space-y-1">
                                <h4 class="text-sm font-black text-foreground">
                                    {{ session('success') ? 'Action Successful' : 'Action Failed' }}
                                </h4>
                                <p class="text-xs text-muted-text font-medium leading-relaxed">
                                    {{ session('success') ?? session('error') }}
                                </p>
                            </div>
                            <button @click="show = false" class="text-muted-text/40 hover:text-muted-text">
                                <i data-lucide="x" size="16"></i>
                            </button>
                        </div>
                    @endif

                    <!-- Pill Container for Search, Icons, and Profile -->
                    <div
                        class="flex items-center bg-white rounded-full shadow-[0_2px_15px_-3px_rgba(0,0,0,0.05),0_10px_20px_-2px_rgba(0,0,0,0.02)] p-1.5 pl-3 gap-3 md:gap-5 border border-gray-50">

                        <!-- Search -->
                        <div
                            class="relative flex items-center bg-white border border-gray-200 rounded-full w-24 sm:w-32 md:w-56 overflow-hidden focus-within:ring-1 focus-within:ring-primary/20 focus-within:border-primary transition-all">
                            <div class="pl-3 flex items-center justify-center text-gray-400 pointer-events-none">
                                <i data-lucide="search" size="14"></i>
                            </div>
                            <input type="text" id="globalSearchAdmin" placeholder="Search Pages" autocomplete="off"
                                class="w-full bg-transparent border-none py-1.5 pl-2 pr-3 text-sm font-semibold text-gray-700 placeholder:text-gray-400 focus:outline-none focus:ring-0">
                        </div>

                        <!-- Icons -->
                        <div class="flex items-center gap-1 md:gap-2">
                            <a href="#"
                                class="p-2 text-gray-400 hover:text-primary hover:bg-gray-50 rounded-full transition-colors hidden md:block">
                                <i data-lucide="info" size="20"></i>
                            </a>
                        </div>

                        <!-- User Profile Avatar -->
                        @php
                            $activeAdmin = Auth::guard('admin')->check() ? Auth::guard('admin')->user() : (\DB::table('users')->where('id', 1)->first() ?? (object) [
                                'name' => 'Super Admin',
                                'role' => 'SUPER ADMIN',
                                'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Admin'
                            ]);
                            $adminAvatar = ($activeAdmin && !empty($activeAdmin->avatar)) ? $activeAdmin->avatar : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($activeAdmin->name ?? 'Admin');
                        @endphp
                        <a href="{{ url('/admin/settings') }}" class="block shrink-0">
                            <img src="{{ asset($adminAvatar) }}" alt="Profile"
                                class="w-10 h-10 rounded-full object-cover border border-gray-100 hover:ring-2 hover:ring-primary/20 transition-all">
                        </a>
                    </div>

                    <!-- Search Dropdown Container -->
                    <div id="globalSearchAdminDropdown"
                        class="absolute top-20 right-6 lg:right-10 w-64 bg-white border border-gray-200 rounded-xl shadow-lg hidden z-[100] max-h-64 overflow-y-auto">
                        <ul id="globalSearchAdminResults" class="py-2 text-xs text-gray-700 divide-y divide-gray-50">
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content Area -->
            <div class="flex-1 overflow-y-auto overflow-x-hidden p-6 lg:p-10 bg-background custom-scroll">
                <div class="max-w-full mx-auto">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
    @stack('modals')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();

            const sidebarNav = document.getElementById('sidebar-nav');
            if (sidebarNav) {
                // Restore scroll position
                const savedScroll = sessionStorage.getItem('sidebarScrollPos');
                if (savedScroll !== null) {
                    sidebarNav.scrollTop = parseInt(savedScroll, 10);
                } else {
                    const activeItem = sidebarNav.querySelector('.bg-primary');
                    if (activeItem) {
                        activeItem.scrollIntoView({ block: 'center' });
                    }
                }

                // Save scroll position when it changes
                let scrollTimeout;
                sidebarNav.addEventListener('scroll', () => {
                    clearTimeout(scrollTimeout);
                    scrollTimeout = setTimeout(() => {
                        sessionStorage.setItem('sidebarScrollPos', sidebarNav.scrollTop);
                    }, 100);
                }, { passive: true });
            }
        });
    </script>
    <!-- Floating Toast Notifications -->
    <div x-data="{ 
            show: false, 
            message: '', 
            type: 'success',
            init() {
                @if(session('success'))
                    this.flash('{{ session('success') }}', 'success');
                @endif
                @if(session('error'))
                    this.flash('{{ session('error') }}', 'error');
                @endif
                @if($errors->any())
                    this.flash('{{ $errors->first() }}', 'error');
                @endif
            },
            flash(msg, type = 'success') {
                this.message = msg;
                this.type = type;
                this.show = true;
                setTimeout(() => this.show = false, 5000);
            }
        }"
        @notify.window="flash($event.detail.message, $event.detail.type)"
        x-show="show" x-transition
        class="fixed bottom-6 right-6 z-[999] flex items-center gap-3 px-6 py-4 rounded-2xl shadow-premium border border-white/10 max-w-md"
        :class="type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'" style="display: none;">
        <template x-if="type === 'success'">
            <i data-lucide="check-circle-2" class="w-5 h-5 shrink-0"></i>
        </template>
        <template x-if="type === 'error'">
            <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
        </template>
        <div class="text-xs font-black tracking-tight" x-text="message"></div>
        <button @click="show = false" class="ml-auto text-white/80 hover:text-white">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>

    <style>
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }

        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.1);
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
        document.addEventListener('alpine:init', function () {
            setTimeout(function () {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }, 100);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();

            const pages = [
                { name: 'Dashboard', url: '{{ url("/admin/dashboard") }}' },
                { name: 'Admin Users', url: '{{ url("/admin/users") }}' },
                { name: 'Paid Users', url: '{{ url("/admin/registered-agents") }}' },
                { name: 'All Packages', url: '{{ url("/admin/packages") }}' },
                { name: 'Pending Packages', url: '{{ url("/admin/packages/pending") }}' },
                { name: 'Add New Package', url: '{{ url("/admin/packages/create") }}' },
                { name: 'International Packages', url: '{{ url("/admin/packages/international") }}' },
                { name: 'Domestic Packages', url: '{{ url("/admin/packages/domestic") }}' },
                { name: 'Advertisements', url: '{{ url("/admin/ads") }}' },
                { name: 'Reviews', url: '{{ url("/admin/reviews") }}' },
                { name: 'Leads', url: '{{ url("/admin/leads") }}' },
                { name: 'Plans', url: '{{ url("/admin/plans") }}' },
                { name: 'Payments', url: '{{ url("/admin/payments") }}' },
                { name: 'Home Editor', url: '{{ url("/admin/home-editor") }}' },
                { name: 'Offer Stickers', url: '{{ url("/admin/offer-stickers") }}' },
                { name: 'Notifications', url: '{{ url("/admin/notifications") }}' },
                { name: 'Pages', url: '{{ url("/admin/cms") }}' },
                { name: 'Contact US', url: '{{ url("/admin/contact") }}' },
                { name: 'Subscribers', url: '{{ url("/admin/subscribers") }}' },
                { name: 'Careers', url: '{{ url("/admin/careers") }}' },
                { name: 'Settings', url: '{{ url("/admin/settings") }}' }
            ];

            const input = document.getElementById('globalSearchAdmin');
            const dropdown = document.getElementById('globalSearchAdminDropdown');
            const resultsContainer = document.getElementById('globalSearchAdminResults');

            if (input) {
                input.addEventListener('input', function () {
                    const val = this.value.toLowerCase();
                    resultsContainer.innerHTML = '';

                    if (!val) {
                        dropdown.classList.add('hidden');
                        return;
                    }

                    const matches = pages.filter(p => p.name.toLowerCase().includes(val));

                    if (matches.length > 0) {
                        matches.forEach(match => {
                            const li = document.createElement('li');
                            li.innerHTML = `<a href="${match.url}" class="block px-4 py-2 hover:bg-gray-100 text-gray-800 font-medium">${match.name}</a>`;
                            resultsContainer.appendChild(li);
                        });
                        dropdown.classList.remove('hidden');
                    } else {
                        dropdown.classList.remove('hidden');
                        resultsContainer.innerHTML = `<li class="px-4 py-2 text-gray-500 italic">No pages found</li>`;
                    }
                });

                document.addEventListener('click', function (e) {
                    if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    @stack('scripts')
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
    <!-- Country Code Selector & Validator Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            function initPhoneFields() {
                document.querySelectorAll('.phone-country-code').forEach(select => {
                    if (select.dataset.phoneInitialized) return;
                    select.dataset.phoneInitialized = "true";

                    const parent = select.closest('.flex') || select.parentElement;
                    const input = parent.querySelector('.phone-number-val');
                    const hidden = parent.querySelector('.phone-full-val');

                    // If hidden field has an initial value, pre-populate select & input
                    if (hidden && hidden.value) {
                        const val = hidden.value;
                        const options = Array.from(select.options);
                        // Sort options by code length descending to match longest code first
                        options.sort((a, b) => b.value.length - a.value.length);
                        for (let opt of options) {
                            if (val.startsWith(opt.value)) {
                                select.value = opt.value;
                                input.value = val.substring(opt.value.length);
                                break;
                            }
                        }
                    }

                    function validatePhone() {
                        const code = select.value;
                        const length = parseInt(select.options[select.selectedIndex].getAttribute('data-len') || '10');
                        let val = input.value.replace(/\D/g, ''); // Remove non-digits
                        
                        // Limit maximum characters
                        if (val.length > length) {
                            val = val.substring(0, length);
                        }
                        input.value = val;
                        
                        // Validation check
                        if (val.length > 0 && val.length !== length) {
                            input.setCustomValidity(`Phone number must be exactly ${length} digits.`);
                        } else {
                            input.setCustomValidity('');
                        }
                        
                        hidden.value = val ? (code + val) : '';
                    }

                    select.addEventListener('change', validatePhone);
                    input.addEventListener('input', validatePhone);
                    
                    // Initial check
                    validatePhone();
                });
            }

            initPhoneFields();
            // Re-run for dynamically loaded/inserted fields (e.g. modals, ajax)
            const observer = new MutationObserver(initPhoneFields);
            observer.observe(document.body, { childList: true, subtree: true });
        });
    </script>
    <x-admin-support-chatbot />

    <!-- Password Update Modal (Global) -->
    <div id="passwordModal" class="fixed inset-0 hidden items-center justify-center p-4" style="z-index: 999999;">
        <!-- Background overlay -->
        <div class="absolute inset-0 transition-opacity" onclick="closePasswordModal()" style="background-color: rgba(17, 24, 39, 0.6); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"></div>
        
        <!-- Modal panel -->
        <div class="relative bg-white rounded-[32px] shadow-2xl border border-gray-100 w-full max-w-md overflow-hidden transform transition-all duration-300 scale-100 flex flex-col p-8 z-10">
            
            <!-- Header with Key Icon & Close Button -->
            <div class="flex items-center justify-between pb-6 border-b border-gray-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#FFF5F2] text-[#B23B06] flex items-center justify-center shadow-inner">
                        <i data-lucide="key-round" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 leading-tight">Change Password</h3>
                        <p class="text-xs text-gray-400 font-medium mt-0.5">Secure your administrator account</p>
                    </div>
                </div>
                <button type="button" onclick="closePasswordModal()" class="w-8 h-8 rounded-full bg-gray-50 hover:bg-gray-100 text-gray-400 hover:text-gray-600 flex items-center justify-center transition-all focus:outline-none">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            
            <!-- Form Fields -->
            <form action="{{ url('admin/profile/change-password') }}" method="POST" class="space-y-5 pt-6">
                @csrf
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Current Password</label>
                    <div class="relative">
                        <input type="password" id="current_password" name="current_password" required placeholder="Enter current password" 
                            class="w-full bg-[#FBFBFA] border border-gray-100 text-gray-800 text-sm font-semibold rounded-2xl px-4 py-4 pr-12 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 focus:border-[#B23B06]/40 outline-none transition-all duration-200">
                        <button type="button" onclick="togglePwd('current_password', 'eye_current')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <i id="eye_current" data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">New Password</label>
                    <div class="relative">
                        <input type="password" id="new_password" name="new_password" required placeholder="Minimum 6 characters" 
                            class="w-full bg-[#FBFBFA] border border-gray-100 text-gray-800 text-sm font-semibold rounded-2xl px-4 py-4 pr-12 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 focus:border-[#B23B06]/40 outline-none transition-all duration-200">
                        <button type="button" onclick="togglePwd('new_password', 'eye_new')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <i id="eye_new" data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Confirm New Password</label>
                    <div class="relative">
                        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required placeholder="Re-type new password" 
                            class="w-full bg-[#FBFBFA] border border-gray-100 text-gray-800 text-sm font-semibold rounded-2xl px-4 py-4 pr-12 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 focus:border-[#B23B06]/40 outline-none transition-all duration-200">
                        <button type="button" onclick="togglePwd('new_password_confirmation', 'eye_confirm')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <i id="eye_confirm" data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="flex gap-3 pt-4 border-t border-gray-50 mt-6 justify-end">
                    <button type="button" onclick="closePasswordModal()" 
                        class="px-6 py-3.5 rounded-2xl bg-gray-50 hover:bg-gray-100 text-gray-600 text-sm font-bold transition-all focus:outline-none border border-gray-100">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-6 py-3.5 rounded-2xl bg-[#B23B06] hover:bg-[#902F04] text-white text-sm font-bold transition-all shadow-md shadow-[#B23B06]/15 hover:shadow-lg focus:outline-none">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openPasswordModal() {
            const modal = document.getElementById('passwordModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        }

        function closePasswordModal() {
            const modal = document.getElementById('passwordModal');
            if (modal) {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        }

        function togglePwd(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            if (!input || !icon) return;
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    </script>
</body>

</html>