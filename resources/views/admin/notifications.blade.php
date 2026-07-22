@extends('layouts.admin')

@section('admin_title', 'Notifications')

@section('content')
<div class="space-y-10 pb-12" x-data="{ showAddModal: false, targetAudience: 'all_users' }">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <h2 class="font-black text-foreground tracking-tight">Notifications Management</h2>
            <p class="text-muted-text font-medium">Overview of communication performance and agent reach across the platform.</p>
        </div>
        <button @click="showAddModal = true" class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3 group">
            <i data-lucide="plus" size="20" class="group-hover:rotate-90 transition-transform"></i>
            New Notification
        </button>
    </div>

    <!-- Dispatches Table -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div class="p-8 border-b border-border-soft flex items-center justify-between">
            <h3 class="text-xl font-black">Recent Dispatches</h3>
        </div>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-border-soft">
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">NOTIFICATION TITLE</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">MESSAGE CONTENT</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">DATE & TIME SENT</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">TYPE</th>
                        <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($notifications as $dispatch)
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <td class="py-8 px-10">
                                <div class="space-y-1">
                                    <p class="text-sm font-black text-foreground group-hover:text-primary transition-colors">{{ $dispatch->title }}</p>
                                    <p class="text-[10px] font-bold text-muted-text uppercase tracking-tighter">ID: NOT-{{ str_pad($dispatch->id, 4, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </td>
                            <td class="py-8 px-10 text-xs font-medium text-muted-text max-w-xs truncate">{{ $dispatch->message }}</td>
                            <td class="py-8 px-10">
                                <p class="text-xs font-bold text-muted-text leading-tight">{{ \Carbon\Carbon::parse($dispatch->sent_at)->format('M d, Y') }}</p>
                                <p class="text-[10px] font-medium text-muted-text/60 mt-1">{{ \Carbon\Carbon::parse($dispatch->sent_at)->format('h:i A') }}</p>
                            </td>
                            <td class="py-8 px-10">
                                <span class="px-3 py-1 rounded-full 
                                    {{ $dispatch->type === 'Alert' ? 'bg-red-50 text-red-500' : 
                                       ($dispatch->type === 'Warning' ? 'bg-yellow-50 text-yellow-500' : 'bg-blue-50 text-blue-500') }} 
                                    text-[10px] font-black uppercase tracking-wider">
                                    {{ $dispatch->type }}
                                </span>
                            </td>
                            <td class="py-8 px-10 text-right">
                                <a 
                                    href="{{ url('/admin/notifications/delete/' . $dispatch->id) }}" 
                                    onclick="return confirm('Are you sure you want to clear this notification?');"
                                    class="p-2 text-muted-text hover:text-red-500 transition-all inline-block"
                                >
                                    <i data-lucide="trash-2" size="18"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-sm font-bold text-muted-text">No platform notification dispatches found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-sm font-bold text-muted-text">Showing {{ $notifications->firstItem() ?? 0 }} to {{ $notifications->lastItem() ?? 0 }} of {{ $notifications->total() }} entries</p>
            <div class="flex items-center gap-2">
                @if($notifications->onFirstPage())
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-left" size="20"></i></button>
                @else
                    <a href="{{ $notifications->previousPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-left" size="20"></i></a>
                @endif
                
                @foreach(range(1, $notifications->lastPage()) as $i)
                    @if($i == 1 || $i == $notifications->lastPage() || abs($i - $notifications->currentPage()) <= 1)
                        @if($i == $notifications->currentPage())
                            <button class="w-10 h-10 rounded-full text-sm font-black bg-primary text-white shadow-lg shadow-primary/20 transition-all">
                                {{ $i }}
                            </button>
                        @else
                            <a href="{{ $notifications->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black transition-all text-muted-text hover:bg-white hover:text-primary flex items-center justify-center">
                                {{ $i }}
                            </a>
                        @endif
                    @elseif($i == 2 || $i == $notifications->lastPage() - 1)
                        <span class="text-muted-text font-black px-1">...</span>
                    @endif
                @endforeach
                
                @if($notifications->hasMorePages())
                    <a href="{{ $notifications->nextPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-right" size="20"></i></a>
                @else
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-right" size="20"></i></button>
                @endif
            </div>
        </div>
    </div>

    <!-- ================= MODALS ================= -->

    <!-- Add Notification Modal -->
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
                    <h3 class="text-xl font-black text-foreground">Broadcast Notification</h3>
                    <p class="text-xs text-muted-text font-medium">Send a global announcement to all platform agents.</p>
                </div>
                <button @click="showAddModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/notifications/store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Notification Title<span class="text-primary">*</span></label>
                    <input required type="text" name="title" placeholder="E.g. Maintenance Alert: Dashboard API" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Alert Category Type</label>
                    <select name="type" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                        <option value="Info">Info Announcement</option>
                        <option value="Alert">Alert (High Priority)</option>
                        <option value="Warning">Warning Notice</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Target Audience</label>
                    <select name="target_audience" x-model="targetAudience" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                        <option value="all_users">All Users</option>
                        <option value="all_agents">All Agents</option>
                        <option value="specific_agent">Specific Agent</option>
                    </select>
                </div>
                <div class="space-y-2" x-show="targetAudience === 'specific_agent'" style="display: none;">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Select Agent</label>
                    <select name="agent_id" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" :required="targetAudience === 'specific_agent'">
                        <option value="">Select an agent...</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }} ({{ $agent->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Broadcast Message Body<span class="text-primary">*</span></label>
                    <textarea required name="message" rows="4" placeholder="Type announcement copy here..." class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm"></textarea>
                </div>
                
                <div class="flex items-center justify-end gap-4 pt-4">
                    <button type="button" @click="showAddModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Send Broadcast</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
