<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="{{ \Illuminate\Support\Facades\DB::table('settings')->where('key', 'favicon')->value('value') ?? asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password – Tour Raja</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .gradient-side {
            background-color: #e85d26;
            background-image: url("{{ asset('images/tour raja-bg.png') }}");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            border-top-left-radius: 120px;
            border-bottom-left-radius: 120px;
        }

        @media (max-width: 1024px) {
            .gradient-side {
                display: none;
            }
        }
    </style>
</head>

<body class="bg-white min-h-screen overflow-x-hidden">
    <div class="flex min-h-screen">
        <!-- Form Side -->
        <div class="w-full lg:w-[45%] flex flex-col justify-center px-8 md:px-16 lg:px-24 py-12">
            <div class="max-w-md w-full mx-auto space-y-10">
                <div class="space-y-4">
                    <h2 class="font-black text-foreground text-3xl tracking-tight">Forgot Password</h2>
                    <p class="text-xs font-bold text-primary uppercase tracking-widest mb-1">Enter your email address to
                        receive a password reset link.</p>
                </div>
                <!-- Flash Messages -->
                @if(session('error'))
                    <div
                        class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 font-bold text-xs flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="shrink-0">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" x2="12" y1="8" y2="12" />
                            <line x1="12" x2="12.01" y1="16" y2="16" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                @if(session('success'))
                    <div
                        class="p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 font-bold text-xs flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            class="shrink-0">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                <!-- Main Form -->
                <form action="{{ url('/forgot-password') }}" method="POST" class="space-y-8">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Email
                            Address<span class="text-primary">*</span></label>
                        <input required type="email" name="email" placeholder="user@example.com" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Please enter a valid email address"
                            class="w-full bg-white border border-border-soft rounded-2xl py-4 px-6 outline-none focus:border-primary/50 transition-all font-medium text-foreground placeholder:text-muted-text/40 shadow-sm" />
                    </div>
                    <button type="submit"
                        class="cursor-pointer w-full bg-[#E8460A] hover:bg-primary-hover text-white rounded-2xl py-5 font-black text-sm uppercase tracking-widest shadow-xl transition-all transform hover:-translate-y-1">
                        Send Reset Link</button>
                    <p class="text-xs font-bold text-muted-text text-center mt-6">Remembered your password? <a
                            href="{{ url('/login') }}" class="text-primary hover:underline">Sign In</a></p>
                </form>
                <div class="pt-12">
                    <p class="text-[10px] font-medium text-muted-text leading-loose">© 2026 Tour Raja Private Limited,
                        India.<br />All rights reserved.</p>
                </div>
            </div>
        </div>
        <!-- Gradient Side -->
        <div class="hidden lg:flex lg:w-[55%] gradient-side items-center justify-center relative">
            <div class="text-center space-y-4 w-full px-12">
                <div class="flex items-center justify-center mb-6">
                    <x-logo white="true" class="h-20 sm:h-28 w-auto" />
                </div>
                <h3 class="text-3xl font-bold tracking-tight" style="color: #E8460A;">Forgot Password?</h3>
                <p class="max-w-md mx-auto" style="color: #E8460A;">Don't worry, we'll help you get back on track.</p>
            </div>
        </div>
    </div>
    <script>lucide.createIcons();</script>
</body>

</html>

