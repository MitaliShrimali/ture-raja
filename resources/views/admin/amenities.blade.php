@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12">
    <div class="flex items-center justify-between">
        <div class="space-y-2">
            <h2 class="font-black text-foreground tracking-tight">Amenities</h2>
            <p class="text-muted-text font-medium">Manage global property features and traveler perks.</p>
        </div>
        <button class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
            <i data-lucide="plus" size="20"></i> Add Amenity
        </button>
    </div>

    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">ICON & NAME</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">CATEGORY</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @php
                        $amenities = [
                            ['sr' => '01', 'name' => 'High-Speed WiFi', 'icon' => 'wifi', 'category' => 'Connectivity', 'status' => 'Active'],
                            ['sr' => '02', 'name' => 'Breakfast Included', 'icon' => 'coffee', 'category' => 'Dining', 'status' => 'Active'],
                            ['sr' => '03', 'name' => 'Free Parking', 'icon' => 'car', 'category' => 'Transport', 'status' => 'Active'],
                            ['sr' => '04', 'name' => 'Air Conditioning', 'icon' => 'wind', 'category' => 'Comfort', 'status' => 'Active'],
                            ['sr' => '05', 'name' => 'Flat-Screen TV', 'icon' => 'tv', 'category' => 'Entertainment', 'status' => 'Inactive'],
                        ];
                    @endphp
                    @foreach($amenities as $item)
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{{ $item['sr'] }}</td>
                            <td class="py-6 px-10">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-muted-text group-hover:text-primary transition-colors">
                                        <i data-lucide="{{ $item['icon'] }}" size="18"></i>
                                    </div>
                                    <span class="text-sm font-black text-foreground">{{ $item['name'] }}</span>
                                </div>
                            </td>
                            <td class="py-6 px-10 text-sm font-bold text-muted-text">{{ $item['category'] }}</td>
                            <td class="py-6 px-10">
                                <span class="px-3 py-1 rounded-full {{ $item['status'] === 'Active' ? 'bg-green-50 text-green-500' : 'bg-gray-50 text-gray-400' }} text-[10px] font-black uppercase tracking-wider">
                                    {{ $item['status'] }}
                                </span>
                            </td>
                            <td class="py-6 px-10 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="p-2 text-muted-text hover:text-primary"><i data-lucide="edit-3" size="18"></i></button>
                                    <button class="p-2 text-muted-text hover:text-red-500"><i data-lucide="trash-2" size="18"></i></button>
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
