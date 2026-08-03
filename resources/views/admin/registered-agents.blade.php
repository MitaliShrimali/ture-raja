@extends('layouts.admin')

@section('admin_title', 'Registered Agents')

@section('content')
<div class="space-y-10 pb-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Admin / Management</p>
            <h2 class="font-black text-foreground tracking-tight">All paid user</h2>
            <p class="text-muted-text font-medium">Viewing all active and inactive paid users.</p>
        </div>
        <a href="{{ url('/admin/agents') }}" class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3 group">
            <i data-lucide="plus" size="20" class="group-hover:rotate-90 transition-transform"></i>
            Add Paid User
        </a>
    </div>

    <!-- Existing Agents List -->
    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <form method="GET" action="{{ url('/admin/registered-agents') }}" class="p-6 border-b border-border-soft flex flex-col gap-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-4 text-sm font-bold text-muted-text">
                    <select name="guaranteed" class="bg-gray-50 border-none rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-primary/20 text-sm w-full md:w-auto">
                        <option value="">Guaranteed</option>
                        <option value="1" {{ request('guaranteed') == '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ request('guaranteed') == '0' ? 'selected' : '' }}>No</option>
                    </select>
                    
                    <select name="plan_id" class="bg-gray-50 border-none rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-primary/20 text-sm w-full md:w-auto">
                        <option value="">Plan Type</option>
                        @foreach($plans ?? [] as $plan)
                            <option value="{{ $plan->id }}" {{ request('plan_id') == $plan->id ? 'selected' : '' }}>{{ $plan->name }}</option>
                        @endforeach
                    </select>

                    <select name="status" class="bg-gray-50 border-none rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-primary/20 text-sm w-full md:w-auto">
                        <option value="">Status</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>

                    <button type="submit" class="bg-primary hover:bg-primary-hover text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg shadow-primary/20 w-full md:w-auto transition-all">Filter</button>
                    <a href="{{ url('/admin/registered-agents') }}" class="text-muted-text hover:text-primary transition-colors text-sm font-bold px-4 py-3 bg-gray-50 hover:bg-gray-100 rounded-xl w-full md:w-auto text-center">Clear</a>
                </div>
                
                <div class="flex items-center gap-2 w-full md:w-80">
                    <div class="relative group w-full">
                        <i data-lucide="search" class="absolute left-5 top-1/2 -translate-y-1/2 text-muted-text group-focus-within:text-primary transition-colors pointer-events-none" size="18"></i>
                        <input 
                            type="text" 
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search agent by name, email, or firm..." 
                            class="w-full bg-gray-50/80 border-0 rounded-2xl py-4 pl-14 pr-6 outline-none focus:ring-2 focus:ring-[#ea580c]/20 transition-all font-medium text-sm shadow-sm"
                        >
                    </div>
                    <button type="submit" class="bg-[#ea580c] hover:bg-orange-600 text-white p-3 rounded-2xl transition-colors shadow-sm shrink-0">
                        <i data-lucide="search" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
        </form>

        <div class="admin-table-container">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">#</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Travel Agent Name</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Email</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Mobile</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Guaranteed</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">Plan</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">Pending</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">Approved</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">Status</th>
                        <th class="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-soft">
                    @forelse($agents as $index => $agent)
                        @php
                            $srNo = str_pad($agents->firstItem() + $index, 2, '0', STR_PAD_LEFT);
                        @endphp
                        <tr class="group hover:bg-gray-50/30 transition-colors">
                            <!-- Serial Number -->
                            <td class="py-6 px-8 text-sm font-bold text-muted-text opacity-60">
                                {{ $srNo }}
                            </td>
                            
                            <td class="py-6 px-8">
                                <a href="{{ url('/admin/agents/profile/' . $agent->id) }}" class="text-sm font-black text-primary hover:text-primary-hover transition-colors leading-tight flex items-center gap-1.5 whitespace-nowrap">
                                    <span class="truncate max-w-[150px]">{{ $agent->name }}</span>
                                    @if(!empty($agent->service_guaranteed))
                                        <i data-lucide="check-circle" class="text-blue-500 shrink-0" size="16" title="Trusted Agent"></i>
                                    @endif
                                </a>
                            </td>
                            
                            <!-- Email -->
                            <td class="py-6 px-8 text-sm font-medium text-muted-text">
                                {{ $agent->email }}
                            </td>
                            
                            <!-- Mobile -->
                            <td class="py-6 px-8 text-sm font-medium text-muted-text">
                                {{ $agent->phone }}
                            </td>
                            
                            <!-- Guaranteed -->
                            <td class="py-6 px-8">
                                @if(!empty($agent->service_guaranteed))
                                    <span class="px-3 py-1 rounded-full bg-green-50 text-green-500 text-[10px] font-black uppercase tracking-wider">
                                        Yes
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-gray-50 text-gray-400 text-[10px] font-black uppercase tracking-wider">
                                        No
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Plan -->
                            <td class="py-6 px-8">
                                <a href="{{ url('/admin/plans') }}" class="inline-block hover:scale-105 transition-all">
                                    @php
                                        $pName = strtolower($agent->plan_name ?? 'basic');
                                    @endphp
                                    @if(isset($agent->tier) && $agent->tier === 'Customise')
                                        <span class="px-3 py-1 rounded-full bg-yellow-50 text-yellow-600 text-[10px] font-black uppercase tracking-wider">
                                            Custom
                                        </span>
                                    @elseif($pName === 'premium')
                                        <span class="px-3 py-1 rounded-full bg-purple-50 text-purple-500 text-[10px] font-black uppercase tracking-wider">
                                            Premium
                                        </span>
                                    @elseif($pName === 'enterprise')
                                        <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-500 text-[10px] font-black uppercase tracking-wider">
                                            Enterprise
                                        </span>
                                    @elseif($pName === 'standard')
                                        <span class="px-3 py-1 rounded-full bg-orange-50 text-orange-500 text-[10px] font-black uppercase tracking-wider">
                                            Standard
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-black uppercase tracking-wider">
                                            {{ $agent->plan_name ?? 'Basic' }}
                                        </span>
                                    @endif
                                </a>
                            </td>
                            
                            <!-- Pending -->
                            <td class="py-6 px-8 text-center">
                                <span class="text-base font-black text-primary">
                                    {{ sprintf('%02d', $agent->pending ?? 0) }}
                                </span>
                            </td>
                            
                            <!-- Approved -->
                            <td class="py-6 px-8 text-center">
                                <span class="text-base font-black text-green-600">
                                    {{ sprintf('%02d', $agent->approved ?? 0) }}
                                </span>
                            </td>
                            
                            <!-- Status -->
                            <td class="py-6 px-8 text-center">
                                 <a href="{{ url('/admin/agents/toggle/' . $agent->id) }}" class="inline-flex items-center cursor-pointer">
                                     <div class="relative inline-flex items-center">
                                         <input type="checkbox" class="sr-only peer" {{ strtolower($agent->status) === 'active' ? 'checked' : '' }} disabled>
                                         <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                     </div>
                                 </a>
                             </td>
                            
                            <!-- Actions -->
                            <td class="py-6 px-8">
                                <div class="flex items-center justify-center gap-2">
                                    <a 
                                        href="{{ url('/admin/agents/profile/' . $agent->id) }}"
                                        class="p-2.5 text-muted-text hover:text-blue-500 hover:bg-blue-50 rounded-xl transition-all"
                                        title="View Profile"
                                    >
                                        <i data-lucide="eye" size="18"></i>
                                    </a>
                                    <a 
                                        href="{{ url('/admin/agents/edit/' . $agent->id) }}"
                                        class="p-2.5 text-muted-text hover:text-primary hover:bg-primary/5 rounded-xl transition-all"
                                        title="Edit"
                                    >
                                        <i data-lucide="edit-3" size="18"></i>
                                    </a>
                                    <a 
                                        href="{{ url('/admin/agents/delete/' . $agent->id) }}" 
                                        onclick="return confirm('Are you sure you want to remove this agent?');"
                                        class="p-2.5 text-muted-text hover:text-red-500 hover:bg-red-50 rounded-xl transition-all"
                                        title="Delete"
                                    >
                                        <i data-lucide="trash-2" size="20"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-12 text-center text-sm font-bold text-muted-text">No registered agents found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        <div class="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
            <p class="text-sm font-bold text-muted-text">Showing {{ $agents->firstItem() ?? 0 }} to {{ $agents->lastItem() ?? 0 }} of {{ $agents->total() }} entries</p>
            <div class="flex items-center gap-2">
                @if($agents->onFirstPage())
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-left" size="20"></i></button>
                @else
                    <a href="{{ $agents->previousPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-left" size="20"></i></a>
                @endif
                
                @foreach(range(1, $agents->lastPage()) as $i)
                    @if($i == 1 || $i == $agents->lastPage() || abs($i - $agents->currentPage()) <= 1)
                        @if($i == $agents->currentPage())
                            <button class="w-10 h-10 rounded-full text-sm font-black bg-primary text-white shadow-lg shadow-primary/20 transition-all">
                                {{ $i }}
                            </button>
                        @else
                            <a href="{{ $agents->url($i) }}" class="w-10 h-10 rounded-full text-sm font-black transition-all text-muted-text hover:bg-white hover:text-primary flex items-center justify-center">
                                {{ $i }}
                            </a>
                        @endif
                    @elseif($i == 2 || $i == $agents->lastPage() - 1)
                        <span class="text-muted-text font-black px-1">...</span>
                    @endif
                @endforeach
                
                @if($agents->hasMorePages())
                    <a href="{{ $agents->nextPageUrl() }}" class="p-2 text-muted-text hover:text-primary transition-colors"><i data-lucide="chevron-right" size="20"></i></a>
                @else
                    <button class="p-2 text-muted-text opacity-40 cursor-not-allowed" disabled><i data-lucide="chevron-right" size="20"></i></button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
