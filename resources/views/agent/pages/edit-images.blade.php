@extends('agent.layouts.app')

@section('title', 'Edit Images - Tour Raja Agent')

@section('content')
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Create New Package</h2>
                <p class="text-[10px] text-gray-400 font-medium">Upload photos to showcase your package.</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('agent.my-packages') }}" class="px-6 py-2 rounded-xl text-xs font-bold text-gray-500 bg-white border border-gray-100 flex items-center justify-center">Discard</a>
                <a href="{{ route('agent.my-packages') }}" class="px-6 py-2 rounded-xl text-xs font-bold text-white bg-[#e85d26] hover:bg-orange-600 transition-colors flex items-center justify-center shadow-lg shadow-orange-100">Save & Exit</a>
            </div>
        </div>

        <!-- Step Indicator -->
        <div class="relative flex items-center justify-between max-w-4xl mx-auto mb-12">
            <div class="flex flex-col items-center z-10">
                <div class="w-10 h-10 bg-orange-50 text-[#e85d26] border-2 border-[#e85d26] rounded-full flex items-center justify-center text-xs font-bold shadow-xl shadow-orange-100"><i class="fas fa-check"></i></div>
                <p class="text-[9px] font-bold text-gray-400 mt-2 uppercase tracking-widest">Details</p>
            </div>
            <div class="absolute left-0 top-5 w-full h-[1px] bg-orange-200 -z-0"></div>
            <div class="flex flex-col items-center z-10">
                <div class="w-10 h-10 bg-orange-50 text-[#e85d26] border-2 border-[#e85d26] rounded-full flex items-center justify-center text-xs font-bold shadow-xl shadow-orange-100"><i class="fas fa-check"></i></div>
                <p class="text-[9px] font-bold text-gray-400 mt-2 uppercase tracking-widest">Pricing</p>
            </div>
            <div class="flex flex-col items-center z-10">
                <div class="w-10 h-10 bg-[#e85d26] text-white rounded-full flex items-center justify-center text-xs font-bold shadow-xl shadow-orange-100">3</div>
                <p class="text-[9px] font-bold text-[#e85d26] mt-2 uppercase tracking-widest">Images</p>
            </div>
        </div>

        <div class="space-y-12">
            <!-- Visual Showcase Intro -->
            <div>
                <h3 class="text-3xl font-bold text-gray-800 mb-2">Visual Showcase</h3>
                <p class="text-[10px] text-gray-400 font-medium leading-relaxed max-w-lg">Curate the visual identity of your travel package. High-resolution imagery increases booking conversion by 40%.</p>
            </div>

            <!-- Package Cover Image -->
            <div>
                <h4 class="text-xs font-bold text-gray-800 mb-6">Package Cover Image</h4>
                <div class="w-full border-2 border-dashed border-orange-200 rounded-[48px] p-24 bg-orange-50/20 flex flex-col items-center justify-center text-center group cursor-pointer hover:bg-orange-50 transition-all">
                    <div class="w-12 h-12 bg-white rounded-full shadow-lg flex items-center justify-center text-primary mb-4 group-hover:scale-110 transition-transform"><i class="fas fa-cloud-upload-alt text-lg"></i></div>
                    <p class="text-sm font-bold text-gray-800 mb-1">Drop files here or click here</p>
                    <p class="text-[9px] text-gray-400 font-medium">Recommended: 1920x1080px (Max 2MB)</p>
                </div>
            </div>

            <!-- Gallery Portfolio -->
            <div>
                <div class="flex items-center justify-between mb-8">
                    <h4 class="text-xs font-bold text-gray-800">Gallery Portfolio</h4>
                    <button class="text-primary text-[10px] font-bold uppercase tracking-widest flex items-center hover:underline"><i class="fas fa-plus mr-2"></i> Add more</button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Item 1 -->
                    <div class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-gray-100 group">
                        <div class="relative overflow-hidden h-48">
                            <img src="https://images.unsplash.com/photo-1540206395-6880f949034a?q=80&w=500&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-4 border-t border-gray-50">
                            <p class="text-[10px] font-bold text-gray-800">Traditional_Village.jpg</p>
                            <p class="text-[8px] text-gray-400 font-medium uppercase mt-1">2.4 MB • Primary Cover</p>
                        </div>
                    </div>
                    <!-- Item 2 -->
                    <div class="bg-white rounded-[32px] overflow-hidden shadow-sm border border-gray-100 group">
                        <div class="relative overflow-hidden h-48">
                            <img src="https://images.unsplash.com/photo-1540202404-a2f29016bb5d?q=80&w=500&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-4 border-t border-gray-50">
                            <p class="text-[10px] font-bold text-gray-800">Sunset_Lounge.jpg</p>
                            <p class="text-[8px] text-gray-400 font-medium uppercase mt-1">1.8 MB</p>
                        </div>
                    </div>
                    <!-- Empty Add Item -->
                    <div class="bg-gray-100/30 rounded-[32px] border-2 border-dashed border-gray-100 flex flex-col items-center justify-center text-center p-12 group cursor-pointer hover:bg-gray-100/50 transition-all">
                        <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-gray-300 group-hover:text-primary transition-colors mb-4"><i class="far fa-image"></i></div>
                        <p class="text-[10px] font-bold text-gray-300 group-hover:text-gray-500 transition-colors uppercase tracking-widest">Add Item</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-20 flex items-center justify-between pb-8">
            <a href="{{ route('agent.edit-itinerary') }}" class="px-10 py-3 rounded-xl text-xs font-bold text-gray-500 bg-white border border-gray-100 flex items-center hover:bg-gray-50 transition-colors"><i class="fas fa-arrow-left mr-3"></i> Previous</a>
            <div class="flex space-x-3 items-center">
                <a href="{{ route('agent.my-packages') }}" class="text-xs font-bold text-gray-400 hover:text-gray-800 transition-colors mr-6 uppercase tracking-widest">Discard</a>
                <a href="{{ route('agent.my-packages') }}" class="px-10 py-3 rounded-xl text-xs font-bold text-white bg-[#e85d26] shadow-xl shadow-orange-100 hover:scale-[1.02] transition-all uppercase flex items-center justify-center">Save And Exit</a>
            </div>
        </div>
    
@endsection
