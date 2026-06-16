  @props(['banners' => null, 'homeAd' => null])
  @php use Illuminate\Support\Str; @endphp

  <style>
    .pure-white-placeholder::placeholder {
      color: #ffffff !important;
      opacity: 1 !important;
    }
    @media (max-width: 767px) {
      .hero-mobile-title {
        font-size: 20px !important;
        line-height: 1.3 !important;
        margin-bottom: 12px !important;
      }
      .hero-content-wrapper {
        padding-top: 80px !important;
      }
      .hero-sound-toggle-wrapper {
        bottom: 6px !important;
        right: 16px !important;
      }
    }
  </style>

  <section class="relative overflow-hidden" style="height:55vh; min-height:400px; max-height:550px;">

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
      <audio id="heroBgMusic" src="{{ asset('audio/bg_music.mp3') }}?v={{ time() }}" autoplay loop></audio>
    </div>

    <div class="absolute bottom-2 right-4 md:bottom-8 md:right-8 z-40 select-none hero-sound-toggle-wrapper">
      <button type="button" onclick="toggleHeroSound()" id="heroSoundToggle" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none" style="background-color: #e85d26; box-shadow: 0 0 10px rgba(232, 93, 38, 0.3);">
        <span id="heroSoundKnob" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-lg ring-0 transition duration-200 ease-in-out" style="transform: translateX(20px);"></span>
      </button>
    </div>



    {{-- Top/Bottom Gradients --}}
    <div class="absolute inset-0 z-10 pointer-events-none" style="background:linear-gradient(to bottom, rgba(0,0,0,0.6) 0%, transparent 25%, transparent 60%, rgba(0,0,0,0.4) 100%);"></div>

    {{-- ── Main Hero Content (Centered Text + Search Form) ── --}}
    <div class="absolute inset-0 z-20 flex flex-col items-center justify-center text-center px-4 md:px-8 pt-16 md:pt-0 hero-content-wrapper">
      
      {{-- Main Headline --}}
      <h1 class="text-xl sm:text-4xl md:text-5xl lg:text-5xl font-black leading-snug w-full mb-3 md:mb-6 hero-mobile-title"
          style="color:#ffffff !important; text-shadow:0 2px 12px rgba(0,0,0,0.5);">
        Discover Exclusive Travel Packages <br class="hidden sm:block">from Local Agents Near You!
      </h1>

      {{-- Glassmorphism Search Bar --}}
      <div class="w-full max-w-7xl">
        <form action="{{ route('search') }}" method="GET">
          <div style="background:rgba(255,255,255,0.15); backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px); border:1px solid rgba(255,255,255,0.25); border-radius:8px;"
              class="flex flex-col md:flex-row items-center gap-0 overflow-hidden">

            {{-- Destination Field --}}
            <div class="flex items-center gap-3 flex-1 w-full md:w-auto px-4 py-3 md:px-6 md:py-4" style="border-right: 1px solid rgba(255,255,255,0.2);">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
              <input type="text" name="destination" placeholder="Search Where You Go !!!"
                    class="bg-transparent border-none focus:ring-0 text-white placeholder-white text-sm font-medium outline-none w-full pure-white-placeholder"
                    style="box-shadow: none;"
                    value="{{ request('destination') }}">
            </div>

            {{-- Agent/City Field --}}
            <div class="flex items-center gap-3 flex-1 w-full md:w-auto px-4 py-3 md:px-6 md:py-4">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              <input type="text" name="from_city" placeholder="Search agent from your location/near by city"
                    class="bg-transparent border-none focus:ring-0 text-white placeholder-white text-[12px] font-medium outline-none w-full pure-white-placeholder"
                    style="box-shadow: none;"
                    value="{{ request('from_city') }}">
            </div>

            {{-- Search Button --}}
            <div class="px-4 py-3 md:px-2 md:py-2 flex-shrink-0 w-full md:w-auto">
              <button type="submit"
                      style="background:#e85d26;"
                      class="rounded-full px-8 py-3 text-white font-bold text-sm hover:bg-orange-600 transition-colors w-full sm:w-auto">
                Search
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>



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
      
      function updateSoundToggleState(isEnabled) {
        const toggle = document.getElementById('heroSoundToggle');
        const knob = document.getElementById('heroSoundKnob');
        if (!toggle || !knob) return;
        if (isEnabled) {
          toggle.style.backgroundColor = '#e85d26';
          knob.style.transform = 'translateX(20px)';
        } else {
          toggle.style.backgroundColor = '#4b5563';
          knob.style.transform = 'translateX(0)';
        }
      }
      
      function toggleHeroSound() {
        const bgMusic = document.getElementById('heroBgMusic');
        if (!bgMusic) return;
        if (bgMusic.paused) {
          bgMusic.play().then(() => {
            updateSoundToggleState(true);
          }).catch(e => console.log('Audio play failed:', e));
        } else {
          bgMusic.pause();
          updateSoundToggleState(false);
        }
      }

      document.addEventListener('DOMContentLoaded', () => {
        slideInterval = setInterval(nextHeroSlide, 5000);
        
        const bgMusic = document.getElementById('heroBgMusic');
        if (bgMusic) {
          bgMusic.volume = 0.5;
          
          const startPlay = () => {
            bgMusic.play()
              .then(() => {
                updateSoundToggleState(true);
                document.removeEventListener('click', startPlay);
                document.removeEventListener('keydown', startPlay);
              })
              .catch(e => {
                console.log('Autoplay blocked, waiting for interaction...', e);
              });
          };
          
          startPlay();
          document.addEventListener('click', startPlay);
          document.addEventListener('keydown', startPlay);
        }
      });
    </script>


    {{-- Bottom fade blur: image fades into white below --}}
    <div class="absolute bottom-0 left-0 right-0 z-10 pointer-events-none" style="height:60px; background:linear-gradient(to bottom, transparent 0%, rgba(255,255,255,0.6) 60%, #ffffff 100%); backdrop-filter:blur(2px); -webkit-backdrop-filter:blur(2px);"></div>
  </section>

  {{-- ── Popular Transits (below hero, white bg) ── --}}
  <section id="popular-transits-section" class="bg-white pt-6 pb-4 md:pt-8 md:pb-6">
    <div class="container-custom">
      <h2 class="font-black text-foreground tracking-tight font-heading mb-6 md:mb-8 text-center" style="font-size: 28px;">Popular Transits</h2>
      <div class="flex flex-nowrap justify-center items-start gap-2 md:gap-6 lg:gap-8 pb-4 md:pb-0 px-2 mx-auto w-full overflow-hidden">
        @php
          try {
              $dbTransits = DB::table('transits')->where('status', 'Active')->get();
          } catch (\Exception $e) {
              $dbTransits = collect();
          }

          if ($dbTransits->isEmpty()) {
              $dbTransits = collect([
                  (object)[
                      'name' => 'Flight Package',
                      'selected_icon' => 'plane',
                      'svg_icon' => null,
                      'image' => null,
                  ],
                  (object)[
                      'name' => 'Train Package',
                      'selected_icon' => 'train',
                      'svg_icon' => null,
                      'image' => null,
                  ],
                  (object)[
                      'name' => 'Bus Package',
                      'selected_icon' => 'bus',
                      'svg_icon' => null,
                      'image' => null,
                  ],
                  (object)[
                      'name' => 'Cruise Package',
                      'selected_icon' => 'ship',
                      'svg_icon' => null,
                      'image' => null,
                  ],
                  (object)[
                      'name' => 'Helicopter Package',
                      'selected_icon' => 'helicopter',
                      'svg_icon' => null,
                      'image' => null,
                  ],
              ]);
          }

          $gifMap = [
              'flight' => 'https://s13.gifyu.com/images/bIHxe.gif',
              'plane' => 'https://s13.gifyu.com/images/bIHxe.gif',
              'train' => 'https://s13.gifyu.com/images/bIHxG.gif',
              'bus' => 'https://s13.gifyu.com/images/bIHxJ.gif',
              'bike' => 'https://s13.gifyu.com/images/bIHxP.gif',
              'motorcycle' => 'https://s13.gifyu.com/images/bIHxP.gif',
              'ship' => 'https://s13.gifyu.com/images/bIHxX.gif',
              'cruise' => 'https://s13.gifyu.com/images/bIHxX.gif',
              'footprints' => 'https://s13.gifyu.com/images/bIHHt.png',
              'user' => 'https://s13.gifyu.com/images/bIHHt.png',
              'helicopter' => 'https://s13.gifyu.com/images/bIHH5.png',
              'car' => 'https://s13.gifyu.com/images/bIHHY.png',
              'map-pin' => 'https://s13.gifyu.com/images/bIHHY.png',
          ];
        @endphp
        @foreach($dbTransits as $t)
          @php
              $imgUrl = '';
              if ($t->svg_icon) {
                  $imgUrl = asset($t->svg_icon);
              } elseif ($t->image) {
                  $imgUrl = asset($t->image);
              } else {
                  $imgUrl = $gifMap[$t->selected_icon] ?? 'https://s13.gifyu.com/images/bIHHY.png';
              }
              
              $cleanType = $t->name;
              $label = str_replace(" Package", "\nPackage", $t->name);
          @endphp
          <a href="{{ url('/discover?tour_type=' . urlencode($cleanType)) }}"
            class="group flex-1 min-w-0 max-w-[80px] md:max-w-[110px] lg:max-w-[130px] flex flex-col items-center gap-1.5 md:gap-3 cursor-pointer hover:opacity-80 transition-opacity flex-shrink">
            <div class="w-12 h-12 md:w-16 md:h-16 lg:w-20 lg:h-20 flex items-center justify-center">
              <img src="{{ $imgUrl }}" alt="{{ $t->name }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
            </div>
            <span class="text-center text-[10px] md:text-xs lg:text-sm font-semibold text-gray-600 leading-tight whitespace-pre-line group-hover:text-[#e85d26] transition-colors w-full truncate md:overflow-visible md:whitespace-pre-line">{{ $label }}</span>
          </a>
        @endforeach
      </div>
    </div>
  </section>

  {{-- ── ADS Section ── --}}
  <section class="bg-white pb-8 md:pb-12 border-b border-gray-100">
    <div class="container-custom">
      @if(isset($homeAd) && $homeAd)
          <a href="{{ route('ad.click', ['id' => $homeAd->id]) }}" target="_blank" class="block w-full h-[80px] md:h-[100px] lg:h-[120px] bg-gray-100 rounded-[20px] shadow-sm hover:shadow-md transition-shadow overflow-hidden group flex items-center justify-center">
              <img src="{{ $homeAd->image }}" alt="{{ $homeAd->campaign_name }}" class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-500">
          </a>
      @else
          <div class="w-full h-[80px] md:h-[100px] lg:h-[120px] bg-[#d5d5d5] rounded-[20px] flex items-center justify-center shadow-sm hover:shadow-md transition-shadow cursor-pointer">
            <span class="text-lg md:text-xl font-black text-black tracking-widest">ADS</span>
          </div>
      @endif
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
