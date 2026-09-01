<?php
$file = 'c:/Users/tusha/Downloads/Tour_raja/resources/views/admin/plans-create.blade.php';
$content = file_get_contents($file);

$html = <<<'HTML'
<div class="space-y-6">
    <div class="bg-white rounded-[32px] border border-border-soft overflow-hidden shadow-sm">
        <div class="p-6 space-y-4">
            <div class="space-y-1 mb-6">
                <h4 class="text-lg font-black text-foreground uppercase tracking-widest">Plan Configuration</h4>
                <p class="text-xs text-muted-text font-medium">Set the limits and features for this plan.</p>
            </div>

            <!-- List Layout -->
            <div class="space-y-2">
                <!-- Package Name -->
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-700">Package Name</span>
                    <input required type="text" name="name" x-model="name" placeholder="E.g. Premium" class="w-48 bg-[#F8F9FA] border border-gray-200 rounded-lg py-2 px-3 outline-none text-xs text-right font-semibold text-foreground focus:border-primary" />
                </div>

                <!-- Suggested Price -->
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-700">Suggested Price</span>
                    <input required type="number" step="0.01" name="price" x-model="price" placeholder="149.00" class="w-48 bg-[#F8F9FA] border border-gray-200 rounded-lg py-2 px-3 outline-none text-xs text-right font-semibold text-foreground focus:border-primary" />
                </div>
                
                <!-- GST -->
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-700">GST (%)</span>
                    <input type="number" step="0.01" name="gst" x-model="gst" placeholder="18" class="w-48 bg-[#F8F9FA] border border-gray-200 rounded-lg py-2 px-3 outline-none text-xs text-right font-semibold text-foreground focus:border-primary" />
                </div>
                
                <!-- Duration -->
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-700">Package Expiry (Duration)</span>
                    <div x-data="{ open: false, selected: '30 Days' }" class="relative w-48 text-left">
                        <input type="hidden" name="duration" :value="selected">
                        <button type="button" @click="open = !open" class="w-full flex items-center justify-between bg-[#F8F9FA] border border-gray-200 rounded-lg py-2 px-3 outline-none text-xs font-semibold text-foreground focus:border-primary">
                            <span x-text="selected"></span>
                            <i data-lucide="chevron-down" size="14"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" style="display: none;" class="absolute right-0 z-50 mt-1 w-full max-h-48 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-xl">
                            @for($i=1; $i<=365; $i++)
                                <div @click="selected = '{{ $i }} {{ $i > 1 ? 'Days' : 'Day' }}'; open = false" class="px-3 py-2 text-xs font-semibold cursor-pointer hover:bg-orange-50 hover:text-primary transition-colors">
                                    {{ $i }} {{ $i > 1 ? 'Days' : 'Day' }}
                                </div>
                            @endfor
                            <div @click="selected = 'Unlimited'; open = false" class="px-3 py-2 text-xs font-semibold cursor-pointer hover:bg-orange-50 hover:text-primary transition-colors">Unlimited</div>
                        </div>
                    </div>
                </div>

                @php
                    $features = [
                        ['key' => 'feat_business_profile', 'label' => 'Business Profile', 'type' => 'boolean'],
                        ['key' => 'package_limit', 'label' => 'Package Listings', 'type' => 'numeric_dropdown'],
                        ['key' => 'feat_domestic_packages', 'label' => 'Domestic Packages', 'type' => 'boolean'],
                        ['key' => 'feat_international_packages', 'label' => 'International Packages', 'type' => 'boolean'],
                        ['key' => 'limit_package_photos', 'label' => 'Package Photos', 'type' => 'numeric_dropdown'],
                        ['key' => 'feat_hotel_options', 'label' => 'Hotel Options', 'type' => 'boolean'],
                        ['key' => 'limit_gallery_images', 'label' => 'Add Gallery', 'type' => 'numeric_dropdown'],
                        ['key' => 'feat_theme_options', 'label' => 'Holiday / Theme Options', 'type' => 'boolean'],
                        ['key' => 'feat_hide_package_price', 'label' => 'Hide Package Price', 'type' => 'boolean'],
                        ['key' => 'feat_website_on_profile', 'label' => 'Website on Profile', 'type' => 'boolean'],
                        ['key' => 'feat_email_on_profile', 'label' => 'Email on Profile', 'type' => 'boolean'],
                        ['key' => 'feat_whatsapp_on_profile', 'label' => 'WhatsApp on Profile', 'type' => 'boolean'],
                        ['key' => 'feat_package_boosting', 'label' => 'Package Boosting', 'type' => 'boolean'],
                        ['key' => 'feat_featured_destination', 'label' => 'Featured Destination', 'type' => 'boolean'],
                        ['key' => 'feat_trusted_seller', 'label' => 'Trusted Seller Badge', 'type' => 'boolean'],
                        ['key' => 'feat_reviews_ratings', 'label' => 'Reviews & Ratings', 'type' => 'boolean'],
                        ['key' => 'feat_profile_analytics', 'label' => 'Profile Analytics', 'type' => 'boolean'],
                        ['key' => 'limit_branches', 'label' => 'Multiple Branches', 'type' => 'numeric_dropdown'],
                    ];
                @endphp

                @foreach($features as $feat)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-xs font-bold text-gray-700">{{ $feat['label'] }}</span>
                        
                        @if($feat['type'] === 'boolean')
                            <div class="flex items-center gap-2" x-data="{ active: true }">
                                <input type="hidden" name="permissions[{{ $feat['key'] }}]" :value="active ? '1' : '0'">
                                <button type="button" @click="active = true" :class="active ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400 hover:bg-gray-200'" class="w-8 h-8 rounded-full flex items-center justify-center transition-all cursor-pointer border border-transparent">
                                    <i data-lucide="check" size="14" stroke-width="3"></i>
                                </button>
                                <button type="button" @click="active = false" :class="!active ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-400 hover:bg-gray-200'" class="w-8 h-8 rounded-full flex items-center justify-center transition-all cursor-pointer border border-transparent">
                                    <i data-lucide="x" size="14" stroke-width="3"></i>
                                </button>
                            </div>
                        @elseif($feat['type'] === 'numeric_dropdown')
                            @php
                                $inputName = $feat['key'] === 'package_limit' ? 'package_limit' : 'permissions[' . $feat['key'] . ']';
                            @endphp
                            <div x-data="{ open: false, selected: '0', val: 0 }" class="relative w-48 text-left">
                                <input type="hidden" name="{{ $inputName }}" :value="val">
                                <button type="button" @click="open = !open" class="w-full flex items-center justify-between bg-[#F8F9FA] border border-gray-200 rounded-lg py-2 px-3 outline-none text-xs font-semibold text-foreground focus:border-primary">
                                    <span x-text="selected === '0' ? 'Unlimited' : selected"></span>
                                    <i data-lucide="chevron-down" size="14"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" style="display: none;" class="absolute right-0 z-50 mt-1 w-full max-h-48 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-xl">
                                    @for($i=1; $i<=100; $i++)
                                        <div @click="selected = '{{ $i }}'; val = {{ $i }}; open = false" class="px-3 py-2 text-xs font-semibold cursor-pointer hover:bg-orange-50 hover:text-primary transition-colors">
                                            {{ $i }}
                                        </div>
                                    @endfor
                                    <div @click="selected = '0'; val = 0; open = false" class="px-3 py-2 text-xs font-semibold cursor-pointer hover:bg-orange-50 hover:text-primary transition-colors">Unlimited</div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
HTML;

$content = preg_replace('/<div class="space-y-6">.*?<!-- Actions -->/s', $html . "\n\n            <!-- Actions -->", $content);
file_put_contents($file, $content);

$file2 = 'c:/Users/tusha/Downloads/Tour_raja/resources/views/admin/plans-edit.blade.php';
$content2 = file_get_contents($file2);

$html2 = <<<'HTML'
<div class="space-y-6">
    <div class="bg-white rounded-[32px] border border-border-soft overflow-hidden shadow-sm">
        <div class="p-6 space-y-4">
            <div class="space-y-1 mb-6">
                <h4 class="text-lg font-black text-foreground uppercase tracking-widest">Plan Configuration</h4>
                <p class="text-xs text-muted-text font-medium">Set the limits and features for this plan.</p>
            </div>

            <!-- List Layout -->
            <div class="space-y-2">
                @php
                    $currentPermissions = \App\Models\PlanPermission::where('plan_id', $plan->id)->get()->keyBy('permission_key');
                @endphp

                <!-- Package Name -->
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-700">Package Name</span>
                    <input required type="text" name="name" x-model="name" value="{{ $plan->name }}" placeholder="E.g. Premium" class="w-48 bg-[#F8F9FA] border border-gray-200 rounded-lg py-2 px-3 outline-none text-xs text-right font-semibold text-foreground focus:border-primary" />
                </div>

                <!-- Suggested Price -->
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-700">Suggested Price</span>
                    <input required type="number" step="0.01" name="price" x-model="price" value="{{ $plan->price }}" placeholder="149.00" class="w-48 bg-[#F8F9FA] border border-gray-200 rounded-lg py-2 px-3 outline-none text-xs text-right font-semibold text-foreground focus:border-primary" />
                </div>
                
                <!-- GST -->
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-700">GST (%)</span>
                    <input type="number" step="0.01" name="gst" x-model="gst" value="{{ $plan->gst ?? 18 }}" placeholder="18" class="w-48 bg-[#F8F9FA] border border-gray-200 rounded-lg py-2 px-3 outline-none text-xs text-right font-semibold text-foreground focus:border-primary" />
                </div>
                
                <!-- Duration -->
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-700">Package Expiry (Duration)</span>
                    <div x-data="{ open: false, selected: '{{ $plan->duration ?? '30 Days' }}' }" class="relative w-48 text-left">
                        <input type="hidden" name="duration" :value="selected">
                        <button type="button" @click="open = !open" class="w-full flex items-center justify-between bg-[#F8F9FA] border border-gray-200 rounded-lg py-2 px-3 outline-none text-xs font-semibold text-foreground focus:border-primary">
                            <span x-text="selected"></span>
                            <i data-lucide="chevron-down" size="14"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" style="display: none;" class="absolute right-0 z-50 mt-1 w-full max-h-48 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-xl">
                            @for($i=1; $i<=365; $i++)
                                <div @click="selected = '{{ $i }} {{ $i > 1 ? 'Days' : 'Day' }}'; open = false" class="px-3 py-2 text-xs font-semibold cursor-pointer hover:bg-orange-50 hover:text-primary transition-colors">
                                    {{ $i }} {{ $i > 1 ? 'Days' : 'Day' }}
                                </div>
                            @endfor
                            <div @click="selected = 'Unlimited'; open = false" class="px-3 py-2 text-xs font-semibold cursor-pointer hover:bg-orange-50 hover:text-primary transition-colors">Unlimited</div>
                        </div>
                    </div>
                </div>

                @php
                    $features = [
                        ['key' => 'feat_business_profile', 'label' => 'Business Profile', 'type' => 'boolean'],
                        ['key' => 'package_limit', 'label' => 'Package Listings', 'type' => 'numeric_dropdown'],
                        ['key' => 'feat_domestic_packages', 'label' => 'Domestic Packages', 'type' => 'boolean'],
                        ['key' => 'feat_international_packages', 'label' => 'International Packages', 'type' => 'boolean'],
                        ['key' => 'limit_package_photos', 'label' => 'Package Photos', 'type' => 'numeric_dropdown'],
                        ['key' => 'feat_hotel_options', 'label' => 'Hotel Options', 'type' => 'boolean'],
                        ['key' => 'limit_gallery_images', 'label' => 'Add Gallery', 'type' => 'numeric_dropdown'],
                        ['key' => 'feat_theme_options', 'label' => 'Holiday / Theme Options', 'type' => 'boolean'],
                        ['key' => 'feat_hide_package_price', 'label' => 'Hide Package Price', 'type' => 'boolean'],
                        ['key' => 'feat_website_on_profile', 'label' => 'Website on Profile', 'type' => 'boolean'],
                        ['key' => 'feat_email_on_profile', 'label' => 'Email on Profile', 'type' => 'boolean'],
                        ['key' => 'feat_whatsapp_on_profile', 'label' => 'WhatsApp on Profile', 'type' => 'boolean'],
                        ['key' => 'feat_package_boosting', 'label' => 'Package Boosting', 'type' => 'boolean'],
                        ['key' => 'feat_featured_destination', 'label' => 'Featured Destination', 'type' => 'boolean'],
                        ['key' => 'feat_trusted_seller', 'label' => 'Trusted Seller Badge', 'type' => 'boolean'],
                        ['key' => 'feat_reviews_ratings', 'label' => 'Reviews & Ratings', 'type' => 'boolean'],
                        ['key' => 'feat_profile_analytics', 'label' => 'Profile Analytics', 'type' => 'boolean'],
                        ['key' => 'limit_branches', 'label' => 'Multiple Branches', 'type' => 'numeric_dropdown'],
                    ];
                @endphp

                @foreach($features as $feat)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100">
                        <span class="text-xs font-bold text-gray-700">{{ $feat['label'] }}</span>
                        
                        @if($feat['type'] === 'boolean')
                            @php
                                $isChecked = isset($currentPermissions[$feat['key']]) && $currentPermissions[$feat['key']]->boolean_value;
                            @endphp
                            <div class="flex items-center gap-2" x-data="{ active: {{ $isChecked ? 'true' : 'false' }} }">
                                <input type="hidden" name="permissions[{{ $feat['key'] }}]" :value="active ? '1' : '0'">
                                <button type="button" @click="active = true" :class="active ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400 hover:bg-gray-200'" class="w-8 h-8 rounded-full flex items-center justify-center transition-all cursor-pointer border border-transparent">
                                    <i data-lucide="check" size="14" stroke-width="3"></i>
                                </button>
                                <button type="button" @click="active = false" :class="!active ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-400 hover:bg-gray-200'" class="w-8 h-8 rounded-full flex items-center justify-center transition-all cursor-pointer border border-transparent">
                                    <i data-lucide="x" size="14" stroke-width="3"></i>
                                </button>
                            </div>
                        @elseif($feat['type'] === 'numeric_dropdown')
                            @php
                                $limitVal = '';
                                if ($feat['key'] === 'package_limit') {
                                    $limitVal = $plan->package_limit ?? 0;
                                } else {
                                    $limitVal = isset($currentPermissions[$feat['key']]) ? $currentPermissions[$feat['key']]->limit_value : 0;
                                }
                                $inputName = $feat['key'] === 'package_limit' ? 'package_limit' : 'permissions[' . $feat['key'] . ']';
                            @endphp
                            <div x-data="{ open: false, selected: '{{ $limitVal }}', val: {{ $limitVal }} }" class="relative w-48 text-left">
                                <input type="hidden" name="{{ $inputName }}" :value="val">
                                <button type="button" @click="open = !open" class="w-full flex items-center justify-between bg-[#F8F9FA] border border-gray-200 rounded-lg py-2 px-3 outline-none text-xs font-semibold text-foreground focus:border-primary">
                                    <span x-text="selected === '0' || val == 0 ? 'Unlimited' : selected"></span>
                                    <i data-lucide="chevron-down" size="14"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" style="display: none;" class="absolute right-0 z-50 mt-1 w-full max-h-48 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-xl">
                                    @for($i=1; $i<=100; $i++)
                                        <div @click="selected = '{{ $i }}'; val = {{ $i }}; open = false" class="px-3 py-2 text-xs font-semibold cursor-pointer hover:bg-orange-50 hover:text-primary transition-colors">
                                            {{ $i }}
                                        </div>
                                    @endfor
                                    <div @click="selected = 'Unlimited'; val = 0; open = false" class="px-3 py-2 text-xs font-semibold cursor-pointer hover:bg-orange-50 hover:text-primary transition-colors">Unlimited</div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
HTML;

$content2 = preg_replace('/<div class="space-y-6">.*?<!-- Actions -->/s', $html2 . "\n\n            <!-- Actions -->", $content2);
file_put_contents($file2, $content2);

echo "Done";
