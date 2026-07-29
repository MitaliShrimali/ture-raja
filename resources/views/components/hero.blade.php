@props(['banners' => null, 'homeAd' => null])
@php use Illuminate\Support\Str; @endphp

<style>
  .orange-placeholder::placeholder {
    color: #e85d26 !important;
    opacity: 1 !important;
  }

  @media (min-width: 768px) {
    .hero-search-divider {
      position: relative;
    }
    .hero-search-divider::after {
      content: '';
      position: absolute;
      right: 0;
      top: 50%;
      transform: translateY(-50%);
      width: 1px;
      height: 60%;
      background-color: #e85d26;
    }
  }
  @media (max-width: 767px) {
    .hero-search-divider {
      border-bottom: 1px solid #e85d26;
    }
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

  .hero-ad-container {
    width: 375px;
    height: 98px;
    margin: 0 auto;
  }
  @media (max-width: 380px) {
    .hero-ad-container {
      width: 100%;
    }
  }
  @media (min-width: 768px) {
    .hero-ad-container {
      width: 100%;
      height: 220px;
    }
  }
  @media (min-width: 1024px) {
    .hero-ad-container {
      width: 100%;
      height: 280px; /* Maintains roughly the same aspect ratio for desktop */
    }
  }
</style>

<section class="relative overflow-hidden" style="height:55vh; min-height:400px; max-height:550px;">

  {{-- ── Background Slider ── --}}
  <div class="absolute inset-0 z-0 bg-[#1A1A24]">
    <div id="heroSlider"
      style="display:flex; width:100%; height:100%; transition:transform 1.2s cubic-bezier(0.65,0,0.35,1); will-change:transform;">
      @php
        $dbBanners = $banners && $banners->isNotEmpty() ? $banners->toArray() : [];
        $slides = [];
        if (!empty($dbBanners)) {
          foreach ($dbBanners as $b) {
            $slides[] = $b->image;
          }
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
          if ($isVideo)
            $hasVideo = true;
        @endphp
        <div style="flex:0 0 100%; width:100%; height:100%; position:relative; overflow:hidden;">
          @if($isVideo)
            <video class="hero-video" src="{{ asset($url) }}" autoplay loop muted playsinline
              style="width:100%; height:100%; object-fit:cover; position:absolute; top:0; left:0;"></video>
          @else
            <div
              style="width:100%; height:100%; background-image:url('{{ asset($url) }}'); background-size:cover; background-position:center;">
            </div>
          @endif
        </div>
      @endforeach
    </div>
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/30 to-black/60 pointer-events-none"></div>
    {{-- Background Music --}}
    <audio id="heroBgMusic" src="{{ asset('audio/bg_music.mp3') }}?v={{ time() }}" loop></audio>
  </div>

  <div class="absolute bottom-2 right-4 md:bottom-8 md:right-8 z-40 select-none hero-sound-toggle-wrapper">
    <button type="button" onclick="toggleHeroSound()" id="heroSoundToggle"
      class="w-8 h-8 rounded-full bg-[#e85d26] hover:bg-orange-600 flex items-center justify-center text-white transition-all shadow-md focus:outline-none"
      style="box-shadow: 0 0 10px rgba(232, 93, 38, 0.4);">
      <!-- Music On Icon -->
      <svg id="musicOnIcon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
        class="w-4 h-4 text-white">
        <path d="M9 18V5l12-2v13"></path>
        <circle cx="6" cy="18" r="3"></circle>
        <circle cx="18" cy="16" r="3"></circle>
      </svg>
      <!-- Music Off Icon (hidden by default) -->
      <svg id="musicOffIcon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
        class="w-4 h-4 text-white hidden">
        <line x1="2" y1="2" x2="22" y2="22"></line>
        <path d="M9 13V5l12-2v9"></path>
        <circle cx="6" cy="18" r="3"></circle>
        <circle cx="18" cy="16" r="3"></circle>
      </svg>
    </button>
  </div>



  {{-- Top/Bottom Gradients --}}
  <div class="absolute inset-0 z-10 pointer-events-none"
    style="background:linear-gradient(to bottom, rgba(0,0,0,0.6) 0%, transparent 25%, transparent 60%, rgba(0,0,0,0.4) 100%);">
  </div>

  {{-- ── Main Hero Content (Centered Text + Search Form) ── --}}
  <div
    class="absolute inset-0 z-20 flex flex-col items-center justify-end text-center px-4 md:px-8 pb-4 md:pb-6 hero-content-wrapper">

    {{-- Main Headline --}}
    <h1 class="font-black leading-snug w-full mb-2 md:mb-4 hero-mobile-title"
      style="color:#ffffff !important; text-shadow:0 2px 12px rgba(0,0,0,0.5); font-size: 36px !important;">
      Discover Exclusive Travel Packages from <br class="hidden sm:block">Local Agents Near You!
    </h1><br>

    <div class="w-full max-w-7xl">
      <form action="{{ route('search') }}" method="GET">
        <div
          style="background:rgba(255,255,255,0.85); backdrop-filter:blur(12px); -webkit-backdrop-filter:blur(12px); border:1px solid #e85d26; border-radius:8px; box-shadow: 0 8px 32px 0 rgba(0,0,0,0.15);"
          class="flex flex-col md:flex-row items-center gap-0 overflow-hidden">

          {{-- Destination Field --}}
          <div class="flex items-center gap-3 flex-1 w-full md:w-auto px-4 py-3 md:px-6 md:py-4 hero-search-divider">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e85d26" stroke-width="2.5">
              <circle cx="11" cy="11" r="8" />
              <path d="m21 21-4.35-4.35" />
            </svg> 
            <input type="text" name="destination" placeholder="Where You Go !!!"
              class="bg-transparent border-none focus:ring-0 text-[#e85d26] placeholder-[#e85d26] text-[15px] font-bold outline-none w-full orange-placeholder"
              style="box-shadow: none;" value="{{ request('destination') }}">
          </div>

          {{-- Agent/City Field --}}
          <div class="flex items-center gap-3 flex-1 w-full md:w-auto px-4 py-3 md:px-6 md:py-4">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e85d26" stroke-width="2.5">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
            <input type="text" name="from_city" placeholder="Search agent from your city/near by location"
              class="bg-transparent border-none focus:ring-0 text-[#e85d26] placeholder-[#e85d26] text-[15px] font-bold outline-none w-full orange-placeholder"
              style="box-shadow: none;" value="{{ request('from_city') }}">
          </div>

          {{-- Search Button --}}
          <div class="px-4 py-3 md:px-2 md:py-2 flex-shrink-0 w-full md:w-auto">
            <button type="submit" style="background:#e85d26;"
              class="rounded-full px-8 py-3 text-white font-bold text-sm hover:bg-orange-600 transition-colors w-full sm:w-auto">
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

    function updateSoundToggleState(isEnabled) {
      const toggle = document.getElementById('heroSoundToggle');
      const onIcon = document.getElementById('musicOnIcon');
      const offIcon = document.getElementById('musicOffIcon');
      if (!toggle) return;
      if (isEnabled) {
        toggle.style.backgroundColor = '#e85d26';
        if (onIcon) onIcon.classList.remove('hidden');
        if (offIcon) offIcon.classList.add('hidden');
      } else {
        toggle.style.backgroundColor = '#4b5563';
        if (onIcon) onIcon.classList.add('hidden');
        if (offIcon) offIcon.classList.remove('hidden');
      }
    }

    function toggleHeroSound() {
      const bgMusic = document.getElementById('heroBgMusic');
      if (!bgMusic) return;
      if (bgMusic.paused) {
        bgMusic.play().then(() => {
          updateSoundToggleState(true);
          sessionStorage.setItem('heroMusicState', 'enabled');
        }).catch(e => console.log('Audio play failed:', e));
      } else {
        bgMusic.pause();
        updateSoundToggleState(false);
        sessionStorage.setItem('heroMusicState', 'disabled');
      }
    }

    document.addEventListener('DOMContentLoaded', () => {
      slideInterval = setInterval(nextHeroSlide, 5000);

      const bgMusic = document.getElementById('heroBgMusic');
      if (bgMusic) {
        bgMusic.volume = 0.5;

        const state = sessionStorage.getItem('heroMusicState');

        if (state === 'disabled' || state === 'played') {
          // If it was explicitly disabled, or it already played on first load, don't autoplay on refresh.
          bgMusic.pause();
          updateSoundToggleState(false);
        } else if (state === 'enabled') {
          // If explicitly enabled, play it.
          bgMusic.play().then(() => {
            updateSoundToggleState(true);
          }).catch(e => {
            updateSoundToggleState(false);
          });
        } else {
          // First time visit
          const startPlay = () => {
            if (sessionStorage.getItem('heroMusicState') === 'disabled') return;
            bgMusic.play()
              .then(() => {
                updateSoundToggleState(true);
                document.removeEventListener('click', startPlay);
                document.removeEventListener('keydown', startPlay);
                if (!sessionStorage.getItem('heroMusicState')) {
                  sessionStorage.setItem('heroMusicState', 'played');
                }
              })
              .catch(e => {
                console.log('Autoplay blocked, waiting for interaction...', e);
              });
          };

          startPlay();
          document.addEventListener('click', startPlay);
          document.addEventListener('keydown', startPlay);
        }
      }
    });
  </script>


  {{-- Bottom fade blur: image fades into white below --}}
  <div class="absolute bottom-0 left-0 right-0 z-10 pointer-events-none"
    style="height:60px; background:linear-gradient(to bottom, transparent 0%, rgba(255,255,255,0.6) 60%, #ffffff 100%); backdrop-filter:blur(2px); -webkit-backdrop-filter:blur(2px);">
  </div>
</section>

{{-- ── Popular Transits (below hero, white bg) ── --}}
<section id="popular-transits-section" class="bg-white pt-8 pb-4 md:pt-12 md:pb-6">
  <div class="container-custom">
    <h2 class="font-black text-foreground tracking-tight font-heading mb-2 md:mb-4 text-center"
      style="font-size: 28px;">Popular Transits</h2>
    <div
      class="flex flex-nowrap justify-center items-start gap-2 md:gap-6 lg:gap-8 pb-4 md:pb-0 px-2 mx-auto w-full overflow-hidden">
      @php
        try {
          $dbTransits = DB::table('transits')->where('status', 'Active')->get();
          $dbTransits = $dbTransits->sortBy(function($t) {
              $name = strtolower(trim($t->name));
              $orderMap = [
                  'land' => 1,
                  'bullet' => 2,
                  'flight' => 3,
                  'train' => 4,
                  'bus' => 5,
                  'cruise' => 6,
                  'tracking' => 7,
                  'helicopter' => 8,
              ];
              $norm = $name;
              if (str_contains($name, 'land') || str_contains($name, 'custom')) $norm = 'land';
              elseif (str_contains($name, 'bullet') || str_contains($name, 'bike')) $norm = 'bullet';
              elseif (str_contains($name, 'flight') || str_contains($name, 'air')) $norm = 'flight';
              elseif (str_contains($name, 'train') || str_contains($name, 'rail')) $norm = 'train';
              elseif (str_contains($name, 'bus') || str_contains($name, 'coach')) $norm = 'bus';
              elseif (str_contains($name, 'cruise') || str_contains($name, 'ship') || str_contains($name, 'boat')) $norm = 'cruise';
              elseif (str_contains($name, 'track') || str_contains($name, 'hike') || str_contains($name, 'trek')) $norm = 'tracking';
              elseif (str_contains($name, 'helicopter') || str_contains($name, 'sky')) $norm = 'helicopter';
              return $orderMap[$norm] ?? 999;
          });
        } catch (\Exception $e) {
          $dbTransits = collect();
        }

        if ($dbTransits->isEmpty()) {
          $dbTransits = collect([
            (object) [
              'name' => 'Flight Package',
              'selected_icon' => 'plane',
              'svg_icon' => null,
              'image' => null,
            ],
            (object) [
              'name' => 'Train Package',
              'selected_icon' => 'train',
              'svg_icon' => null,
              'image' => null,
            ],
            (object) [
              'name' => 'Bus Package',
              'selected_icon' => 'bus',
              'svg_icon' => null,
              'image' => null,
            ],
            (object) [
              'name' => 'Cruise Package',
              'selected_icon' => 'ship',
              'svg_icon' => null,
              'image' => null,
            ],
            (object) [
              'name' => 'Helicopter Package',
              'selected_icon' => 'helicopter',
              'svg_icon' => null,
              'image' => null,
            ],
          ]);
        }

        $gifMap = [
          'flight' => 'images/airplane.gif',
          'plane' => 'images/airplane.gif',
          'train' => 'images/train.gif',
          'bus' => 'images/bus.gif',
          'bike' => 'images/motorcycle.gif',
          'motorcycle' => 'images/motorcycle.gif',
          'ship' => 'images/cruise-ship.gif',
          'cruise' => 'images/cruise-ship.gif',
          'footprints' => 'images/hiking.gif',
          'user' => 'images/hiking.gif',
          'helicopter' => 'images/helicopter.gif',
          'car' => 'images/beach.gif',
          'map-pin' => 'images/beach.gif',
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
          class="group flex-1 min-w-0 max-w-[85px] md:max-w-[105px] lg:max-w-[125px] flex flex-col items-center gap-1 md:gap-2 cursor-pointer hover:opacity-80 transition-opacity flex-shrink">
          <div class="w-14 h-14 md:w-16 md:h-16 lg:w-[4.5rem] lg:h-[4.5rem] flex items-center justify-center">
            <img src="{{ asset($imgUrl) }}" alt="{{ $t->name }}"
              class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
          </div>
          <span
            class="text-center text-[12px] md:text-[14px] lg:text-[15px] font-bold text-gray-600 leading-tight whitespace-pre-line group-hover:text-[#e85d26] transition-colors w-full truncate md:overflow-visible md:whitespace-pre-line">{{ $label }}</span>
        </a>
      @endforeach
    </div>
  </div>
</section>

{{-- ── ADS Section ── --}}
<section class="bg-white pb-8 md:pb-12 border-b border-gray-100">
  <div class="container-custom">
    @if(isset($homeAd) && count($homeAd) > 0)
        <!-- Slider Wrapper -->
        <div class="w-full overflow-hidden relative">
            <!-- Slider Track -->
            <div class="flex transition-transform duration-500 ease-in-out" id="homeAdSlider" style="width: 100%;">
                @foreach($homeAd as $ad)
                  <div class="w-full flex-shrink-0" style="flex: 0 0 100%;">
                      <a href="{{ route('ad.click', ['id' => $ad->id ?? 0]) }}" target="_blank"
                        class="block shadow-sm hover:shadow-md transition-shadow overflow-hidden group relative hero-ad-container">
                        <span
                          class="absolute top-3 left-3 bg-black/60 text-white font-extrabold text-[9px] uppercase tracking-wider px-2.5 py-1 rounded-md z-10 pointer-events-none font-sans">AD</span>
                        <img src="{{ asset($ad->image ?? '') }}" alt="{{ $ad->campaign_name ?? 'Ad' }}"
                          class="w-full h-full object-fill block group-hover:scale-[1.02] transition-transform duration-500">
                      </a>
                  </div>
                @endforeach
            </div>
        </div>

        @if(count($homeAd) > 1)
            <!-- Dots -->
            <div class="flex justify-center gap-2 mt-4" id="homeAdDots">
                @foreach($homeAd as $index => $ad)
                    <button class="w-2.5 h-2.5 rounded-full {{ $index === 0 ? 'bg-[#e85d26]' : 'bg-gray-300' }} transition-colors" onclick="goToHomeAd({{ $index }})"></button>
                @endforeach
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let currentAdIndex = 0;
                    const adCount = {{ count($homeAd) }};
                    const slider = document.getElementById('homeAdSlider');
                    const dots = document.getElementById('homeAdDots').querySelectorAll('button');

                    window.goToHomeAd = function(index) {
                        currentAdIndex = index;
                        updateHomeAdSlider();
                    };

                    function updateHomeAdSlider() {
                        slider.style.transform = `translateX(-${currentAdIndex * 100}%)`;
                        dots.forEach((dot, idx) => {
                            if(idx === currentAdIndex) {
                                dot.classList.remove('bg-gray-300');
                                dot.classList.add('bg-[#e85d26]');
                            } else {
                                dot.classList.remove('bg-[#e85d26]');
                                dot.classList.add('bg-gray-300');
                            }
                        });
                    }

                    setInterval(() => {
                        currentAdIndex = (currentAdIndex + 1) % adCount;
                        updateHomeAdSlider();
                    }, 5000);
                });
            </script>
        @endif
    @else
      <div
        class="bg-[#d5d5d5] flex items-center justify-center shadow-sm hover:shadow-md transition-shadow cursor-pointer hero-ad-container">
        <span class="text-lg md:text-xl font-black text-black tracking-widest">ADS</span>
      </div>
    @endif
  </div>
</section>
{{-- Navbar transit visibility is handled by Alpine isScrolled in navbar.blade.php --}}