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
      @php
        $hasVideo = false;
      @endphp
      @foreach($slides as $index => $url)
        @php
          $isVideo = \Illuminate\Support\Str::endsWith(strtolower($url), ['.mp4', '.webm', '.ogg']);
          if($isVideo) $hasVideo = true;
        @endphp
        <div style="flex:0 0 100%; width:100%; height:100%; position:relative; overflow:hidden;">
          @if($isVideo)
            <video class="hero-video" src="{{ $url }}" autoplay loop muted playsinline style="width:100%; height:100%; object-fit:cover; position:absolute; top:0; left:0;"></video>
          @else
            <div style="width:100%; height:100%; background-image:url('{{ $url }}'); background-size:cover; background-position:center;"></div>
          @endif
        </div>
      @endforeach
    </div>
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/30 to-black/60 pointer-events-none"></div>
    {{-- Background Music --}}
    <audio id="heroBgMusic" src="/public/audio/destroyer_of_all.mp3?v={{ time() }}" loop></audio>
  </div>

  {{-- ── Sound Toggle (Bottom Right Corner) ── --}}
  @if($hasVideo)
  <button onclick="toggleHeroSound()" id="heroSoundToggle" class="absolute bottom-8 right-8 z-40 bg-black/40 hover:bg-black/60 p-3 rounded-full text-white transition-all cursor-pointer backdrop-blur-sm" title="Toggle Sound">
    <!-- Muted Icon -->
    <svg id="icon-muted" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><line x1="23" y1="9" x2="17" y2="15"/><line x1="17" y1="9" x2="23" y2="15"/></svg>
    <!-- Unmuted Icon -->
    <svg id="icon-unmuted" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="hidden"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"/></svg>
  </button>
  @endif

  {{-- ── Slide dots (Centered) ── --}}
  <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-30 flex items-center gap-3 bg-black/20 px-4 py-2 rounded-full backdrop-blur-sm">
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
    <h1 class="text-3xl sm:text-4xl md:text-5xl font-black leading-snug w-full mb-8"
        style="color:#ffffff !important; text-shadow:0 2px 12px rgba(0,0,0,0.5);">
      Discover Exclusive Travel Packages<br class="hidden sm:block">from Local Agents Near You!
    </h1>

    {{-- Glassmorphism Search Bar --}}
    <div class="w-full max-w-5xl">
      <form action="{{ route('search') }}" method="GET">
        <div style="background:rgba(255,255,255,0.15); backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px); border:1px solid rgba(255,255,255,0.25); border-radius:8px;"
             class="flex flex-col md:flex-row items-center gap-0 overflow-hidden">

          {{-- Destination Field --}}
          <div class="flex items-center gap-3 flex-1 w-full md:w-auto px-4 py-3 md:px-6 md:py-4" style="border-right: 1px solid rgba(255,255,255,0.2);">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.7)" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input type="text" name="destination" placeholder="Search Where You Go !!!"
                   class="bg-transparent border-none focus:ring-0 text-white placeholder-white/70 text-sm font-medium outline-none w-full"
                   style="box-shadow: none;"
                   value="{{ request('destination') }}">
          </div>

          {{-- Agent/City Field --}}
          <div class="flex items-center gap-3 flex-1 w-full md:w-auto px-4 py-3 md:px-6 md:py-4">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.7)" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <input type="text" name="from_city" placeholder="Search agent from your location/near by city"
                   class="bg-transparent border-none focus:ring-0 text-white placeholder-white/70 text-[12px] font-medium outline-none w-full"
                   style="box-shadow: none;"
                   value="{{ request('from_city') }}">
          </div>

          {{-- Search Button --}}
          <div class="px-4 py-3 md:px-2 md:py-2 flex-shrink-0 w-full md:w-auto">
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
    const totalSlides = {{ count($slides) }};
    const heroSlider = document.getElementById('heroSlider');
    let slideInterval;

    function showSlide(index) {
      currentSlide = index;
      heroSlider.style.transform = `translateX(-${index * 100}%)`;
      document.querySelectorAll('.hero-dot').forEach((dot, i) => {
        dot.classList.toggle('opacity-100', i === index);
        dot.classList.toggle('opacity-40', i !== index);
      });
    }
    function nextHeroSlide() { showSlide((currentSlide + 1) % totalSlides); resetHeroInterval(); }
    function prevHeroSlide() { showSlide((currentSlide - 1 + totalSlides) % totalSlides); resetHeroInterval(); }
    function goToHeroSlide(i) { showSlide(i); resetHeroInterval(); }
    function resetHeroInterval() { clearInterval(slideInterval); slideInterval = setInterval(nextHeroSlide, 5000); }
    
    function toggleHeroSound() {
      const bgMusic = document.getElementById('heroBgMusic');
      const iconMuted = document.getElementById('icon-muted');
      const iconUnmuted = document.getElementById('icon-unmuted');
      
      if(bgMusic.paused) {
        bgMusic.play().catch(e => console.log('Audio play failed:', e));
        iconMuted.classList.add('hidden');
        iconUnmuted.classList.remove('hidden');
      } else {
        bgMusic.pause();
        iconMuted.classList.remove('hidden');
        iconUnmuted.classList.add('hidden');
      }
    }

    document.addEventListener('DOMContentLoaded', () => { slideInterval = setInterval(nextHeroSlide, 5000); });
  </script>
  {{-- Bottom fade blur: image fades into white below --}}
  <div class="absolute bottom-0 left-0 right-0 z-10 pointer-events-none" style="height:80px; background:linear-gradient(to bottom, transparent 0%, rgba(255,255,255,0.6) 60%, #ffffff 100%); backdrop-filter:blur(2px); -webkit-backdrop-filter:blur(2px);"></div>
</section>

{{-- ── Popular Transits (below hero, white bg) ── --}}
<section id="popular-transits-section" class="bg-white py-12 lg:py-16 border-b border-gray-100">
  <div class="container-custom">
    <h2 class="font-black text-foreground tracking-tight font-heading mb-10 text-center" style="font-size: 36px;">Popular Transits</h2>
    <div class="flex flex-nowrap overflow-x-auto hide-scrollbar scroll-smooth items-start justify-around gap-6 md:gap-10 pb-4 md:pb-0 px-2">
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
           class="group flex-none md:flex-1 w-28 md:w-auto flex flex-col items-center gap-3 cursor-pointer hover:opacity-80 transition-opacity">
          <div class="w-20 h-20 md:w-24 md:h-24 flex items-center justify-center">
            <img src="{{ $t['gif'] }}" alt="{{ $t['label'] }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
          </div>
          <span class="text-center text-sm md:text-base font-semibold text-gray-600 leading-tight whitespace-pre-line group-hover:text-[#e85d26] transition-colors w-full">{{ $t['label'] }}</span>
        </a>
      @endforeach
    </div>
  </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const section = document.getElementById('popular-transits-section');
        const navbarTransits = document.getElementById('navbar-transits');
        
        if(section && navbarTransits) {
            window.addEventListener('scroll', function() {
                // Trigger when scrollY > section end
                const triggerPoint = section.offsetTop + section.offsetHeight - 80; 
                if (window.scrollY > triggerPoint) {
                    navbarTransits.classList.remove('opacity-0', 'pointer-events-none', 'scale-90', 'translate-y-4');
                    navbarTransits.classList.add('opacity-100', 'scale-100', 'translate-y-0');
                } else {
                    navbarTransits.classList.add('opacity-0', 'pointer-events-none', 'scale-90', 'translate-y-4');
                    navbarTransits.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
                }
            });
        }
    });
</script>
