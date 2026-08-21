<nav 
    :class="(isScrolled || !isHome) ? 'bg-white shadow-sm py-5 lg:py-6' : 'bg-transparent py-8'"
    class="fixed top-0 left-0 right-0 z-[100] transition-all duration-500"
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
          $dbTransits = DB::table('transits')->where('status', 'Active')->orderBy('sr_no', 'asc')->get();

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
        
        <div class="flex-1 relative flex justify-center items-center z-20">
            <div id="navbar-transits" class="absolute top-1/2 left-1/2 -translate-x-1/2 hidden lg:flex flex-nowrap max-w-full w-max items-center justify-center gap-2 xl:gap-4 2xl:gap-8 px-2 transition-all duration-500 z-50 opacity-0 scale-90 translate-y-0 pointer-events-none" :class="{ 'opacity-100 scale-100 -translate-y-1/2 pointer-events-auto': isPastTransits || !isHome, 'opacity-0 scale-90 translate-y-0 pointer-events-none': !(isPastTransits || !isHome) }">
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
                      $label = str_replace(" Packages", "<br>Packages", e(trim($t->name)));
                      $label = str_replace(" Package", "<br>Package", $label);
                  @endphp
                  <a href="{{ url('/discover?tour_type=' . urlencode($cleanType)) }}" class="flex flex-col items-center justify-center group flex-1 min-w-[60px] max-w-[90px] xl:max-w-[120px] text-center">
                     <div class="w-8 h-8 xl:w-9 xl:h-9 2xl:w-11 2xl:h-11 flex items-center justify-center shrink-0">
                        <img src="{{ asset($imgUrl) }}" alt="{{ $t->name }}" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
                     </div>
                     <span class="block text-[9px] xl:text-[10px] 2xl:text-[12px] font-bold text-gray-700 tracking-tight group-hover:text-primary transition-colors mt-1 text-center leading-tight whitespace-normal">{!! $label !!}</span>
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
                <div class="relative hidden lg:block mr-2" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button 
                        @click="window.location.href='{{ url('/profile') }}'"
                        class="relative flex items-center gap-2.5 text-left group"
                    >
                        <div class="relative flex items-center justify-center group-hover:scale-105 transition-transform duration-300 flex-shrink-0">
                            <i data-lucide="heart" size="20" class="text-primary nav-heart-icon"></i>
                            @php
                                $wishlistCount = \Illuminate\Support\Facades\DB::table('user_wishlists')->where('user_id', Auth::id())->count();
                            @endphp
                            @if($wishlistCount > 0)
                                <span class="absolute -top-1.5 -right-1.5 flex items-center justify-center min-w-[16px] h-4 px-1 text-[9px] font-black text-white bg-primary rounded-full border border-white shadow-sm">
                                    {{ $wishlistCount }}
                                </span>
                            @endif
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
                
                @php
                    $navProfile = DB::table('user_profiles')->where('user_id', Auth::id())->first();
                    $navAvatarUrl = ($navProfile && $navProfile->avatar)
                        ? asset($navProfile->avatar)
                        : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'User') . '&background=e85d26&color=fff';
                    
                    $profileLink = url('/profile');
                    if (Auth::user()->role === 'Admin') $profileLink = url('/admin/dashboard');
                    elseif (Auth::user()->role === 'Agent') $profileLink = url('/agent/dashboard');
                @endphp
                <a href="{{ $profileLink }}" class="hidden lg:flex items-center justify-center w-10 h-10 bg-primary text-white rounded-full transition-all shadow-sm border border-black/5 hover:scale-105 duration-300 ml-2 overflow-hidden">
                    <img src="{{ $navAvatarUrl }}" class="w-full h-full object-cover">
                </a>
            @else
                <!-- Wishlist (Not Logged In) -->
                <div class="relative hidden lg:block mr-2">
                    <button 
                        @click="$dispatch('open-login-modal')"
                        class="relative flex items-center gap-2.5 text-left group"
                    >
                        <div class="relative flex items-center justify-center group-hover:scale-105 transition-transform duration-300 flex-shrink-0">
                            <i data-lucide="heart" size="20" class="text-primary nav-heart-icon"></i>
                        </div>
                        <div class="hidden xl:flex flex-col justify-center transition-colors duration-300" :class="(isScrolled || !isHome) ? 'text-foreground' : 'text-white'">
                            <span class="text-[14px] font-black leading-tight tracking-wide">Wishlist</span>
                            <span class="text-[10px] font-bold opacity-80 leading-tight">Save favourites</span>
                        </div>
                    </button>
                </div>
                
                <!-- Profile Icon (Not Logged In -> Triggers Modal) -->
                <button 
                    @click="$dispatch('open-login-modal')"
                    class="hidden lg:flex items-center justify-center w-10 h-10 bg-primary text-white rounded-full transition-all shadow-sm border border-black/5 hover:scale-105 duration-300 ml-2 overflow-hidden"
                >
                    <i data-lucide="user" size="20"></i>
                </button>
            @endif



            <!-- Mobile Wishlist Icon -->
            <button 
                @click="{{ Auth::check() ? 'window.location.href=\''.(Auth::user()->role === 'Admin' ? url('/admin/dashboard') : (Auth::user()->role === 'Agent' ? url('/agent/dashboard') : url('/profile'))).'\'' : '$dispatch(\'open-login-modal\')' }}"
                class="hidden relative flex items-center justify-center text-primary"
            >
                <i data-lucide="heart" size="20" class="text-primary nav-heart-icon"></i>
            </button>
            
            <!-- Mobile Profile Icon -->
            <button 
                @click="{{ Auth::check() ? 'window.location.href=\''.(Auth::user()->role === 'Admin' ? url('/admin/dashboard') : (Auth::user()->role === 'Agent' ? url('/agent/dashboard') : url('/profile'))).'\'' : '$dispatch(\'open-login-modal\')' }}"
                class="lg:hidden flex items-center justify-center w-8 h-8 bg-primary text-white rounded-full ml-2 overflow-hidden"
            >
                @if(Auth::check())
                    <img src="{{ $navAvatarUrl ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name ?? 'User').'&background=e85d26&color=fff' }}" class="w-full h-full object-cover">
                @else
                    <i data-lucide="user" size="16"></i>
                @endif
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
