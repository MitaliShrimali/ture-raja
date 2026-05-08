@extends('layouts.app')

@section('content')
    <!-- Small Hero Header -->
    <div class="bg-primary pt-32 pb-20">
        <div class="container-custom text-white text-center">
            <h1 class="text-4xl md:text-5xl font-black mb-4 font-syne">Explore Destinations</h1>
            <p class="text-white/80 max-w-2xl mx-auto font-medium">
                Discover 100+ amazing tour packages across the globe with premium services and best prices.
            </p>
        </div>
    </div>

    <div class="container-custom py-16">
        <form action="{{ url('/listing') }}" method="GET" class="flex flex-col lg:flex-row gap-12">
            <!-- Sidebar -->
            <div class="lg:w-1/4 shrink-0">
                <x-filter-sidebar />
                
                <div class="mt-8">
                    <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white py-4 rounded-2xl font-bold transition-all shadow-lg shadow-primary/20">
                        Apply Filters
                    </button>
                    <a href="{{ url('/listing') }}" class="block text-center mt-4 text-muted-text font-bold hover:text-primary transition-colors text-sm">
                        Clear All Filters
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="flex-1 space-y-8">
                <!-- Top Bar -->
                <div class="bg-white rounded-[24px] p-6 shadow-soft flex flex-col md:flex-row items-center justify-between gap-6">
                    <div>
                        <p class="text-gray-400 font-bold uppercase tracking-widest text-xs mb-1">Search Results</p>
                        <h3 class="text-2xl font-black">Showing <span class="text-primary">{{ $packages->count() }}</span> Packages</h3>
                    </div>
                    
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <div class="relative flex-1 md:w-64">
                            <select class="w-full bg-background border border-gray-100 rounded-xl py-3 px-4 font-bold focus:outline-none appearance-none">
                                <option>Recommended</option>
                                <option>Price: Low to High</option>
                                <option>Price: High to Low</option>
                                <option>Top Rated</option>
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" size={18}></i>
                        </div>
                        <div class="flex bg-background p-1 rounded-xl">
                            <button type="button" class="p-2 bg-white shadow-soft rounded-lg text-primary">
                                <i data-lucide="layout-grid" size="20"></i>
                            </button>
                            <button type="button" class="p-2 text-gray-400 hover:text-primary transition-colors">
                                <i data-lucide="list" size="20"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                    @forelse($packages as $pkg)
                        <x-package-card :pkg="$pkg" />
                    @empty
                        <div class="col-span-full py-20 text-center space-y-4">
                            <h4 class="text-2xl font-bold">No packages found</h4>
                            <p class="text-gray-500">Try adjusting your filters or search term.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Load More -->
                @if($packages->count() > 0)
                    <div class="text-center pt-10">
                        <button type="button" class="bg-white hover:bg-gray-50 text-foreground px-10 py-5 rounded-full font-bold shadow-soft transition-all border border-gray-100">
                            Load More Results
                        </button>
                    </div>
                @endif
            </div>
        </form>
    </div>
@endsection
