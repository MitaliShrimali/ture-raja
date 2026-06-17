<header class="h-20 bg-white border-b border-gray-100 flex items-center justify-between px-6 lg:px-8 sticky top-0 z-50 shrink-0 mb-6">
    <div class="flex items-center gap-4">
        <!-- Mobile Menu Button -->
        <button onclick="openSidebar()" class="lg:hidden p-2 hover:bg-gray-50 rounded-xl text-gray-400">
            <i class="fas fa-bars text-lg"></i>
        </button>
        
        <div class="flex flex-col">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] leading-none mb-1">Agent Portal</p>
            <h2 class="text-sm sm:text-lg font-black text-gray-800 tracking-tight leading-none">{{ $page_title ?? 'Dashboard' }}</h2>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <!-- Pill Container for Search, Icons, and Profile -->
        <div class="flex items-center bg-white rounded-full shadow-[0_2px_15px_-3px_rgba(0,0,0,0.05),0_10px_20px_-2px_rgba(0,0,0,0.02)] p-1 pl-3 gap-3 md:gap-5 border border-gray-50">
            
            <!-- Search -->
            <div class="flex items-center bg-white border border-gray-200 rounded-full w-24 sm:w-32 md:w-48 overflow-hidden focus-within:ring-1 focus-within:ring-primary/20 focus-within:border-primary transition-all">
                <div class="pl-2.5 flex items-center justify-center text-gray-400 pointer-events-none">
                    <i class="fas fa-search text-xs"></i>
                </div>
                <input 
                    type="text" 
                    placeholder="Type" 
                    class="w-full bg-transparent border-none py-1.5 pl-2 pr-3 text-xs font-semibold text-gray-700 placeholder:text-gray-400 focus:outline-none focus:ring-0"
                >
            </div>

            <!-- Icons -->
            <div class="flex items-center gap-1 md:gap-1.5">
                <a href="{{ route('agent.notifications') }}" class="relative p-1.5 text-gray-400 hover:text-primary hover:bg-gray-50 rounded-full transition-colors">
                    <i class="fas fa-bell text-sm"></i>
                </a>
                <a href="{{ route('agent.settings') }}" class="p-1.5 text-gray-400 hover:text-primary hover:bg-gray-50 rounded-full transition-colors">
                    <i class="fas fa-cog text-sm"></i>
                </a>
                <a href="{{ route('agent.about') }}" class="p-1.5 text-gray-400 hover:text-primary hover:bg-gray-50 rounded-full transition-colors">
                    <i class="fas fa-info-circle text-sm"></i>
                </a>
            </div>

            <!-- User Profile Avatar -->
            <a href="{{ route('agent.profile') }}" class="block shrink-0 pr-1">
                @php
                    $navAgent = \DB::table('agents')->where('id', session('agent_id'))->first();
                    $navLogo = ($navAgent && $navAgent->logo) ? $navAgent->logo : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode(session('agent_name', 'Agent'));
                @endphp
                <img src="{{ $navLogo }}" alt="Profile" class="w-8 h-8 rounded-full object-cover border border-gray-100 hover:ring-2 hover:ring-primary/20 transition-all">
            </a>
        </div>
    </div>
</header>
