@extends('agent.layouts.app')

@section('title', 'Dashboard - Tour Raja Agent')

@section('content')
<!-- Stats Cards Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <!-- Card 1 -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Packages</p>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-2xl font-bold text-gray-800">200</span>
                        <span class="text-[10px] font-bold text-green-500">+55%</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center text-white shadow-lg shadow-orange-100">
                    <i class="fas fa-box"></i>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Active Packages</p>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-2xl font-bold text-gray-800">20</span>
                        <span class="text-[10px] font-bold text-green-500">+12%</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center text-white shadow-lg shadow-orange-100">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Pending Packages</p>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-2xl font-bold text-gray-800">02</span>
                        <span class="text-[10px] font-bold text-green-500">+55%</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center text-white shadow-lg shadow-orange-100">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <!-- Card 4 -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Expired Packages</p>
                    <div class="flex items-baseline space-x-2">
                        <span class="text-2xl font-bold text-gray-800">00</span>
                        <span class="text-[10px] font-bold text-green-500">+55%</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center text-white shadow-lg shadow-orange-100">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
        </div>

        <!-- Middle Section: Chart & Profiles -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <!-- Chart Placeholder -->
            <div class="lg:col-span-6 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <p class="text-xs font-bold text-gray-400 mb-1">Total Leads</p>
                        <h3 class="text-3xl font-bold text-gray-800">682</h3>
                    </div>
                    <div class="w-10 h-10 bg-orange-50 text-primary rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                </div>
                <!-- Simple CSS Chart Placeholder to match design -->
                <div class="flex items-end justify-between h-48 px-2">
                    <div class="w-6 bg-gray-100 rounded-t-lg h-[40%]"></div>
                    <div class="w-6 bg-gray-100 rounded-t-lg h-[70%]"></div>
                    <div class="w-6 bg-gray-100 rounded-t-lg h-[55%]"></div>
                    <div class="w-6 bg-gray-100 rounded-t-lg h-[65%]"></div>
                    <div class="w-6 bg-gray-100 rounded-t-lg h-[50%]"></div>
                    <div class="w-6 bg-primary rounded-t-lg h-[90%] shadow-lg shadow-orange-100"></div>
                    <div class="w-6 bg-gray-100 rounded-t-lg h-[60%]"></div>
                    <div class="w-6 bg-gray-100 rounded-t-lg h-[80%]"></div>
                    <div class="w-6 bg-gray-100 rounded-t-lg h-[45%]"></div>
                    <div class="w-6 bg-gray-100 rounded-t-lg h-[75%]"></div>
                    <div class="w-6 bg-gray-100 rounded-t-lg h-[55%]"></div>
                    <div class="w-6 bg-gray-100 rounded-t-lg h-[65%]"></div>
                </div>
                <div class="flex justify-between mt-4 text-[10px] font-bold text-gray-300 uppercase">
                    <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>May</span><span>Jun</span><span>Jul</span><span>Aug</span><span>Sep</span><span>Oct</span><span>Nov</span><span>Dec</span>
                </div>
            </div>

            <!-- Profile Card -->
            <div class="lg:col-span-3 bg-white p-4 sm:p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center">
                <div class="relative mb-4">
                    <img src="https://i.pravatar.cc/150?u=person" class="w-24 h-24 rounded-full border-4 border-gray-50" alt="Profile">
                    <div class="absolute bottom-1 right-1 w-6 h-6 bg-green-500 border-4 border-white rounded-full"></div>
                </div>
                <h4 class="text-xl font-bold text-primary">Person Name</h4>
                <p class="text-xs text-gray-400 font-medium mb-6"><i class="fas fa-map-marker-alt mr-1"></i> Rajkot, Gujarat</p>
                
                <div class="grid grid-cols-3 gap-8 w-full border-t border-gray-50 pt-6">
                    <div>
                        <p class="text-[10px] font-bold text-gray-300 uppercase mb-1">Package</p>
                        <p class="text-lg font-bold text-gray-800">28</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-300 uppercase mb-1">Leads</p>
                        <p class="text-lg font-bold text-gray-800">643</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-300 uppercase mb-1">Review</p>
                        <p class="text-lg font-bold text-gray-800">76</p>
                    </div>
                </div>
            </div>

            <!-- Team Members -->
            <div class="lg:col-span-3 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h4 class="text-sm font-bold text-gray-800">Team members</h4>
                    <button class="w-6 h-6 bg-orange-50 text-primary rounded-lg flex items-center justify-center">
                        <i class="fas fa-plus text-[10px]"></i>
                    </button>
                </div>
                <div class="space-y-4">
                    <?php for($i=0; $i<3; $i++): ?>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="https://i.pravatar.cc/100?img=<?php echo $i+10; ?>" class="w-10 h-10 rounded-xl mr-3" alt="Member">
                            <div>
                                <p class="text-xs font-bold text-gray-800">Name Surname</p>
                                <p class="text-[10px] text-gray-400">Manager</p>
                            </div>
                        </div>
                        <button class="text-gray-300"><i class="fas fa-ellipsis-v"></i></button>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <!-- Bottom Section: Leads Table & Conversations -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6">
            <!-- Recent Lead Table -->
            <div class="lg:col-span-8 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h4 class="text-lg font-bold text-gray-800">Recent Lead</h4>
                        <p class="text-xs text-green-500 font-bold flex items-center mt-1">
                            <i class="fas fa-check-circle mr-1"></i> 30 done this month
                        </p>
                    </div>
                    <button class="text-gray-300"><i class="fas fa-ellipsis-v"></i></button>
                </div>
                <div class="overflow-x-auto">
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
                            <?php 
                            $projects = [
                                ['name' => 'Chakra Soft UI Version', 'icon' => 'fab fa-xbox text-purple-500', 'budget' => '$14,000', 'prog' => '60%', 'color' => 'bg-primary'],
                                ['name' => 'Add Progress Track', 'icon' => 'fas fa-chart-line text-blue-500', 'budget' => '$3,000', 'prog' => '10%', 'color' => 'bg-blue-500'],
                                ['name' => 'Fix Platform Errors', 'icon' => 'fas fa-exclamation-triangle text-red-500', 'budget' => 'Not set', 'prog' => '100%', 'color' => 'bg-green-500'],
                                ['name' => 'Launch our Mobile App', 'icon' => 'fab fa-spotify text-green-500', 'budget' => '$32,000', 'prog' => '100%', 'color' => 'bg-green-500'],
                            ];
                            foreach($projects as $p): ?>
                            <tr class="group hover:bg-gray-50/50 transition-colors whitespace-nowrap">
                                <td class="py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-lg border border-gray-100 flex items-center justify-center mr-3">
                                            <i class="<?php echo $p['icon']; ?>"></i>
                                        </div>
                                        <span class="text-xs font-bold text-gray-800"><?php echo $p['name']; ?></span>
                                    </div>
                                </td>
                                <td class="py-4">
                                    <div class="flex -space-x-2">
                                        <img src="https://i.pravatar.cc/30?u=1" class="w-6 h-6 rounded-full border-2 border-white">
                                        <img src="https://i.pravatar.cc/30?u=2" class="w-6 h-6 rounded-full border-2 border-white">
                                        <img src="https://i.pravatar.cc/30?u=3" class="w-6 h-6 rounded-full border-2 border-white">
                                    </div>
                                </td>
                                <td class="py-4 text-xs font-bold text-gray-600"><?php echo $p['budget']; ?></td>
                                <td class="py-4">
                                    <div class="w-32">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-[10px] font-bold text-primary"><?php echo $p['prog']; ?></span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="<?php echo $p['color']; ?> h-full" style="width: <?php echo $p['prog']; ?>"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Conversations -->
            <div class="lg:col-span-4 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <h4 class="text-sm font-bold text-gray-800 mb-6">Conversations</h4>
                <div class="space-y-6">
                    <?php for($i=0; $i<5; $i++): ?>
                    <div class="flex items-start justify-between group">
                        <div class="flex items-center">
                            <img src="https://i.pravatar.cc/100?u=<?php echo $i; ?>" class="w-10 h-10 rounded-xl mr-3" alt="User">
                            <div>
                                <p class="text-xs font-bold text-gray-800">Esthera Jackson</p>
                                <p class="text-[10px] text-gray-400 line-clamp-1">Hi! I need more information...</p>
                            </div>
                        </div>
                        <button class="text-[10px] font-bold text-primary uppercase hover:underline opacity-0 group-hover:opacity-100 transition-opacity">Reply</button>
                    </div>
                    <?php endfor; ?>
                </div>
                <div class="flex justify-center space-x-2 mt-8">
                    <div class="w-2 h-2 bg-gray-200 rounded-full"></div>
                    <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                    <div class="w-2 h-2 bg-gray-200 rounded-full"></div>
                </div>
            </div>
        </div>
@endsection
