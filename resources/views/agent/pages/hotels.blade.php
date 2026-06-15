@extends('agent.layouts.app')

@section('title', 'Hotels - Tour Raja Agent')

@section('content')


        <!-- Search Bar -->
        <div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-100 flex items-center mb-8">
            <div class="flex-grow flex items-center px-4">
                <i class="fas fa-search text-gray-300 mr-3"></i>
                <input type="text" placeholder="Search/Edit Hotel"
                    class="w-full bg-transparent border-none outline-none text-sm text-gray-600 placeholder:text-gray-300">
            </div>
            <button
                class="bg-primary text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-lg shadow-orange-100">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <!-- Hotel Table Container -->
        <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Hotel</h3>
                <a href="javascript:void(0)" onclick="toggleHotelModal()"
                    class="bg-primary text-white px-6 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-orange-100 hover:scale-105 transition-all w-fit">
                    + Add Hotel
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr
                            class="text-[9px] font-bold text-gray-300 uppercase tracking-widest border-b border-gray-50 whitespace-nowrap">
                            <th class="pb-4 pl-4">Srl No.</th>
                            <th class="pb-4">Hotel Names</th>
                            <th class="pb-4">Status</th>
                            <th class="pb-4">Address</th>
                            <th class="pb-4">Category</th>
                            <th class="pb-4">State</th>
                            <th class="pb-4">Country</th>
                            <th class="pb-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50" id="hotelTableBody">
                        <?php
                        $hotels = [
                            ['id' => 1, 'srl' => '103', 'name' => 'Rahi Coral Beach Resort', 'loc' => 'GOA', 'status' => 'Online', 'cat' => 'Deluxe', 'state' => 'Goa', 'country' => 'India', 'address' => 'GOA'],
                            ['id' => 2, 'srl' => '104', 'name' => 'Grand Hyatt', 'loc' => 'Mumbai', 'status' => 'Offline', 'cat' => 'Luxury', 'state' => 'Maharashtra', 'country' => 'India', 'address' => 'Mumbai'],
                            ['id' => 3, 'srl' => '105', 'name' => 'The Taj Mahal Palace', 'loc' => 'Mumbai', 'status' => 'Online', 'cat' => 'Luxury', 'state' => 'Maharashtra', 'country' => 'India', 'address' => 'Mumbai'],
                            ['id' => 4, 'srl' => '106', 'name' => 'Oberoi Amarvilas', 'loc' => 'Agra', 'status' => 'Online', 'cat' => 'Luxury', 'state' => 'UP', 'country' => 'India', 'address' => 'Agra'],
                        ];
                        foreach ($hotels as $h): ?>
                            <tr class="group hover:bg-gray-50/50 transition-colors whitespace-nowrap" id="hotel-row-<?php echo $h['id']; ?>">
                                <td class="py-4 pl-4 text-xs font-bold text-gray-800"><?php echo $h['srl']; ?></td>
                                <td class="py-4">
                                    <div class="flex items-center">
                                        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=50&auto=format&fit=crop"
                                            class="w-10 h-10 rounded-xl object-cover mr-3 border border-gray-100 shadow-sm">
                                        <div>
                                            <p class="text-[10px] font-bold text-gray-800 hotel-name">
                                                <?php echo $h['name']; ?></p>
                                            <p class="text-[8px] text-gray-400 font-medium hotel-loc">
                                                <?php echo $h['loc']; ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-[8px] font-bold uppercase tracking-tighter hotel-status <?php echo $h['status'] == 'Online' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400'; ?>">
                                        <?php echo $h['status']; ?>
                                    </span>
                                </td>
                                <td class="py-4 text-[10px] font-bold text-gray-800 hotel-address"><?php echo isset($h['address']) ? $h['address'] : $h['loc']; ?></td>
                                <td class="py-4 text-[10px] font-bold text-gray-800 hotel-cat"><?php echo $h['cat']; ?></td>
                                <td class="py-4 text-[10px] font-bold text-gray-800 hotel-state"><?php echo $h['state']; ?></td>
                                <td class="py-4 text-[10px] font-bold text-gray-800 hotel-country"><?php echo $h['country']; ?></td>
                                <td class="py-4 text-center">
                                    <div class="flex items-center justify-center space-x-3">
                                        <button onclick="editHotel(<?php echo htmlspecialchars(json_encode($h)); ?>)"
                                            class="text-[9px] font-bold text-gray-400 hover:text-gray-800 transition-colors">Edit</button>
                                        <button onclick="deleteHotel(<?php echo $h['id']; ?>)"
                                            class="text-[9px] font-bold text-gray-400 hover:text-red-500 transition-colors">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-12 flex flex-col lg:flex-row items-center justify-between py-6 border-t border-gray-100">
            <p class="text-xs text-gray-400 font-medium mb-4 lg:mb-0">Copyright © 2026 Tour Raja Private Limited, India.
                All rights reserved.</p>
            <div class="flex space-x-6 text-xs text-gray-400 font-medium">
                <a href="#" class="hover:text-primary">About Us</a>
                <a href="#" class="hover:text-primary">License</a>
                <a href="#" class="hover:text-primary">Terms of Services</a>
                <a href="#" class="hover:text-primary">Privacy Policy</a>
            </div>
        </footer>
    

<!-- Add/Edit Hotel Modal -->
<div id="addHotelModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-gray-900/20 backdrop-blur-sm" onclick="toggleHotelModal()"></div>

    <!-- Modal Content -->
    <div class="bg-white w-full max-w-md rounded-[32px] p-8 shadow-2xl relative z-10 scale-95 opacity-0 transition-all duration-300"
        id="modalContainer">
        <button onclick="toggleHotelModal()"
            class="absolute top-6 right-8 text-gray-400 hover:text-gray-800 transition-colors">
            <i class="fas fa-times"></i>
        </button>

        <h3 class="text-2xl font-bold text-gray-800 mb-0.5" id="modalTitle">Add Hotel</h3>
        <p class="text-[10px] text-gray-400 font-medium mb-6">Include a new stay in the traveler's itinerary.</p>

        <form class="space-y-4" onsubmit="handleHotelSubmit(event)">
            <input type="hidden" id="hotelId">
            <div>
                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Hotel
                    Name</label>
                <input type="text" id="hotelName" placeholder="e.g. Alila Villas Uluwatu"
                    class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium placeholder:text-gray-300">
            </div>

            <div>
                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">City</label>
                <div class="relative">
                    <i class="fas fa-map-marker-alt absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" id="hotelCity" placeholder="Search City"
                        class="w-full pl-12 pr-5 py-3 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium placeholder:text-gray-300">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Status</label>
                    <div class="relative">
                        <select id="hotelStatus" required
                            class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium appearance-none">
                            <option value="Online">Online</option>
                            <option value="Offline">Offline</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Address</label>
                    <div class="relative">
                        <select id="hotelAddress" required
                            class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium appearance-none">
                            <option value="GOA">GOA</option>
                            <option value="Mumbai">Mumbai</option>
                            <option value="Agra">Agra</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Uluwatu">Uluwatu</option>
                            <option value="Seminyak">Seminyak</option>
                            <option value="Ubud">Ubud</option>
                            <option value="Marina Bay">Marina Bay</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Category</label>
                    <div class="relative">
                        <select id="hotelCategory" required
                            class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium appearance-none">
                            <option value="Standard">Standard</option>
                            <option value="Deluxe">Deluxe</option>
                            <option value="Luxury">Luxury</option>
                            <option value="Budget">Budget</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">State</label>
                    <div class="relative">
                        <select id="hotelState" required
                            class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium appearance-none">
                            <option value="Goa">Goa</option>
                            <option value="Maharashtra">Maharashtra</option>
                            <option value="UP">UP</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Bali">Bali</option>
                            <option value="Singapore">Singapore</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Country</label>
                    <div class="relative">
                        <select id="hotelCountry" required
                            class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium appearance-none">
                            <option value="India">India</option>
                            <option value="Indonesia">Indonesia</option>
                            <option value="Singapore">Singapore</option>
                            <option value="Thailand">Thailand</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] pointer-events-none"></i>
                    </div>
                </div>
            </div>

            <!-- Live Preview Area -->
            <div class="relative rounded-[24px] overflow-hidden h-44 bg-gray-200">
                <img src="https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?q=80&w=600&auto=format&fit=crop"
                    class="w-full h-full object-cover brightness-50">
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center p-6">
                    <p class="text-[8px] font-bold text-white uppercase tracking-[3px] mb-1 opacity-80">Live Preview</p>
                    <h4 class="text-white font-bold text-xs leading-relaxed max-w-[180px]">Imagery will be automatically
                        fetched based on name.</h4>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-6 pt-2">
                <button type="button" onclick="toggleHotelModal()"
                    class="text-xs font-bold text-gray-800 hover:text-gray-400 transition-colors">Cancel</button>
                <button type="submit"
                    class="bg-primary text-white px-6 py-3 rounded-2xl text-xs font-bold flex items-center shadow-lg shadow-orange-100 hover:scale-[1.02] active:scale-95 transition-all">
                    <i class="fas fa-save mr-2"></i> <span id="submitBtnText">Save Hotel</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleHotelModal(mode = 'add') {
        const modal = document.getElementById('addHotelModal');
        const container = document.getElementById('modalContainer');
        const title = document.getElementById('modalTitle');
        const submitBtnText = document.getElementById('submitBtnText');

        if (modal.classList.contains('hidden')) {
            if (mode === 'add') {
                title.innerText = 'Add Hotel';
                submitBtnText.innerText = 'Save Hotel';
                document.getElementById('hotelId').value = '';
                document.getElementById('hotelName').value = '';
                document.getElementById('hotelCity').value = '';
                document.getElementById('hotelCategory').value = 'Deluxe';
                document.getElementById('hotelStatus').value = 'Online';
                document.getElementById('hotelAddress').value = 'GOA';
                document.getElementById('hotelState').value = 'Goa';
                document.getElementById('hotelCountry').value = 'India';
            }
            modal.classList.remove('hidden');
            setTimeout(() => {
                container.classList.remove('scale-95', 'opacity-0');
                container.classList.add('scale-100', 'opacity-100');
            }, 10);
        } else {
            container.classList.remove('scale-100', 'opacity-100');
            container.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    }

    function editHotel(hotel) {
        const title = document.getElementById('modalTitle');
        const submitBtnText = document.getElementById('submitBtnText');

        title.innerText = 'Edit Hotel';
        submitBtnText.innerText = 'Update Hotel';

        document.getElementById('hotelId').value = hotel.id;
        document.getElementById('hotelName').value = hotel.name;
        document.getElementById('hotelCity').value = hotel.loc;
        document.getElementById('hotelCategory').value = hotel.cat;
        document.getElementById('hotelStatus').value = hotel.status;
        document.getElementById('hotelAddress').value = hotel.address || 'GOA';
        document.getElementById('hotelState').value = hotel.state || 'Goa';
        document.getElementById('hotelCountry').value = hotel.country || 'India';

        toggleHotelModal('edit');
    }

    function deleteHotel(id) {
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
                const row = document.getElementById('hotel-row-' + id);
                row.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    row.remove();
                    Swal.fire({
                        title: 'Deleted!',
                        text: 'Hotel has been deleted.',
                        icon: 'success',
                        confirmButtonColor: '#F0642F',
                        borderRadius: '2rem'
                    });
                }, 300);
            }
        });
    }

    function handleHotelSubmit(e) {
        e.preventDefault();
        const id = document.getElementById('hotelId').value;
        const name = document.getElementById('hotelName').value;
        const city = document.getElementById('hotelCity').value;
        const category = document.getElementById('hotelCategory').value;
        const status = document.getElementById('hotelStatus').value;
        const address = document.getElementById('hotelAddress').value;
        const state = document.getElementById('hotelState').value;
        const country = document.getElementById('hotelCountry').value;

        if (id) {
            // Mock Update
            const row = document.getElementById('hotel-row-' + id);
            row.querySelector('.hotel-name').innerText = name;
            row.querySelector('.hotel-loc').innerText = city;
            row.querySelector('.hotel-cat').innerText = category;
            
            const statusBadge = row.querySelector('.hotel-status');
            statusBadge.innerText = status;
            statusBadge.className = `px-3 py-1 rounded-full text-[8px] font-bold uppercase tracking-tighter hotel-status ${status == 'Online' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400'}`;
            
            row.querySelector('.hotel-address').innerText = address;
            row.querySelector('.hotel-state').innerText = state;
            row.querySelector('.hotel-country').innerText = country;

            // Update parameters in edit function call
            const editBtn = row.querySelector('button[onclick^="editHotel"]');
            const hotelData = { id: parseInt(id), srl: row.cells[0].innerText, name, loc: city, status, cat: category, address, state, country };
            editBtn.setAttribute('onclick', `editHotel(${JSON.stringify(hotelData)})`);

            toastr.success('Hotel updated successfully');
        } else {
            // Mock Add
            const tbody = document.getElementById('hotelTableBody');
            const newId = Date.now();
            const srl = '10' + (tbody.children.length + 3);
            const hotelData = { id: newId, srl, name, loc: city, status, cat: category, address, state, country };
            
            const newRowHtml = `
                <tr class="group hover:bg-gray-50/50 transition-colors whitespace-nowrap" id="hotel-row-${newId}">
                    <td class="py-4 pl-4 text-xs font-bold text-gray-800">${srl}</td>
                    <td class="py-4">
                        <div class="flex items-center">
                            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=50&auto=format&fit=crop"
                                class="w-10 h-10 rounded-xl object-cover mr-3 border border-gray-100 shadow-sm">
                            <div>
                                <p class="text-[10px] font-bold text-gray-800 hotel-name">${name}</p>
                                <p class="text-[8px] text-gray-400 font-medium hotel-loc">${city}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4">
                        <span class="px-3 py-1 rounded-full text-[8px] font-bold uppercase tracking-tighter hotel-status ${status == 'Online' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400'}">
                            ${status}
                        </span>
                    </td>
                    <td class="py-4 text-[10px] font-bold text-gray-800 hotel-address">${address}</td>
                    <td class="py-4 text-[10px] font-bold text-gray-800 hotel-cat">${category}</td>
                    <td class="py-4 text-[10px] font-bold text-gray-800 hotel-state">${state}</td>
                    <td class="py-4 text-[10px] font-bold text-gray-800 hotel-country">${country}</td>
                    <td class="py-4 text-center">
                        <div class="flex items-center justify-center space-x-3">
                            <button onclick='editHotel(${JSON.stringify(hotelData)})'
                                class="text-[9px] font-bold text-gray-400 hover:text-gray-800 transition-colors">Edit</button>
                            <button onclick="deleteHotel(${newId})"
                                class="text-[9px] font-bold text-gray-400 hover:text-red-500 transition-colors">Delete</button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', newRowHtml);
            toastr.success('Hotel added successfully');
        }

        toggleHotelModal();
    }
</script>
@endsection
