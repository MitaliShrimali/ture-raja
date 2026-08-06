<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Sign In - TourRaja</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <!-- <script src="https://unpkg.com/@tailwindcss/browser@4"></script> -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

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
@php
    $title = 'Agent Sign In';
    $subtext = 'Enter your agent credentials to access the TourRaja Agent Portal.';
    $actionUrl = route('agent.login.submit');
    $defaultEmail = '';
    $btnText = 'Access Agent Portal';
@endphp

<body class="bg-white min-h-screen relative overflow-x-hidden">
    <!-- Top Navbar menu bar -->
    <div class="absolute top-6 left-1/2 -translate-x-1/2 z-50 w-full max-w-4xl px-4 hidden md:block">
        <div
            class="bg-white/95 backdrop-blur-md rounded-full shadow-lg border border-gray-100 px-8 py-3.5 flex items-center justify-between">
            <x-logo class="h-8 w-auto" />
            <div class="flex items-center gap-8">
                <a href="{{ route('agent.signup') }}"
                    class="text-[10px] font-black text-gray-500 hover:text-[#e85d26] uppercase tracking-widest transition-colors flex items-center gap-1.5"><i
                        data-lucide="user-plus" class="w-3.5 h-3.5"></i> Sign Up</a>
                <a href="{{ route('agent.login') }}"
                    class="text-[10px] font-black text-[#e85d26] uppercase tracking-widest transition-colors flex items-center gap-1.5"><i
                        data-lucide="log-in" class="w-3.5 h-3.5"></i> Sign In</a>
            </div>
        </div>
    </div>

    <div class="flex min-h-screen"
        x-data="{ showPassword: false, showForgotPassword: false, forgotEmailSent: false, loading: false }">
        <!-- Form Side -->
        <div class="w-full lg:w-[45%] flex flex-col justify-center px-8 md:px-16 lg:px-24 py-12 pt-28">
            <div class="max-w-md w-full mx-auto space-y-8">
                <div class="space-y-2">
                    <h2 class="font-black tracking-tight font-heading text-4xl" style="color: #e85d26;">{{ $title }}</h2>
                    <p class="text-xs font-bold text-gray-400">Enter your email and password to sign in!</p>
                </div>

                <!-- Google Sign In Removed -->

                <!-- Success Message for Reset Link -->
                <div x-show="forgotEmailSent" x-transition
                    class="p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 font-bold text-xs flex items-center gap-3"
                    style="display: none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                        class="shrink-0">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                    <span>A password reset link has been successfully sent to your email!</span>
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
                <form action="{{ $actionUrl }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-700 pl-1">Email<span
                                style="color: #e85d26;">*</span></label>
                        <input required type="email" name="email" value="{{ old('email', $defaultEmail) }}"
                            placeholder="mail@youragency.com" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Please enter a valid email address"
                            class="w-full bg-white border border-gray-200 rounded-2xl py-4 px-6 outline-none focus:border-[#e85d26]/50 transition-all font-medium text-foreground placeholder:text-gray-400 text-sm shadow-sm" />
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-700 pl-1">Password<span
                                style="color: #e85d26;">*</span></label>
                        <div class="relative">
                            <input required :type="showPassword ? 'text' : 'password'"
                                name="password" placeholder="Min. 8 characters"
                                class="w-full bg-white border border-gray-200 rounded-2xl py-4 px-6 pr-14 outline-none focus:border-[#e85d26]/50 transition-all font-medium text-foreground placeholder:text-gray-400 text-sm shadow-sm" />
                            <button @click="showPassword = !showPassword" type="button"
                                class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center text-gray-400 hover:text-[#e85d26] transition-colors">
                                <span x-show="!showPassword"><i data-lucide="eye" class="w-5 h-5"></i></span>
                                <span x-show="showPassword" style="display:none;"><i data-lucide="eye-off" class="w-5 h-5"></i></span>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="remember"
                                class="w-5 h-5 rounded-lg border-gray-300 focus:ring-[#e85d26]/20 transition-all"
                                style="accent-color: #e85d26;" checked />
                            <span
                                class="text-xs font-bold text-gray-500 group-hover:text-foreground transition-colors">Keep
                                me logged in</span>
                        </label>
                        <a href="{{ route('agent.forgot-password') }}"
                            class="text-xs font-bold hover:underline" style="color: #e85d26;">Forget password?</a>
                    </div>

                    <button type="submit"
                        class="cursor-pointer w-full text-white rounded-2xl py-4 font-bold text-sm shadow-lg transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2"
                        style="background-color: #e85d26; box-shadow: 0 10px 20px rgba(232, 93, 38, 0.2);"
                        onmouseover="this.style.backgroundColor='#d44f1c'"
                        onmouseout="this.style.backgroundColor='#e85d26'">
                        <span x-show="loading"
                            class="animate-spin w-4 h-4 border-2 border-white border-t-transparent rounded-full"></span>
                        <span>{{ $btnText }}</span>
                    </button>

                    <p class="text-xs font-bold text-gray-400 text-center mt-6">
                        Not registered yet? <a href="{{ route('agent.signup') }}" class="hover:underline font-bold"
                            style="color: #e85d26;">Create an Account</a>
                    </p>
                </form>
                <script>
                    window.onpageshow = function (event) {
                        if (event.persisted) {
                            window.location.reload();
                        }
                    };
                </script>

                <div class="pt-12">
                    <p class="text-[10px] font-medium text-gray-400 leading-loose">
                        Copyright © 2026 Tour Raja Private Limited, India. <br /> All rights reserved.
                    </p>
                </div>
            </div>
        </div>

        <!-- Orange Side -->
        <div class="hidden lg:flex lg:w-[55%] orange-side items-center justify-center relative"
            style="border-bottom-left-radius: 250px;">
            <div class="text-center space-y-4 w-full px-12">
                <!-- Large Logo -->
                <div class="flex items-center justify-center">
                    <x-logo white="true" class="h-24 sm:h-32 w-auto" />
                </div>
            </div>

            <!-- Footer Links -->
            <div
                class="absolute bottom-8 left-1/2 -translate-x-1/2 w-full px-6 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-center">
                <a href="{{ url('/about') }}"
                    class="text-[10px] font-bold text-white/70 hover:text-white uppercase tracking-widest transition-colors">About
                    Us</a>
                <a href="{{ url('/terms-and-conditions') }}"
                    class="text-[10px] font-bold text-white/70 hover:text-white uppercase tracking-widest transition-colors">License</a>
                <a href="{{ url('/terms-and-conditions') }}"
                    class="text-[10px] font-bold text-white/70 hover:text-white uppercase tracking-widest transition-colors">Terms
                    of Services</a>
                <a href="{{ url('/privacy-policy') }}"
                    class="text-[10px] font-bold text-white/70 hover:text-white uppercase tracking-widest transition-colors">Privacy
                    Policy</a>
            </div>
        </div>
    </div>

    <!-- Agent Support Chatbot -->
    <x-agent-support-chatbot />

    <script>
        lucide.createIcons();
    </script>
</body>

</html>