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
                    <h2 class="font-black text-foreground tracking-tight font-heading">Sign In</h2>
                    <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Enter your credentials to access the TourRaja Admin Central.</p>
                </div>

                <!-- Main Form -->
                <form action="{{ url('admin/dashboard') }}" class="space-y-8">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Email Address<span class="text-primary">*</span></label>
                        <input type="email" value="admin@tourraja.com" placeholder="admin@tourraja.com" class="w-full bg-white border border-border-soft rounded-2xl py-4 px-6 outline-none focus:border-primary/50 transition-all font-medium text-foreground placeholder:text-muted-text/40 shadow-sm shadow-black/5" />
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Password<span class="text-primary">*</span></label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" value="password123" placeholder="Enter password" class="w-full bg-white border border-border-soft rounded-2xl py-4 px-6 pr-14 outline-none focus:border-primary/50 transition-all font-medium text-foreground placeholder:text-muted-text/40 shadow-sm shadow-black/5" />
                            <button @click="showPassword = !showPassword" type="button" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center text-muted-text hover:text-primary transition-colors">
                                <!-- Eye Open SVG -->
                                <template x-if="!showPassword">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </template>
                                <!-- Eye Closed SVG -->
                                <template x-if="showPassword">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88 2 2m17.76 17.76L22 22M2 12s3-7 10-7a9.06 9.06 0 0 1 5.01 1.51M9.1 9.1a3 3 0 0 0 3.79 3.79M12 19c-7 0-10-7-10-7a9.75 9.75 0 0 1 1.51-2.01M17.76 17.76A10.38 10.38 0 0 1 12 19c-7 0-10-7-10-7M22 12s-3 7-10 7a9.06 9.06 0 0 1-5.01-1.51M12 5c7 0 10 7 10 7a9.75 9.75 0 0 1-1.51 2.01M17.76 17.76a3 3 0 0 0-3.79-3.79"/></svg>
                                </template>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" class="w-5 h-5 rounded-lg border-border-soft text-primary focus:ring-primary/20 transition-all" checked />
                            <span class="text-xs font-bold text-muted-text group-hover:text-foreground transition-colors">Remember me</span>
                        </label>
                        <a href="#" class="text-xs font-bold text-primary hover:underline">Forgot password?</a>
                    </div>

                    <button type="submit" class="w-full bg-[#E8460A] hover:bg-primary-hover text-white rounded-2xl py-5 font-black text-sm uppercase tracking-widest shadow-xl shadow-primary/20 transition-all transform hover:-translate-y-1">
                        Access Dashboard
                    </button>
                </form>

                <div class="pt-12">
                    <p class="text-[10px] font-medium text-muted-text leading-loose">
                        Copyright © 2026 Tour Raja Private Limited, India. <br/> All rights reserved for administrative control.
                    </p>
                </div>
            </div>
        </div>

        <!-- Orange Side -->
        <div class="hidden lg:flex lg:w-[55%] orange-side items-center justify-center relative">
            <div class="text-center space-y-4">
                <div class="flex items-center justify-center">
                    <h2 class="font-black text-white tracking-tighter flex items-center gap-2">
                        tourraja
                        <div class="relative -top-6">
                            <i data-lucide="crown" class="text-white" size="32" stroke-width="3"></i>
                        </div>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
