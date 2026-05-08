@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <p class="text-xs font-bold text-primary uppercase tracking-widest">Inventory & Stays</p>
            <h1 class="text-5xl font-black text-foreground tracking-tight">Tour Packages</h1>
            <p class="text-muted-text font-medium">Review and approve global travel packages.</p>
        </div>
        <button class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
            <i data-lucide="plus" size="20"></i> Add New Package
        </button>
    </div>

    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="relative group w-full md:w-96">
                <i data-lucide="search" class="absolute left-5 top-1/2 -translate-y-1/2 text-muted-text group-focus-within:text-primary transition-colors" size="18"></i>
                <input 
                    type="text" 
                    placeholder="Search packages by title or location..." 
                    class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-14 pr-6 outline-none focus:ring-2 focus:ring-primary/10 transition-all font-medium text-sm"
                >
            </div>
        </div>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">ID</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">PACKAGE TITLE</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">DURATION</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">PRICE</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">STOCK</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @php
                        $packages = [
                            ['id' => '01', 'title' => 'Bali Tropical Paradise', 'location' => 'Indonesia', 'price' => '₹1,200', 'duration' => '7 Days', 'status' => 'Active', 'stock' => '12 Left'],
                            ['id' => '02', 'title' => 'Swiss Alps Adventure', 'location' => 'Switzerland', 'price' => '₹2,500', 'duration' => '10 Days', 'status' => 'Active', 'stock' => '05 Left'],
                            ['id' => '03', 'title' => 'Goa Beach Retreat', 'location' => 'India', 'price' => '₹450', 'duration' => '4 Days', 'status' => 'Draft', 'stock' => '20 Left'],
                            ['id' => '04', 'title' => 'Dubai Luxury Escape', 'location' => 'UAE', 'price' => '₹3,100', 'duration' => '6 Days', 'status' => 'Active', 'stock' => '08 Left'],
                        ];
                    @endphp
                    @foreach($packages as $pkg)
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-8 text-sm font-bold text-muted-text opacity-60">{{ $pkg['id'] }}</td>
                            <td class="py-6 px-8">
                                <div class="space-y-1">
                                    <p class="text-sm font-black text-foreground">{{ $pkg['title'] }}</p>
                                    <div class="flex items-center gap-1.5 text-xs text-muted-text">
                                        <i data-lucide="map-pin" size="12" class="text-primary"></i>
                                        <span>{{ $pkg['location'] }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-8">
                                <div class="flex items-center gap-2 text-sm font-bold text-foreground">
                                    <i data-lucide="clock" size="14" class="text-muted-text"></i>
                                    {{ $pkg['duration'] }}
                                </div>
                            </td>
                            <td class="py-6 px-8 text-sm font-black text-foreground">{{ $pkg['price'] }}</td>
                            <td class="py-6 px-8 text-sm font-bold text-orange-500">{{ $pkg['stock'] }}</td>
                            <td class="py-6 px-8">
                                <span class="px-3 py-1 rounded-full {{ $pkg['status'] === 'Active' ? 'bg-green-50 text-green-500' : 'bg-gray-50 text-gray-400' }} text-[10px] font-black uppercase tracking-wider">
                                    {{ $pkg['status'] }}
                                </span>
                            </td>
                            <td class="py-6 px-8">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="p-2.5 text-muted-text hover:text-primary hover:bg-primary/5 rounded-xl transition-all">
                                        <i data-lucide="eye" size="18"></i>
                                    </button>
                                    <button class="p-2.5 text-muted-text hover:text-primary hover:bg-primary/5 rounded-xl transition-all">
                                        <i data-lucide="edit-3" size="18"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
