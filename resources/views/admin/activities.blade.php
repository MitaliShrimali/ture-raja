@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12 max-w-5xl mx-auto">
    <div class="flex items-center justify-between">
        <div class="space-y-2">
            <h1 class="text-5xl font-black text-foreground tracking-tight">Activities</h1>
            <p class="text-muted-text font-medium">Define specific experiences available across tour packages.</p>
        </div>
        <button class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
            <i data-lucide="plus" size="20"></i> Add Activity
        </button>
    </div>

    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">ACTIVITY NAME</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">INTENSITY</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @php
                        $activities = [
                            ['sr' => '01', 'name' => 'Scuba Diving', 'icon' => 'zap', 'intensity' => 'High', 'status' => 'Active'],
                            ['sr' => '02', 'name' => 'Photography Tour', 'icon' => 'camera', 'intensity' => 'Low', 'status' => 'Active'],
                            ['sr' => '03', 'name' => 'City Sightseeing', 'icon' => 'compass', 'intensity' => 'Medium', 'status' => 'Active'],
                            ['sr' => '04', 'name' => 'Mountain Hiking', 'icon' => 'map', 'intensity' => 'High', 'status' => 'Active'],
                        ];
                    @endphp
                    @foreach($activities as $item)
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{{ $item['sr'] }}</td>
                            <td class="py-6 px-10">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-primary/5 rounded-xl flex items-center justify-center text-primary">
                                        <i data-lucide="{{ $item['icon'] }}" size="18"></i>
                                    </div>
                                    <span class="text-sm font-black text-foreground">{{ $item['name'] }}</span>
                                </div>
                            </td>
                            <td class="py-6 px-10">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider 
                                    {{ $item['intensity'] === 'High' ? 'bg-red-50 text-red-500' : 
                                       ($item['intensity'] === 'Medium' ? 'bg-yellow-50 text-yellow-500' : 'bg-green-50 text-green-500') }}">
                                    {{ $item['intensity'] }}
                                </span>
                            </td>
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
