@extends('layouts.admin')

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
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        @foreach([
            ['title' => 'Total Revenue', 'value' => $data['metrics']['totalRevenue'], 'growth' => $data['metrics']['revenueGrowth'], 'icon' => 'bar-chart-3', 'color' => 'primary'],
            ['title' => 'Verified Agents', 'value' => $data['metrics']['activeAgents'], 'growth' => $data['metrics']['agentGrowth'], 'icon' => 'users', 'color' => 'blue-500'],
            ['title' => 'Active Packages', 'value' => $data['metrics']['activePackages'], 'growth' => $data['metrics']['packageGrowth'], 'icon' => 'package', 'color' => 'green-500'],
            ['title' => 'Total Subscribers', 'value' => $data['metrics']['totalSubscribers'], 'growth' => $data['metrics']['subscriberGrowth'], 'icon' => 'globe', 'color' => 'orange-500'],
        ] as $metric)
            <div class="bg-white p-8 rounded-[32px] shadow-soft border border-border-soft space-y-4">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-{{ $metric['color'] }}">
                        <i data-lucide="{{ $metric['icon'] }}" size="18"></i>
                    </div>
                    <span class="text-xs font-black text-green-500 bg-green-50 px-2 py-1 rounded-lg">{{ $metric['growth'] }}</span>
                </div>
                <div>
                    <p class="text-xs font-black text-muted-text uppercase tracking-widest">{{ $metric['title'] }}</p>
                    <h3 class="text-3xl font-black font-syne text-foreground">{{ $metric['value'] }}</h3>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Subscriptions Table -->
        <div class="lg:col-span-2 bg-white rounded-[32px] shadow-soft p-8 space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-black text-foreground">Recent Subscriptions</h3>
                    <p class="text-sm text-muted-text font-medium">Tracking the latest 5 premium activations</p>
                </div>
                <button class="flex items-center gap-2 text-xs font-bold text-primary uppercase tracking-widest hover:gap-3 transition-all">
                    View All <i data-lucide="arrow-up-right" size="14"></i>
                </button>
            </div>

            <div class="admin-table-container">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-border-soft">
                            <th class="pb-4 text-[10px] font-black text-muted-text uppercase tracking-widest">User / Agent</th>
                            <th class="pb-4 text-[10px] font-black text-muted-text uppercase tracking-widest">Plan Type</th>
                            <th class="pb-4 text-[10px] font-black text-muted-text uppercase tracking-widest">Status</th>
                            <th class="pb-4 text-[10px] font-black text-muted-text uppercase tracking-widest">Amount</th>
                            <th class="pb-4 text-[10px] font-black text-muted-text uppercase tracking-widest">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft">
                        @for($i = 1; $i <= 5; $i++)
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center font-bold text-muted-text">
                                            {{ chr(64 + $i) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-foreground">User_{{ $i }}</p>
                                            <p class="text-[10px] text-muted-text font-medium">user{{ $i }}@example.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5">
                                    <span class="px-3 py-1 rounded-full bg-primary/5 text-primary text-[10px] font-black uppercase tracking-wider">
                                        Premium Plus
                                    </span>
                                </td>
                                <td class="py-5">
                                    <div class="flex items-center gap-2 text-green-500">
                                        <i data-lucide="check-circle-2" size="14"></i>
                                        <span class="text-xs font-bold">Active</span>
                                    </div>
                                </td>
                                <td class="py-5">
                                    <p class="text-sm font-bold text-foreground">₹199.00</p>
                                </td>
                                <td class="py-5">
                                    <p class="text-xs text-muted-text font-medium">Oct 24, 2024</p>
                                </td>
                            </tr>
                        @endfor
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
                @foreach($data['recentActivities'] as $idx => $activity)
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
                @endforeach
            </div>

            <button class="w-full py-4 rounded-2xl bg-gray-50 text-xs font-black text-muted-text uppercase tracking-widest hover:bg-gray-100 transition-colors flex items-center justify-center gap-2">
                View All Reports <i data-lucide="external-link" size="14"></i>
            </button>
        </div>
    </div>
</div>
@endsection
