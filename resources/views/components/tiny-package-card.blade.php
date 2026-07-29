@props([
    'pkg' => null
])

@php
    $pkgArr = $pkg ? (is_object($pkg) ? clone $pkg : (object)$pkg) : null;
    if ($pkgArr) {
        $pkgArr = $pkgArr instanceof \Illuminate\Database\Eloquent\Model ? $pkgArr->toArray() : (array)$pkgArr;
    } else {
        $pkgArr = [];
    }
    
    $title = $pkgArr['title'] ?? 'Package';
    $duration = $pkgArr['duration'] ?? 'N/A';
    $price = $pkgArr['price'] ?? 0;
    $rating = $pkgArr['rating'] ?? 0;
    $reviews = $pkgArr['reviews'] ?? 0;
    $image = $pkgArr['image'] ?? 'images/placeholder.jpg';
    $category = $pkgArr['category'] ?? $pkgArr['categories_list'] ?? '';
    
    // Ensure image handles relative paths
    $imageUrl = (!empty($image) && filter_var($image, FILTER_VALIDATE_URL)) ? $image : asset($image);
@endphp

<a href="{{ url('/package/'.($pkgArr['id'] ?? 1)) }}" 
   class="tiny-pkg-card flex flex-col bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition-all group" 
   data-category="{{ strtolower($category) }}" 
   data-duration="{{ strtolower($duration) }}" 
   data-rating="{{ floor((float)$rating) }}" 
   data-price="{{ (float)$price }}">
    <div class="relative w-full h-32 flex-shrink-0 bg-gray-50">
        <img src="{{ $imageUrl }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
    </div>
    <div class="p-3 flex flex-col flex-grow">
        <h3 class="font-semibold text-[11px] text-text-main line-clamp-2 leading-snug mb-2">{{ $title }}</h3>
        
        <div class="flex items-center justify-between text-[11px] font-bold text-gray-500 mb-2 mt-auto">
            <span class="flex items-center gap-1">
                <i data-lucide="clock" class="w-3 h-3"></i> {{ $duration }}
            </span>
            <div class="flex items-center gap-1 text-gray-700">
                <svg class="w-3 h-3 text-yellow-500 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                {{ number_format((float)$rating, 1) }}
            </div>
        </div>
        
        <div class="pt-2 border-t border-gray-100 flex items-center justify-between">
            <span class="text-[10px] text-gray-400 uppercase tracking-wider font-bold">Price</span>
            <div class="font-black text-primary text-[14px]">
                ₹{{ number_format($price) }}
            </div>
        </div>
    </div>
</a>
