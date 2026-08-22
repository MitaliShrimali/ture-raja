@extends('agent.layouts.app')

@section('title', 'Dashboard - Tour Raja Agent')

@section('content')
<!-- Stats Cards Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <!-- Card 1: Total Packages -->
            <a href="{{ route('agent.my-packages') }}" class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between cursor-pointer hover:shadow-md hover:border-orange-200 hover:bg-orange-50/30 transition-all duration-200 group">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 group-hover:text-orange-500 transition-colors">Total Packages</p>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-2xl font-bold text-gray-800">{{ $totalPackages }}</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center text-white shadow-lg shadow-orange-100 group-hover:scale-110 transition-transform">
                    <i class="fas fa-box"></i>
                </div>
            </a>
            <!-- Card 2: Active Packages -->
            <a href="{{ route('agent.my-packages') }}" class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between cursor-pointer hover:shadow-md hover:border-green-200 hover:bg-green-50/30 transition-all duration-200 group">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 group-hover:text-green-600 transition-colors">Active Packages</p>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-2xl font-bold text-gray-800">{{ $activePackages }}</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center text-white shadow-lg shadow-orange-100 group-hover:scale-110 transition-transform">
                    <i class="fas fa-check-circle"></i>
                </div>
            </a>
            <!-- Card 3: Pending Packages -->
            <a href="{{ route('agent.my-packages') }}" class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between cursor-pointer hover:shadow-md hover:border-yellow-200 hover:bg-yellow-50/30 transition-all duration-200 group">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 group-hover:text-yellow-600 transition-colors">Pending Packages</p>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-2xl font-bold text-gray-800">{{ $pendingPackages }}</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center text-white shadow-lg shadow-orange-100 group-hover:scale-110 transition-transform">
                    <i class="fas fa-clock"></i>
                </div>
            </a>
            <!-- Card 4: Expired Packages -->
            <a href="{{ route('agent.my-packages') }}" class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between cursor-pointer hover:shadow-md hover:border-red-200 hover:bg-red-50/30 transition-all duration-200 group">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 group-hover:text-red-500 transition-colors">Expired Packages</p>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-2xl font-bold text-gray-800">{{ $expiredPackages }}</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center text-white shadow-lg shadow-orange-100 group-hover:scale-110 transition-transform">
                    <i class="fas fa-times-circle"></i>
                </div>
            </a>
        </div>

        <!-- Middle Section: Chart & Profiles -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <!-- Chart Placeholder -->
            <div class="lg:col-span-8 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <p class="text-xs font-bold text-gray-400 mb-1">Total Leads</p>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $totalLeads }}</h3>
                    </div>
                    <div class="w-10 h-10 bg-orange-50 text-primary rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                </div>
                <!-- Simple CSS Chart Placeholder to match design -->
                <div class="flex items-end justify-between h-48 px-2">
                    @foreach($chartData as $val)
                        <div class="w-6 rounded-t-lg transition-all duration-300 {{ $val > 0 ? 'bg-primary shadow-lg shadow-orange-100' : 'bg-gray-100' }}" style="height: {{ max($val, 5) }}%"></div>
                    @endforeach
                </div>
                <div class="flex justify-between mt-4 text-[10px] font-bold text-gray-300 uppercase">
                    <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span><span>Jul</span><span>Aug</span><span>Sep</span><span>Oct</span><span>Nov</span><span>Dec</span>
                </div>
            </div>

            <!-- Profile Card -->
            <div class="lg:col-span-4 bg-white p-4 sm:p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center">
                <div class="relative mb-4">
                    @php
                        $profileLogo = ($agent && $agent->logo) ? $agent->logo : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode(session('agent_name', 'Agent'));
                        $profileName = ($agent && $agent->agency_name) ? $agent->agency_name : ($agent ? $agent->name : session('agent_name', 'Agent'));
                        $locParts = array_filter([$agent->city ?? null, $agent->state ?? null, $agent->country ?? null]);
                        $profileRegion = !empty($locParts) ? implode(', ', $locParts) : 'Location not set';
                    @endphp
                    <img src="{{ asset($profileLogo) }}" class="w-24 h-24 rounded-full border-4 border-gray-50 object-cover" alt="Profile">
                    <div class="absolute bottom-1 right-1 w-6 h-6 bg-green-500 border-4 border-white rounded-full"></div>
                </div>
                <h4 class="text-xl font-bold text-primary flex items-center justify-center gap-1">
                    {{ $profileName }}
                    @if($agent && $agent->service_guaranteed)
                        <i data-lucide="check-circle" class="text-blue-500 w-5 h-5 shrink-0" title="Trusted Agent"></i>
                    @endif
                </h4>
                <p class="text-xs text-gray-400 font-medium mb-6"><i class="fas fa-map-marker-alt mr-1"></i> {{ $profileRegion }}</p>
                
                <div class="grid grid-cols-3 gap-8 w-full border-t border-gray-50 pt-6">
                    <div>
                        <p class="text-[10px] font-bold text-gray-300 uppercase mb-1">Package</p>
                        <p class="text-lg font-bold text-gray-800">{{ $profilePackages }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-300 uppercase mb-1">Leads</p>
                        <p class="text-lg font-bold text-gray-800">{{ $profileLeads }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-300 uppercase mb-1">Review</p>
                        <p class="text-lg font-bold text-gray-800">{{ $profileReviews }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Section: Leads Table -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6">
            <!-- Recent Lead Table -->
            <div class="lg:col-span-12 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="text-lg font-bold text-gray-800">Recent Lead</h4>
                        <p class="text-xs text-green-500 font-bold flex items-center mt-1">
                            <i class="fas fa-check-circle mr-1"></i> {{ count($recentLeads) > 0 ? '30' : '0' }} done this month
                        </p>
                    </div>
                    <button class="text-gray-300"><i class="fas fa-ellipsis-v"></i></button>
                </div>
                <div class="overflow-x-auto">
                    @if(count($recentLeads) > 0)
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-bold text-gray-300 uppercase border-b border-gray-50 whitespace-nowrap">
                                <th class="pb-4">Companies</th>
                                <th class="pb-4">Members</th>
                                <th class="pb-4">Budget</th>
                                <th class="pb-4">Completion</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($recentLeads as $p)
                            <tr class="group hover:bg-gray-50/50 transition-colors whitespace-nowrap">
                                <td class="py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-lg border border-gray-100 flex items-center justify-center mr-3">
                                            <i class="{{ $p['icon'] }}"></i>
                                        </div>
                                        <span class="text-xs font-bold text-gray-800">{{ $p['name'] }}</span>
                                    </div>
                                </td>
                                <td class="py-4">
                                    <div class="flex -space-x-2">
                                        <img src="https://i.pravatar.cc/30?u=1" class="w-6 h-6 rounded-full border-2 border-white">
                                        <img src="https://i.pravatar.cc/30?u=2" class="w-6 h-6 rounded-full border-2 border-white">
                                        <img src="https://i.pravatar.cc/30?u=3" class="w-6 h-6 rounded-full border-2 border-white">
                                    </div>
                                </td>
                                <td class="py-4 text-xs font-bold text-gray-600">{{ $p['budget'] }}</td>
                                <td class="py-4">
                                    <div class="w-32">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-[10px] font-bold text-primary">{{ $p['prog'] }}</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="{{ $p['color'] }} h-full" style="width: {{ $p['prog'] }}"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="py-12 text-center text-gray-400 font-medium">
                        <i class="fas fa-inbox text-3xl mb-3 block text-gray-300"></i>
                        No recent leads found.
                    </div>
                    @endif
                </div>
            </div>
        </div>
@endsection
