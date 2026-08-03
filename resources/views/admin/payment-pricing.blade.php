@extends('layouts.admin')

@section('title', 'Payment Pricing Settings')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Page header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-3xl md:text-4xl text-slate-800 font-extrabold tracking-tight">Payment Pricing ✨</h1>
            <p class="text-sm text-slate-500 mt-2">Manage the pricing and configurations for dynamic addons.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-4 py-4 rounded-r-lg shadow-sm mb-6 flex items-center">
            <i data-lucide="check-circle" class="w-5 h-5 mr-3 text-emerald-500"></i>
            <span class="block sm:inline font-medium">{{ session('success') }}</span>
        </div>
    @endif
    
    @if ($errors->any())
        <div class="bg-rose-50 border-l-4 border-rose-500 text-rose-700 px-4 py-4 rounded-r-lg shadow-sm mb-6">
            <div class="flex items-center mb-2">
                <i data-lucide="alert-circle" class="w-5 h-5 mr-3 text-rose-500"></i>
                <span class="font-bold">Please correct the following errors:</span>
            </div>
            <ul class="list-disc pl-10 space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        <!-- Boost Active Tours -->
        <div class="bg-white shadow-xl shadow-slate-200/50 rounded-3xl overflow-hidden border border-slate-100 transition-all duration-300 hover:shadow-2xl hover:shadow-[#ea580c]/10">
            <header class="px-6 py-5 bg-gradient-to-r from-orange-50 to-white border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-lg font-black text-slate-800 tracking-wide flex items-center">
                    <i data-lucide="rocket" class="w-5 h-5 mr-2 text-[#ea580c]"></i>
                    Boost Tours
                </h2>
                <button type="button" onclick="toggleForm('boostForm')" class="text-xs font-bold text-white bg-[#ea580c] hover:bg-orange-600 px-3 py-1.5 rounded-lg shadow-md transition-colors flex items-center">
                    <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Add
                </button>
            </header>
            
            <div id="boostForm" class="p-6 border-b border-slate-100 hidden">
                <form action="{{ route('admin.payment-pricing.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="type" value="boost">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Name <span class="text-rose-500">*</span></label>
                        <input name="name" class="w-full bg-slate-50 border-0 border-b-2 border-slate-200 focus:ring-0 focus:border-[#ea580c] rounded-t-lg px-4 py-3 text-sm text-slate-700 transition-colors placeholder-slate-400" type="text" placeholder="e.g. Boost Active Tours" required />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description</label>
                        <input name="description" class="w-full bg-slate-50 border-0 border-b-2 border-slate-200 focus:ring-0 focus:border-[#ea580c] rounded-t-lg px-4 py-3 text-sm text-slate-700 transition-colors placeholder-slate-400" type="text" placeholder="e.g. Boost your tours for featured placements" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Price (₹) <span class="text-rose-500">*</span></label>
                            <input name="price" class="w-full bg-slate-50 border-0 border-b-2 border-slate-200 focus:ring-0 focus:border-[#ea580c] rounded-t-lg px-4 py-3 text-sm text-slate-700 transition-colors placeholder-slate-400" type="number" step="0.01" required />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Duration (Days)</label>
                            <input name="duration_days" class="w-full bg-slate-50 border-0 border-b-2 border-slate-200 focus:ring-0 focus:border-[#ea580c] rounded-t-lg px-4 py-3 text-sm text-slate-700 transition-colors placeholder-slate-400" type="number" step="1" min="1" placeholder="Optional" />
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-[#ea580c] hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-orange-500/30 transition-all duration-300 transform hover:-translate-y-0.5">
                        Save Boost
                    </button>
                </form>
            </div>
            
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <tbody class="divide-y divide-slate-100">
                            @foreach($boosts as $boost)
                            <tr class="hover:bg-slate-50 transition-colors rounded-xl group">
                                <td class="p-3">
                                    <div class="font-bold text-slate-800 text-sm">{{ $boost->name }}</div>
                                    <div class="text-xs text-slate-500 mt-1">{{ $boost->description }}</div>
                                </td>
                                <td class="p-3 text-right">
                                    <div class="font-black text-emerald-600 text-sm">₹{{ number_format($boost->price, 2) }}</div>
                                    @if($boost->duration_days)
                                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1">{{ $boost->duration_days }} Days</div>
                                    @endif
                                </td>
                                <td class="p-3 w-px whitespace-nowrap">
                                    <div class="flex justify-end items-center gap-1">
                                        <button class="text-slate-400 hover:text-[#ea580c] transition-colors p-2 rounded-full hover:bg-orange-50 flex items-center justify-center" onclick="openEditModal({{ $boost->id }}, '{{ addslashes($boost->name) }}', '{{ addslashes($boost->description) }}', {{ $boost->price }}, {{ $boost->duration_days ?? 'null' }})">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                        <a href="{{ route('admin.payment-pricing.delete', $boost->id) }}" onclick="return confirm('Are you sure you want to delete this pricing?')" class="text-slate-400 hover:text-rose-500 transition-colors p-2 rounded-full hover:bg-rose-50 flex items-center justify-center">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @if($boosts->isEmpty())
                            <tr><td colspan="3" class="p-8 text-center text-sm font-medium text-slate-400">No boost pricings configured.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Ad Placements -->
        <div class="bg-white shadow-xl shadow-slate-200/50 rounded-3xl overflow-hidden border border-slate-100 transition-all duration-300 hover:shadow-2xl hover:shadow-[#ea580c]/10">
            <header class="px-6 py-5 bg-gradient-to-r from-orange-50 to-white border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-lg font-black text-slate-800 tracking-wide flex items-center">
                    <i data-lucide="layout-template" class="w-5 h-5 mr-2 text-[#ea580c]"></i>
                    Ad Placements
                </h2>
                <button type="button" onclick="toggleForm('adForm')" class="text-xs font-bold text-white bg-[#ea580c] hover:bg-orange-600 px-3 py-1.5 rounded-lg shadow-md transition-colors flex items-center">
                    <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Add
                </button>
            </header>
            
            <div id="adForm" class="p-6 border-b border-slate-100 hidden">
                <form action="{{ route('admin.payment-pricing.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="type" value="ad">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Name <span class="text-rose-500">*</span></label>
                        <input name="name" class="w-full bg-slate-50 border-0 border-b-2 border-slate-200 focus:ring-0 focus:border-[#ea580c] rounded-t-lg px-4 py-3 text-sm text-slate-700 transition-colors placeholder-slate-400" type="text" placeholder="e.g. Home Hero Banner" required />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description</label>
                        <input name="description" class="w-full bg-slate-50 border-0 border-b-2 border-slate-200 focus:ring-0 focus:border-[#ea580c] rounded-t-lg px-4 py-3 text-sm text-slate-700 transition-colors placeholder-slate-400" type="text" placeholder="e.g. Main spotlight visibility" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Price (₹) <span class="text-rose-500">*</span></label>
                            <input name="price" class="w-full bg-slate-50 border-0 border-b-2 border-slate-200 focus:ring-0 focus:border-[#ea580c] rounded-t-lg px-4 py-3 text-sm text-slate-700 transition-colors placeholder-slate-400" type="number" step="0.01" required />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Duration (Days)</label>
                            <input name="duration_days" class="w-full bg-slate-50 border-0 border-b-2 border-slate-200 focus:ring-0 focus:border-[#ea580c] rounded-t-lg px-4 py-3 text-sm text-slate-700 transition-colors placeholder-slate-400" type="number" step="1" min="1" placeholder="Optional" />
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-[#ea580c] hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-orange-500/30 transition-all duration-300 transform hover:-translate-y-0.5">
                        Save Ad Placement
                    </button>
                </form>
            </div>
            
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <tbody class="divide-y divide-slate-100">
                            @foreach($ads as $ad)
                            <tr class="hover:bg-slate-50 transition-colors rounded-xl group">
                                <td class="p-3">
                                    <div class="font-bold text-slate-800 text-sm">{{ $ad->name }}</div>
                                    <div class="text-xs text-slate-500 mt-1">{{ $ad->description }}</div>
                                </td>
                                <td class="p-3 text-right">
                                    <div class="font-black text-emerald-600 text-sm">₹{{ number_format($ad->price, 2) }}</div>
                                    @if($ad->duration_days)
                                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1">{{ $ad->duration_days }} Days</div>
                                    @endif
                                </td>
                                <td class="p-3 w-px whitespace-nowrap">
                                    <div class="flex justify-end items-center gap-1">
                                        <button class="text-slate-400 hover:text-[#ea580c] transition-colors p-2 rounded-full hover:bg-orange-50 flex items-center justify-center" onclick="openEditModal({{ $ad->id }}, '{{ addslashes($ad->name) }}', '{{ addslashes($ad->description) }}', {{ $ad->price }}, {{ $ad->duration_days ?? 'null' }})">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                        <a href="{{ route('admin.payment-pricing.delete', $ad->id) }}" onclick="return confirm('Are you sure you want to delete this pricing?')" class="text-slate-400 hover:text-rose-500 transition-colors p-2 rounded-full hover:bg-rose-50 flex items-center justify-center">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @if($ads->isEmpty())
                            <tr><td colspan="3" class="p-8 text-center text-sm font-medium text-slate-400">No ad pricings configured.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Trusted Agent -->
        <div class="bg-white shadow-xl shadow-slate-200/50 rounded-3xl overflow-hidden border border-slate-100 transition-all duration-300 hover:shadow-2xl hover:shadow-[#ea580c]/10">
            <header class="px-6 py-5 bg-gradient-to-r from-orange-50 to-white border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-lg font-black text-slate-800 tracking-wide flex items-center">
                    <i data-lucide="shield-check" class="w-5 h-5 mr-2 text-[#ea580c]"></i>
                    Trusted Agent
                </h2>
                <button type="button" onclick="toggleForm('trustedAgentForm')" class="text-xs font-bold text-white bg-[#ea580c] hover:bg-orange-600 px-3 py-1.5 rounded-lg shadow-md transition-colors flex items-center">
                    <i data-lucide="plus" class="w-4 h-4 mr-1"></i> Add
                </button>
            </header>
            
            <div id="trustedAgentForm" class="p-6 border-b border-slate-100 hidden">
                <form action="{{ route('admin.payment-pricing.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="type" value="trusted_agent">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Name <span class="text-rose-500">*</span></label>
                        <input name="name" class="w-full bg-slate-50 border-0 border-b-2 border-slate-200 focus:ring-0 focus:border-[#ea580c] rounded-t-lg px-4 py-3 text-sm text-slate-700 transition-colors placeholder-slate-400" type="text" placeholder="e.g. Trusted Agent Verification" required />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description</label>
                        <input name="description" class="w-full bg-slate-50 border-0 border-b-2 border-slate-200 focus:ring-0 focus:border-[#ea580c] rounded-t-lg px-4 py-3 text-sm text-slate-700 transition-colors placeholder-slate-400" type="text" placeholder="e.g. Stand out with a Blue Tick" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Price (₹) <span class="text-rose-500">*</span></label>
                            <input name="price" class="w-full bg-slate-50 border-0 border-b-2 border-slate-200 focus:ring-0 focus:border-[#ea580c] rounded-t-lg px-4 py-3 text-sm text-slate-700 transition-colors placeholder-slate-400" type="number" step="0.01" required />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Duration (Days)</label>
                            <input name="duration_days" class="w-full bg-slate-50 border-0 border-b-2 border-slate-200 focus:ring-0 focus:border-[#ea580c] rounded-t-lg px-4 py-3 text-sm text-slate-700 transition-colors placeholder-slate-400" type="number" step="1" min="1" placeholder="Optional" />
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-[#ea580c] hover:bg-orange-600 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-orange-500/30 transition-all duration-300 transform hover:-translate-y-0.5">
                        Save Trusted Agent Pricing
                    </button>
                </form>
            </div>
            
            <div class="p-4">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <tbody class="divide-y divide-slate-100">
                            @foreach($trustedAgents as $agentOpt)
                            <tr class="hover:bg-slate-50 transition-colors rounded-xl group">
                                <td class="p-3">
                                    <div class="font-bold text-slate-800 text-sm">{{ $agentOpt->name }}</div>
                                    <div class="text-xs text-slate-500 mt-1">{{ $agentOpt->description }}</div>
                                </td>
                                <td class="p-3 text-right">
                                    <div class="font-black text-emerald-600 text-sm">₹{{ number_format($agentOpt->price, 2) }}</div>
                                    @if($agentOpt->duration_days)
                                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-1">{{ $agentOpt->duration_days }} Days</div>
                                    @endif
                                </td>
                                <td class="p-3 w-px whitespace-nowrap">
                                    <div class="flex justify-end items-center gap-1">
                                        <button class="text-slate-400 hover:text-[#ea580c] transition-colors p-2 rounded-full hover:bg-orange-50 flex items-center justify-center" onclick="openEditModal({{ $agentOpt->id }}, '{{ addslashes($agentOpt->name) }}', '{{ addslashes($agentOpt->description) }}', {{ $agentOpt->price }}, {{ $agentOpt->duration_days ?? 'null' }})">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </button>
                                        <a href="{{ route('admin.payment-pricing.delete', $agentOpt->id) }}" onclick="return confirm('Are you sure you want to delete this pricing?')" class="text-slate-400 hover:text-rose-500 transition-colors p-2 rounded-full hover:bg-rose-50 flex items-center justify-center">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @if($trustedAgents->isEmpty())
                            <tr><td colspan="3" class="p-8 text-center text-sm font-medium text-slate-400">No trusted agent pricings configured.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Edit Modal Overlay -->
<div id="editModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md p-8 transform transition-all border border-slate-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-black text-slate-800 text-xl tracking-tight" id="modal-title">Edit Pricing ✨</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-rose-500 bg-slate-50 hover:bg-rose-50 p-2 rounded-full transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Name <span class="text-rose-500">*</span></label>
                    <input id="edit_name" name="name" class="w-full bg-slate-50 border-0 border-b-2 border-slate-200 focus:ring-0 focus:border-[#ea580c] rounded-t-lg px-4 py-3 text-sm text-slate-700 transition-colors" type="text" required />
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Description</label>
                    <input id="edit_description" name="description" class="w-full bg-slate-50 border-0 border-b-2 border-slate-200 focus:ring-0 focus:border-[#ea580c] rounded-t-lg px-4 py-3 text-sm text-slate-700 transition-colors" type="text" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Price (₹) <span class="text-rose-500">*</span></label>
                        <input id="edit_price" name="price" class="w-full bg-slate-50 border-0 border-b-2 border-slate-200 focus:ring-0 focus:border-[#ea580c] rounded-t-lg px-4 py-3 text-sm text-slate-700 transition-colors" type="number" step="0.01" required />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Duration (Days)</label>
                        <input id="edit_duration_days" name="duration_days" class="w-full bg-slate-50 border-0 border-b-2 border-slate-200 focus:ring-0 focus:border-[#ea580c] rounded-t-lg px-4 py-3 text-sm text-slate-700 transition-colors" type="number" step="1" min="1" />
                    </div>
                </div>
                <div class="pt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-6 py-3 rounded-xl font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-3 rounded-xl font-bold text-white bg-[#ea580c] hover:bg-orange-600 shadow-lg shadow-orange-500/30 transform hover:-translate-y-0.5 transition-all">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleForm(formId) {
        const form = document.getElementById(formId);
        if (form.classList.contains('hidden')) {
            form.classList.remove('hidden');
        } else {
            form.classList.add('hidden');
        }
    }

    function openEditModal(id, name, description, price, duration_days) {
        document.getElementById('editForm').action = "{{ url('/admin/payment-pricing/update') }}/" + id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_price').value = price;
        document.getElementById('edit_duration_days').value = duration_days ? duration_days : '';
        document.getElementById('editModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    
    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeEditModal();
        }
    });
</script>
@endsection
