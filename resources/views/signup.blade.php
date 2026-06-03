<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up – TourRaja</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-side {
            background-color: #e85d26;
            background-image: url("{{ asset('images/tourraja-bg.png') }}");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            border-top-left-radius: 120px;
            border-bottom-left-radius: 120px;
        }
        @media (max-width: 1024px) { .gradient-side { display: none; } }
    </style>
</head>
<body class="bg-white min-h-screen overflow-x-hidden">
    <div class="flex min-h-screen" x-data="{ showPassword: false }">
        <!-- Form Side -->
        <div class="w-full lg:w-[45%] flex flex-col justify-center px-8 md:px-16 lg:px-24 pt-32 pb-12">
            <div class="max-w-md w-full mx-auto space-y-10">
                @php
                    $type = $type ?? 'customer';
                    $title = 'Sign Up';
                    if ($type === 'admin') $title = 'Admin Sign Up';
                    elseif ($type === 'agent') $title = 'Agent Sign Up';
                    elseif ($type === 'customer') $title = 'Customer Sign Up';
                @endphp
                <div class="space-y-4">
                    <h2 class="font-black text-foreground tracking-tight text-3xl">{{ $title }}</h2>
                    <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Create your TourRaja account.</p>
                </div>
                @if($errors->any())
                    <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 font-bold text-xs flex flex-col gap-1">
                        @foreach($errors->all() as $error)
                            <span>- {{ $error }}</span>
                        @endforeach
                    </div>
                @endif
                <form action="{{ url('/signup/submit') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}" />
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">First Name<span class="text-primary">*</span></label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required placeholder="John" class="w-full bg-white border border-border-soft rounded-2xl py-4 px-6 outline-none focus:border-primary/50 transition-all font-medium text-foreground shadow-sm" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Last Name<span class="text-primary">*</span></label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required placeholder="Doe" class="w-full bg-white border border-border-soft rounded-2xl py-4 px-6 outline-none focus:border-primary/50 transition-all font-medium text-foreground shadow-sm" />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Email<span class="text-primary">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="mail@example.com" class="w-full bg-white border border-border-soft rounded-2xl py-4 px-6 outline-none focus:border-primary/50 transition-all font-medium text-foreground placeholder:text-muted-text/40 shadow-sm" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Password<span class="text-primary">*</span></label>
                        <input type="password" name="password" required placeholder="Min. 8 characters" class="w-full bg-white border border-border-soft rounded-2xl py-4 px-6 outline-none focus:border-primary/50 transition-all font-medium text-foreground shadow-sm" />
                    </div>
                    <div class="flex items-center gap-3 py-2">
                        <input type="checkbox" class="w-5 h-5 rounded-lg border-border-soft text-primary focus:ring-primary/20 transition-all" required />
                        <span class="text-xs font-bold text-muted-text">I agree to the <a href="{{ url('/terms-and-conditions') }}" target="_blank" class="text-primary hover:underline">Terms & Conditions</a></span>
                    </div>
                    <button type="submit" class="w-full bg-[#E8460A] hover:bg-primary-hover text-white rounded-2xl py-5 font-black text-sm uppercase tracking-widest shadow-xl transition-all transform hover:-translate-y-1">Create Account</button>
                    <p class="text-xs font-bold text-muted-text text-center mt-6">Already have an account? <a href="{{ url('/login') }}" class="text-primary hover:underline">Sign In</a></p>
                </form>
                <div class="pt-8">
                    <p class="text-[10px] font-medium text-muted-text leading-loose">© 2026 Tour Raja Private Limited, India. All rights reserved.</p>
                </div>
            </div>
        </div>
        <!-- Gradient Side -->
        <div class="hidden lg:flex lg:w-[55%] gradient-side items-center justify-center relative">
            <div class="text-center space-y-4 w-full px-12">
                <div class="flex items-center justify-center mb-6">
                    <x-logo white="true" class="h-20 sm:h-28 w-auto" />
                </div>
                <h3 class="text-3xl font-bold text-white tracking-tight">Join Us!</h3>
                <p class="text-white/80 max-w-md mx-auto">Start your adventures with TourRaja today.</p>
            </div>
        </div>
    </div>
    <script>
        // Reload page on bfcache restore to refresh CSRF token
        window.onpageshow = function(event) { if (event.persisted) { window.location.reload(); } };
    </script>
    <script>lucide.createIcons();</script>
</body>
</html>
                            class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3.5 px-5 text-sm font-medium text-gray-800 placeholder:text-gray-300 outline-none focus:border-[#e85d26]/60 focus:bg-white transition-all shadow-sm" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5 pl-1">
                            Password <span style="color:#e85d26;">*</span>
                        </label>
                        <input type="password" name="password" required placeholder="Min. 8 characters"
                            class="w-full bg-gray-50 border border-gray-200 rounded-2xl py-3.5 px-5 text-sm font-medium text-gray-800 placeholder:text-gray-300 outline-none focus:border-[#e85d26]/60 focus:bg-white transition-all shadow-sm" />
                    </div>

                    <!-- Terms -->
                    <div class="flex items-start gap-3 pt-1">
                        <input type="checkbox" required class="w-4 h-4 mt-0.5 rounded border-gray-300 shrink-0" />
                        <span class="text-xs text-gray-500 leading-relaxed">
                            I agree to the
                            <a href="{{ url('/terms-and-conditions') }}" target="_blank"
                               class="font-semibold hover:underline" style="color:#e85d26;">Terms &amp; Conditions</a>
                            and
                            <a href="{{ url('/privacy-policy') }}" target="_blank"
                               class="font-semibold hover:underline" style="color:#e85d26;">Privacy Policy</a>
                        </span>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-signup w-full text-white rounded-2xl py-3.5 font-bold text-sm shadow-lg mt-2">
                        Create Account
                    </button>

                    <!-- Sign-in link -->
                    <p class="text-xs text-gray-400 text-center pt-1">
                        Already have an account?
                        <a href="{{ url('/login') }}" class="font-semibold hover:underline" style="color:#e85d26;">Sign In</a>
                    </p>
                </form>

                <script>
                    window.onpageshow = function(e) { if (e.persisted) window.location.reload(); };
                </script>

                <!-- Copyright -->
                <p class="text-[10px] text-gray-300 mt-12 leading-relaxed">
                    © 2026 Tour Raja Private Limited, India. All rights reserved.
                </p>
            </div>
        </div>

        <!-- ── Orange / Image Side ── -->
        <div class="hidden lg:flex lg:w-[55%] gradient-side flex-col items-center justify-center relative">

            <div class="flex items-center justify-center">
                <x-logo white="true" class="h-28 w-auto" />
            </div>

            <div class="mt-8 text-center px-12">
                <h2 class="text-2xl font-bold text-white tracking-tight mb-2">Join TourRaja!</h2>
                <p class="text-white/70 text-sm max-w-xs mx-auto leading-relaxed">Start your adventures today and explore the world's most amazing destinations.</p>
            </div>

            <!-- Footer links -->
            <div class="absolute bottom-10 w-full flex flex-wrap items-center justify-center gap-x-8 gap-y-4 px-8">
                <a href="{{ url('/about') }}"
                   class="text-[10px] font-semibold text-white/60 hover:text-white uppercase tracking-widest transition-colors">About Us</a>
                <a href="{{ url('/terms-and-conditions') }}"
                   class="text-[10px] font-semibold text-white/60 hover:text-white uppercase tracking-widest transition-colors">Terms of Service</a>
                <a href="{{ url('/privacy-policy') }}"
                   class="text-[10px] font-semibold text-white/60 hover:text-white uppercase tracking-widest transition-colors">Privacy Policy</a>
                <a href="{{ url('/contact') }}"
                   class="text-[10px] font-semibold text-white/60 hover:text-white uppercase tracking-widest transition-colors">Contact</a>
            </div>
        </div>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
