<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - TourRaja</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .orange-side {
            background-color: #e85d26;
            background-image: url("{{ asset('images/tourraja-bg.png') }}");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            border-top-left-radius: 120px;
            border-bottom-left-radius: 120px;
        }
        @media (max-width: 1024px) {
            .orange-side {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-white min-h-screen relative overflow-x-hidden">


    <div class="flex min-h-screen">
        <!-- Form Side -->
        <div class="w-full lg:w-[45%] flex flex-col justify-center px-8 md:px-16 lg:px-24 pt-32 pb-12">
            <div class="max-w-md w-full mx-auto space-y-10">
                @php
                    $type = $type ?? 'admin';
                    $title = 'Sign Up';
                    if ($type === 'agent') $title = 'Agent Sign Up';
                    elseif ($type === 'customer') $title = 'Customer Sign Up';
                    elseif ($type === 'admin') $title = 'Admin Sign Up';
                @endphp
                <div class="space-y-4">
                    <h2 class="font-black text-foreground tracking-tight">{{ $title }}</h2>
                    <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Create your TourRaja account today!</p>
                </div>

                <!-- Flash Messages -->
                @if($errors->any())
                    <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 font-bold text-xs flex flex-col gap-1">
                        @foreach($errors->all() as $error)
                            <span>- {{ $error }}</span>
                        @endforeach
                    </div>
                @endif

                <!-- Main Form -->
                <form action="{{ url('/signup/submit') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}" />
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">First Name<span class="text-primary">*</span></label>
                            <input type="text" name="first_name" required placeholder="John" class="w-full bg-white border border-border-soft rounded-2xl py-4 px-6 outline-none focus:border-primary/50 transition-all font-medium text-foreground shadow-sm shadow-black/5" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Last Name<span class="text-primary">*</span></label>
                            <input type="text" name="last_name" required placeholder="Doe" class="w-full bg-white border border-border-soft rounded-2xl py-4 px-6 outline-none focus:border-primary/50 transition-all font-medium text-foreground shadow-sm shadow-black/5" />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Email<span class="text-primary">*</span></label>
                            <input type="email" name="email" required placeholder="mail@example.com" class="w-full bg-white border border-border-soft rounded-2xl py-4 px-6 outline-none focus:border-primary/50 transition-all font-medium text-foreground placeholder:text-muted-text/40 shadow-sm shadow-black/5" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Password<span class="text-primary">*</span></label>
                        <input type="password" name="password" required placeholder="Min. 8 characters" class="w-full bg-white border border-border-soft rounded-2xl py-4 px-6 outline-none focus:border-primary/50 transition-all font-medium text-foreground shadow-sm shadow-black/5" />
                    </div>

                    <div class="flex items-center gap-3 py-2">
                        <input type="checkbox" class="w-5 h-5 rounded-lg border-border-soft text-primary focus:ring-primary/20 transition-all" required />
                        <span class="text-xs font-bold text-muted-text">I agree to the <a href="{{ url('/terms-and-conditions') }}" target="_blank" class="text-primary hover:underline">Terms & Conditions</a></span>
                    </div>

                    <button type="submit" class="w-full bg-[#E8460A] hover:bg-primary-hover text-white rounded-2xl py-5 font-black text-sm uppercase tracking-widest shadow-xl shadow-primary/20 transition-all transform hover:-translate-y-1">
                        Create Account
                    </button>

                    <p class="text-xs font-bold text-muted-text">
                        Already have an account? <a href="{{ url('/admin/login') }}" class="text-primary hover:underline">Sign In</a>
                    </p>
                </form>

                <div class="pt-8">
                    <p class="text-[10px] font-medium text-muted-text leading-loose">
                        Copyright © 2026 Tour Raja Private Limited, India. All rights reserved.
                    </p>
                </div>
            </div>
        </div>

        <!-- Orange Side -->
        <div class="hidden lg:flex lg:w-[55%] orange-side items-center justify-center relative">
            <div class="text-center space-y-4 w-full px-12">
                <!-- Large Logo -->
                <div class="flex items-center justify-center">
                    <x-logo white="true" class="h-20 sm:h-28 w-auto" />
                </div>
            </div>

            <!-- Footer Links -->
            <div class="absolute bottom-12 left-1/2 -translate-x-1/2 w-full px-12 flex justify-between items-center">
                <div class="flex items-center gap-8">
                    <a href="#" class="text-[10px] font-black text-white/60 hover:text-white uppercase tracking-widest transition-colors">About Us</a>
                    <a href="#" class="text-[10px] font-black text-white/60 hover:text-white uppercase tracking-widest transition-colors">License</a>
                    <a href="#" class="text-[10px] font-black text-white/60 hover:text-white uppercase tracking-widest transition-colors">Terms of Services</a>
                    <a href="#" class="text-[10px] font-black text-white/60 hover:text-white uppercase tracking-widest transition-colors">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Chatbot Bubble -->
    <button class="fixed bottom-8 right-8 w-16 h-16 bg-[#0052FF] text-white rounded-full flex items-center justify-center shadow-2xl shadow-blue-500/40 hover:scale-110 transition-transform z-50">
        <i data-lucide="message-circle" size="28" fill="white"></i>
    </button>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
