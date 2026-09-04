@extends('agent.layouts.app')

@section('title', 'Branch - Tour Raja Agent')

@section('content')

        @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-xl text-red-700 text-sm font-semibold flex items-center shadow-sm">
            <i class="fas fa-exclamation-circle text-red-500 mr-3 text-lg"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-xl text-green-700 text-sm font-semibold flex items-center shadow-sm">
            <i class="fas fa-check-circle text-green-500 mr-3 text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <!-- Search Bar -->
        <div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-100 flex items-center mb-8">
            <div class="flex-grow flex items-center px-4">
                <i class="fas fa-search text-gray-300 mr-3"></i>
                <input type="text" id="branchSearchInput" oninput="filterBranches()" placeholder="Search/Edit Branch" class="w-full bg-transparent border-none outline-none text-sm text-gray-600 placeholder:text-gray-300">
            </div>
            <button class="bg-primary text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-lg shadow-orange-100">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <!-- Branch Table Container -->
        <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Branch</h3>
                @if($canAddBranch ?? true)
                <a href="{{ route('agent.add-branch') }}" class="bg-primary text-white px-6 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-orange-100 hover:scale-105 transition-all w-fit">
                    + Add Branch
                </a>
                @else
                <a href="{{ route('agent.add-branch') }}" class="bg-gray-400 text-white px-6 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg transition-all w-fit cursor-not-allowed" title="Branch limit reached">
                    + Add Branch (Limit Reached)
                </a>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[9px] font-bold text-gray-300 uppercase tracking-widest border-b border-gray-50 whitespace-nowrap">
                            <th class="pb-4 pl-4">Srl No.</th>
                            <th class="pb-4">Branch Names</th>
                            <th class="pb-4">Status</th>
                            <th class="pb-4">Location</th>
                            <th class="pb-4">State</th>
                            <th class="pb-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($branches as $index => $b)
                        @php
                            $city = $b->location;
                            $state = $b->state;
                            if (empty($state) && strpos($b->location, ',') !== false) {
                                $parts = explode(',', $b->location);
                                $city = trim($parts[0] ?? '');
                                $state = trim($parts[1] ?? '');
                            }
                            $srl = 100 + $index + 1;
                        @endphp
                        <tr class="group hover:bg-gray-50/50 transition-colors whitespace-nowrap" id="branch-row-{{ $b->id }}">
                            <td class="py-4 pl-4 text-xs font-bold text-gray-800">{{ $srl }}</td>
                            <td class="py-4">
                                <div class="flex items-center">
                                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=100&auto=format&fit=crop" class="w-10 h-10 rounded-xl object-cover mr-3 border border-gray-100">
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-800">{{ $b->agency_name }}</p>
                                        <p class="text-[8px] text-gray-400 font-medium">{{ $city }}{{ $state ? ', ' . $state : '' }}{{ $b->country ? ', ' . $b->country : '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4">
                                @php
                                    $displayStatus = $b->status;
                                    if(strtolower($b->status) == 'online') $displayStatus = 'Active';
                                    if(strtolower($b->status) == 'offline') $displayStatus = 'Inactive';
                                @endphp
                                <span class="px-3 py-1 rounded-lg text-[8px] font-bold uppercase tracking-tighter {{ $displayStatus == 'Active' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                    {{ $displayStatus }}
                                </span>
                            </td>
                            <td class="py-4 text-[10px] font-bold text-gray-800">{{ $city }}</td>
                            <td class="py-4 text-[10px] font-bold text-gray-800">{{ strtoupper($state) }}</td>
                            <td class="py-4 text-center">
                                @if($b->id != 0)
                                <div class="flex items-center justify-center space-x-3">
                                    <a href="{{ route('agent.edit-branch', $b->id) }}" class="text-[9px] font-bold text-gray-400 hover:text-gray-800 transition-colors">Edit</a>
                                    <a href="javascript:void(0)" onclick="deleteBranch({{ $b->id }})" class="text-[9px] font-bold text-gray-400 hover:text-red-500 transition-colors">Delete</a>
                                </div>
                                @else
                                <div class="flex items-center justify-center space-x-3">
                                    <span class="text-[9px] font-bold text-gray-300">Main Profile</span>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <script>
            function deleteBranch(id) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#F0642F',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    borderRadius: '2rem'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ url("agent/branch/delete") }}/' + id;
                    }
                });
            }
            function filterBranches() {
                const input = document.getElementById('branchSearchInput');
                const filter = input.value.toLowerCase();
                const tbody = document.querySelector('tbody');
                const rows = tbody.querySelectorAll('tr[id^="branch-row-"]');

                rows.forEach(row => {
                    const text = row.textContent || row.innerText;
                    if (text.toLowerCase().indexOf(filter) > -1) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            }
        </script>
@endsection
