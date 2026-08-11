<aside id="sidebar" class="fixed left-0 top-0 h-screen w-56 bg-white border-r border-gray-100 transition-all duration-300 z-50 overflow-y-auto scrollbar-none flex flex-col justify-between -translate-x-full lg:translate-x-0">
    <div class="px-4 pt-4">
        <div class="flex items-center justify-between mb-4">
            <div class="flex justify-center w-full">
                <x-logo class="h-8 w-auto text-foreground" />
            </div>
            <button onclick="closeSidebar()" class="lg:hidden w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-700 rounded-xl hover:bg-gray-100 transition-all">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>

        <nav class="space-y-0.5">
            <p class="text-[9px] font-bold text-gray-400 uppercase px-2 mb-2 tracking-wider">Main Menu</p>
            
            <a href="{{ route('agent.dashboard') }}" class="flex items-center px-2.5 py-1.5 rounded-xl transition-all group {{ request()->routeIs('agent.dashboard') ? 'bg-orange-50 text-[#e85d26] font-bold' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2 transition-all {{ request()->routeIs('agent.dashboard') ? 'bg-[#e85d26] text-white' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <i class="fas fa-home text-xs"></i>
                </div>
                <span class="text-xs">Dashboard</span>
            </a>

            <a href="{{ route('agent.my-packages') }}" class="flex items-center px-2.5 py-1.5 rounded-xl transition-all group {{ request()->routeIs('agent.my-packages') ? 'bg-orange-50 text-[#e85d26] font-bold' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2 transition-all {{ request()->routeIs('agent.my-packages') ? 'bg-[#e85d26] text-white' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <i class="fas fa-box text-xs"></i>
                </div>
                <span class="text-xs">My Packages</span>
            </a>

            <a href="{{ route('agent.hotels') }}" class="flex items-center px-2.5 py-1.5 rounded-xl transition-all group {{ request()->routeIs('agent.hotels') ? 'bg-orange-50 text-[#e85d26] font-bold' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2 transition-all {{ request()->routeIs('agent.hotels') ? 'bg-[#e85d26] text-white' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <i class="fas fa-hotel text-xs"></i>
                </div>
                <span class="text-xs">Add Hotel Name</span>
            </a>

            <a href="{{ route('agent.leads') }}" class="flex items-center px-2.5 py-1.5 rounded-xl transition-all group {{ request()->routeIs('agent.leads') ? 'bg-orange-50 text-[#e85d26] font-bold' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2 transition-all {{ request()->routeIs('agent.leads') ? 'bg-[#e85d26] text-white' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <i class="fas fa-bullhorn text-xs"></i>
                </div>
                <span class="text-xs">Lead</span>
            </a>

            <a href="{{ route('agent.invoice') }}" class="flex items-center px-2.5 py-1.5 rounded-xl transition-all group {{ request()->routeIs('agent.invoice') ? 'bg-orange-50 text-[#e85d26] font-bold' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2 transition-all {{ request()->routeIs('agent.invoice') ? 'bg-[#e85d26] text-white' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <i class="fas fa-file-invoice-dollar text-xs"></i>
                </div>
                <span class="text-xs">Invoice</span>
            </a>

            <a href="{{ route('agent.payment') }}" class="flex items-center px-2.5 py-1.5 rounded-xl transition-all group {{ request()->routeIs('agent.payment') ? 'bg-orange-50 text-[#e85d26] font-bold' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2 transition-all {{ request()->routeIs('agent.payment') ? 'bg-[#e85d26] text-white' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <i class="fas fa-credit-card text-xs"></i>
                </div>
                <span class="text-xs">Billing</span>
            </a>

            <a href="{{ route('agent.branch') }}" class="flex items-center px-2.5 py-1.5 rounded-xl transition-all group {{ request()->routeIs('agent.branch') ? 'bg-orange-50 text-[#e85d26] font-bold' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2 transition-all {{ request()->routeIs('agent.branch') ? 'bg-[#e85d26] text-white' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <i class="fas fa-code-branch text-xs"></i>
                </div>
                <span class="text-xs">Add Branch</span>
            </a>

            <a href="{{ route('agent.gallery') }}" class="flex items-center px-2.5 py-1.5 rounded-xl transition-all group {{ request()->routeIs('agent.gallery') ? 'bg-orange-50 text-[#e85d26] font-bold' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2 transition-all {{ request()->routeIs('agent.gallery') ? 'bg-[#e85d26] text-white' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <i class="fas fa-images text-xs"></i>
                </div>
                <span class="text-xs">Gallery/Images</span>
            </a>

            <a href="{{ route('agent.feedback') }}" class="flex items-center px-2.5 py-1.5 rounded-xl transition-all group {{ request()->routeIs('agent.feedback') ? 'bg-orange-50 text-[#e85d26] font-bold' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2 transition-all {{ request()->routeIs('agent.feedback') ? 'bg-[#e85d26] text-white' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <i class="fas fa-comments text-xs"></i>
                </div>
                <span class="text-xs">Feedbacks</span>
            </a>

            <a href="{{ route('agent.services') }}" class="flex items-center px-2.5 py-1.5 rounded-xl transition-all group {{ request()->routeIs('agent.services') ? 'bg-orange-50 text-[#e85d26] font-bold' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2 transition-all {{ request()->routeIs('agent.services') ? 'bg-[#e85d26] text-white' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <i class="fas fa-tools text-xs"></i>
                </div>
                <span class="text-xs">Service</span>
            </a>

            <p class="text-[9px] font-bold text-gray-400 uppercase px-2 mt-4 mb-2 tracking-wider">Account Related</p>

            <a href="{{ route('agent.about') }}" class="flex items-center px-2.5 py-1.5 rounded-xl transition-all group {{ request()->routeIs('agent.about') ? 'bg-orange-50 text-[#e85d26] font-bold' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2 transition-all {{ request()->routeIs('agent.about') ? 'bg-[#e85d26] text-white' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <i class="fas fa-info-circle text-xs"></i>
                </div>
                <span class="text-xs">About Tour Raja</span>
            </a>

            <a href="{{ route('agent.profile-images') }}" class="flex items-center px-2.5 py-1.5 rounded-xl transition-all group {{ request()->routeIs('agent.profile-images') ? 'bg-orange-50 text-[#e85d26] font-bold' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2 transition-all {{ request()->routeIs('agent.profile-images') ? 'bg-[#e85d26] text-white' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <i class="fas fa-portrait text-xs"></i>
                </div>
                <span class="text-xs">Profile Images</span>
            </a>

            <a href="{{ route('agent.settings') }}" class="flex items-center px-2.5 py-1.5 rounded-xl transition-all group {{ request()->routeIs('agent.settings') ? 'bg-orange-50 text-[#e85d26] font-bold' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center mr-2 transition-all {{ request()->routeIs('agent.settings') ? 'bg-[#e85d26] text-white' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <i class="fas fa-cog text-xs"></i>
                </div>
                <span class="text-xs">Settings</span>
            </a>

            <a href="{{ route('agent.logout') }}" class="flex items-center px-2.5 py-1.5 rounded-xl text-gray-500 hover:text-red-500 hover:bg-red-50 transition-all group">
                <div class="w-6 h-6 flex items-center justify-center mr-2">
                    <i class="fas fa-sign-out-alt text-xs"></i>
                </div>
                <span class="font-medium text-xs">Log Out</span>
            </a>
        </nav>
    </div>

    <!-- Sidebar Help Card Removed -->
</aside>

<!-- Mobile Overlay Backdrop -->
<div id="sidebarBackdrop" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-40 hidden" onclick="closeSidebar()"></div>
