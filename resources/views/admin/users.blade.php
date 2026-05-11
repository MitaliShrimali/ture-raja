@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Admin / User Management</p>
            <h2 class="font-black text-foreground tracking-tight">Admin User</h2>
            <p class="text-muted-text font-medium">Manage and delegate access to your platform team.</p>
        </div>
        <button class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3 group">
            <i data-lucide="plus" size="20" class="group-hover:rotate-90 transition-transform"></i>
            Add Admin User
        </button>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4 text-sm font-bold text-muted-text">
                <span>Show</span>
                <select class="bg-gray-50 border-none rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-primary/20">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>
                <span>entries</span>
            </div>

            <div class="relative group w-full md:w-96">
                <i data-lucide="search" class="absolute left-5 top-1/2 -translate-y-1/2 text-muted-text group-focus-within:text-primary transition-colors" size="18"></i>
                <input 
                    type="text" 
                    placeholder="Search user by name or email..." 
                    class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-14 pr-6 outline-none focus:ring-2 focus:ring-primary/10 transition-all font-medium text-sm"
                >
            </div>
        </div>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">NAME</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">EMAIL</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">ROLE</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @php
                        $users = [
                            ['sr' => '01', 'name' => 'Rian Jatmiko', 'email' => 'rian_j@tourraja.id', 'role' => 'SUPER ADMIN', 'initials' => 'RJ', 'color' => 'bg-orange-100 text-orange-600'],
                            ['sr' => '02', 'name' => 'Siti Wahyuni', 'email' => 'siti.w@tourraja.id', 'role' => 'MANAGER', 'initials' => 'SW', 'color' => 'bg-blue-100 text-blue-600'],
                            ['sr' => '03', 'name' => 'Budi Antoro', 'email' => 'budi.a@tourraja.id', 'role' => 'EDITOR', 'initials' => 'BA', 'color' => 'bg-green-100 text-green-600'],
                            ['sr' => '04', 'name' => 'Dewi Anggraeni', 'email' => 'dewi.a@tourraja.id', 'role' => 'EDITOR', 'initials' => 'DA', 'color' => 'bg-purple-100 text-purple-600'],
                            ['sr' => '05', 'name' => 'Hendra Rusli', 'email' => 'hendra.r@tourraja.id', 'role' => 'MANAGER', 'initials' => 'HR', 'color' => 'bg-pink-100 text-pink-600'],
                        ];
                    @endphp
                    @foreach($users as $user)
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-8 text-sm font-bold text-muted-text opacity-60">{{ $user['sr'] }}</td>
                            <td class="py-6 px-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl {{ $user['color'] }} flex items-center justify-center font-black text-xs">
                                        {{ $user['initials'] }}
                                    </div>
                                    <span class="text-sm font-black text-foreground">{{ $user['name'] }}</span>
                                </div>
                            </td>
                            <td class="py-6 px-8 text-sm font-medium text-muted-text">{{ $user['email'] }}</td>
                            <td class="py-6 px-8">
                                <span class="px-3 py-1 rounded-full {{ $user['role'] === 'SUPER ADMIN' ? 'bg-orange-50 text-orange-500' : 'bg-gray-50 text-gray-400' }} text-[10px] font-black uppercase tracking-wider">
                                    {{ $user['role'] }}
                                </span>
                            </td>
                            <td class="py-6 px-8">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="p-2.5 text-muted-text hover:text-primary hover:bg-primary/5 rounded-xl transition-all">
                                        <i data-lucide="edit-3" size="18"></i>
                                    </button>
                                    <button class="p-2.5 text-muted-text hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                        <i data-lucide="trash-2" size="18"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-sm font-bold text-muted-text">Showing 1 to 5 of 48 entries</p>
            <div class="flex items-center gap-2">
                <button class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-left" size="20"></i></button>
                @foreach([1, 2, 3, "...", 10] as $p)
                    <button class="w-10 h-10 rounded-full text-sm font-black transition-all {{ $p === 1 ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-muted-text hover:bg-white hover:text-primary' }}">
                        {{ $p }}
                    </button>
                @endforeach
                <button class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-right" size="20"></i></button>
            </div>
        </div>
    </div>

    <!-- Metrics Section -->
    <div class="space-y-6">
        <h3 class="text-2xl font-black text-foreground flex items-center gap-3">
            <div class="w-1.5 h-6 bg-primary rounded-full"></div>
            Access Distribution
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-8 rounded-[32px] shadow-soft border border-border-soft space-y-2">
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Total Admins</p>
                <div class="flex items-end gap-3">
                    <h4 class="text-4xl font-black">48</h4>
                    <span class="text-xs font-bold text-green-500 mb-1">+2 this month</span>
                </div>
            </div>
            <div class="bg-white p-8 rounded-[32px] shadow-soft border border-border-soft space-y-2">
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Active Now</p>
                <div class="flex items-center gap-3">
                    <h4 class="text-4xl font-black">12</h4>
                    <div class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></div>
                </div>
            </div>
            <div class="bg-white p-8 rounded-[32px] shadow-soft border border-border-soft space-y-2">
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Permissions Audit</p>
                <div class="flex items-end gap-3">
                    <h4 class="text-4xl font-black">100%</h4>
                    <span class="text-[10px] font-bold text-muted-text uppercase mb-1">Secure</span>
                </div>
            </div>
            <div class="bg-[#1A1A24] p-8 rounded-[32px] shadow-xl text-white space-y-4 relative overflow-hidden group">
                <div class="relative z-10 space-y-1">
                    <p class="text-white/60 text-xs font-medium">Need custom roles for your expanding team?</p>
                    <button class="flex items-center gap-2 text-primary font-black uppercase text-[10px] tracking-widest pt-2 group-hover:gap-3 transition-all">
                        Manage Roles <i data-lucide="chevron-right" size="14"></i>
                    </button>
                </div>
                <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-primary/20 blur-3xl rounded-full transition-transform group-hover:scale-150"></div>
            </div>
        </div>
    </div>
</div>
@endsection
