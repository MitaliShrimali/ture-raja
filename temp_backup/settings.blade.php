@extends('layouts.admin')

@section('admin_title')
    {{ request('tab') === 'general' ? 'General Settings' : 'Settings Hub' }}
@endsection

@section('content')
@if(request('tab') === 'general')
<div class="space-y-8 pb-12">

    <form action="{{ url('admin/settings/update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Column: Forms -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- General Agency Information -->
                <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.02)] space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-50 pb-4">
                        <div class="w-8 h-8 rounded-lg bg-[#FDF2E9] flex items-center justify-center text-[#B23B06]">
                            <i data-lucide="building-2" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">General Agency Information</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Agency Name</label>
                            <input type="text" name="agency_name" value="{{ $settings['agency_name'] ?? 'Explorer Global Travels' }}" 
                                class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl px-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Primary Email</label>
                            <input type="email" name="primary_email" value="{{ $settings['primary_email'] ?? 'admin@explorerglobal.com' }}" 
                                class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl px-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Contact Phone</label>
                            <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '+1 (555) 0123-4567' }}" 
                                class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl px-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Website URL</label>
                            <input type="url" name="website_url" value="{{ $settings['website_url'] ?? 'https://explorerglobal.com' }}" 
                                class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl px-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200" required>
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Registered Office Address</label>
                            <textarea name="registered_office_address" rows="3" 
                                class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl px-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200 leading-relaxed" required>{{ $settings['registered_office_address'] ?? '742 Discovery Plaza, Suite 900, New York, NY 10001, United States' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Bank Details & Tax Config Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Bank Details -->
                    <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.02)] space-y-6">
                        <div class="flex items-center gap-3 border-b border-gray-50 pb-4">
                            <div class="w-8 h-8 rounded-lg bg-[#FDF2E9] flex items-center justify-center text-[#B23B06]">
                                <i data-lucide="landmark" class="w-4 h-4"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Bank Details</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Bank Name</label>
                                <input type="text" name="bank_name" value="{{ $settings['bank_name'] ?? '' }}" placeholder="e.g. Chase Manhattan"
                                    class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl px-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Account Number</label>
                                <input type="text" name="account_number" value="{{ $settings['account_number'] ?? '' }}" placeholder="XXXX XXXX XXXX 8890"
                                    class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl px-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">IFSC / SWIFT Code</label>
                                <input type="text" name="ifsc_code" value="{{ $settings['ifsc_code'] ?? '' }}" placeholder="CHASEUS33"
                                    class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl px-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200">
                            </div>
                        </div>
                    </div>

                    <!-- Tax Config -->
                    <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.02)] space-y-6">
                        <div class="flex items-center gap-3 border-b border-gray-50 pb-4">
                            <div class="w-8 h-8 rounded-lg bg-[#FDF2E9] flex items-center justify-center text-[#B23B06]">
                                <i data-lucide="percent" class="w-4 h-4"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Tax Config</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between bg-[#F5F4F2] rounded-xl px-5 py-3.5">
                                <span class="text-xs font-black text-gray-800">CGST (%)</span>
                                <input type="number" name="cgst" value="{{ $settings['cgst'] ?? '9' }}" 
                                    class="bg-transparent border-0 text-right font-black text-xl text-[#B23B06] w-20 focus:ring-0 p-0">
                            </div>
                            <div class="flex items-center justify-between bg-[#F5F4F2] rounded-xl px-5 py-3.5">
                                <span class="text-xs font-black text-gray-800">SGST (%)</span>
                                <input type="number" name="sgst" value="{{ $settings['sgst'] ?? '9' }}" 
                                    class="bg-transparent border-0 text-right font-black text-xl text-[#B23B06] w-20 focus:ring-0 p-0">
                            </div>
                            <div class="flex items-center justify-between bg-[#F5F4F2] rounded-xl px-5 py-3.5">
                                <span class="text-xs font-black text-gray-800">IGST (%)</span>
                                <input type="number" name="igst" value="{{ $settings['igst'] ?? '18' }}" 
                                    class="bg-transparent border-0 text-right font-black text-xl text-[#B23B06] w-20 focus:ring-0 p-0">
                            </div>
                            <div class="space-y-2 pt-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">GST Identification Number</label>
                                <input type="text" name="gstin" value="{{ $settings['gstin'] ?? '22AAAAA0000A1Z5' }}" 
                                    class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl px-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoice Sequence & Format -->
                <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.02)] space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-50 pb-4">
                        <div class="w-8 h-8 rounded-lg bg-[#FDF2E9] flex items-center justify-center text-[#B23B06]">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Invoice Sequence & Format</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Prefix</label>
                            <input type="text" name="invoice_prefix" value="{{ $settings['invoice_prefix'] ?? 'INV-' }}" 
                                class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl px-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Next Number</label>
                            <input type="text" name="invoice_next_number" value="{{ $settings['invoice_next_number'] ?? '1024' }}" 
                                class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl px-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Format</label>
                            <div class="relative">
                                <select name="invoice_format" 
                                    class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl px-4 py-3.5 pr-10 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200 appearance-none">
                                    <option value="prefix_number" {{ ($settings['invoice_format'] ?? '') === 'prefix_number' ? 'selected' : '' }}>Prefix + Number</option>
                                    <option value="number_only" {{ ($settings['invoice_format'] ?? '') === 'number_only' ? 'selected' : '' }}>Number Only</option>
                                    <option value="prefix_year_number" {{ ($settings['invoice_format'] ?? '') === 'prefix_year_number' ? 'selected' : '' }}>Prefix + Year + Number</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                    <i data-lucide="chevron-down" class="w-4 h-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security & Authentication -->
                <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.02)] space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-50 pb-4">
                        <div class="w-8 h-8 rounded-lg bg-[#FDF2E9] flex items-center justify-center text-[#B23B06]">
                            <i data-lucide="shield-check" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Security & Authentication</h3>
                    </div>

                    <div class="space-y-4">
                        <!-- Change Password Line -->
                        <div class="flex items-center justify-between p-5 rounded-2xl bg-[#FFF5F2]/40 border border-[#FFF5F2] hover:bg-[#FFF5F2]/60 transition-colors">
                            <div class="flex gap-4 items-start">
                                <div class="w-10 h-10 rounded-xl bg-[#FFF5F2] text-[#B23B06] flex items-center justify-center shrink-0">
                                    <i data-lucide="key-round" class="w-5 h-5"></i>
                                </div>
                                <div class="space-y-0.5">
                                    <h4 class="text-sm font-black text-gray-900">Change Password</h4>
                                    <p class="text-xs text-gray-400 font-semibold">Last changed 4 months ago. Recommended every 6 months.</p>
                                </div>
                            </div>
                            <a href="#" class="text-sm font-black text-[#B23B06] hover:underline px-4 py-2">Update</a>
                        </div>

                        <!-- 2FA Line -->
                        <div class="flex items-center justify-between p-5 rounded-2xl bg-[#FFF5F2]/40 border border-[#FFF5F2] hover:bg-[#FFF5F2]/60 transition-colors">
                            <div class="flex gap-4 items-start">
                                <div class="w-10 h-10 rounded-xl bg-[#FFF5F2] text-[#B23B06] flex items-center justify-center shrink-0">
                                    <i data-lucide="shield" class="w-5 h-5"></i>
                                </div>
                                <div class="space-y-0.5">
                                    <h4 class="text-sm font-black text-gray-900">Two-Factor Authentication (2FA)</h4>
                                    <p class="text-xs text-gray-400 font-semibold">Add an extra layer of security to your account.</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="two_factor_enabled" value="1" class="sr-only peer" {{ ($settings['two_factor_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#B23B06]"></div>
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Brand Assets & Social -->
            <div class="lg:col-span-4 space-y-8">
                
                <!-- Brand Assets -->
                <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.02)] space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-50 pb-4">
                        <div class="w-8 h-8 rounded-lg bg-[#FDF2E9] flex items-center justify-center text-[#B23B06]">
                            <i data-lucide="image" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Brand Assets</h3>
                    </div>

                    <!-- Agency Logo Upload Box -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Agency Logo</label>
                        <div class="relative group border-2 border-dashed border-gray-200 rounded-[24px] p-6 bg-[#FBFBFA] flex flex-col items-center justify-center hover:border-[#B23B06]/30 transition-colors cursor-pointer" onclick="document.getElementById('agency_logo_input').click()">
                            <input type="file" id="agency_logo_input" name="agency_logo" class="hidden" onchange="previewLogo(this)">
                            
                            @if(!empty($settings['agency_logo']))
                                <img id="agency_logo_preview" src="{{ asset($settings['agency_logo']) }}" alt="Agency Logo" class="max-h-28 object-contain rounded-lg">
                            @else
                                <div id="agency_logo_preview_container" class="w-28 h-28 bg-[#0A5C66] rounded-xl flex items-center justify-center p-4">
                                    <span class="text-white text-xs font-black tracking-tight text-center">AGENCY<br><span class="text-[8px] opacity-70 font-semibold">SAFE FOR WORK</span></span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Favicon Upload Box -->
                    <div class="space-y-2 pt-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Favicon (32x32)</label>
                        <div class="flex items-center justify-between bg-[#F5F4F2] rounded-2xl p-3 border border-gray-50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm overflow-hidden p-1.5">
                                    <img id="favicon_preview" src="{{ asset($settings['favicon'] ?? 'https://api.dicebear.com/7.x/identicon/svg?seed=Favicon') }}" alt="Favicon" class="w-full h-full object-contain">
                                </div>
                            </div>
                            <input type="file" id="favicon_input" name="favicon" class="hidden" onchange="previewFavicon(this)">
                            <button type="button" onclick="document.getElementById('favicon_input').click()" 
                                class="px-4 py-2 rounded-xl bg-[#FFF4CE] text-[#E85D26] text-xs font-black hover:bg-[#FFEAA7] transition-all">
                                Change Icon
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Social Presence -->
                <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.02)] space-y-6">
                    <div class="flex items-center gap-3 border-b border-gray-50 pb-4">
                        <div class="w-8 h-8 rounded-lg bg-[#FDF2E9] flex items-center justify-center text-[#B23B06]">
                            <i data-lucide="share-2" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">Social Presence</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="relative flex items-center">
                            <div class="absolute left-4 text-gray-400">
                                <i data-lucide="facebook" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="facebook_url" value="{{ $settings['facebook_url'] ?? 'facebook.com/explorerglobal' }}" 
                                class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl pl-12 pr-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200">
                        </div>

                        <div class="relative flex items-center">
                            <div class="absolute left-4 text-gray-400">
                                <i data-lucide="instagram" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="instagram_url" value="{{ $settings['instagram_url'] ?? 'instagram.com/explorerglobal' }}" 
                                class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl pl-12 pr-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200">
                        </div>

                        <div class="relative flex items-center">
                            <div class="absolute left-4 text-gray-400">
                                <i data-lucide="linkedin" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="linkedin_url" value="{{ $settings['linkedin_url'] ?? 'linkedin.com/company/explorerglobal' }}" 
                                class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl pl-12 pr-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200">
                        </div>

                        <div class="relative flex items-center">
                            <div class="absolute left-4 text-gray-400">
                                <i data-lucide="twitter" class="w-4 h-4"></i>
                            </div>
                            <input type="text" name="twitter_url" value="{{ $settings['twitter_url'] ?? 'twitter.com/explorertravel' }}" 
                                class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl pl-12 pr-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-4 pt-2">
                    <button type="submit" class="w-full bg-[#B23B06] hover:bg-[#902F04] text-white font-bold py-4 px-6 rounded-2xl flex items-center justify-center gap-2 shadow-lg shadow-[#B23B06]/20 transition-all duration-200">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        Update All Settings
                    </button>
                    <a href="{{ url('admin/settings') }}" class="w-full bg-[#E5E7EB] hover:bg-[#D1D5DB] text-gray-700 font-bold py-4 px-6 rounded-2xl flex items-center justify-center transition-all duration-200 text-center block">
                        Discard Changes
                    </a>
                    <div class="text-center">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Last Updated: Oct 24, 2023 • 02:45 PM</span>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-[32px] p-8 border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.02)] space-y-6">
                    <h3 class="text-lg font-bold text-gray-900 border-b border-gray-50 pb-4">Recent Activity</h3>
                    
                    <div class="space-y-6">
                        <!-- Activity 1 -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                                <i data-lucide="log-in" class="w-5 h-5"></i>
                            </div>
                            <div class="space-y-0.5">
                                <h4 class="text-sm font-bold text-gray-900">Successful Login</h4>
                                <p class="text-xs text-gray-400 font-medium">San Francisco, CA • 2 hours ago</p>
                            </div>
                        </div>

                        <!-- Activity 2 -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                                <i data-lucide="database" class="w-5 h-5"></i>
                            </div>
                            <div class="space-y-0.5">
                                <h4 class="text-sm font-bold text-gray-900">Bulk Expedition Update</h4>
                                <p class="text-xs text-gray-400 font-medium">Bali Summer Retreats • Yesterday</p>
                            </div>
                        </div>

                        <!-- Activity 3 -->
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center shrink-0">
                                <i data-lucide="shield-check" class="w-5 h-5"></i>
                            </div>
                            <div class="space-y-0.5">
                                <h4 class="text-sm font-bold text-gray-900">Security Audit Performed</h4>
                                <p class="text-xs text-gray-400 font-medium">Global System Log • 3 days ago</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-gray-50 text-center">
                        <a href="#" class="text-xs font-black text-gray-400 hover:text-gray-600 uppercase tracking-widest block py-2">View Full Log</a>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>

<script>
    function previewLogo(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById('agency_logo_preview');
                if (preview) {
                    preview.src = e.target.result;
                } else {
                    var container = document.getElementById('agency_logo_preview_container');
                    if (container) {
                        var img = document.createElement('img');
                        img.id = 'agency_logo_preview';
                        img.src = e.target.result;
                        img.className = 'max-h-28 object-contain rounded-lg';
                        container.parentNode.replaceChild(img, container);
                    }
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewFavicon(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('favicon_preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@else
<div class="space-y-8 pb-12">

    <!-- 6 Settings Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6">
        <!-- Card 1: General -->
        <a href="{{ url('admin/settings?tab=general') }}" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center group">
            <div class="w-12 h-12 rounded-full bg-[#FFF4CE] flex items-center justify-center text-[#E85D26] mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="settings" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2">General</h3>
            <p class="text-xs text-gray-400 font-medium leading-relaxed max-w-[200px]">
                Core platform identification and global metadata.
            </p>
        </a>

        <!-- Card 2: Preference -->
        <a href="{{ url('admin/settings/preferences') }}" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center group">
            <div class="w-12 h-12 rounded-full bg-[#FFE4E6] flex items-center justify-center text-[#E11D48] mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="sliders" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2">Preference</h3>
            <p class="text-xs text-gray-400 font-medium leading-relaxed max-w-[200px]">
                Localization, timezone, and default unit behavior.
            </p>
        </a>

        <!-- Card 3: Mail Setup -->
        <a href="{{ url('admin/settings/mail-setup') }}" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center group">
            <div class="w-12 h-12 rounded-full bg-[#E0F2FE] flex items-center justify-center text-[#0284C7] mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="mail" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2">Mail Setup</h3>
            <p class="text-xs text-gray-400 font-medium leading-relaxed max-w-[200px]">
                SMTP configurations and delivery protocol routing.
            </p>
        </a>

        <!-- Card 4: Payment -->
        <a href="{{ url('admin/settings/payment-setup') }}" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center group">
            <div class="w-12 h-12 rounded-full bg-[#DCFCE7] flex items-center justify-center text-[#16A34A] mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="credit-card" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2">Payment</h3>
            <p class="text-xs text-gray-400 font-medium leading-relaxed max-w-[200px]">
                Gateway integration and currency management.
            </p>
        </a>

        <!-- Card 5: Whatsapp Template -->
        <a href="{{ url('admin/settings/whatsapp-template') }}" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center group">
            <div class="w-12 h-12 rounded-full bg-[#ECE9FE] flex items-center justify-center text-[#4F46E5] mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="message-square" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2 group-hover:text-[#4F46E5] transition-colors">Whatsapp Template</h3>
            <p class="text-xs text-gray-400 font-medium leading-relaxed max-w-[200px]">
                Direct mobile messaging and alert structures.
            </p>
        </a>

        <!-- Card 6: Email Template -->
        <a href="{{ url('admin/settings/email-template') }}" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center group">
            <div class="w-12 h-12 rounded-full bg-[#FCE7F3] flex items-center justify-center text-[#DB2777] mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="file-text" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2 group-hover:text-[#DB2777] transition-colors">Email Template</h3>
            <p class="text-xs text-gray-400 font-medium leading-relaxed max-w-[200px]">
                Rich HTML layouts for user lifecycle notifications.
            </p>
        </a>

        <!-- Card 7: Package Reminder -->
        <a href="{{ url('admin/settings/package-reminder') }}" class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col items-center text-center group">
            <div class="w-12 h-12 rounded-full bg-[#FEF3C7] flex items-center justify-center text-[#D97706] mb-4 group-hover:scale-110 transition-transform">
                <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2 group-hover:text-[#D97706] transition-colors">Package Reminder</h3>
            <p class="text-xs text-gray-400 font-medium leading-relaxed max-w-[200px]">
                Package validity reminder mail configuration.
            </p>
        </a>

    </div>

    <!-- Bottom Status and Activity Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pt-4">
        <!-- API Health Status -->
        <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 flex flex-col justify-between">
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-gray-900">API Health Status</h3>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-black uppercase border border-green-200 bg-green-50 text-green-700">
                    <span class="inline-block w-2 h-2 rounded-full bg-green-600 animate-pulse"></span>
                    System Operational
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-gray-50">
                <div>
                    <span class="text-xs font-bold text-gray-400 block mb-1">Latency</span>
                    <span class="text-2xl font-black text-gray-900">124ms</span>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-400 block mb-1">Uptime</span>
                    <span class="text-2xl font-black text-gray-900">99.98%</span>
                </div>
            </div>
        </div>

        <!-- Admin Activity -->
        <div class="rounded-[32px] p-8 shadow-md flex flex-col justify-between text-white" style="background-color: #B23B06 !important;">
            <div class="space-y-3">
                <h3 class="text-lg font-bold">Admin Activity</h3>
                <p class="text-sm font-medium text-white/80 leading-relaxed">
                    Last modification: Homepage Banner updated by Super Admin 2 hours ago.
                </p>
            </div>
            
            <div class="mt-6 pt-6 flex justify-start">
                <a href="#" class="bg-white text-[#B23B06] px-5 py-2.5 rounded-full text-xs font-bold shadow-sm hover:shadow-md hover:bg-gray-50 transition-all">
                    View Audit Logs
                </a>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
