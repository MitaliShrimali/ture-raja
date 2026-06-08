@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12" x-data="{ tier: 'Premium', status: 'Active' }">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Admin / Management</p>
            <h2 class="font-black text-foreground tracking-tight">Add Paid User</h2>
            <p class="text-muted-text font-medium max-w-xl">
                Expand the network by registering a new paid user. All fields are required for secure portal access.
            </p>
        </div>
        <a href="{{ url('/admin/registered-agents') }}" class="bg-gray-100 hover:bg-gray-200 text-muted-text px-8 py-4 rounded-2xl font-black text-sm transition-all flex items-center gap-3">
            <i data-lucide="users" size="20"></i>
            View All Paid Users
        </a>
    </div>

    <!-- Onboard Agent Form -->
    <form action="{{ url('/admin/agents/store') }}" method="POST" enctype="multipart/form-data">
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
                        <h3 class="text-2xl font-black text-foreground">User Information</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Full Name</label>
                            <input required type="text" name="name" placeholder="e.g. John Doe" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm">
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
                <!-- Company Profile Image Card -->
                <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-8 flex flex-col items-center text-center space-y-4" x-data="{ imagePreview: null }">
                    <h4 class="text-xs font-black text-muted-text uppercase tracking-widest pl-2 self-start">Company Profile Image</h4>
                    
                    <div class="relative mt-2">
                        <!-- Dashed Box / Preview -->
                        <div class="w-32 h-32 rounded-[28px] border-2 border-dashed border-gray-200 bg-gray-50/50 flex items-center justify-center overflow-hidden cursor-pointer hover:bg-gray-50 transition-all" @click="$refs.fileInput.click()">
                            <template x-if="imagePreview">
                                <img :src="imagePreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!imagePreview">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </template>
                        </div>
                        
                        <!-- Floating Upload Button -->
                        <button type="button" @click="$refs.fileInput.click()" class="absolute -bottom-1 -right-1 w-10 h-10 rounded-full bg-[#af3a03] hover:bg-[#8f2f02] text-white flex items-center justify-center shadow-lg transition-all border-2 border-white">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Hidden File Input -->
                    <input type="file" name="logo" x-ref="fileInput" class="hidden" accept="image/*" @change="
                        const file = $event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = (e) => { imagePreview = e.target.result; };
                            reader.readAsDataURL(file);
                        }
                    ">
                    
                    <div class="space-y-1">
                        <p class="text-sm font-bold text-foreground">Company Profile Image</p>
                        <p class="text-[11px] text-muted-text font-bold leading-relaxed max-w-[200px]">
                            Upload a high-resolution logo or headshot. Min 500x500px suggested.
                        </p>
                    </div>
                </div>

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
                    <a href="{{ url('/admin/registered-agents') }}" class="w-full bg-gray-200 hover:bg-gray-300 text-foreground rounded-3xl py-6 font-black text-lg transition-all flex items-center justify-center gap-3">
                        <i data-lucide="x" size="22"></i>
                        Cancel Onboarding
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
