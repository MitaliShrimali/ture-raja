@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12 max-w-5xl mx-auto">
    <div class="flex items-center justify-between">
        <div class="space-y-2">
            <h1 class="text-5xl font-black text-foreground tracking-tight">Holiday Types</h1>
            <p class="text-muted-text font-medium">Categorize your travel offerings for better discovery.</p>
        </div>
        <button class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
            <i data-lucide="plus" size="20"></i> Add Category
        </button>
    </div>

    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">CATEGORY NAME</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">INVENTORY</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @php
                        $holidayTypes = [
                            ['sr' => '01', 'name' => 'Beach & Island', 'icon' => 'umbrella', 'count' => '124 Packages', 'status' => 'Active'],
                            ['sr' => '02', 'name' => 'Mountain Trekking', 'icon' => 'mountain', 'count' => '86 Packages', 'status' => 'Active'],
                            ['sr' => '03', 'name' => 'Wildlife Safari', 'icon' => 'tent', 'count' => '42 Packages', 'status' => 'Active'],
                            ['sr' => '04', 'name' => 'Cruise & Sailing', 'icon' => 'ship', 'count' => '15 Packages', 'status' => 'Active'],
                        ];
                    @endphp
                    @foreach($holidayTypes as $item)
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{{ $item['sr'] }}</td>
                            <td class="py-6 px-10">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-primary/5 rounded-xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                        <i data-lucide="{{ $item['icon'] }}" size="18"></i>
                                    </div>
                                    <span class="text-sm font-black text-foreground">{{ $item['name'] }}</span>
                                </div>
                            </td>
                            <td class="py-6 px-10 text-sm font-bold text-muted-text">{{ $item['count'] }}</td>
                            <td class="py-6 px-10">
                                <span class="px-3 py-1 rounded-full bg-green-50 text-green-500 text-[10px] font-black uppercase tracking-wider">
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
