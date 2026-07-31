@extends('layouts.admin')

@section('admin_title')
    {{ request('tab') === 'general' ? 'General Settings' : 'Settings Hub' }}
@endsection

@section('content')
@if(request('tab') === 'general')
<div class="space-y-8 pb-12">
@php
    $phoneVal = $settings['contact_phone'] ?? '';
    $countryCodeVal = $settings['contact_country_code'] ?? '';
    if (empty($countryCodeVal) && str_starts_with($phoneVal, '+')) {
        $parts = explode(' ', $phoneVal, 2);
        if (count($parts) === 2) {
            $countryCodeVal = $parts[0];
            $phoneVal = $parts[1];
        }
    }
    $phoneVal = preg_replace('/[^0-9]/', '', $phoneVal);
@endphp


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
                                pattern="[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$"
                                title="Please enter a valid email address (e.g. name@example.com)"
                                class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl px-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Contact Phone</label>
                            <div class="flex gap-2">
                                <select name="contact_country_code" id="contact_country_code" class="bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl px-3 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200 w-28" onchange="updatePhoneValidation()">
                                    <option value="+91" data-len="10" {{ $countryCodeVal === '+91' ? 'selected' : '' }}>+91 (IN)</option>
                                    <option value="+1" data-len="10" {{ $countryCodeVal === '+1' ? 'selected' : '' }}>+1 (US)</option>
                                    <option value="+44" data-len="10" {{ $countryCodeVal === '+44' ? 'selected' : '' }}>+44 (UK)</option>
                                    <option value="+971" data-len="9" {{ $countryCodeVal === '+971' ? 'selected' : '' }}>+971 (AE)</option>
                                    <option value="+62" data-len="11" {{ $countryCodeVal === '+62' ? 'selected' : '' }}>+62 (ID)</option>
                                    <option value="+60" data-len="9" {{ $countryCodeVal === '+60' ? 'selected' : '' }}>+60 (MY)</option>
                                    <option value="+65" data-len="8" {{ $countryCodeVal === '+65' ? 'selected' : '' }}>+65 (SG)</option>
                                </select>
                                <input type="tel" id="contact_phone_input" name="contact_phone" value="{{ $phoneVal }}" placeholder="10-digit number" 
                                    class="flex-1 bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl px-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200" required
                                    oninput="this.value=this.value.replace(/[^0-9]/g,''); validatePhone(this)">
                            </div>
                            <p id="phone_error" class="text-xs text-red-500 font-semibold hidden">Please enter a valid phone number for the selected country code.</p>
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
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" id="cgst_enabled" name="cgst_enabled" value="1" {{ ($settings['cgst_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                                        class="w-4 h-4 rounded accent-[#B23B06] cursor-pointer">
                                    <label for="cgst_enabled" class="text-xs font-black text-gray-800 cursor-pointer">CGST (%)</label>
                                </div>
                                <input type="number" name="cgst" value="{{ $settings['cgst'] ?? '9' }}" min="0" max="100" step="0.01"
                                    class="bg-transparent border-0 text-right font-black text-xl text-[#B23B06] w-20 focus:ring-0 p-0">
                            </div>
                            <div class="flex items-center justify-between bg-[#F5F4F2] rounded-xl px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" id="sgst_enabled" name="sgst_enabled" value="1" {{ ($settings['sgst_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                                        class="w-4 h-4 rounded accent-[#B23B06] cursor-pointer">
                                    <label for="sgst_enabled" class="text-xs font-black text-gray-800 cursor-pointer">SGST (%)</label>
                                </div>
                                <input type="number" name="sgst" value="{{ $settings['sgst'] ?? '9' }}" min="0" max="100" step="0.01"
                                    class="bg-transparent border-0 text-right font-black text-xl text-[#B23B06] w-20 focus:ring-0 p-0">
                            </div>
                            <div class="flex items-center justify-between bg-[#F5F4F2] rounded-xl px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" id="igst_enabled" name="igst_enabled" value="1" {{ ($settings['igst_enabled'] ?? '0') == '1' ? 'checked' : '' }}
                                        class="w-4 h-4 rounded accent-[#B23B06] cursor-pointer">
                                    <label for="igst_enabled" class="text-xs font-black text-gray-800 cursor-pointer">IGST (%)</label>
                                </div>
                                <input type="number" name="igst" value="{{ $settings['igst'] ?? '18' }}" min="0" max="100" step="0.01"
                                    class="bg-transparent border-0 text-right font-black text-xl text-[#B23B06] w-20 focus:ring-0 p-0">
                            </div>
                            <p class="text-[10px] text-gray-400 font-semibold pt-1">Check the taxes you want to apply on invoices. Unchecked taxes will not appear on generated invoices.</p>
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

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Prefix</label>
                            <input type="text" id="invoice_prefix_input" name="invoice_prefix" value="{{ $settings['invoice_prefix'] ?? 'INV-' }}" 
                                class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl px-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200"
                                onkeyup="document.getElementById('invoice_preview_prefix').textContent = this.value">
                        </div>
                        <div class="space-y-2 flex flex-col justify-center">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-wider block">Format Example</label>
                            <div class="text-sm font-semibold text-gray-700 bg-gray-50 px-4 py-3.5 rounded-xl border border-gray-100">
                                <span id="invoice_preview_prefix" class="text-[#B23B06]">{{ $settings['invoice_prefix'] ?? 'INV-' }}</span>{{ date('Y') }}-01
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 font-medium">
                        The invoice format is automatically set to <strong>PREFIX + YEAR + SEQUENCE</strong>. The sequence starts at 01 and automatically resets every new calendar year.
                    </p>
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
                            <button type="button" onclick="openPasswordModal()" class="text-sm font-black text-[#B23B06] hover:underline px-4 py-2 focus:outline-none">Update</button>
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
                            <div class="absolute left-4 flex items-center justify-center w-5 h-5" style="color: #1877F2;">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </div>
                            <input type="text" name="facebook_url" value="{{ $settings['facebook_url'] ?? 'facebook.com/explorerglobal' }}" 
                                class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl pl-12 pr-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200" placeholder="facebook.com/yourpage">
                        </div>

                        <div class="relative flex items-center">
                            <div class="absolute left-4 flex items-center justify-center w-5 h-5">
                                <svg class="w-5 h-5" fill="url(#ig-grad)" viewBox="0 0 24 24">
                                    <defs>
                                        <linearGradient id="ig-grad" x1="0" y1="1" x2="1" y2="0">
                                            <stop offset="0%" stop-color="#f09433"/>
                                            <stop offset="25%" stop-color="#e6683c"/>
                                            <stop offset="50%" stop-color="#dc2743"/>
                                            <stop offset="75%" stop-color="#cc2366"/>
                                            <stop offset="100%" stop-color="#bc1888"/>
                                        </linearGradient>
                                    </defs>
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </div>
                            <input type="text" name="instagram_url" value="{{ $settings['instagram_url'] ?? 'instagram.com/explorerglobal' }}" 
                                class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl pl-12 pr-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200" placeholder="instagram.com/yourhandle">
                        </div>

                        <div class="relative flex items-center">
                            <div class="absolute left-4 flex items-center justify-center w-5 h-5" style="color: #0A66C2;">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </div>
                            <input type="text" name="linkedin_url" value="{{ $settings['linkedin_url'] ?? 'linkedin.com/company/explorerglobal' }}" 
                                class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl pl-12 pr-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200" placeholder="linkedin.com/company/yourpage">
                        </div>

                        <div class="relative flex items-center">
                            <div class="absolute left-4 flex items-center justify-center w-5 h-5" style="color: #000;">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.253 5.622 5.91-5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </div>
                            <input type="text" name="twitter_url" value="{{ $settings['twitter_url'] ?? 'twitter.com/explorertravel' }}" 
                                class="w-full bg-[#F5F4F2] border-0 text-gray-800 text-sm font-semibold rounded-xl pl-12 pr-4 py-3.5 focus:bg-white focus:ring-2 focus:ring-[#B23B06]/20 transition-all duration-200" placeholder="x.com/yourhandle">
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
                        @forelse($activities ?? [] as $activity)
                            @php
                                $icon = 'activity';
                                $colorClass = 'bg-gray-100 text-gray-500';
                                $actLower = strtolower($activity->activity);
                                if (str_contains($actLower, 'login')) {
                                    $icon = 'log-in';
                                    $colorClass = 'bg-amber-50 text-amber-500';
                                } elseif (str_contains($actLower, 'settings') || str_contains($actLower, 'preference')) {
                                    $icon = 'sliders';
                                    $colorClass = 'bg-blue-50 text-blue-500';
                                } elseif (str_contains($actLower, 'password') || str_contains($actLower, 'security')) {
                                    $icon = 'shield-check';
                                    $colorClass = 'bg-red-50 text-red-500';
                                } elseif (str_contains($actLower, 'profile')) {
                                    $icon = 'user';
                                    $colorClass = 'bg-green-50 text-green-500';
                                }
                            @endphp
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full {{ $colorClass }} flex items-center justify-center shrink-0">
                                    <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
                                </div>
                                <div class="space-y-0.5">
                                    <h4 class="text-sm font-bold text-gray-900">{{ $activity->activity }}</h4>
                                    <p class="text-[11px] text-gray-400 font-semibold">{{ $activity->details }}</p>
                                    <p class="text-[10px] text-gray-400 font-medium">{{ $activity->user_name }} • {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-gray-400 text-xs font-semibold">
                                No activity logs recorded yet.
                            </div>
                        @endforelse
                    </div>

                    <div class="pt-2 border-t border-gray-50 text-center">
                        <a href="{{ url('admin/settings/activity-logs') }}" class="text-xs font-black text-gray-400 hover:text-gray-600 uppercase tracking-widest block py-2">View Full Log</a>
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

    // Phone validation based on country code
    const phoneLengths = { '+91': 10, '+1': 10, '+44': 10, '+971': 9, '+62': 11, '+60': 9, '+65': 8 };
    function validatePhone(input) {
        const codeSelect = document.getElementById('contact_country_code');
        const errorEl = document.getElementById('phone_error');
        if (!codeSelect || !errorEl) return;
        const required = phoneLengths[codeSelect.value] || 10;
        if (input.value.length > required) input.value = input.value.substring(0, required);
        if (input.value.length > 0 && input.value.length !== required) {
            errorEl.textContent = `Phone number must be exactly ${required} digits for ${codeSelect.value}.`;
            errorEl.classList.remove('hidden');
            input.setCustomValidity('Invalid length');
        } else {
            errorEl.classList.add('hidden');
            input.setCustomValidity('');
        }
    }
    function updatePhoneValidation() {
        const input = document.getElementById('contact_phone_input');
        if (input) validatePhone(input);
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
