<nav 
    :class="(isScrolled || !isHome) ? 'bg-white shadow-sm py-4' : 'bg-transparent py-8'"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-500"
>

    <div class="container-custom flex items-center justify-between relative w-full">
        <!-- Logo -->
        <div class="flex items-center justify-start flex-shrink-0 z-10">
            <a href="{{ url('/') }}" class="flex items-center group">
                <x-logo x-bind:class="(isScrolled || !isHome) ? 'text-foreground' : 'text-white'" class="h-8 sm:h-10 md:h-12 w-auto transition-colors duration-300" />
            </a>
        </div>

        <!-- Centered Menu (Transit Icons) -->
        @php
          $isHome = request()->is('/');
          $dbTransits = DB::table('transits')->where('status', 'Active')->get();
          $sortOrder = [
              'Land/Customised Packages' => 1,
              'Flight Package' => 2,
              'Train Package' => 3,
              'Bus Package' => 4,
              'Bullet Packages' => 5,
              'Cruise Package' => 6,
              'Tracking Package' => 7,
              'Helicopter Package' => 8,
          ];
          $dbTransits = $dbTransits->sortBy(function($t) use ($sortOrder) {
              return $sortOrder[$t->name] ?? 999;
          });
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
        
        <div class="flex-1 relative flex justify-center items-center z-20">
            <div id="navbar-transits" class="absolute top-1/2 left-1/2 -translate-x-1/2 hidden lg:flex flex-nowrap w-auto items-center justify-center gap-1 sm:gap-2.5 md:gap-3.5 px-2 transition-all duration-500 z-50" :class="(isPastTransits || !isHome) ? 'opacity-100 scale-100 -translate-y-1/2' : 'opacity-0 scale-90 translate-y-0 pointer-events-none'">
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
                      $shortLabel = str_replace(" Package", "", $t->name);
                      if ($shortLabel === 'Int. tour by Car') {
                          $shortLabel = 'Car';
                      }
                  @endphp
                  <a href="{{ url('/discover?tour_type=' . urlencode($cleanType)) }}" class="flex flex-col items-center group w-12 sm:w-14 flex-shrink-0">
                     <div class="w-8 h-8 md:w-9 md:h-9 flex items-center justify-center">
                        <img src="{{ asset($imgUrl) }}" alt="{{ $t->name }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
                     </div>
                     <span class="text-[9px] md:text-[9.5px] font-bold text-gray-700 tracking-tight group-hover:text-primary transition-colors mt-0.5 truncate w-full text-center leading-tight">{{ $shortLabel }}</span>
                  </a>
                @endforeach
            </div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 hidden lg:flex items-center gap-8 text-[15px] font-bold text-white transition-all duration-500 z-50 whitespace-nowrap" :class="(!isScrolled && isHome) ? 'opacity-100 scale-100 -translate-y-1/2' : 'opacity-0 scale-95 -translate-y-[80%] pointer-events-none'">
                <a href="{{ url('/') }}" class="hover:text-white transition-colors flex items-center gap-1.5 relative group whitespace-nowrap {{ request()->is('/') ? 'text-white' : 'text-white/80' }}">
                    Home
                    <span class="absolute -bottom-1 left-0 {{ request()->is('/') ? 'w-full' : 'w-0' }} h-0.5 bg-white group-hover:w-full transition-all duration-300"></span>
                </a>
                @if(!request()->is('discover'))
                <a href="{{ url('/discover') }}" class="hover:text-white transition-colors flex items-center gap-1.5 relative group whitespace-nowrap {{ request()->is('discover') ? 'text-white' : 'text-white/80' }}">
                    Top Destinations
                    <span class="absolute -bottom-1 left-0 {{ request()->is('discover') ? 'w-full' : 'w-0' }} h-0.5 bg-white group-hover:w-full transition-all duration-300"></span>
                </a>
                @endif
            </div>
        </div>

        <!-- Right Side Icons & Actions -->
        <div class="flex items-center justify-end gap-4 lg:gap-6 flex-shrink-0 z-10">
            
            <div class="hidden lg:flex items-center gap-5 text-[13px] font-bold transition-all duration-500 overflow-hidden" :class="(isScrolled || !isHome) ? 'opacity-100 pointer-events-auto max-w-[500px] mr-2 text-text-main' : 'opacity-0 pointer-events-none max-w-0 mr-0'">
                @if(!request()->is('discover'))
                <a href="{{ url('/discover') }}" class="hover:text-primary transition-colors flex items-center gap-1.5 relative group whitespace-nowrap {{ request()->is('discover') ? 'text-primary' : '' }}">
                    Top Destinations
                    <span class="absolute -bottom-1 left-0 {{ request()->is('discover') ? 'w-full' : 'w-0' }} h-0.5 bg-primary group-hover:w-full transition-all duration-300"></span>
                </a>
                @endif
            </div>
            
            @if(Auth::check())
                <!-- Wishlist (Logged In) -->
                <div class="relative hidden lg:block" x-data="{ open: false }" @click.away="open = false">
                    <button 
                        @click="open = !open"
                        class="relative flex items-center gap-2.5 text-left group"
                    >
                        <div class="relative flex items-center justify-center group-hover:scale-105 transition-transform duration-300 flex-shrink-0">
                            <i data-lucide="heart" size="20" class="text-primary"></i>
                            <span id="wishlist-count" class="absolute -top-1 -right-1 w-4 h-4 bg-black text-white text-[10px] font-black rounded-full flex items-center justify-center hidden">0</span>
                        </div>
                        <div class="hidden xl:flex flex-col justify-center transition-colors duration-300" :class="(isScrolled || !isHome) ? 'text-foreground' : 'text-white'">
                            <span class="text-[14px] font-black leading-tight tracking-wide">Wishlist</span>
                            <span class="text-[10px] font-bold opacity-80 leading-tight">Save favourites</span>
                        </div>
                    </button>

                    <!-- Wishlist Dropdown -->
                    <div 
                        x-show="open" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="absolute right-0 mt-3 w-80 bg-white rounded-3xl shadow-premium border border-border-soft overflow-hidden"
                        style="display: none;"
                    >
                        <div class="p-5 border-b border-border-soft">
                            <h4 class="text-sm font-black text-foreground uppercase tracking-widest">My Wishlist</h4>
                        </div>
                        <div id="wishlist-items" class="max-h-96 overflow-y-auto p-4 space-y-4">
                            <!-- Items will be injected here -->
                            <p class="text-center text-text-muted text-xs py-8 font-bold">Your wishlist is empty</p>
                        </div>
                        <div class="p-4 bg-gray-50">
                            <a href="{{ url('/listing') }}" class="block w-full py-3 bg-black text-white text-center text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-primary transition-all">Explore More</a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Wishlist (Not Logged In -> Triggers Modal) -->
                <div class="relative hidden lg:block">
                    <button 
                        @click="$dispatch('open-login-modal')"
                        class="relative flex items-center gap-2.5 text-left group"
                    >
                        <div class="relative flex items-center justify-center group-hover:scale-105 transition-transform duration-300 flex-shrink-0">
                            <i data-lucide="heart" size="20" class="text-primary"></i>
                        </div>
                        <div class="hidden xl:flex flex-col justify-center transition-colors duration-300" :class="(isScrolled || !isHome) ? 'text-foreground' : 'text-white'">
                            <span class="text-[14px] font-black leading-tight tracking-wide">Wishlist</span>
                            <span class="text-[10px] font-bold opacity-80 leading-tight">Save favourites</span>
                        </div>
                    </button>
                </div>
            @endif

            @if(Auth::check())
                <a href="{{ url('/profile') }}" class="hidden lg:flex items-center justify-center w-10 h-10 bg-primary text-white rounded-full transition-all shadow-sm border border-black/5 hover:scale-105 duration-300 ml-2">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset(Auth::user()->avatar) }}" class="w-full h-full rounded-full object-cover">
                    @else
                        <img src="{{ asset('images/default-avatar.svg') }}" class="w-full h-full rounded-full object-cover">
                    @endif
                </a>
            @endif



            <!-- Mobile Wishlist Icon -->
            <button 
                @click="{{ Auth::check() ? 'window.location.href=\''.url('/profile').'\'' : '$dispatch(\'open-login-modal\')' }}"
                class="hidden relative flex items-center justify-center text-primary"
            >
                <i data-lucide="heart" size="20" class=""></i>
            </button>

            <!-- Mobile Toggle -->
            <button 
                @click="isMobileMenuOpen = true"
                :class="(isScrolled || !isHome) ? 'text-foreground' : 'text-white'"
                class="lg:hidden p-2.5 ml-1"
            >
                <i data-lucide="menu" size="28"></i>
            </button>
        </div>
    </div>
</nav>
