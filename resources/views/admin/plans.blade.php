@extends('layouts.admin')

@section('content')
<div class="space-y-10 pb-12" x-data="{ showAddModal: false, showEditModal: false, editPlan: { id: '', name: '', price: '', duration: '', status: '' } }">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <h2 class="font-black text-foreground tracking-tight">Subscription Plans</h2>
            <p class="text-muted-text font-medium">Define and configure agent-tier subscription levels and benefits.</p>
        </div>
        <button @click="showAddModal = true" class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
            <i data-lucide="plus" size="20"></i> Create New Tier
        </button>
    </div>

    <!-- Tier Comparison Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @forelse($plans as $plan)
            @php
                $features = json_decode($plan->features, true) ?? ['Access to standard packages'];
                $activeUsersCount = \DB::table('paid_users')->where('plan', $plan->name)->count();
            @endphp
            <div class="bg-white p-10 rounded-[40px] shadow-premium border border-border-soft relative overflow-hidden group hover-lift transition-all">
                @if($loop->first)
                    <div class="absolute top-8 -right-12 bg-primary text-white text-[10px] font-black uppercase tracking-widest px-12 py-1 rotate-45 shadow-lg">Premium</div>
                @endif
                <div class="space-y-8">
                    <div class="space-y-4">
                        <span class="px-4 py-1.5 rounded-full bg-primary/5 text-primary text-[10px] font-black uppercase tracking-widest">
                            {{ $plan->name }}
                        </span>
                        <div class="flex items-baseline gap-1">
                            <h2 class="font-black text-foreground">₹{{ number_format($plan->price, 2) }}</h2>
                            <span class="text-muted-text font-bold text-sm">/ {{ $plan->duration }}</span>
                        </div>
                    </div>

                    <div class="space-y-4 border-t border-border-soft pt-8">
                        @foreach($features as $feature)
                            <div class="flex items-center gap-3">
                                <div class="w-5 h-5 bg-green-50 text-green-500 rounded-full flex items-center justify-center">
                                    <i data-lucide="check" size="12"></i>
                                </div>
                                <span class="text-sm font-medium text-muted-text">{{ $feature }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-4">
                        <div class="flex items-center justify-between text-[10px] font-bold text-muted-text uppercase tracking-widest mb-2">
                            <span>Active Subscriptions</span>
                            <span>{{ $activeUsersCount }}</span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full" style="width: {{ min(100, max(5, ($activeUsersCount / 100) * 100)) }}%"></div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <button 
                            @click="showEditModal = true; editPlan = { id: '{{ $plan->id }}', name: '{{ addslashes($plan->name) }}', price: '{{ $plan->price }}', duration: '{{ addslashes($plan->duration) }}', status: '{{ $plan->status }}' }"
                            class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 text-foreground text-xs font-black uppercase tracking-widest rounded-xl transition-all"
                        >
                            Edit Tier
                        </button>
                        <a 
                            href="{{ url('/admin/plans/delete/' . $plan->id) }}" 
                            onclick="return confirm('Are you sure you want to delete this subscription plan tier?');"
                            class="p-3 bg-red-50 hover:bg-red-100 text-red-500 rounded-xl transition-all"
                        >
                            <i data-lucide="trash-2" size="16"></i>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 py-12 text-center text-sm font-bold text-muted-text">No subscription plans registered.</div>
        @endforelse
    </div>

    <!-- ================= MODALS ================= -->

    <!-- Add Plan Modal -->
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
                    <h3 class="text-xl font-black text-foreground">Create Plan Tier</h3>
                    <p class="text-xs text-muted-text font-medium">Log a new global agent subscription tier.</p>
                </div>
                <button @click="showAddModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/plans/store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Plan Tier Name<span class="text-primary">*</span></label>
                    <input required type="text" name="name" placeholder="E.g. Gold Plus" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Price (INR)<span class="text-primary">*</span></label>
                        <input required type="number" step="0.01" name="price" placeholder="E.g. 1999" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Duration</label>
                        <input required type="text" name="duration" placeholder="E.g. Month, Year" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                    <select name="status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="flex items-center justify-end gap-4 pt-4">
                    <button type="button" @click="showAddModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Save Tier</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Plan Modal -->
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
                    <h3 class="text-xl font-black text-foreground">Edit Tier Settings</h3>
                    <p class="text-xs text-muted-text font-medium">Modify pricing and duration thresholds.</p>
                </div>
                <button @click="showEditModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/plans/update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="id" x-model="editPlan.id" />
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Plan Tier Name<span class="text-primary">*</span></label>
                    <input required type="text" name="name" x-model="editPlan.name" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Price (INR)<span class="text-primary">*</span></label>
                        <input required type="number" step="0.01" name="price" x-model="editPlan.price" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Duration</label>
                        <input required type="text" name="duration" x-model="editPlan.duration" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                    <select name="status" x-model="editPlan.status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
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
