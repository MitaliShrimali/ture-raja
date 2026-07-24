@extends('agent.layouts.app')

@section('title', 'Settings - Tour Raja Agent')

@section('content')
@php
    $agent = \DB::table('agents')->where('id', session('agent_id'))->first();
@endphp

        <div class="max-w-6xl mx-auto">
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
                                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">COMPANY NAME</label>
                                                <input type="text" name="name" value="{{ $agent->agency_name ?? $agent->name ?? '' }}" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div>
                                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">SINCE</label>
                                                <input type="text" name="since" value="{{ $agent->since ?? '2026' }}" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div>
                                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">PRIMARY MOBILE *</label>
                                                <div class="flex gap-2 items-center">
                                                    <div class="relative w-28 shrink-0">
                                                        <select class="phone-country-code w-full px-3 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20 appearance-none">
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
                                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">SECONDARY MOBILE</label>
                                                <div class="flex gap-2 items-center">
                                                    <div class="relative w-28 shrink-0">
                                                        <select class="phone-country-code w-full px-3 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20 appearance-none">
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
                                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">LANDLINE NUMBER</label>
                                                <input type="text" name="landline" value="{{ $agent->landline ?? '' }}" placeholder="e.g. +91-79-12345678" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div>
                                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">OFFICIAL EMAIL ADDRESS</label>
                                                <input type="email" name="email" value="{{ $agent->email ?? '' }}" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div>
                                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">WEBSITE URL</label>
                                                <input type="url" name="website" value="{{ $agent->website ?? '' }}" placeholder="e.g. https://www.youragency.com" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">ABOUT TRAVEL AGENT (Max 160 characters - Fits in Orange Header)</label>
                                                <textarea name="about" rows="3" maxlength="160" placeholder="Describe your agency in 3-4 sentences..." class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">{{ $agent->about ?? '' }}</textarea>
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
                                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">OFFICE ADDRESS</label>
                                                <input type="text" name="address" value="{{ $agent->address ?? '' }}" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div class="relative">
                                                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">CITY (SEARCH) *</label>
                                                    <input type="text" id="settingsCity" name="city" value="{{ $agent->city ?? '' }}" required placeholder="Search city" autocomplete="off" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                                    <div id="citySuggestions" class="absolute left-0 right-0 z-50 mt-1 max-h-60 overflow-y-auto bg-white rounded-2xl border border-gray-100 shadow-xl hidden custom-scroll"></div>
                                                </div>
                                                <div>
                                                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">STATE *</label>
                                                    <input type="text" id="settingsState" name="state" value="{{ $agent->state ?? '' }}" required placeholder="State" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                                </div>
                                                <div>
                                                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">COUNTRY *</label>
                                                    <input type="text" id="settingsCountry" name="country" value="{{ $agent->country ?? '' }}" required placeholder="Country" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                                </div>
                                            </div>
                                            <div class="w-1/3">
                                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">PINCODE *</label>
                                                <input type="text" name="pincode" value="{{ $agent->pincode ?? '' }}" required pattern="[0-9]{6}" title="Please enter a valid 6-digit pincode" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Save Button -->
                                    <div class="flex justify-end pt-4">
                                        <button type="submit" class="px-8 py-3.5 bg-primary hover:bg-orange-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-orange-100 transition-all">Save Profile Settings</button>
                                    </div>
                                </div>

                                <!-- Right Side: Branding, Tax, Social -->
                                <div class="lg:col-span-4 space-y-8">
                                    <!-- Agency Branding -->
                                    <div class="bg-white p-2 rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
                                        <div class="bg-orange-100 h-28 rounded-t-[30px] relative">
                                            <div class="absolute -bottom-6 left-1/2 -translate-x-1/2 w-14 h-14 bg-orange-800 rounded-xl flex items-center justify-center text-white shadow-xl overflow-hidden cursor-pointer" onclick="document.getElementById('logo_file').click()">
                                                @if($agent && $agent->logo)
                                                    <img src="{{ asset($agent->logo) }}" class="w-full h-full object-cover">
                                                @else
                                                    <i class="fas fa-camera-retro text-lg"></i>
                                                @endif
                                            </div>
                                            <input type="file" name="logo_file" id="logo_file" class="hidden" accept="image/*">
                                        </div>
                                        <div class="p-6 pt-10 text-center">
                                            <h4 class="text-xs font-bold text-gray-800 mb-1">Agency Branding</h4>
                                            <p class="text-[8px] text-gray-400 font-medium mb-4">Click logo icon above or button below to upload your company logo (Max 2MB).</p>
                                            <button type="button" onclick="document.getElementById('logo_file').click()" class="w-full py-2.5 bg-white border border-gray-100 rounded-xl text-[9px] font-bold text-orange-800 uppercase tracking-widest hover:bg-gray-50">Upload Logo</button>
                                        </div>
                                    </div>

                                    <!-- Tax & Compliance -->
                                    <div class="bg-white p-6 rounded-[32px] shadow-sm border border-gray-100 relative overflow-hidden group">
                                        <div class="absolute top-0 left-0 w-full h-1 bg-orange-800"></div>
                                        <h4 class="text-xs font-bold text-gray-800 mb-6 tracking-tight">Tax & Compliance</h4>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-[8px] font-bold text-gray-300 uppercase mb-1.5 tracking-widest">PAN NUMBER</label>
                                                <input type="text" name="pan_number" value="ABCDE1234F" class="w-full px-4 py-2.5 rounded-lg bg-gray-50 border-none text-[10px] font-bold text-gray-800 uppercase">
                                            </div>
                                            <div>
                                                <label class="block text-[8px] font-bold text-gray-300 uppercase mb-1.5 tracking-widest">GST NUMBER</label>
                                                <input type="text" name="gst_number" value="29ABCDE1234F1Z5" class="w-full px-4 py-2.5 rounded-lg bg-gray-50 border-none text-[10px] font-bold text-gray-800 uppercase">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Social Integration -->
                                    <div class="bg-white p-6 rounded-[32px] shadow-sm border border-gray-100">
                                        <h4 class="text-xs font-bold text-gray-800 mb-6 tracking-tight">Social Integration</h4>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest flex items-center gap-2">
                                                    <i class="fab fa-facebook-f text-blue-600"></i> Facebook Profile
                                                </label>
                                                <input type="url" name="facebook" value="{{ $agent->facebook ?? '' }}" placeholder="https://facebook.com/yourpage" class="w-full px-4 py-2.5 rounded-xl bg-gray-50 border-none text-[10px] font-medium text-gray-800 focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div>
                                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest flex items-center gap-2">
                                                    <i class="fab fa-twitter text-cyan-500"></i> Twitter (X)
                                                </label>
                                                <input type="url" name="twitter" value="{{ $agent->twitter ?? '' }}" placeholder="https://twitter.com/yourhandle" class="w-full px-4 py-2.5 rounded-xl bg-gray-50 border-none text-[10px] font-medium text-gray-800 focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div>
                                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest flex items-center gap-2">
                                                    <i class="fab fa-linkedin-in text-blue-800"></i> LinkedIn Company
                                                </label>
                                                <input type="url" name="linkedin" value="{{ $agent->linkedin ?? '' }}" placeholder="https://linkedin.com/company/yourcompany" class="w-full px-4 py-2.5 rounded-xl bg-gray-50 border-none text-[10px] font-medium text-gray-800 focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div>
                                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest flex items-center gap-2">
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

                            <div class="space-y-6">
                                <div class="grid grid-cols-1 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Current Password</label>
                                        <input type="password" placeholder="••••••••" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/10 text-gray-700 text-sm font-bold">
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">New Password</label>
                                            <input type="password" placeholder="••••••••" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/10 text-gray-700 text-sm font-bold">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Confirm New Password</label>
                                            <input type="password" placeholder="••••••••" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/10 text-gray-700 text-sm font-bold">
                                        </div>
                                    </div>
                                </div>
                                <div class="pt-4">
                                    <button class="w-full py-4 bg-primary text-white text-xs font-black rounded-2xl shadow-lg shadow-orange-100 hover:bg-orange-600 transition-all uppercase tracking-widest">Update Password</button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8">
                            <h3 class="text-xl font-bold text-gray-800 mb-6">Two-Factor Authentication</h3>
                            <div class="flex items-center justify-between p-6 bg-orange-50 rounded-3xl border border-orange-100">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-primary shadow-sm text-xl">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">Two-factor authentication is disabled</p>
                                        <p class="text-[10px] text-gray-500 max-w-sm">Add an extra layer of security to your account by requiring a verification code in addition to your password.</p>
                                    </div>
                                </div>
                                <button class="px-6 py-3 bg-primary text-white text-[10px] font-black rounded-xl uppercase tracking-widest hover:bg-orange-600 transition-all">Enable</button>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications Section -->
                    <div id="content-notifications" class="tab-content hidden space-y-8">
                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8">
                            <h3 class="text-xl font-bold text-gray-800 mb-8">Notification Preferences</h3>
                            <div class="space-y-4">
                                <?php 
                                $notifs = [
                                    ['title' => 'Email Notifications', 'desc' => 'Receive emails about your account activity', 'icon' => 'fas fa-envelope', 'checked' => true],
                                    ['title' => 'Desktop Notifications', 'desc' => 'Show desktop alerts for new leads', 'icon' => 'fas fa-desktop', 'checked' => false],
                                    ['title' => 'SMS Alerts', 'desc' => 'Get urgent updates via SMS', 'icon' => 'fas fa-mobile-alt', 'checked' => true],
                                    ['title' => 'Marketing Emails', 'desc' => 'Receive news and promotional offers', 'icon' => 'fas fa-percentage', 'checked' => false],
                                ];
                                foreach($notifs as $n):
                                ?>
                                <div class="flex items-center justify-between p-5 bg-gray-50 rounded-[2rem] hover:bg-white hover:shadow-xl hover:shadow-gray-100 transition-all border border-transparent hover:border-gray-100">
                                    <div class="flex items-center space-x-5">
                                        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-primary shadow-sm text-lg">
                                            <i class="<?php echo $n['icon']; ?>"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800"><?php echo $n['title']; ?></p>
                                            <p class="text-[10px] text-gray-400"><?php echo $n['desc']; ?></p>
                                        </div>
                                    </div>
                                    <div class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" <?php echo $n['checked'] ? 'checked' : ''; ?>>
                                        <div class="w-12 h-7 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
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
                title: 'Request Sent',
                text: 'Your account deletion request is being processed.',
                icon: 'success',
                confirmButtonColor: '#F0642F',
                borderRadius: '2rem'
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

    // Nominatim autocomplete for city, state, country in Settings
    const cityInput = document.getElementById('settingsCity');
    const suggestionsDiv = document.getElementById('citySuggestions');
    let debounceTimer;

    if (cityInput && suggestionsDiv) {
        cityInput.addEventListener('input', () => {
            const query = cityInput.value.trim();

            clearTimeout(debounceTimer);
            if (!query || query.length < 3) {
                suggestionsDiv.innerHTML = '';
                suggestionsDiv.classList.add('hidden');
                return;
            }

            suggestionsDiv.innerHTML = '<div class="px-4 py-3 text-xs text-gray-400 font-medium flex items-center gap-2"><i class="fas fa-spinner fa-spin text-orange-800"></i> Searching...</div>';
            suggestionsDiv.classList.remove('hidden');

            debounceTimer = setTimeout(() => {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&limit=10&accept-language=en&q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    suggestionsDiv.innerHTML = '';
                    if (data && data.length > 0) {
                        const seen = new Set();
                        data.forEach(item => {
                            const address = item.address || {};
                            
                            let city = address.city || address.town || address.village || address.suburb || address.municipality || address.county || address.state_district || '';
                            if (!city && item.display_name) {
                                city = item.display_name.split(',')[0].trim();
                            }
                            
                            const state = address.state || address.region || '';
                            const country = address.country || '';

                            if (city && country) {
                                const key = `${city.toLowerCase()}_${state.toLowerCase()}_${country.toLowerCase()}`;
                                if (seen.has(key)) return;
                                seen.add(key);

                                const row = document.createElement('div');
                                row.className = 'px-4 py-2.5 hover:bg-orange-50 cursor-pointer text-xs font-semibold text-gray-700 transition-colors flex items-center justify-between border-b border-gray-50 last:border-0';
                                row.innerHTML = `<span>${city}</span><span class="text-[10px] text-gray-400 font-medium">${state ? state + ', ' : ''}${country}</span>`;
                                row.onclick = () => {
                                    cityInput.value = city;
                                    document.getElementById('settingsState').value = state;
                                    document.getElementById('settingsCountry').value = country;
                                    suggestionsDiv.classList.add('hidden');
                                };
                                suggestionsDiv.appendChild(row);
                            }
                        });

                        if (suggestionsDiv.children.length > 0) {
                            suggestionsDiv.classList.remove('hidden');
                        } else {
                            suggestionsDiv.innerHTML = '<div class="px-4 py-3 text-xs text-gray-400 font-medium">No cities found</div>';
                        }
                    } else {
                        suggestionsDiv.innerHTML = '<div class="px-4 py-3 text-xs text-gray-400 font-medium">No cities found</div>';
                    }
                })
                .catch(err => {
                    console.error('Error fetching cities:', err);
                    suggestionsDiv.classList.add('hidden');
                });
            }, 400);
        });

        document.addEventListener('click', (e) => {
            if (!cityInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.classList.add('hidden');
            }
        });
    }
});
</script>
@endsection
