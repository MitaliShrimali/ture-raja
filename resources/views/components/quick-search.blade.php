<div class="bg-gray-50 pt-6 pb-6 relative z-40 border-b border-gray-200">
    <div class="container-custom text-center mb-0">
        <div class="w-full max-w-6xl mx-auto">
            <form action="{{ url('/discover') }}" method="GET">
            <div class="p-3 md:p-3 rounded-lg flex flex-col md:flex-row items-center gap-3 w-full shadow-sm" style="background: #e85d26;">
                
                {{-- Destination Field --}}
                <div class="flex items-center gap-3 flex-1 w-full rounded-md px-4 py-3" style="background: rgba(255,255,255,0.2);">
                <i data-lucide="search" class="text-white" size="18"></i>
                <input type="text" name="search" placeholder="Search Where You Go !!!"
                        class="bg-transparent border-none focus:ring-0 text-white placeholder-white/90 text-sm font-bold outline-none w-full p-0"
                        value="{{ request('search') }}">
                </div>

                {{-- Agent/City Field --}}
                <div class="flex items-center gap-3 flex-1 w-full rounded-md px-4 py-3" style="background: rgba(255,255,255,0.2);">
                <i data-lucide="user" class="text-white" size="18"></i>
                <input type="text" name="city" placeholder="Search Nearby Agent Location"
                        class="bg-transparent border-none focus:ring-0 text-white placeholder-white/90 text-sm font-bold outline-none w-full p-0"
                        value="{{ is_array(request('city')) ? implode(', ', request('city')) : request('city') }}">
                </div>

                {{-- Check In Field --}}
                <div class="flex items-center gap-3 w-full md:w-auto rounded-md px-4 py-3 shrink-0" style="background: rgba(255,255,255,0.2);">
                <i data-lucide="calendar" class="text-white" size="18"></i>
                <input type="text" name="check_in" placeholder="Check in" onfocus="(this.type='date')" onblur="(this.type='text')"
                        class="bg-transparent border-none focus:ring-0 text-white placeholder-white/90 text-sm font-bold outline-none w-full md:w-28 p-0"
                        value="{{ request('check_in') }}">
                </div>

                {{-- Search Button --}}
                <div class="w-full md:w-auto shrink-0">
                <button type="submit"
                        class="rounded-full bg-white text-gray-900 font-bold text-sm hover:bg-gray-100 transition-colors w-full sm:w-auto px-10 py-3 shadow-sm">
                    Search
                </button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
