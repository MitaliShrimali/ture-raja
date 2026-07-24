@extends('agent.layouts.app')

@section('title', 'Services - Tour Raja Agent')

@section('content')
@php
    $selectedServices = json_decode($agent->services ?? '[]', true) ?: [];
    $selectedNames = array_column($selectedServices, 'name');
    
    $defaultServices = [
        ['name' => 'Visa', 'icon' => 'fas fa-passport'],
        ['name' => 'Travel Insurance', 'icon' => 'fas fa-shield-alt'],
        ['name' => 'Flight Booking', 'icon' => 'fas fa-plane'],
        ['name' => 'International Tour', 'icon' => 'fas fa-globe'],
        ['name' => 'Domestic Tour', 'icon' => 'fas fa-map-marked-alt'],
        ['name' => 'Train Booking', 'icon' => 'fas fa-train'],
        ['name' => 'Passport', 'icon' => 'fas fa-id-card'],
        ['name' => 'Bus Booking', 'icon' => 'fas fa-bus'],
        ['name' => 'Hotel Booking', 'icon' => 'fas fa-hotel'],
        ['name' => 'Holidays Packages', 'icon' => 'fas fa-umbrella-beach'],
        ['name' => 'Cruise Packages', 'icon' => 'fas fa-ship'],
        ['name' => 'Ticket Reservation', 'icon' => 'fas fa-ticket-alt'],
        ['name' => 'Rental Car/Bikes', 'icon' => 'fas fa-car'],
        ['name' => 'Devotions Package', 'icon' => 'fas fa-pray'],
    ];

    // Identify custom services (services in selected list but not in defaults)
    $defaultNames = array_column($defaultServices, 'name');
    $customServices = [];
    foreach ($selectedServices as $s) {
        if (!in_array($s['name'], $defaultNames)) {
            $customServices[] = $s;
        }
    }
    
    // Total list to show
    $allServices = array_merge($defaultServices, $customServices);
@endphp

<div class="max-w-6xl mx-auto space-y-8">
    <!-- Header Area -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800 tracking-tight">Our Services</h2>
            <p class="text-xs text-gray-400 mt-1">Select the services you offer. Selected services will display on your package details page.</p>
        </div>
        <button onclick="openAddServiceModal()" class="px-5 py-2.5 bg-primary text-white rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-orange-100 hover:scale-105 transition-all flex items-center gap-2">
            <i class="fas fa-plus"></i> Add Service
        </button>
    </div>

    <!-- Services Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($allServices as $s)
            @php
                $isChecked = in_array($s['name'], $selectedNames);
            @endphp
            <div class="bg-white p-5 rounded-[24px] shadow-sm border border-gray-100 flex items-center justify-between group hover:shadow-md transition-all">
                <div class="flex items-center">
                    <div class="w-11 h-11 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center mr-4 group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                        <i class="{{ $s['icon'] }} text-base"></i>
                    </div>
                    <h4 class="text-xs font-bold text-gray-700 leading-tight group-hover:text-gray-900 transition-colors">{{ $s['name'] }}</h4>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" value="1" class="sr-only peer service-checkbox" data-name="{{ $s['name'] }}" data-icon="{{ $s['icon'] }}" {{ $isChecked ? 'checked' : '' }}>
                    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary"></div>
                </label>
            </div>
        @endforeach
    </div>
</div>

<!-- Add Service Modal -->
<div id="addServiceModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
    <div class="bg-white rounded-[32px] w-full max-w-md p-8 shadow-2xl relative animate-in fade-in-50 zoom-in-95 duration-150">
        <button onclick="closeAddServiceModal()" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fas fa-times text-lg"></i>
        </button>

        <h3 class="text-lg font-bold text-gray-800 mb-6">Add Custom Service</h3>
        
        <form action="{{ route('agent.services.add') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2.5 tracking-wider">Service Name *</label>
                <input type="text" name="name" required placeholder="e.g. Helicopter Tour" class="w-full px-4 py-3 rounded-xl bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
            </div>

            <div>
                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2.5 tracking-wider">Select Icon *</label>
                <div class="grid grid-cols-5 gap-3 max-h-40 overflow-y-auto p-2 bg-gray-50 rounded-xl">
                    @php
                        $icons = [
                            'fas fa-passport', 'fas fa-shield-alt', 'fas fa-plane', 'fas fa-globe', 
                            'fas fa-map-marked-alt', 'fas fa-train', 'fas fa-id-card', 'fas fa-bus', 
                            'fas fa-hotel', 'fas fa-umbrella-beach', 'fas fa-ship', 'fas fa-ticket-alt', 
                            'fas fa-car', 'fas fa-pray', 'fas fa-map', 'fas fa-compass', 'fas fa-suitcase',
                            'fas fa-hiking', 'fas fa-camera', 'fas fa-map-signs'
                        ];
                    @endphp
                    @foreach($icons as $idx => $icon)
                        <label class="flex flex-col items-center justify-center p-2.5 bg-white border border-gray-100 rounded-lg cursor-pointer hover:border-primary hover:bg-orange-50/30 transition-all select-icon-label relative">
                            <input type="radio" name="icon" value="{{ $icon }}" class="sr-only select-icon-radio" {{ $idx === 0 ? 'checked' : '' }}>
                            <i class="{{ $icon }} text-base text-gray-500 hover:text-primary transition-colors"></i>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-4">
                <button type="button" onclick="closeAddServiceModal()" class="px-5 py-3 text-xs font-bold text-gray-400 hover:text-gray-600 transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg shadow-orange-100">Add Service</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openAddServiceModal() {
        document.getElementById('addServiceModal').classList.remove('hidden');
    }

    function closeAddServiceModal() {
        document.getElementById('addServiceModal').classList.add('hidden');
    }

    // Handle active icon selection visual
    document.addEventListener('DOMContentLoaded', () => {
        const labels = document.querySelectorAll('.select-icon-label');
        labels.forEach(lbl => {
            const radio = lbl.querySelector('.select-icon-radio');
            if (radio.checked) {
                lbl.classList.add('border-primary', 'bg-orange-50/50');
                lbl.querySelector('i').classList.add('text-primary');
            }
            lbl.addEventListener('click', () => {
                labels.forEach(l => {
                    l.classList.remove('border-primary', 'bg-orange-50/50');
                    l.querySelector('i').classList.remove('text-primary');
                });
                lbl.classList.add('border-primary', 'bg-orange-50/50');
                lbl.querySelector('i').classList.add('text-primary');
            });
        });

        // Handle checkbox toggles via AJAX
        document.querySelectorAll('.service-checkbox').forEach(chk => {
            chk.addEventListener('change', () => {
                const name = chk.getAttribute('data-name');
                const icon = chk.getAttribute('data-icon');
                const checked = chk.checked;

                fetch("{{ route('agent.services.toggle') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name: name,
                        icon: icon,
                        checked: checked
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Flash message or subtle toast
                    } else {
                        chk.checked = !checked;
                        alert('Failed to update service status');
                    }
                })
                .catch(err => {
                    console.error('Error toggling service:', err);
                    chk.checked = !checked;
                    alert('Error updating service status');
                });
            });
        });
    });
</script>
@endpush

<style>
    .select-icon-label input[type="radio"]:checked + i {
        color: #F0642F !important;
    }
</style>
@endsection
