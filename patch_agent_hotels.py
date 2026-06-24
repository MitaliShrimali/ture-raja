import re

# 1. Update AgentController.php
with open('app/Http/Controllers/AgentController.php', 'r', encoding='utf-8') as f:
    content = f.read()

# Update hotels()
hotels_method_pattern = r'''public function hotels\(\)\s*\{\s*\$hotels = DB::table\('hotels'\)->orderBy\('name', 'asc'\)->get\(\);\s*\$agents = DB::table\('agents'\)->orderBy\('name', 'asc'\)->get\(\);\s*return view\('agent\.pages\.hotels', \[\s*'page_title'\s*=> 'Hotels',\s*'page_breadcrumb'\s*=> 'Pages / Hotels',\s*'hotels'\s*=> \$hotels,\s*'agents'\s*=> \$agents,\s*\]\);\s*\}'''

new_hotels_method = r'''public function hotels()
    {
        $agentId   = session('agent_id');
        $agentName = session('agent_name', '');

        $hotels = DB::table('hotels')->orderBy('id', 'desc')->get();
        $agents = DB::table('agents')->orderBy('name', 'asc')->get();
        
        $hotelCategories = DB::table('hotel_categories')->orderBy('id', 'asc')->get();

        $allPackages = DB::table('packages')->select('id', 'title', 'agent')->orderBy('created_at', 'desc')->get();
        $packages = $allPackages->filter(function ($pkg) use ($agentId, $agentName) {
            if (!$pkg->agent) return false;
            $agentData = json_decode($pkg->agent, true);
            if (!$agentData) return false;
            return (isset($agentData['id']) && $agentData['id'] == $agentId)
                || (isset($agentData['name']) && $agentData['name'] === $agentName);
        })->values();

        return view('agent.pages.hotels', [
            'page_title'      => 'Hotels',
            'page_breadcrumb' => 'Pages / Hotels',
            'hotels'          => $hotels,
            'agents'          => $agents,
            'hotelCategories' => $hotelCategories,
            'packages'        => $packages,
        ]);
    }
    
    public function storeHotel(\Illuminate\Http\Request $request)
    {
        $request->validate(['name' => 'required', 'location' => 'required']);
        
        $hotelId = DB::table('hotels')->insertGetId([
            'name' => $request->name,
            'category' => $request->category ?? 'Luxury Resort',
            'location' => $request->location,
            'rating' => 5,
            'status' => $request->status ?? 'Published',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($request->filled('package_id')) {
            $this->addHotelToPackage($request->package_id, $request->name, $request->category);
        }

        return redirect()->back()->with('success', 'Hotel added successfully!');
    }

    public function updateHotel(\Illuminate\Http\Request $request)
    {
        $request->validate(['id' => 'required', 'name' => 'required']);
        
        DB::table('hotels')->where('id', $request->id)->update([
            'name' => $request->name,
            'category' => $request->category,
            'location' => $request->location,
            'status' => $request->status ?? 'Published',
            'updated_at' => now(),
        ]);

        if ($request->filled('package_id')) {
            $this->addHotelToPackage($request->package_id, $request->name, $request->category);
        }

        return redirect()->back()->with('success', 'Hotel updated successfully!');
    }

    public function deleteHotel($id)
    {
        DB::table('hotels')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Hotel deleted successfully!');
    }

    private function addHotelToPackage($packageId, $hotelName, $hotelCategory)
    {
        $agentId   = session('agent_id');
        $agentName = session('agent_name', '');
        
        $package = DB::table('packages')->where('id', $packageId)->first();
        if ($package) {
            $agentData = json_decode($package->agent, true);
            $belongsToAgent = false;
            if ($agentData) {
                if ((isset($agentData['id']) && $agentData['id'] == $agentId) || 
                    (isset($agentData['name']) && $agentData['name'] === $agentName)) {
                    $belongsToAgent = true;
                }
            }
            if ($belongsToAgent) {
                $hotels = json_decode($package->hotels, true) ?? [];
                // Check if already exists
                $exists = false;
                foreach($hotels as $h) {
                    if ($h['name'] == $hotelName) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $hotels[] = [
                        'name' => $hotelName,
                        'room' => $hotelCategory,
                        'image' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=200&auto=format&fit=crop'
                    ];
                    DB::table('packages')->where('id', $packageId)->update([
                        'hotels' => json_encode($hotels)
                    ]);
                }
            }
        }
    }'''

content = re.sub(hotels_method_pattern, new_hotels_method.replace('\\', '\\\\'), content, count=1)
with open('app/Http/Controllers/AgentController.php', 'w', encoding='utf-8') as f:
    f.write(content)

# 2. Update resources/views/agent/pages/hotels.blade.php
with open('resources/views/agent/pages/hotels.blade.php', 'r', encoding='utf-8') as f:
    blade = f.read()

# Fix form to use real POST actions
form_regex = r'<form class="space-y-4" onsubmit="handleHotelSubmit\(event\)">\s*<input type="hidden" id="hotelId">'
new_form = r'''<form class="space-y-4" method="POST" action="{{ route('hotels.store') }}" id="hotelForm">
            @csrf
            <input type="hidden" id="hotelId" name="id">'''
blade = re.sub(form_regex, new_form, blade)

# Add name attributes to existing inputs and match backend expectations
blade = blade.replace('id="hotelName" placeholder=', 'id="hotelName" name="name" required placeholder=')
blade = blade.replace('id="hotelCity" placeholder=', 'id="hotelCity" name="location" required placeholder=')
blade = blade.replace('id="hotelCategory"', 'id="hotelCategory" name="category"')
blade = blade.replace('id="hotelStatus"', 'id="hotelStatus" name="status"')
blade = blade.replace('id="hotelAddress"', 'id="hotelAddress" name="address"')
blade = blade.replace('id="hotelState"', 'id="hotelState" name="state"')
blade = blade.replace('id="hotelCountry"', 'id="hotelCountry" name="country"')

# Add dropdowns for Package Name and Hotel Category (which are now dynamic)
# Find the end of the input fields section, before the live preview
dropdown_injection_point = r'</div>\s*<!-- Live Preview Area -->'
dropdowns_html = r'''</div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Link to Package (Optional)</label>
                    <select name="package_id" id="hotelPackage" class="w-full px-4 py-3 rounded-xl border border-gray-100 focus:border-primary focus:ring-0 outline-none text-xs font-medium bg-gray-50/50">
                        <option value="">-- Do not link --</option>
                        @foreach($packages as $pkg)
                            <option value="{{ $pkg->id }}">{{ $pkg->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Hotel Category</label>
                    <select name="category" id="hotelCategory" class="w-full px-4 py-3 rounded-xl border border-gray-100 focus:border-primary focus:ring-0 outline-none text-xs font-medium bg-gray-50/50">
                        @foreach($hotelCategories as $cat)
                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Live Preview Area -->'''

blade = re.sub(r'<select id="hotelCategory"([^>]*?)>.*?</select>', r'', blade, flags=re.DOTALL) # Remove old mock dropdown if any
blade = re.sub(dropdown_injection_point, dropdowns_html.replace('\\', '\\\\'), blade)

# Update Javascript
js_regex = r'function toggleHotelModal\(mode = \'add\'\).*?function handleHotelSubmit\(e\) \{.*?\}\s*</script>'

new_js = r'''function toggleHotelModal(mode = 'add') {
        const modal = document.getElementById('addHotelModal');
        const container = document.getElementById('modalContainer');
        const title = document.getElementById('modalTitle');
        const submitBtnText = document.getElementById('submitBtnText');
        const form = document.getElementById('hotelForm');

        if (modal.classList.contains('hidden')) {
            if (mode === 'add') {
                title.innerText = 'Add Hotel';
                submitBtnText.innerText = 'Save Hotel';
                form.action = "{{ route('hotels.store') }}";
                document.getElementById('hotelId').value = '';
                document.getElementById('hotelName').value = '';
                document.getElementById('hotelCity').value = '';
                if(document.getElementById('hotelPackage')) document.getElementById('hotelPackage').value = '';
                // defaults
            }
            modal.classList.remove('hidden');
            setTimeout(() => {
                container.classList.remove('scale-95', 'opacity-0');
                container.classList.add('scale-100', 'opacity-100');
            }, 10);
        } else {
            container.classList.remove('scale-100', 'opacity-100');
            container.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    }

    function editHotel(hotel) {
        const title = document.getElementById('modalTitle');
        const submitBtnText = document.getElementById('submitBtnText');
        const form = document.getElementById('hotelForm');

        title.innerText = 'Edit Hotel';
        submitBtnText.innerText = 'Update Hotel';
        form.action = "{{ route('hotels.update') }}";

        document.getElementById('hotelId').value = hotel.id;
        document.getElementById('hotelName').value = hotel.name;
        document.getElementById('hotelCity').value = hotel.loc;
        if(document.getElementById('hotelPackage')) document.getElementById('hotelPackage').value = ''; // Reset for safety

        toggleHotelModal('edit');
    }

    function deleteHotel(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#F0642F',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            borderRadius: '2rem'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit a form to the delete route
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "/agent/hotels/delete/" + id;
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = "{{ csrf_token() }}";
                form.appendChild(csrf);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>'''
blade = re.sub(js_regex, new_js.replace('\\', '\\\\'), blade, flags=re.DOTALL)

with open('resources/views/agent/pages/hotels.blade.php', 'w', encoding='utf-8') as f:
    f.write(blade)

print("Patch applied.")
