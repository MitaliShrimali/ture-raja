@extends('layouts.admin')

@section('admin_title', 'Dashboard')

@section('content')
<div class="space-y-10 pb-12">
    <!-- Hero Header -->
    <section class="space-y-2">
        <h2 class="font-black font-syne text-foreground tracking-tight">
            Global Command Center
        </h2>
        <p class="text-lg text-muted-text font-medium">
            Monitoring platform performance across 12 regions • <span class="text-primary">Live Data</span>
        </p>
    </section>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
        @php
            $isLive = in_array(request()->getHost(), ['tour-raja.com', 'www.tour-raja.com']);
            $totalRev = $isLive ? '₹0.00' : $data['metrics']['totalRevenue'];
            $totalProfit = $isLive ? 'Profit: ₹0.00' : 'Profit: ' . $data['metrics']['totalProfit'];
        @endphp
        @foreach([
            ['title' => 'Total Rev. / Profit', 'value' => $totalRev, 'growth' => $totalProfit, 'icon' => 'bar-chart-3', 'color' => 'primary', 'link' => url('/admin/payments')],
            ['title' => 'Total Agents', 'value' => $data['metrics']['activeAgents'], 'growth' => $data['metrics']['agentGrowth'], 'icon' => 'users', 'color' => 'blue-500', 'link' => url('/admin/registered-agents')],
            ['title' => 'Total Subscribers', 'value' => $data['metrics']['totalSubscribers'], 'growth' => $data['metrics']['subscriberGrowth'], 'icon' => 'globe', 'color' => 'orange-500', 'link' => url('/admin/subscribers')],
            ['title' => 'Pending Packages', 'value' => $data['metrics']['pendingPackages'], 'growth' => 'Action Req.', 'icon' => 'clock', 'color' => 'orange-500', 'link' => url('/admin/packages/pending')],
            ['title' => 'Expired Packages', 'value' => $data['metrics']['expiredPackages'], 'growth' => 'Review', 'icon' => 'alert-circle', 'color' => 'red-500', 'link' => url('/admin/packages?status=expired')],
        ] as $metric)
            <a href="{{ $metric['link'] }}" class="block bg-white p-6 rounded-[32px] shadow-soft border border-border-soft space-y-4 hover:shadow-lg hover:border-primary/20 transition-all">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-{{ $metric['color'] }}">
                        <i data-lucide="{{ $metric['icon'] }}" size="18"></i>
                    </div>
                    <span class="text-[10px] font-black text-green-500 bg-green-50 px-2 py-1 rounded-lg">{{ $metric['growth'] }}</span>
                </div>
                <div>
                    <p class="text-[10px] font-black text-muted-text uppercase tracking-widest">{{ $metric['title'] }}</p>
                    <h3 class="text-2xl font-black font-syne text-foreground">{{ $metric['value'] }}</h3>
                </div>
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Subscriptions Table -->
        <div class="lg:col-span-2 bg-white rounded-[32px] shadow-soft p-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-border-soft pb-4">
                <div>
                    <h3 class="text-2xl font-black text-foreground">Recent Registered Agents</h3>
                    <p class="text-sm text-muted-text font-medium">Tracking agent registrations</p>
                </div>
                
                <div class="flex items-center gap-4">
                    <form id="agentSearchForm" action="{{ url('/admin') }}" method="GET" class="flex items-center gap-2">
                        <div class="relative">
                            <input type="text" id="agentSearchInput" name="search" value="{{ request('search') }}" placeholder="Search agents..." autocomplete="off" class="w-full sm:w-64 pl-10 pr-8 py-2 bg-gray-50 border border-gray-100 rounded-xl text-sm focus:ring-2 focus:ring-[#ea580c]/20 focus:border-[#ea580c] transition-all placeholder:text-gray-400">
                            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                            <button type="button" id="clearSearchBtn" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 transition-colors {{ request('search') ? '' : 'hidden' }}">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <button type="submit" id="searchSubmitBtn" class="bg-[#ea580c] hover:bg-orange-600 text-white p-2 rounded-xl transition-colors shadow-sm shrink-0">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>
                    </form>
                    <a href="{{ url('/admin/registered-agents') }}" class="hidden sm:flex items-center gap-2 text-xs font-bold text-[#ea580c] uppercase tracking-widest hover:gap-3 transition-all shrink-0">
                        View All <i data-lucide="arrow-up-right" size="14"></i>
                    </a>
                </div>
            </div>

            <div class="admin-table-container">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-border-soft">
                            <th class="pb-4 text-[10px] font-black text-muted-text uppercase tracking-widest">Agent</th>
                            <th class="pb-4 text-[10px] font-black text-muted-text uppercase tracking-widest">Phone</th>
                            <th class="pb-4 text-[10px] font-black text-muted-text uppercase tracking-widest">Plan Type</th>
                            <th class="pb-4 text-[10px] font-black text-muted-text uppercase tracking-widest">Status</th>
                            <th class="pb-4 text-[10px] font-black text-muted-text uppercase tracking-widest">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft" id="agentsTableBody">
                        @forelse($recentAgents as $agent)
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center font-bold text-muted-text uppercase">
                                            {{ substr($agent->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-foreground">{{ $agent->name }}</p>
                                            <p class="text-[10px] text-muted-text font-medium">{{ $agent->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5">
                                    <p class="text-xs text-muted-text font-bold">{{ $agent->phone }}</p>
                                </td>
                                <td class="py-5">
                                    <span class="px-3 py-1 rounded-full bg-primary/5 text-primary text-[10px] font-black uppercase tracking-wider">
                                        {{ $agent->plan_name ?? 'Basic' }}
                                    </span>
                                </td>
                                <td class="py-5">
                                    @php
                                        $statusClass = 'text-red-500';
                                        $statusIcon = 'x-circle';
                                        $lowerStatus = strtolower($agent->status ?? 'pending');
                                        if ($lowerStatus === 'active' || $lowerStatus === 'approved') {
                                            $statusClass = 'text-green-500';
                                            $statusIcon = 'check-circle-2';
                                        } elseif ($lowerStatus === 'pending') {
                                            $statusClass = 'text-orange-500';
                                            $statusIcon = 'clock';
                                        }
                                    @endphp
                                    <div class="flex items-center gap-2 {{ $statusClass }}">
                                        <i data-lucide="{{ $statusIcon }}" size="14"></i>
                                        <span class="text-xs font-bold">{{ ucfirst($agent->status ?? 'Pending') }}</span>
                                    </div>
                                </td>
                                <td class="py-5">
                                    <p class="text-xs text-muted-text font-medium">{{ date('M d, Y', strtotime($agent->created_at)) }}</p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-sm font-bold text-muted-text">No recent agent registrations found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Global Activity Feed -->
        <div class="bg-white rounded-[32px] shadow-soft p-8 space-y-8 h-fit">
            <div>
                <h3 class="text-2xl font-black font-syne text-foreground">Live Activity</h3>
                <p class="text-sm text-muted-text font-medium">Real-time platform updates</p>
            </div>

            <div class="space-y-6">
                @forelse($data['recentActivities'] as $idx => $activity)
                    <div class="flex gap-4 relative">
                        @if($idx !== count($data['recentActivities']) - 1)
                            <div class="absolute left-5 top-10 bottom-0 w-[1px] bg-border-soft"></div>
                        @endif
                        <div class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center shadow-sm {{ $activity['status'] === 'completed' ? 'bg-green-50 text-green-500' : 'bg-orange-50 text-orange-500' }}">
                            <i data-lucide="{{ $activity['status'] === 'completed' ? 'check-circle-2' : 'clock' }}" size="20"></i>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm font-bold text-foreground leading-snug">
                                {{ $activity['user'] }} <span class="font-medium text-muted-text">{{ $activity['action'] }}</span>
                            </p>
                            <p class="text-[10px] font-bold text-muted-text uppercase tracking-widest opacity-60">
                                {{ $activity['time'] }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm font-medium text-muted-text text-center py-4">No recent activities found.</p>
                @endforelse
            </div>

            <a href="{{ url('/admin/settings/activity-logs') }}" class="w-full py-4 rounded-2xl bg-gray-50 text-xs font-black text-muted-text uppercase tracking-widest hover:bg-gray-100 transition-colors flex items-center justify-center gap-2">
                View All Activity Logs <i data-lucide="external-link" size="14"></i>
            </a>
        </div>
    </div>



</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('agentSearchInput');
        const clearBtn = document.getElementById('clearSearchBtn');
        const tableBody = document.getElementById('agentsTableBody');
        let debounceTimer;

        function performSearch(query) {
            // Update URL without reloading
            const newUrl = new URL(window.location.href);
            if (query) {
                newUrl.searchParams.set('search', query);
                clearBtn.classList.remove('hidden');
            } else {
                newUrl.searchParams.delete('search');
                clearBtn.classList.add('hidden');
            }
            window.history.replaceState({}, '', newUrl);

            // Visual feedback
            tableBody.style.opacity = '0.5';

            fetch(newUrl.toString())
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTableBody = doc.getElementById('agentsTableBody');
                    
                    if (newTableBody) {
                        tableBody.innerHTML = newTableBody.innerHTML;
                        // Re-initialize lucide icons for new content if needed
                        if (typeof lucide !== 'undefined') {
                            lucide.createIcons();
                        }
                    }
                })
                .catch(error => console.error('Search error:', error))
                .finally(() => {
                    tableBody.style.opacity = '1';
                });
        }

        searchInput.addEventListener('input', function(e) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                performSearch(e.target.value.trim());
            }, 300); // 300ms debounce
        });

        document.getElementById('agentSearchForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent standard page reload
            clearTimeout(debounceTimer);
            performSearch(searchInput.value.trim());
        });

        clearBtn.addEventListener('click', function() {
            searchInput.value = '';
            performSearch('');
        });
    });
</script>
@endsection
