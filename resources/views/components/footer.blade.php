<style>
  @media (min-width: 1024px) {
    .footer-partition {
      border-left: 1px solid rgba(255, 255, 255, 0.2);
      padding-left: 2rem;
    }
  }
</style>
<footer style="background:#e85d26;" class="text-white">
  <div class="max-w-7xl mx-auto px-6 py-10">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">

      {{-- Col 1: Logo + Become Agent --}}
      <div class="col-span-2 md:col-span-1 space-y-5">
        <a href="{{ url('/') }}">
          <x-logo-white class="h-10 w-auto text-white" />
        </a>
        <a href="{{ url('https://emperorsmartsolutions.com/Tour_Raja_Agent/') }}"
           class="inline-block border border-white text-white text-xs font-bold px-5 py-2.5 rounded-md hover:bg-white hover:text-[#e85d26] transition-all">
          Become Agent/Login
        </a>
      </div>

      {{-- Col 2: Tour Raja --}}
      <div>
        <h4 style="font-size:13px; font-weight:700; margin-bottom:16px;" class="text-white">Tour Raja</h4>
        <ul style="list-style:none; padding:0; margin:0;">
          <li style="padding:1px 0;"><a href="{{ url('/about') }}" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">About Us</a></li>
          <li style="padding:1px 0;"><a href="#" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">Career</a></li>
          <li style="padding:1px 0;"><a href="{{ url('/contact') }}" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">Contact Us</a></li>
          <li style="padding:1px 0;"><a href="#" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">Privacy Policy</a></li>
          <li style="padding:1px 0;"><a href="#" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">Terms of Services</a></li>
        </ul>
      </div>

      {{-- Col 3: Quick Links --}}
      <div class="footer-partition">
        <h4 style="font-size:13px; font-weight:700; margin-bottom:16px;" class="text-white">Quick Links</h4>
        <ul style="list-style:none; padding:0; margin:0;">
          <li style="padding:1px 0;"><a href="#" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">Site Map</a></li>
          <li style="padding:1px 0;"><a href="{{ url('/discover?category=international') }}" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">International Destinations</a></li>
          <li style="padding:1px 0;"><a href="{{ url('/discover?category=domestic') }}" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">Domestic Destinations</a></li>
          <li style="padding:1px 0;"><a href="#" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">Multi-City Destinations</a></li>
          <li style="padding:1px 0;"><a href="{{ url('/discover') }}" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">Popular Destinations</a></li>
        </ul>
      </div>

      {{-- Col 4: Popular Transits --}}
      <div class="footer-partition">
        <h4 style="font-size:13px; font-weight:700; margin-bottom:16px;" class="text-white">Popular Transits</h4>
        <ul style="list-style:none; padding:0; margin:0;">
          <li style="padding:1px 0;"><a href="{{ url('/discover?transit=flight') }}" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">Flight Package</a></li>
          <li style="padding:1px 0;"><a href="{{ url('/discover?transit=train') }}" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">Train Package</a></li>
          <li style="padding:1px 0;"><a href="{{ url('/discover?transit=bus') }}" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">Bus Package</a></li>
          <li style="padding:1px 0;"><a href="{{ url('/discover?transit=bullet') }}" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">Bullet Ride Package</a></li>
          <li style="padding:1px 0;"><a href="{{ url('/discover?transit=cruise') }}" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">Cruise Package</a></li>
          <li style="padding:1px 0;"><a href="{{ url('/discover?transit=land') }}" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">Land Package</a></li>
          <li style="padding:1px 0;"><a href="{{ url('/discover?transit=tracking') }}" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">Tracking Package</a></li>
          <li style="padding:1px 0;"><a href="{{ url('/discover?transit=helicopter') }}" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">Helicopter Package</a></li>
        </ul>
      </div>

      {{-- Col 5: More Links --}}
      <div class="footer-partition">
        <h4 style="font-size:13px; font-weight:700; margin-bottom:16px;" class="text-white">More Links</h4>
        <ul style="list-style:none; padding:0; margin:0;">
          <li style="padding:1px 0;"><a href="{{ url('/profile?tab=history') }}" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">My Bookings</a></li>
          <li style="padding:1px 0;"><a href="#" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">Saved Agent</a></li>
          <li style="padding:1px 0;"><a href="{{ url('/profile') }}" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">My Account</a></li>
          <li style="padding:1px 0;"><a href="#" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">My Reviews</a></li>
          <li style="padding:1px 0;"><a href="{{ url('/admin/login') }}" style="color:rgba(255,255,255,0.85); font-size:12px; text-decoration:none;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">Admin Login</a></li>
        </ul>
      </div>

    </div>

    {{-- Bottom bar --}}
    <div class="mt-10 pt-6 border-t border-white/20 flex flex-col sm:flex-row items-center justify-between gap-4">
      <p style="color:rgba(255,255,255,0.7); font-size:11px;">
        Copyright &copy; {{ date('Y') }} Tour Raja Private Limited, India. All rights reserved.
      </p>
      <div class="flex items-center gap-4">
        {{-- Facebook --}}
        <a href="#" class="text-white/80 hover:text-white transition-colors">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
        </a>
        {{-- Instagram --}}
        <a href="#" class="text-white/80 hover:text-white transition-colors">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
        </a>
        {{-- X / Twitter --}}
        <a href="#" class="text-white/80 hover:text-white transition-colors">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.738-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        </a>
        {{-- YouTube --}}
        <a href="#" class="text-white/80 hover:text-white transition-colors">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.95C5.12 20 12 20 12 20s6.88 0 8.59-.47a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="white"/></svg>
        </a>
      </div>
    </div>
  </div>
</footer>
