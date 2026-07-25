@extends('layouts.admin')

@section('admin_title', 'Package Expiry Reminders')

@section('content')
<div class="space-y-8 pb-12">
    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="space-y-1">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Package Expiry Reminders</h2>
            <p class="text-xs text-gray-400 font-semibold leading-relaxed max-w-xl">
                Send expiry reminder emails to agents whose packages are expiring within 30 days or have already expired.
            </p>
        </div>
        <a href="{{ url('admin/settings') }}" class="border border-gray-200 hover:bg-gray-50 px-5 py-3 rounded-2xl font-black text-xs transition-all flex items-center gap-2 uppercase tracking-wider text-gray-700">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Settings
        </a>
    </div>

    @if (session('success'))
        <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-2xl shadow-sm text-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    {{-- Main Two-Column Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Left Side: Expiring Packages List --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[32px] p-8 shadow-premium border border-border-soft space-y-6">
                <div class="flex items-center justify-between border-b border-border-soft pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-[#FFF5F2] rounded-xl flex items-center justify-center text-[#b13c0b]">
                            <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-base font-black text-gray-900">Expiring Soon / Expired Packages</h3>
                    </div>
                    <span class="text-xs font-bold bg-red-50 text-red-500 rounded-full px-3 py-1">{{ $packages->count() }} Packages</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="py-4 px-4 text-[10px] font-black text-muted-text uppercase tracking-widest">Package Name</th>
                                <th class="py-4 px-4 text-[10px] font-black text-muted-text uppercase tracking-widest">Registered Agent</th>
                                <th class="py-4 px-4 text-[10px] font-black text-muted-text uppercase tracking-widest">Expiry Date</th>
                                <th class="py-4 px-4 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-soft">
                            @forelse($packages as $pkg)
                                <tr class="hover:bg-gray-50/30 transition-colors">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ asset($pkg->image) }}" class="w-10 h-8 rounded-lg object-cover" />
                                            <div>
                                                <span class="text-xs font-bold text-gray-800 block">{{ $pkg->title }}</span>
                                                <span class="text-[9px] text-gray-400 font-semibold">{{ $pkg->location }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div>
                                            <span class="text-xs font-bold text-gray-800 block">{{ $pkg->agent_name }}</span>
                                            <span class="text-[9px] text-[#b13c0b] font-semibold">{{ $pkg->agent_email }}</span>
                                        </div>
                                    </td>
                                        @php
                                            $daysLeft = (int)ceil(now()->diffInDays(\Carbon\Carbon::parse($pkg->expiry_date), false));
                                            $isExpired = $daysLeft < 0;
                                            $absDays = abs($daysLeft);
                                        @endphp
                                        <div>
                                            <span class="text-xs font-bold text-gray-800 block">{{ \Carbon\Carbon::parse($pkg->expiry_date)->format('M d, Y') }}</span>
                                            @if($isExpired)
                                                <span class="text-[9px] text-red-500 font-bold uppercase">Expired {{ $absDays }} {{ $absDays == 1 ? 'day' : 'days' }} ago</span>
                                            @else
                                                <span class="text-[9px] text-orange-500 font-bold uppercase">Expires in {{ $absDays }} {{ $absDays == 1 ? 'day' : 'days' }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <form action="{{ route('settings.send-reminder') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="type" value="individual">
                                            <input type="hidden" name="package_id" value="{{ $pkg->id }}">
                                            <input type="hidden" name="subject" class="sync-subject">
                                            <input type="hidden" name="body" class="sync-body">
                                            <button type="submit" onclick="syncTemplateData(this)" class="px-3.5 py-2 bg-primary hover:bg-orange-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                                Send Reminder
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center text-xs font-bold text-gray-400">
                                        No packages are expiring soon or currently expired.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right Side: Reminder Template Editor --}}
        <div>
            <div class="bg-white rounded-[32px] p-8 shadow-premium border border-border-soft space-y-6 sticky top-8">
                <div class="flex items-center gap-3 border-b border-border-soft pb-4">
                    <div class="w-10 h-10 bg-[#FFF5F2] rounded-xl flex items-center justify-center text-[#b13c0b]">
                        <i data-lucide="edit-3" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-base font-black text-gray-900">Email Template</h3>
                </div>

                <div class="space-y-4">
                    {{-- Subject --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Subject</label>
                        <input type="text" id="editor-subject" value="Action Required: Your Tour Package &quot;{PACKAGE_TITLE}&quot; is Expiring Soon" class="w-full bg-[#FFF5F2] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-bold text-foreground text-xs shadow-sm" />
                    </div>

                    {{-- Body --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Body Text</label>
                        <textarea id="editor-body" rows="8" class="w-full bg-[#FFF5F2] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-semibold text-foreground text-xs shadow-sm">Dear {AGENT_NAME},

We would like to remind you that your tour package "{PACKAGE_TITLE}" listed on Tour Raja is scheduled to expire on {EXPIRY_DATE}.

To keep this package active and visible to customers, please log in to your agent dashboard and update/renew the validity.

Best regards,
Tour Raja Team</textarea>
                    </div>

                    {{-- Dynamic Tags Info --}}
                    <div class="p-4 bg-orange-50/50 rounded-2xl border border-orange-100/50 space-y-2">
                        <span class="text-[9px] font-black text-[#b13c0b] uppercase tracking-wider block">AVAILABLE TEMPLATE TAGS</span>
                        <div class="grid grid-cols-2 gap-2 text-[9px] font-bold text-gray-500">
                            <div><code class="bg-orange-100 text-[#b13c0b] px-1.5 py-0.5 rounded">{AGENT_NAME}</code></div>
                            <div><code class="bg-orange-100 text-[#b13c0b] px-1.5 py-0.5 rounded">{PACKAGE_TITLE}</code></div>
                            <div><code class="bg-orange-100 text-[#b13c0b] px-1.5 py-0.5 rounded">{EXPIRY_DATE}</code></div>
                        </div>
                    </div>

                    {{-- Send All Trigger Form --}}
                    @if($packages->count() > 0)
                        <form action="{{ route('settings.send-reminder') }}" method="POST" class="pt-2">
                            @csrf
                            <input type="hidden" name="type" value="all">
                            <input type="hidden" name="subject" class="sync-subject">
                            <input type="hidden" name="body" class="sync-body">
                            <button type="submit" onclick="syncTemplateData(this)" style="background-color: #b13c0b;" class="w-full hover:opacity-90 text-white py-4 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl text-center transition-all">
                                <i class="fas fa-paper-plane mr-1.5"></i> Send to All Expiring
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// Function to sync editor subject/body to form targets before submission
function syncTemplateData(button) {
    const subject = document.getElementById('editor-subject').value;
    const body = document.getElementById('editor-body').value;
    
    const form = button.closest('form');
    if (form) {
        form.querySelector('.sync-subject').value = subject;
        form.querySelector('.sync-body').value = body;
    }
}
</script>
@endsection
