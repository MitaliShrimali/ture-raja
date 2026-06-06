<aside id="sidebar" class="fixed left-0 top-0 h-screen w-72 bg-white border-r border-gray-100 transition-all duration-300 z-50 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-200 -translate-x-full lg:translate-x-0">
    <div class="p-6">
        <div class="flex items-center justify-between mb-10 px-2">
            <div class="mb-6">
                <img src="{{ asset('agent/assets/images/logo.svg') }}" alt="TourRaja Logo" class="w-[420px] h-auto object-contain drop-shadow-2xl">
            </div>
            <button onclick="closeSidebar()" class="lg:hidden w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-700 rounded-xl hover:bg-gray-100 transition-all">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <nav class="space-y-1">
            <p class="text-[10px] font-bold text-gray-400 uppercase px-4 mb-4 tracking-widest">Main Menu</p>
            
            <a href="{{ route('agent.dashboard') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('agent.dashboard') ? 'bg-white text-primary font-bold shadow-sm shadow-orange-100' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-all {{ request()->routeIs('agent.dashboard') ? 'bg-primary text-white shadow-md shadow-orange-200' : '' }}">
                    <i class="fas fa-home text-sm"></i>
                </div>
                <span class="text-sm">Dashboard</span>
            </a>

            <a href="{{ route('agent.my-packages') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('agent.my-packages') ? 'bg-white text-primary font-bold shadow-sm shadow-orange-100' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-all {{ request()->routeIs('agent.my-packages') ? 'bg-primary text-white shadow-md shadow-orange-200' : '' }}">
                    <i class="fas fa-box text-sm"></i>
                </div>
                <span class="text-sm">My Packages</span>
            </a>

            <a href="{{ route('agent.hotels') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('agent.hotels') ? 'bg-white text-primary font-bold shadow-sm shadow-orange-100' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-all {{ request()->routeIs('agent.hotels') ? 'bg-primary text-white shadow-md shadow-orange-200' : '' }}">
                    <i class="fas fa-hotel text-sm"></i>
                </div>
                <span class="text-sm">Add Hotel Name</span>
            </a>

            <a href="{{ route('agent.leads') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('agent.leads') ? 'bg-white text-primary font-bold shadow-sm shadow-orange-100' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-all {{ request()->routeIs('agent.leads') ? 'bg-primary text-white shadow-md shadow-orange-200' : '' }}">
                    <i class="fas fa-bullhorn text-sm"></i>
                </div>
                <span class="text-sm">Lead</span>
            </a>

            <a href="{{ route('agent.invoice') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('agent.invoice') ? 'bg-white text-primary font-bold shadow-sm shadow-orange-100' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-all {{ request()->routeIs('agent.invoice') ? 'bg-primary text-white shadow-md shadow-orange-200' : '' }}">
                    <i class="fas fa-file-invoice-dollar text-sm"></i>
                </div>
                <span class="text-sm">Invoice</span>
            </a>

            <a href="{{ route('agent.payment') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('agent.payment') ? 'bg-white text-primary font-bold shadow-sm shadow-orange-100' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-all {{ request()->routeIs('agent.payment') ? 'bg-primary text-white shadow-md shadow-orange-200' : '' }}">
                    <i class="fas fa-credit-card text-sm"></i>
                </div>
                <span class="text-sm">Payment</span>
            </a>

            <a href="{{ route('agent.branch') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('agent.branch') ? 'bg-white text-primary font-bold shadow-sm shadow-orange-100' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-all {{ request()->routeIs('agent.branch') ? 'bg-primary text-white shadow-md shadow-orange-200' : '' }}">
                    <i class="fas fa-code-branch text-sm"></i>
                </div>
                <span class="text-sm">Add Branch</span>
            </a>

            <a href="{{ route('agent.gallery') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('agent.gallery') ? 'bg-white text-primary font-bold shadow-sm shadow-orange-100' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-all {{ request()->routeIs('agent.gallery') ? 'bg-primary text-white shadow-md shadow-orange-200' : '' }}">
                    <i class="fas fa-images text-sm"></i>
                </div>
                <span class="text-sm">Gallery/Images</span>
            </a>

            <a href="{{ route('agent.feedback') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('agent.feedback') ? 'bg-white text-primary font-bold shadow-sm shadow-orange-100' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-all {{ request()->routeIs('agent.feedback') ? 'bg-primary text-white shadow-md shadow-orange-200' : '' }}">
                    <i class="fas fa-comments text-sm"></i>
                </div>
                <span class="text-sm">Feedbacks</span>
            </a>

            <a href="{{ route('agent.about') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('agent.about') ? 'bg-white text-primary font-bold shadow-sm shadow-orange-100' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-all {{ request()->routeIs('agent.about') ? 'bg-primary text-white shadow-md shadow-orange-200' : '' }}">
                    <i class="fas fa-info-circle text-sm"></i>
                </div>
                <span class="text-sm">About TourRaja</span>
            </a>

            <p class="text-[10px] font-bold text-gray-400 uppercase px-4 mt-8 mb-4 tracking-widest">Account Related</p>

            <a href="{{ route('agent.profile') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('agent.profile') ? 'bg-white text-primary font-bold shadow-sm shadow-orange-100' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-all {{ request()->routeIs('agent.profile') ? 'bg-primary text-white shadow-md shadow-orange-200' : '' }}">
                    <i class="fas fa-user-circle text-sm"></i>
                </div>
                <span class="text-sm">Profile</span>
            </a>

            <a href="{{ route('agent.services') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('agent.services') ? 'bg-white text-primary font-bold shadow-sm shadow-orange-100' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-all {{ request()->routeIs('agent.services') ? 'bg-primary text-white shadow-md shadow-orange-200' : '' }}">
                    <i class="fas fa-tools text-sm"></i>
                </div>
                <span class="text-sm">Service</span>
            </a>

            <a href="{{ route('agent.settings') }}" class="flex items-center px-4 py-3 rounded-2xl transition-all group {{ request()->routeIs('agent.settings') ? 'bg-white text-primary font-bold shadow-sm shadow-orange-100' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-all {{ request()->routeIs('agent.settings') ? 'bg-primary text-white shadow-md shadow-orange-200' : '' }}">
                    <i class="fas fa-cog text-sm"></i>
                </div>
                <span class="text-sm">Settings</span>
            </a>

            <a href="{{ route('agent.login') }}" class="flex items-center px-4 py-3 rounded-2xl text-gray-400 hover:text-red-500 hover:bg-red-50 transition-all group">
                <div class="w-8 h-8 flex items-center justify-center mr-3">
                    <i class="fas fa-sign-out-alt text-sm"></i>
                </div>
                <span class="font-medium text-sm">Log Out</span>
            </a>
        </nav>

        <!-- Sidebar Help Card -->
        <div class="mt-10 p-4 bg-gradient-to-br from-primary to-orange-400 rounded-3xl relative overflow-hidden">
            <div class="absolute -top-4 -right-4 w-16 h-16 bg-white/10 rounded-full"></div>
            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-white mb-3">
                <i class="fas fa-question text-xs"></i>
            </div>
            <p class="text-white font-bold text-xs mb-1">Need help?</p>
            <p class="text-white/80 text-[10px] mb-3 leading-tight">Please check guideline</p>
            <button class="w-full bg-white text-primary text-[10px] font-bold py-2 rounded-xl">Click Here</button>
        </div>
    </div>
</aside>

<!-- Mobile Overlay Backdrop -->
<div id="sidebarBackdrop" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-40 hidden" onclick="closeSidebar()"></div>
