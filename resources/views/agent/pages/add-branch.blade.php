@extends('agent.layouts.app')

@section('title', 'Add Branch - Tour Raja Agent')

@section('content')


        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side: Form -->
            <div class="lg:col-span-8 bg-white p-10 rounded-[48px] shadow-sm border border-gray-100">
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Add Branch</h3>
                <p class="text-[11px] text-gray-400 font-medium mb-10">Register a new physical office location to your agency network. Ensure all asterisk (*) fields are filled.</p>

                <form action="branch.php" class="space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest text-right">TRAVEL COMPANY / AGENCY NAME *</label>
                            <div class="relative">
                                <i class="far fa-building absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                <input type="text" placeholder="e.g. Horizon Ascent Bali" class="w-full pl-12 pr-6 py-4 rounded-[20px] bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest text-right">PHONE *</label>
                            <div class="relative">
                                <i class="fas fa-phone-alt absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                                <input type="text" placeholder="+62 812 3456 789" class="w-full pl-12 pr-6 py-4 rounded-[20px] bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest text-right">LOCATION *</label>
                        <div class="relative">
                            <i class="fas fa-map-marker-alt absolute left-5 top-1/2 -translate-y-1/2 text-gray-300"></i>
                            <input type="text" placeholder="Select City or Region" class="w-full pl-12 pr-6 py-4 rounded-[20px] bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-bold text-gray-400 uppercase mb-3 tracking-widest text-right">ADDRESS *</label>
                        <div class="relative">
                            <i class="far fa-map absolute left-5 top-6 text-gray-300"></i>
                            <textarea rows="5" placeholder="Full street address, building name, and floor number" class="w-full pl-12 pr-6 py-5 rounded-[32px] bg-gray-50 border-none text-xs font-medium focus:ring-2 focus:ring-primary/20"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-8 pt-6">
                        <a href="branch.php" class="text-sm font-bold text-gray-500 hover:text-gray-800 transition-colors">Cancel</a>
                        <button type="submit" class="bg-orange-800 text-white px-10 py-4 rounded-[24px] text-sm font-bold flex items-center shadow-xl shadow-orange-100 hover:scale-[1.02] transition-all">
                            Save Branch <i class="far fa-check-circle ml-3"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Side: Guidelines -->
            <div class="lg:col-span-4 space-y-8">
                <!-- Branch Standards -->
                <div class="bg-white p-8 rounded-[48px] shadow-sm border border-gray-100 relative overflow-hidden group">
                    <!-- Decorative background pattern (simulated) -->
                    <div class="absolute inset-0 opacity-5 pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                    
                    <h4 class="text-[10px] font-bold text-orange-800 uppercase tracking-[3px] mb-8">Branch Standards</h4>
                    <ul class="space-y-6 relative z-10">
                        <li class="flex items-start">
                            <div class="w-5 h-5 bg-orange-100 text-orange-800 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"><i class="fas fa-check text-[8px]"></i></div>
                            <p class="ml-4 text-[10px] text-gray-500 font-medium leading-relaxed">Each branch must have a dedicated local phone number.</p>
                        </li>
                        <li class="flex items-start">
                            <div class="w-5 h-5 bg-orange-100 text-orange-800 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"><i class="fas fa-check text-[8px]"></i></div>
                            <p class="ml-4 text-[10px] text-gray-500 font-medium leading-relaxed">Verification of address will be required within 48 hours.</p>
                        </li>
                        <li class="flex items-start">
                            <div class="w-5 h-5 bg-orange-100 text-orange-800 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"><i class="fas fa-check text-[8px]"></i></div>
                            <p class="ml-4 text-[10px] text-gray-500 font-medium leading-relaxed">Branches are visible to customers immediately after saving.</p>
                        </li>
                    </ul>
                </div>

                <!-- Bulk Import Box -->
                <div class="bg-orange-800 p-8 rounded-[48px] shadow-2xl shadow-orange-200 relative overflow-hidden group">
                    <!-- Abstract circles (simulated with CSS circles) -->
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-orange-700/50 rounded-full"></div>
                    <div class="absolute -right-5 -bottom-5 w-20 h-20 bg-orange-600/30 rounded-full"></div>
                    
                    <h4 class="text-white font-bold text-lg leading-tight mb-4 relative z-10">Need to import bulk locations?</h4>
                    <p class="text-orange-100/70 text-[10px] font-medium leading-relaxed mb-8 relative z-10">Download our CSV template to add multiple branches at once.</p>
                    <button class="w-full py-4 bg-orange-700/50 text-white rounded-[20px] text-[10px] font-bold flex items-center justify-center border border-orange-600/50 hover:bg-orange-700 transition-colors relative z-10">
                        <i class="fas fa-file-download mr-3"></i> Download Template
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer -->
@endsection
