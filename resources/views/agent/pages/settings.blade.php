@extends('agent.layouts.app')

@section('title', 'Settings - Tour Raja Agent')

@section('content')
@php
    $agent = \DB::table('agents')->where('id', session('agent_id'))->first();
@endphp

        <div class="max-w-6xl mx-auto">
            <!-- Profile Completion status Bar -->
            @if(isset($profileCompletionPercentage))
                <div class="bg-white rounded-[2rem] border border-gray-100 p-6 mb-8 shadow-sm">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <h3 class="text-sm font-black text-gray-800 tracking-tight flex items-center gap-2">
                                <i class="fas fa-user-shield text-primary"></i> Profile Completion status
                            </h3>
                            <p id="progress-status-desc" class="text-xs text-gray-400 font-medium mt-1">
                                @if($profileCompletionPercentage < 80)
                                    <span class="text-red-500 font-bold"><i class="fas fa-exclamation-triangle"></i> Locked:</span> Please complete at least 80% of your details to unlock full access.
                                @else
                                    <span class="text-green-500 font-bold"><i class="fas fa-check-circle"></i> Unlocked:</span> Your profile is complete and all agent features are unlocked!
                                @endif
                            </p>
                        </div>
                        <div class="flex-grow max-w-md w-full">
                            <div class="flex items-center justify-between text-xs font-bold text-gray-600 mb-2">
                                <span>Verification Progress</span>
                                <span id="progress-percentage-text" class="{{ $profileCompletionPercentage < 80 ? 'text-red-500' : 'text-green-500' }}">{{ $profileCompletionPercentage }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                                <div id="progress-bar-fill" class="h-full transition-all duration-500 rounded-full {{ $profileCompletionPercentage < 80 ? 'bg-red-500' : 'bg-green-500' }}"
                                     style="width: {{ $profileCompletionPercentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Settings Navigation -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 space-y-2 sticky top-8">
                        <button onclick="switchTab('general')" id="tab-general" class="tab-btn w-full flex items-center space-x-3 px-4 py-3 rounded-xl bg-primary text-white shadow-lg shadow-orange-100 transition-all font-bold text-sm">
                            <i class="fas fa-user-circle"></i>
                            <span>General settings</span>
                        </button>
                        <button onclick="switchTab('security')" id="tab-security" class="tab-btn w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-gray-50 transition-all font-bold text-sm">
                            <i class="fas fa-lock"></i>
                            <span>Security</span>
                        </button>
                        <button onclick="switchTab('notifications')" id="tab-notifications" class="tab-btn w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-gray-50 transition-all font-bold text-sm">
                            <i class="fas fa-bell"></i>
                            <span>Notifications</span>
                        </button>
                        <button onclick="switchTab('delete')" id="tab-delete" class="tab-btn w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-red-400 hover:bg-red-50 transition-all font-bold text-sm">
                            <i class="fas fa-trash-alt"></i>
                            <span>Delete Account</span>
                        </button>
                    </div>
                </div>

                <!-- Settings Content -->
                <div class="lg:col-span-9 space-y-8">
                    
                    <!-- General Settings Section -->
                    <div id="content-general" class="tab-content space-y-8">
                        <form action="{{ route('agent.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                            @csrf
                            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                                <!-- Left Side: Identity & Location -->
                                <div class="lg:col-span-8 space-y-8">
                                    <!-- Agency Identity -->
                                    <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100">
                                        <div class="flex items-center mb-8">
                                            <div class="w-1.5 h-6 bg-orange-800 rounded-full mr-4"></div>
                                            <h4 class="text-lg font-bold text-gray-800 tracking-tight">Agency Identity</h4>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 tracking-widest">COMPANY NAME <span class="text-red-500">*</span></label>
                                                <input type="text" name="name" value="{{ $agent->agency_name ?? $agent->name ?? '' }}" required class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 tracking-widest">SINCE</label>
                                                <input type="text" name="since" value="{{ $agent->since ?? '' }}" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 tracking-widest">PRIMARY MOBILE <span class="text-red-500">*</span></label>
                                                <div class="flex gap-2 items-center">
                                                    <div class="relative w-20 shrink-0">
                                                        <select class="phone-country-code w-full px-2 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20 appearance-none">
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
                                                        <input type="tel" required placeholder="Primary Mobile *"
                                                            class="phone-number-val w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                                    </div>
                                                    <input type="hidden" class="phone-full-val" name="phone" value="{{ $agent->phone ?? '' }}">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 tracking-widest">SECONDARY MOBILE</label>
                                                <div class="flex gap-2 items-center">
                                                    <div class="relative w-20 shrink-0">
                                                        <select class="phone-country-code w-full px-2 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20 appearance-none">
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
                                                        <input type="tel" placeholder="Secondary Mobile"
                                                            class="phone-number-val w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                                    </div>
                                                    <input type="hidden" class="phone-full-val" name="secondary_phone" value="{{ $agent->secondary_phone ?? '' }}">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 tracking-widest">LANDLINE NUMBER</label>
                                                <input type="text" name="landline" value="{{ $agent->landline ?? '' }}" placeholder="e.g. +91-79-12345678" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 tracking-widest">OFFICIAL EMAIL ADDRESS <span class="text-red-500">*</span></label>
                                                <input type="email" name="email" required pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$" title="Please enter a valid email ending with a proper domain like .com" value="{{ $agent->email ?? '' }}" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 tracking-widest">WEBSITE URL</label>
                                                <input type="text" name="website" value="{{ $agent->website ?? '' }}" placeholder="e.g. www.youragency.com" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 tracking-widest">GST NUMBER</label>
                                                <input type="text" name="gst_number" value="{{ $agent->gst_number ?? '' }}" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium uppercase focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 tracking-widest">SAC/HSN CODE</label>
                                                <input type="text" name="sac_hsn_code" value="{{ $agent->sac_hsn_code ?? '' }}" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium uppercase focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 tracking-widest">ABOUT TRAVEL AGENT (Max 160 characters - Fits in Orange Header) <span class="text-red-500">*</span></label>
                                                <textarea name="about" required rows="3" maxlength="160" placeholder="Describe your agency in 3-4 sentences..." class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">{{ $agent->about ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Location & Presence -->
                                    <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100">
                                        <div class="flex items-center mb-8">
                                            <div class="w-1.5 h-6 bg-orange-800 rounded-full mr-4"></div>
                                            <h4 class="text-lg font-bold text-gray-800 tracking-tight">Location & Presence</h4>
                                        </div>

                                        <div class="space-y-6">
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 tracking-widest">OFFICE ADDRESS <span class="text-red-500">*</span></label>
                                                <input type="text" name="address" required value="{{ $agent->address ?? '' }}" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div class="relative">
                                                    <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 tracking-widest">CITY (SEARCH) <span class="text-red-500">*</span></label>
                                                    <input type="text" id="settingsCity" name="city" value="{{ $agent->city ?? '' }}" required placeholder="Search city" autocomplete="off" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                                    <div id="citySuggestions" class="absolute left-0 right-0 z-50 mt-1 max-h-60 overflow-y-auto bg-white rounded-2xl border border-gray-100 shadow-xl hidden custom-scroll"></div>
                                                </div>
                                                <div>
                                                    <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 tracking-widest">STATE <span class="text-red-500">*</span></label>
                                                    <input type="text" id="settingsState" name="state" value="{{ $agent->state ?? '' }}" required placeholder="State" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                                </div>
                                                <div>
                                                    <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 tracking-widest">COUNTRY <span class="text-red-500">*</span></label>
                                                    <input type="text" id="settingsCountry" name="country" value="{{ $agent->country ?? '' }}" required placeholder="Country" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                                </div>
                                            </div>
                                            <div class="w-1/3">
                                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 tracking-widest">PINCODE <span class="text-red-500">*</span></label>
                                                <input type="text" name="pincode" value="{{ $agent->pincode ?? '' }}" required pattern="[0-9]{6}" title="Please enter a valid 6-digit pincode" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Why Us (Showcase)</label>
                                                <textarea name="why_us" rows="4" maxlength="300" placeholder="Tell customers why they should choose you..." class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">{{ $agent->why_us ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Business Card Card -->
                                    <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100 mt-8 relative overflow-hidden group">
                                        <div class="absolute top-0 left-0 w-full h-1 bg-orange-800"></div>
                                        <div class="flex items-center justify-between mb-6">
                                            <div class="flex items-center">
                                                <div class="w-1.5 h-6 bg-orange-800 rounded-full mr-4"></div>
                                                <h4 class="text-lg font-bold text-gray-800 tracking-tight">Business Card <span class="text-red-500">*</span></h4>
                                            </div>
                                            <!-- Success Badge Preview Icon -->
                                            <div id="card_success_badge" class="{{ ($agent && ($agent->business_card_front || $agent->business_card_back || $agent->business_card)) ? '' : 'hidden' }} flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-bold border border-green-100">
                                                <i class="fas fa-check-circle"></i> Card Uploaded
                                            </div>
                                        </div>
                                        <p class="text-[10px] text-gray-400 font-medium mb-6 -mt-3">Upload front and back images of your business card. Supported formats: JPG, PNG (Max 2MB each).</p>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                                            <!-- Front Side -->
                                            <div>
                                                <h5 class="text-[12px] font-bold text-gray-400 uppercase mb-3 tracking-widest">FRONT SIDE</h5>
                                                <div class="w-full aspect-[1.58/1] bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden cursor-pointer hover:bg-orange-50/20 transition-all relative" onclick="document.getElementById('business_card_front_file').click()">
                                                    @if($agent && $agent->business_card_front)
                                                        <img id="card_front_preview" src="{{ asset($agent->business_card_front) }}" class="w-full h-full object-cover">
                                                    @elseif($agent && $agent->business_card)
                                                        <img id="card_front_preview" src="{{ asset($agent->business_card) }}" class="w-full h-full object-cover">
                                                    @else
                                                        <img id="card_front_preview" class="hidden w-full h-full object-cover">
                                                        <div id="card_front_placeholder" class="text-center p-4">
                                                            <i class="fas fa-id-card text-2xl text-gray-300 mb-2"></i>
                                                            <span class="text-[10px] font-bold text-gray-400 block">Click to upload front</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex gap-3 mt-3">
                                                    <button type="button" onclick="document.getElementById('business_card_front_file').click()" class="flex-1 py-3 bg-primary hover:bg-orange-600 text-white rounded-xl text-[9px] font-black uppercase tracking-widest shadow-sm transition-all">
                                                        <i class="fas fa-upload mr-1"></i> Front
                                                    </button>
                                                    <button type="button" id="delete_card_front_btn" onclick="deleteCardFront()" class="{{ ($agent && ($agent->business_card_front || $agent->business_card)) ? '' : 'hidden' }} flex-1 py-3 bg-red-50 hover:bg-red-100 text-red-500 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                                                        <i class="fas fa-trash-alt mr-1"></i> Delete
                                                    </button>
                                                </div>
                                                <input type="file" name="business_card_front_file" id="business_card_front_file" class="hidden" accept="image/*" onchange="previewCardFront(this)">
                                                <input type="hidden" name="delete_card_front" id="delete_card_front" value="0">
                                            </div>

                                            <!-- Back Side -->
                                            <div>
                                                <h5 class="text-[12px] font-bold text-gray-400 uppercase mb-3 tracking-widest">BACK SIDE</h5>
                                                <div class="w-full aspect-[1.58/1] bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden cursor-pointer hover:bg-orange-50/20 transition-all relative" onclick="document.getElementById('business_card_back_file').click()">
                                                    @if($agent && $agent->business_card_back)
                                                        <img id="card_back_preview" src="{{ asset($agent->business_card_back) }}" class="w-full h-full object-cover">
                                                    @else
                                                        <img id="card_back_preview" class="hidden w-full h-full object-cover">
                                                        <div id="card_back_placeholder" class="text-center p-4">
                                                            <i class="fas fa-id-card text-2xl text-gray-300 mb-2"></i>
                                                            <span class="text-[10px] font-bold text-gray-400 block">Click to upload back</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex gap-3 mt-3">
                                                    <button type="button" onclick="document.getElementById('business_card_back_file').click()" class="flex-1 py-3 bg-primary hover:bg-orange-600 text-white rounded-xl text-[9px] font-black uppercase tracking-widest shadow-sm transition-all">
                                                        <i class="fas fa-upload mr-1"></i> Back
                                                    </button>
                                                    <button type="button" id="delete_card_back_btn" onclick="deleteCardBack()" class="{{ ($agent && $agent->business_card_back) ? '' : 'hidden' }} flex-1 py-3 bg-red-50 hover:bg-red-100 text-red-500 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                                                        <i class="fas fa-trash-alt mr-1"></i> Delete
                                                    </button>
                                                </div>
                                                <input type="file" name="business_card_back_file" id="business_card_back_file" class="hidden" accept="image/*" onchange="previewCardBack(this)">
                                                <input type="hidden" name="delete_card_back" id="delete_card_back" value="0">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Save Button -->
                                    <div class="flex justify-end pt-8">
                                        <button type="submit" class="px-8 py-3.5 bg-primary hover:bg-orange-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-orange-100 transition-all">Save Profile Settings</button>
                                    </div>
                                </div>

                                <!-- Right Side: Branding, Tax, Social -->
                                <div class="lg:col-span-4 space-y-8">
                                    <!-- Agency Branding -->
                                    <div class="bg-white p-2 rounded-[32px] shadow-sm border border-gray-100 overflow-hidden relative group">
                                        <!-- Banner Section -->
                                        <div class="mb-6">
                                            <h4 class="text-xs font-bold text-gray-800 mb-1">Agency Banner</h4>
                                            <p class="text-[8px] text-gray-400 font-medium mb-2">Upload your hero banner. Supported formats: JPG, PNG (Max 2MB).</p>
                                            <div class="bg-orange-100 h-28 rounded-2xl relative cursor-pointer group/banner border border-gray-100 overflow-hidden" onclick="document.getElementById('banner_file').click()">
                                                @if($agent && $agent->banner)
                                                    <img id="banner_preview" src="{{ asset($agent->banner) }}" class="w-full h-full object-cover">
                                                @else
                                                    <img id="banner_preview" class="hidden w-full h-full object-cover">
                                                    <div id="banner_placeholder" class="w-full h-full flex flex-col items-center justify-center text-orange-800/50">
                                                        <i class="fas fa-image text-2xl mb-1"></i>
                                                        <span class="text-[8px] font-bold uppercase tracking-widest">Upload Banner</span>
                                                    </div>
                                                @endif
                                                <div class="absolute inset-0 bg-black/30 opacity-0 group-hover/banner:opacity-100 transition-opacity flex items-center justify-center">
                                                    <span class="text-white text-xs font-bold shadow-sm">Change Banner</span>
                                                </div>
                                                
                                                <button type="button" id="delete_banner_btn" onclick="deleteBanner(event)" class="{{ ($agent && $agent->banner) ? '' : 'hidden' }} absolute top-2 right-2 w-6 h-6 bg-red-500/80 hover:bg-red-500 text-white rounded-full flex items-center justify-center transition-all z-10" title="Remove Banner">
                                                    <i class="fas fa-times text-xs"></i>
                                                </button>
                                                
                                                <input type="file" name="banner_file" id="banner_file" class="hidden" accept="image/jpeg,image/png" onchange="previewBanner(this)">
                                                <input type="hidden" name="delete_banner" id="delete_banner" value="0">
                                            </div>
                                        </div>

                                        <!-- Logo Section -->
                                        <div>
                                            <h4 class="text-xs font-bold text-gray-800 mb-1">Agency Logo</h4>
                                            <p class="text-[8px] text-gray-400 font-medium mb-2">Upload your company logo. Supported formats: JPG, PNG (Max 2MB).</p>
                                            
                                            <div class="flex items-center gap-4">
                                                <div class="w-20 h-20 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 shadow-sm overflow-hidden cursor-pointer border border-gray-200" onclick="document.getElementById('logo_file').click()">
                                                    <img id="logo_preview" src="{{ ($agent && $agent->logo) ? asset($agent->logo) : '' }}" class="{{ ($agent && $agent->logo) ? '' : 'hidden' }} w-full h-full object-cover">
                                                    <div id="logo_placeholder" class="{{ ($agent && $agent->logo) ? 'hidden' : '' }} text-center">
                                                        <i class="fas fa-camera-retro text-xl"></i>
                                                    </div>
                                                </div>
                                                
                                                <div class="flex flex-col gap-2 flex-grow">
                                                    <button type="button" onclick="document.getElementById('logo_file').click()" class="w-full py-2 bg-white border border-gray-100 rounded-xl text-[9px] font-bold text-orange-800 uppercase tracking-widest hover:bg-gray-50">Upload Logo</button>
                                                    <button type="button" id="delete_logo_btn" onclick="deleteLogo()" class="{{ ($agent && $agent->logo) ? '' : 'hidden' }} w-full py-2 bg-red-50 border border-red-100 rounded-xl text-red-500 hover:bg-red-100 transition-all text-[9px] font-bold uppercase tracking-widest">
                                                        <i class="fas fa-trash-alt mr-1"></i> Remove Logo
                                                    </button>
                                                </div>
                                                
                                                <input type="file" name="logo_file" id="logo_file" class="hidden" accept="image/jpeg,image/png" onchange="previewLogo(this)">
                                                <input type="hidden" name="delete_logo" id="delete_logo" value="0">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tax & Compliance removed -->

                                    <!-- Social Integration -->
                                    <div class="bg-white p-6 rounded-[32px] shadow-sm border border-gray-100">
                                        <h4 class="text-xs font-bold text-gray-800 mb-6 tracking-tight">Social Integration</h4>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 tracking-widest flex items-center gap-2">
                                                    <i class="fab fa-facebook-f text-blue-600"></i> Facebook Profile
                                                </label>
                                                <input type="url" name="facebook" value="{{ $agent->facebook ?? '' }}" placeholder="https://facebook.com/yourpage" class="w-full px-4 py-2.5 rounded-xl bg-gray-50 border-none text-[10px] font-medium text-gray-800 focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 tracking-widest flex items-center gap-2">
                                                    <i class="fab fa-twitter text-cyan-500"></i> Twitter (X)
                                                </label>
                                                <input type="url" name="twitter" value="{{ $agent->twitter ?? '' }}" placeholder="https://twitter.com/yourhandle" class="w-full px-4 py-2.5 rounded-xl bg-gray-50 border-none text-[10px] font-medium text-gray-800 focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 tracking-widest flex items-center gap-2">
                                                    <i class="fab fa-linkedin-in text-blue-800"></i> LinkedIn Company
                                                </label>
                                                <input type="url" name="linkedin" value="{{ $agent->linkedin ?? '' }}" placeholder="https://linkedin.com/company/yourcompany" class="w-full px-4 py-2.5 rounded-xl bg-gray-50 border-none text-[10px] font-medium text-gray-800 focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div>
                                                <label class="block text-[11px] font-bold text-gray-400 uppercase mb-2 tracking-widest flex items-center gap-2">
                                                    <i class="fab fa-instagram text-pink-500"></i> Instagram Feed
                                                </label>
                                                <input type="url" name="instagram" value="{{ $agent->instagram ?? '' }}" placeholder="https://instagram.com/yourprofile" class="w-full px-4 py-2.5 rounded-xl bg-gray-50 border-none text-[10px] font-medium text-gray-800 focus:ring-2 focus:ring-primary/20">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Security Section -->
                    <div id="content-security" class="tab-content hidden space-y-8">
                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8">
                            <div class="flex items-center justify-between mb-10">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">Security Settings</h3>
                                    <p class="text-sm text-gray-400 mt-1">Manage your password and security preferences.</p>
                                </div>
                            </div>

                            <form action="{{ route('agent.settings.password') }}" method="POST" id="passwordUpdateForm">
                                @csrf
                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 gap-6">
                                        <div class="space-y-2">
                                            <label class="text-[12px] font-bold text-gray-400 uppercase tracking-widest ml-1">Current Password</label>
                                            <div class="relative">
                                                <input type="password" name="current_password" id="current_password" placeholder="••••••••" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/10 text-gray-700 text-sm font-bold pr-12" required>
                                                <button type="button" onclick="togglePasswordVisibility('current_password', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="space-y-2">
                                                <label class="text-[12px] font-bold text-gray-400 uppercase tracking-widest ml-1">New Password</label>
                                                <div class="relative">
                                                    <input type="password" name="new_password" id="new_password" placeholder="••••••••" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/10 text-gray-700 text-sm font-bold pr-12" required minlength="8">
                                                    <button type="button" onclick="togglePasswordVisibility('new_password', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="space-y-2">
                                                <label class="text-[12px] font-bold text-gray-400 uppercase tracking-widest ml-1">Confirm New Password</label>
                                                <div class="relative">
                                                    <input type="password" name="confirm_new_password" id="confirm_new_password" placeholder="••••••••" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/10 text-gray-700 text-sm font-bold pr-12" required minlength="8">
                                                    <button type="button" onclick="togglePasswordVisibility('confirm_new_password', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pt-4">
                                        <button type="submit" id="btnUpdatePassword" class="w-full py-4 bg-primary text-white text-xs font-black rounded-2xl shadow-lg shadow-orange-100 hover:bg-orange-600 transition-all uppercase tracking-widest">Update Password</button>
                                    </div>
                                </div>
                            </form>
                        </div>



                    </div>

                    <!-- Notifications Section -->
                    <div id="content-notifications" class="tab-content hidden space-y-8">
                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8">
                            <div class="flex items-center justify-between mb-8">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">Notification Preferences</h3>
                                    <p class="text-xs text-gray-400 mt-1">Control how you receive activity updates and alerts.</p>
                                </div>
                            </div>

                            <form action="{{ route('agent.settings.notifications') }}" method="POST">
                                @csrf
                                <div class="space-y-5">
                                    <!-- Email Notifications -->
                                    <div class="flex items-center justify-between p-5 bg-gray-50 rounded-[2rem] hover:bg-white hover:shadow-xl hover:shadow-gray-100 transition-all border border-transparent hover:border-gray-100">
                                        <div class="flex items-center space-x-5">
                                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-primary shadow-sm text-lg">
                                                <i class="fas fa-envelope"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-800">Email Notifications</p>
                                                <p class="text-[11px] text-gray-500 mt-0.5">Receive email alerts about your account activity, package updates, and incoming lead inquiries.</p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="notify_email" value="1" class="sr-only peer" {{ ($agent && $agent->notify_email != 0) ? 'checked' : '' }}>
                                            <div class="w-12 h-7 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                        </label>
                                    </div>

                                    <!-- SMS Alerts -->
                                    <div class="flex items-center justify-between p-5 bg-gray-50 rounded-[2rem] hover:bg-white hover:shadow-xl hover:shadow-gray-100 transition-all border border-transparent hover:border-gray-100">
                                        <div class="flex items-center space-x-5">
                                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-primary shadow-sm text-lg">
                                                <i class="fas fa-mobile-alt"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-gray-800">SMS Alerts</p>
                                                <p class="text-[11px] text-gray-500 mt-0.5">Receive SMS notifications on your mobile phone for account activities and lead updates.</p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="notify_sms" value="1" class="sr-only peer" {{ ($agent && $agent->notify_sms != 0) ? 'checked' : '' }}>
                                            <div class="w-12 h-7 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                        </label>
                                    </div>
                                </div>

                                <div class="pt-6 flex justify-end">
                                    <button type="submit" class="px-8 py-3.5 bg-primary text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-orange-100 hover:bg-orange-600 transition-all cursor-pointer">
                                        Save Notification Preferences
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Delete Account Section -->
                    <div id="content-delete" class="tab-content hidden space-y-8">
                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-red-100 p-8">
                            <div class="flex items-center space-x-4 mb-8">
                                <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center text-red-500 text-2xl">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">Delete Account</h3>
                                    <p class="text-sm text-red-400 font-medium">Once you delete your account, there is no going back. Please be certain.</p>
                                </div>
                            </div>
                            
                            <div class="p-6 bg-red-50/50 rounded-3xl border border-red-50 mb-8">
                                <ul class="space-y-3">
                                    <li class="flex items-center text-xs text-red-600 font-medium">
                                        <i class="fas fa-circle text-[6px] mr-3"></i>
                                        All your tour packages will be permanently deleted.
                                    </li>
                                    <li class="flex items-center text-xs text-red-600 font-medium">
                                        <i class="fas fa-circle text-[6px] mr-3"></i>
                                        All lead data and customer conversations will be lost.
                                    </li>
                                    <li class="flex items-center text-xs text-red-600 font-medium">
                                        <i class="fas fa-circle text-[6px] mr-3"></i>
                                        Your subscription will be cancelled immediately.
                                    </li>
                                </ul>
                            </div>

                            <button onclick="confirmDeleteAccount()" class="w-full py-4 bg-red-500 text-white text-xs font-black rounded-2xl shadow-lg shadow-red-100 hover:bg-red-600 transition-all uppercase tracking-widest active:scale-[0.98]">Permanently Delete My Account</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>


    

<!-- OTP Verification Modal (Moved outside layout structure to prevent clipping) -->
<div id="otpModal" class="fixed inset-0 bg-black/60 z-[100] hidden flex items-center justify-center p-4" style="margin: 0; padding: 0;">
    <div class="bg-white rounded-3xl p-8 w-full max-w-md shadow-2xl transform transition-all relative z-[101] m-auto">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-orange-100 text-primary rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Verify Your Identity</h3>
            <p class="text-sm text-gray-500 mt-2">We've sent a 6-digit OTP code to your email. It expires in 5 minutes.</p>
        </div>
        <form id="otpVerifyForm">
            <div class="space-y-4">
                <input type="text" id="otp_code" name="otp" placeholder="Enter 6-digit OTP" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/10 text-center text-gray-700 text-lg font-black tracking-widest" required maxlength="6" pattern="\d{6}">
                <button type="submit" id="btnVerifyOtp" class="w-full py-4 bg-primary text-white text-xs font-black rounded-2xl shadow-lg shadow-orange-100 hover:bg-orange-600 transition-all uppercase tracking-widest">Verify & Update Password</button>
                <button type="button" onclick="closeOtpModal()" class="w-full py-3 text-gray-500 hover:text-gray-700 text-xs font-bold uppercase tracking-widest">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function switchTab(tabName) {
    // Hide all contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Show selected content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Reset all buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-primary', 'text-white', 'shadow-lg', 'shadow-orange-100');
        btn.classList.add('text-gray-400', 'hover:bg-gray-50');
    });
    
    // Set active button
    const activeBtn = document.getElementById('tab-' + tabName);
    activeBtn.classList.add('bg-primary', 'text-white', 'shadow-lg', 'shadow-orange-100');
    activeBtn.classList.remove('text-gray-400', 'hover:bg-gray-50');
}

function togglePasswordVisibility(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

function confirmDeleteAccount() {
    Swal.fire({
        title: 'Delete Account?',
        text: "This action is permanent and cannot be undone!",
        icon: 'error',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#9ca3af',
        confirmButtonText: 'Yes, delete everything',
        borderRadius: '2rem'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Are you absolutely sure?',
                text: 'Please confirm again that you want to delete your account.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Yes, I am sure',
                borderRadius: '2rem'
            }).then((secondResult) => {
                if (secondResult.isConfirmed) {
                    Swal.fire({
                        title: 'Request Sent',
                        text: 'Your account deletion request is being processed.',
                        icon: 'success',
                        confirmButtonColor: '#F0642F',
                        borderRadius: '2rem'
                    });
                }
            });
        }
    });
}

// Logo upload size limit validation (2MB)
document.addEventListener('DOMContentLoaded', () => {
    const logoInput = document.getElementById('logo_file');
    if (logoInput) {
        logoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const fileSize = this.files[0].size / 1024 / 1024; // in MB
                if (fileSize > 2) {
                    Swal.fire({
                        title: 'File Too Large',
                        text: 'Your logo file must be less than 2MB. Your file is ' + fileSize.toFixed(2) + 'MB.',
                        icon: 'warning',
                        confirmButtonColor: '#F0642F',
                        borderRadius: '2rem'
                    });
                    this.value = ''; // Reset input
                }
            }
        });
    }

    // Nominatim autocomplete for city, state, country in Settings — India First
    const cityInput = document.getElementById('settingsCity');
    const suggestionsDiv = document.getElementById('citySuggestions');
    let debounceTimer;

    if (cityInput && suggestionsDiv) {
        cityInput.addEventListener('input', () => {
            const query = cityInput.value.trim();
            clearTimeout(debounceTimer);
            if (!query || query.length < 2) {
                suggestionsDiv.innerHTML = '';
                suggestionsDiv.classList.add('hidden');
                return;
            }

            suggestionsDiv.innerHTML = '<div class="px-4 py-3 text-xs text-gray-400 font-medium flex items-center gap-2"><i class="fas fa-spinner fa-spin text-orange-800"></i> Searching...</div>';
            suggestionsDiv.classList.remove('hidden');

            debounceTimer = setTimeout(() => {
                const base = `https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&accept-language=en`;
                const indiaUrl = `${base}&countrycodes=in&limit=15&q=${encodeURIComponent(query)}`;

                const parseItem = (item) => {
                    const address = item.address || {};
                    let city = address.city || address.town || address.village || address.suburb || address.municipality || address.county || address.state_district || '';
                    if (!city && item.display_name) city = item.display_name.split(',')[0].trim();
                    const state   = address.state   || address.region || '';
                    const country = address.country || '';
                    return { city, state, country };
                };

                const renderRow = (city, state, country) => {
                    const row = document.createElement('div');
                    row.className = 'px-4 py-2.5 hover:bg-orange-50 cursor-pointer text-xs font-semibold text-gray-700 transition-colors flex items-center justify-between border-b border-gray-50 last:border-0';
                    row.innerHTML = `<span>${city}</span><span class="text-[10px] text-gray-400 font-medium">${state ? state + ', ' : ''}${country}</span>`;
                    row.onclick = () => {
                        cityInput.value = city;
                        const stateEl   = document.getElementById('settingsState');
                        const countryEl = document.getElementById('settingsCountry');
                        if (stateEl)   stateEl.value   = state;
                        if (countryEl) countryEl.value = country;
                        suggestionsDiv.classList.add('hidden');
                    };
                    return row;
                };

                fetch(indiaUrl).then(r => r.json()).then(indiaData => {
                    suggestionsDiv.innerHTML = '';
                    const seen = new Set();
                    const indiaResults = [];

                    (indiaData || []).forEach(item => {
                        const { city, state, country } = parseItem(item);
                        if (!city || !country) return;
                        const key = `${city.toLowerCase()}_${state.toLowerCase()}_${country.toLowerCase()}`;
                        if (seen.has(key)) return;
                        seen.add(key);
                        indiaResults.push({ city, state, country });
                    });

                    indiaResults.forEach(({ city, state, country }) => {
                        suggestionsDiv.appendChild(renderRow(city, state, country));
                    });

                    if (indiaResults.length < 5) {
                        const globalUrl = `${base}&limit=10&q=${encodeURIComponent(query)}`;
                        fetch(globalUrl).then(r => r.json()).then(globalData => {
                            (globalData || []).forEach(item => {
                                const { city, state, country } = parseItem(item);
                                if (!city || !country) return;
                                if (country.toLowerCase() === 'india') return;
                                const key = `${city.toLowerCase()}_${state.toLowerCase()}_${country.toLowerCase()}`;
                                if (seen.has(key)) return;
                                seen.add(key);
                                suggestionsDiv.appendChild(renderRow(city, state, country));
                            });
                            if (suggestionsDiv.children.length === 0) {
                                suggestionsDiv.innerHTML = '<div class="px-4 py-3 text-xs text-gray-400 font-medium">No cities found</div>';
                            }
                            suggestionsDiv.classList.remove('hidden');
                        }).catch(() => {});
                    } else {
                        suggestionsDiv.classList.remove('hidden');
                    }

                    if (suggestionsDiv.children.length === 0 && indiaResults.length === 0) {
                        suggestionsDiv.innerHTML = '<div class="px-4 py-3 text-xs text-gray-400 font-medium flex items-center gap-2"><i class="fas fa-spinner fa-spin text-orange-800"></i> Searching...</div>';
                    }
                }).catch(() => suggestionsDiv.classList.add('hidden'));
            }, 350);
        });

        document.addEventListener('click', (e) => {
            if (!cityInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.classList.add('hidden');
            }
        });
    }
});

// Client-side Live Progress calculation
function calculateLiveProgress() {
    const fields = [
        { selector: 'input[name="name"]' },
        { selector: 'input[name="phone"]' },
        { selector: 'input[name="email"]' },
        { selector: 'input[name="address"]' },
        { selector: 'input[name="city"]' },
        { selector: 'input[name="state"]' },
        { selector: 'input[name="country"]' },
        { selector: 'input[name="pincode"]' },
        { selector: 'textarea[name="about"]' }
    ];

    let filled = 0;
    const totalFields = 10; // 9 text fields + logo

    // Check text/input fields
    fields.forEach(f => {
        const el = document.querySelector(f.selector);
        if (el && el.value.trim() !== '') {
            filled++;
        }
    });

    // Check Logo
    const logoInput = document.getElementById('logo_file');
    const hasExistingLogo = {{ ($agent && $agent->logo) ? 'true' : 'false' }};
    const deleteLogoVal = document.getElementById('delete_logo') ? document.getElementById('delete_logo').value : '0';
    if (deleteLogoVal !== '1' && ((logoInput && logoInput.files && logoInput.files.length > 0) || hasExistingLogo)) {
        filled++;
    }

    const percentage = Math.round((filled / totalFields) * 100);

    // Update UI elements
    const progressTexts = document.querySelectorAll('#progress-percentage-text, [x-text*="profileCompletionPercentage"]');
    const progressBars = document.querySelectorAll('#progress-bar-fill, [style*="profileCompletionPercentage"]');
    const progressStatusDesc = document.getElementById('progress-status-desc');

    progressTexts.forEach(el => el.innerText = percentage + '%');
    progressBars.forEach(el => {
        el.style.width = percentage + '%';
        if (percentage < 80) {
            el.classList.remove('bg-green-500');
            el.classList.add('bg-red-500');
        } else {
            el.classList.remove('bg-red-500');
            el.classList.add('bg-green-500');
        }
    });
    
    if (progressStatusDesc) {
        if (percentage < 80) {
            progressStatusDesc.innerHTML = '<span class="text-red-500 font-bold"><i class="fas fa-exclamation-triangle"></i> Locked:</span> Please complete at least 80% of your details to unlock full access.';
        } else {
            progressStatusDesc.innerHTML = '<span class="text-green-500 font-bold"><i class="fas fa-check-circle"></i> Unlocked:</span> Your profile is complete and all agent features are unlocked!';
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Attach change/input listeners to all form fields
    const form = document.querySelector('form[action*="settings/update"]');
    if (form) {
        form.querySelectorAll('input, textarea, select').forEach(el => {
            el.addEventListener('input', calculateLiveProgress);
            el.addEventListener('change', calculateLiveProgress);
        });
        
        // Prevent duplicate phone numbers
        const phoneInputs = form.querySelectorAll('.phone-number-val, input[name="landline"]');
        phoneInputs.forEach(input => {
            input.addEventListener('change', function() {
                const val = this.value.trim();
                if(!val) return;
                phoneInputs.forEach(other => {
                    if(other !== this && other.value.trim() === val) {
                        Swal.fire({
                            title: 'Duplicate Number',
                            text: 'This number is already used in another phone field.',
                            icon: 'warning',
                            confirmButtonColor: '#F0642F',
                            borderRadius: '2rem'
                        });
                        this.value = '';
                        calculateLiveProgress();
                    }
                });
            });
        });
    }
    // Initial run
    calculateLiveProgress();
});

// Client-side Logo Image preview helper
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const fileSize = input.files[0].size / 1024 / 1024; // in MB
        if (fileSize > 2) {
            Swal.fire({
                title: 'File Too Large',
                text: 'Your logo file must be less than 2MB. Your file is ' + fileSize.toFixed(2) + 'MB.',
                icon: 'warning',
                confirmButtonColor: '#F0642F',
                borderRadius: '2rem'
            });
            input.value = ''; // Reset input
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('logo_preview');
            const placeholder = document.getElementById('logo_placeholder');
            const deleteBtn = document.getElementById('delete_logo_btn');
            const deleteInput = document.getElementById('delete_logo');
            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            if (placeholder) placeholder.classList.add('hidden');
            if (deleteBtn) deleteBtn.classList.remove('hidden');
            if (deleteInput) deleteInput.value = '0';
            calculateLiveProgress();
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Client-side Banner Image preview helper
function previewBanner(input) {
    if (input.files && input.files[0]) {
        const fileSize = input.files[0].size / 1024 / 1024; // in MB
        if (fileSize > 5) {
            Swal.fire({
                title: 'File Too Large',
                text: 'Your banner file must be less than 5MB. Your file is ' + fileSize.toFixed(2) + 'MB.',
                icon: 'warning',
                confirmButtonColor: '#F0642F',
                borderRadius: '2rem'
            });
            input.value = ''; // Reset input
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('banner_preview');
            const placeholder = document.getElementById('banner_placeholder');
            const deleteBtn = document.getElementById('delete_banner_btn');
            const deleteInput = document.getElementById('delete_banner');
            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            if (placeholder) placeholder.classList.add('hidden');
            if (deleteBtn) deleteBtn.classList.remove('hidden');
            if (deleteInput) deleteInput.value = '0';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Client-side Banner Delete handler
function deleteBanner(e) {
    e.stopPropagation(); // prevent triggering the file upload click
    const preview = document.getElementById('banner_preview');
    const placeholder = document.getElementById('banner_placeholder');
    const deleteBtn = document.getElementById('delete_banner_btn');
    const deleteInput = document.getElementById('delete_banner');
    const fileInput = document.getElementById('banner_file');
    
    if (preview) preview.classList.add('hidden');
    if (placeholder) placeholder.classList.remove('hidden');
    if (deleteBtn) deleteBtn.classList.add('hidden');
    if (deleteInput) deleteInput.value = '1';
    if (fileInput) fileInput.value = '';
}

// Client-side Logo Delete handler
function deleteLogo() {
    const preview = document.getElementById('logo_preview');
    const placeholder = document.getElementById('logo_placeholder');
    const deleteBtn = document.getElementById('delete_logo_btn');
    const deleteInput = document.getElementById('delete_logo');
    const fileInput = document.getElementById('logo_file');
    
    if (preview) preview.classList.add('hidden');
    if (placeholder) placeholder.classList.remove('hidden');
    if (deleteBtn) deleteBtn.classList.add('hidden');
    if (deleteInput) deleteInput.value = '1';
    if (fileInput) fileInput.value = '';
    
    calculateLiveProgress();
}

// Client-side Business Card Image preview helper (Front)
function previewCardFront(input) {
    if (input.files && input.files[0]) {
        const fileSize = input.files[0].size / 1024 / 1024; // in MB
        if (fileSize > 2) {
            Swal.fire({
                title: 'File Too Large',
                text: 'Your front business card file must be less than 2MB. Your file is ' + fileSize.toFixed(2) + 'MB.',
                icon: 'warning',
                confirmButtonColor: '#F0642F',
                borderRadius: '2rem'
            });
            input.value = ''; // Reset input
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('card_front_preview');
            const placeholder = document.getElementById('card_front_placeholder');
            const badge = document.getElementById('card_success_badge');
            const deleteBtn = document.getElementById('delete_card_front_btn');
            const deleteInput = document.getElementById('delete_card_front');
            
            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            if (placeholder) placeholder.classList.add('hidden');
            if (badge) badge.classList.remove('hidden');
            if (deleteBtn) deleteBtn.classList.remove('hidden');
            if (deleteInput) deleteInput.value = '0';
            
            calculateLiveProgress();
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Client-side Business Card Image preview helper (Back)
function previewCardBack(input) {
    if (input.files && input.files[0]) {
        const fileSize = input.files[0].size / 1024 / 1024; // in MB
        if (fileSize > 2) {
            Swal.fire({
                title: 'File Too Large',
                text: 'Your back business card file must be less than 2MB. Your file is ' + fileSize.toFixed(2) + 'MB.',
                icon: 'warning',
                confirmButtonColor: '#F0642F',
                borderRadius: '2rem'
            });
            input.value = ''; // Reset input
            return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('card_back_preview');
            const placeholder = document.getElementById('card_back_placeholder');
            const badge = document.getElementById('card_success_badge');
            const deleteBtn = document.getElementById('delete_card_back_btn');
            const deleteInput = document.getElementById('delete_card_back');
            
            if (preview) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            }
            if (placeholder) placeholder.classList.add('hidden');
            if (badge) badge.classList.remove('hidden');
            if (deleteBtn) deleteBtn.classList.remove('hidden');
            if (deleteInput) deleteInput.value = '0';
            
            calculateLiveProgress();
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Client-side Business Card Delete handler (Front)
function deleteCardFront() {
    const preview = document.getElementById('card_front_preview');
    const placeholder = document.getElementById('card_front_placeholder');
    const deleteBtn = document.getElementById('delete_card_front_btn');
    const deleteInput = document.getElementById('delete_card_front');
    const fileInput = document.getElementById('business_card_front_file');
    
    if (preview) preview.classList.add('hidden');
    if (placeholder) placeholder.classList.remove('hidden');
    if (deleteBtn) deleteBtn.classList.add('hidden');
    if (deleteInput) deleteInput.value = '1';
    if (fileInput) fileInput.value = '';
    
    calculateLiveProgress();
}

// Client-side Business Card Delete handler (Back)
function deleteCardBack() {
    const preview = document.getElementById('card_back_preview');
    const placeholder = document.getElementById('card_back_placeholder');
    const deleteBtn = document.getElementById('delete_card_back_btn');
    const deleteInput = document.getElementById('delete_card_back');
    const fileInput = document.getElementById('business_card_back_file');
    
    if (preview) preview.classList.add('hidden');
    if (placeholder) placeholder.classList.remove('hidden');
    if (deleteBtn) deleteBtn.classList.add('hidden');
    if (deleteInput) deleteInput.value = '1';
    if (fileInput) fileInput.value = '';
    
    calculateLiveProgress();
}

// ---- OTP Password Update Logic ----
document.addEventListener('DOMContentLoaded', function() {
    const pwdForm = document.getElementById('passwordUpdateForm');
    const otpModal = document.getElementById('otpModal');
    const otpVerifyForm = document.getElementById('otpVerifyForm');
    
    if (pwdForm) {
        pwdForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnUpdatePassword');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Requesting OTP...';
            btn.disabled = true;

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                
                if (data.success) {
                    Swal.fire({
                        title: 'OTP Sent',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#F0642F',
                        borderRadius: '2rem'
                    });
                    otpModal.classList.remove('hidden');
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message,
                        icon: 'error',
                        confirmButtonColor: '#ef4444',
                        borderRadius: '2rem'
                    });
                }
            })
            .catch(error => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'An unexpected error occurred. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#ef4444',
                    borderRadius: '2rem'
                });
            });
        });
    }

    if (otpVerifyForm) {
        otpVerifyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnVerifyOtp');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Verifying...';
            btn.disabled = true;

            const otpCode = document.getElementById('otp_code').value;
            const csrfToken = document.querySelector('input[name="_token"]').value;

            fetch('{{ route("agent.settings.password.verify") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ otp: otpCode })
            })
            .then(response => response.json())
            .then(data => {
                btn.innerHTML = originalText;
                btn.disabled = false;

                if (data.success) {
                    closeOtpModal();
                    pwdForm.reset();
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#F0642F',
                        borderRadius: '2rem'
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message,
                        icon: 'error',
                        confirmButtonColor: '#ef4444',
                        borderRadius: '2rem'
                    });
                }
            })
            .catch(error => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'An unexpected error occurred during verification.',
                    icon: 'error',
                    confirmButtonColor: '#ef4444',
                    borderRadius: '2rem'
                });
            });
        });
    }
});

function closeOtpModal() {
    document.getElementById('otpModal').classList.add('hidden');
    const otpForm = document.getElementById('otpVerifyForm');
    if(otpForm) otpForm.reset();
}
</script>
@endsection

