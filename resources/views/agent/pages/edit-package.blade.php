@extends('agent.layouts.app')

@section('title', 'Edit Package - Tour Raja Agent')

@section('content')
<div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-xs text-gray-400 font-medium">Pages / My Packages / Edit Package</p>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Edit Package</h2>
                <p class="text-[10px] text-gray-400 font-medium">Update your package details to attract more travelers.</p>
            </div>
            <div class="flex space-x-3">
                <button class="px-6 py-2 rounded-xl text-xs font-bold text-gray-500 bg-white border border-gray-100">Discard</button>
                <a href="edit-itinerary.php" class="px-6 py-2 rounded-xl text-xs font-bold text-white bg-orange-800 hover:bg-orange-900 transition-colors">Save & Next</a>
            </div>
        </div>

        <!-- Step Indicator -->
        <div class="relative flex items-center justify-between max-w-4xl mx-auto mb-12">
            <div class="flex flex-col items-center z-10">
                <div class="w-10 h-10 bg-orange-700 text-white rounded-full flex items-center justify-center text-xs font-bold shadow-xl shadow-orange-100">1</div>
                <p class="text-[9px] font-bold text-primary mt-2 uppercase tracking-widest">Identity & Logistics</p>
            </div>
            <div class="absolute left-0 top-5 w-full h-[1px] bg-gray-200 -z-0"></div>
            <div class="flex flex-col items-center z-10">
                <div class="w-10 h-10 bg-white border-2 border-gray-100 text-gray-300 rounded-full flex items-center justify-center text-xs font-bold">2</div>
                <p class="text-[9px] font-bold text-gray-300 mt-2 uppercase tracking-widest">Itinerary & Meals</p>
            </div>
            <div class="flex flex-col items-center z-10">
                <div class="w-10 h-10 bg-white border-2 border-gray-100 text-gray-300 rounded-full flex items-center justify-center text-xs font-bold">3</div>
                <p class="text-[9px] font-bold text-gray-300 mt-2 uppercase tracking-widest">Inclusions & Final</p>
            </div>
        </div>

        <div class="space-y-8">
            <!-- Package Identity -->
            <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-8 h-8 bg-orange-50 text-primary rounded-xl flex items-center justify-center"><i class="fas fa-id-card text-xs"></i></div>
                    <h3 class="text-sm font-bold text-gray-800">Package Identity</h3>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Package Name</label>
                        <input type="text" placeholder="The Ultimate Bali Escape" class="w-full px-4 py-3 rounded-xl bg-gray-50 border-none focus:ring-1 focus:ring-primary text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Destination Type</label>
                        <div class="flex bg-gray-50 p-1 rounded-xl">
                            <button type="button" class="flex-1 py-2 text-[10px] font-bold rounded-lg bg-orange-50 text-primary shadow-sm">Domestic</button>
                            <button type="button" class="flex-1 py-2 text-[10px] font-bold rounded-lg text-gray-400">International</button>
                        </div>
                    </div>
                    <div class="lg:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">City List (Select Cities Included)</label>
                        <div class="w-full px-4 py-2 rounded-xl bg-gray-50 flex flex-wrap items-center gap-2">
                            <span class="bg-orange-800 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg flex items-center">Ubud <i class="fas fa-times ml-2 cursor-pointer"></i></span>
                            <span class="bg-orange-800 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg flex items-center">Seminyak <i class="fas fa-times ml-2 cursor-pointer"></i></span>
                            <span class="bg-orange-800 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg flex items-center">Uluwatu <i class="fas fa-times ml-2 cursor-pointer"></i></span>
                            <button class="bg-white border border-gray-100 text-[10px] font-bold px-3 py-1.5 rounded-lg text-gray-400">+ Add City</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logistics & Departure -->
            <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-8 h-8 bg-orange-50 text-primary rounded-xl flex items-center justify-center"><i class="fas fa-map-marked-alt text-xs"></i></div>
                    <h3 class="text-sm font-bold text-gray-800">Logistics & Departure</h3>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Duration</label>
                        <select class="w-full px-4 py-3 rounded-xl bg-gray-50 border-none text-sm font-medium appearance-none">
                            <option>5 Days / 4 Nights</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Package Validity</label>
                        <div class="relative">
                            <input type="text" placeholder="20 Dec 2024 - 30 Mar 2025" class="w-full px-4 py-3 rounded-xl bg-gray-50 border-none text-sm font-medium">
                            <i class="far fa-calendar absolute right-4 top-1/2 -translate-y-1/2 text-gray-300"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Transit Type</label>
                        <select class="w-full px-4 py-3 rounded-xl bg-gray-50 border-none text-sm font-medium appearance-none">
                            <option>Direct Flight</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Departure City</label>
                        <input type="text" placeholder="New Delhi" class="w-full px-4 py-3 rounded-xl bg-gray-50 border-none text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Departure State</label>
                        <input type="text" placeholder="Delhi" class="w-full px-4 py-3 rounded-xl bg-gray-50 border-none text-sm font-medium">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Departure Country</label>
                        <select class="w-full px-4 py-3 rounded-xl bg-gray-50 border-none text-sm font-medium appearance-none">
                            <option>India</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Pricing & Specifics Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Pricing -->
                <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100">
                    <div class="flex items-center space-x-3 mb-8">
                        <div class="w-8 h-8 bg-orange-50 text-primary rounded-xl flex items-center justify-center"><i class="fas fa-tag text-xs"></i></div>
                        <h3 class="text-sm font-bold text-gray-800">Pricing</h3>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Price Per Person (INR)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">₹</span>
                                <input type="text" placeholder="45999" class="w-full pl-8 pr-4 py-3 rounded-xl bg-gray-50 border-none text-sm font-medium">
                            </div>
                        </div>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="hide_price" class="text-primary focus:ring-primary">
                            <span class="text-[10px] font-medium text-gray-400">Hide price from package listing</span>
                        </label>
                    </div>
                </div>
                <!-- Specifics -->
                <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100">
                    <div class="flex items-center space-x-3 mb-8">
                        <div class="w-8 h-8 bg-orange-50 text-primary rounded-xl flex items-center justify-center"><i class="fas fa-bullseye text-xs"></i></div>
                        <h3 class="text-sm font-bold text-gray-800">Specifics</h3>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Theme Selection</label>
                            <select class="w-full px-4 py-3 rounded-xl bg-gray-50 border-none text-sm font-medium appearance-none">
                                <option>Solo Travelers</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Holiday Type</label>
                            <select class="w-full px-4 py-3 rounded-xl bg-gray-50 border-none text-sm font-medium appearance-none">
                                <option>Multi City</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trip Keywords -->
            <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-8 h-8 bg-orange-50 text-primary rounded-xl flex items-center justify-center"><i class="fas fa-search text-xs"></i></div>
                    <h3 class="text-sm font-bold text-gray-800">Trip Keywords</h3>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-2">Search Keywords (Helps travelers find you)</label>
                    <div class="w-full px-4 py-2 rounded-xl bg-gray-50 flex flex-wrap items-center gap-2">
                        <span class="bg-gray-200 text-gray-600 text-[10px] font-bold px-3 py-1.5 rounded-lg">Bali Beaches</span>
                        <span class="bg-gray-200 text-gray-600 text-[10px] font-bold px-3 py-1.5 rounded-lg">Scuba Diving</span>
                        <span class="bg-gray-200 text-gray-600 text-[10px] font-bold px-3 py-1.5 rounded-lg">Temple Tour</span>
                        <span class="bg-gray-200 text-gray-600 text-[10px] font-bold px-3 py-1.5 rounded-lg">Nightlife</span>
                        <input type="text" placeholder="Type and press enter..." class="bg-transparent border-none outline-none text-[10px] text-gray-400 font-medium ml-2">
                    </div>
                </div>
            </div>

            <!-- Upload Brochure -->
            <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-8 h-8 bg-orange-50 text-primary rounded-xl flex items-center justify-center"><i class="fas fa-file-pdf text-xs"></i></div>
                    <h3 class="text-sm font-bold text-gray-800">Upload Brochure</h3>
                </div>
                <div class="border-2 border-dashed border-gray-100 rounded-3xl p-12 flex flex-col items-center justify-center text-center">
                    <div class="w-12 h-12 bg-orange-50 text-primary rounded-full flex items-center justify-center mb-4"><i class="fas fa-cloud-upload-alt"></i></div>
                    <p class="text-sm font-bold text-gray-800 mb-1">Drop your brochure here</p>
                    <p class="text-[10px] text-gray-400 mb-6 font-medium">Or click to browse from your computer</p>
                    <button class="px-6 py-2 border border-gray-100 rounded-lg text-[10px] font-bold text-gray-500 bg-white">Choose File</button>
                    <p class="text-[8px] text-gray-300 mt-4 font-bold uppercase tracking-widest">PDF format only • Max 5MB</p>
                </div>
            </div>
        </div>

        <div class="mt-12 flex items-center justify-between pb-8">
            <a href="my-packages.php" class="text-[10px] font-bold text-gray-400 flex items-center hover:text-gray-800 transition-colors"><i class="fas fa-arrow-left mr-2"></i> Back to Packages</a>
            <div class="flex space-x-3">
                <button class="px-8 py-3 rounded-xl text-xs font-bold text-gray-500 bg-white border border-gray-100">Save Draft</button>
                <a href="edit-itinerary.php" class="px-10 py-3 rounded-xl text-xs font-bold text-white bg-orange-800 shadow-xl shadow-orange-100 hover:scale-[1.02] transition-all">SAVE & NEXT</a>
            </div>
        </div>
    
@endsection
