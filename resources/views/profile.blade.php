@extends('layouts.app')

@section('content')
@php
    $userData = $user ?? null;
    $profileData = $profile ?? null;
    $displayName = $userData ? $userData->name : 'Guest User';
    $displayEmail = $userData ? $userData->email : '';
    $displayPhone = $profileData ? ($profileData->phone ?? '') : '';
    $displayCity = $profileData ? ($profileData->city ?? 'Mumbai') : 'Mumbai';
    $displayCountry = $profileData ? ($profileData->country ?? 'India') : 'India';
    $displayDOB = $profileData ? ($profileData->date_of_birth ?? '') : '';
    $displayGender = $profileData ? ($profileData->gender ?? '') : '';
    $avatarUrl = ($profileData && $profileData->avatar) 
        ? asset('storage/' . $profileData->avatar) 
        : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($displayName);
    $joinedDate = $userData ? \Carbon\Carbon::parse($userData->created_at)->format('F Y') : 'Recently';
    
    $wishlistItems = (isset($wishlist) && $wishlist) ? $wishlist : collect();
    $bookingItems  = (isset($bookings) && $bookings) ? $bookings : collect();
    $notifItems    = (isset($userNotifications)) ? $userNotifications : collect();
    $unread        = $unreadCount ?? 0;
@endphp

<div x-data="{ activeTab: '{{ request('tab', 'wishlist') }}' }">
    <!-- Profile Header -->
    <div class="relative pt-32 pb-48 bg-foreground overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-30">
            <img src="{{ asset('tourex/hero-bg.png') }}" alt="Profile Cover" class="w-full h-full object-cover" />
        </div>
        <div class="container-custom relative z-10 flex flex-col items-center text-center text-white">
            <div class="relative mb-6">
                <div class="w-32 h-32 md:w-40 md:h-40 rounded-[40px] border-4 border-white overflow-hidden shadow-2xl">
                    <img src="{{ $avatarUrl }}" alt="Avatar" class="w-full h-full object-cover bg-white" id="avatar-preview" />
                </div>
                <label for="avatar-upload" class="absolute bottom-2 right-2 w-10 h-10 bg-primary text-white rounded-2xl flex items-center justify-center shadow-lg hover:bg-primary-hover transition-colors cursor-pointer">
                    <i data-lucide="camera" size="20"></i>
                </label>
            </div>
            <h1 class="text-4xl font-black mb-2 font-syne">{{ $displayName }}</h1>
            <div class="flex items-center gap-4 text-white/60 font-medium">
                <div class="flex items-center gap-1">
                    <i data-lucide="map-pin" size="16"></i>
                    <span>{{ $displayCity }}, {{ $displayCountry }}</span>
                </div>
                <div class="w-1 h-1 bg-white/20 rounded-full"></div>
                <div class="flex items-center gap-1">
                    <i data-lucide="calendar" size="16"></i>
                    <span>Joined {{ $joinedDate }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="container-custom mt-4">
            <div class="p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 font-bold text-sm flex items-center gap-3">
                <i data-lucide="check-circle" size="20"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="container-custom mt-4">
            <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 font-bold text-sm flex items-center gap-3">
                <i data-lucide="alert-circle" size="20"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <!-- Main Dashboard -->
    <div class="container-custom -mt-24 pb-20 relative z-20">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Tabs -->
            <div class="lg:w-1/4 shrink-0">
                <div class="bg-white rounded-[32px] p-4 shadow-soft border border-gray-50 flex flex-col">
                    <button @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-400 hover:text-primary hover:bg-primary/5'" class="flex items-center gap-4 p-5 rounded-[20px] font-bold transition-all">
                        <i data-lucide="user" size="22"></i>
                        <span>Personal Info</span>
                    </button>
                    <button @click="activeTab = 'wishlist'" :class="activeTab === 'wishlist' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-400 hover:text-primary hover:bg-primary/5'" class="flex items-center gap-4 p-5 rounded-[20px] font-bold transition-all">
                        <i data-lucide="heart" size="22"></i>
                        <span>My Wishlist</span>
                        @if($wishlistItems->count() > 0)
                            <span class="ml-auto text-[10px] font-black px-2 py-0.5 rounded-full bg-primary/10 text-primary">{{ $wishlistItems->count() }}</span>
                        @endif
                    </button>
                    <button @click="activeTab = 'history'" :class="activeTab === 'history' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-400 hover:text-primary hover:bg-primary/5'" class="flex items-center gap-4 p-5 rounded-[20px] font-bold transition-all">
                        <i data-lucide="history" size="22"></i>
                        <span>Booking History</span>
                        @if($bookingItems->count() > 0)
                            <span class="ml-auto text-[10px] font-black px-2 py-0.5 rounded-full bg-primary/10 text-primary">{{ $bookingItems->count() }}</span>
                        @endif
                    </button>
                    <button @click="activeTab = 'notifications'" :class="activeTab === 'notifications' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-400 hover:text-primary hover:bg-primary/5'" class="flex items-center gap-4 p-5 rounded-[20px] font-bold transition-all">
                        <i data-lucide="bell" size="22"></i>
                        <span>Notifications</span>
                        @if($unread > 0)
                            <span class="ml-auto text-[10px] font-black px-2 py-0.5 rounded-full bg-red-500 text-white">{{ $unread }}</span>
                        @endif
                    </button>
                    <button @click="activeTab = 'settings'" :class="activeTab === 'settings' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-400 hover:text-primary hover:bg-primary/5'" class="flex items-center gap-4 p-5 rounded-[20px] font-bold transition-all">
                        <i data-lucide="settings" size="22"></i>
                        <span>Settings</span>
                    </button>
                    <button @click="activeTab = 'membership'" :class="activeTab === 'membership' ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-gray-400 hover:text-primary hover:bg-primary/5'" class="flex items-center gap-4 p-5 rounded-[20px] font-bold transition-all">
                        <i data-lucide="award" size="22"></i>
                        <span>Membership & Payments</span>
                        @if($activePlan)
                            <span class="ml-auto text-[10px] font-black px-2 py-0.5 rounded-full bg-green-500 text-white uppercase tracking-wider">Active</span>
                        @endif
                    </button>
                    <hr class="my-4 border-gray-100" />
                    <a href="{{ route('login') }}" class="flex items-center gap-4 p-5 rounded-[20px] font-bold text-red-500 hover:bg-red-50 transition-all">
                        <i data-lucide="log-out" size="22"></i>
                        <span>Sign Out</span>
                    </a>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="flex-1 space-y-8">

                <!-- ═══════════ WISHLIST TAB ═══════════ -->
                <div x-show="activeTab === 'wishlist'" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <div class="flex items-center justify-between">
                        <h2 class="text-3xl font-black text-foreground font-syne">Saved Packages</h2>
                        <span class="bg-primary/10 text-primary px-4 py-1.5 rounded-full font-bold text-sm">
                            {{ $wishlistItems->count() }} Saved
                        </span>
                    </div>

                    @if($wishlistItems->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @foreach($wishlistItems as $item)
                                <div class="bg-white rounded-[32px] overflow-hidden shadow-soft border border-gray-50 group hover:shadow-card transition-all">
                                    <div class="relative h-48 overflow-hidden">
                                        <img src="{{ $item->package_image ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=600' }}" 
                                             alt="{{ $item->package_title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        <a href="{{ route('wishlist.remove', $item->package_id) }}" 
                                           onclick="return confirm('Remove from wishlist?');"
                                           class="absolute top-3 right-3 w-9 h-9 bg-white/90 rounded-full flex items-center justify-center text-red-500 hover:bg-red-500 hover:text-white transition-all">
                                            <i data-lucide="heart" size="16" class="fill-current"></i>
                                        </a>
                                    </div>
                                    <div class="p-6 space-y-3">
                                        <h4 class="text-lg font-black text-foreground">{{ $item->package_title }}</h4>
                                        <p class="text-primary font-black text-xl">₹{{ number_format($item->package_price) }}</p>
                                        <a href="/packages/{{ Str::slug($item->package_title) }}" class="block w-full text-center py-3 bg-primary/10 hover:bg-primary hover:text-white text-primary rounded-xl font-bold text-sm transition-all">
                                            View Package
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        @if(method_exists($wishlistItems, 'links'))
                            <div class="mt-8">{{ $wishlistItems->links() }}</div>
                        @endif
                    @else
                        <div class="bg-white rounded-[32px] p-16 shadow-soft border border-gray-50 text-center space-y-4">
                            <div class="w-20 h-20 mx-auto bg-gray-50 rounded-full flex items-center justify-center text-gray-300">
                                <i data-lucide="heart" size="40"></i>
                            </div>
                            <h4 class="text-xl font-black text-foreground">No saved packages yet</h4>
                            <p class="text-gray-400 font-medium">Browse our packages and save the ones you love!</p>
                            <a href="{{ route('discover') }}" class="inline-block mt-4 bg-primary text-white px-8 py-4 rounded-full font-black text-sm hover:bg-primary-hover transition-all">
                                Browse Packages
                            </a>
                        </div>
                    @endif
                </div>

                <!-- ═══════════ PERSONAL INFO TAB ═══════════ -->
                <div x-show="activeTab === 'profile'" class="animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <div class="bg-white rounded-[32px] p-8 md:p-12 shadow-soft border border-gray-50 space-y-10">
                        <h2 class="text-3xl font-black font-syne">Personal Information</h2>
                        
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                            @csrf
                            {{-- Hidden avatar field --}}
                            <input type="file" name="avatar" id="avatar-upload" class="hidden" accept="image/*"
                                   onchange="previewAvatar(event)">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Full Name<span class="text-primary">*</span></label>
                                    <input type="text" name="name" required value="{{ $displayName }}" class="w-full bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Email Address<span class="text-primary">*</span></label>
                                    <input type="email" name="email" required value="{{ $displayEmail }}" class="w-full bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Phone Number</label>
                                    <input type="text" name="phone" value="{{ $displayPhone }}" placeholder="+91 99999 00000" class="w-full bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Date of Birth</label>
                                    <input type="date" name="date_of_birth" value="{{ $displayDOB }}" class="w-full bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-widest text-gray-400">City</label>
                                    <input type="text" name="city" value="{{ $displayCity }}" placeholder="Mumbai" class="w-full bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Country</label>
                                    <input type="text" name="country" value="{{ $displayCountry }}" placeholder="India" class="w-full bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Gender</label>
                                    <select name="gender" class="w-full bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ $displayGender === 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ $displayGender === 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ $displayGender === 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-10 py-4 rounded-full font-bold transition-all shadow-lg shadow-primary/20">
                                Save Changes
                            </button>
                        </form>
                    </div>
                </div>

                <!-- ═══════════ BOOKING HISTORY TAB ═══════════ -->
                <div x-show="activeTab === 'history'" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <h2 class="text-3xl font-black text-foreground font-syne">Booking History</h2>
                    <div class="space-y-6">
                        @forelse($bookingItems as $booking)
                            <div class="bg-white rounded-[32px] p-6 shadow-soft border border-gray-50 flex flex-col md:flex-row items-center gap-6 hover:shadow-card transition-all">
                                <div class="w-full md:w-48 h-32 rounded-2xl overflow-hidden shrink-0">
                                    <img src="{{ $booking->package_image ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=400' }}" 
                                         alt="{{ $booking->package_title }}" class="w-full h-full object-cover" />
                                </div>
                                <div class="flex-1 space-y-1 text-left">
                                    <div class="flex items-center justify-between mb-2 flex-wrap gap-2">
                                        <span class="font-bold text-xs uppercase tracking-widest px-3 py-1 rounded-full
                                            {{ $booking->status === 'Confirmed' ? 'bg-green-50 text-green-600' : 
                                               ($booking->status === 'Cancelled' ? 'bg-red-50 text-red-500' : 
                                                ($booking->status === 'Completed' ? 'bg-blue-50 text-blue-600' : 'bg-yellow-50 text-yellow-600')) }}">
                                            {{ $booking->status }}
                                        </span>
                                        <span class="text-gray-400 text-sm font-medium">
                                            Travel: {{ \Carbon\Carbon::parse($booking->travel_date)->format('d M Y') }}
                                        </span>
                                    </div>
                                    <h4 class="text-xl font-bold">{{ $booking->package_title }}</h4>
                                    <p class="text-gray-400 font-medium">
                                        ₹{{ number_format($booking->package_price) }} · {{ $booking->guests }} Guest(s)
                                    </p>
                                    <p class="text-xs text-gray-400">Booked {{ \Carbon\Carbon::parse($booking->created_at)->diffForHumans() }}</p>
                                </div>
                                <div class="flex flex-col gap-2 shrink-0">
                                    <a href="/packages/{{ Str::slug($booking->package_title) }}" class="px-6 py-3 bg-foreground text-white rounded-xl font-bold hover:bg-black transition-colors text-sm text-center">
                                        View Details
                                    </a>
                                    @if(in_array($booking->status, ['Pending']))
                                        <a href="{{ route('booking.cancel', $booking->id) }}" 
                                           onclick="return confirm('Cancel this booking?');"
                                           class="px-6 py-3 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl font-bold transition-colors text-sm text-center">
                                            Cancel
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="bg-white rounded-[32px] p-16 shadow-soft border border-gray-50 text-center space-y-4">
                                <div class="w-20 h-20 mx-auto bg-gray-50 rounded-full flex items-center justify-center text-gray-300">
                                    <i data-lucide="calendar-x" size="40"></i>
                                </div>
                                <h4 class="text-xl font-black text-foreground">No bookings yet</h4>
                                <p class="text-gray-400 font-medium">Start planning your next adventure!</p>
                                <a href="{{ route('discover') }}" class="inline-block mt-4 bg-primary text-white px-8 py-4 rounded-full font-black text-sm hover:bg-primary-hover transition-all">
                                    Browse Packages
                                </a>
                            </div>
                        @endforelse
                    </div>

                    @if(method_exists($bookingItems, 'links'))
                        <div class="mt-4">{{ $bookingItems->links() }}</div>
                    @endif
                </div>

                <!-- ═══════════ NOTIFICATIONS TAB ═══════════ -->
                <div x-show="activeTab === 'notifications'" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <div class="flex items-center justify-between">
                        <h2 class="text-3xl font-black text-foreground font-syne">Notifications</h2>
                        @if($unread > 0)
                            <span class="bg-red-50 text-red-500 px-4 py-1.5 rounded-full font-bold text-sm">{{ $unread }} Unread</span>
                        @endif
                    </div>
                    <div class="space-y-4">
                        @forelse($notifItems as $notif)
                            <div class="bg-white rounded-[24px] p-6 shadow-soft border {{ !$notif->is_read ? 'border-primary/20 bg-primary/5' : 'border-gray-50' }} flex items-start gap-4 transition-all">
                                <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0
                                    {{ $notif->type === 'Alert' ? 'bg-red-50 text-red-500' : 
                                       ($notif->type === 'Promo' ? 'bg-orange-50 text-orange-500' : 'bg-blue-50 text-blue-500') }}">
                                    <i data-lucide="{{ $notif->type === 'Alert' ? 'alert-triangle' : ($notif->type === 'Promo' ? 'tag' : 'bell') }}" size="20"></i>
                                </div>
                                <div class="flex-1 space-y-1">
                                    <div class="flex items-center justify-between gap-2 flex-wrap">
                                        <p class="font-black text-foreground">{{ $notif->title }}</p>
                                        <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-500 font-medium">{{ $notif->message }}</p>
                                </div>
                                @if(!$notif->is_read)
                                    <a href="{{ route('notification.read', $notif->id) }}" class="shrink-0 p-2 text-primary hover:bg-primary hover:text-white rounded-xl transition-all" title="Mark as read">
                                        <i data-lucide="check" size="16"></i>
                                    </a>
                                @endif
                            </div>
                        @empty
                            <div class="bg-white rounded-[32px] p-16 shadow-soft border border-gray-50 text-center space-y-4">
                                <div class="w-20 h-20 mx-auto bg-gray-50 rounded-full flex items-center justify-center text-gray-300">
                                    <i data-lucide="bell-off" size="40"></i>
                                </div>
                                <h4 class="text-xl font-black text-foreground">No notifications yet</h4>
                                <p class="text-gray-400 font-medium">We'll notify you of offers, booking updates, and more!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- ═══════════ SETTINGS TAB ═══════════ -->
                <div x-show="activeTab === 'settings'" class="animate-in fade-in slide-in-from-bottom-4 duration-500 space-y-8">
                    <!-- Change Password -->
                    <div class="bg-white rounded-[32px] p-8 md:p-12 shadow-soft border border-gray-50 space-y-8">
                        <h2 class="text-3xl font-black font-syne">Change Password</h2>
                        <form action="{{ route('profile.password') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Current Password</label>
                                <input type="password" name="current_password" required placeholder="••••••••" class="w-full bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-widest text-gray-400">New Password</label>
                                    <input type="password" name="new_password" required placeholder="Min 8 characters" class="w-full bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-widest text-gray-400">Confirm New Password</label>
                                    <input type="password" name="new_password_confirmation" required placeholder="Re-enter password" class="w-full bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                                </div>
                            </div>
                            <button type="submit" class="bg-foreground hover:bg-black text-white px-10 py-4 rounded-full font-bold transition-all shadow-lg">
                                Update Password
                            </button>
                        </form>
                    </div>
                    
                    <!-- Newsletter Preference -->
                    <div class="bg-white rounded-[32px] p-8 md:p-12 shadow-soft border border-gray-50 space-y-6">
                        <h2 class="text-3xl font-black font-syne">Newsletter</h2>
                        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-4">
                            @csrf
                            <input type="email" name="email" value="{{ $displayEmail }}" placeholder="your@email.com" class="flex-1 bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold focus:ring-2 focus:ring-primary/20 transition-all" />
                            <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-full font-bold transition-all shadow-lg shadow-primary/20 shrink-0">
                                Subscribe
                            </button>
                        </form>
                        <p class="text-xs text-gray-400 font-bold">No spam. Exclusive travel deals only.</p>
                    </div>
                </div>

                <!-- ═══════════ MEMBERSHIP & PAYMENTS TAB ═══════════ -->
                <div x-show="activeTab === 'membership'" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                    <div class="flex items-center justify-between">
                        <h2 class="text-3xl font-black text-foreground font-syne">Membership & Billing</h2>
                    </div>

                    @if($activePlan)
                        <!-- Active Plan Premium Card -->
                        <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 via-purple-950 to-slate-900 text-white rounded-[32px] p-8 md:p-10 shadow-lg">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/10 rounded-full blur-3xl -mr-20 -mt-20"></div>
                            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                                <div class="space-y-4">
                                    <span class="inline-block px-4 py-1.5 bg-primary/20 border border-primary/30 rounded-full text-xs font-black text-primary uppercase tracking-widest">
                                        Active Plan
                                    </span>
                                    <h3 class="text-3xl font-black font-syne">{{ $activePlan->plan_name }}</h3>
                                    <div class="flex items-center gap-6 text-white/75 font-medium text-sm">
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="check-circle" size="16" class="text-green-400"></i>
                                            <span>Price: ₹{{ number_format($activePlan->price) }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="calendar" size="16"></i>
                                            <span>Duration: {{ $activePlan->duration }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                                            <span>Status: {{ $activePlan->status }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center border border-white/10 shrink-0">
                                    <i data-lucide="crown" class="text-primary animate-bounce" size="32"></i>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- No Active Plan - VIP Pitch Card -->
                        <div class="relative overflow-hidden bg-gradient-to-r from-slate-950 via-slate-800 to-slate-950 text-white rounded-[32px] p-8 md:p-10 shadow-lg">
                            <div class="absolute inset-0 bg-cover bg-center opacity-10" style="background-image: url('{{ asset('tourex/hero-bg.png') }}')"></div>
                            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                                <div class="space-y-4">
                                    <h3 class="text-3xl font-black font-syne">Unlock the TourRaja VIP Club</h3>
                                    <p class="text-white/70 max-w-lg text-sm leading-relaxed font-medium">
                                        Get access to customized travel plans, premium local tour guides, zero booking fees, and 24/7 dedicated travel advisor support!
                                    </p>
                                    <div class="flex items-center gap-4 text-xs font-bold text-white/50 uppercase tracking-wider">
                                        <span>✓ Zero Booking Fees</span>
                                        <span>✓ 24/7 Support</span>
                                        <span>✓ Exclusive Discounts</span>
                                    </div>
                                </div>
                                <a href="{{ route('discover') }}" class="px-8 py-4 bg-primary hover:bg-primary-hover text-white rounded-full font-black text-sm uppercase tracking-wider transition-all shrink-0">
                                    Explore Packages
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Payment Logs / Transaction History -->
                    <div class="bg-white rounded-[32px] p-8 md:p-10 shadow-soft border border-gray-50 space-y-6">
                        <h3 class="text-2xl font-black text-foreground font-syne">Transaction History</h3>
                        
                        @if(count($userPayments) > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead>
                                        <tr class="border-b border-gray-100 pb-4">
                                            <th class="text-xs font-bold uppercase tracking-widest text-gray-400 py-4">Transaction ID</th>
                                            <th class="text-xs font-bold uppercase tracking-widest text-gray-400 py-4">Plan / Service</th>
                                            <th class="text-xs font-bold uppercase tracking-widest text-gray-400 py-4">Amount</th>
                                            <th class="text-xs font-bold uppercase tracking-widest text-gray-400 py-4">Date</th>
                                            <th class="text-xs font-bold uppercase tracking-widest text-gray-400 py-4">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @foreach($userPayments as $pay)
                                            <tr>
                                                <td class="py-4 font-mono font-bold text-sm text-foreground">{{ $pay->payment_id }}</td>
                                                <td class="py-4 font-bold text-sm text-foreground">{{ $pay->plan_type }}</td>
                                                <td class="py-4 font-black text-sm text-primary">₹{{ number_format($pay->amount, 2) }}</td>
                                                <td class="py-4 text-sm text-gray-400 font-medium">{{ \Carbon\Carbon::parse($pay->date)->format('d M Y') }}</td>
                                                <td class="py-4">
                                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider
                                                        {{ $pay->status === 'Completed' ? 'bg-green-50 text-green-600' : 'bg-yellow-50 text-yellow-600' }}">
                                                        {{ $pay->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-400 font-medium text-sm">
                                <i data-lucide="receipt" class="mx-auto text-gray-300 mb-3" size="32"></i>
                                <p>No billing or payment history available.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div><!-- /tab content -->
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewAvatar(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('avatar-preview').src = e.target.result;
    };
    reader.readAsDataURL(file);
    // Auto-submit the profile form when a new avatar is selected
    event.target.closest('form') && event.target.closest('form').submit();
}
</script>
@endpush
@endsection
