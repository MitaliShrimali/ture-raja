@extends('agent.layouts.app')

@section('title', 'Feedback - Tour Raja Agent')

@section('content')
<div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-xs text-gray-400 font-medium">Pages / Feedback</p>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Feedback</h2>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-100 flex items-center mb-12">
            <div class="flex-grow flex items-center px-4">
                <i class="fas fa-search text-gray-300 mr-3"></i>
                <input type="text" placeholder="Search/Edit Branch" class="w-full bg-transparent border-none outline-none text-sm text-gray-600 placeholder:text-gray-300">
            </div>
            <button class="bg-primary text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-lg shadow-orange-100">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-10">
            <h3 class="text-lg font-bold text-gray-800 tracking-tight">Customer Feedback</h3>
            <button class="bg-primary text-white px-6 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-orange-100 hover:scale-105 transition-all w-fit">
                + Add Feedback
            </button>
        </div>

        <!-- Feedback Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            <?php for($i=0; $i<12; $i++): ?>
            <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-gray-200/50 transition-all group">
                <div class="flex items-center mb-6">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=100&auto=format&fit=crop" class="w-14 h-14 rounded-2xl object-cover border-2 border-orange-50">
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-bold text-gray-800">Pinky Shah (Rajkot)</h4>
                        <div class="flex text-yellow-400 text-[8px] mt-1">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-[10px] text-gray-400 font-medium leading-relaxed mb-8">I recently booked my Dubai package through Miths Holidays, and I am extremely satisfied with their service. They offered me the best price and ensured a very smooth and enjoyable travel experience. Everything was well-organized, and their support team was very helpful throughout the trip. I highly recommend Miths Holidays for anyone planning a holiday. Thank you for the wonderful experience!</p>
                
                <div class="flex space-x-3">
                    <button class="bg-primary text-white px-4 py-1.5 rounded-lg text-[10px] font-bold flex items-center"><i class="fas fa-edit mr-2"></i> Edit</button>
                    <button class="bg-gray-100 text-gray-400 p-1.5 rounded-lg hover:text-red-500 transition-colors"><i class="far fa-trash-alt"></i></button>
                </div>
            </div>
            <?php endfor; ?>
        </div>

        <!-- Footer -->
@endsection
