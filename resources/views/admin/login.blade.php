<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - TourRaja</title>
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
    <div class="flex min-h-screen" x-data="{ showPassword: false }">
        <!-- Form Side -->
        <div class="w-full lg:w-[45%] flex flex-col justify-center px-8 md:px-16 lg:px-24 py-12">
            <div class="max-w-md w-full mx-auto space-y-10">
                <div class="space-y-4">
                    <h1 class="text-4xl font-black text-foreground tracking-tight font-heading">Sign In</h1>
                    <p class="text-muted-text font-medium">Enter your email and password to access the admin dashboard!</p>
                </div>

                <!-- Google Sign In -->
                <button class="w-full bg-[#F3F6FF] hover:bg-[#EBF0FF] text-foreground font-bold py-4 rounded-2xl flex items-center justify-center gap-3 transition-all border border-transparent">
                    <svg width="20" height="20" viewBox="0 0 48 48" class="shrink-0">
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"></path>
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"></path>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"></path>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"></path>
                    </svg>
                    <span>Sign in with Google</span>
                </button>

                <div class="relative flex items-center py-4">
                    <div class="flex-grow border-t border-border-soft"></div>
                    <span class="flex-shrink mx-4 text-[10px] font-black text-muted-text uppercase tracking-widest">or</span>
                    <div class="flex-grow border-t border-border-soft"></div>
                </div>

                <!-- Main Form -->
                <form action="/admin/dashboard" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Email<span class="text-primary">*</span></label>
                        <input type="email" placeholder="mail@tourraja.com" class="w-full bg-white border border-border-soft rounded-2xl py-4 px-6 outline-none focus:border-primary/50 transition-all font-medium text-foreground placeholder:text-muted-text/40 shadow-sm shadow-black/5" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Password<span class="text-primary">*</span></label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" placeholder="Min. 8 characters" class="w-full bg-white border border-border-soft rounded-2xl py-4 px-6 pr-12 outline-none focus:border-primary/50 transition-all font-medium text-foreground placeholder:text-muted-text/40 shadow-sm shadow-black/5" />
                            <button @click="showPassword = !showPassword" type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-muted-text hover:text-primary transition-colors">
                                <i :data-lucide="showPassword ? 'eye-off' : 'eye'" size="18"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" class="w-5 h-5 rounded-lg border-border-soft text-primary focus:ring-primary/20 transition-all" checked />
                            <span class="text-xs font-bold text-muted-text group-hover:text-foreground transition-colors">Keep me logged in</span>
                        </label>
                        <a href="#" class="text-xs font-bold text-primary hover:underline">Forget password?</a>
                    </div>

                    <button type="submit" class="w-full bg-[#E8460A] hover:bg-primary-hover text-white rounded-2xl py-5 font-black text-sm uppercase tracking-widest shadow-xl shadow-primary/20 transition-all transform hover:-translate-y-1">
                        Sign In
                    </button>

                    <p class="text-xs font-bold text-muted-text">
                        Not registered yet? <a href="#" class="text-primary hover:underline">Create an Account</a>
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
