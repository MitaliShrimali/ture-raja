@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <h1 class="text-5xl font-black text-foreground tracking-tight">Hotel Management</h1>
            <p class="text-muted-text font-medium">Manage partner properties, availability, and premium stays.</p>
        </div>
        <button class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
            <i data-lucide="plus" size="20"></i> Add New Hotel
        </button>
    </div>

    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="relative group w-full md:w-96">
                <i data-lucide="search" class="absolute left-5 top-1/2 -translate-y-1/2 text-muted-text" size="18"></i>
                <input type="text" placeholder="Search hotels by name or location..." class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-14 pr-6 outline-none text-sm font-bold">
            </div>
            <button class="flex items-center gap-2 px-6 py-3 bg-gray-50 rounded-xl text-[10px] font-black text-muted-text uppercase tracking-widest hover:bg-gray-100 transition-all">
                <i data-lucide="filter" size="16"></i> Filters
            </button>
        </div>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">HOTEL NAME & CATEGORY</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">LOCATION</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">RATING</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @php
                        $hotels = [
                            ['sr' => '01', 'name' => 'The Grand Palace', 'location' => 'Jaipur, India', 'category' => 'Luxury Resort', 'rating' => 5, 'status' => 'Published'],
                            ['sr' => '02', 'name' => 'Alpine View Inn', 'location' => 'Zermatt, Switzerland', 'category' => 'Boutique Hotel', 'rating' => 4, 'status' => 'Published'],
                            ['sr' => '03', 'name' => 'Coastal Sands Resort', 'location' => 'Goa, India', 'category' => 'Beachfront', 'rating' => 4, 'status' => 'Draft'],
                            ['sr' => '04', 'name' => 'Desert Rose Oasis', 'location' => 'Dubai, UAE', 'category' => 'Ultra Luxury', 'rating' => 5, 'status' => 'Published'],
                        ];
                    @endphp
                    @foreach($hotels as $hotel)
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{{ $hotel['sr'] }}</td>
                            <td class="py-6 px-10">
                                <div class="space-y-1">
                                    <p class="text-sm font-black text-foreground">{{ $hotel['name'] }}</p>
                                    <p class="text-[10px] font-bold text-primary uppercase tracking-widest">{{ $hotel['category'] }}</p>
                                </div>
                            </td>
                            <td class="py-6 px-10">
                                <div class="flex items-center gap-1.5 text-sm font-medium text-muted-text">
                                    <i data-lucide="map-pin" size="14" class="text-muted-text/60"></i>
                                    {{ $hotel['location'] }}
                                </div>
                            </td>
                            <td class="py-6 px-10">
                                <div class="flex items-center gap-1">
                                    @for($i = 0; $i < 5; $i++)
                                        <i data-lucide="star" size="12" class="{{ $i < $hotel['rating'] ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200' }}"></i>
                                    @endfor
                                </div>
                            </td>
                            <td class="py-6 px-10">
                                <span class="px-3 py-1 rounded-full {{ $hotel['status'] === 'Published' ? 'bg-green-50 text-green-500' : 'bg-gray-50 text-gray-400' }} text-[10px] font-black uppercase tracking-wider">
                                    {{ $hotel['status'] }}
                                </span>
                            </td>
                            <td class="py-6 px-10 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="p-2.5 text-muted-text hover:text-primary hover:bg-primary/5 rounded-xl transition-all"><i data-lucide="edit-3" size="18"></i></button>
                                    <button class="p-2.5 text-muted-text hover:text-red-500 hover:bg-red-50 rounded-xl transition-all"><i data-lucide="trash-2" size="18"></i></button>
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
