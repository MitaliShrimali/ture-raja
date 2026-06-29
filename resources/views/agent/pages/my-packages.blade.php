@extends('agent.layouts.app')

@section('title', 'My Packages - Tour Raja Agent')

@section('content')

@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4 text-green-700 text-sm font-semibold flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    {{ session('success') }}
</div>
@endif

<!-- Search + Create Bar -->
<div class="flex items-center gap-4 mb-8">
    <div class="flex-1 bg-white p-2 rounded-2xl shadow-sm border border-gray-100 flex items-center">
        <div class="flex-grow flex items-center px-4">
            <i class="fas fa-search text-gray-300 mr-3"></i>
            <input type="text" id="pkgSearchInput" oninput="filterPackages()" placeholder="Search packages by name..." class="w-full bg-transparent border-none outline-none text-sm text-gray-600 placeholder:text-gray-300">
        </div>
        <button class="bg-[#e85d26] text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-lg shadow-orange-100">
            <i class="fas fa-search"></i>
        </button>
    </div>
    <a href="{{ route('agent.packages.create') }}" class="flex items-center gap-2 px-5 py-3 bg-[#e85d26] text-white rounded-2xl font-bold text-xs uppercase tracking-wider shadow-lg shadow-orange-100 hover:bg-orange-600 transition-all whitespace-nowrap">
        <i class="fas fa-plus"></i>
        <span>Create Package</span>
    </a>
</div>

<!-- Packages Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-6" id="packagesGrid">

    @forelse($packages as $pkg)
    @php
        $isActive = $pkg->status === 'Active';
        $isPending = $pkg->status === 'Pending';
        $statusColor = $isActive ? 'bg-green-50 text-green-600 border-green-100' : ($isPending ? 'bg-yellow-50 text-yellow-600 border-yellow-100' : 'bg-orange-50 text-[#e85d26] border-orange-100');
    @endphp
    <div class="bg-white rounded-[32px] p-4 shadow-sm border border-gray-100 group hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-500 package-card" data-name="{{ strtolower($pkg->title) }}" id="pkg-card-{{ $pkg->id }}">
        <div class="relative mb-4 overflow-hidden rounded-[24px]">
            <img src="{{ asset($pkg->image ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=500') }}" class="w-full h-40 object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $pkg->title }}">
            @if(!$isPending)
            <!-- Toggle Button -->
            <button onclick="togglePkgStatus({{ $pkg->id }}, this)" id="toggle-btn-{{ $pkg->id }}" class="absolute top-3 right-3 w-8 h-4 rounded-full border-2 border-white flex items-center transition-all duration-300 {{ $isActive ? 'bg-[#e85d26] justify-end' : 'bg-gray-300 justify-start' }}">
                <div class="w-2.5 h-2.5 bg-white rounded-full mx-0.5"></div>
            </button>
            @endif
            @if($isPending)
            <div class="absolute top-3 left-3 bg-yellow-500 text-white text-[8px] font-black px-2 py-1 rounded-full uppercase tracking-wider">Pending Review</div>
            @endif
        </div>

        <div class="flex items-center space-x-2 mb-1">
            <h4 class="text-sm font-bold text-gray-800 truncate flex-1">{{ $pkg->title }}</h4>
            <span class="text-[8px] font-bold px-2 py-0.5 rounded-full border uppercase italic tracking-tighter {{ $statusColor }}">{{ $pkg->status }}</span>
        </div>
        <p class="text-[9px] text-gray-400 font-medium mb-3">{{ ucfirst($pkg->category) }} • {{ $pkg->group_size ?? 'Direct Flight' }} • {{ $pkg->location }}</p>

        <p class="text-[9px] text-gray-400 leading-relaxed line-clamp-2 mb-4">
            Explore the best of {{ $pkg->location ?? $pkg->title }}. Comfortable stays, smooth transfers, and carefully selected sightseeing.
        </p>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <p class="text-[8px] font-bold text-gray-300 uppercase tracking-widest">Duration</p>
                <p class="text-[10px] font-bold text-gray-800">{{ $pkg->duration ?? '—' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-bold text-gray-300 uppercase tracking-widest">Validity</p>
                <p class="text-[10px] font-bold text-gray-800">{{ $pkg->stock ?? '—' }}</p>
            </div>
            <div>
                <p class="text-[8px] font-bold text-gray-300 uppercase tracking-widest">Price</p>
                <p class="text-[10px] font-bold text-gray-800">{{ $pkg->currency ?? '₹' }} {{ number_format($pkg->price) }}</p>
            </div>
            <div>
                <p class="text-[8px] font-bold text-gray-300 uppercase tracking-widest">Category</p>
                <p class="text-[10px] font-bold text-gray-800">{{ ucfirst($pkg->category ?? 'General') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-2">
            <a href="{{ route('agent.packages.edit', $pkg->id) }}" class="flex items-center justify-center space-x-1 py-2 border border-orange-100 rounded-xl text-[#e85d26] text-[10px] font-bold hover:bg-orange-50 transition-colors">
                <i class="fas fa-edit text-[8px]"></i> <span>Edit</span>
            </a>
            <button onclick="deletePkg({{ $pkg->id }})" class="flex items-center justify-center space-x-1 py-2 border border-red-50 rounded-xl text-red-400 text-[10px] font-bold hover:bg-red-50 transition-colors">
                <i class="fas fa-trash text-[8px]"></i> <span>Delete</span>
            </button>
        </div>
    </div>
    @empty
    <!-- Empty state: only show the create card -->
    @endforelse

    <!-- Create New Card (always shown) -->
    <a href="{{ route('agent.packages.create') }}" class="bg-gray-100/50 rounded-[32px] border-2 border-dashed border-gray-200 flex flex-col items-center justify-center p-8 text-center hover:bg-gray-100 transition-colors cursor-pointer group min-h-[300px]">
        <div class="w-12 h-12 rounded-full border-2 border-gray-300 flex items-center justify-center text-gray-400 mb-4 group-hover:bg-white group-hover:border-[#e85d26] group-hover:text-[#e85d26] transition-all">
            <i class="fas fa-plus"></i>
        </div>
        <p class="text-sm font-bold text-gray-400 group-hover:text-gray-800 transition-colors">Create a New Package</p>
        <p class="text-[10px] text-gray-300 mt-1 font-medium">Fill in details and submit for review</p>
    </a>
</div>

@if($packages->isEmpty())
<div class="text-center py-8">
    <p class="text-gray-400 text-sm font-medium">You haven't created any packages yet. Click <strong>Create Package</strong> to get started!</p>
</div>
@endif

<script>
    function filterPackages() {
        const searchVal = document.getElementById('pkgSearchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.package-card');
        cards.forEach(card => {
            const name = card.getAttribute('data-name');
            card.style.display = name.includes(searchVal) ? 'block' : 'none';
        });
    }

    async function togglePkgStatus(id, btn) {
        try {
            const response = await fetch(`/agent/packages/toggle/${id}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            if (data.success) {
                if (data.new_status === 'Inactive') {
                    btn.classList.remove('justify-end', 'bg-[#e85d26]');
                    btn.classList.add('justify-start', 'bg-gray-300');
                    btn.style.backgroundColor = '#d1d5db';
                    toastr.info('Package disabled globally!');
                } else {
                    btn.classList.remove('justify-start', 'bg-gray-300');
                    btn.classList.add('justify-end', 'bg-[#e85d26]');
                    btn.style.backgroundColor = '#e85d26';
                    toastr.success('Package enabled and live globally!');
                }
            } else {
                toastr.error(data.message || 'Error updating status');
            }
        } catch(error) {
            toastr.error('Failed to communicate with server');
        }
    }

    function deletePkg(id) {
        Swal.fire({
            title: 'Delete Package?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e85d26',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                const card = document.getElementById('pkg-card-' + id);
                if (card) {
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        card.remove();
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Package deleted successfully.',
                            icon: 'success',
                            confirmButtonColor: '#e85d26',
                        });
                    }, 300);
                }
            }
        });
    }

    // Initialize toggle buttons correctly
    document.querySelectorAll('[id^="toggle-btn-"]').forEach(btn => {
        if (btn.classList.contains('bg-[#e85d26]')) {
            btn.style.backgroundColor = '#e85d26';
        } else {
            btn.style.backgroundColor = '#d1d5db';
        }
    });
</script>
@endsection
