@props(['banners' => null])
@php use Illuminate\Support\Str; @endphp

<section class="relative overflow-hidden" style="height:75vh; min-height:500px; max-height:700px;">

  {{-- ── Background Slider ── --}}
  <div class="absolute inset-0 z-0 bg-[#1A1A24]">
    <div id="heroSlider" style="display:flex; width:100%; height:100%; transition:transform 1.2s cubic-bezier(0.65,0,0.35,1); will-change:transform;">
      @php
        $dbBanners = $banners && $banners->isNotEmpty() ? $banners->toArray() : [];
        $slides = [];
        if (!empty($dbBanners)) {
          foreach ($dbBanners as $b) { $slides[] = $b->image; }
        } else {
          $slides = [
            'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1920&q=80',
            'https://images.unsplash.com/photo-1506929562872-bb421503ef21?auto=format&fit=crop&w=1920&q=80',
            'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?auto=format&fit=crop&w=1920&q=80',
            'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1920&q=80',
          ];
        }
      @endphp
      @foreach($slides as $index => $url)
        <div style="flex:0 0 100%; width:100%; height:100%; background-image:url('{{ $url }}'); background-size:cover; background-position:center;"></div>
      @endforeach
    </div>
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/30 to-black/60 pointer-events-none"></div>
  </div>

  {{-- ── Slide dots ── --}}
  <div class="absolute bottom-8 right-8 z-30 flex items-center gap-3">
    <button onclick="prevHeroSlide()" class="text-white/60 hover:text-white transition-colors">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    @foreach($slides as $i => $url)
      <div class="hero-dot {{ $i===0?'opacity-100':'opacity-40' }}" data-dot="{{ $i }}" onclick="goToHeroSlide({{ $i }})"
           style="width:8px;height:8px;border-radius:50%;background:#fff;cursor:pointer;transition:opacity .3s;"></div>
    @endforeach
    <button onclick="nextHeroSlide()" class="text-white/60 hover:text-white transition-colors">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
  </div>

  {{-- ── Hero Content ── --}}
  <div class="relative z-20 h-full flex flex-col items-center justify-center text-center px-4 pt-16 pb-8">
    <h1 class="text-3xl sm:text-4xl md:text-5xl font-black leading-snug w-full mb-8 whitespace-nowrap"
        style="color:#ffffff !important; text-shadow:0 2px 12px rgba(0,0,0,0.5);">
      Discover Exclusive Travel Packages<br>from Local Agents Near You!
    </h1>

    {{-- Glassmorphism Search Bar --}}
    <div class="w-full max-w-3xl">
      <form action="{{ route('search') }}" method="GET">
        <div style="background:rgba(255,255,255,0.15); backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px); border:1px solid rgba(255,255,255,0.25); border-radius:8px;"
             class="flex flex-row items-center gap-0 overflow-hidden">

          {{-- Destination Field --}}
          <div class="flex items-center gap-3 flex-1 px-6 py-4" style="border-right: 1px solid rgba(255,255,255,0.2);">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.7)" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" name="destination" placeholder="Search Where You Go !!!"
                   class="bg-transparent text-white placeholder-white/70 text-sm font-medium outline-none w-full"
                   value="{{ request('destination') }}">
          </div>

          {{-- Agent/City Field --}}
          <div class="flex items-center gap-3 flex-1 px-6 py-4">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.7)" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <input type="text" name="from_city" placeholder="Search agent from your location/near by city"
                   class="bg-transparent text-white placeholder-white/70 text-[12px] font-medium outline-none w-full"
                   value="{{ request('from_city') }}">
          </div>

          {{-- Search Button --}}
          <div class="px-2 py-2 flex-shrink-0">
            <button type="submit"
                    style="background:#e85d26; border-radius:6px;"
                    class="px-8 py-3 text-white font-bold text-sm hover:bg-orange-600 transition-colors w-full sm:w-auto">
              Search
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script>
    let currentSlide = 0;
    const slider    = document.getElementById('heroSlider');
    const dots      = document.querySelectorAll('.hero-dot');
    const totalSlides = dots.length;
    let slideInterval;

    function showSlide(i) {
      if (!slider) return;
      slider.style.transform = `translateX(-${i * 100}%)`;
      dots.forEach((d, idx) => d.style.opacity = idx === i ? '1' : '0.4');
      currentSlide = i;
    }
    function nextHeroSlide() { showSlide((currentSlide + 1) % totalSlides); resetHeroInterval(); }
    function prevHeroSlide() { showSlide((currentSlide - 1 + totalSlides) % totalSlides); resetHeroInterval(); }
    function goToHeroSlide(i) { showSlide(i); resetHeroInterval(); }
    function resetHeroInterval() { clearInterval(slideInterval); slideInterval = setInterval(nextHeroSlide, 5000); }
    document.addEventListener('DOMContentLoaded', () => { slideInterval = setInterval(nextHeroSlide, 5000); });
  </script>
  {{-- Bottom fade blur: image fades into white below --}}
  <div class="absolute bottom-0 left-0 right-0 z-10 pointer-events-none" style="height:80px; background:linear-gradient(to bottom, transparent 0%, rgba(255,255,255,0.6) 60%, #ffffff 100%); backdrop-filter:blur(2px); -webkit-backdrop-filter:blur(2px);"></div>
</section>

{{-- ── Popular Transits (below hero, white bg) ── --}}
<section class="bg-white py-12 lg:py-16 border-b border-gray-100">
  <div class="container-custom">
    <h2 class="text-3xl md:text-5xl font-black text-foreground tracking-tight font-heading mb-10">Popular Transits</h2>
    {{-- flex-nowrap = all 8 icons in ONE single horizontal line --}}
    <div class="flex flex-nowrap items-start justify-between gap-2">
      @php
        $transits = [
          ['label' => "Land / customise\nPackage",   'gif' => 'https://s13.gifyu.com/images/bIHHY.png'],
          ['label' => "Flight\nPackage",              'gif' => 'https://s13.gifyu.com/images/bIHxe.gif'],
          ['label' => "Train\nPackage",               'gif' => 'https://s13.gifyu.com/images/bIHxG.gif'],
          ['label' => "Bus\nPackage",                 'gif' => 'https://s13.gifyu.com/images/bIHxJ.gif'],
          ['label' => "Bullet Ride\nPackage",         'gif' => 'https://s13.gifyu.com/images/bIHxP.gif'],
          ['label' => "Cruise\nPackage",              'gif' => 'https://s13.gifyu.com/images/bIHxX.gif'],
          ['label' => "Tracking\nPackage",            'gif' => 'https://s13.gifyu.com/images/bIHHt.png'],
          ['label' => "Helicopter\nPackage",          'gif' => 'https://s13.gifyu.com/images/bIHH5.png'],
        ];
      @endphp
      @foreach($transits as $t)
        <a href="{{ route('search', ['transit' => Str::slug($t['label'])]) }}"
           class="group flex-1 flex flex-col items-center gap-2 cursor-pointer hover:opacity-80 transition-opacity min-w-0">
          <div class="w-16 h-16 flex items-center justify-center">
            <img src="{{ $t['gif'] }}" alt="{{ $t['label'] }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
          </div>
          <span class="text-center text-[11px] font-semibold text-gray-600 leading-tight whitespace-pre-line group-hover:text-[#e85d26] transition-colors w-full">{{ $t['label'] }}</span>
        </a>
      @endforeach
    </div>
  </div>
</section>
