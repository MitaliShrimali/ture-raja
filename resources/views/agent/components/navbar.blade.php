<nav class="flex items-center justify-between p-2 sm:p-4 mb-4 sm:mb-6">
    <div class="flex items-center gap-2 sm:gap-3 overflow-hidden">
        <!-- Hamburger for mobile -->
        <button onclick="openSidebar()" class="lg:hidden w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-gray-500 hover:text-primary hover:bg-white rounded-lg sm:rounded-xl transition-all shadow-sm border border-gray-100 bg-white flex-shrink-0">
            <i class="fas fa-bars text-sm"></i>
        </button>
        <div class="truncate">
            <p class="text-[10px] text-gray-400 font-medium hidden sm:block truncate">{{ $page_breadcrumb ?? 'Pages / Dashboard' }}</p>
            <h2 class="text-base sm:text-3xl font-bold text-gray-800 tracking-tight truncate">{{ $page_title ?? 'Main Dashboard' }}</h2>
        </div>
    </div>

    <div class="flex items-center bg-white p-1 sm:p-2 rounded-full shadow-sm border border-gray-100 space-x-0.5 sm:space-x-2 flex-shrink-0">
        <!-- Search (hidden on very small screens) -->
        <div class="hidden md:flex items-center bg-gray-50 px-4 py-2 rounded-full border border-gray-100 group focus-within:border-primary transition-all">
            <i class="fas fa-search text-gray-400 text-sm mr-2 group-focus-within:text-primary"></i>
            <input type="text" placeholder="Type here..." class="bg-transparent border-none outline-none text-sm text-gray-600 placeholder:text-gray-300 w-32 lg:w-48">
        </div>
        
        <a href="{{ route('agent.notifications') }}" class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-gray-400 hover:text-primary transition-colors">
            <i class="fas fa-bell text-xs sm:text-base"></i>
        </a>
        
        <a href="{{ route('agent.settings') }}" class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center text-gray-400 hover:text-primary transition-colors">
            <i class="fas fa-cog text-xs sm:text-base"></i>
        </a>
        
        <a href="{{ route('agent.about') }}" class="w-8 h-8 sm:w-10 sm:h-10 flex items-center justify-center bg-gray-400 text-white rounded-full transition-colors hover:bg-gray-500 hidden xs:flex">
            <i class="fas fa-info text-[8px] sm:text-[10px]"></i>
        </a>

        <a href="{{ route('agent.profile') }}" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center bg-primary text-white font-bold text-[10px] sm:text-sm shadow-sm transition-all border-2 border-white">
            AU
        </a>
    </div>
</nav>
