@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12" x-data="{ showAddModal: false, showEditModal: false, editUser: { id: '', name: '', email: '', role: '' }, addRole: 'SUPER ADMIN' }">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Admin / User Management</p>
            <h2 class="font-black text-foreground tracking-tight">Admin User</h2>
            <p class="text-muted-text font-medium">Manage and delegate access to your platform team.</p>
        </div>
        <button @click="showAddModal = true" class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3 group">
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

            <!-- Search Form -->
            <form method="GET" action="{{ url('/admin/users') }}" class="relative group w-full md:w-96">
                <i data-lucide="search" class="absolute left-5 top-1/2 -translate-y-1/2 text-muted-text group-focus-within:text-primary transition-colors" size="18"></i>
                <input 
                    type="text" 
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Search user by name or email..." 
                    class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-14 pr-6 outline-none focus:ring-2 focus:ring-primary/10 transition-all font-medium text-sm"
                >
            </form>
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
                    @forelse($users as $index => $user)
                        @php
                            $initials = collect(explode(' ', $user->name))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                            $colors = ['bg-orange-100 text-orange-600', 'bg-blue-100 text-blue-600', 'bg-green-100 text-green-600', 'bg-purple-100 text-purple-600', 'bg-pink-100 text-pink-600'];
                            $color = $colors[$user->id % count($colors)];
                            $srNo = str_pad($users->firstItem() + $index, 2, '0', STR_PAD_LEFT);
                        @endphp
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-8 text-sm font-bold text-muted-text opacity-60">{{ $srNo }}</td>
                            <td class="py-6 px-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl {{ $color }} flex items-center justify-center font-black text-xs uppercase">
                                        {{ $initials }}
                                    </div>
                                    <span class="text-sm font-black text-foreground">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="py-6 px-8 text-sm font-medium text-muted-text">{{ $user->email }}</td>
                            <td class="py-6 px-8">
                                <span class="px-3 py-1 rounded-full {{ $user->role === 'SUPER ADMIN' ? 'bg-orange-50 text-orange-500' : ($user->role === 'MANAGER' ? 'bg-blue-50 text-blue-500' : 'bg-gray-50 text-gray-400') }} text-[10px] font-black uppercase tracking-wider">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="py-6 px-8">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        @click="showEditModal = true; editUser = { id: '{{ $user->id }}', name: '{{ addslashes($user->name) }}', email: '{{ addslashes($user->email) }}', role: '{{ $user->role }}' }"
                                        class="p-2.5 text-muted-text hover:text-primary hover:bg-primary/5 rounded-xl transition-all"
                                    >
                                        <i data-lucide="edit-3" size="18"></i>
                                    </button>
                                    <a 
                                        href="{{ url('/admin/users/delete/' . $user->id) }}" 
                                        onclick="return confirm('Are you sure you want to remove this admin user?');"
                                        class="p-2.5 text-muted-text hover:text-red-500 hover:bg-red-50 rounded-xl transition-all"
                                    >
                                        <i data-lucide="trash-2" size="18"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-sm font-bold text-muted-text">No admin users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-sm font-bold text-muted-text">Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} entries</p>
            <div class="flex items-center gap-2">
                @if($users->onFirstPage())
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-left" size="20"></i></button>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-left" size="20"></i></a>
                @endif
                
                @foreach(range(1, $users->lastPage()) as $i)
                    @if($i == 1 || $i == $users->lastPage() || abs($i - $users->currentPage()) <= 1)
                        @if($i == $users->currentPage())
                            <button class="w-10 h-10 rounded-full text-sm font-black bg-primary text-white shadow-lg shadow-primary/20 transition-all">
                                {{ $i }}
                            </button>
                        @else
                            <a href="{{ $users->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black transition-all text-muted-text hover:bg-white hover:text-primary flex items-center justify-center">
                                {{ $i }}
                            </a>
                        @endif
                    @elseif($i == 2 || $i == $users->lastPage() - 1)
                        <span class="text-muted-text font-black px-1">...</span>
                    @endif
                @endforeach
                
                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-right" size="20"></i></a>
                @else
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-right" size="20"></i></button>
                @endif
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
                    <h4 class="text-4xl font-black">{{ $users->total() }}</h4>
                    <span class="text-xs font-bold text-green-500 mb-1">+{{ DB::table('users')->where('created_at', '>=', now()->startOfMonth())->count() }} this month</span>
                </div>
            </div>
            <div class="bg-white p-8 rounded-[32px] shadow-soft border border-border-soft space-y-2">
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Active Now</p>
                <div class="flex items-center gap-3">
                    <h4 class="text-4xl font-black">2</h4>
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

    <!-- ================= MODALS ================= -->

    <!-- Add Admin User Modal -->
    <div 
        x-show="showAddModal" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        style="display: none;"
    >
        <div @click.away="showAddModal = false" class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-lg w-full overflow-hidden p-10 space-y-8">
            <div class="flex items-center justify-between border-b border-border-soft pb-4">
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-foreground">Add Admin User</h3>
                    <p class="text-xs text-muted-text font-medium">Create a new administrator credential for the platform.</p>
                </div>
                <button @click="showAddModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/users/store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Full Name<span class="text-primary">*</span></label>
                    <input required type="text" name="name" placeholder="E.g. Siti Wahyuni" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Email Address<span class="text-primary">*</span></label>
                    <input required type="email" name="email" placeholder="E.g. siti.w@tourraja.id" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Access Role<span class="text-primary">*</span></label>
                    <select required name="role" x-model="addRole" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                        <option value="SUPER ADMIN">SUPER ADMIN</option>
                        <option value="MANAGER">MANAGER</option>
                        <option value="EDITOR">EDITOR</option>
                    </select>
                </div>
                
                <!-- Warning for Super Admin role -->
                <div x-show="addRole === 'SUPER ADMIN'" class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex items-start gap-4 transition-all" x-transition>
                    <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center flex-shrink-0 border border-gray-100">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e85d26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <h5 class="text-xs font-bold text-gray-800">Security Profile: Full Access</h5>
                        <p class="text-[11px] text-gray-500 leading-relaxed font-medium">
                            The Super Admin role has unrestricted access to all modules including system configuration, financial reports, user access management, and global data deletion. All actions performed by this user are logged in the master audit trail.
                        </p>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Password<span class="text-primary">*</span></label>
                    <input required type="password" name="password" placeholder="Enter secure password" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                
                <div class="flex items-center justify-end gap-4 pt-4">
                    <button type="button" @click="showAddModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Admin User Modal -->
    <div 
        x-show="showEditModal" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        style="display: none;"
    >
        <div @click.away="showEditModal = false" class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-lg w-full overflow-hidden p-10 space-y-8">
            <div class="flex items-center justify-between border-b border-border-soft pb-4">
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-foreground">Edit Admin User</h3>
                    <p class="text-xs text-muted-text font-medium">Update the administrator credential and team permissions.</p>
                </div>
                <button @click="showEditModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/users/update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="id" x-model="editUser.id" />
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Full Name<span class="text-primary">*</span></label>
                    <input required type="text" name="name" x-model="editUser.name" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Email Address<span class="text-primary">*</span></label>
                    <input required type="email" name="email" x-model="editUser.email" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Access Role<span class="text-primary">*</span></label>
                    <select required name="role" x-model="editUser.role" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                        <option value="SUPER ADMIN">SUPER ADMIN</option>
                        <option value="MANAGER">MANAGER</option>
                        <option value="EDITOR">EDITOR</option>
                    </select>
                </div>

                <!-- Warning for Super Admin role -->
                <div x-show="editUser.role === 'SUPER ADMIN'" class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex items-start gap-4 transition-all" x-transition>
                    <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center flex-shrink-0 border border-gray-100">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#e85d26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <h5 class="text-xs font-bold text-gray-800">Security Profile: Full Access</h5>
                        <p class="text-[11px] text-gray-500 leading-relaxed font-medium">
                            The Super Admin role has unrestricted access to all modules including system configuration, financial reports, user access management, and global data deletion. All actions performed by this user are logged in the master audit trail.
                        </p>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Password<span class="text-muted-text"> (Leave blank to keep current)</span></label>
                    <input type="password" name="password" placeholder="Enter new password if updating" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                
                <div class="flex items-center justify-end gap-4 pt-4">
                    <button type="button" @click="showEditModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
