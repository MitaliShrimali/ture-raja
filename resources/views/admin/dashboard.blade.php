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
            ['title' => 'Total Revenue', 'value' => $data['metrics']['totalRevenue'], 'growth' => $data['metrics']['revenueGrowth'], 'icon' => 'bar-chart-3', 'color' => 'primary', 'link' => url('/admin/payments')],
            ['title' => 'Verified Agents', 'value' => $data['metrics']['activeAgents'], 'growth' => $data['metrics']['agentGrowth'], 'icon' => 'users', 'color' => 'blue-500', 'link' => url('/admin/agents')],
            ['title' => 'Active Packages', 'value' => $data['metrics']['activePackages'], 'growth' => $data['metrics']['packageGrowth'], 'icon' => 'package', 'color' => 'green-500', 'link' => url('/admin/packages')],
            ['title' => 'Total Subscribers', 'value' => $data['metrics']['totalSubscribers'], 'growth' => $data['metrics']['subscriberGrowth'], 'icon' => 'globe', 'color' => 'orange-500', 'link' => url('/admin/subscribers')],
        ] as $metric)
            <a href="{{ $metric['link'] }}" class="block bg-white p-8 rounded-[32px] shadow-soft border border-border-soft space-y-4 hover:shadow-lg hover:border-primary/20 transition-all">
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
            </a>
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
                        @forelse($recentPayments as $payment)
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center font-bold text-muted-text uppercase">
                                            {{ substr($payment->user_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-foreground">{{ $payment->user_name }}</p>
                                            <p class="text-[10px] text-muted-text font-medium">{{ $payment->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-5">
                                    <span class="px-3 py-1 rounded-full bg-primary/5 text-primary text-[10px] font-black uppercase tracking-wider">
                                        {{ $payment->plan_type }}
                                    </span>
                                </td>
                                <td class="py-5">
                                    <div class="flex items-center gap-2 {{ $payment->status === 'Completed' ? 'text-green-500' : ($payment->status === 'Pending' ? 'text-orange-500' : 'text-red-500') }}">
                                        <i data-lucide="{{ $payment->status === 'Completed' ? 'check-circle-2' : ($payment->status === 'Pending' ? 'clock' : 'x-circle') }}" size="14"></i>
                                        <span class="text-xs font-bold">{{ $payment->status }}</span>
                                    </div>
                                </td>
                                <td class="py-5">
                                    <p class="text-sm font-bold text-foreground">₹{{ number_format($payment->amount, 2) }}</p>
                                </td>
                                <td class="py-5">
                                    <p class="text-xs text-muted-text font-medium">{{ date('M d, Y', strtotime($payment->date)) }}</p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-sm font-bold text-muted-text">No subscription transactions found.</td>
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

            <a href="{{ url('/admin/reports') }}" class="w-full py-4 rounded-2xl bg-gray-50 text-xs font-black text-muted-text uppercase tracking-widest hover:bg-gray-100 transition-colors flex items-center justify-center gap-2">
                View All Reports <i data-lucide="external-link" size="14"></i>
            </a>
        </div>
    </div>

    <!-- Package Approvals -->
    <div class="bg-white rounded-[32px] shadow-soft p-8 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-black text-foreground">Package Approvals</h3>
                <p class="text-sm text-muted-text font-medium">Review and publish submitted tour packages</p>
            </div>
            <div class="flex items-center gap-4">
                @if($pendingPackagesCount > 0)
                    <span class="text-xs font-black text-white px-3 py-1.5 rounded-full" style="background-color:#e85d26;">
                        {{ $pendingPackagesCount }} NEW
                    </span>
                @endif
                <a href="{{ url('/admin/packages') }}" class="flex items-center gap-2 text-xs font-bold text-primary uppercase tracking-widest hover:gap-3 transition-all">
                    View All <i data-lucide="arrow-up-right" size="14"></i>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 font-bold text-xs flex items-center gap-3">
                <i data-lucide="check-circle-2" size="16" class="shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @forelse($pendingPackages as $pkg)
            <div class="flex items-center justify-between py-5 border-b border-border-soft last:border-0">
                <div class="flex items-center gap-4">
                    <!-- Package image -->
                    <div class="w-14 h-14 rounded-2xl overflow-hidden shrink-0 bg-gray-100">
                        <img src="{{ $pkg->image ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=200' }}"
                             alt="{{ $pkg->title }}"
                             class="w-full h-full object-cover">
                    </div>
                    <!-- Info -->
                    <div>
                        <p class="text-sm font-bold text-foreground">{{ $pkg->title }}</p>
                        <p class="text-xs text-muted-text font-medium mt-0.5">
                            {{ $pkg->location ?? 'Global' }}
                            @if($pkg->created_at)
                                &nbsp;·&nbsp;{{ \Carbon\Carbon::parse($pkg->created_at)->diffForHumans() }}
                            @endif
                        </p>
                        <!-- Action buttons -->
                        <div class="flex items-center gap-2 mt-3">
                            <a href="{{ route('admin.package.approve', $pkg->id) }}"
                               class="px-4 py-1.5 rounded-full text-white text-[10px] font-black uppercase tracking-wider transition-all hover:opacity-90"
                               style="background-color:#e85d26;"
                               onclick="return confirm('Approve and publish this package?')">
                                Approve
                            </a>
                            <a href="{{ route('admin.package.decline', $pkg->id) }}"
                               class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider border border-gray-200 text-muted-text hover:border-red-300 hover:text-red-500 transition-all"
                               onclick="return confirm('Decline this package?')">
                                Decline
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Price -->
                <div class="text-right shrink-0 ml-4">
                    <p class="text-sm font-black text-foreground">₹{{ number_format($pkg->price, 0) }}</p>
                    @if($pkg->duration)
                        <p class="text-[10px] text-muted-text font-medium mt-0.5">{{ $pkg->duration }}</p>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-12 text-center">
                <div class="w-16 h-16 rounded-2xl bg-green-50 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="check-circle-2" size="28" class="text-green-500"></i>
                </div>
                <p class="text-sm font-bold text-muted-text">All packages are reviewed!</p>
                <p class="text-xs text-muted-text/60 mt-1">No packages pending approval right now.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
