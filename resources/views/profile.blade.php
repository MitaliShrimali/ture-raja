@extends('layouts.app')

@section('content')
@php
    $userData       = $user ?? null;
    $profileData    = $profile ?? null;
    $displayName    = $userData ? $userData->name  : 'Guest User';
    $displayEmail   = $userData ? $userData->email : '';
    $displayPhone   = $profileData ? ($profileData->phone         ?? '') : '';
    $displayCity    = $profileData ? ($profileData->city          ?? '') : '';
    $displayCountry = $profileData ? ($profileData->country       ?? '') : '';
    $displayDOB     = $profileData ? ($profileData->date_of_birth ?? '') : '';
    $displayGender  = $profileData ? ($profileData->gender        ?? '') : '';
    $displayAddress = trim($displayCity . ($displayCountry ? ', '.$displayCountry : ''));
    $avatarUrl      = ($profileData && $profileData->avatar)
        ? asset('storage/'.$profileData->avatar)
        : 'https://api.dicebear.com/7.x/avataaars/svg?seed='.urlencode($displayName);
    $wishlistItems  = (isset($wishlist) && $wishlist)  ? $wishlist  : collect();
    $bookingItems   = (isset($bookings) && $bookings)  ? $bookings  : collect();
    $activePlan     = $activePlan ?? null;
    $userPayments   = $userPayments ?? [];
@endphp

@if(session('success'))
<div class="pf-toast pf-toast--ok">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="pf-toast pf-toast--err">{{ session('error') }}</div>
@endif

<div x-data="{ tab: 'account' }" class="pf-page">

  {{-- ══════════ HERO ══════════ --}}
  <div class="pf-hero">
    <div class="pf-hero-bg">
      <img src="{{ asset('tourex/hero-bg.png') }}" alt="cover">
    </div>
    <div class="pf-hero-overlay"></div>
    <div class="pf-hero-inner">
      {{-- "Profile" label top-left --}}
      <h1 class="pf-hero-label">Profile</h1>

      {{-- Avatar block centred --}}
      <div class="pf-hero-user">
        <form action="{{ route('profile.update') }}" method="POST"
              enctype="multipart/form-data" id="pf-av-form">
          @csrf
          <label for="pf-av-input" class="pf-avatar-wrap">
            <img src="{{ asset($avatarUrl) }}" alt="avatar" id="pf-av-img">
            <span class="pf-avatar-cam">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                   stroke="#fff" stroke-width="2.5">
                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2
                         0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                <circle cx="12" cy="13" r="4"/>
              </svg>
            </span>
          </label>
          <input type="file" id="pf-av-input" name="avatar"
                 accept="image/*" style="display:none"
                 onchange="pfUploadAvatar(this)">
        </form>
        <p class="pf-hero-name">{{ $displayName }}</p>
        <p class="pf-hero-email">{{ $displayEmail }}</p>
      </div>
    </div>
  </div>{{-- /hero --}}

  {{-- ══════════ TAB BAR ══════════ --}}
  <div class="pf-tabbar">
    <div class="pf-tabs-inner">
      <button @click="tab='account'"
              :class="tab==='account' ? 'pf-tab-on' : ''"
              class="pf-tab">Account</button>
      <button @click="tab='history'"
              :class="tab==='history' ? 'pf-tab-on' : ''"
              class="pf-tab">History</button>
      <button @click="tab='wishlist'"
              :class="tab==='wishlist' ? 'pf-tab-on' : ''"
              class="pf-tab">Wishlist</button>
    </div>
  </div>

  {{-- ══════════ CONTENT ══════════ --}}
  <div class="pf-content">

    {{-- ── ACCOUNT TAB ── --}}
    <div x-show="tab==='account'" x-transition.opacity.duration.200ms>

      {{-- Account fields --}}
      <section class="pf-section">
        <h2 class="pf-sec-h">Account</h2>

        <form action="{{ route('profile.update') }}" method="POST"
              enctype="multipart/form-data" id="pf-form">
          @csrf
          <div class="pf-fields-box">

            {{-- Name --}}
            <div class="pf-row" x-data="{open:false}">
              <div class="pf-row-left">
                <span class="pf-lbl">Name</span>
                <span class="pf-val" x-show="!open">{{ $displayName }}</span>
                <input x-show="open" x-ref="fi" type="text" name="name"
                       value="{{ $displayName }}" class="pf-inp">
              </div>
              <button type="button" class="pf-edit"
                      @click="open=!open;if(open)$nextTick(()=>$refs.fi.focus())">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2
                           0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1
                           1-4 9.5-9.5z"/>
                </svg>
                Edit
              </button>
            </div>

            {{-- Email --}}
            <div class="pf-row" x-data="{open:false}">
              <div class="pf-row-left">
                <span class="pf-lbl">Email</span>
                <span class="pf-val" x-show="!open">{{ $displayEmail }}</span>
                <input x-show="open" x-ref="fi" type="email" name="email"
                       value="{{ $displayEmail }}" class="pf-inp">
              </div>
              <button type="button" class="pf-edit"
                      @click="open=!open;if(open)$nextTick(()=>$refs.fi.focus())">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2
                           0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1
                           1-4 9.5-9.5z"/>
                </svg>
                Edit
              </button>
            </div>

            {{-- Password --}}
            <div class="pf-row" x-data="{open:false}">
              <div class="pf-row-left">
                <span class="pf-lbl">Password</span>
                <span class="pf-val" x-show="!open">••••••••••••••</span>
                <input x-show="open" x-ref="fi" type="password"
                       name="new_password" placeholder="New password"
                       class="pf-inp">
              </div>
              <button type="button" class="pf-edit"
                      @click="open=!open;if(open)$nextTick(()=>$refs.fi.focus())">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2
                           0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1
                           1-4 9.5-9.5z"/>
                </svg>
                Edit
              </button>
            </div>

            {{-- Phone --}}
            <div class="pf-row" x-data="{open:false}">
              <div class="pf-row-left">
                <span class="pf-lbl">Phone number</span>
                <span class="pf-val" x-show="!open">{{ $displayPhone ?: '—' }}</span>
                <input x-show="open" x-ref="fi" type="text" name="phone"
                       value="{{ $displayPhone }}" placeholder="+91 99999 00000"
                       class="pf-inp">
              </div>
              <button type="button" class="pf-edit"
                      @click="open=!open;if(open)$nextTick(()=>$refs.fi.focus())">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2
                           0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1
                           1-4 9.5-9.5z"/>
                </svg>
                Edit
              </button>
            </div>

            {{-- Address --}}
            <div class="pf-row" x-data="{open:false}">
              <div class="pf-row-left">
                <span class="pf-lbl">Address</span>
                <span class="pf-val" x-show="!open">{{ $displayAddress ?: '—' }}</span>
                <div x-show="open" class="pf-inp-row">
                  <input x-ref="fi" type="text" name="city"
                         value="{{ $displayCity }}" placeholder="City" class="pf-inp">
                  <input type="text" name="country"
                         value="{{ $displayCountry }}" placeholder="Country" class="pf-inp">
                </div>
              </div>
              <button type="button" class="pf-edit"
                      @click="open=!open;if(open)$nextTick(()=>$refs.fi.focus())">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2
                           0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1
                           1-4 9.5-9.5z"/>
                </svg>
                Edit
              </button>
            </div>

            {{-- Date of birth --}}
            <div class="pf-row" x-data="{open:false}">
              <div class="pf-row-left">
                <span class="pf-lbl">Date of birth</span>
                <span class="pf-val" x-show="!open">
                  {{ $displayDOB
                       ? \Carbon\Carbon::parse($displayDOB)->format('m-d-Y')
                       : '—' }}
                </span>
                <input x-show="open" x-ref="fi" type="date"
                       name="date_of_birth" value="{{ $displayDOB }}"
                       class="pf-inp">
              </div>
              <button type="button" class="pf-edit"
                      @click="open=!open;if(open)$nextTick(()=>$refs.fi.focus())">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                  <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2
                           0 0 0 2-2v-7"/>
                  <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1
                           1-4 9.5-9.5z"/>
                </svg>
                Edit
              </button>
            </div>

          </div>{{-- /pf-fields-box --}}

          <div class="pf-save-row" style="display: flex; justify-content: space-between; align-items: center;">
            <a href="{{ route('logout') }}" class="text-red-500 hover:text-red-600 hover:bg-red-50 font-semibold px-4 py-2 rounded-lg transition-colors" style="display: inline-flex; align-items: center; gap: 6px; text-decoration: none; border: 1px solid #fee2e2; background-color: #fef2f2;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
              </svg>
              Logout
            </a>
            <button type="submit" class="pf-save-btn">Save Changes</button>
          </div>
        </form>
      </section>



      {{-- ── Newsletter ── --}}
      <section class="pf-section">
        <div class="pf-news">
          <div class="pf-news-left">
            <span class="pf-news-tag">Join our newsletter</span>
            <h3 class="pf-news-heading">Subscribe to see secret deals<br>prices drop the moment you sign up!</h3>
            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="pf-news-form">
              @csrf
              <input type="email" name="email" value="{{ $displayEmail }}"
                     placeholder="Your Email" class="pf-news-input">
              <button type="submit" class="pf-news-btn">Subscribe</button>
            </form>
            <p class="pf-news-note">No ads. No tricks. No commitments.</p>
          </div>
          <div class="pf-news-img">
            <img src="https://images.unsplash.com/photo-1575037614876-c38a4d44f5b8?auto=format&fit=crop&w=600&q=80" alt="Newsletter">
          </div>
        </div>
      </section>

    </div>{{-- /account tab --}}

    {{-- ── HISTORY TAB ── --}}
    <div x-show="tab==='history'" x-transition.opacity.duration.200ms>

      {{-- ── Your History / Searches ── --}}
      <section class="pf-section pf-hist-section">
        <div class="pf-hist-head">
          <div>
            <h2 class="pf-hist-title">Your History/Searches</h2>
            <p class="pf-hist-sub">Favourite destinations based on customer reviews</p>
          </div>
          <div class="pf-filters">
            <button class="pf-flt">Categories
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <button class="pf-flt">Duration
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <button class="pf-flt">Review / Rating
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <button class="pf-flt">Price range
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
          </div>
        </div>

        @php
          /* ── Build destination list dynamically ──
             1. Pull unique destinations the user searched for
             2. For each, try to find a matching package (by location or title)
             3. Fall back to curated demo destinations if no searches exist
          */
          $searchedDests = isset($searchHistory) ? $searchHistory : collect();

          // Map each searched destination to a package image + slug
          $destCards = $searchedDests->map(function($s) use ($packages) {
            $term = $s->destination ?: $s->from_city;
            if (!$term) return null;
            // Try to match a package by location or title
            $pkg = $packages->first(function($p) use ($term) {
              $loc   = is_array($p) ? ($p['location'] ?? '') : ($p->location ?? '');
              $title = is_array($p) ? ($p['title']    ?? '') : ($p->title    ?? '');
              return stripos($loc, $term) !== false || stripos($title, $term) !== false
                  || stripos($term, $loc) !== false;
            });
            $img  = $pkg ? (is_array($pkg) ? $pkg['image']    : $pkg->image)    : null;
            $slug = $pkg ? (is_array($pkg) ? ($pkg['slug'] ?? Str::slug($pkg['title'])) : Str::slug($pkg->title)) : null;
            // If image looks like a relative path, wrap with asset()
            if ($img && !str_starts_with($img, 'http')) {
              $img = asset($img);
            }
            if (!$img) {
              // Generic Unsplash for the searched city
              $img = 'https://source.unsplash.com/400x300/?' . urlencode($term) . ',city,travel';
            }
            return ['name' => ucwords($term), 'image' => $img, 'slug' => $slug, 'count' => rand(80,400)];
          })->filter()->unique('name')->take(7)->values();

          // Also pull package locations from packages list to enrich
          $pkgLocations = $packages->map(function($p) {
            $img  = is_array($p) ? ($p['image'] ?? '') : ($p->image ?? '');
            $loc  = is_array($p) ? ($p['location'] ?? '') : ($p->location ?? '');
            $slug = is_array($p) ? ($p['slug']   ?? Str::slug($p['title'] ?? '')) : Str::slug($p->title ?? '');
            if ($img && !str_starts_with($img, 'http')) $img = asset($img);
            return ['name' => $loc, 'image' => $img, 'slug' => $slug, 'count' => rand(100,500)];
          })->filter(fn($x) => !empty($x['name']))->unique('name');

          // If fewer than 7 from search history, pad with package locations not already shown
          if ($destCards->count() < 7) {
            $existing = $destCards->pluck('name')->map('strtolower')->toArray();
            $extra = $pkgLocations->filter(fn($x) => !in_array(strtolower($x['name']), $existing))
                                  ->take(7 - $destCards->count())->values();
            $destCards = $destCards->concat($extra);
          }

          // Final fallback if still empty
          if ($destCards->isEmpty()) {
            $destCards = collect([
              ['name'=>'Venice',    'image'=>'https://images.unsplash.com/photo-1514890547357-a9ee288728e0?auto=format&fit=crop&w=400&q=80', 'slug'=>'venice',    'count'=>386],
              ['name'=>'Amsterdam', 'image'=>'https://images.unsplash.com/photo-1534351590666-13e3e96b5017?auto=format&fit=crop&w=400&q=80', 'slug'=>'amsterdam', 'count'=>356],
              ['name'=>'Budapest',  'image'=>'https://images.unsplash.com/photo-1551867633-194f125bddfa?auto=format&fit=crop&w=400&q=80', 'slug'=>'budapest',  'count'=>356],
              ['name'=>'Lisbon',    'image'=>'https://images.unsplash.com/photo-1555881400-74d7acaacd8b?auto=format&fit=crop&w=400&q=80', 'slug'=>'lisbon',    'count'=>356],
              ['name'=>'London',    'image'=>'https://images.unsplash.com/photo-1513635269975-59663e0ac1ad?auto=format&fit=crop&w=400&q=80', 'slug'=>'london',    'count'=>356],
              ['name'=>'Ottawa',    'image'=>'https://images.unsplash.com/photo-1503614472-8c93d56e92ce?auto=format&fit=crop&w=400&q=80', 'slug'=>'ottawa',    'count'=>356],
              ['name'=>'Paris',     'image'=>'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=400&q=80', 'slug'=>'paris',     'count'=>356],
            ]);
          }

          $firstRow  = $destCards->take(4);
          $secondRow = $destCards->skip(4)->take(3);
        @endphp

        {{-- Row 1: 4 cards --}}
        <div class="pf-dest-grid pf-dest-grid--4">
          @foreach($firstRow as $dc)
          <a href="{{ route('discover', ['search' => $dc['name']]) }}" class="pf-dest-card">
            <div class="pf-dest-img">
              <img src="{{ asset($dc['image']) }}" alt="{{ $dc['name'] }}" loading="lazy">
            </div>
            <div class="pf-dest-body">
              <p class="pf-dest-name">{{ $dc['name'] }}</p>
              <p class="pf-dest-meta">{{ $dc['count'] }}k Tours, 24k Activities</p>
            </div>
            <div class="pf-dest-arrow">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
          </a>
          @endforeach
        </div>

        {{-- Row 2: 3 cards + CTA card --}}
        <div class="pf-dest-grid pf-dest-grid--4" style="margin-top:16px">
          @foreach($secondRow as $dc)
          <a href="{{ route('discover', ['search' => $dc['name']]) }}" class="pf-dest-card">
            <div class="pf-dest-img">
              <img src="{{ asset($dc['image']) }}" alt="{{ $dc['name'] }}" loading="lazy">
            </div>
            <div class="pf-dest-body">
              <p class="pf-dest-name">{{ $dc['name'] }}</p>
              <p class="pf-dest-meta">{{ $dc['count'] }}k Tours, 24k Activities</p>
            </div>
            <div class="pf-dest-arrow">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
          </a>
          @endforeach
          {{-- CTA card --}}
          <div class="pf-dest-cta">
            <p class="pf-dest-cta-title">Crafting Your<br>Perfect Travel<br>Experience</p>
            <a href="{{ route('discover') }}" class="pf-dest-cta-btn">
              Browse
              <span>All destinations</span>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
          </div>
        </div>
      </section>

      {{-- ── Booking History List ── --}}
      <section class="pf-section">
        <h2 class="pf-sec-h">Booking History</h2>
        @forelse($bookingItems as $bk)
        <div class="pf-bk-card">
          <div class="pf-bk-img">
            <img src="{{ asset($bk->package_image ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=400&q=80') }}"
                 alt="{{ $bk->package_title }}">
          </div>
          <div class="pf-bk-info">
            <h4>{{ $bk->package_title }}</h4>
            <p>{{ $bk->guests }} Guest(s) &middot; {{ $bk->package_currency ?? '₹' }}{{ number_format($bk->package_price) }}</p>
            <span class="pf-badge pf-badge--{{ strtolower($bk->status) }}">{{ $bk->status }}</span>
          </div>
          <a href="{{ route('discover', ['search' => $bk->package_title]) }}" class="pf-bk-link">View →</a>
        </div>
        @empty
        <div class="pf-empty">
          <p>No bookings yet. Start exploring!</p>
          <a href="{{ route('discover') }}" class="pf-empty-btn">Browse Packages</a>
        </div>
        @endforelse
      </section>

    </div>

    {{-- ── WISHLIST TAB ── --}}
    <div x-show="tab==='wishlist'" x-transition.opacity.duration.200ms>

      {{-- Header (shown when items exist) --}}
      <div class="pf-wl-header" id="pf-wl-has-items" style="display:none">
        <div>
          <h2 class="pf-wl-title">My Wishlist</h2>
          <p class="pf-wl-sub" id="pf-wl-count-label">0 saved packages</p>
        </div>
        <a href="{{ route('discover') }}" class="pf-wl-browse-btn">+ Explore More</a>
      </div>

      {{-- Wishlist grid (JS-rendered) --}}
      <div class="pf-wl-grid" id="pf-wl-grid"></div>

      {{-- Empty state --}}
      <div class="pf-wl-empty" id="pf-wl-empty" style="display:none">
        <img src="{{ asset('tourex/travel-sticker.png') }}"
             onerror="this.src='https://illustrations.popsy.co/amber/traveling.svg'"
             alt="No wishlist items" class="pf-wl-empty-img">
        <p class="pf-wl-empty-text">You don't have any tickets yet. Start planning your next trip!</p>
      </div>

      {{-- Newsletter (shown when wishlist empty) --}}
      <section class="pf-section" id="pf-wl-newsletter" style="display:none">
        <div class="pf-news">
          <div class="pf-news-left">
            <span class="pf-news-tag">Join our newsletter</span>
            <h3 class="pf-news-heading">Subscribe to see secret deals<br>prices drop the moment you sign up!</h3>
            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="pf-news-form">
              @csrf
              <input type="email" name="email" value="{{ $displayEmail }}"
                     placeholder="Your Email" class="pf-news-input">
              <button type="submit" class="pf-news-btn">Subscribe</button>
            </form>
            <p class="pf-news-note">No ads. No trails. No commitments.</p>
          </div>
          <div class="pf-news-img">
            <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?auto=format&fit=crop&w=600&q=80" alt="Newsletter">
          </div>
        </div>
      </section>

    </div>{{-- /wishlist tab --}}

  </div>{{-- /pf-content --}}
</div>{{-- /pf-page --}}

@push('scripts')
<script>
function pfUploadAvatar(input) {
  if (!input.files || !input.files[0]) return;
  const r = new FileReader();
  r.onload = e => document.getElementById('pf-av-img').src = e.target.result;
  r.readAsDataURL(input.files[0]);
  document.getElementById('pf-av-form').submit();
}
// Auto-hide toasts
document.querySelectorAll('.pf-toast').forEach(t => {
  setTimeout(() => t.style.opacity = '0', 3500);
  setTimeout(() => t.remove(), 4000);
});

// ── Render wishlist from localStorage ──────────────────────────────────────
function pfRenderWishlist() {
  const wishlist = JSON.parse(localStorage.getItem('tourraja_wishlist') || '[]');
  const grid       = document.getElementById('pf-wl-grid');
  const header     = document.getElementById('pf-wl-has-items');
  const emptyState = document.getElementById('pf-wl-empty');
  const newsletter = document.getElementById('pf-wl-newsletter');
  const countLabel = document.getElementById('pf-wl-count-label');

  if (!grid) return;

  if (wishlist.length === 0) {
    grid.innerHTML        = '';
    header.style.display  = 'none';
    emptyState.style.display  = 'flex';
    newsletter.style.display  = 'block';
  } else {
    header.style.display  = 'flex';
    emptyState.style.display  = 'none';
    newsletter.style.display  = 'none';
    countLabel.textContent = wishlist.length + ' saved ' + (wishlist.length === 1 ? 'package' : 'packages');

    grid.innerHTML = wishlist.map((item, idx) => `
      <a href="/packages/${item.slug}" class="pf-wl-card" style="text-decoration:none">
        <div class="pf-wl-thumb">
          <img src="${item.image || 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=200&q=80'}"
               alt="${item.title}"
               onerror="this.src='https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=200&q=80'"
               loading="lazy">
        </div>
        <div class="pf-wl-info">
          <h4>${item.title}</h4>
          <p class="pf-wl-price-line">
            <span class="pf-wl-price">${item.currency || '₹'}${Number(item.price).toLocaleString('en-IN')}</span>
            <span class="pf-wl-per">/ person</span>
          </p>
        </div>
        <div class="pf-wl-actions">
          <span class="pf-wl-view">View →</span>
          <button class="pf-wl-remove" onclick="event.preventDefault();event.stopPropagation();pfRemoveWishlist('${item.slug}')" title="Remove">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
          </button>
        </div>
      </a>
    `).join('');
  }
}

function pfRemoveWishlist(slug) {
  let wishlist = JSON.parse(localStorage.getItem('tourraja_wishlist') || '[]');
  wishlist = wishlist.filter(item => item.slug !== slug);
  localStorage.setItem('tourraja_wishlist', JSON.stringify(wishlist));
  pfRenderWishlist();
  // Also sync removal to server
  if (typeof updateWishlistUI === 'function') updateWishlistUI();
}

// Run on page load + whenever tab switches to wishlist
document.addEventListener('DOMContentLoaded', () => {
  pfRenderWishlist();
  // Re-render when switching to wishlist tab
  document.querySelectorAll('[\\@click]').forEach(btn => {
    btn.addEventListener('click', () => {
      setTimeout(pfRenderWishlist, 50);
    });
  });
});
</script>
@endpush

<style>
/* ══════════════════════════════════
   PROFILE PAGE — pixel-exact
══════════════════════════════════ */
.pf-page { background:#f5f6f8; min-height:100vh; }

/* ── Hero ── */
.pf-hero {
  position:relative;
  height:240px;
  overflow:hidden;
}
.pf-hero-bg {
  position:absolute; inset:0;
}
.pf-hero-bg img {
  width:100%; height:100%; object-fit:cover; object-position:center 40%;
}
.pf-hero-overlay {
  position:absolute; inset:0;
  background:linear-gradient(to bottom, rgba(0,0,0,.30) 0%, rgba(0,0,0,.52) 100%);
}
.pf-hero-inner {
  position:relative; z-index:2;
  max-width:900px; margin:0 auto;
  padding:28px 28px 0;
  height:100%;
  display:flex; flex-direction:column;
}
/* "Profile" title — top-left */
.pf-hero-label {
  font-size:2rem; font-weight:900; color:#fff;
  font-family:'Syne',sans-serif; letter-spacing:-.02em;
  margin:0; text-shadow:0 2px 8px rgba(0,0,0,.35);
  align-self:flex-start;
}
/* Avatar block — centered */
.pf-hero-user {
  display:flex; flex-direction:column; align-items:center;
  margin-top:auto;
  transform:translateY(52px);
}
.pf-avatar-wrap {
  position:relative; display:block;
  width:92px; height:92px;
  border-radius:50%;
  border:3px solid #fff;
  overflow:visible;
  cursor:pointer;
  margin-bottom:10px;
}
.pf-avatar-wrap img {
  width:92px; height:92px;
  border-radius:50%; object-fit:cover; background:#fff; display:block;
}
.pf-avatar-cam {
  position:absolute; bottom:2px; right:2px;
  width:26px; height:26px;
  background:#e8663a; border-radius:50%;
  display:flex; align-items:center; justify-content:center;
  border:2px solid #fff; z-index:3;
}
.pf-hero-name {
  font-size:1.1rem; font-weight:800; color:#fff;
  font-family:'Syne',sans-serif; margin:0 0 3px;
}
.pf-hero-email {
  font-size:.78rem; color:rgba(255,255,255,.72); margin:0;
}

/* ── Tab Bar ── */
.pf-tabbar {
  background:#fff;
  border-bottom:1.5px solid #e5e7eb;
  margin-top:56px;
  position:sticky; top:0; z-index:30;
  box-shadow:0 2px 6px rgba(0,0,0,.04);
}
.pf-tabs-inner {
  display:flex;
  max-width:900px; margin:0 auto;
  padding:0;
  justify-content:space-evenly;
}
.pf-tab {
  flex:1;
  padding:18px 0 16px;
  font-size:.95rem; font-weight:600;
  color:#9ca3af;
  background:none; border:none;
  border-bottom:2.5px solid transparent;
  cursor:pointer; white-space:nowrap;
  text-align:center;
  letter-spacing:.01em;
  transition:color .18s, border-color .18s;
}
.pf-tab:hover { color:#e8663a; }
.pf-tab-on {
  color:#e8663a !important;
  border-bottom-color:#e8663a !important;
}

/* ── Content container ── */
.pf-content {
  max-width:900px; margin:0 auto;
  padding:36px 28px 64px;
}
.pf-section { margin-bottom:48px; }
.pf-sec-h {
  font-size:1.25rem; font-weight:800; color:#111827;
  font-family:'Syne',sans-serif; margin:0 0 18px;
}

/* ── Account field rows ── */
.pf-fields-box {
  border:1.5px solid #e5e7eb;
  border-radius:12px; overflow:hidden; background:#fff;
}
.pf-row {
  display:flex; align-items:center; justify-content:space-between;
  padding:16px 20px; gap:12px;
  border-bottom:1.5px solid #f3f4f6;
}
.pf-row:last-child { border-bottom:none; }
.pf-row-left {
  flex:1; min-width:0;
  display:flex; flex-direction:column; gap:3px;
}
.pf-lbl {
  font-size:.68rem; font-weight:700; color:#9ca3af;
  text-transform:uppercase; letter-spacing:.06em;
}
.pf-val {
  font-size:.93rem; font-weight:500; color:#111827;
}
.pf-inp {
  font-size:.9rem; font-weight:500; color:#111827;
  border:1.5px solid #e8663a; border-radius:8px;
  padding:7px 12px; background:#fff8f5; outline:none;
  width:100%; max-width:280px;
  transition:box-shadow .18s;
}
.pf-inp:focus { box-shadow:0 0 0 3px rgba(232,102,58,.18); }
.pf-inp-row { display:flex; gap:8px; flex-wrap:wrap; }
.pf-edit {
  display:flex; align-items:center; gap:5px;
  padding:6px 14px;
  font-size:.74rem; font-weight:600; color:#555;
  background:#fff; border:1.5px solid #d1d5db;
  border-radius:8px; cursor:pointer; white-space:nowrap; flex-shrink:0;
  transition:all .15s;
}
.pf-edit:hover { background:#e8663a; color:#fff; border-color:#e8663a; }
.pf-save-row { margin-top:16px; text-align:right; }
.pf-save-btn {
  background:#e8663a; color:#fff;
  padding:11px 28px; border-radius:999px;
  font-weight:700; font-size:.88rem; border:none; cursor:pointer;
  box-shadow:0 4px 14px rgba(232,102,58,.3);
  transition:background .18s, transform .1s;
}
.pf-save-btn:hover { background:#d04e1f; transform:translateY(-1px); }

/* ── History/Searches section ── */
.pf-hist-head {
  display:flex; justify-content:space-between;
  align-items:flex-start; flex-wrap:wrap; gap:14px;
  margin-bottom:20px;
}
.pf-hist-title {
  font-size:1.65rem; font-weight:900; color:#111827;
  font-family:'Syne',sans-serif; margin:0 0 4px;
}
.pf-hist-sub { font-size:.78rem; color:#9ca3af; margin:0; }
.pf-filters { display:flex; flex-wrap:wrap; gap:8px; align-items:center; }
.pf-flt {
  display:flex; align-items:center; gap:4px;
  padding:6px 12px; font-size:.73rem; font-weight:600; color:#555;
  background:#fff; border:1px solid #d1d5db; border-radius:999px;
  cursor:pointer; transition:all .15s;
}
.pf-flt:hover { border-color:#e8663a; color:#e8663a; }

/* destination cards — 4-col grid */
.pf-dest-grid {
  display:grid;
  gap:16px;
}
.pf-dest-grid--4 { grid-template-columns:repeat(4,1fr); }
.pf-dest-card {
  background:#fff; border-radius:12px; overflow:hidden;
  border:1px solid #f0f0f0;
  box-shadow:0 1px 5px rgba(0,0,0,.05);
  text-decoration:none; color:inherit;
  display:flex; flex-direction:column;
  transition:transform .2s, box-shadow .2s;
}
.pf-dest-card:hover { transform:translateY(-4px); box-shadow:0 8px 22px rgba(0,0,0,.1); }
.pf-dest-img { height:120px; overflow:hidden; }
.pf-dest-img img { width:100%; height:100%; object-fit:cover; transition:transform .4s; }
.pf-dest-card:hover .pf-dest-img img { transform:scale(1.07); }
.pf-dest-body { padding:10px 12px 2px; flex:1; }
.pf-dest-name { font-size:.88rem; font-weight:700; color:#111827; margin:0 0 3px; }
.pf-dest-meta { font-size:.7rem; color:#9ca3af; margin:0; }
.pf-dest-arrow {
  padding:6px 12px 10px;
  display:flex; align-items:center; justify-content:flex-end;
  color:#d1d5db; transition:color .15s;
}
.pf-dest-card:hover .pf-dest-arrow { color:#e8663a; }

/* CTA card */
.pf-dest-cta {
  background:#f8f9fb;
  border-radius:12px;
  border:1px solid #e9eaec;
  padding:22px 20px;
  display:flex; flex-direction:column; justify-content:space-between;
  min-height:180px;
}
.pf-dest-cta-title {
  font-size:.97rem; font-weight:800; color:#111827;
  font-family:'Syne',sans-serif; line-height:1.45; margin:0 0 20px;
}
.pf-dest-cta-btn {
  display:inline-flex; align-items:center; gap:8px;
  background:#111827; color:#fff;
  border-radius:999px;
  padding:10px 18px 10px 20px;
  font-size:.78rem; font-weight:700;
  text-decoration:none;
  transition:background .18s;
  width:fit-content;
}
.pf-dest-cta-btn span { font-size:.72rem; opacity:.7; font-weight:500; }
.pf-dest-cta-btn:hover { background:#e8663a; }

/* ── Newsletter ── */
.pf-news {
  background:#e5eef8;
  border-radius:16px; overflow:hidden;
  display:flex; min-height:190px;
}
.pf-news-left {
  flex:1; padding:32px 36px;
  display:flex; flex-direction:column; justify-content:center;
}
.pf-news-tag {
  display:inline-block;
  background:#e8663a; color:#fff;
  font-size:.72rem; font-weight:700;
  padding:5px 14px; border-radius:999px;
  margin-bottom:12px; width:fit-content;
}
.pf-news-heading {
  font-size:1.1rem; font-weight:800; color:#111827;
  line-height:1.45; margin:0 0 16px; max-width:300px;
}
.pf-news-form { display:flex; gap:8px; flex-wrap:wrap; }
.pf-news-input {
  flex:1; min-width:150px;
  padding:9px 14px; border:1.5px solid #c8d6e5;
  border-radius:8px; font-size:.83rem; outline:none; background:#fff;
}
.pf-news-input:focus { border-color:#e8663a; }
.pf-news-btn {
  background:#111827; color:#fff;
  padding:9px 18px; border-radius:8px;
  font-weight:700; font-size:.83rem; border:none; cursor:pointer;
  transition:background .18s;
}
.pf-news-btn:hover { background:#374151; }
.pf-news-note { font-size:.7rem; color:#9ca3af; margin:10px 0 0; }
.pf-news-img { width:220px; flex-shrink:0; }
.pf-news-img img { width:100%; height:100%; object-fit:cover; }

/* ── Wishlist (compact list style, matches booking cards) ── */
.pf-wl-header {
  display:flex; align-items:center; justify-content:space-between;
  margin-bottom:18px; flex-wrap:wrap; gap:12px;
}
.pf-wl-title {
  font-size:1.25rem; font-weight:800; color:#111827;
  font-family:'Syne',sans-serif; margin:0 0 3px;
}
.pf-wl-sub { font-size:.75rem; color:#9ca3af; margin:0; }
.pf-wl-browse-btn {
  display:inline-flex; align-items:center;
  background:#111827; color:#fff;
  padding:9px 18px; border-radius:999px;
  font-size:.8rem; font-weight:700; text-decoration:none;
  transition:background .18s;
}
.pf-wl-browse-btn:hover { background:#e8663a; }

/* The grid is now a flex column list */
.pf-wl-grid {
  display:flex; flex-direction:column; gap:0;
}

/* Each card = horizontal row, identical to pf-bk-card */
.pf-wl-card {
  display:flex; align-items:center; gap:16px;
  background:#fff; border:1px solid #f0f0f0;
  border-radius:12px; padding:14px 18px;
  margin-bottom:10px;
  box-shadow:0 1px 4px rgba(0,0,0,.04);
  transition:box-shadow .2s, transform .15s;
  cursor:pointer;
  color:inherit;
}
.pf-wl-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.09); transform:translateY(-1px); }

/* Thumbnail — matches pf-bk-img */
.pf-wl-thumb {
  width:80px; height:60px;
  border-radius:8px; overflow:hidden; flex-shrink:0;
}
.pf-wl-thumb img { width:100%; height:100%; object-fit:cover; }

/* Info — matches pf-bk-info */
.pf-wl-info { flex:1; min-width:0; }
.pf-wl-info h4 {
  font-size:.93rem; font-weight:700; color:#111827;
  margin:0 0 5px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.pf-wl-price-line { display:flex; align-items:baseline; gap:4px; margin:0; }
.pf-wl-price { font-size:.95rem; font-weight:800; color:#e8663a; }
.pf-wl-per { font-size:.72rem; color:#9ca3af; }

/* Actions (View link + trash) */
.pf-wl-actions { display:flex; align-items:center; gap:10px; flex-shrink:0; }
.pf-wl-view {
  font-size:.8rem; font-weight:600; color:#e8663a;
  white-space:nowrap;
}
.pf-wl-remove {
  background:none; border:none; cursor:pointer;
  color:#d1d5db; padding:4px;
  transition:color .15s;
}
.pf-wl-remove:hover { color:#dc2626; }

/* Empty wishlist state */
.pf-wl-empty {
  display:flex; flex-direction:column; align-items:center;
  padding:52px 24px 40px;
}
.pf-wl-empty-img {
  width:200px; height:auto;
  margin-bottom:18px;
  filter:drop-shadow(0 4px 12px rgba(0,0,0,.08));
}
.pf-wl-empty-text {
  font-size:.93rem; color:#6b7280; font-weight:500;
  text-align:center; margin:0 0 32px;
  max-width:300px; line-height:1.6;
}

/* ── Booking cards ── */
.pf-bk-card {
  display:flex; align-items:center; gap:16px;
  background:#fff; border:1px solid #f0f0f0;
  border-radius:12px; padding:14px 18px;
  margin-bottom:12px;
  box-shadow:0 1px 4px rgba(0,0,0,.04);
  transition:box-shadow .2s;
}
.pf-bk-card:hover { box-shadow:0 4px 16px rgba(0,0,0,.08); }
.pf-bk-img { width:80px; height:60px; border-radius:8px; overflow:hidden; flex-shrink:0; }
.pf-bk-img img { width:100%; height:100%; object-fit:cover; }
.pf-bk-info { flex:1; min-width:0; }
.pf-bk-info h4 { font-size:.93rem; font-weight:700; color:#111827; margin:0 0 4px; }
.pf-bk-info p { font-size:.79rem; color:#6b7280; margin:0 0 6px; }
.pf-bk-link { font-size:.8rem; font-weight:600; color:#e8663a; white-space:nowrap; text-decoration:none; }

/* ── Status badges ── */
.pf-badge {
  display:inline-block; font-size:.67rem; font-weight:700;
  padding:2px 10px; border-radius:999px; text-transform:uppercase; letter-spacing:.04em;
}
.pf-badge--confirmed,.pf-badge--completed { background:#dcfce7; color:#16a34a; }
.pf-badge--pending  { background:#fef9c3; color:#ca8a04; }
.pf-badge--cancelled { background:#fee2e2; color:#dc2626; }

/* ── Payment plan card ── */
.pf-plan {
  background:linear-gradient(135deg,#1e1e3f,#312e81);
  border-radius:14px; padding:26px 30px; color:#fff; margin-bottom:22px;
}
.pf-plan-badge {
  font-size:.68rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase;
  background:rgba(232,102,58,.2); border:1px solid rgba(232,102,58,.4);
  color:#fbbf24; padding:3px 12px; border-radius:999px;
  display:inline-block; margin-bottom:10px;
}
.pf-plan-name { font-size:1.7rem; font-weight:900; margin:0 0 12px; font-family:'Syne',sans-serif; }
.pf-plan-meta { display:flex; gap:18px; font-size:.85rem; color:rgba(255,255,255,.7); }

/* ── Payments table ── */
.pf-pay-tbl-wrap { overflow-x:auto; background:#fff; border:1px solid #f0f0f0; border-radius:12px; }
.pf-pay-tbl { width:100%; border-collapse:collapse; }
.pf-pay-tbl th {
  text-align:left; font-size:.68rem; font-weight:700; text-transform:uppercase;
  letter-spacing:.05em; color:#9ca3af; padding:13px 16px;
  border-bottom:1.5px solid #f0f0f0;
}
.pf-pay-tbl td { padding:13px 16px; font-size:.86rem; color:#111827; border-bottom:1px solid #f9fafb; }
.pf-pay-tbl tr:last-child td { border-bottom:none; }
.mono { font-family:monospace; font-weight:700; }

/* ── Empty state ── */
.pf-empty {
  text-align:center; padding:48px 24px;
  background:#fff; border-radius:12px; border:1px solid #f0f0f0;
}
.pf-empty p { font-size:.95rem; font-weight:600; color:#9ca3af; margin:0 0 14px; }
.pf-empty-btn {
  display:inline-block; background:#e8663a; color:#fff;
  padding:10px 24px; border-radius:999px;
  font-weight:700; font-size:.85rem; text-decoration:none;
  transition:background .18s;
}
.pf-empty-btn:hover { background:#d04e1f; }

/* ── Toast ── */
.pf-toast {
  position:fixed; top:84px; right:18px; z-index:9999;
  padding:12px 20px; border-radius:10px;
  font-size:.85rem; font-weight:600; color:#fff;
  box-shadow:0 6px 20px rgba(0,0,0,.15);
  transition:opacity .5s;
  animation:pfToastIn .35s ease;
}
.pf-toast--ok  { background:#16a34a; }
.pf-toast--err { background:#dc2626; }
@keyframes pfToastIn {
  from { opacity:0; transform:translateX(30px); }
  to   { opacity:1; transform:translateX(0);    }
}

/* ── Responsive ── */
@media (max-width:768px) {
  .pf-hero { height:210px; }
  .pf-hero-label { font-size:1.5rem; }
  .pf-dest-grid--4 { grid-template-columns:repeat(2,1fr); }
  .pf-news { flex-direction:column; }
  .pf-news-img { width:100%; height:150px; }
  .pf-hist-head { flex-direction:column; }
  .pf-hist-title { font-size:1.35rem; }
  .pf-wl-grid { grid-template-columns:repeat(2,1fr); }
}
@media (max-width:520px) {
  .pf-tab { font-size:.82rem; padding:16px 0 14px; }
  .pf-content { padding:28px 14px 52px; }
  .pf-row { flex-direction:row; align-items:center; }
  .pf-edit { align-self:auto; }
  .pf-news-left { padding:22px 22px; }
  .pf-news-heading { font-size:.95rem; }
  .pf-hero-user { transform:translateY(46px); }
  .pf-dest-grid--4 { grid-template-columns:repeat(2,1fr); }
}
@media (max-width:380px) {
  .pf-dest-grid { grid-template-columns:repeat(2,1fr); }
}
</style>

@endsection
