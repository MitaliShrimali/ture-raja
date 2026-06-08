@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12" x-data="{ showViewModal: false, activeApp: { role: '', resume_path: '', first_name: '', middle_name: '', last_name: '', email: '', phone: '', location: '', location_other: '', notice_period: '', gender: '', education: '', total_exp: '', relevant_exp: '', current_ctc: '', expected_ctc: '' } }">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <h2 class="font-black text-foreground tracking-tight">Career Applications</h2>
            <p class="text-muted-text font-medium">Manage and review incoming job applications.</p>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex items-center justify-between">
            <h3 class="text-xl font-black">Recent Applications</h3>
        </div>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">APPLICANT</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">ROLE</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">EXPERIENCE</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">RESUME</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($applications as $index => $app)
                        @php
                            $srNo = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                        @endphp
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{{ $srNo }}</td>
                            <td class="py-6 px-10">
                                <div class="space-y-1">
                                    <p class="text-sm font-black text-foreground">{{ $app->first_name }} {{ $app->last_name }}</p>
                                    <div class="flex items-center gap-3 text-[10px] text-muted-text font-medium">
                                        <span class="flex items-center gap-1"><i data-lucide="mail" size="10"></i> {{ $app->email }}</span>
                                        <span class="flex items-center gap-1"><i data-lucide="phone" size="10"></i> {{ $app->phone }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-6 px-10 text-sm font-bold text-foreground">{{ $app->role }}</td>
                            <td class="py-6 px-10 text-sm font-medium text-muted-text">{{ $app->total_exp }}</td>
                            <td class="py-6 px-10">
                                @if($app->resume_path)
                                    <a href="{{ url('resume/view/' . $app->resume_path) }}" target="_blank" class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 px-3 py-1.5 rounded-xl text-xs font-bold hover:bg-blue-100 transition-colors">
                                        <i data-lucide="file-text" size="14"></i> View Resume
                                    </a>
                                @else
                                    <span class="text-xs font-medium text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="py-6 px-10 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        @click="showViewModal = true; activeApp = { role: '{{ addslashes($app->role) }}', first_name: '{{ addslashes($app->first_name) }}', middle_name: '{{ addslashes($app->middle_name) }}', last_name: '{{ addslashes($app->last_name) }}', email: '{{ addslashes($app->email) }}', phone: '{{ addslashes($app->phone) }}', location: '{{ addslashes($app->location) }}', location_other: '{{ addslashes($app->location_other) }}', notice_period: '{{ addslashes($app->notice_period) }}', gender: '{{ addslashes($app->gender) }}', education: '{{ addslashes($app->education) }}', total_exp: '{{ addslashes($app->total_exp) }}', relevant_exp: '{{ addslashes($app->relevant_exp) }}', current_ctc: '{{ addslashes($app->current_ctc) }}', expected_ctc: '{{ addslashes($app->expected_ctc) }}', resume_path: '{{ $app->resume_path ? url('resume/view/' . $app->resume_path) : '' }}' }"
                                        class="p-2 text-muted-text hover:text-primary transition-all"
                                    >
                                        <i data-lucide="eye" size="18"></i>
                                    </button>
                                    <a 
                                        href="{{ url('/admin/careers/delete/' . $app->id) }}" 
                                        onclick="return confirm('Are you sure you want to delete this application?');"
                                        class="p-2 text-muted-text hover:text-red-500 transition-all"
                                    >
                                        <i data-lucide="trash-2" size="18"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-sm font-bold text-muted-text">No career applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ================= MODALS ================= -->

    <!-- View Application Modal -->
    <div 
        x-show="showViewModal" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        style="display: none;"
    >
        <div @click.away="showViewModal = false" class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-2xl w-full overflow-hidden p-10 space-y-8 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-border-soft pb-4">
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-foreground" x-text="'Application for ' + activeApp.role"></h3>
                    <p class="text-xs text-muted-text font-medium" x-text="'Applicant: ' + activeApp.first_name + ' ' + (activeApp.middle_name ? activeApp.middle_name + ' ' : '') + activeApp.last_name"></p>
                </div>
                <button @click="showViewModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4 text-xs font-bold text-muted-text">
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Email</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeApp.email"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Phone</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeApp.phone || 'N/A'"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Location</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeApp.location + (activeApp.location_other ? ' (' + activeApp.location_other + ')' : '')"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Gender</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeApp.gender"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Education</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeApp.education"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Total Experience</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeApp.total_exp"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Relevant Experience</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeApp.relevant_exp || 'N/A'"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Notice Period</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeApp.notice_period"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Current CTC</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeApp.current_ctc || 'N/A'"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Expected CTC</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeApp.expected_ctc"></p>
                    </div>
                    <div class="space-y-1 col-span-2">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Resume</p>
                        <div class="bg-gray-50 p-3 rounded-xl">
                            <a x-show="activeApp.resume_path" :href="activeApp.resume_path" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 w-max">
                                <i data-lucide="file-text" size="16"></i> View Resume
                            </a>
                            <span x-show="!activeApp.resume_path">N/A</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-end pt-4">
                <button type="button" @click="showViewModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
