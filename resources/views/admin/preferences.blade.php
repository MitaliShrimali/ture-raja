@extends('layouts.admin')

@section('admin_title', 'Preferences')

@section('content')
<div class="space-y-8 pb-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <a href="{{ url('admin/settings') }}" class="p-1.5 hover:bg-gray-100 rounded-lg text-gray-500 transition-colors">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Platform Preferences</h2>
            </div>
            <p class="text-sm text-gray-500 font-medium max-w-2xl leading-relaxed pl-9">
                Customize structural metadata, default options, and taxonomy lists across the application.
            </p>
        </div>
    </div>

    <!-- Preferences Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        
        <!-- Hotel Category -->
        <a href="{{ url('admin/settings/preferences/hotel-categories') }}" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-start group">
            <div class="w-12 h-12 rounded-2xl bg-[#FFF5F2] flex items-center justify-center text-[#B23B06] mb-5 group-hover:scale-110 transition-transform">
                <i data-lucide="home" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-black text-gray-900 mb-1 group-hover:text-[#B23B06] transition-colors">Hotel Category</h3>
            <p class="text-xs text-gray-400 font-semibold leading-relaxed">
                Manage lodging classification levels (5 Star, Guest House, Apartment).
            </p>
        </a>
 
        <!-- Amenities -->
        <a href="{{ url('admin/settings/preferences/amenities') }}" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-start group">
            <div class="w-12 h-12 rounded-2xl bg-[#FFF5F2] flex items-center justify-center text-[#B23B06] mb-5 group-hover:scale-110 transition-transform">
                <i data-lucide="archive" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-black text-gray-900 mb-1 group-hover:text-[#B23B06] transition-colors">Amenities</h3>
            <p class="text-xs text-gray-400 font-semibold leading-relaxed">
                Define amenities like Pool, Wi-Fi, and Spa packages.
            </p>
        </a>
 
        <!-- Holiday Type -->
        <a href="{{ url('admin/settings/preferences/holiday-types') }}" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-start group">
            <div class="w-12 h-12 rounded-2xl bg-[#FFF5F2] flex items-center justify-center text-[#B23B06] mb-5 group-hover:scale-110 transition-transform">
                <i data-lucide="calendar-days" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-black text-gray-900 mb-1 group-hover:text-[#B23B06] transition-colors">Holiday Type</h3>
            <p class="text-xs text-gray-400 font-semibold leading-relaxed">
                Group packages under Family, Honeymoon, or Solo categories.
            </p>
        </a>
 
        <!-- Activity -->
        <a href="{{ url('admin/settings/preferences/activities') }}" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-start group">
            <div class="w-12 h-12 rounded-2xl bg-[#FFF5F2] flex items-center justify-center text-[#B23B06] mb-5 group-hover:scale-110 transition-transform">
                <i data-lucide="gauge" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-black text-gray-900 mb-1 group-hover:text-[#B23B06] transition-colors">Activity</h3>
            <p class="text-xs text-gray-400 font-semibold leading-relaxed">
                Manage outdoor excursions, hikes, and local events.
            </p>
        </a>
 
        <!-- Transit -->
        <a href="{{ url('admin/settings/preferences/transits') }}" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-start group">
            <div class="w-12 h-12 rounded-2xl bg-[#FFF5F2] flex items-center justify-center text-[#B23B06] mb-5 group-hover:scale-110 transition-transform">
                <i data-lucide="rocket" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-black text-gray-900 mb-1 group-hover:text-[#B23B06] transition-colors">Transit</h3>
            <p class="text-xs text-gray-400 font-semibold leading-relaxed">
                Define flights, trains, cruises, and custom transit models.
            </p>
        </a>
 
        <!-- Duration -->
        <a href="{{ url('admin/settings/preferences/durations') }}" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-start group">
            <div class="w-12 h-12 rounded-2xl bg-[#FFF5F2] flex items-center justify-center text-[#B23B06] mb-5 group-hover:scale-110 transition-transform">
                <i data-lucide="hourglass" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-black text-gray-900 mb-1 group-hover:text-[#B23B06] transition-colors">Duration</h3>
            <p class="text-xs text-gray-400 font-semibold leading-relaxed">
                Configure default duration templates for packages.
            </p>
        </a>

        <!-- Theme -->
        <a href="{{ url('admin/settings/preferences/themes') }}" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-start group">
            <div class="w-12 h-12 rounded-2xl bg-[#FFF5F2] flex items-center justify-center text-[#B23B06] mb-5 group-hover:scale-110 transition-transform">
                <i data-lucide="hexagon" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-black text-gray-900 mb-1 group-hover:text-[#B23B06] transition-colors">Theme</h3>
            <p class="text-xs text-gray-400 font-semibold leading-relaxed">
                Manage visual styling and themes for travel destinations.
            </p>
        </a>

        <!-- Country -->
        <a href="{{ url('admin/settings/preferences/countries') }}" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-start group">
            <div class="w-12 h-12 rounded-2xl bg-[#FFF5F2] flex items-center justify-center text-[#B23B06] mb-5 group-hover:scale-110 transition-transform">
                <i data-lucide="globe" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-black text-gray-900 mb-1 group-hover:text-[#B23B06] transition-colors">Country</h3>
            <p class="text-xs text-gray-400 font-semibold leading-relaxed">
                Manage global country registry and visa templates.
            </p>
        </a>

        <!-- State -->
        <a href="#" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-start group opacity-75">
            <div class="w-12 h-12 rounded-2xl bg-[#FFF5F2] flex items-center justify-center text-[#B23B06] mb-5 group-hover:scale-110 transition-transform">
                <i data-lucide="disc" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-black text-gray-900 mb-1">State</h3>
            <p class="text-xs text-gray-400 font-semibold leading-relaxed">
                List regional states, provinces, and territories.
            </p>
        </a>

        <!-- City -->
        <a href="#" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-start group opacity-75">
            <div class="w-12 h-12 rounded-2xl bg-[#FFF5F2] flex items-center justify-center text-[#B23B06] mb-5 group-hover:scale-110 transition-transform">
                <i data-lucide="map-pin" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-black text-gray-900 mb-1">City</h3>
            <p class="text-xs text-gray-400 font-semibold leading-relaxed">
                Organize travel destination cities and hot spots.
            </p>
        </a>

    </div>
</div>
@endsection
