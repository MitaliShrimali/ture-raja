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
            background-color: #E8460A;
            background-image: url("data:image/svg+xml,%3Csvg width='100%25' height='100%25' viewBox='0 0 1600 800' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 800V0c400 0 400 800 800 800s400-800 800-800v800H0z' fill='white' fill-opacity='0.05'/%3E%3Cpath d='M0 800V0c200 0 200 800 400 800s200-800 400-800 200 800 400 800 200-800 400-800v800H0z' fill='white' fill-opacity='0.03'/%3E%3C/svg%3E");
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
    <!-- Floating Navbar -->
    <div class="fixed top-8 left-1/2 -translate-x-1/2 z-50 w-full max-w-4xl px-6">
        <div class="glass rounded-full py-3 px-8 flex items-center justify-between shadow-lg shadow-black/5">
            <div class="flex items-center gap-8">
                <a href="#" class="text-[10px] font-black uppercase tracking-widest text-foreground/60 hover:text-primary transition-colors flex items-center gap-2">
                    <i data-lucide="layout-dashboard" size="14"></i> DASHBOARD
                </a>
                <a href="#" class="text-[10px] font-black uppercase tracking-widest text-foreground/60 hover:text-primary transition-colors flex items-center gap-2">
                    <i data-lucide="user" size="14"></i> PROFILE
                </a>
                <a href="#" class="text-[10px] font-black uppercase tracking-widest text-primary flex items-center gap-2">
                    <i data-lucide="user-plus" size="14"></i> SIGN UP
                </a>
                <a href="{{ url('/admin/login') }}" class="text-[10px] font-black uppercase tracking-widest text-foreground/60 hover:text-primary flex items-center gap-2 transition-colors">
                    <i data-lucide="log-in" size="14"></i> SIGN IN
                </a>
            </div>
            <button class="bg-[#E8460A] text-white px-6 py-2 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-primary-hover transition-all">
                Contact Admin
            </button>
        </div>
    </div>

    <div class="flex min-h-screen">
        <!-- Form Side -->
        <div class="w-full lg:w-[45%] flex flex-col justify-center px-8 md:px-16 lg:px-24 pt-32 pb-12">
            <div class="max-w-md w-full mx-auto space-y-10">
                <div class="space-y-4">
                    <h1 class="text-4xl font-black text-foreground tracking-tight">Sign Up</h1>
                    <p class="text-muted-text font-medium">Join the TourRaja admin network today!</p>
                </div>

                <!-- Main Form -->
                <form action="/admin/dashboard" class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">First Name<span class="text-primary">*</span></label>
                            <input type="text" placeholder="John" class="w-full bg-white border border-border-soft rounded-2xl py-4 px-6 outline-none focus:border-primary/50 transition-all font-medium text-foreground shadow-sm shadow-black/5" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Last Name<span class="text-primary">*</span></label>
                            <input type="text" placeholder="Doe" class="w-full bg-white border border-border-soft rounded-2xl py-4 px-6 outline-none focus:border-primary/50 transition-all font-medium text-foreground shadow-sm shadow-black/5" />
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Email<span class="text-primary">*</span></label>
                        <input type="email" placeholder="mail@simmmple.com" class="w-full bg-white border border-border-soft rounded-2xl py-4 px-6 outline-none focus:border-primary/50 transition-all font-medium text-foreground placeholder:text-muted-text/40 shadow-sm shadow-black/5" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Password<span class="text-primary">*</span></label>
                        <input type="password" placeholder="Min. 8 characters" class="w-full bg-white border border-border-soft rounded-2xl py-4 px-6 outline-none focus:border-primary/50 transition-all font-medium text-foreground shadow-sm shadow-black/5" />
                    </div>

                    <div class="flex items-center gap-3 py-2">
                        <input type="checkbox" class="w-5 h-5 rounded-lg border-border-soft text-primary focus:ring-primary/20 transition-all" required />
                        <span class="text-xs font-bold text-muted-text">I agree to the <a href="#" class="text-primary hover:underline">Terms & Conditions</a></span>
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
            <div class="text-center space-y-4">
                <!-- Large Logo -->
                <div class="flex items-center justify-center">
                    <h2 class="text-7xl font-black text-white tracking-tighter flex items-center gap-2">
                        tourraja
                        <div class="relative -top-6">
                            <i data-lucide="crown" class="text-white" size="32" stroke-width="3"></i>
                        </div>
                    </h2>
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
