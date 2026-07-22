@extends('layouts.admin')

@section('admin_title', 'Users Create')

@section('content')
<div class="pb-16 text-[#1A1A24]" x-data="adminData()">
    <!-- Header with Back Button & Breadcrumbs -->
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ url('/admin/users') }}" class="p-3 bg-white hover:bg-gray-100 border border-border-soft transition-all shadow-sm hover:shadow flex items-center justify-center" style="border-radius: 1rem; border: 1px solid #e4e4e7 !important;">
            <svg class="w-5 h-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </a>
        <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-widest" style="color: #71717a !important;">
            <a href="{{ url('/admin/users') }}" class="hover:text-foreground transition-colors">User Management</a>
            <svg class="w-3 h-3 opacity-60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            <span class="text-foreground" style="color: #18181b !important;">Add Admin User</span>
        </div>
    </div>

    <form action="{{ url('/admin/users/store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <!-- Main Form Container -->
        <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 flex flex-col lg:flex-row gap-12 items-start">
            
            <!-- Left Side: Avatar Card -->
            <div class="w-full lg:w-72 flex flex-col items-center gap-6 shrink-0 bg-[#f8f9fa] p-8 rounded-[32px] border border-border-soft/60">
                <div class="relative w-44 h-44 group">
                    <!-- Avatar Frame/Background -->
                    <div class="w-full h-full rounded-[38px] bg-[#2E3545] overflow-hidden flex items-center justify-center border-4 border-white shadow-md relative">
                        <img :src="previewUrl" class="w-full h-full object-cover" alt="User Avatar">
                    </div>
                    <!-- Edit Pencil Button Overlay -->
                    <button type="button" @click="triggerFileInput()" class="absolute bottom-1 -right-1 w-11 h-11 text-white rounded-full flex items-center justify-center border-4 border-white shadow-lg transition-transform hover:scale-105 active:scale-95 cursor-pointer" style="background-color: #b13c0b !important; color: #ffffff !important; z-index: 10;">
                        <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                    </button>
                    <!-- Hidden File Input -->
                    <input type="file" name="avatar" id="avatar-file-input" class="hidden" accept="image/*" @change="handleFileChange($event)">
                </div>
                
                <div class="text-center space-y-1">
                    <h4 class="text-lg font-black text-foreground" x-text="name ? name : 'New User'">New User</h4>
                    <p class="text-xs text-muted-text font-bold uppercase tracking-wider" x-text="role.toLowerCase() + ' account'">super admin account</p>
                </div>
            </div>

            <!-- Right Side: Credentials Form -->
            <div class="flex-1 w-full space-y-8">
                <div>
                    <h2 class="text-3xl font-black text-foreground tracking-tight">Add Admin User</h2>
                    <p class="text-sm text-muted-text font-medium mt-1">Create administrative credentials and platform permissions.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- User Name -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-1">User Name</label>
                        <input required type="text" name="name" x-model="name" placeholder="Enter full name" class="w-full bg-[#f4f4f6] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>

                    <!-- Email Address -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-1">Email Address</label>
                        <input required type="email" name="email" x-model="email" placeholder="Enter email address" class="w-full bg-[#f4f4f6] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>

                    <!-- Platform Role -->
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-1">Platform Role</label>
                        <div class="flex gap-3 h-14">
                            <div class="relative flex-1">
                                <select required name="role" x-model="role" class="w-full h-full bg-[#f4f4f6] border-none rounded-2xl px-6 pr-12 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-medium text-foreground shadow-sm appearance-none">
                                    <template x-for="r in roles" :key="r.id">
                                        <option :value="r.name" x-text="r.name"></option>
                                    </template>
                                </select>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-muted-text flex items-center justify-center">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                </div>
                            </div>
                            <button type="button" @click="showRoleModal = true" class="w-14 h-14 bg-[#b13c0b] hover:bg-[#8e3009] border-none rounded-2xl flex items-center justify-center transition-colors text-white shadow-sm cursor-pointer shrink-0">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-1">Password</label>
                        <div class="relative">
                            <input required :type="showPassword ? 'text' : 'password'" name="password" placeholder="••••••••" class="w-full bg-[#f4f4f6] border-none rounded-2xl py-4 pl-6 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-medium text-foreground shadow-sm" style="padding-right: 60px !important;" />
                            <button type="button" @click="showPassword = !showPassword" class="text-muted-text hover:text-foreground transition-colors flex items-center justify-center cursor-pointer" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); z-index: 10;">
                                <template x-if="showPassword">
                                    <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                </template>
                                <template x-if="!showPassword">
                                    <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </template>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-1">Confirm Password</label>
                        <div class="relative">
                            <input required type="password" placeholder="••••••••" class="w-full bg-[#f4f4f6] border-none rounded-2xl py-4 pl-6 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-medium text-foreground shadow-sm" style="padding-right: 60px !important;" />
                            <div class="text-muted-text flex items-center justify-center" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); z-index: 10;">
                                <svg class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4 border-t border-border-soft">
                    <a href="{{ url('/admin/users') }}" class="px-8 py-3.5 bg-white border border-border-soft hover:bg-gray-50 rounded-full text-xs font-black uppercase tracking-widest transition-all" style="color: #4b5563 !important;">Cancel</a>
                    <button type="submit" class="px-8 py-3.5 rounded-full text-xs font-black uppercase tracking-widest flex items-center gap-2 shadow-xl transition-all cursor-pointer" style="background-color: #b13c0b !important; color: #ffffff !important;">
                        <svg class="w-4 h-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg> Update Account
                    </button>
                </div>
            </div>
        </div>

    <!-- Permission Matrix Card -->
        <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-10">
            <div class="flex items-center gap-3 border-b border-border-soft pb-5">
                <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center">
                    <svg class="w-5 h-5" style="color: #b13c0b !important;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>
                </div>
                <h3 class="text-lg font-black text-foreground">Permission Matrix</h3>
            </div>

            <!-- Matrix Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- GENERAL -->
                <div class="space-y-4">
                    <h4 class="text-xs font-black uppercase tracking-widest border-b border-border-soft pb-2" style="color: #b13c0b !important;">General</h4>
                    <div class="flex flex-col gap-3">
                        @foreach(['Role List', 'Role Create', 'Role Edit', 'Role Delete'] as $item)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="permissions[general][{{ Str::snake($item) }}]" value="1" class="w-5 h-5 rounded-full border border-gray-300 text-[#b13c0b] focus:ring-[#b13c0b]/20 focus:ring-offset-0 focus:outline-none transition-all">
                            <span class="text-sm font-bold text-muted-text group-hover:text-foreground transition-colors">{{ $item }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- AGENTS & USERS -->
                <div class="space-y-4">
                    <h4 class="text-xs font-black uppercase tracking-widest border-b border-border-soft pb-2" style="color: #b13c0b !important;">Agents & Users</h4>
                    <div class="flex flex-col gap-3">
                        @foreach(['Travel Agent List', 'Travel Agent Create', 'Travel Agent Edit', 'Travel Agent Leads', 'Userlist List'] as $item)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="permissions[agents_users][{{ Str::snake($item) }}]" value="1" class="w-5 h-5 rounded-full border border-gray-300 text-[#b13c0b] focus:ring-[#b13c0b]/20 focus:ring-offset-0 focus:outline-none transition-all">
                            <span class="text-sm font-bold text-muted-text group-hover:text-foreground transition-colors">{{ $item }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- PACKAGES -->
                <div class="space-y-4">
                    <h4 class="text-xs font-black uppercase tracking-widest border-b border-border-soft pb-2" style="color: #b13c0b !important;">Packages</h4>
                    <div class="flex flex-col gap-3">
                        @foreach(['Package List', 'Plan List', 'Plan Create', 'Advertisement List', 'Banner List'] as $item)
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="permissions[packages][{{ Str::snake($item) }}]" value="1" class="w-5 h-5 rounded-full border border-gray-300 text-[#b13c0b] focus:ring-[#b13c0b]/20 focus:ring-offset-0 focus:outline-none transition-all">
                            <span class="text-sm font-bold text-muted-text group-hover:text-foreground transition-colors">{{ $item }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- MANAGEMENT -->
                <div class="space-y-4">
                    <h4 class="text-xs font-black uppercase tracking-widest border-b border-border-soft pb-2" style="color: #b13c0b !important;">Management</h4>
                    <div class="flex flex-col gap-3">
                        @foreach(['Contact Message', 'Lead Message', 'Mail Setup', 'Whatsapp Template', 'General Settings'] as $item)
                        @php $snake = Str::snake($item); @endphp
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="permissions[management][{{ $snake }}]" value="1" class="w-5 h-5 rounded-full border border-gray-300 text-[#b13c0b] focus:ring-[#b13c0b]/20 focus:ring-offset-0 focus:outline-none transition-all">
                            <span class="text-sm font-bold text-muted-text group-hover:text-foreground transition-colors">{{ $item }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- PAYMENT -->
                <div class="space-y-4">
                    <h4 class="text-xs font-black uppercase tracking-widest border-b border-border-soft pb-2" style="color: #b13c0b !important;">Payment</h4>
                    <div class="flex flex-col gap-3">
                        @foreach(['GST', 'Billing', 'Payment Management', 'Export Sheet'] as $item)
                        @php $snake = Str::snake($item); @endphp
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="permissions[payment][{{ $snake }}]" value="1" class="w-5 h-5 rounded-full border border-gray-300 text-[#b13c0b] focus:ring-[#b13c0b]/20 focus:ring-offset-0 focus:outline-none transition-all">
                            <span class="text-sm font-bold text-muted-text group-hover:text-foreground transition-colors">{{ $item }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- MASTERS & GEOGRAPHY -->
            <div class="space-y-6 pt-6 border-t border-border-soft">
                <h4 class="text-xs font-black uppercase tracking-widest" style="color: #b13c0b !important;">Masters & Geography</h4>
                
                <div style="display: grid; grid-template-columns: repeat(6, minmax(0, 1fr)); gap: 24px; width: 100%;">
                    <!-- AMENITIES -->
                    <div class="space-y-3">
                        <span class="text-[10px] font-black text-muted-text uppercase tracking-wider block">Amenities</span>
                        <div class="flex flex-col gap-2">
                            @foreach(['List', 'Create', 'Edit'] as $item)
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" name="permissions[masters_geography][amenities][{{ Str::lower($item) }}]" value="1" class="w-4.5 h-4.5 rounded-full border border-gray-300 text-[#b13c0b] focus:ring-[#b13c0b]/20 focus:ring-offset-0 focus:outline-none transition-all">
                                <span class="text-xs font-bold text-muted-text group-hover:text-foreground transition-colors">{{ $item }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- HOLIDAY -->
                    <div class="space-y-3">
                        <span class="text-[10px] font-black text-muted-text uppercase tracking-wider block">Holiday</span>
                        <div class="flex flex-col gap-2">
                            @foreach(['List', 'Create', 'Delete'] as $item)
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" name="permissions[masters_geography][holiday][{{ Str::lower($item) }}]" value="1" class="w-4.5 h-4.5 rounded-full border border-gray-300 text-[#b13c0b] focus:ring-[#b13c0b]/20 focus:ring-offset-0 focus:outline-none transition-all">
                                <span class="text-xs font-bold text-muted-text group-hover:text-foreground transition-colors">{{ $item }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- ACTIVITY -->
                    <div class="space-y-3">
                        <span class="text-[10px] font-black text-muted-text uppercase tracking-wider block">Activity</span>
                        <div class="flex flex-col gap-2">
                            @foreach(['List', 'Edit', 'Transit'] as $item)
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" name="permissions[masters_geography][activity][{{ Str::lower($item) }}]" value="1" class="w-4.5 h-4.5 rounded-full border border-gray-300 text-[#b13c0b] focus:ring-[#b13c0b]/20 focus:ring-offset-0 focus:outline-none transition-all">
                                <span class="text-xs font-bold text-muted-text group-hover:text-foreground transition-colors">{{ $item }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- COUNTRY -->
                    <div class="space-y-3">
                        <span class="text-[10px] font-black text-muted-text uppercase tracking-wider block">Country</span>
                        <div class="flex flex-col gap-2">
                            @foreach(['List', 'Create', 'State'] as $item)
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" name="permissions[masters_geography][country][{{ Str::lower($item) }}]" value="1" class="w-4.5 h-4.5 rounded-full border border-gray-300 text-[#b13c0b] focus:ring-[#b13c0b]/20 focus:ring-offset-0 focus:outline-none transition-all">
                                <span class="text-xs font-bold text-muted-text group-hover:text-foreground transition-colors">{{ $item }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- CITY -->
                    <div class="space-y-3">
                        <span class="text-[10px] font-black text-muted-text uppercase tracking-wider block">City</span>
                        <div class="flex flex-col gap-2">
                            @foreach(['List', 'Create', 'Delete'] as $item)
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" name="permissions[masters_geography][city][{{ Str::lower($item) }}]" value="1" class="w-4.5 h-4.5 rounded-full border border-gray-300 text-[#b13c0b] focus:ring-[#b13c0b]/20 focus:ring-offset-0 focus:outline-none transition-all">
                                <span class="text-xs font-bold text-muted-text group-hover:text-foreground transition-colors">{{ $item }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- THEME -->
                    <div class="space-y-3">
                        <span class="text-[10px] font-black text-muted-text uppercase tracking-wider block">Theme</span>
                        <div class="flex flex-col gap-2">
                            @foreach(['List', 'Create', 'Duration'] as $item)
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="checkbox" name="permissions[masters_geography][theme][{{ Str::lower($item) }}]" value="1" class="w-4.5 h-4.5 rounded-full border border-gray-300 text-[#b13c0b] focus:ring-[#b13c0b]/20 focus:ring-offset-0 focus:outline-none transition-all">
                                <span class="text-xs font-bold text-muted-text group-hover:text-foreground transition-colors">{{ $item }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        

        <!-- Super Admin Warning Banner -->
        <div x-show="role === 'SUPER ADMIN'" class="p-6 bg-orange-50/40 rounded-[32px] border border-orange-100/70 flex items-start gap-5 transition-all duration-300 shadow-sm" x-transition>
            <div class="w-12 h-12 rounded-full bg-white shadow-sm flex items-center justify-center flex-shrink-0 border border-orange-100 text-[#b13c0b]">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            </div>
            <div class="space-y-1">
                <h5 class="text-sm font-black text-gray-800" style="color: #b13c0b !important;">Security Profile: Full Access</h5>
                <p class="text-xs text-gray-500 leading-relaxed font-semibold">
                    The Super Admin role has unrestricted access to all modules including system configuration, financial reports, user access management, and global data deletion. All actions performed by this user are logged in the master audit trail.
                </p>
            </div>
        </div>


        <!-- Sticky Footer bar -->
        <div class="p-6 bg-orange-50 rounded-[32px] border border-orange-100 flex flex-col md:flex-row items-center justify-between gap-6 shadow-md">
            <div class="flex items-center gap-3">
                <div class="text-[#b13c0b]">
                    <svg class="w-5 h-5" style="color: #b13c0b !important;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                </div>
                <span class="text-xs font-bold text-gray-700">Roles can be modified at any time from the Role Management list.</span>
            </div>
            <div class="flex items-center gap-6">
                <a href="{{ url('/admin/users') }}" class="text-xs font-black uppercase tracking-wider transition-colors" style="color: #b13c0b !important;">Discard Changes</a>
                <button type="submit" class="px-8 py-4 rounded-2xl text-xs font-black uppercase tracking-wider shadow-lg transition-all cursor-pointer" style="background-color: #b13c0b !important; color: #ffffff !important;">
                    Finalize & Create Role
                </button>
            </div>
        </div>
    </form>

    <!-- Role Management Modal -->
    <div x-show="showRoleModal" class="fixed inset-0 z-[100] flex items-center justify-center" style="background-color: rgba(0,0,0,0.5); display: none;">
        <div @click.away="showRoleModal = false" class="bg-white rounded-2xl p-6 w-96 shadow-xl max-h-[80vh] flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg text-foreground">Manage Roles</h3>
                <button type="button" @click="showRoleModal = false" class="text-gray-500 hover:text-black cursor-pointer">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            
            <div class="flex gap-2 mb-4">
                <input type="text" x-model="newRoleName" placeholder="New Role Name" class="flex-1 bg-[#f4f4f6] rounded-xl px-4 py-2 outline-none text-sm font-medium text-foreground">
                <button type="button" @click="addRole()" class="px-4 py-2 bg-[#b13c0b] text-white rounded-xl text-sm font-bold cursor-pointer">Add</button>
            </div>

            <div class="flex-1 overflow-y-auto pr-2 space-y-2">
                <template x-for="r in roles" :key="r.id">
                    <div class="flex justify-between items-center bg-gray-50 p-2 rounded-lg">
                        <span class="text-sm font-semibold text-gray-700" x-text="r.name"></span>
                        <div @click.stop.prevent="deleteRole(r.id)" class="text-red-500 hover:text-red-700 cursor-pointer p-1">
                            <svg class="w-4 h-4 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    

<script>
function adminData() {
    return {
        name: '', 
        email: '', 
        role: 'SUPER ADMIN', 
        showPassword: false, 
        previewUrl: 'https://api.dicebear.com/7.x/avataaars/svg?seed=newadmin',
        roles: @json($roles),
        showRoleModal: false,
        newRoleName: '',
        addRole() {
            if (!this.newRoleName.trim()) return;
            fetch('{{ url('/admin/roles/store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name: this.newRoleName })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.roles.push(data.role);
                    this.newRoleName = '';
                    this.role = data.role.name;
                } else {
                    alert(data.message);
                }
            });
        },
        deleteRole(id) {
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ url('/admin/roles/delete') }}/' + id, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(async res => {
                if(!res.ok) throw new Error(await res.text());
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    this.roles = this.roles.filter(r => r.id != id);
                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Role successfully deleted!', type: 'success' } }));
                } else {
                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: data.message || 'Error deleting role', type: 'error' } }));
                }
            })
            .catch(err => {
                console.error("Delete error:", err);
                window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Failed to delete role.', type: 'error' } }));
            });
        },
        triggerFileInput() {
            document.getElementById('avatar-file-input').click();
        },
        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.previewUrl = URL.createObjectURL(file);
            }
        }
    }
}
</script>
@endsection
