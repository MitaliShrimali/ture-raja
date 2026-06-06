@extends('agent.layouts.app')

@section('title', 'Payment - Tour Raja Agent')

@section('content')




        <!-- Search Bar -->
        <div class="mb-10">
            <div class="relative w-full max-w-full">
                <input type="text" placeholder="Search/Download Payment" class="w-full pl-14 pr-20 py-5 bg-white rounded-2xl shadow-sm border border-gray-100 focus:outline-none focus:ring-2 focus:ring-primary/10 text-gray-700 text-sm font-medium">
                <div class="absolute left-6 top-1/2 -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
                <div class="absolute right-3 top-1/2 -translate-y-1/2">
                    <button class="w-12 h-12 bg-primary text-white rounded-xl flex items-center justify-center shadow-lg shadow-orange-100 hover:bg-orange-600 transition-all active:scale-95">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Payment Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <!-- Payment Card 1 -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden group hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-500">
                <div class="relative p-4">
                    <div class="relative overflow-hidden rounded-[2rem]">
                        <img src="{{ asset('agent/assets/images/') }}/payment_bg.png" class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-700" alt="Payment BG">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    <div class="absolute top-8 right-8 w-12 h-12 bg-primary text-white rounded-2xl flex items-center justify-center shadow-2xl cursor-pointer hover:bg-orange-600 transition-colors z-10">
                        <i class="fas fa-download"></i>
                    </div>
                </div>
                <div class="px-8 pb-8 pt-2">
                    <h4 class="text-lg font-bold text-gray-800 mb-2 leading-tight group-hover:text-primary transition-colors">Welcome Offer no_of_package 100 pr</h4>
                    <p class="text-xs text-gray-400 font-bold mb-8 uppercase tracking-widest">Location/Area</p>
                    <button class="w-full py-4 bg-green-500 text-white text-xs font-black rounded-2xl shadow-lg shadow-green-100 hover:bg-green-600 transition-all active:scale-[0.98] uppercase tracking-widest">Success</button>
                </div>
            </div>

            <!-- Payment Card 2 -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden group hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-500">
                <div class="relative p-4">
                    <div class="relative overflow-hidden rounded-[2rem]">
                        <img src="{{ asset('agent/assets/images/') }}/payment_bg.png" class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-700" alt="Payment BG">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </div>
                    <div class="absolute top-8 right-8 w-12 h-12 bg-primary text-white rounded-2xl flex items-center justify-center shadow-2xl cursor-pointer hover:bg-orange-600 transition-colors z-10">
                        <i class="fas fa-download"></i>
                    </div>
                </div>
                <div class="px-8 pb-8 pt-2">
                    <h4 class="text-lg font-bold text-gray-800 mb-2 leading-tight group-hover:text-primary transition-colors">3 Month Advertise</h4>
                    <p class="text-xs text-gray-400 font-bold mb-8 uppercase tracking-widest">Location/Area</p>
                    <button class="w-full py-4 bg-red-500 text-white text-xs font-black rounded-2xl shadow-lg shadow-red-100 hover:bg-red-600 transition-all active:scale-[0.98] uppercase tracking-widest">Failed</button>
                </div>
            </div>
        </div>

        <!-- Sticky Weather Card (Matching design floating style) -->
        <div class="hidden lg:block fixed bottom-8 left-8 z-40">
            <div class="bg-white p-5 rounded-[2.5rem] shadow-2xl border border-gray-50 w-48 hover:scale-105 transition-all duration-500 cursor-default">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-sun text-yellow-400 text-2xl mr-3"></i>
                        <h5 class="text-2xl font-black text-gray-800 tracking-tighter">30°</h5>
                    </div>
                    <button class="text-gray-300 hover:text-gray-500 transition-colors"><i class="fas fa-ellipsis-v text-xs"></i></button>
                </div>
                <p class="text-[10px] font-bold text-gray-400 flex items-center mb-6 tracking-widest uppercase">
                    <i class="fas fa-map-marker-alt mr-2 text-primary"></i> Rajkot
                </p>
                <div class="flex justify-between items-end border-t border-gray-50 pt-5">
                    <div class="text-center">
                        <i class="fas fa-cloud-sun text-gray-300 text-xs block mb-2"></i>
                        <span class="text-[9px] font-black text-gray-800 block">31°</span>
                        <span class="text-[7px] font-bold text-gray-300 uppercase mt-1 block">Mo</span>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-cloud-rain text-gray-300 text-xs block mb-2"></i>
                        <span class="text-[9px] font-black text-gray-800 block">29°</span>
                        <span class="text-[7px] font-bold text-gray-300 uppercase mt-1 block">Tu</span>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-sun text-gray-300 text-xs block mb-2"></i>
                        <span class="text-[9px] font-black text-gray-800 block">33°</span>
                        <span class="text-[7px] font-bold text-gray-300 uppercase mt-1 block">We</span>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-cloud text-gray-300 text-xs block mb-2"></i>
                        <span class="text-[9px] font-black text-gray-800 block">28°</span>
                        <span class="text-[7px] font-bold text-gray-300 uppercase mt-1 block">Th</span>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-sun text-gray-300 text-xs block mb-2"></i>
                        <span class="text-[9px] font-black text-gray-800 block">32°</span>
                        <span class="text-[7px] font-bold text-gray-300 uppercase mt-1 block">Fr</span>
                    </div>
                </div>
            </div>
        </div>
@endsection
