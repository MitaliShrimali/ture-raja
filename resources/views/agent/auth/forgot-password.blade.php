<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" href="{{ \Illuminate\Support\Facades\DB::table('settings')->where('key', 'favicon')->value('value') ?? asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Forgot Password - Tour Raja</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .orange-side {
            background-color: #e85d26;
            background-image: url("{{ asset('images/tour raja-bg.png') }}");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            border-top-left-radius: 120px;
            border-bottom-left-radius: 120px;
        }
        @media (max-width: 1024px) {
            .orange-side { display: none; }
        }
    </style>
</head>
<body class="bg-white min-h-screen relative overflow-x-hidden">
    <!-- Top Navbar menu bar -->
    <div class="absolute top-6 left-1/2 -translate-x-1/2 z-50 w-full max-w-4xl px-4 hidden md:block">
        <div class="bg-white/95 backdrop-blur-md rounded-full shadow-lg border border-gray-100 px-8 py-3.5 flex items-center justify-between">
            <x-logo class="h-8 w-auto" />
            <div class="flex items-center gap-8">
                <a href="{{ route('agent.signup') }}" class="text-[10px] font-black text-gray-500 hover:text-[#e85d26] uppercase tracking-widest transition-colors flex items-center gap-1.5"><i data-lucide="user-plus" class="w-3.5 h-3.5"></i> Sign Up</a>
                <a href="{{ route('agent.login') }}" class="text-[10px] font-black text-[#e85d26] uppercase tracking-widest transition-colors flex items-center gap-1.5"><i data-lucide="log-in" class="w-3.5 h-3.5"></i> Sign In</a>
            </div>
        </div>
    </div>

    <div class="flex min-h-screen">
        <!-- Form Side -->
        <div class="w-full lg:w-[45%] flex flex-col justify-center px-8 md:px-16 lg:px-24 py-12 pt-28">
            <div class="max-w-md w-full mx-auto space-y-8">
                <div class="space-y-2">
                    <h2 class="font-black tracking-tight font-heading text-4xl" style="color: #e85d26;">Reset Password</h2>
                    <p class="text-xs font-bold text-gray-400">Enter your email to receive a password reset link.</p>
                </div>

                <!-- Flash Messages -->
                @if(session('error'))
                    <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 font-bold text-xs flex items-center gap-3">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                @if(session('success'))
                    <div class="p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 font-bold text-xs flex items-center gap-3">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Main Form -->
                <form action="{{ route('agent.forgot-password.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-700 pl-1">Email<span style="color: #e85d26;">*</span></label>
                        <input required type="email" name="email" value="{{ old('email') }}" placeholder="mail@youragency.com"
                            class="w-full bg-white border border-gray-200 rounded-2xl py-4 px-6 outline-none focus:border-[#e85d26]/50 transition-all font-medium text-foreground placeholder:text-gray-400 text-sm shadow-sm" />
                    </div>

                    <button type="submit"
                        class="cursor-pointer w-full text-white rounded-2xl py-4 font-bold text-sm shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2"
                        style="background-color: #e85d26; box-shadow: 0 10px 20px rgba(232, 93, 38, 0.2);"
                        onmouseover="this.style.backgroundColor='#d44f1c'"
                        onmouseout="this.style.backgroundColor='#e85d26'">
                        Send Reset Link
                    </button>

                    <p class="text-xs font-bold text-gray-400 text-center mt-6">
                        Remembered your password? <a href="{{ route('agent.login') }}" class="hover:underline font-bold" style="color: #e85d26;">Sign In</a>
                    </p>
                </form>

                <div class="pt-12">
                    <p class="text-[10px] font-medium text-gray-400 leading-loose">
                        Copyright © 2026 Tour Raja Private Limited, India. <br /> All rights reserved.
                    </p>
                </div>
            </div>
        </div>

        <!-- Orange Side -->
        <div class="hidden lg:flex lg:w-[55%] orange-side items-center justify-center relative" style="border-bottom-left-radius: 250px;">
            <div class="text-center space-y-4 w-full px-12">
                <div class="flex items-center justify-center">
                    <x-logo localWhite="true" class="h-24 sm:h-32 w-auto" />
                </div>
            </div>
        </div>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>

