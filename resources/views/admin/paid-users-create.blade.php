@extends('layouts.admin')

@section('admin_title', 'Add Paid User')

@section('content')
<div class="space-y-6 pb-12" x-data="{ imagePreview: null, hideContactDetails: false }">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div class="space-y-1">
            <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Agent Management</p>
            <h2 class="font-black text-foreground tracking-tight text-3xl">Add New Paid User</h2>
            <p class="text-muted-text font-medium text-sm">Onboard a new agency partner to the platform hub ecosystem.</p>
        </div>
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <a href="{{ url('/admin/paid-users') }}" class="w-full sm:w-auto px-6 py-3 bg-gray-100 hover:bg-gray-200 text-muted-text rounded-full text-xs font-black uppercase tracking-widest transition-all text-center">
                Discard Changes
            </a>
            <button type="submit" form="addPaidUserForm" class="w-full sm:w-auto px-8 py-3 bg-primary hover:bg-primary-hover text-white rounded-full text-xs font-black uppercase tracking-widest transition-all shadow-xl shadow-primary/30 text-center">
                Submit Application
            </button>
        </div>
    </div>

    <!-- Main Content Form -->
    <form id="addPaidUserForm" action="{{ url('/admin/paid-users/store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col lg:flex-row gap-6 items-start">
        @csrf
        <!-- Left Sidebar Column -->
        <div class="w-full lg:w-1/3 xl:w-1/4 space-y-6 sticky top-32">
            
            <!-- Profile Image Card -->
            <div class="bg-white rounded-[32px] shadow-premium border border-border-soft p-6 flex flex-col items-center text-center">
                <div class="relative mb-4">
                    <!-- Image Upload Area -->
                    <div class="w-32 h-32 rounded-3xl border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center relative overflow-hidden group cursor-pointer hover:border-primary/50 transition-colors">
                        <template x-if="!imagePreview">
                            <i data-lucide="camera" size="28" class="text-gray-300 group-hover:text-primary/50 transition-colors"></i>
                        </template>
                        <template x-if="imagePreview">
                            <img :src="imagePreview" class="w-full h-full object-cover" />
                        </template>
                        <input 
                            type="file" 
                            name="avatar" 
                            accept="image/*"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                            @change="const file = $event.target.files[0]; if(file){ imagePreview = URL.createObjectURL(file); }"
                        >
                    </div>
                    <!-- Upload Icon Button -->
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white border-2 border-white shadow-lg pointer-events-none">
                        <i data-lucide="upload" size="14"></i>
                    </div>
                </div>
                
                <h3 class="font-black text-foreground mb-1 text-sm">Company Profile Image</h3>
                <p class="text-[10px] text-muted-text font-medium leading-relaxed px-2">Upload a high-resolution logo or headshot. Min 500x500px suggested.</p>
            </div>

            <!-- Platform Options Card -->
            <div class="bg-white rounded-[24px] shadow-premium border border-border-soft p-6 space-y-4">
                <div class="flex items-center gap-2 mb-1">
                    <i data-lucide="sliders-horizontal" size="18" class="text-primary"></i>
                    <h3 class="font-black text-foreground text-sm">Platform Options</h3>
                </div>
                
                <div class="flex items-center justify-between gap-4 p-3 bg-gray-50 rounded-2xl border border-gray-100">
                    <div class="space-y-0.5">
                        <h4 class="text-xs font-black text-foreground">Hide Contact Details</h4>
                        <p class="text-[9px] text-muted-text font-medium leading-tight">Keep private from public listings</p>
                    </div>
                    <button 
                        type="button" 
                        @click="hideContactDetails = !hideContactDetails"
                        class="w-10 h-5 rounded-full relative transition-colors duration-300 focus:outline-none shrink-0"
                        :class="hideContactDetails ? 'bg-primary' : 'bg-gray-300'"
                    >
                        <span 
                            class="absolute top-1 left-1 bg-white w-3 h-3 rounded-full transition-transform duration-300 shadow-sm"
                            :class="hideContactDetails ? 'translate-x-5' : 'translate-x-0'"
                        ></span>
                    </button>
                    <input type="hidden" name="hide_contact_details" :value="hideContactDetails ? '1' : '0'">
                </div>
            </div>
            
        </div>

        <!-- Right Main Column -->
        <div class="w-full lg:w-2/3 xl:w-3/4 space-y-6">
            
            <!-- Agent/Company Information -->
            <div class="bg-white rounded-[32px] shadow-premium border border-border-soft p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-1.5 h-5 bg-primary rounded-full"></div>
                    <h3 class="text-lg font-black text-foreground tracking-tight">Agent/Company Information</h3>
                </div>

                <div class="space-y-4">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-muted-text uppercase tracking-widest pl-1">Company Name</label>
                        <input type="text" name="name" placeholder="Ascent Global Ventures" class="w-full bg-[#F8F9FA] border-none rounded-xl py-3 px-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground text-sm" />
                    </div>

                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-muted-text uppercase tracking-widest pl-1">Mobile Number</label>
                            <div class="flex gap-2 items-center">
                                <div class="relative w-28 shrink-0">
                                    <select class="phone-country-code w-full bg-[#F8F9FA] border-none rounded-xl py-3 px-3 outline-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                        <option value="+91" data-len="10" selected>🇮🇳 +91</option>
                                        <option value="+1" data-len="10">🇺🇸 +1</option>
                                        <option value="+44" data-len="10">🇬🇧 +44</option>
                                        <option value="+62" data-len="11">🇮🇩 +62</option>
                                        <option value="+65" data-len="8">🇸🇬 +65</option>
                                        <option value="+971" data-len="9">🇦🇪 +971</option>
                                        <option value="+61" data-len="9">🇦🇺 +61</option>
                                        <option value="+66" data-len="9">🇹🇭 +66</option>
                                        <option value="+60" data-len="10">🇲🇾 +60</option>
                                    </select>
                                </div>
                                <div class="relative flex-grow">
                                    <input type="tel" required placeholder="Mobile Number *"
                                        class="phone-number-val w-full bg-[#F8F9FA] border-none rounded-xl py-3 px-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground text-sm">
                                </div>
                            </div>
                            <input type="hidden" class="phone-full-val" name="mobile_number">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-muted-text uppercase tracking-widest pl-1">Phone Number</label>
                            <div class="flex gap-2 items-center">
                                <div class="relative w-28 shrink-0">
                                    <select class="phone-country-code w-full bg-[#F8F9FA] border-none rounded-xl py-3 px-3 outline-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                        <option value="+91" data-len="10" selected>🇮🇳 +91</option>
                                        <option value="+1" data-len="10">🇺🇸 +1</option>
                                        <option value="+44" data-len="10">🇬🇧 +44</option>
                                        <option value="+62" data-len="11">🇮🇩 +62</option>
                                        <option value="+65" data-len="8">🇸🇬 +65</option>
                                        <option value="+971" data-len="9">🇦🇪 +971</option>
                                        <option value="+61" data-len="9">🇦🇺 +61</option>
                                        <option value="+66" data-len="9">🇹🇭 +66</option>
                                        <option value="+60" data-len="10">🇲🇾 +60</option>
                                    </select>
                                </div>
                                <div class="relative flex-grow">
                                    <input type="tel" required placeholder="Phone Number *"
                                        class="phone-number-val w-full bg-[#F8F9FA] border-none rounded-xl py-3 px-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground text-sm">
                                </div>
                            </div>
                            <input type="hidden" class="phone-full-val" name="phone_number">
                        </div>

                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-muted-text uppercase tracking-widest pl-1">Official Email</label>
                        <input type="email" name="email" placeholder="admin@company.com" class="w-full bg-[#F8F9FA] border-none rounded-xl py-3 px-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground text-sm" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-muted-text uppercase tracking-widest pl-1">Country</label>
                            <input type="text" name="country" placeholder="United States" class="w-full bg-[#F8F9FA] border-none rounded-xl py-3 px-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground text-sm" />
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-muted-text uppercase tracking-widest pl-1">State/Province</label>
                            <input type="text" name="state" placeholder="California" class="w-full bg-[#F8F9FA] border-none rounded-xl py-3 px-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground text-sm" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-muted-text uppercase tracking-widest pl-1">City</label>
                            <input type="text" name="city" placeholder="San Francisco" class="w-full bg-[#F8F9FA] border-none rounded-xl py-3 px-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground text-sm" />
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-muted-text uppercase tracking-widest pl-1">Pincode/Zip</label>
                            <input type="text" name="pincode" placeholder="94105" class="w-full bg-[#F8F9FA] border-none rounded-xl py-3 px-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground text-sm" />
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-muted-text uppercase tracking-widest pl-1">Full Address</label>
                        <textarea name="address" rows="2" placeholder="Suite 400, 101 California St." class="w-full bg-[#F8F9FA] border-none rounded-xl py-3 px-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground text-sm resize-none"></textarea>
                    </div>
                </div>
            </div>

            <!-- Social & Web Presence -->
            <div class="bg-white rounded-[32px] shadow-premium border border-border-soft p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-1.5 h-5 bg-blue-500 rounded-full"></div>
                    <h3 class="text-lg font-black text-foreground tracking-tight">Social & Web Presence</h3>
                </div>

                <div class="space-y-4">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-muted-text uppercase tracking-widest pl-1">About Us / Bio</label>
                        <textarea name="bio" rows="2" placeholder="Brief description of the agency's mission and history..." class="w-full bg-[#F8F9FA] border-none rounded-xl py-3 px-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground text-sm resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="relative group">
                            <i data-lucide="facebook" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" size="16"></i>
                            <input type="url" name="facebook_url" placeholder="Facebook URL" class="w-full bg-[#F8F9FA] border-none rounded-xl py-3 pl-10 pr-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground text-sm" />
                        </div>
                        <div class="relative group">
                            <i data-lucide="twitter" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" size="16"></i>
                            <input type="url" name="twitter_url" placeholder="Twitter URL" class="w-full bg-[#F8F9FA] border-none rounded-xl py-3 pl-10 pr-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground text-sm" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="relative group">
                            <i data-lucide="linkedin" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" size="16"></i>
                            <input type="url" name="linkedin_url" placeholder="LinkedIn URL" class="w-full bg-[#F8F9FA] border-none rounded-xl py-3 pl-10 pr-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground text-sm" />
                        </div>
                        <div class="relative group">
                            <i data-lucide="globe-2" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" size="16"></i>
                            <input type="url" name="google_plus_url" placeholder="Google Plus" class="w-full bg-[#F8F9FA] border-none rounded-xl py-3 pl-10 pr-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground text-sm" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="relative group">
                            <i data-lucide="instagram" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" size="16"></i>
                            <input type="url" name="instagram_url" placeholder="Instagram URL" class="w-full bg-[#F8F9FA] border-none rounded-xl py-3 pl-10 pr-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground text-sm" />
                        </div>
                        <div class="relative group">
                            <i data-lucide="message-circle" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" size="16"></i>
                            <input type="text" name="skype_id" placeholder="Skype ID" class="w-full bg-[#F8F9FA] border-none rounded-xl py-3 pl-10 pr-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground text-sm" />
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-muted-text uppercase tracking-widest pl-1">Website URL</label>
                        <input type="url" name="website_url" placeholder="https://www.example.com" class="w-full bg-[#F8F9FA] border-none rounded-xl py-3 px-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground text-sm" />
                    </div>
                </div>
            </div>

            <!-- Primary Contact Person -->
            <div class="bg-[#F2EFEA] rounded-[32px] shadow-sm border border-border-soft p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-1.5 h-5 bg-gray-700 rounded-full"></div>
                    <h3 class="text-lg font-black text-foreground tracking-tight">Primary Contact Person</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-muted-text uppercase tracking-widest pl-1">Full Name</label>
                        <input type="text" name="contact_name" placeholder="Johnathan Doe" class="w-full bg-white border-none rounded-xl py-3 px-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground text-sm shadow-sm" />
                    </div>
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-muted-text uppercase tracking-widest pl-1">Personal Email</label>
                        <input type="email" name="contact_email" placeholder="j.doe@company.com" class="w-full bg-white border-none rounded-xl py-3 px-5 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground text-sm shadow-sm" />
                    </div>
                </div>
            </div>

            <!-- Bottom Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-between pt-4 gap-4">
                <p class="text-[10px] text-muted-text font-medium">* All fields are required for premium certification</p>
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <a href="{{ url('/admin/paid-users') }}" class="px-6 py-3 text-[11px] font-black text-foreground uppercase tracking-widest hover:text-primary transition-colors">Cancel</a>
                    <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-primary hover:bg-primary-hover text-white rounded-full text-[11px] font-black uppercase tracking-widest transition-all shadow-xl shadow-primary/30">
                        Save Profile
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
