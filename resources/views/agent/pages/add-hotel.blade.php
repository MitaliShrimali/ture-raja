@extends('agent.layouts.app')

@section('title', 'Add Hotel - Tour Raja Agent')

@section('content')
<div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-xs text-gray-400 font-medium">Pages / Add Hotel Name / Add Hotel</p>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Add Hotel</h2>
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-xs font-bold text-gray-800 mb-8 uppercase tracking-widest">Add Hotel</h3>

            <form action="hotels.php" class="space-y-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-800 mb-2 uppercase tracking-widest">Hotel Name & City: <span class="text-red-500">*</span></label>
                        <input type="text" class="w-full px-4 py-3 rounded-xl border border-gray-100 focus:border-primary focus:ring-0 outline-none text-xs font-medium bg-white shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-800 mb-2 uppercase tracking-widest">Category:</label>
                        <div class="relative">
                            <select class="w-full px-4 py-3 rounded-xl border border-gray-100 focus:border-primary focus:ring-0 outline-none text-xs font-medium bg-white shadow-sm appearance-none">
                                <option value="" disabled selected>Search Category</option>
                                <option>Deluxe</option>
                                <option>Luxury</option>
                                <option>Standard</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 text-[10px]"></i>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 pt-10 border-t border-gray-50">
                    <a href="hotels.php" class="px-8 py-2 rounded-xl text-[10px] font-bold text-gray-400 bg-gray-50 border border-gray-100 hover:bg-gray-100 transition-all uppercase tracking-widest">Cancel</a>
                    <button type="submit" class="px-8 py-2 bg-blue-600 text-white rounded-xl text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-blue-100 hover:scale-105 transition-all">Save</button>
                </div>
            </form>
        </div>
    
@endsection
