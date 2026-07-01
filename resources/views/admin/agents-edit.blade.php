@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12" x-data="{ 
    tier: '{{ $agent->tier ?? 'Premium' }}', 
    status: '{{ $agent->status ?? 'Active' }}',
    showCustomPlanModal: false, 
    customAgentSearch: '{{ $agent->name }}', 
    customPlanTier: '{{ $agent->plan_name ?? '' }}', 
    customSacHsn: '', 
    customSaleType: 'Direct Sale' 
}">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-border-soft pb-6">
        <div class="flex items-center gap-4">
            <a href="{{ url('/admin/registered-agents') }}" class="w-12 h-12 rounded-2xl bg-gray-100 hover:bg-gray-200 text-muted-text flex items-center justify-center transition-all">
                <i data-lucide="arrow-left" size="20"></i>
            </a>
            <div class="space-y-1">
                <h2 class="text-3xl font-black text-foreground tracking-tight">Edit Travel Agent</h2>
                <p class="text-muted-text font-semibold text-sm">Update agency partner details in the platform hub ecosystem.</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ url('/admin/registered-agents') }}" class="px-6 py-3.5 border-2 border-dashed border-gray-200 hover:border-gray-300 text-gray-500 rounded-2xl font-bold text-sm transition-all flex items-center gap-2">
                Discard Changes
            </a>
            <button type="submit" form="agentForm" class="px-6 py-3.5 bg-[#af3a03] hover:bg-[#8f2f02] text-white rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-2">
                Save Changes
            </button>
        </div>
    </div>

    <!-- Onboard Agent Form -->
    <form id="agentForm" action="{{ url('/admin/agents/update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $agent->id }}" />
        <input type="hidden" name="tier" x-model="tier" />
        <input type="hidden" name="status" x-model="status" />
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- COLUMN 1: Company Profile Image & Service Configuration (lg:col-span-3) -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Company Profile Image Card -->
                <div class="bg-white rounded-[32px] border border-border-soft p-6 flex flex-col items-center text-center space-y-4" x-data="{ imagePreview: '{{ $agent->logo ? asset($agent->logo) : null }}' }">
                    <div class="relative">
                        <!-- Dashed Box / Preview -->
                        <div class="w-32 h-32 rounded-[28px] border-2 border-dashed border-orange-200 bg-orange-50/10 flex items-center justify-center overflow-hidden cursor-pointer hover:bg-orange-50/20 transition-all" @click="$refs.fileInput.click()">
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
                        <button type="button" @click="$refs.fileInput.click()" class="absolute -bottom-1 -right-1 w-9 h-9 rounded-full bg-primary hover:bg-primary-hover text-white flex items-center justify-center shadow-lg transition-all border-2 border-white">
                            <i data-lucide="upload" size="14"></i>
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
                        <p class="text-sm font-black text-gray-800">Company Profile Image</p>
                        <p class="text-[11px] text-muted-text font-semibold leading-relaxed">
                            Upload a high-resolution logo or headshot. Min 500x500px suggested.
                        </p>
                    </div>
                </div>

                <!-- Service Configuration Card -->
                <div class="bg-white rounded-[32px] border border-border-soft p-6 space-y-6">
                    <div class="flex items-center gap-3">
                        <i data-lucide="sliders" size="20" class="text-primary"></i>
                        <h3 class="text-lg font-black text-gray-800">Service Configuration</h3>
                    </div>

                    <div class="space-y-4">
                        <!-- Service Guaranteed -->
                        <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100 flex items-center justify-between">
                            <div class="space-y-0.5">
                                <p class="text-sm font-black text-gray-800">Service Guaranteed</p>
                                <p class="text-[10px] text-muted-text font-semibold uppercase">Enable automated SLA</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="service_guaranteed" value="1" class="sr-only peer" {{ $agent->service_guaranteed ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                        </div>

                        <!-- Bill Generate -->
                        <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100 flex items-center justify-between">
                            <div class="space-y-0.5">
                                <p class="text-sm font-black text-gray-800">Bill Generate</p>
                                <p class="text-[10px] text-muted-text font-semibold uppercase">Enable billing & invoices</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="generate_bill" value="1" class="sr-only peer" {{ $agent->generate_bill ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- COLUMN 2: Agent/Company Information & Account Status (lg:col-span-5) -->
            <div class="lg:col-span-5 space-y-8">
                <!-- Agent/Company Information Section -->
                <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                        <h3 class="text-xl font-black text-gray-800">Agent/Company Information</h3>
                    </div>

                    <div class="space-y-5">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider pl-1">Company Name</label>
                            <input required type="text" name="name" value="{{ $agent->name }}" placeholder="Ascent Global Ventures" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-3.5 px-5 outline-none transition-all font-bold text-foreground text-sm">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider pl-1">Mobile Number</label>
                                <input required type="text" name="phone" value="{{ $agent->phone }}" placeholder="+91 00000 00000" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-3.5 px-5 outline-none transition-all font-bold text-foreground text-sm">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider pl-1">Phone Number</label>
                                <input type="text" name="landline" value="{{ $agent->landline }}" placeholder="+1 (555) 123-4567" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-3.5 px-5 outline-none transition-all font-bold text-foreground text-sm">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider pl-1">Official Email</label>
                            <input required type="email" name="email" value="{{ $agent->email }}" placeholder="admin@company.com" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-3.5 px-5 outline-none transition-all font-bold text-foreground text-sm">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider pl-1">Country</label>
                                <input type="text" name="country" value="{{ $agent->country }}" placeholder="United States" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-3.5 px-5 outline-none transition-all font-bold text-foreground text-sm">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider pl-1">State/Province</label>
                                <input type="text" name="state" value="{{ $agent->state }}" placeholder="California" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-3.5 px-5 outline-none transition-all font-bold text-foreground text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider pl-1">City</label>
                                <input type="text" name="city" value="{{ $agent->city }}" placeholder="San Francisco" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-3.5 px-5 outline-none transition-all font-bold text-foreground text-sm">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider pl-1">Pincode/Zip</label>
                                <input type="text" name="pincode" value="{{ $agent->pincode }}" placeholder="94105" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-3.5 px-5 outline-none transition-all font-bold text-foreground text-sm">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider pl-1">Full Address</label>
                            <textarea name="address" rows="3" placeholder="Suite 400, 101 California St." class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-3.5 px-5 outline-none transition-all font-bold text-foreground text-sm resize-none">{{ $agent->address }}</textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider pl-1">Pending Bookings/Leads</label>
                                <input required type="number" name="pending" min="0" value="{{ $agent->pending ?? 0 }}" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-3.5 px-5 outline-none transition-all font-bold text-foreground text-sm">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider pl-1">Approved Bookings/Leads</label>
                                <input required type="number" name="approved" min="0" value="{{ $agent->approved ?? 0 }}" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-3.5 px-5 outline-none transition-all font-bold text-foreground text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Status Section -->
                <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-6">
                    <div class="flex items-center gap-3">
                        <h3 class="text-lg font-black text-gray-800">Account Status</h3>
                    </div>

                    <div class="bg-gray-100 p-1.5 rounded-2xl flex max-w-sm">
                        <button type="button" @click="status = 'Active'" :class="status === 'Active' ? 'bg-primary text-white shadow-lg' : 'text-gray-500 hover:text-gray-800'" class="flex-1 py-3 px-6 rounded-xl text-sm font-black transition-all">Active</button>
                        <button type="button" @click="status = 'Inactive'" :class="status === 'Inactive' ? 'bg-primary text-white shadow-lg' : 'text-gray-500 hover:text-gray-800'" class="flex-1 py-3 px-6 rounded-xl text-sm font-black transition-all">Inactive</button>
                    </div>
                </div>
            </div>

            <!-- COLUMN 3: Social & Web Presence & Tier Selection (lg:col-span-4) -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Social & Web Presence Card -->
                <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                        <h3 class="text-xl font-black text-gray-800">Social & Web Presence</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider pl-1">About Us / Bio</label>
                            <textarea name="about" rows="3" placeholder="Brief description of the agency's mission and history..." class="w-full border border-gray-200 rounded-2xl py-3.5 px-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-foreground text-sm resize-none">{{ $agent->about }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="relative flex items-center">
                                <div class="absolute left-4 text-gray-400 flex items-center justify-center pointer-events-none">
                                    <i data-lucide="facebook" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="facebook" value="{{ $agent->facebook }}" placeholder="Facebook URL" class="w-full border border-gray-200 rounded-2xl py-3 px-4 pl-11 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-xs">
                            </div>
                            <div class="relative flex items-center">
                                <div class="absolute left-4 text-gray-400 flex items-center justify-center pointer-events-none">
                                    <i data-lucide="twitter" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="twitter" value="{{ $agent->twitter }}" placeholder="Twitter URL" class="w-full border border-gray-200 rounded-2xl py-3 px-4 pl-11 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-xs">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="relative flex items-center">
                                <div class="absolute left-4 text-gray-400 flex items-center justify-center pointer-events-none">
                                    <i data-lucide="linkedin" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="linkedin" value="{{ $agent->linkedin }}" placeholder="LinkedIn URL" class="w-full border border-gray-200 rounded-2xl py-3 px-4 pl-11 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-xs">
                            </div>
                            <div class="relative flex items-center">
                                <div class="absolute left-4 text-gray-400 flex items-center justify-center pointer-events-none">
                                    <i data-lucide="globe" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="google_plus" value="{{ $agent->google_plus }}" placeholder="Google Plus" class="w-full border border-gray-200 rounded-2xl py-3 px-4 pl-11 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-xs">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="relative flex items-center">
                                <div class="absolute left-4 text-gray-400 flex items-center justify-center pointer-events-none">
                                    <i data-lucide="instagram" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="instagram" value="{{ $agent->instagram }}" placeholder="Instagram URL" class="w-full border border-gray-200 rounded-2xl py-3 px-4 pl-11 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-xs">
                            </div>
                            <div class="relative flex items-center">
                                <div class="absolute left-4 text-gray-400 flex items-center justify-center pointer-events-none">
                                    <i data-lucide="message-square" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="skype" value="{{ $agent->skype }}" placeholder="Skype ID" class="w-full border border-gray-200 rounded-2xl py-3 px-4 pl-11 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-xs">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider pl-1">Website URL</label>
                            <div class="relative flex items-center">
                                <div class="">
                                    <i data-lucide="globe" class="w-4 h-4"></i>
                                </div>
                                <input type="text" name="website" value="{{ $agent->website }}" placeholder="https://www.example.com" class="w-full border border-gray-200 rounded-2xl py-3.5 pl-11 pr-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-foreground text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tier Selection Card -->
                <div class="bg-white rounded-[32px] border border-border-soft p-8 space-y-6">
                    <h3 class="text-lg font-black text-gray-800 pl-1">Tier Selection</h3>
                    <div class="space-y-3">
                        <button type="button" @click="tier = 'Standard'" :class="tier === 'Standard' ? 'border-primary bg-primary/5 ring-2 ring-primary/20' : 'border-gray-200 bg-white'" class="w-full p-4 rounded-2xl text-left border transition-all flex flex-col">
                            <span class="text-sm font-black transition-colors" :class="tier === 'Standard' ? 'text-primary' : 'text-gray-800'">Standard</span>
                            <span class="text-[10px] text-muted-text font-semibold">Up to 50 bookings/mo</span>
                        </button>
                        <button type="button" @click="tier = 'Premium'" :class="tier === 'Premium' ? 'border-primary bg-primary/5 ring-2 ring-primary/20' : 'border-gray-200 bg-white'" class="w-full p-4 rounded-2xl text-left border transition-all flex flex-col">
                            <span class="text-sm font-black transition-colors" :class="tier === 'Premium' ? 'text-primary' : 'text-gray-800'">Premium</span>
                            <span class="text-[10px] text-muted-text font-semibold">Unlimited bookings & VIP support</span>
                        </button>
                        <button type="button" @click="tier = 'Enterprise'" :class="tier === 'Enterprise' ? 'border-primary bg-primary/5 ring-2 ring-primary/20' : 'border-gray-200 bg-white'" class="w-full p-4 rounded-2xl text-left border transition-all flex flex-col">
                            <span class="text-sm font-black transition-colors" :class="tier === 'Enterprise' ? 'text-primary' : 'text-gray-800'">Enterprise</span>
                            <span class="text-[10px] text-muted-text font-semibold">Custom white-label solutions</span>
                        </button>
                        <button type="button" @click="tier = 'Customise'; showCustomPlanModal = true" :class="tier === 'Customise' ? 'border-primary bg-primary/5 ring-2 ring-primary/20' : 'border-gray-200 bg-white'" class="w-full p-4 rounded-2xl text-left border transition-all flex flex-col">
                            <span class="text-sm font-black transition-colors" :class="tier === 'Customise' ? 'text-primary' : 'text-gray-800'">Customise</span>
                            <span class="text-[10px] text-muted-text font-semibold">Custom package</span>
                        </button>
                    </div>
                </div>

                <!-- Bottom Form Buttons -->
                <div class="flex items-center justify-end gap-4">
                    <a href="{{ url('/admin/registered-agents') }}" class="px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-3xl font-black text-sm transition-all flex items-center justify-center gap-2">
                        Cancel Changes
                    </a>
                    <button type="submit" class="px-6 py-4 bg-primary hover:bg-primary-hover text-white rounded-3xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center justify-center gap-2 group">
                        Save Changes
                        <i data-lucide="save" size="16" class="group-hover:scale-110 transition-transform"></i>
                    </button>
                </div>
            </div>

        </div>

        <!-- Custom Plan Modal -->
        <div x-show="showCustomPlanModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/40 backdrop-blur-sm p-4" x-cloak x-transition>
            <div @click.away="showCustomPlanModal = false" class="bg-white w-full max-w-3xl rounded-[32px] shadow-2xl p-8 relative max-h-[90vh] overflow-y-auto">
                
                <div class="space-y-2 mb-8">
                    <div class="flex items-center gap-2 text-[#e85d26] font-bold text-xs tracking-widest uppercase">
                        <i data-lucide="shield" size="14"></i> Administrative Task
                    </div>
                    <h2 class="text-4xl font-black text-gray-900">Custom User Plan</h2>
                    <p class="text-gray-500 font-medium">Configure and assign a specialized subscription tier to a travel partner. All fields are mandatory for billing integrity.</p>
                </div>

                <div class="space-y-8">
                    <!-- 1. AGENT SELECTION -->
                    <div class="space-y-3">
                        <label class="text-xs font-black text-gray-600 uppercase tracking-widest">1. Agent Selection</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                <i data-lucide="search" size="20"></i>
                            </div>
                            <input type="text" name="custom_agent_search" x-model="customAgentSearch" placeholder="Search by name or UID..." class="w-full bg-[#F8F8F8] border-none rounded-2xl py-4 pl-12 pr-4 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-gray-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- 2. PLAN TIER -->
                        <div class="space-y-3">
                            <label class="text-xs font-black text-gray-600 uppercase tracking-widest">2. Plan Tier</label>
                            <select name="custom_plan_tier" x-model="customPlanTier" class="w-full bg-[#F8F8F8] border-none rounded-2xl py-4 px-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-gray-800 appearance-none">
                                <option value="">Select available tier</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->name }}">{{ $plan->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 3. SAC/HSN CODE -->
                        <div class="space-y-3">
                            <label class="text-xs font-black text-gray-600 uppercase tracking-widest">3. SAC/HSN Code</label>
                            <input type="text" name="custom_sac_hsn" x-model="customSacHsn" placeholder="e.g. 998511" class="w-full bg-[#F8F8F8] border-none rounded-2xl py-4 px-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-semibold text-gray-800">
                        </div>
                    </div>

                    <!-- 4. SALE TYPE -->
                    <div class="space-y-3">
                        <label class="text-xs font-black text-gray-600 uppercase tracking-widest">4. Sale Type</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="relative cursor-pointer block">
                                <input type="radio" name="custom_sale_type" value="Direct Sale" x-model="customSaleType" class="sr-only">
                                <div class="p-5 bg-[#F8F8F8] rounded-2xl border-2 transition-all flex items-center justify-between" :class="customSaleType === 'Direct Sale' ? 'border-primary bg-primary/5' : 'border-transparent'">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="store" size="24" :class="customSaleType === 'Direct Sale' ? 'text-primary' : 'text-gray-400'"></i>
                                        <span class="font-black text-gray-900">Direct Sale</span>
                                    </div>
                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors" :class="customSaleType === 'Direct Sale' ? 'border-primary' : 'border-gray-300'">
                                        <div class="w-3 h-3 rounded-full bg-primary transition-opacity" :class="customSaleType === 'Direct Sale' ? 'opacity-100' : 'opacity-0'"></div>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="relative cursor-pointer block">
                                <input type="radio" name="custom_sale_type" value="Partner Ref" x-model="customSaleType" class="sr-only">
                                <div class="p-5 bg-[#F8F8F8] rounded-2xl border-2 transition-all flex items-center justify-between" :class="customSaleType === 'Partner Ref' ? 'border-primary bg-primary/5' : 'border-transparent'">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="users" size="24" :class="customSaleType === 'Partner Ref' ? 'text-primary' : 'text-gray-400'"></i>
                                        <span class="font-black text-gray-900">Partner Ref</span>
                                    </div>
                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors" :class="customSaleType === 'Partner Ref' ? 'border-primary' : 'border-gray-300'">
                                        <div class="w-3 h-3 rounded-full bg-primary transition-opacity" :class="customSaleType === 'Partner Ref' ? 'opacity-100' : 'opacity-0'"></div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button type="button" @click="document.getElementById('agentForm').submit()" class="flex-1 bg-[#e85d26] hover:opacity-90 text-white py-4 rounded-2xl font-black text-lg transition-colors flex items-center justify-center gap-2">
                            <i data-lucide="shield-check" size="20"></i> Assign Plan
                        </button>
                        <button type="button" @click="showCustomPlanModal = false" class="px-8 py-4 bg-[#E5E5E5] hover:bg-gray-300 text-gray-800 rounded-2xl font-black text-lg transition-colors">
                            Discard
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection
