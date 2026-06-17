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
                                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">PRIMARY MOBILE</label>
                                                <input type="text" name="phone" value="{{ $agent->phone ?? '' }}" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div>
                                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">SECONDARY MOBILE</label>
                                                <input type="text" name="landline" value="{{ $agent->landline ?? '' }}" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">OFFICIAL EMAIL ADDRESS</label>
                                                <input type="email" name="email" value="{{ $agent->email ?? '' }}" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
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
                                                <div>
                                                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">COUNTRY</label>
                                                    <select name="country" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium appearance-none">
                                                        <option value="India" {{ ($agent->country ?? 'India') === 'India' ? 'selected' : '' }}>India</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">STATE</label>
                                                    <input type="text" name="state" value="{{ $agent->state ?? '' }}" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                                </div>
                                                <div>
                                                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">CITY</label>
                                                    <input type="text" name="city" value="{{ $agent->city ?? '' }}" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                                                </div>
                                            </div>
                                            <div class="w-1/3">
                                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">PINCODE</label>
                                                <input type="text" name="pincode" value="{{ $agent->pincode ?? '' }}" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
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
                                                    <img src="{{ $agent->logo }}" class="w-full h-full object-cover">
                                                @else
                                                    <i class="fas fa-camera-retro text-lg"></i>
                                                @endif
                                            </div>
                                            <input type="file" name="logo_file" id="logo_file" class="hidden" accept="image/*">
                                        </div>
                                        <div class="p-6 pt-10 text-center">
                                            <h4 class="text-xs font-bold text-gray-800 mb-1">Agency Branding</h4>
                                            <p class="text-[8px] text-gray-400 font-medium mb-4">Click logo icon above or button below to upload your company logo.</p>
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
                                        <div class="space-y-3.5">
                                            @php
                                                $socials = [
                                                    ['name' => 'Facebook Profile', 'icon' => 'fab fa-facebook-f', 'color' => 'text-blue-600'],
                                                    ['name' => 'Twitter (X)', 'icon' => 'fab fa-twitter', 'color' => 'text-cyan-500'],
                                                    ['name' => 'LinkedIn Company', 'icon' => 'fab fa-linkedin-in', 'color' => 'text-blue-800'],
                                                    ['name' => 'Google+ Profile', 'icon' => 'fab fa-google-plus-g', 'color' => 'text-red-500'],
                                                    ['name' => 'Instagram Feed', 'icon' => 'fab fa-instagram', 'color' => 'text-pink-500'],
                                                    ['name' => 'Skype ID', 'icon' => 'fab fa-skype', 'color' => 'text-cyan-400'],
                                                ];
                                            @endphp
                                            @foreach($socials as $soc)
                                            <div class="flex items-center justify-between group cursor-pointer">
                                                <div class="flex items-center">
                                                    <div class="w-7 h-7 rounded-lg bg-gray-50 flex items-center justify-center mr-3 group-hover:bg-white group-hover:shadow-lg transition-all">
                                                        <i class="{{ $soc['icon'] }} {{ $soc['color'] }} text-[10px]"></i>
                                                    </div>
                                                    <span class="text-[9px] text-gray-400 font-medium">{{ $soc['name'] }}</span>
                                                </div>
                                                <i class="fas fa-link text-[7px] text-gray-200 group-hover:text-primary transition-colors"></i>
                                            </div>
                                            @endforeach
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

        <footer class="mt-16 flex flex-col lg:flex-row items-center justify-between py-8 border-t border-gray-100">
            <p class="text-[11px] text-gray-400 font-bold mb-4 lg:mb-0 uppercase tracking-widest">Copyright © 2026 Tour Raja Private Limited, India. All rights reserved.</p>
            <div class="flex space-x-8 text-[11px] text-gray-400 font-bold uppercase tracking-widest">
                <a href="#" class="hover:text-primary transition-colors">About Us</a>
                <a href="#" class="hover:text-primary transition-colors">License</a>
                <a href="#" class="hover:text-primary transition-colors">Terms of Services</a>
                <a href="#" class="hover:text-primary transition-colors">Privacy Policy</a>
            </div>
        </footer>
    

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
</script>
@endsection
