@extends('agent.layouts.app')

@section('title', 'Branch - Tour Raja Agent')

@section('content')


        <!-- Search Bar -->
        <div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-100 flex items-center mb-8">
            <div class="flex-grow flex items-center px-4">
                <i class="fas fa-search text-gray-300 mr-3"></i>
                <input type="text" placeholder="Search/Edit Branch" class="w-full bg-transparent border-none outline-none text-sm text-gray-600 placeholder:text-gray-300">
            </div>
            <button class="bg-primary text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-lg shadow-orange-100">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <!-- Branch Table Container -->
        <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Branch</h3>
                <a href="add-branch.php" class="bg-primary text-white px-6 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-orange-100 hover:scale-105 transition-all w-fit">
                    + Add Branch
                </a>
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
                        <?php 
                        $branches = [
                            ['srl' => '103', 'name' => 'Miths Holidays', 'loc' => 'Amreli', 'status' => 'Online', 'state' => 'GUJARAT'],
                            ['srl' => '103', 'name' => 'Rahi Coral Beach Resort', 'loc' => 'Rajkot', 'status' => 'Offline', 'state' => 'GUJARAT'],
                        ];
                        foreach($branches as $b): ?>
                        <tr class="group hover:bg-gray-50/50 transition-colors whitespace-nowrap">
                            <td class="py-4 pl-4 text-xs font-bold text-gray-800"><?php echo $b['srl']; ?></td>
                            <td class="py-4">
                                <div class="flex items-center">
                                    <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=100&auto=format&fit=crop" class="w-10 h-10 rounded-xl object-cover mr-3 border border-gray-100">
                                    <div>
                                        <p class="text-[10px] font-bold text-gray-800"><?php echo $b['name']; ?></p>
                                        <p class="text-[8px] text-gray-400 font-medium">Rajkot, Gujarat</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4">
                                <span class="px-3 py-1 rounded-lg text-[8px] font-bold uppercase tracking-tighter <?php echo $b['status'] == 'Online' ? 'bg-green-500 text-white' : 'bg-gray-300 text-white'; ?>">
                                    <?php echo $b['status']; ?>
                                </span>
                            </td>
                            <td class="py-4 text-[10px] font-bold text-gray-800"><?php echo $b['loc']; ?></td>
                            <td class="py-4 text-[10px] font-bold text-gray-800"><?php echo $b['state']; ?></td>
                            <td class="py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <button class="text-[9px] font-bold text-gray-400 hover:text-gray-800 transition-colors">Edit</button>
                                    <button class="text-[9px] font-bold text-gray-400 hover:text-red-500 transition-colors">Delete</button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
@endsection
