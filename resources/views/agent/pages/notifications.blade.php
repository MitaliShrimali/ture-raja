@extends('agent.layouts.app')

@section('title', 'Notifications - Tour Raja Agent')

@section('content')


        <!-- Search Bar -->
        <div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-100 flex items-center mb-12">
            <div class="flex-grow flex items-center px-4">
                <i class="fas fa-search text-gray-300 mr-3"></i>
                <input type="text" id="notificationSearchInput" oninput="filterNotifications()" placeholder="Search Notification" class="w-full bg-transparent border-none outline-none text-sm text-gray-600 placeholder:text-gray-300">
            </div>
            <button class="bg-primary text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-lg shadow-orange-100">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <div class="space-y-12">
            <!-- Notification Center -->
            <div class="bg-white p-10 rounded-[48px] shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">Notification Center</h3>
                        <p class="text-[11px] text-gray-400 font-medium mt-1">Stay updated with your agency's activity and performance.</p>
                    </div>
                    <div class="flex space-x-3">
                        <button onclick="markAllAsRead()" class="bg-gray-50 text-gray-800 px-4 py-2 rounded-xl text-[10px] font-bold flex items-center hover:bg-gray-100"><i class="fas fa-check-double mr-2"></i> Mark all as read</button>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($notifications as $notif)
                        @php
                            $borderColor = 'border-orange-400';
                            $bgColor = 'bg-orange-100';
                            $textColor = 'text-orange-500';
                            $icon = 'fas fa-info-circle';

                            if ($notif->type === 'Alert') {
                                $borderColor = 'border-red-400';
                                $bgColor = 'bg-red-100';
                                $textColor = 'text-red-500';
                                $icon = 'fas fa-exclamation-triangle';
                            } elseif ($notif->type === 'Warning') {
                                $borderColor = 'border-yellow-400';
                                $bgColor = 'bg-yellow-100';
                                $textColor = 'text-yellow-500';
                                $icon = 'fas fa-bell';
                            }
                        @endphp
                        <div class="notification-item flex items-start p-6 rounded-[32px] border-l-4 {{ $borderColor }} {{ $notif->is_read ? 'bg-white shadow-sm' : 'bg-gray-50/50 hover:bg-white hover:shadow-xl hover:shadow-gray-200/50' }} transition-all group relative">
                            <div class="w-10 h-10 {{ $bgColor }} {{ $textColor }} rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="{{ $icon }} text-xs"></i>
                            </div>
                            <div class="ml-6 flex-grow">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="text-sm font-bold text-gray-800">{{ $notif->title }}</h4>
                                    <span class="text-[8px] font-bold text-gray-300 uppercase tracking-widest">
                                        {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-[10px] text-gray-400 font-medium leading-relaxed max-w-xl">{{ $notif->message }}</p>
                            </div>
                            @if(!$notif->is_read)
                                <div class="unread-dot absolute right-6 top-1/2 -translate-y-1/2 w-2 h-2 bg-orange-500 rounded-full"></div>
                            @endif
                        </div>
                    @empty
                        <div class="py-12 text-center">
                            <i class="fas fa-bell-slash text-gray-200 text-4xl mb-4"></i>
                            <p class="text-xs font-bold text-gray-400">No notifications found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Insights & Profile -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Smart Insights -->
                <div class="lg:col-span-4 bg-orange-800 p-8 rounded-[48px] shadow-2xl shadow-orange-100 relative overflow-hidden group">
                    <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-orange-700/50 rounded-full"></div>
                    <div class="w-10 h-10 bg-orange-700/50 rounded-xl flex items-center justify-center text-white mb-6"><i class="fas fa-magic text-xs"></i></div>
                    <h4 class="text-2xl font-bold text-white mb-3 tracking-tight">Smart Insights</h4>
                    <p class="text-[11px] text-orange-100/70 font-medium leading-relaxed">Your booking frequency is 15% higher than local average this month.</p>
                </div>

                <!-- Maximize Profile -->
                <div class="lg:col-span-8 bg-white p-10 rounded-[48px] shadow-sm border border-gray-100 flex items-center gap-10">
                    <div class="flex-grow space-y-6">
                        <h4 class="text-2xl font-bold text-gray-800 tracking-tight">Maximize Your Profile</h4>
                        <p class="text-[11px] text-gray-400 font-medium leading-relaxed">Complete your agency's detailed description to unlock the 'Elite Explorer' badge and 5% extra commission.</p>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center text-[10px] font-bold text-gray-400">
                                <span class="uppercase tracking-widest">Profile Completion</span>
                                <span class="text-orange-800">65%</span>
                            </div>
                            <div class="h-2 w-full bg-gray-50 rounded-full overflow-hidden">
                                <div class="h-full bg-orange-800 rounded-full" style="width: 65%"></div>
                            </div>
                        </div>
                        <button class="bg-gray-50 border border-gray-100 px-8 py-3 rounded-2xl text-[10px] font-bold text-gray-800 uppercase tracking-widest hover:bg-gray-100 transition-colors">Complete Profile</button>
                    </div>
                    <div class="w-1/3">
                        <div class="bg-cyan-50 rounded-[32px] p-4 relative aspect-square overflow-hidden group">
                            <img src="https://cdni.iconscout.com/illustration/premium/thumb/web-development-team-4064379-3363935.png" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-500">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <script>
            function filterNotifications() {
                const input = document.getElementById('notificationSearchInput');
                const filter = input.value.toLowerCase();
                const items = document.querySelectorAll('.notification-item');

                items.forEach(item => {
                    const text = item.textContent || item.innerText;
                    if (text.toLowerCase().indexOf(filter) > -1) {
                        item.style.display = "";
                    } else {
                        item.style.display = "none";
                    }
                });
            }

            function markAllAsRead() {
                fetch('{{ route("agent.notifications.mark-read") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message);
                        // Remove unread indicators and clear badge in navbar
                        document.querySelectorAll('.unread-dot').forEach(el => el.remove());
                        const navbarBadge = document.querySelector('.relative .absolute.bg-red-500');
                        if (navbarBadge) navbarBadge.remove();
                        // Optional: reload the page to refresh container classes
                        setTimeout(() => location.reload(), 800);
                    }
                })
                .catch(error => {
                    toastr.error('Failed to mark notifications as read.');
                });
            }
        </script>
@endsection
