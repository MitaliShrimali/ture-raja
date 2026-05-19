@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12" x-data="{ tier: 'Premium', status: 'Active' }">
    <!-- Header -->
    <div class="space-y-2">
        <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Admin / Management</p>
        <h2 class="font-black text-foreground tracking-tight">Onboard New Agent</h2>
        <p class="text-muted-text font-medium max-w-2xl">
            Expand the Horizon network by registering a new premium travel agent partner. All fields are required for secure portal access.
        </p>
    </div>

    <!-- Onboard Agent Form -->
    <form action="{{ url('/admin/agents/store') }}" method="POST">
        @csrf
        <input type="hidden" name="tier" x-model="tier" />
        <input type="hidden" name="status" x-model="status" />
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Main Form Area -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Entity Information Section -->
                <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                            <i data-lucide="building-2" size="22"></i>
                        </div>
                        <h3 class="text-2xl font-black text-foreground">Entity Information</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Travel Agent Name</label>
                            <input required type="text" name="name" placeholder="e.g. Atlas Global Travels" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Email Address</label>
                            <div class="relative group">
                                <i data-lucide="mail" class="absolute left-6 top-1/2 -translate-y-1/2 text-muted-text group-focus-within:text-primary transition-colors" size="18"></i>
                                <input required type="email" name="email" placeholder="contact@agency.com" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 pl-14 pr-6 outline-none transition-all font-bold text-foreground text-sm">
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Mobile Number</label>
                            <div class="relative group">
                                <i data-lucide="phone" class="absolute left-6 top-1/2 -translate-y-1/2 text-muted-text group-focus-within:text-primary transition-colors" size="18"></i>
                                <input required type="text" name="phone" placeholder="+1 (555) 000-0000" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 pl-14 pr-6 outline-none transition-all font-bold text-foreground text-sm">
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Assigned Region</label>
                            <select name="region" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm">
                                <option value="North America">North America</option>
                                <option value="Europe">Europe</option>
                                <option value="Asia Pacific" selected>Asia Pacific</option>
                                <option value="Middle East">Middle East</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Service Configuration Section -->
                <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                            <i data-lucide="zap" size="22"></i>
                        </div>
                        <h3 class="text-2xl font-black text-foreground">Service Configuration</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-[#FDFDFD] p-6 rounded-[28px] border border-border-soft flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-sm font-black text-foreground">Service Guaranteed</p>
                                <p class="text-[10px] text-muted-text font-bold uppercase">Enable automated SLA monitoring</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="service_guaranteed" value="1" class="sr-only peer" checked>
                                <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                        </div>

                        <div class="bg-[#FDFDFD] p-6 rounded-[28px] border border-border-soft flex items-center justify-between">
                            <div class="space-y-1">
                                <p class="text-sm font-black text-foreground">API Access</p>
                                <p class="text-[10px] text-muted-text font-bold uppercase">Allow third-party integrations</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="api_access" value="1" class="sr-only peer">
                                <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Controls Area -->
            <div class="space-y-8">
                <!-- Tier Selection Card -->
                <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-8 space-y-6">
                    <h4 class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Tier Selection</h4>
                    <div class="space-y-3">
                        <button type="button" @click="tier = 'Standard'" :class="tier === 'Standard' ? 'border-primary bg-primary/[0.02]' : 'border-gray-100 bg-white'" class="w-full p-5 rounded-3xl text-left border-2 transition-all">
                            <p class="text-sm font-black text-foreground">Standard</p>
                            <p class="text-[10px] text-muted-text font-bold uppercase mt-1">Up to 50 bookings/mo</p>
                        </button>
                        <button type="button" @click="tier = 'Premium'" :class="tier === 'Premium' ? 'border-primary bg-primary/[0.02]' : 'border-gray-100 bg-white'" class="w-full p-5 rounded-3xl text-left border-2 transition-all">
                            <p class="text-sm font-black text-foreground">Premium</p>
                            <p class="text-[10px] text-muted-text font-bold uppercase mt-1">Unlimited bookings & VIP support</p>
                        </button>
                        <button type="button" @click="tier = 'Enterprise'" :class="tier === 'Enterprise' ? 'border-primary bg-primary/[0.02]' : 'border-gray-100 bg-white'" class="w-full p-5 rounded-3xl text-left border-2 transition-all">
                            <p class="text-sm font-black text-foreground">Enterprise</p>
                            <p class="text-[10px] text-muted-text font-bold uppercase mt-1">Custom white-label solutions</p>
                        </button>
                    </div>
                </div>

                <!-- Account Status Card -->
                <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-8 space-y-6">
                    <h4 class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Account Status</h4>
                    <div class="bg-gray-100 p-1.5 rounded-2xl flex">
                        <button type="button" @click="status = 'Active'" :class="status === 'Active' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-muted-text hover:text-foreground'" class="flex-1 py-3 px-6 rounded-xl text-sm font-black transition-all">Active</button>
                        <button type="button" @click="status = 'Inactive'" :class="status === 'Inactive' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-muted-text hover:text-foreground'" class="flex-1 py-3 px-6 rounded-xl text-sm font-black transition-all">Inactive</button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-4">
                    <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white rounded-3xl py-6 font-black text-lg shadow-xl shadow-primary/20 transition-all flex items-center justify-center gap-3 group">
                        <i data-lucide="rocket" size="22" class="group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                        Create User Account
                    </button>
                    <button type="button" onclick="history.back();" class="w-full bg-gray-200 hover:bg-gray-300 text-foreground rounded-3xl py-6 font-black text-lg transition-all flex items-center justify-center gap-3">
                        <i data-lucide="x" size="22"></i>
                        Cancel Onboarding
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Existing Agents List -->
    <div class="mt-16 space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-black text-foreground">Registered Agents</h3>
            <p class="text-sm text-muted-text font-medium">Viewing all active and inactive partners</p>
        </div>

        <div class="bg-white rounded-[32px] shadow-soft border border-border-soft overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-border-soft">
                    <tr>
                        <th class="py-5 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Agent Name</th>
                        <th class="py-5 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Region & Tier</th>
                        <th class="py-5 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Status</th>
                        <th class="py-5 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($agents ?? [] as $agent)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-5 px-8">
                                <p class="text-sm font-bold text-foreground">{{ $agent->name }}</p>
                                <p class="text-[10px] text-muted-text font-medium">{{ $agent->email }} • {{ $agent->phone }}</p>
                            </td>
                            <td class="py-5 px-8">
                                <p class="text-sm font-bold text-foreground">{{ $agent->region }}</p>
                                <p class="text-[10px] text-primary font-black uppercase tracking-wider">{{ $agent->tier }}</p>
                            </td>
                            <td class="py-5 px-8">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $agent->status === 'Active' ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-500' }}">
                                    {{ $agent->status }}
                                </span>
                            </td>
                            <td class="py-5 px-8">
                                <div class="flex items-center gap-3">
                                    <a href="{{ url('/admin/agents/toggle/' . $agent->id) }}" class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-muted-text hover:text-primary transition-colors">
                                        <i data-lucide="power" size="14"></i>
                                    </a>
                                    <a href="{{ url('/admin/agents/delete/' . $agent->id) }}" class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-400 hover:text-red-500 transition-colors" onclick="return confirm('Are you sure you want to remove this agent?');">
                                        <i data-lucide="trash-2" size="14"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-sm font-bold text-muted-text">No agents registered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
