@extends('agent.layouts.app')

@section('title', 'Edit Itinerary - Tour Raja Agent')

@section('content')
<div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-xs text-gray-400 font-medium">Pages / My Packages / Edit Package</p>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Edit Package</h2>
                <p class="text-[10px] text-gray-400 font-medium">Update your package details to attract more travelers.</p>
            </div>
            <div class="flex space-x-3">
                <button class="px-6 py-2 rounded-xl text-xs font-bold text-gray-500 bg-white border border-gray-100">Discard</button>
                <a href="edit-images.php" class="px-6 py-2 rounded-xl text-xs font-bold text-white bg-orange-800 hover:bg-orange-900 transition-colors">Save & Next</a>
            </div>
        </div>

        <!-- Step Indicator -->
        <div class="relative flex items-center justify-between max-w-4xl mx-auto mb-16">
            <!-- Line Background -->
            <div class="absolute left-0 top-5 w-full h-[1px] bg-gray-100 -z-0"></div>
            
            <!-- Step 1 -->
            <div class="flex flex-col items-center z-10">
                <div class="w-10 h-10 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center text-xs font-bold">1</div>
                <p class="text-[9px] font-bold text-gray-400 mt-3 uppercase tracking-widest">Identity & Logistics</p>
            </div>
            
            <!-- Step 2 (Active) -->
            <div class="flex flex-col items-center z-10">
                <div class="w-10 h-10 bg-orange-100 text-primary rounded-full flex items-center justify-center text-xs font-bold shadow-lg shadow-orange-50">2</div>
                <p class="text-[9px] font-bold text-primary mt-3 uppercase tracking-widest">Itinerary & Meals</p>
            </div>
            
            <!-- Step 3 -->
            <div class="flex flex-col items-center z-10">
                <div class="w-10 h-10 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center text-xs font-bold">3</div>
                <p class="text-[9px] font-bold text-gray-400 mt-3 uppercase tracking-widest">Inclusions & Final</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side: Journey Builder -->
            <div class="lg:col-span-8 space-y-8">
                <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Build Your Journey</h3>
                    <p class="text-[10px] text-gray-400 font-medium mb-8">Craft a detailed day-by-day experience for your travelers. Add accommodations, transportation, and curated activities.</p>

                    <!-- Day 01 Block -->
                    <div class="bg-gray-50/50 rounded-3xl p-6 border border-gray-100 relative">
                        <button class="absolute top-6 right-6 text-red-500 text-[10px] font-bold uppercase tracking-widest flex items-center"><i class="fas fa-trash-alt mr-2"></i> Remove</button>
                        
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-8 h-8 bg-orange-50 text-primary rounded-xl flex items-center justify-center"><i class="far fa-calendar-alt text-xs"></i></div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-800">Day 01</h4>
                                <p class="text-[8px] text-gray-400 font-medium">Initial arrival and orientation</p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2">Arrival City</label>
                                <input type="text" placeholder="e.g. New Delhi, India" class="w-full px-4 py-3 rounded-xl bg-white border border-gray-100 text-xs font-medium">
                            </div>
                            <div>
                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2">Description</label>
                                <textarea rows="4" placeholder="Tell the story of Day 1..." class="w-full px-4 py-3 rounded-xl bg-white border border-gray-100 text-xs font-medium"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-white p-4 rounded-2xl border border-gray-100">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center text-orange-800 font-bold text-[10px]"><i class="fas fa-car mr-2"></i> Transfers</div>
                                        <button class="text-primary text-[10px] font-bold">+ Add</button>
                                    </div>
                                    <div class="h-10 bg-gray-50 rounded-lg flex items-center px-4">
                                        <p class="text-[9px] text-gray-300 font-medium italic">No transfers added yet</p>
                                    </div>
                                </div>
                                <div class="bg-white p-4 rounded-2xl border border-gray-100">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center text-orange-800 font-bold text-[10px]"><i class="fas fa-hotel mr-2"></i> Hotels</div>
                                        <button class="text-primary text-[10px] font-bold">+ Add</button>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-2 flex items-center justify-between border border-gray-100">
                                        <div class="flex items-center">
                                            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=100&auto=format&fit=crop" class="w-8 h-8 rounded-lg object-cover mr-2">
                                            <div>
                                                <p class="text-[8px] font-bold text-gray-800">Taj Palace, New Delhi</p>
                                                <p class="text-[7px] text-gray-400 font-medium">Luxury Stay • 1 Night</p>
                                            </div>
                                        </div>
                                        <button class="text-gray-300 text-[8px]"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3">Meals Included</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center px-4 py-2 bg-orange-800 text-white rounded-lg text-[9px] font-bold cursor-pointer"><input type="checkbox" checked class="hidden"> <i class="fas fa-check mr-2"></i> Breakfast</label>
                                    <label class="flex items-center px-4 py-2 bg-gray-100 text-gray-400 rounded-lg text-[9px] font-bold cursor-pointer"><input type="checkbox" class="hidden"> <i class="far fa-circle mr-2"></i> Lunch</label>
                                    <label class="flex items-center px-4 py-2 bg-orange-800 text-white rounded-lg text-[9px] font-bold cursor-pointer"><input type="checkbox" checked class="hidden"> <i class="fas fa-check mr-2"></i> Dinner</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="w-full py-4 mt-8 border-2 border-dashed border-gray-100 rounded-2xl flex items-center justify-center text-[10px] font-bold text-gray-400 hover:bg-gray-50 transition-colors">
                        <div class="w-6 h-6 bg-white shadow-sm rounded-full flex items-center justify-center mr-2"><i class="fas fa-plus"></i></div> Add Next Day
                    </button>
                </div>

                <!-- Sightseeing Details -->
                <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center"><i class="fas fa-camera text-xs"></i></div>
                            <h3 class="text-sm font-bold text-gray-800">Sightseeing Details</h3>
                        </div>
                        <button class="bg-orange-800 text-white px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest">+ Add Point</button>
                    </div>
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[9px] font-bold text-gray-300 uppercase tracking-widest border-b border-gray-50">
                                <th class="pb-4">Location</th>
                                <th class="pb-4">Activity</th>
                                <th class="pb-4">Duration</th>
                                <th class="pb-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr>
                                <td class="py-4 text-[10px] font-bold text-gray-800">Red Fort</td>
                                <td class="py-4 text-[10px] text-gray-400 font-medium">Historical Guided Tour</td>
                                <td class="py-4 text-[10px] text-gray-400 font-medium">3 Hours</td>
                                <td class="py-4 text-right text-gray-300"><i class="fas fa-ellipsis-v"></i></td>
                            </tr>
                            <tr>
                                <td class="py-4 text-[10px] font-bold text-gray-800">Chandni Chowk</td>
                                <td class="py-4 text-[10px] text-gray-400 font-medium">Street Food & Rickshaw Ride</td>
                                <td class="py-4 text-[10px] text-gray-400 font-medium">2 Hours</td>
                                <td class="py-4 text-right text-gray-300"><i class="fas fa-ellipsis-v"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Inclusions & Exclusions -->
                <div class="grid grid-cols-2 gap-8">
                    <div class="bg-green-50/50 p-6 rounded-[32px] border border-green-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center text-green-700 font-bold text-xs"><i class="fas fa-check-circle mr-2"></i> Inclusions</div>
                            <button class="text-green-700"><i class="fas fa-plus"></i></button>
                        </div>
                        <ul class="space-y-2">
                            <li class="text-[10px] text-green-600/80 font-medium flex items-center"> All airport transfers</li>
                            <li class="text-[10px] text-green-600/80 font-medium flex items-center"> Daily breakfast and dinner</li>
                        </ul>
                    </div>
                    <div class="bg-red-50/50 p-6 rounded-[32px] border border-red-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center text-red-700 font-bold text-xs"><i class="fas fa-times-circle mr-2"></i> Exclusions</div>
                            <button class="text-red-700"><i class="fas fa-plus"></i></button>
                        </div>
                        <ul class="space-y-2">
                            <li class="text-[10px] text-red-600/80 font-medium flex items-center"> International Airfare</li>
                            <li class="text-[10px] text-red-600/80 font-medium flex items-center"> Travel Insurance</li>
                        </ul>
                    </div>
                </div>

                <!-- Editorial Details -->
                <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100">
                    <h3 class="text-sm font-bold text-gray-800 mb-6 uppercase tracking-widest">Editorial Details</h3>
                    <div class="space-y-8">
                        <div>
                            <label class="block text-[9px] font-bold text-gray-400 uppercase mb-4 tracking-widest">About the tour</label>
                            <div class="border border-gray-100 rounded-2xl p-4 bg-gray-50/50">
                                <div class="flex space-x-4 mb-4 border-b border-gray-100 pb-2">
                                    <button class="text-gray-400"><i class="fas fa-bold"></i></button>
                                    <button class="text-gray-400"><i class="fas fa-italic"></i></button>
                                    <button class="text-gray-400"><i class="fas fa-list-ul"></i></button>
                                    <button class="text-gray-400"><i class="fas fa-link"></i></button>
                                </div>
                                <textarea placeholder="Explain why this tour is unique..." class="w-full bg-transparent border-none outline-none text-[10px] font-medium text-gray-400 h-32"></textarea>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-gray-400 uppercase mb-4 tracking-widest">Terms & Conditions</label>
                            <textarea placeholder="Specific booking policies for this package..." class="w-full p-4 rounded-2xl bg-gray-50/50 border border-gray-100 outline-none text-[10px] font-medium text-gray-400 h-24"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Sidebar Config -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Pricing & Dates -->
                <div class="bg-white p-6 rounded-[32px] shadow-sm border border-gray-100">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Pricing & Dates</h4>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[8px] font-bold text-gray-300 uppercase mb-1">Base Price</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs">$</span>
                                <input type="text" value="2450" class="w-full pl-8 pr-4 py-2 rounded-xl bg-gray-50 border-none text-xs font-bold text-gray-800">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[8px] font-bold text-gray-300 uppercase mb-1">Primary Departure</label>
                            <div class="relative">
                                <input type="text" value="12 October, 2024" class="w-full px-4 py-2 rounded-xl bg-gray-50 border-none text-xs font-bold text-gray-800">
                                <i class="far fa-calendar absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 text-[10px]"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Amenities -->
                <div class="bg-white p-6 rounded-[32px] shadow-sm border border-gray-100">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Essential Amenities</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex items-center text-[9px] font-bold text-gray-800 cursor-pointer"><input type="radio" checked class="mr-2 text-primary focus:ring-primary"> Wi-Fi</label>
                        <label class="flex items-center text-[9px] font-bold text-gray-800 cursor-pointer"><input type="radio" checked class="mr-2 text-primary focus:ring-primary"> Kitchen</label>
                        <label class="flex items-center text-[9px] font-bold text-gray-400 cursor-pointer"><input type="radio" class="mr-2 text-primary focus:ring-primary"> Laundry</label>
                        <label class="flex items-center text-[9px] font-bold text-gray-800 cursor-pointer"><input type="radio" checked class="mr-2 text-primary focus:ring-primary"> AC Room</label>
                    </div>
                </div>

                <!-- Tour Category -->
                <div class="bg-white p-6 rounded-[32px] shadow-sm border border-gray-100">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Tour Category</h4>
                    <div class="flex flex-wrap gap-2">
                        <span class="bg-orange-50 text-primary border border-orange-100 px-3 py-1 rounded-full text-[8px] font-bold uppercase tracking-tighter">Adventure</span>
                        <span class="bg-gray-100 text-gray-400 border border-gray-50 px-3 py-1 rounded-full text-[8px] font-bold uppercase tracking-tighter">Cultural</span>
                        <span class="bg-orange-800 text-white px-3 py-1 rounded-full text-[8px] font-bold uppercase tracking-tighter">Hill Station</span>
                        <span class="bg-gray-100 text-gray-400 border border-gray-50 px-3 py-1 rounded-full text-[8px] font-bold uppercase tracking-tighter">Wildlife</span>
                        <span class="bg-gray-100 text-gray-400 border border-gray-50 px-3 py-1 rounded-full text-[8px] font-bold uppercase tracking-tighter">Religious</span>
                    </div>
                </div>

                <!-- Progress Info -->
                <div class="bg-orange-50/50 p-6 rounded-[32px] border border-orange-100">
                    <div class="flex items-center text-primary font-bold text-[10px] mb-2 uppercase tracking-widest">
                        <div class="w-1.5 h-1.5 bg-primary rounded-full mr-2"></div> Draft In Progress
                    </div>
                    <p class="text-[9px] text-gray-400 font-medium leading-relaxed">Your changes were last saved at 5:32 PM. Make sure all compulsory fields are complete before moving to Pricing.</p>
                </div>
            </div>
        </div>

        <div class="mt-12 flex items-center justify-between pb-8">
            <a href="edit-package.php" class="text-[10px] font-bold text-gray-400 flex items-center hover:text-gray-800 transition-colors"><i class="fas fa-arrow-left mr-2"></i> Previous Step</a>
            <div class="flex space-x-3">
                <button class="px-8 py-3 rounded-xl text-xs font-bold text-gray-500 bg-white border border-gray-100">Save Draft</button>
                <a href="edit-images.php" class="px-10 py-3 rounded-xl text-xs font-bold text-white bg-orange-800 shadow-xl shadow-orange-100 hover:scale-[1.02] transition-all uppercase">Save & Continue</a>
            </div>
        </div>
    
@endsection
