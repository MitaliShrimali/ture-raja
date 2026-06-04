@extends('layouts.admin')

@section('admin_title', 'Add New Package')

@section('content')
<div class="space-y-8" x-data="{ 
    previewUrl: '', 
    galleryPreviews: [],
    brochureName: '',
    showInclusions: true,
    showExclusions: true,
    days: [
        { title: 'Arrival & Check-in', desc: 'Arrive at destination, transfer to your hotel and relax.' },
        { title: 'City Exploration', desc: 'Full day guided tour exploring major landmarks.' },
        { title: 'Leisure & Departure', desc: 'Free time for shopping before transferring to the airport.' }
    ],
    addDay() {
        this.days.push({ title: 'New Day Tour', desc: 'Activities details...' });
    },
    removeDay(index) {
        if (this.days.length > 1) {
            this.days.splice(index, 1);
        }
    },
    handleGalleryChange(event) {
        this.galleryPreviews = [];
        const files = event.target.files;
        for (let i = 0; i < files.length; i++) {
            this.galleryPreviews.push(URL.createObjectURL(files[i]));
        }
    }
}">
    <div class="flex items-center gap-4">
        <a href="{{ url('/admin/packages') }}" class="p-3 bg-white hover:bg-gray-50 border border-border-soft rounded-2xl transition-all shadow-sm text-muted-text hover:text-primary">
            <i data-lucide="arrow-left" size="20"></i>
        </a>
        <div>
            <h2 class="font-black text-foreground tracking-tight">Create Travel Package</h2>
            <p class="text-muted-text font-medium text-sm">Add a new global destination package listing to the platform.</p>
        </div>
    </div>

    <form action="{{ url('/admin/packages/store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Left & Middle: Main Form details -->
            <div class="lg:col-span-2 space-y-8">
                <!-- General Info -->
                <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                            <i data-lucide="building-2" size="22"></i>
                        </div>
                        <h3 class="text-2xl font-black text-foreground">General Information</h3>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Package Title<span class="text-primary">*</span></label>
                        <input required type="text" name="title" placeholder="E.g. Bali Luxury Villa Escape" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Location<span class="text-primary">*</span></label>
                            <input required type="text" name="location" placeholder="E.g. Bali, Indonesia" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Duration<span class="text-primary">*</span></label>
                            <input required type="text" name="duration" placeholder="E.g. 5 Days, 4 Nights" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Assigned Travel Agent</label>
                            <select name="agent" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm">
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->name }}">{{ $agent->name }} ({{ $agent->region }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Category</label>
                            <select name="category" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm">
                                <option value="Tropical">Tropical</option>
                                <option value="Mountains">Mountains</option>
                                <option value="City">City</option>
                                <option value="Adventure">Adventure</option>
                                <option value="domestic">Domestic</option>
                                <option value="international">International</option>
                                <option value="religious">Religious</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Pricing & Inventory Section -->
                <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                            <i data-lucide="tag" size="22"></i>
                        </div>
                        <h3 class="text-2xl font-black text-foreground">Pricing & Inventory</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Price (INR)<span class="text-primary">*</span></label>
                            <input required type="number" name="price" placeholder="E.g. 1299" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Old Price (INR)</label>
                            <input type="number" name="old_price" placeholder="E.g. 1999" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Stock Slots Left</label>
                            <input type="text" name="stock" placeholder="E.g. 10 Left" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Group Size</label>
                            <input type="text" name="group_size" placeholder="E.g. 4-6 guest" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Promo Badge</label>
                            <input type="text" name="badge" placeholder="E.g. Bestseller" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm" />
                        </div>
                    </div>
                </div>

                <!-- Inclusions / Exclusions Section -->
                <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8">
                    <div class="flex items-center gap-6 pl-1 border-b border-border-soft pb-4">
                        <h3 class="text-2xl font-black text-foreground">Inclusions & Exclusions</h3>
                        <label class="flex items-center gap-2 text-xs font-bold text-gray-700 cursor-pointer ml-auto">
                            <input type="checkbox" name="show_included" x-model="showInclusions" class="rounded border-gray-300 text-primary focus:ring-primary/20" />
                            <span>Show Inclusions</span>
                        </label>
                        <label class="flex items-center gap-2 text-xs font-bold text-gray-700 cursor-pointer">
                            <input type="checkbox" name="show_excluded" x-model="showExclusions" class="rounded border-gray-300 text-primary focus:ring-primary/20" />
                            <span>Show Exclusions</span>
                        </label>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4" x-show="showInclusions">
                            <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">What's Included</label>
                            <div class="bg-[#F5F5F5] rounded-2xl p-6 space-y-3">
                                @foreach(['Hotel Stay', 'Daily Breakfast', 'Tour Guide', 'Transfers'] as $inc)
                                    <label class="flex items-center gap-3 cursor-pointer select-none">
                                        <input type="checkbox" name="included[]" value="{{ $inc }}" checked :disabled="!showInclusions" class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/20 transition-all cursor-pointer" />
                                        <span class="text-sm font-bold text-foreground">{{ $inc }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="space-y-4" x-show="showExclusions">
                            <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">What's Excluded</label>
                            <div class="bg-[#F5F5F5] rounded-2xl p-6 space-y-3">
                                @foreach(['Flights', 'Personal Expenses', 'Visa Fees'] as $exc)
                                    <label class="flex items-center gap-3 cursor-pointer select-none">
                                        <input type="checkbox" name="excluded[]" value="{{ $exc }}" checked :disabled="!showExclusions" class="w-5 h-5 rounded border-gray-300 text-primary focus:ring-primary/20 transition-all cursor-pointer" />
                                        <span class="text-sm font-bold text-foreground">{{ $exc }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Itinerary Section -->
                <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8">
                    <div class="flex items-center justify-between border-b border-border-soft pb-4">
                        <h3 class="text-2xl font-black text-foreground">Tour Itinerary</h3>
                        <button type="button" @click="addDay()" class="text-xs font-black text-primary hover:text-primary-hover flex items-center gap-1">
                            <i data-lucide="plus" size="14"></i> Add Day
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <template x-for="(day, index) in days" :key="index">
                            <div class="bg-gray-50/50 p-6 rounded-3xl border border-gray-100 space-y-4 relative">
                                <button type="button" @click="removeDay(index)" class="absolute top-4 right-4 text-muted-text hover:text-red-500" x-show="days.length > 1">
                                    <i data-lucide="trash-2" size="16"></i>
                                </button>
                                <div class="flex items-center gap-4">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center font-black text-xs shrink-0" x-text="'D' + (index + 1)"></div>
                                    <input required type="text" name="itinerary_titles[]" x-model="day.title" placeholder="Day Title (e.g. Arrival & Check-in)" class="w-full bg-white border border-gray-100 rounded-xl py-3 px-4 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-sm text-foreground shadow-sm" />
                                </div>
                                <textarea rows="2" name="itinerary_descriptions[]" x-model="day.desc" placeholder="Day description activities..." class="w-full bg-white border border-gray-100 rounded-xl py-3 px-4 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-sm text-foreground shadow-sm resize-none"></textarea>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Review & Categorization Section -->
                <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                            <i data-lucide="star" size="22"></i>
                        </div>
                        <h3 class="text-2xl font-black text-foreground">Review & Ratings</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Rating</label>
                            <input type="text" name="rating" placeholder="E.g. 4.8" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Reviews Count</label>
                            <input type="number" name="reviews" placeholder="E.g. 10" class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm" />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Image URL (Fallback option)</label>
                        <input type="text" name="image" placeholder="E.g. https://images.unsplash.com/..." class="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm" />
                    </div>
                </div>
            </div>

            <!-- Right Sidebar Panel: Image Uploads & Brochure -->
            <div class="space-y-8">
                <!-- Main Package Profile Image (Dashed Box Upload Area) -->
                <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-8 flex flex-col items-center text-center space-y-4">
                    <h4 class="text-xs font-black text-muted-text uppercase tracking-widest pl-2 self-start">Main Package Image</h4>
                    
                    <div class="relative mt-2">
                        <!-- Dashed Box -->
                        <div class="w-32 h-32 rounded-[28px] border-2 border-dashed border-gray-200 bg-gray-50/50 flex items-center justify-center overflow-hidden cursor-pointer hover:bg-gray-50 transition-all" @click="$refs.mainImageInput.click()">
                            <template x-if="previewUrl">
                                <img :src="previewUrl" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!previewUrl">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </template>
                        </div>
                        
                        <!-- Floating Upload Button -->
                        <button type="button" @click="$refs.mainImageInput.click()" class="absolute -bottom-1 -right-1 w-10 h-10 rounded-full bg-[#af3a03] hover:bg-[#8f2f02] text-white flex items-center justify-center shadow-lg transition-all border-2 border-white">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                        </button>
                    </div>

                    <input required type="file" name="image_file" x-ref="mainImageInput" class="hidden" accept="image/*" @change="previewUrl = URL.createObjectURL($event.target.files[0])" />

                    <div class="space-y-1">
                        <p class="text-sm font-bold text-foreground">Package Featured Pic</p>
                        <p class="text-[11px] text-muted-text font-bold leading-relaxed max-w-[200px]">
                            Upload a high-resolution preview photo. Min 500x500px suggested.
                        </p>
                    </div>
                </div>

                <!-- Gallery Upload Box -->
                <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-8 space-y-4">
                    <h4 class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Gallery Uploads</h4>
                    <div class="w-full bg-[#F5F5F5] rounded-2xl p-6 border-2 border-dashed border-gray-200 text-center cursor-pointer hover:bg-gray-100 transition-all" @click="$refs.galleryInput.click()">
                        <i data-lucide="images" class="mx-auto text-gray-400 mb-2" size="28"></i>
                        <span class="text-xs font-bold text-muted-text">Select Multiple Images</span>
                        <input type="file" name="gallery_files[]" x-ref="galleryInput" multiple class="hidden" @change="handleGalleryChange($event)" />
                    </div>
                    <div class="grid grid-cols-3 gap-2" x-show="galleryPreviews.length > 0">
                        <template x-for="url in galleryPreviews">
                            <div class="h-16 rounded-xl overflow-hidden border border-gray-200">
                                <img :src="url" class="w-full h-full object-cover" />
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Brochure Upload Box -->
                <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-8 space-y-4">
                    <h4 class="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Brochure PDF</h4>
                    <div class="w-full bg-[#F5F5F5] rounded-2xl p-6 border-2 border-dashed border-gray-200 text-center cursor-pointer hover:bg-gray-100 transition-all" @click="$refs.brochureInput.click()">
                        <i data-lucide="file-text" class="mx-auto text-gray-400 mb-2" size="28"></i>
                        <span class="text-xs font-bold text-muted-text" x-text="brochureName ? brochureName : 'Select Brochure File'">Select Brochure File</span>
                        <input type="file" name="brochure_file" x-ref="brochureInput" accept=".pdf" class="hidden" @change="brochureName = $event.target.files[0] ? $event.target.files[0].name : ''" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-4 pt-8 border-t border-border-soft mt-8">
            <a href="{{ url('/admin/packages') }}" class="px-8 py-4 bg-gray-100 hover:bg-gray-200 rounded-2xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</a>
            <button type="submit" class="px-8 py-4 bg-primary text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Publish Package</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endsection
