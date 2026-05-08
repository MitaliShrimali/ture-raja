@extends('layouts.app')

@section('content')
    <div x-data="{ activeTab: 'wishlist' }">
        <!-- Profile Header -->
        <div class="relative pt-32 pb-48 bg-foreground overflow-hidden">
            <div class="absolute inset-0 z-0 opacity-30">
                <img src="{{ asset('tourex/hero-bg.png') }}" alt="Profile Cover" class="w-full h-full object-cover" />
            </div>
            <div class="container-custom relative z-10 flex flex-col items-center text-center text-white">
                <div class="relative mb-6">
                    <div class="w-32 h-32 md:w-40 md:h-40 rounded-[40px] border-4 border-white overflow-hidden shadow-2xl">
                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=John" alt="Avatar" class="w-full h-full object-cover bg-white" />
                    </div>
                    <button class="absolute bottom-2 right-2 w-10 h-10 bg-primary text-white rounded-2xl flex items-center justify-center shadow-lg hover:bg-primary-hover transition-colors">
                        <i data-lucide="camera" size="20"></i>
                    </button>
                </div>
                <h1 class="text-4xl font-black mb-2 font-syne">John Doe</h1>
                <div class="flex items-center gap-4 text-white/60 font-medium">
                    <div class="flex items-center gap-1">
                        <i data-lucide="map-pin" size="16"></i>
                        <span>New York, USA</span>
                    </div>
                    <div class="w-1 h-1 bg-white/20 rounded-full"></div>
                    <div class="flex items-center gap-1">
                        <i data-lucide="calendar" size="16"></i>
                        <span>Joined April 2024</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard -->
        <div class="container-custom -mt-24 pb-20 relative z-20">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar Tabs -->
                <div class="lg:w-1/4 shrink-0">
                    <div class="bg-white rounded-[32px] p-4 shadow-soft border border-gray-50 flex flex-col">
                        <button
                            @click="activeTab = 'profile'"
                            :class="activeTab === 'profile' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-400 hover:text-primary hover:bg-primary/5'"
                            class="flex items-center gap-4 p-5 rounded-[20px] font-bold transition-all"
                        >
                            <i data-lucide="user" size="22"></i>
                            <span>Personal Info</span>
                        </button>
                        <button
                            @click="activeTab = 'wishlist'"
                            :class="activeTab === 'wishlist' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-400 hover:text-primary hover:bg-primary/5'"
                            class="flex items-center gap-4 p-5 rounded-[20px] font-bold transition-all"
                        >
                            <i data-lucide="heart" size="22"></i>
                            <span>My Wishlist</span>
                        </button>
                        <button
                            @click="activeTab = 'history'"
                            :class="activeTab === 'history' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-400 hover:text-primary hover:bg-primary/5'"
                            class="flex items-center gap-4 p-5 rounded-[20px] font-bold transition-all"
                        >
                            <i data-lucide="history" size="22"></i>
                            <span>Booking History</span>
                        </button>
                        <button
                            @click="activeTab = 'settings'"
                            :class="activeTab === 'settings' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-400 hover:text-primary hover:bg-primary/5'"
                            class="flex items-center gap-4 p-5 rounded-[20px] font-bold transition-all"
                        >
                            <i data-lucide="settings" size="22"></i>
                            <span>Settings</span>
                        </button>
                        <hr class="my-4 border-gray-100" />
                        <button class="flex items-center gap-4 p-5 rounded-[20px] font-bold text-red-500 hover:bg-red-50 transition-all">
                            <i data-lucide="log-out" size="22"></i>
                            <span>Sign Out</span>
                        </button>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="flex-1 space-y-8">
                    <!-- Wishlist Tab -->
                    <div x-show="activeTab === 'wishlist'" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <div class="flex items-center justify-between">
                            <h2 class="text-3xl font-black text-foreground font-syne">Saved Packages</h2>
                            <span class="bg-primary/10 text-primary px-4 py-1.5 rounded-full font-bold text-sm">
                                4 Saved
                            </span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- In a real app, these would come from the user's wishlist -->
                            @foreach($packages->take(2) as $pkg)
                                <x-package-card :pkg="$pkg" />
                            @endforeach
                        </div>
                    </div>

                    <!-- Profile Tab -->
                    <div x-show="activeTab === 'profile'" class="bg-white rounded-[32px] p-8 md:p-12 shadow-soft border border-gray-50 space-y-10 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <h2 class="text-3xl font-black font-syne">Personal Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-gray-400">First Name</label>
                                <input type="text" value="John" class="w-full bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Last Name</label>
                                <input type="text" value="Doe" class="w-full bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold" />
                            </div>
                            <div class="space-y-2 md:col-span-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Email Address</label>
                                <input type="email" value="john.doe@example.com" class="w-full bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Phone Number</label>
                                <input type="text" value="+1 (234) 567-890" class="w-full bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Date of Birth</label>
                                <input type="text" value="25 Jan 1990" class="w-full bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold" />
                            </div>
                        </div>
                        <button class="bg-primary hover:bg-primary-hover text-white px-10 py-4 rounded-full font-bold transition-all shadow-lg shadow-primary/20">
                            Save Changes
                        </button>
                    </div>

                    <!-- History Tab -->
                    <div x-show="activeTab === 'history'" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                        <h2 class="text-3xl font-black text-foreground font-syne">Recent Bookings</h2>
                        <div class="space-y-6">
                            @foreach($packages->take(2) as $pkg)
                                <div class="bg-white rounded-[32px] p-6 shadow-soft border border-gray-50 flex flex-col md:flex-row items-center gap-6 hover:shadow-card transition-all">
                                    <div class="w-full md:w-48 h-32 rounded-2xl overflow-hidden shrink-0">
                                        <img src="{{ asset($pkg['image']) }}" alt="{{ $pkg['title'] }}" class="w-full h-full object-cover" />
                                    </div>
                                    <div class="flex-1 space-y-1 text-left">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-primary font-bold text-xs uppercase tracking-widest">Confirmed</span>
                                            <span class="text-gray-400 text-sm font-medium">Booked on 12 Apr 2024</span>
                                        </div>
                                        <h4 class="text-xl font-bold">{{ $pkg['title'] }}</h4>
                                        <p class="text-gray-400 font-medium">Package Price: ₹{{ $pkg['price'] }}</p>
                                    </div>
                                    <button class="w-full md:w-auto px-8 py-3 bg-foreground text-white rounded-xl font-bold hover:bg-black transition-colors">
                                        View Details
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
