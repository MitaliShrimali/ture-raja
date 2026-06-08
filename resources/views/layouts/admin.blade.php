<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Admin Dashboard - Tour Raja</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-background text-foreground font-body h-full overflow-x-hidden antialiased" x-data="{ 
        sidebarOpen: false, 
        sidebarDropdowns: {},
        showAddModal: false, 
        showEditModal: false, 
        addPreviewUrl: '', 
        editPreviewUrl: '', 
        editPkg: { id: '', title: '', location: '', price: '', old_price: '', rating: '4.8', reviews: '10', duration: '', group_size: '4-6 guest', image: '', stock: '', status: '', category: '', badge: '', brochure: '', included: [], excluded: [] } 
    }">
    <!-- Root Layout Wrapper -->
    <div class="flex h-full overflow-hidden w-screen max-w-full relative">
        
        <!-- Sidebar Backdrop (Mobile) -->
        <template x-if="sidebarOpen">
            <div 
                @click="sidebarOpen = false"
                class="fixed inset-0 bg-black/60 z-[60] backdrop-blur-sm lg:hidden transition-opacity duration-300"
                x-transition:enter="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="opacity-100"
                x-transition:leave-end="opacity-0"
            ></div>
        </template>
 
        <!-- Sidebar -->
        <aside 
            :class="{
                'translate-x-0 w-72 md:w-80': sidebarOpen,
                '-translate-x-full lg:translate-x-0 lg:w-72 xl:w-80': !sidebarOpen
            }" 
            class="bg-white border-r border-border-soft flex flex-col fixed inset-y-0 left-0 z-[70] transition-all duration-300 transform lg:static shadow-xl lg:shadow-none h-full"
        >
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
            <nav id="sidebar-nav" class="flex-1 overflow-y-auto px-4 py-6 space-y-8 hide-scrollbar">
                @php
                    $menuGroups = [
                        [
                            'label' => 'ADMIN CENTRAL',
                            'items' => [
                                ['name' => 'Global Dashboard', 'icon' => 'layout-dashboard', 'href' => '/admin/dashboard'],
                                ['name' => 'Admin User', 'icon' => 'user-round', 'href' => '/admin/users'],
                                ['name' => 'Customers', 'icon' => 'user', 'href' => '/admin/customers'],
                                ['name' => 'Agent Management', 'icon' => 'users', 'href' => '/admin/agents'],
                                ['name' => 'Registered Agents', 'icon' => 'users-round', 'href' => '/admin/registered-agents'],
                                ['name' => 'Lead Management', 'icon' => 'target', 'href' => '/admin/leads'],
                            ]
                        ],
                        [
                            'label' => 'INVENTORY & STAYS',
                            'items' => [
                                ['name' => 'Hotel Management', 'icon' => 'building-2', 'href' => '/admin/hotels'],
                                ['name' => 'Amenities', 'icon' => 'clipboard-list', 'href' => '/admin/amenities'],
                                [
                                    'name' => 'Tour Packages', 
                                    'icon' => 'package', 
                                    'href' => '/admin/packages',
                                    'children' => [
                                        ['name' => 'All Packages', 'href' => '/admin/packages'],
                                        ['name' => 'Add New Package', 'href' => '/admin/packages/create'],
                                        ['name' => 'International Packages', 'href' => '/admin/packages/international'],
                                        ['name' => 'Domestic Packages', 'href' => '/admin/packages/domestic'],
                                    ]
                                ],
                                ['name' => 'Holiday Types', 'icon' => 'layout', 'href' => '/admin/holiday-types'],
                                ['name' => 'Activities', 'icon' => 'target', 'href' => '/admin/activities'],
                            ]
                        ],
                        [
                            'label' => 'SUBSCRIPTION OVERSIGHT',
                            'items' => [
                                [
                                    'name' => 'Paid User', 
                                    'icon' => 'user-plus', 
                                    'href' => '/admin/paid-users',
                                    'children' => [
                                        ['name' => 'All Paid Users', 'href' => '/admin/paid-users'],
                                        ['name' => 'Add Paid User', 'href' => '/admin/paid-users/create'],
                                    ]
                                ],
                                ['name' => 'User Plan', 'icon' => 'clipboard-list', 'href' => '/admin/user-plans'],
                                ['name' => 'Payment', 'icon' => 'credit-card', 'href' => '/admin/payments'],
                                ['name' => 'Advertisement', 'icon' => 'megaphone', 'href' => '/admin/ads'],
                                ['name' => 'Plan', 'icon' => 'clipboard-list', 'href' => '/admin/plans'],
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
                        ]
                    ];
                @endphp

                @foreach($menuGroups as $group)
                    <div class="space-y-3">
                        <p class="px-4 text-[10px] font-black text-text-muted uppercase tracking-[0.2em] opacity-60">
                            {{ $group['label'] }}
                        </p>
                        <div class="space-y-1">
                            @foreach($group['items'] as $item)
                                @if(isset($item['children']))
                                    @php
                                        $hasActiveChild = false;
                                        foreach($item['children'] as $child) {
                                            if (request()->is(ltrim($child['href'], '/'))) {
                                                $hasActiveChild = true;
                                            }
                                        }
                                    @endphp
                                    <div x-data="{ open: {{ $hasActiveChild ? 'true' : 'false' }} }" class="space-y-1">
                                        <button 
                                            @click="open = !open" 
                                            class="w-full flex items-center justify-between px-4 py-3.5 rounded-2xl transition-all duration-300 {{ $hasActiveChild ? 'bg-primary/5 text-primary' : 'text-text-muted hover:bg-gray-50 hover:text-foreground' }}"
                                        >
                                            <div class="flex items-center gap-4">
                                                <i data-lucide="{{ $item['icon'] }}" size="20"></i>
                                                <span class="text-sm font-black tracking-tight">{{ $item['name'] }}</span>
                                            </div>
                                            <i data-lucide="chevron-down" size="16" class="transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                                        </button>
                                        <div x-show="open" x-collapse class="pl-12 space-y-1 pt-1" style="display: none;">
                                            @foreach($item['children'] as $child)
                                                @php
                                                    $isChildActive = request()->is(ltrim($child['href'], '/'));
                                                @endphp
                                                <a 
                                                    href="{{ url($child['href']) }}" 
                                                    class="block py-2.5 px-4 rounded-xl text-xs font-bold transition-all {{ $isChildActive ? 'text-primary bg-primary/5 font-black border-l-2 border-primary pl-3' : 'text-text-muted hover:text-foreground hover:bg-gray-50/50' }}"
                                                >
                                                    {{ $child['name'] }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    @php
                                        $isActive = request()->is(ltrim($item['href'], '/')) || request()->is(ltrim($item['href'], '/') . '/*');
                                    @endphp
                                    <a 
                                        href="{{ url($item['href']) }}" 
                                        class="group flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-300 {{ $isActive ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-text-muted hover:bg-gray-50 hover:text-foreground' }}"
                                    >
                                        <i data-lucide="{{ $item['icon'] }}" size="20"></i>
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
                <a href="{{ url('/logout') }}" class="flex items-center gap-4 px-4 py-3.5 text-red-500 hover:bg-red-50 rounded-2xl transition-all text-sm font-black">
                    <i data-lucide="log-out" size="20"></i>
                    <span>Exit Admin</span>
                </a>
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="flex-1 flex flex-col h-full min-w-0 overflow-hidden relative">
            <!-- Header -->
            <header class="h-24 bg-white/90 backdrop-blur-md border-b border-border-soft flex items-center justify-between px-6 lg:px-10 sticky top-0 z-50 shrink-0">
                <div class="flex items-center gap-4">
                    <!-- Mobile Menu Button -->
                    <button @click="sidebarOpen = true" class="p-2.5 hover:bg-gray-50 rounded-xl text-text-muted lg:hidden">
                        <i data-lucide="menu" size="24"></i>
                    </button>
                    
                    <div class="hidden sm:flex flex-col">
                        <p class="text-[10px] font-black text-text-muted uppercase tracking-[0.2em]">Platform Admin</p>
                        <h2 class="text-xl font-black text-foreground tracking-tight">@yield('admin_title', 'Dashboard')</h2>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    
                    <!-- Session Notifications -->
                    @if(session('success') || session('error'))
                        <div 
                            x-data="{ show: true }" 
                            x-init="setTimeout(() => show = false, 4000)" 
                            x-show="show" 
                            x-transition:enter="transition ease-out duration-300 transform"
                            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed top-8 right-8 z-[100] max-w-sm w-full bg-white rounded-3xl shadow-premium border {{ session('success') ? 'border-green-100' : 'border-red-100' }} p-6 flex items-start gap-4"
                        >
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 {{ session('success') ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-500' }}">
                                <i data-lucide="{{ session('success') ? 'check-circle-2' : 'alert-circle' }}" size="20"></i>
                            </div>
                            <div class="flex-1 space-y-1">
                                <h4 class="text-sm font-black text-foreground">{{ session('success') ? 'Action Successful' : 'Action Failed' }}</h4>
                                <p class="text-xs text-muted-text font-medium leading-relaxed">{{ session('success') ?? session('error') }}</p>
                            </div>
                            <button @click="show = false" class="text-muted-text/40 hover:text-muted-text">
                                <i data-lucide="x" size="16"></i>
                            </button>
                        </div>
                    @endif

                    <!-- Pill Container for Search, Icons, and Profile -->
                    <div class="flex items-center bg-white rounded-full shadow-[0_2px_15px_-3px_rgba(0,0,0,0.05),0_10px_20px_-2px_rgba(0,0,0,0.02)] p-1.5 pl-3 gap-3 md:gap-5 border border-gray-50">
                        
                        <!-- Search -->
                        <div class="flex items-center bg-white border border-gray-200 rounded-full w-24 sm:w-32 md:w-56 overflow-hidden focus-within:ring-1 focus-within:ring-primary/20 focus-within:border-primary transition-all">
                            <div class="pl-3 flex items-center justify-center text-gray-400 pointer-events-none">
                                <i data-lucide="search" size="14"></i>
                            </div>
                            <input 
                                type="text" 
                                placeholder="Type" 
                                class="w-full bg-transparent border-none py-1.5 pl-2 pr-3 text-sm font-semibold text-gray-700 placeholder:text-gray-400 focus:outline-none focus:ring-0"
                            >
                        </div>

                        <!-- Icons -->
                        <div class="flex items-center gap-1 md:gap-2">
                            <a href="{{ url('/admin/notifications') }}" class="relative p-2 text-gray-400 hover:text-primary hover:bg-gray-50 rounded-full transition-colors">
                                <i data-lucide="bell" size="20"></i>
                            </a>
                            <a href="{{ url('/admin/settings') }}" class="p-2 text-gray-400 hover:text-primary hover:bg-gray-50 rounded-full transition-colors">
                                <i data-lucide="settings" size="20"></i>
                            </a>
                            <button type="button" class="p-2 text-gray-400 hover:text-primary hover:bg-gray-50 rounded-full transition-colors">
                                <i data-lucide="info" size="20"></i>
                            </button>
                        </div>

                        <!-- User Profile Avatar -->
                        @php
                            $activeAdmin = Auth::check() ? Auth::user() : (\DB::table('users')->where('id', 1)->first() ?? (object)[
                                'name' => 'Super Admin',
                                'role' => 'SUPER ADMIN',
                                'avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=Admin'
                            ]);
                            $adminAvatar = ($activeAdmin && !empty($activeAdmin->avatar)) ? $activeAdmin->avatar : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($activeAdmin->name ?? 'Admin');
                        @endphp
                        <a href="{{ url('/admin/settings') }}" class="block shrink-0">
                            <img src="{{ $adminAvatar }}" alt="Profile" class="w-10 h-10 rounded-full object-cover border border-gray-100 hover:ring-2 hover:ring-primary/20 transition-all">
                        </a>
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
    <div 
        x-data="{ 
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
        x-show="show"
        x-transition
        class="fixed bottom-6 right-6 z-[999] flex items-center gap-3 px-6 py-4 rounded-2xl shadow-premium border border-white/10 max-w-md"
        :class="type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'"
        style="display: none;"
    >
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
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.05); border-radius: 10px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: rgba(0,0,0,0.1); }
    </style>
</body>
</html>

