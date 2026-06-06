@extends('agent.layouts.app')

@section('title', 'Profile - Tour Raja Agent')

@section('content')
<div class="flex items-center justify-between mb-12">
            <div>
                <p class="text-xs text-gray-400 font-medium">Pages / Profile</p>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Profile</h2>
            </div>
            <div class="flex space-x-3">
                <button class="px-8 py-2.5 rounded-2xl text-xs font-bold text-gray-400 bg-gray-100 hover:bg-gray-200 transition-colors">Discard</button>
                <button class="px-8 py-2.5 rounded-2xl text-xs font-bold text-white bg-orange-800 shadow-xl shadow-orange-100 hover:scale-105 transition-all">Save Changes</button>
            </div>
        </div>

        <div class="mb-6">
            <p class="text-[10px] font-bold text-orange-800 uppercase tracking-[3px] mb-2">ACCOUNT CONFIGURATION</p>
            <h3 class="text-4xl font-bold text-gray-800 mb-2">Profile Settings</h3>
            <p class="text-[11px] text-gray-400 font-medium max-w-lg leading-relaxed">Manage your agency credentials, professional details, and social presence in one editorial dashboard.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side: Identity & Location -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Agency Identity -->
                <div class="bg-white p-10 rounded-[48px] shadow-sm border border-gray-100">
                    <div class="flex items-center mb-10">
                        <div class="w-1.5 h-6 bg-orange-800 rounded-full mr-4"></div>
                        <h4 class="text-lg font-bold text-gray-800 tracking-tight">Agency Identity</h4>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest">COMPANY NAME</label>
                            <input type="text" value="Tourraja Global Travels" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest">SINCE</label>
                            <input type="text" value="2015" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest">PRIMARY MOBILE</label>
                            <input type="text" value="+91 98765 43210" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest">SECONDARY MOBILE</label>
                            <input type="text" value="+91 87654 32109" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest">OFFICIAL EMAIL ADDRESS</label>
                            <input type="email" value="contact@tourraja.com" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                        </div>
                    </div>
                </div>

                <!-- Location & Presence -->
                <div class="bg-white p-10 rounded-[48px] shadow-sm border border-gray-100">
                    <div class="flex items-center mb-10">
                        <div class="w-1.5 h-6 bg-orange-800 rounded-full mr-4"></div>
                        <h4 class="text-lg font-bold text-gray-800 tracking-tight">Location & Presence</h4>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest">OFFICE ADDRESS</label>
                            <input type="text" value="124 Explorer's Plaza, MG Road, Tech District" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest">COUNTRY</label>
                                <select class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none text-xs font-medium appearance-none">
                                    <option>India</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest">STATE</label>
                                <input type="text" value="Karnataka" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest">CITY</label>
                                <input type="text" value="Bangalore" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                            </div>
                        </div>
                        <div class="w-1/3">
                            <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest">PINCODE</label>
                            <input type="text" value="560001" class="w-full px-6 py-4 rounded-2xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Branding, Tax, Social -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Agency Branding -->
                <div class="bg-white p-2 rounded-[48px] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-orange-100 h-32 rounded-t-[46px] relative">
                        <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 w-16 h-16 bg-orange-800 rounded-2xl flex items-center justify-center text-white shadow-xl">
                            <i class="fas fa-camera-retro text-xl"></i>
                        </div>
                    </div>
                    <div class="p-8 pt-12 text-center">
                        <h4 class="text-sm font-bold text-gray-800 mb-2">Agency Branding</h4>
                        <p class="text-[9px] text-gray-400 font-medium mb-6">Click to update your company logo and brand cover photo.</p>
                        <button class="w-full py-3 bg-white border border-gray-100 rounded-2xl text-[10px] font-bold text-orange-800 uppercase tracking-widest hover:bg-gray-50">Update Assets</button>
                    </div>
                </div>

                <!-- Tax & Compliance -->
                <div class="bg-white p-8 rounded-[48px] shadow-sm border border-gray-100 relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-full h-1 bg-orange-800"></div>
                    <h4 class="text-sm font-bold text-gray-800 mb-8 tracking-tight">Tax & Compliance</h4>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[8px] font-bold text-gray-300 uppercase mb-2 tracking-widest">PAN NUMBER</label>
                            <input type="text" value="ABCDE1234F" class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none text-xs font-bold text-gray-800 uppercase">
                        </div>
                        <div>
                            <label class="block text-[8px] font-bold text-gray-300 uppercase mb-2 tracking-widest">GST NUMBER</label>
                            <input type="text" value="29ABCDE1234F1Z5" class="w-full px-5 py-3 rounded-xl bg-gray-50 border-none text-xs font-bold text-gray-800 uppercase">
                        </div>
                    </div>
                </div>

                <!-- Social Integration -->
                <div class="bg-white p-10 rounded-[48px] shadow-sm border border-gray-100">
                    <h4 class="text-sm font-bold text-gray-800 mb-8 tracking-tight">Social Integration</h4>
                    <div class="space-y-4">
                        <?php 
                        $socials = [
                            ['name' => 'Facebook Profile', 'icon' => 'fab fa-facebook-f', 'color' => 'text-blue-600'],
                            ['name' => 'Twitter (X)', 'icon' => 'fab fa-twitter', 'color' => 'text-cyan-500'],
                            ['name' => 'LinkedIn Company', 'icon' => 'fab fa-linkedin-in', 'color' => 'text-blue-800'],
                            ['name' => 'Google+ Profile', 'icon' => 'fab fa-google-plus-g', 'color' => 'text-red-500'],
                            ['name' => 'Instagram Feed', 'icon' => 'fab fa-instagram', 'color' => 'text-pink-500'],
                            ['name' => 'Skype ID', 'icon' => 'fab fa-skype', 'color' => 'text-cyan-400'],
                        ];
                        foreach($socials as $soc): ?>
                        <div class="flex items-center justify-between group cursor-pointer">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-xl bg-gray-50 flex items-center justify-center mr-4 group-hover:bg-white group-hover:shadow-lg transition-all">
                                    <i class="<?php echo $soc['icon']; ?> <?php echo $soc['color']; ?> text-xs"></i>
                                </div>
                                <span class="text-[10px] text-gray-400 font-medium"><?php echo $soc['name']; ?></span>
                            </div>
                            <i class="fas fa-link text-[8px] text-gray-200 group-hover:text-primary transition-colors"></i>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
@endsection
