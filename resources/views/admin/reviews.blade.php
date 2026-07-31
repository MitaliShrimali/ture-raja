@extends('layouts.admin')
@section('title', 'Manage Reviews')

@section('content')
<div x-data="{ showAddModal: false, showEditModal: false, editReview: {} }">
    <div class="flex items-center justify-between mb-8">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-foreground font-heading">Client Reviews</h1>
            <p class="text-sm font-medium text-muted-text">Manage what our clients say across the platform.</p>
        </div>
        <button @click="showAddModal = true" class="px-6 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest flex items-center gap-2 shadow-xl shadow-primary/20 hover:bg-primary-hover hover:-translate-y-0.5 transition-all">
            <i data-lucide="plus" size="16"></i> Add Review
        </button>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-3xl p-6 border border-border-soft shadow-sm flex items-center gap-6 relative overflow-hidden">
            <div class="absolute -right-6 -top-6 w-32 h-32 bg-primary/5 rounded-full blur-2xl"></div>
            <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shrink-0 relative z-10">
                <i data-lucide="message-square" size="24"></i>
            </div>
            <div class="relative z-10">
                <h3 class="text-3xl font-black text-foreground">{{ $reviews->total() }}</h3>
                <p class="text-xs font-bold text-muted-text uppercase tracking-wider mt-1">Total Reviews</p>
            </div>
        </div>
    </div>

    <!-- Active Reviews List -->
    <div class="bg-white rounded-3xl border border-border-soft shadow-sm overflow-hidden">
        <div class="p-6 border-b border-border-soft flex items-center justify-between bg-gray-50/50">
            <h2 class="text-sm font-black text-foreground uppercase tracking-widest">Active Reviews</h2>
        </div>
        <div class="p-6">
            @if(count($reviews) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($reviews as $review)
                    <div class="bg-white rounded-3xl p-6 border border-border-soft shadow-sm hover:shadow-xl hover:border-primary/20 transition-all group flex flex-col h-full relative">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-full overflow-hidden shrink-0 border-2 border-gray-100">
                                <img src="{{ Str::startsWith($review->image, 'http') ? $review->image : asset($review->image) }}" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-foreground">{{ $review->name }}</h3>
                                <p class="text-[10px] font-bold text-muted-text uppercase">{{ $review->location }}</p>
                            </div>
                            <div class="ml-auto">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $review->status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $review->status }}
                                </span>
                            </div>
                        </div>

                        <div class="flex gap-1 mb-3 text-orange-400">
                            @for($i=0; $i<$review->rating; $i++)
                                <i data-lucide="star" class="fill-current" size="14"></i>
                            @endfor
                            @for($i=$review->rating; $i<5; $i++)
                                <i data-lucide="star" class="text-gray-200" size="14"></i>
                            @endfor
                        </div>

                        <p class="text-sm text-gray-600 italic flex-grow mb-6 break-words">"{{ $review->text }}"</p>

                        <div class="flex items-center gap-2 mt-auto pt-4 border-t border-gray-100">
                            <button @click="editReview = {{ json_encode($review) }}; showEditModal = true" class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-black bg-gray-50 text-foreground hover:bg-gray-100 hover:text-primary transition-colors">
                                <i data-lucide="edit-3" size="14"></i> Edit
                            </button>
                            <a href="{{ url('/admin/reviews/toggle/'.$review->id) }}" class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-black {{ $review->status === 'Active' ? 'bg-gray-50 text-gray-600 hover:bg-orange-50 hover:text-orange-600' : 'bg-green-50 text-green-700 hover:bg-green-100' }} transition-colors">
                                <i data-lucide="{{ $review->status === 'Active' ? 'pause-circle' : 'play-circle' }}" size="14"></i> 
                                {{ $review->status === 'Active' ? 'Pause' : 'Activate' }}
                            </a>
                            <a href="{{ url('/admin/reviews/delete/'.$review->id) }}" onclick="return confirm('Delete this review?')" class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition-colors shrink-0">
                                <i data-lucide="trash-2" size="14"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="text-center py-16 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-muted-text">
                        <i data-lucide="message-square" size="24"></i>
                    </div>
                    <h3 class="text-sm font-black text-foreground mb-1 uppercase tracking-widest">No Reviews Yet</h3>
                    <p class="text-xs text-muted-text font-medium mb-4">You haven't added any client reviews.</p>
                    <button @click="showAddModal = true" class="px-6 py-2.5 bg-white border border-border-soft hover:border-primary text-foreground text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-sm hover:shadow-md">
                        Add First Review
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Add Modal -->
    <div x-show="showAddModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-transition.opacity>
        <div class="absolute inset-0 bg-foreground/40 backdrop-blur-sm" @click="showAddModal = false"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-[32px] shadow-2xl p-6 sm:p-8 animate-fade-in-up">
            
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-border-soft">
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-foreground">Add New Review</h3>
                    <p class="text-xs text-muted-text font-medium">Create a new client review to display.</p>
                </div>
                <button @click="showAddModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/reviews/store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                <div class="col-span-1 md:col-span-2 space-y-2" x-data="{ imagePreview: null }">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Reviewer Avatar</label>
                    <div class="relative w-full border-2 border-dashed border-border-soft hover:border-primary/50 rounded-2xl p-8 transition-colors bg-gray-50 flex flex-col items-center justify-center gap-3 text-center cursor-pointer group overflow-hidden" :class="imagePreview ? 'border-primary/50' : ''">
                        <template x-if="imagePreview">
                            <img :src="imagePreview" class="absolute inset-0 w-full h-full object-cover opacity-40 group-hover:opacity-20 transition-opacity" />
                        </template>
                        <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-primary group-hover:scale-110 transition-transform relative z-10">
                            <i data-lucide="upload-cloud" size="24"></i>
                        </div>
                        <input type="file" name="image_file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" 
                               @change="if($event.target.files.length) imagePreview = URL.createObjectURL($event.target.files[0]); else imagePreview = null" />
                        <div class="relative z-10">
                            <span class="text-sm font-bold text-foreground block" x-text="imagePreview ? 'Click or drag to replace image' : 'Click or drag image to upload'"></span>
                            <p class="text-[10px] text-muted-text font-bold mt-1">Leave empty to use auto-generated avatar.</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Reviewer Name<span class="text-primary">*</span></label>
                    <input required type="text" name="name" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" placeholder="John Doe" />
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Location</label>
                    <input type="text" name="location" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" placeholder="New York" />
                </div>

                <div class="col-span-1 md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Review Text<span class="text-primary">*</span></label>
                    <textarea required name="text" rows="3" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm resize-none" placeholder="I had a great time..."></textarea>
                </div>
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Rating</label>
                    <select name="rating" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                        <option value="5">5 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="2">2 Stars</option>
                        <option value="1">1 Star</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                    <select name="status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                <div class="col-span-1 md:col-span-2 flex items-center justify-end gap-4 pt-4 border-t border-border-soft">
                    <button type="button" @click="showAddModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Publish Review</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-transition.opacity>
        <div class="absolute inset-0 bg-foreground/40 backdrop-blur-sm" @click="showEditModal = false"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-[32px] shadow-2xl p-6 sm:p-8 animate-fade-in-up">
            
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-border-soft">
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-foreground">Edit Review</h3>
                    <p class="text-xs text-muted-text font-medium">Update the details for this review.</p>
                </div>
                <button @click="showEditModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ url('/admin/reviews/update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                <input type="hidden" name="id" x-model="editReview.id">

                <div class="col-span-1 md:col-span-2 space-y-2" x-data="{ imagePreview: null }">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Reviewer Avatar</label>
                    <div class="relative w-full border-2 border-dashed border-border-soft hover:border-primary/50 rounded-2xl p-8 transition-colors bg-gray-50 flex flex-col items-center justify-center gap-3 text-center cursor-pointer group overflow-hidden" :class="(imagePreview || editReview.image) ? 'border-primary/50' : ''">
                        <template x-if="imagePreview || editReview.image">
                            <img :src="imagePreview || (editReview.image.startsWith('http') ? editReview.image : '/' + editReview.image)" class="absolute inset-0 w-full h-full object-cover opacity-40 group-hover:opacity-20 transition-opacity" />
                        </template>
                        <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center text-primary group-hover:scale-110 transition-transform relative z-10">
                            <i data-lucide="upload-cloud" size="24"></i>
                        </div>
                        <input type="file" name="image_file" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" 
                               @change="if($event.target.files.length) imagePreview = URL.createObjectURL($event.target.files[0]); else imagePreview = null" />
                        <div class="relative z-10">
                            <span class="text-sm font-bold text-foreground block" x-text="(imagePreview || editReview.image) ? 'Click or drag to replace image' : 'Click or drag image to upload'"></span>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" name="image" x-model="editReview.image">

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Reviewer Name<span class="text-primary">*</span></label>
                    <input required type="text" name="name" x-model="editReview.name" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Location</label>
                    <input type="text" name="location" x-model="editReview.location" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>

                <div class="col-span-1 md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Review Text<span class="text-primary">*</span></label>
                    <textarea required name="text" rows="3" x-model="editReview.text" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm resize-none"></textarea>
                </div>
                
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Rating</label>
                    <select name="rating" x-model="editReview.rating" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                        <option value="5">5 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="2">2 Stars</option>
                        <option value="1">1 Star</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                    <select name="status" x-model="editReview.status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm">
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>

                <div class="col-span-1 md:col-span-2 flex items-center justify-end gap-4 pt-4 border-t border-border-soft">
                    <button type="button" @click="showEditModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-primary text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">Update Review</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
