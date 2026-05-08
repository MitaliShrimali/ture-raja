@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <h1 class="text-5xl font-black text-foreground tracking-tight">Content Management</h1>
            <p class="text-muted-text font-medium">Edit and manage all static pages and system content.</p>
        </div>
        <button class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
            <i data-lucide="plus" size="20"></i> Create New Page
        </button>
    </div>

    <!-- CMS Categories -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6 group hover:shadow-premium transition-all">
            <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-3xl flex items-center justify-center">
                <i data-lucide="file-text" size="32"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Legal Pages</p>
                <h4 class="text-2xl font-black text-foreground tracking-tight">04 Pages</h4>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6 group hover:shadow-premium transition-all">
            <div class="w-16 h-16 bg-purple-50 text-purple-500 rounded-3xl flex items-center justify-center">
                <i data-lucide="info" size="32"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">General Info</p>
                <h4 class="text-2xl font-black text-foreground tracking-tight">12 Pages</h4>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6 group hover:shadow-premium transition-all">
            <div class="w-16 h-16 bg-green-50 text-green-500 rounded-3xl flex items-center justify-center">
                <i data-lucide="message-square" size="32"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Support Content</p>
                <h4 class="text-2xl font-black text-foreground tracking-tight">08 Pages</h4>
            </div>
        </div>
    </div>

    <!-- Page Table -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex items-center justify-between">
            <h3 class="text-xl font-black">Platform Pages</h3>
            <div class="flex items-center gap-3">
                <button class="p-2.5 text-muted-text hover:text-foreground"><i data-lucide="filter" size="20"></i></button>
            </div>
        </div>
        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">PAGE TITLE</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SLUG / URL</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">LAST UPDATED</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @php
                        $cmsPages = [
                            ['title' => 'Terms of Services', 'slug' => '/terms-of-service', 'updated' => '2 days ago', 'status' => 'Published'],
                            ['title' => 'Privacy Policy', 'slug' => '/privacy-policy', 'updated' => '1 week ago', 'status' => 'Published'],
                            ['title' => 'Refund Policy', 'slug' => '/refunds', 'updated' => '3 hours ago', 'status' => 'Draft'],
                            ['title' => 'About Us', 'slug' => '/about', 'updated' => 'Oct 12, 2023', 'status' => 'Published'],
                            ['title' => 'Contact Support', 'slug' => '/contact', 'updated' => 'Sep 05, 2023', 'status' => 'Published'],
                        ];
                    @endphp
                    @foreach($cmsPages as $page)
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-10">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-muted-text">
                                        <i data-lucide="layout" size="14"></i>
                                    </div>
                                    <span class="text-sm font-bold text-foreground">{{ $page['title'] }}</span>
                                </div>
                            </td>
                            <td class="py-6 px-10">
                                <code class="bg-gray-50 px-3 py-1 rounded-lg text-[10px] font-black text-primary">{{ $page['slug'] }}</code>
                            </td>
                            <td class="py-6 px-10 text-sm font-medium text-muted-text">{{ $page['updated'] }}</td>
                            <td class="py-6 px-10">
                                <span class="px-3 py-1 rounded-full {{ $page['status'] === 'Published' ? 'bg-green-50 text-green-500' : 'bg-yellow-50 text-yellow-600' }} text-[10px] font-black uppercase tracking-wider">
                                    {{ $page['status'] }}
                                </span>
                            </td>
                            <td class="py-6 px-10 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="edit-3" size="18"></i></button>
                                    <button class="p-2 text-muted-text hover:text-red-500 transition-colors"><i data-lucide="trash-2" size="18"></i></button>
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
