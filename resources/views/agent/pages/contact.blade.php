@extends('agent.layouts.app')

@section('title', 'Contact Inquiries - Tour Raja Agent')

@section('content')

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-medium flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-2xl text-sm font-medium flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-red-500"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Header + stats --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-black text-gray-900">Contact Inquiries</h2>
            <p class="text-xs text-gray-400 font-medium mt-1">Messages sent to you from the public contact form</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-white border border-gray-100 rounded-2xl px-5 py-3 shadow-sm text-center">
                <p class="text-xl font-black text-gray-900">{{ $contacts->count() }}</p>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Total</p>
            </div>
            <div class="bg-yellow-50 border border-yellow-100 rounded-2xl px-5 py-3 shadow-sm text-center">
                <p class="text-xl font-black text-yellow-600">{{ $contacts->where('status', 'Pending')->count() }}</p>
                <p class="text-[10px] text-yellow-500 font-bold uppercase tracking-widest">Pending</p>
            </div>
            <div class="bg-green-50 border border-green-100 rounded-2xl px-5 py-3 shadow-sm text-center">
                <p class="text-xl font-black text-green-600">{{ $contacts->where('status', 'Resolved')->count() }}</p>
                <p class="text-[10px] text-green-500 font-bold uppercase tracking-widest">Resolved</p>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-[32px] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-black text-gray-800 uppercase tracking-widest">All Inquiries</h3>
            <span class="text-xs text-gray-400 font-medium">{{ $contacts->count() }} {{ Str::plural('entry', $contacts->count()) }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[9px] font-bold text-gray-300 uppercase tracking-widest border-b border-gray-50 whitespace-nowrap bg-gray-50/50">
                        <th class="py-5 px-6">#</th>
                        <th class="py-5 px-6">Sender</th>
                        <th class="py-5 px-6">Phone</th>
                        <th class="py-5 px-6">Subject</th>
                        <th class="py-5 px-6">Message</th>
                        <th class="py-5 px-6">Status</th>
                        <th class="py-5 px-6">Received</th>
                        <th class="py-5 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($contacts as $index => $c)
                        @php
                            $status = $c->status ?? 'Pending';
                            $statusColor = match(strtolower($status)) {
                                'resolved'  => 'bg-green-100 text-green-700',
                                'pending'   => 'bg-yellow-100 text-yellow-700',
                                'closed'    => 'bg-gray-100 text-gray-500',
                                default     => 'bg-blue-100 text-blue-700',
                            };
                            $contactData = json_encode([
                                'id'       => $c->id,
                                'name'     => $c->name,
                                'email'    => $c->email,
                                'phone'    => $c->phone ?? null,
                                'subject'  => $c->subject ?? null,
                                'message'  => $c->message ?? null,
                                'status'   => $status,
                                'received' => \Carbon\Carbon::parse($c->created_at)->format('d M Y, h:i A'),
                            ]);
                        @endphp
                        <tr class="group hover:bg-gray-50/50 transition-colors whitespace-nowrap" id="contact-row-{{ $c->id }}">
                            <td class="py-4 px-6 text-xs font-bold text-gray-400">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <img src="https://i.pravatar.cc/100?u=c{{ $c->id }}" class="w-9 h-9 rounded-xl object-cover border border-gray-100" alt="">
                                    <div>
                                        <p class="text-xs font-black text-gray-900">{{ $c->name }}</p>
                                        <p class="text-[10px] text-gray-400 font-medium">{{ $c->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-xs font-bold text-gray-700">
                                @if($c->phone)
                                    <a href="tel:{{ $c->phone }}" class="hover:text-primary transition-colors flex items-center gap-1">
                                        <i class="fas fa-phone text-gray-300 text-[9px]"></i> {{ $c->phone }}
                                    </a>
                                @else
                                    <span class="text-gray-300">&mdash;</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-xs font-bold text-primary max-w-[140px] truncate">{{ $c->subject ?? 'General Inquiry' }}</td>
                            <td class="py-4 px-6 max-w-[200px]">
                                <p class="text-[10px] text-gray-500 font-medium leading-relaxed line-clamp-2 whitespace-normal">{{ $c->message ?? '—' }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="{{ $statusColor }} px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider">{{ $status }}</span>
                            </td>
                            <td class="py-4 px-6 text-[10px] text-gray-400 font-medium">
                                {{ \Carbon\Carbon::parse($c->created_at)->diffForHumans() }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <button
                                        onclick="openViewModal({{ $contactData }})"
                                        class="text-[10px] font-bold text-gray-400 hover:text-primary transition-colors flex items-center gap-1"
                                        title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button
                                        onclick="markResolved({{ $c->id }}, this)"
                                        class="text-[10px] font-bold text-gray-400 hover:text-green-500 transition-colors flex items-center gap-1 {{ strtolower($status) === 'resolved' ? 'text-green-500' : '' }}"
                                        title="{{ strtolower($status) === 'resolved' ? 'Already Resolved' : 'Mark Resolved' }}">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-gray-300">
                                    <i class="fas fa-inbox text-5xl"></i>
                                    <p class="text-sm font-bold text-gray-400">No contact inquiries yet</p>
                                    <p class="text-xs text-gray-300">Messages sent through the public contact form will appear here</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="mt-12 flex flex-col lg:flex-row items-center justify-between py-6 border-t border-gray-100">
        <p class="text-xs text-gray-400 font-medium mb-4 lg:mb-0">Copyright &copy; {{ date('Y') }} Tour Raja Private Limited, India. All rights reserved.</p>
        <div class="flex space-x-6 text-xs text-gray-400 font-medium">
            <a href="#" class="hover:text-primary">About Us</a>
            <a href="#" class="hover:text-primary">Terms of Services</a>
            <a href="#" class="hover:text-primary">Privacy Policy</a>
        </div>
    </footer>

{{-- View Modal --}}
<div id="viewContactModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-gray-900/20 backdrop-blur-sm" onclick="closeViewModal()"></div>
    <div class="bg-white w-full max-w-lg rounded-[32px] p-8 shadow-2xl relative z-10 scale-95 opacity-0 transition-all duration-300" id="viewContactModalContainer">
        <button onclick="closeViewModal()" class="absolute top-6 right-8 text-gray-400 hover:text-gray-800 transition-colors">
            <i class="fas fa-times"></i>
        </button>
        <h3 class="text-xl font-black text-gray-900 mb-1">Contact Details</h3>
        <p class="text-[10px] text-gray-400 font-medium mb-6">Full details of the submitted inquiry</p>

        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Name</p>
                    <p id="vcName" class="text-sm font-black text-gray-900"></p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status</p>
                    <p id="vcStatus" class="text-sm font-black text-gray-900"></p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Email</p>
                    <p id="vcEmail" class="text-xs font-bold text-gray-700 break-all"></p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-4">
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Phone</p>
                    <p id="vcPhone" class="text-xs font-bold text-gray-700"></p>
                </div>
            </div>
            <div class="bg-gray-50 rounded-2xl p-4">
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Subject</p>
                <p id="vcSubject" class="text-sm font-black text-primary"></p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-4">
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Message</p>
                <p id="vcMessage" class="text-xs text-gray-600 font-medium leading-relaxed whitespace-pre-line"></p>
            </div>
            <div class="text-right">
                <p id="vcReceived" class="text-[10px] text-gray-400 font-medium"></p>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button onclick="closeViewModal()" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-gray-600 uppercase tracking-widest transition-all">Close</button>
        </div>
    </div>
</div>

<script>
function openViewModal(data) {
    document.getElementById('vcName').innerText    = data.name || '—';
    document.getElementById('vcEmail').innerText   = data.email || '—';
    document.getElementById('vcPhone').innerText   = data.phone || 'Not provided';
    document.getElementById('vcSubject').innerText = data.subject || 'General Inquiry';
    document.getElementById('vcMessage').innerText = data.message || '—';
    document.getElementById('vcStatus').innerText  = data.status || '—';
    document.getElementById('vcReceived').innerText = 'Received: ' + (data.received || '');

    const modal = document.getElementById('viewContactModal');
    const container = document.getElementById('viewContactModalContainer');
    modal.classList.remove('hidden');
    setTimeout(() => {
        container.classList.remove('scale-95', 'opacity-0');
        container.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeViewModal() {
    const modal = document.getElementById('viewContactModal');
    const container = document.getElementById('viewContactModalContainer');
    container.classList.remove('scale-100', 'opacity-100');
    container.classList.add('scale-95', 'opacity-0');
    setTimeout(() => modal.classList.add('hidden'), 300);
}

function markResolved(id, btn) {
    fetch('/agent/leads/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ id: id, status: 'Resolved' })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const row = document.getElementById('contact-row-' + id);
            const badge = row.querySelector('span.rounded-full');
            if (badge) {
                badge.innerText = 'Resolved';
                badge.className = 'bg-green-100 text-green-700 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider';
            }
            btn.classList.add('text-green-500');
            if (typeof toastr !== 'undefined') toastr.success('Marked as resolved!');
        }
    })
    .catch(err => console.error(err));
}
</script>

@endsection
