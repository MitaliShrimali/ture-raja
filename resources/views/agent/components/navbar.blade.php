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
            <div class="relative flex items-center bg-white border border-gray-200 rounded-full w-24 sm:w-32 md:w-48 overflow-hidden focus-within:ring-1 focus-within:ring-primary/20 focus-within:border-primary transition-all">
                <div class="pl-2.5 flex items-center justify-center text-gray-400 pointer-events-none">
                    <i class="fas fa-search text-xs"></i>
                </div>
                <input 
                    type="text" 
                    id="globalSearchAgent"
                    placeholder="Search Pages" 
                    autocomplete="off"
                    class="w-full bg-transparent border-none py-1.5 pl-2 pr-3 text-xs font-semibold text-gray-700 placeholder:text-gray-400 focus:outline-none focus:ring-0"
                >
            </div>
            
            <!-- Global Search Dropdown (Absolute to navbar, relative to body via script positioning or we can make the parent container relative) -->

            <!-- Icons -->
            <div class="flex items-center gap-1 md:gap-1.5">
                <a href="{{ route('agent.notifications') }}" class="relative p-1.5 text-gray-400 hover:text-primary hover:bg-gray-50 rounded-full transition-colors">
                    <i class="fas fa-bell text-sm"></i>
                </a>

                <a href="{{ route('agent.about') }}" class="p-1.5 text-gray-400 hover:text-primary hover:bg-gray-50 rounded-full transition-colors">
                    <i class="fas fa-info-circle text-sm"></i>
                </a>
            </div>

            <!-- User Profile Avatar -->
            <a href="{{ route('agent.profile') }}" class="block shrink-0 pr-1 relative">
                @php
                    $navAgent = \DB::table('agents')->where('id', session('agent_id'))->first();
                    $navLogo = ($navAgent && $navAgent->logo) ? $navAgent->logo : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode(session('agent_name', 'Agent'));
                @endphp
                <img src="{{ asset($navLogo) }}" alt="Profile" class="w-8 h-8 rounded-full object-cover border border-gray-100 hover:ring-2 hover:ring-primary/20 transition-all">
                @if($navAgent && $navAgent->service_guaranteed)
                    <div class="absolute -bottom-1 -right-1 bg-white rounded-full flex items-center justify-center" style="padding: 1px;">
                        <i data-lucide="check-circle" class="text-blue-500 w-3.5 h-3.5 shrink-0" title="Trusted Agent"></i>
                    </div>
                @endif
            </a>
        </div>
        
        <!-- Search Dropdown Container -->
        <div id="globalSearchAgentDropdown" class="absolute top-16 right-6 lg:right-8 w-64 bg-white border border-gray-200 rounded-xl shadow-lg hidden z-50 max-h-64 overflow-y-auto">
            <ul id="globalSearchAgentResults" class="py-2 text-xs text-gray-700 divide-y divide-gray-50">
            </ul>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pages = [
        { name: 'Dashboard', url: '{{ route("agent.dashboard") }}' },
        { name: 'Packages', url: '{{ route("agent.my-packages") }}' },
        { name: 'Add Package / Create Package', url: '{{ route("agent.packages.create") }}' },
        { name: 'Leads', url: '{{ route("agent.leads") }}' },
        { name: 'Hotels', url: '{{ route("agent.hotels") }}' },
        { name: 'Add Hotel', url: '{{ route("agent.add-hotel") }}' },
        { name: 'Branches', url: '{{ route("agent.branch") }}' },
        { name: 'Add Branch', url: '{{ route("agent.add-branch") }}' },
        { name: 'Gallery', url: '{{ route("agent.gallery") }}' },
        { name: 'Notifications', url: '{{ route("agent.notifications") }}' },
        { name: 'Feedback', url: '{{ route("agent.feedback") }}' },
        { name: 'Settings', url: '{{ route("agent.settings") }}' },
        { name: 'Profile', url: '{{ route("agent.profile") }}' },
        { name: 'About', url: '{{ route("agent.about") }}' },
        { name: 'Services', url: '{{ route("agent.services") }}' },
        { name: 'Contact', url: '{{ route("agent.contact") }}' },
        { name: 'Payment / Invoice', url: '{{ route("agent.payment") }}' }
    ];

    const input = document.getElementById('globalSearchAgent');
    const dropdown = document.getElementById('globalSearchAgentDropdown');
    const resultsContainer = document.getElementById('globalSearchAgentResults');

    if(input) {
        input.addEventListener('input', function() {
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

        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    }
});
</script>
