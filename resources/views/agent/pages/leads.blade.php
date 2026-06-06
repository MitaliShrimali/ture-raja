@extends('agent.layouts.app')

@section('title', 'Leads - Tour Raja Agent')

@section('content')
<div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-xs text-gray-400 font-medium">Pages / Leads</p>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Leads</h2>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-100 flex items-center mb-8">
            <div class="flex-grow flex items-center px-4">
                <i class="fas fa-search text-gray-300 mr-3"></i>
                <input type="text" placeholder="Search/Filter Leads" class="w-full bg-transparent border-none outline-none text-sm text-gray-600 placeholder:text-gray-300">
            </div>
            <button class="bg-primary text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-lg shadow-orange-100">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <!-- Leads Table -->
        <div class="bg-white rounded-[32px] p-4 sm:p-8 shadow-sm border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Contact Us</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[9px] font-bold text-gray-300 uppercase tracking-widest border-b border-gray-50 whitespace-nowrap">
                            <th class="pb-4 pl-4">Srl No.</th>
                            <th class="pb-4">Names</th>
                            <th class="pb-4">Status</th>
                            <th class="pb-4">Email</th>
                            <th class="pb-4">Phone</th>
                            <th class="pb-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php 
                        $leads = [
                            ['id' => 1, 'srl' => '103', 'name' => 'John Doe', 'loc' => 'Rajkot, Gujarat', 'status' => 'Convert', 'color' => 'bg-green-500', 'email' => 'john@gmail.com', 'phone' => '+91 98765 43210'],
                            ['id' => 2, 'srl' => '104', 'name' => 'Sarah Smith', 'loc' => 'Morbi, Rajkot', 'status' => 'No use', 'color' => 'bg-red-500', 'email' => 'sarah@gmail.com', 'phone' => '+91 88888 88888'],
                            ['id' => 3, 'srl' => '105', 'name' => 'Michael Brown', 'loc' => 'Ahmedabad, Gujarat', 'status' => 'Pending', 'color' => 'bg-yellow-400', 'email' => 'michael@gmail.com', 'phone' => '+91 77777 77777'],
                            ['id' => 4, 'srl' => '106', 'name' => 'Emma Wilson', 'loc' => 'Surat, Gujarat', 'status' => 'Working', 'color' => 'bg-blue-400', 'email' => 'emma@gmail.com', 'phone' => '+91 66666 66666'],
                        ];
                        foreach($leads as $l): ?>
                        <tr class="group hover:bg-gray-50/50 transition-colors whitespace-nowrap" id="lead-row-<?php echo $l['id']; ?>">
                            <td class="py-4 pl-4 text-xs font-bold text-gray-800"><?php echo $l['srl']; ?></td>
                            <td class="py-4">
                                <div class="flex items-center">
                                    <img src="https://i.pravatar.cc/100?u=<?php echo $l['id']; ?>" class="w-10 h-10 rounded-xl object-cover mr-3 border border-gray-100">
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-800 lead-name"><?php echo $l['name']; ?></p>
                                        <p class="text-[8px] text-gray-400 font-medium lead-loc"><?php echo $l['loc']; ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4">
                                <span class="<?php echo $l['color']; ?> text-white px-3 py-1 rounded-lg text-[8px] font-bold lead-status">
                                    <?php echo $l['status']; ?>
                                </span>
                            </td>
                            <td class="py-4 text-[10px] font-bold text-gray-800 lead-email"><?php echo $l['email']; ?></td>
                            <td class="py-4 text-[10px] font-bold text-gray-800 lead-phone"><?php echo $l['phone']; ?></td>
                            <td class="py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <button onclick="editLead(<?php echo htmlspecialchars(json_encode($l)); ?>)" class="text-[9px] font-bold text-gray-400 hover:text-gray-800 transition-colors">Edit</button>
                                    <button onclick="deleteLead(<?php echo $l['id']; ?>)" class="text-[9px] font-bold text-gray-400 hover:text-red-500 transition-colors">Delete</button>
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
            <p class="text-xs text-gray-400 font-medium mb-4 lg:mb-0">Copyright © 2026 Tour Raja Private Limited, India. All rights reserved.</p>
            <div class="flex space-x-6 text-xs text-gray-400 font-medium">
                <a href="#" class="hover:text-primary">About Us</a>
                <a href="#" class="hover:text-primary">License</a>
                <a href="#" class="hover:text-primary">Terms of Services</a>
                <a href="#" class="hover:text-primary">Privacy Policy</a>
            </div>
        </footer>
    

<!-- Edit Lead Modal -->
<div id="leadModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-gray-900/20 backdrop-blur-sm" onclick="toggleLeadModal()"></div>
    <div class="bg-white w-full max-w-md rounded-[32px] p-8 shadow-2xl relative z-10 scale-95 opacity-0 transition-all duration-300" id="leadModalContainer">
        <button onclick="toggleLeadModal()" class="absolute top-6 right-8 text-gray-400 hover:text-gray-800 transition-colors">
            <i class="fas fa-times"></i>
        </button>
        <h3 class="text-2xl font-bold text-gray-800 mb-0.5">Edit Lead</h3>
        <p class="text-[10px] text-gray-400 font-medium mb-6">Update contact information for this lead.</p>
        <form class="space-y-4" onsubmit="handleLeadSubmit(event)">
            <input type="hidden" id="editLeadId">
            <div>
                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Name</label>
                <input type="text" id="editLeadName" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium">
            </div>
            <div>
                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Email</label>
                <input type="email" id="editLeadEmail" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium">
            </div>
            <div>
                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Phone</label>
                <input type="text" id="editLeadPhone" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-none focus:ring-1 focus:ring-primary/20 text-xs font-medium">
            </div>
            <div class="flex items-center justify-end space-x-6 pt-2">
                <button type="button" onclick="toggleLeadModal()" class="text-xs font-bold text-gray-800 hover:text-gray-400 transition-colors">Cancel</button>
                <button type="submit" class="bg-primary text-white px-6 py-3 rounded-2xl text-xs font-bold flex items-center shadow-lg shadow-orange-100 hover:scale-[1.02] active:scale-95 transition-all">
                    <i class="fas fa-save mr-2"></i> Update Lead
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Lead Modal -->
<div id="viewLeadModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-gray-900/20 backdrop-blur-sm" onclick="toggleViewLeadModal()"></div>
    <div class="bg-white w-full max-w-md rounded-[32px] p-8 shadow-2xl relative z-10 scale-95 opacity-0 transition-all duration-300" id="viewLeadModalContainer">
        <button onclick="toggleViewLeadModal()" class="absolute top-6 right-8 text-gray-400 hover:text-gray-800 transition-colors">
            <i class="fas fa-times"></i>
        </button>
        <h3 class="text-2xl font-bold text-gray-800 mb-0.5">Lead Details</h3>
        <p class="text-[10px] text-gray-400 font-medium mb-8">Comprehensive information for the selected lead.</p>
        
        <div class="space-y-6">
            <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-2xl">
                <img id="viewLeadImg" src="" class="w-16 h-16 rounded-2xl object-cover border-2 border-white shadow-sm">
                <div>
                    <h4 id="viewLeadName" class="text-lg font-bold text-gray-800"></h4>
                    <p id="viewLeadLoc" class="text-xs text-gray-400"></p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 gap-4">
                <div class="p-4 border border-gray-100 rounded-2xl">
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1 tracking-widest">Email Address</label>
                    <p id="viewLeadEmail" class="text-xs font-bold text-gray-800"></p>
                </div>
                <div class="p-4 border border-gray-100 rounded-2xl">
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1 tracking-widest">Phone Number</label>
                    <p id="viewLeadPhone" class="text-xs font-bold text-gray-800"></p>
                </div>
                <div class="p-4 border border-gray-100 rounded-2xl">
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1 tracking-widest">Current Status</label>
                    <div id="viewLeadStatus" class="mt-1"></div>
                </div>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-50">
            <button onclick="toggleViewLeadModal()" class="w-full py-4 bg-gray-800 text-white text-xs font-black rounded-2xl shadow-lg hover:bg-black transition-all uppercase tracking-widest">Close Details</button>
        </div>
    </div>
</div>

<script>
function toggleViewLeadModal() {
    const modal = document.getElementById('viewLeadModal');
    const container = document.getElementById('viewLeadModalContainer');
    if (modal.classList.contains('hidden')) {
        modal.classList.remove('hidden');
        setTimeout(() => {
            container.classList.remove('scale-95', 'opacity-0');
            container.classList.add('scale-100', 'opacity-100');
        }, 10);
    } else {
        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
}

function viewLead(lead) {
    document.getElementById('viewLeadImg').src = `https://i.pravatar.cc/100?u=${lead.id}`;
    document.getElementById('viewLeadName').innerText = lead.name;
    document.getElementById('viewLeadLoc').innerText = lead.loc;
    document.getElementById('viewLeadEmail').innerText = lead.email;
    document.getElementById('viewLeadPhone').innerText = lead.phone;
    
    const statusDiv = document.getElementById('viewLeadStatus');
    statusDiv.innerHTML = `<span class="${lead.color} text-white px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm">${lead.status}</span>`;
    
    toggleViewLeadModal();
}

function markAsPending(id) {
    const row = document.getElementById('lead-row-' + id);
    const statusBadge = row.querySelector('.lead-status');
    
    statusBadge.innerText = 'Pending';
    statusBadge.className = 'bg-yellow-400 text-white px-3 py-1 rounded-lg text-[8px] font-bold lead-status';
    
    toastr.warning('Lead marked as pending');
}

function toggleLeadModal() {
    const modal = document.getElementById('leadModal');
    const container = document.getElementById('leadModalContainer');
    if (modal.classList.contains('hidden')) {
        modal.classList.remove('hidden');
        setTimeout(() => {
            container.classList.remove('scale-95', 'opacity-0');
            container.classList.add('scale-100', 'opacity-100');
        }, 10);
    } else {
        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
}

function editLead(lead) {
    document.getElementById('editLeadId').value = lead.id;
    document.getElementById('editLeadName').value = lead.name;
    document.getElementById('editLeadEmail').value = lead.email;
    document.getElementById('editLeadPhone').value = lead.phone;
    toggleLeadModal();
}

function handleLeadSubmit(e) {
    e.preventDefault();
    const id = document.getElementById('editLeadId').value;
    const name = document.getElementById('editLeadName').value;
    const email = document.getElementById('editLeadEmail').value;
    const phone = document.getElementById('editLeadPhone').value;
    
    const row = document.getElementById('lead-row-' + id);
    row.querySelector('.lead-name').innerText = name;
    row.querySelector('.lead-email').innerText = email;
    row.querySelector('.lead-phone').innerText = phone;
    
    toastr.success('Lead updated successfully');
    toggleLeadModal();
}

function deleteLead(id) {
    Swal.fire({
        title: 'Delete Lead?',
        text: "You won't be able to recover this lead information!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#F0642F',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        borderRadius: '2rem'
    }).then((result) => {
        if (result.isConfirmed) {
            const row = document.getElementById('lead-row-' + id);
            row.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                row.remove();
                Swal.fire({
                    title: 'Deleted!',
                    text: 'Lead has been removed.',
                    icon: 'success',
                    confirmButtonColor: '#F0642F',
                    borderRadius: '2rem'
                });
            }, 300);
        }
    });
}
</script>
@endsection
