@extends('agent.layouts.app')

@section('title', 'Settings - Tour Raja Agent')

@section('content')




        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Settings Navigation -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 space-y-2 sticky top-8">
                        <button onclick="switchTab('general')" id="tab-general" class="tab-btn w-full flex items-center space-x-3 px-4 py-3 rounded-xl bg-primary text-white shadow-lg shadow-orange-100 transition-all font-bold text-sm">
                            <i class="fas fa-user-circle"></i>
                            <span>General</span>
                        </button>
                        <button onclick="switchTab('security')" id="tab-security" class="tab-btn w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-gray-50 transition-all font-bold text-sm">
                            <i class="fas fa-lock"></i>
                            <span>Security</span>
                        </button>
                        <button onclick="switchTab('notifications')" id="tab-notifications" class="tab-btn w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-gray-50 transition-all font-bold text-sm">
                            <i class="fas fa-bell"></i>
                            <span>Notifications</span>
                        </button>
                        <button onclick="switchTab('appearance')" id="tab-appearance" class="tab-btn w-full flex items-center space-x-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-gray-50 transition-all font-bold text-sm">
                            <i class="fas fa-palette"></i>
                            <span>Appearance</span>
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
                        <!-- Profile Card -->
                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8">
                            <div class="flex items-center justify-between mb-10">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">Profile Information</h3>
                                    <p class="text-sm text-gray-400 mt-1">Update your account details and profile picture.</p>
                                </div>
                                <button onclick="toastr.success('Settings saved successfully')" class="px-6 py-2 bg-primary text-white text-xs font-bold rounded-xl shadow-lg shadow-orange-100 hover:bg-orange-600 transition-all">Save Changes</button>
                            </div>

                            <div class="flex flex-col md:flex-row items-start md:items-center gap-8 mb-10">
                                <div class="relative group">
                                    <div class="w-32 h-32 rounded-[2rem] bg-primary flex items-center justify-center text-white text-4xl font-black shadow-xl shadow-orange-100">AU</div>
                                    <button class="absolute -bottom-2 -right-2 w-10 h-10 bg-white text-primary rounded-xl shadow-lg flex items-center justify-center hover:scale-110 transition-transform">
                                        <i class="fas fa-camera"></i>
                                    </button>
                                </div>
                                <div class="space-y-2">
                                    <h4 class="text-lg font-bold text-gray-800">Profile Photo</h4>
                                    <p class="text-xs text-gray-400 max-w-xs leading-relaxed">Accepted formats: JPG, PNG, GIF. Max file size: 2MB. Recommended dimensions: 400x400px.</p>
                                    <div class="flex gap-3 pt-2">
                                        <button class="px-4 py-2 bg-orange-50 text-primary text-[10px] font-black rounded-lg uppercase tracking-widest">Upload New</button>
                                        <button class="px-4 py-2 text-gray-300 text-[10px] font-black rounded-lg uppercase tracking-widest hover:text-red-500 transition-colors">Remove</button>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Full Name</label>
                                    <input type="text" value="Admin User" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/10 text-gray-700 text-sm font-bold">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Email Address</label>
                                    <input type="email" value="admin@tourraja.com" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/10 text-gray-700 text-sm font-bold">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Phone Number</label>
                                    <input type="text" value="+91 98765 43210" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/10 text-gray-700 text-sm font-bold">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Location</label>
                                    <input type="text" value="Rajkot, Gujarat, India" class="w-full px-5 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-primary/10 text-gray-700 text-sm font-bold">
                                </div>
                            </div>
                        </div>

                        <!-- Preferences Section -->
                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8">
                            <h3 class="text-xl font-bold text-gray-800 mb-8">System Preferences</h3>
                            <div class="space-y-6">
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary shadow-sm">
                                            <i class="fas fa-language"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">Language</p>
                                            <p class="text-[10px] text-gray-400">Select your preferred language</p>
                                        </div>
                                    </div>
                                    <select class="bg-transparent border-none text-xs font-bold text-gray-500 focus:ring-0">
                                        <option>English (US)</option>
                                        <option>Gujarati</option>
                                        <option>Hindi</option>
                                    </select>
                                </div>
                            </div>
                        </div>
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

                    <!-- Appearance Section -->
                    <div id="content-appearance" class="tab-content hidden space-y-8">
                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8">
                            <h3 class="text-xl font-bold text-gray-800 mb-8">Interface Appearance</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-4">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Theme Mode</p>
                                    <div class="grid grid-cols-2 gap-4">
                                        <button class="flex flex-col items-center p-4 rounded-3xl border-2 border-primary bg-orange-50 space-y-3">
                                            <div class="w-full h-20 bg-white rounded-xl border border-gray-100 flex items-center justify-center">
                                                <i class="fas fa-sun text-primary text-2xl"></i>
                                            </div>
                                            <span class="text-xs font-bold text-primary">Light Mode</span>
                                        </button>
                                        <button class="flex flex-col items-center p-4 rounded-3xl border-2 border-transparent bg-gray-50 space-y-3 hover:border-gray-200 transition-all">
                                            <div class="w-full h-20 bg-gray-800 rounded-xl flex items-center justify-center">
                                                <i class="fas fa-moon text-white text-2xl"></i>
                                            </div>
                                            <span class="text-xs font-bold text-gray-500">Dark Mode</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Accent Color</p>
                                    <div class="grid grid-cols-4 gap-4 pt-2">
                                        <button class="w-10 h-10 rounded-full bg-[#F0642F] ring-4 ring-orange-100 ring-offset-2"></button>
                                        <button class="w-10 h-10 rounded-full bg-blue-500 hover:scale-110 transition-transform"></button>
                                        <button class="w-10 h-10 rounded-full bg-purple-500 hover:scale-110 transition-transform"></button>
                                        <button class="w-10 h-10 rounded-full bg-green-500 hover:scale-110 transition-transform"></button>
                                    </div>
                                </div>
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
