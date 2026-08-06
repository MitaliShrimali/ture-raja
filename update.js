const fs = require('fs');

let content = fs.readFileSync('resources/views/components/package-form.blade.php', 'utf-8');

const php_block = @php
     =  ?? null;
     = [];
    if ( && !empty(->categories)) {
         = json_decode(->categories, true);
        if (is_string()) {
             = json_decode(, true) ?: [];
        } elseif (is_array()) {
             = ;
        }
    }
    
     =  && ->gallery ? json_decode(->gallery, true) : [];
    if (!is_array())  = [];

     =  && ->inclusions ? json_decode(->inclusions, true) : [];
    if (!is_array())  = [];

     =  && ->exclusions ? json_decode(->exclusions, true) : [];
    if (!is_array())  = [];
    
     =  && ->keywords ? json_decode(->keywords, true) : [];
    if (!is_array())  = [];
    
     =  && ->itinerary ? json_decode(->itinerary, true) : [];
@endphp
;

const new_x_data = <div class="space-y-4 pb-12" @itinerary-updated.window="itineraryContent = .detail" x-data="{ 
        step: 1,
        category: {{ json_encode(->category ?? 'domestic') }},
        title: {{ json_encode(->title ?? '') }},
        location: {{ json_encode(->location ?? '') }},
        duration: {{ json_encode(->duration ?? '') }},
        price: {{ json_encode(->price ?? '') }},
        currency: {{ json_encode(->currency ?? '₹') }},
        inrPrice: '',
        rates: { '₹': 1, '$': 86.5, '€': 89.2, '£': 105.4, 'AED': 23.5 },
        initPrice() {
            if (this.price) {
                this.inrPrice = (this.price * this.rates[this.currency]).toFixed(2);
                if(this.inrPrice.endsWith('.00')) this.inrPrice = Math.round(this.inrPrice);
            }
        },
        updatePrice(fromBase) {
            if (fromBase) {
                this.inrPrice = (this.price * this.rates[this.currency]).toFixed(2);
                if(this.inrPrice.endsWith('.00')) this.inrPrice = Math.round(this.inrPrice);
            } else {
                if(this.inrPrice) {
                    this.price = (this.inrPrice / this.rates[this.currency]).toFixed(2);
                    if(this.price.endsWith('.00')) this.price = Math.round(this.price);
                } else {
                    this.price = '';
                }
            }
        },
        old_price: {{ json_encode(->old_price ?? '') }},
        validity: {{ json_encode(->validity ?? '') }},
        sightseeing: {{ json_encode(->sightseeing ?? '') }},
        stock: {{ json_encode(->stock ?? '') }},
        categories: {{ json_encode() }},
        badge: {{ json_encode(->badge ?? '') }},
        group_size: {{ json_encode(->group_size ?? 'Direct Flight') }},
        rating: {{ json_encode(->rating ?? '4.8') }},
        reviews: {{ json_encode(->reviews ?? '10') }},
        previewUrl: {{ json_encode( && ->image ? asset(->image) : '') }},
        galleryPreviews: {{ json_encode(array_values(array_map(function () {
            return [
                'url' => asset(),
                'name' => basename(),
                'size' => 'Existing',
                'is_gallery' => true,
                'path' => 
            ];
        }, ))) }},
        brochureName: {{ json_encode( && ->brochure ? basename(->brochure) : '') }},
        brochureUrl: {{ json_encode( && ->brochure ? asset(->brochure) : '') }},
        itineraryContent: {{ json_encode(strip_tags(->editorial_itinerary ?? '') ? trim(strip_tags(->editorial_itinerary)) : '') }},
        inclusions: {{ json_encode() }},
        exclusions: {{ json_encode() }},
        newInclusion: '',
        newExclusion: '',
        cities: {{ json_encode(array_values(array_filter(array_map('trim', explode(',', ->location ?? ''))))) }},
        newCity: '',
        keywords: {{ json_encode() }},
        newKeyword: '',
        customAmenities: {{ json_encode(array_values(array_diff(json_decode(->amenities ?? '[]', true) ?: [], ['Free Wifi', 'Breakfast Included', 'Travel Insurance', 'Private Chef Included', 'Tour Manager Included']))) }},
        newAmenity: '',
        days: {{ ( && count() > 0) ? json_encode() : json_encode([['title' => '', 'desc' => '', 'duration' => '']]) }},
;

content = content.replace(/(@props\[.*?\]\))/s, "\n" + php_block);
let startIdx = content.indexOf('<div class="space-y-4 pb-12" @itinerary-updated.window="itineraryContent = .detail" x-data="{');
let endIdx = content.indexOf('addAmenity() {', startIdx); // Just find a point to replace up to
if(startIdx > -1) {
    let replaced_x_data = content.substring(startIdx, endIdx);
    content = content.replace(replaced_x_data, new_x_data);
}

// Clean up old days if present
content = content.replace(/days: \[\s*\{ title: '', desc: '', duration: '' \}\s*\],/, '');

fs.writeFileSync('resources/views/components/package-form.blade.php', content, 'utf-8');
console.log("Done");
