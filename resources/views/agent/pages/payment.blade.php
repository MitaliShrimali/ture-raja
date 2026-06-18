@extends('agent.layouts.app')

@section('title', 'Billing & Promotions - Tour Raja Agent')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header -->
    <div class="mb-10 flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight mb-2">Billing & Promotions</h1>
            <p class="text-gray-500 text-sm font-medium">Manage your advertising credits, boost your travel packages, and review your historical transactions.</p>
        </div>
        <button onclick="document.getElementById('bankDetailsModal').classList.remove('hidden')" class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-500 hover:text-[#ea580c] hover:border-orange-200 hover:bg-orange-50 transition-colors shadow-sm">
            <i class="fas fa-pencil-alt"></i>
        </button>
    </div>

    <!-- Top Cards Row -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
        
        <!-- Left Card (Credits) -->
        <div class="lg:col-span-4 bg-[#ea580c] rounded-[2rem] p-8 relative overflow-hidden shadow-xl shadow-orange-600/20 text-white">
            <div class="absolute -right-6 -bottom-6 opacity-20">
                <i class="fas fa-wallet text-[150px]"></i>
            </div>
            <div class="relative z-10 flex flex-col h-full justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-orange-200 mb-2">Already Spent</p>
                    <h2 class="text-4xl font-black mb-8">₹{{ number_format($payments->where('status', 'Success')->sum('amount'), 2) }}</h2>
                </div>
                <div>
                    <button class="bg-white text-[#ea580c] font-black text-sm px-6 py-3 rounded-full hover:bg-gray-50 transition-colors shadow-lg flex items-center w-max">
                        <i class="fas fa-plus-circle mr-2"></i> Top Up Credits
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Card (Current Plan) -->
        <div class="lg:col-span-8 bg-[#f8fafc] rounded-[2rem] p-8 relative border border-gray-100 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-start mb-6">
                <div class="bg-blue-500 text-white text-[10px] font-black uppercase tracking-widest py-1.5 px-3 rounded-full flex items-center">
                    <i class="fas fa-check-circle mr-1.5"></i> {{ $activePlan ? $activePlan->name : 'Basic' }} Partner Plan
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Package Limit</p>
                    <p class="text-sm font-black text-gray-800">{{ $activePlan ? ($activePlan->package_limit >= 9999 ? 'Unlimited' : $activePlan->package_limit) : 1 }} Packages</p>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-2xl font-black text-gray-900 mb-2">Maximize your Visibility</h3>
                <p class="text-gray-500 text-sm font-medium">Unlimited listings and priority support included with premium plans.</p>
            </div>

            <div class="flex flex-wrap items-center justify-between border-t border-gray-200/60 pt-6 mt-auto">
                <div class="flex items-center space-x-12">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Current Plan</p>
                        <p class="text-sm font-black text-gray-900">₹{{ $activePlan ? number_format($activePlan->price) : '0' }}/mo</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Status</p>
                        <p class="text-sm font-black text-gray-900 flex items-center">
                            <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span> Active
                        </p>
                    </div>
                </div>
                
                <button onclick="document.getElementById('upgradeModal').classList.remove('hidden')" class="text-[#ea580c] font-bold text-sm hover:text-orange-700 transition-colors cursor-pointer mt-4 sm:mt-0">
                    Manage Plan
                </button>
            </div>
        </div>

    </div>

    <!-- Middle Section: Promos -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
        
        <!-- Promotions List -->
        <div class="lg:col-span-8">
            <div class="flex items-center mb-6">
                <div class="h-px bg-gray-300 w-8 mr-4"></div>
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest">Package Promotion Options</h3>
            </div>
            
            <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm p-2">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <div>
                        <h4 class="text-lg font-bold text-gray-900">Boost Active Tours</h4>
                        <p class="text-xs font-medium text-gray-400">Boost your tours for featured placements.</p>
                    </div>
                    <select id="boostFilter" onchange="filterBoosts()" class="text-xs border border-gray-200 rounded-lg text-gray-600 outline-none focus:ring-[#ea580c] focus:border-[#ea580c] py-1.5 px-3">
                        <option value="all">All Packages</option>
                        <option value="active">Active Boosts</option>
                    </select>
                </div>
                
                <!-- Dynamic Promo Items -->
                @forelse($agentPackages as $pkg)
                <div class="p-4 flex flex-wrap sm:flex-nowrap items-center justify-between hover:bg-gray-50 rounded-2xl transition-colors boost-card" data-status="{{ $pkg->is_boosted ? 'active' : 'inactive' }}">
                    <div class="flex items-center mb-4 sm:mb-0 cursor-pointer" onclick="window.location.href='{{ url('/packages/edit/' . $pkg->id) }}'">
                        <div class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden mr-4 shrink-0">
                            @php
                                $firstImg = $pkg->image ?? 'https://images.unsplash.com/photo-1548013146-72479768bada?q=80&w=100&auto=format&fit=crop';
                            @endphp
                            <img src="{{ $firstImg }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h5 class="text-sm font-bold text-gray-900 hover:text-[#ea580c] transition-colors">{{ $pkg->title }}</h5>
                            <p class="text-xs text-gray-500 mt-1">{{ $pkg->duration }} • {{ ucfirst($pkg->category) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Price</p>
                            <p class="text-sm font-black text-[#ea580c]">₹12.50<span class="text-[10px] text-gray-400 font-medium">/day</span></p>
                        </div>
                        @if($pkg->is_boosted)
                            <button class="bg-orange-100 text-[#ea580c] font-bold text-xs px-6 py-2.5 rounded-full cursor-default inline-block border border-orange-200" disabled>Active</button>
                        @else
                            <a href="{{ route('agent.checkout', ['type' => 'boost', 'id' => $pkg->id]) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold text-xs px-6 py-2.5 rounded-full transition-colors inline-block">Boost</a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-xs text-gray-400 font-medium">No packages found.</div>
                @endforelse
            </div>
        </div>

        <!-- Right Promo Cards -->
        <div class="lg:col-span-4">
            <div class="flex items-center mb-6 lg:hidden">
                <div class="h-px bg-gray-300 w-8 mr-4"></div>
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest">Ad Placements</h3>
            </div>
            
            <!-- Trusted Agent -->
            <div class="bg-blue-50/50 rounded-[2rem] border border-blue-100 shadow-sm p-6 mb-6">
                <div class="flex justify-between items-start mb-4">
                    <h4 class="text-lg font-bold text-gray-900">Trusted Agent</h4>
                    <i class="fas fa-check-circle text-blue-500"></i>
                </div>
                <p class="text-xs text-gray-500 font-medium mb-4 leading-relaxed">Stand out with a Blue Tick and Service Guaranteed badge.</p>
                @if(isset($agent) && $agent->service_guaranteed)
                    <button class="w-full bg-blue-100 text-blue-600 font-bold text-sm py-4 rounded-xl cursor-default border border-blue-200 block text-center">Active - Trusted Agent</button>
                @else
                    <a href="{{ route('agent.checkout', ['type' => 'ad', 'id' => 'blue_tick', 'name' => 'Trusted Agent Verification', 'amount' => 1499]) }}" class="w-full bg-blue-500 text-white font-bold text-sm py-4 rounded-xl hover:bg-blue-600 transition-colors shadow-lg block text-center shadow-blue-200">Get Verified - ₹1499</a>
                @endif
            </div>

            <div class="bg-[#f8fafc] rounded-[2rem] border border-gray-100 shadow-sm p-6">
                <div class="flex justify-between items-start mb-4">
                    <h4 class="text-lg font-bold text-gray-900">AD Placement</h4>
                    <i class="fas fa-star text-[#ea580c]"></i>
                </div>
                <p class="text-xs text-gray-500 font-medium mb-6 leading-relaxed">Secure prime real estate and triple your package impressions.</p>
                
                <form action="{{ route('agent.checkout') }}" method="GET">
                    <input type="hidden" name="type" value="ad">
                    
                    <div class="mb-4">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 block">Select Package to Advertise</label>
                        <select name="package_id" class="w-full border-gray-200 rounded-xl text-xs focus:ring-[#ea580c] focus:border-[#ea580c] p-3 outline-none" required>
                            <option value="">Choose a package...</option>
                            @foreach($agentPackages as $pkg)
                                <option value="{{ $pkg->id }}">{{ $pkg->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-3 mb-6">
                        <!-- Option 1 -->
                        <label class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-2xl cursor-pointer hover:border-orange-300 transition-colors">
                            <div class="flex items-center">
                                <input type="radio" name="name" value="Home Hero Banner" class="w-4 h-4 text-[#ea580c] focus:ring-[#ea580c]" required>
                                <div class="ml-3">
                                    <p class="text-sm font-bold text-gray-900">Home Hero Banner</p>
                                    <p class="text-[10px] text-gray-400">Main spotlight visibility</p>
                                </div>
                            </div>
                            <span class="text-sm font-black text-[#ea580c]">₹999</span>
                        </label>

                        <!-- Option 2 -->
                        <label class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-2xl cursor-pointer hover:border-orange-300 transition-colors">
                            <div class="flex items-center">
                                <input type="radio" name="name" value="Package Sidebar" class="w-4 h-4 text-[#ea580c] focus:ring-[#ea580c]">
                                <div class="ml-3">
                                    <p class="text-sm font-bold text-gray-900">Package Sidebar</p>
                                    <p class="text-[10px] text-gray-400">Targeted placement</p>
                                </div>
                            </div>
                            <span class="text-sm font-black text-[#ea580c]">₹499</span>
                        </label>

                        <!-- Option 3 -->
                        <label class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-2xl cursor-pointer hover:border-orange-300 transition-colors">
                            <div class="flex items-center">
                                <input type="radio" name="name" value="Footer Banner" class="w-4 h-4 text-[#ea580c] focus:ring-[#ea580c]">
                                <div class="ml-3">
                                    <p class="text-sm font-bold text-gray-900">Footer Banner</p>
                                    <p class="text-[10px] text-gray-400">Persistent site-wide visibility</p>
                                </div>
                            </div>
                            <span class="text-sm font-black text-[#ea580c]">₹399</span>
                        </label>
                        
                        <!-- Option 4 -->
                        <label class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-2xl cursor-pointer hover:border-orange-300 transition-colors">
                            <div class="flex items-center">
                                <input type="radio" name="name" value="Under Domestic Packages" class="w-4 h-4 text-[#ea580c] focus:ring-[#ea580c]">
                                <div class="ml-3">
                                    <p class="text-sm font-bold text-gray-900">Under Domestic Packages</p>
                                    <p class="text-[10px] text-gray-400">High intent placement</p>
                                </div>
                            </div>
                            <span class="text-sm font-black text-[#ea580c]">₹599</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-[#1e293b] text-white font-bold text-sm py-4 rounded-xl hover:bg-black transition-colors shadow-lg">Purchase Placement</button>
                </form>
            </div>
        </div>

    </div>

    <!-- Billing History -->
    <div>
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div class="h-px bg-gray-300 w-8 mr-4"></div>
                <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest">Billing History</h3>
            </div>
            <a href="{{ route('agent.invoice') }}" class="text-[#ea580c] text-xs font-bold hover:text-orange-700 transition-colors flex items-center">
                View All Invoices <i class="fas fa-file-invoice ml-1.5"></i>
            </a>
        </div>

        <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            <th class="p-6 rounded-tl-[2rem]">Description</th>
                            <th class="p-6">Date</th>
                            <th class="p-6">Status</th>
                            <th class="p-6 text-right rounded-tr-[2rem]">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($payments as $payment)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                            <td class="p-6">
                                <p class="font-bold text-gray-900 mb-1">{{ $payment->plan_type ? $payment->plan_type . ' Subscription' : ucfirst(str_replace('_', ' ', $payment->type)) }}</p>
                                <p class="text-xs text-gray-400">{{ $payment->invoice_number }}</p>
                            </td>
                            <td class="p-6 font-medium text-gray-600">
                                {{ \Carbon\Carbon::parse($payment->date)->format('M d, Y') }}
                            </td>
                            <td class="p-6">
                                @if($payment->status == 'Completed' || $payment->status == 'Success')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-green-50 text-green-600 uppercase tracking-wider">
                                        <i class="fas fa-check-circle mr-1.5"></i> Paid
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-red-50 text-red-600 uppercase tracking-wider">
                                        <i class="fas fa-times-circle mr-1.5"></i> Failed
                                    </span>
                                @endif
                            </td>
                            <td class="p-6 text-right">
                                <div class="flex items-center justify-end space-x-4">
                                    <span class="font-black text-gray-900">₹{{ number_format($payment->amount, 2) }}</span>
                                    <a href="{{ route('agent.invoice.download', $payment->id) }}" target="_blank" class="text-gray-400 hover:text-[#ea580c] transition-colors"><i class="fas fa-download"></i></a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-12 text-center text-gray-400 font-medium">
                                No billing history found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Manage Plan Modal -->
<div id="upgradeModal" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm overflow-y-auto w-full h-full flex items-center justify-center p-4">
    <div class="relative w-full max-w-5xl bg-white rounded-[2rem] shadow-2xl p-8 max-h-[90vh] overflow-y-auto">
        
        <!-- Close Button -->
        <button onclick="document.getElementById('upgradeModal').classList.add('hidden')" class="absolute top-6 right-6 w-10 h-10 bg-gray-100 text-gray-500 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
            <i class="fas fa-times"></i>
        </button>

        <div class="text-center mb-10">
            <h2 class="text-3xl font-black text-gray-900 mb-4">Choose Your Plan</h2>
            <p class="text-gray-500 font-medium">Upgrade to unlock more features and unlimited packages.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($plans as $plan)
            <div class="bg-white rounded-[2rem] p-6 shadow-sm border {{ ($activePlan && $activePlan->id == $plan->id) ? 'border-[#ea580c] ring-2 ring-[#ea580c]/20' : 'border-gray-100' }} hover:shadow-xl transition-all duration-500 flex flex-col h-full relative group">
                @if($activePlan && $activePlan->id == $plan->id)
                <div class="absolute top-4 right-4 bg-[#ea580c] text-white text-[10px] font-black uppercase tracking-widest py-1 px-3 rounded-full">
                    Current
                </div>
                @endif
                
                <h4 class="text-lg font-bold text-gray-800 mb-1">{{ $plan->name }}</h4>
                <p class="text-xs text-gray-400 font-medium mb-4">{{ $plan->description }}</p>
                <div class="mb-6">
                    <span class="text-3xl font-black text-gray-800">₹{{ number_format($plan->price) }}</span>
                    <span class="text-xs text-gray-400 font-bold">/ {{ $plan->duration }}</span>
                </div>
                
                <div class="flex-grow mb-6 space-y-3">
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-0.5 mr-2"></i>
                        <span class="text-sm text-gray-600 font-medium">{{ $plan->features }}</span>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-0.5 mr-2"></i>
                        <span class="text-sm text-gray-600 font-medium">{{ $plan->package_limit >= 9999 ? 'Unlimited' : $plan->package_limit }} Package Limit</span>
                    </div>
                    @if($plan->name != 'Basic')
                    <div class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mt-0.5 mr-2"></i>
                        <span class="text-sm text-gray-600 font-medium">Priority Support</span>
                    </div>
                    @endif
                </div>
                
                @if($activePlan && $activePlan->id == $plan->id)
                    <button class="w-full py-3 bg-gray-100 text-gray-400 text-xs font-black rounded-xl uppercase tracking-widest cursor-not-allowed" disabled>Active</button>
                @else
                    <a href="{{ route('agent.checkout', ['type' => 'plan', 'id' => $plan->id]) }}" class="w-full py-3 bg-[#ea580c] text-white text-xs font-black rounded-xl shadow-lg shadow-orange-100 hover:bg-orange-600 transition-all active:scale-[0.98] uppercase tracking-widest flex justify-center items-center">Upgrade Now</a>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Bank Details Modal -->
<div id="bankDetailsModal" class="fixed inset-0 z-50 hidden bg-gray-900/60 backdrop-blur-sm overflow-y-auto w-full h-full flex items-center justify-center p-4">
    <div class="relative w-full max-w-lg bg-white rounded-[2rem] shadow-2xl p-8">
        
        <!-- Close Button -->
        <button onclick="document.getElementById('bankDetailsModal').classList.add('hidden')" class="absolute top-6 right-6 w-10 h-10 bg-gray-100 text-gray-500 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
            <i class="fas fa-times"></i>
        </button>

        <div class="text-center mb-8">
            <h2 class="text-2xl font-black text-gray-900 mb-2">Payment Details</h2>
            <p class="text-gray-500 font-medium text-sm">Saved payment methods and bank details</p>
        </div>

        <div class="space-y-4">
            <!-- Bank Detail -->
            <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Company / Agent Name</p>
                        <p class="text-sm font-black text-gray-900">{{ $agent->name ?? $agent->agency_name ?? 'Tour Raja Agent' }}</p>
                    </div>
                    <i class="fas fa-building text-gray-300 text-xl"></i>
                </div>
            </div>

            <!-- UPI Detail -->
            <div class="bg-white rounded-2xl p-4 border-2 border-orange-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-[#ea580c] mr-3">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-900">Default UPI</p>
                            <p class="text-[10px] text-gray-500 mt-0.5">agent@upi</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 bg-orange-50 text-[#ea580c] text-[10px] font-bold rounded uppercase tracking-wider">Primary</span>
                </div>
            </div>

            <!-- Card Detail -->
            <div class="bg-white rounded-2xl p-4 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-600 mr-3">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-900">Visa ending in 4242</p>
                            <p class="text-[10px] text-gray-500 mt-0.5">Expires 12/26</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <button class="w-full mt-8 py-3 bg-gray-100 text-gray-600 text-xs font-black rounded-xl hover:bg-gray-200 transition-colors uppercase tracking-widest"><i class="fas fa-plus mr-1"></i> Add New Payment Method</button>
    </div>
</div>

@if(session('show_upgrade_modal'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('upgradeModal').classList.remove('hidden');
    });
</script>
@endif



<script>
function filterBoosts() {
    const filter = document.getElementById('boostFilter').value;
    const cards = document.querySelectorAll('.boost-card');
    cards.forEach(card => {
        if (filter === 'all') {
            card.style.display = 'flex';
        } else {
            card.style.display = card.dataset.status === filter ? 'flex' : 'none';
        }
    });
}
</script>
@endsection
