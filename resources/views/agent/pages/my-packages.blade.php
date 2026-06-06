@extends('agent.layouts.app')

@section('title', 'My Packages - Tour Raja Agent')

@section('content')
<div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-xs text-gray-400 font-medium">Pages / My Packages</p>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">My Packages</h2>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-100 flex items-center mb-8">
            <div class="flex-grow flex items-center px-4">
                <i class="fas fa-search text-gray-300 mr-3"></i>
                <input type="text" placeholder="Search/Edit Hotel" class="w-full bg-transparent border-none outline-none text-sm text-gray-600 placeholder:text-gray-300">
            </div>
            <button class="bg-primary text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-lg shadow-orange-100">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <!-- Packages Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-6">
            <?php 
            $packages = array_fill(0, 9, [
                'name' => 'Japan',
                'status' => 'Approved',
                'type' => 'International • Flight Package • Japan',
                'price' => '1,00,000',
                'duration' => '4 Days / 3 Night',
                'expiry' => '27-03-2026'
            ]);
            
            foreach($packages as $pkg): ?>
            <div class="bg-white rounded-[32px] p-4 shadow-sm border border-gray-100 group hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-500">
                <div class="relative mb-4 overflow-hidden rounded-[24px]">
                    <img src="https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?q=80&w=500&auto=format&fit=crop" class="w-full h-40 object-cover group-hover:scale-110 transition-transform duration-700" alt="Japan">
                    <div class="absolute top-3 right-3 w-8 h-4 bg-primary rounded-full border-2 border-white flex items-center justify-center">
                        <div class="w-2 h-2 bg-white rounded-full"></div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-2 mb-1">
                    <h4 class="text-sm font-bold text-gray-800"><?php echo $pkg['name']; ?></h4>
                    <span class="bg-orange-50 text-primary text-[8px] font-bold px-2 py-0.5 rounded-full border border-orange-100 uppercase italic tracking-tighter">Approved</span>
                </div>
                <p class="text-[9px] text-gray-400 font-medium mb-3"><?php echo $pkg['type']; ?></p>
                
                <p class="text-[9px] text-gray-400 leading-relaxed line-clamp-3 mb-4">
                    Making it an ideal vacation for couples, families, and friends. With comfortable stays, smooth transfers, and carefully selected sightseeing...
                </p>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-[8px] font-bold text-gray-300 uppercase tracking-widest">Duration</p>
                        <p class="text-[10px] font-bold text-gray-800"><?php echo $pkg['duration']; ?></p>
                    </div>
                    <div>
                        <p class="text-[8px] font-bold text-gray-300 uppercase tracking-widest">Expiry Date</p>
                        <p class="text-[10px] font-bold text-gray-800"><?php echo $pkg['expiry']; ?></p>
                    </div>
                    <div>
                        <p class="text-[8px] font-bold text-gray-300 uppercase tracking-widest">Price</p>
                        <p class="text-[10px] font-bold text-gray-800">₹ <?php echo $pkg['price']; ?></p>
                    </div>
                    <div>
                        <p class="text-[8px] font-bold text-gray-300 uppercase tracking-widest">Holiday Type</p>
                        <p class="text-[10px] font-bold text-gray-800">Multi City</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <a href="edit-package.php" class="flex items-center justify-center space-x-1 py-2 border border-orange-100 rounded-xl text-primary text-[10px] font-bold hover:bg-orange-50 transition-colors">
                        <i class="fas fa-edit text-[8px]"></i> <span>Edit</span>
                    </a>
                    <button class="flex items-center justify-center space-x-1 py-2 border border-red-50 rounded-xl text-red-400 text-[10px] font-bold hover:bg-red-50 transition-colors">
                        <i class="fas fa-trash text-[8px]"></i> <span>Delete</span>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Create New Card -->
            <div class="bg-gray-100/50 rounded-[32px] border-2 border-dashed border-gray-200 flex flex-col items-center justify-center p-8 text-center hover:bg-gray-100 transition-colors cursor-pointer group">
                <div class="w-12 h-12 rounded-full border-2 border-gray-300 flex items-center justify-center text-gray-400 mb-4 group-hover:bg-white group-hover:border-primary group-hover:text-primary transition-all">
                    <i class="fas fa-plus"></i>
                </div>
                <p class="text-sm font-bold text-gray-400 group-hover:text-gray-800 transition-colors">Create a New Project</p>
            </div>
        </div>

        <!-- Footer -->
@endsection
