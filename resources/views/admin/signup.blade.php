<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="{{ \Illuminate\Support\Facades\DB::table('settings')->where('key', 'favicon')->value('value') ?? asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign Up – Tour Raja</title>
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
            margin: 0;
        }

        .hero-bg {
            background-color: #e85d26;
            background-image: url("{{ asset('images/tour raja-bg.png') }}");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            position: relative;
        }

        .navbar-pill {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.85);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: #fff;
        }

        .card-form {
            border-radius: 24px;
            box-shadow: 0 20px 60px -15px rgba(0, 0, 0, 0.12);
        }

        .input-field {
            width: 100%;
            border: none;
            border-bottom: 1.5px solid #e5e7eb;
            padding: 14px 4px 10px 4px;
            font-size: 14px;
            font-weight: 500;
            color: #1a1a1a;
            background: transparent;
            outline: none;
            transition: border-color 0.2s;
        }

        .input-field::placeholder {
            color: #b0b0b0;
            font-weight: 400;
        }

        .input-field:focus {
            border-color: #e85d26;
        }

        .btn-signup {
            background-color: #e85d26;
            transition: background-color 0.2s, transform 0.15s;
        }

        .btn-signup:hover {
            background-color: #d04d18;
            transform: translateY(-1px);
        }

        .toggle-track {
            width: 44px;
            height: 24px;
            border-radius: 999px;
            position: relative;
            cursor: pointer;
            transition: background 0.25s;
        }

        .toggle-track.on {
            background: #e85d26;
        }

        .toggle-track.off {
            background: #d1d5db;
        }

        .toggle-knob {
            width: 18px;
            height: 18px;
            border-radius: 999px;
            background: #fff;
            position: absolute;
            top: 3px;
            transition: left 0.25s;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
        }

        .toggle-track.on .toggle-knob {
            left: 23px;
        }

        .toggle-track.off .toggle-knob {
            left: 3px;
        }

        .social-btn {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            border: 1.5px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fff;
        }

        .social-btn:hover {
            border-color: #e85d26;
            box-shadow: 0 2px 12px rgba(232, 93, 38, 0.12);
        }
    </style>
</head>

<body class="bg-[#f0f0f0] min-h-screen flex flex-col">

    @php
        $type = $type ?? 'admin';
    @endphp

    <!-- ─── Orange Hero ─── -->
    <div class="hero-bg">
        <!-- Floating Navbar -->
        <div class="max-w-5xl mx-auto px-6 pt-6 pb-4">
            <div class="navbar-pill rounded-full px-6 py-3 flex items-center justify-between">
                <x-logo white="true" class="h-8 w-auto" />
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ url('/admin/signup') }}" class="nav-link flex items-center gap-1.5" style="color:#fff;">
                        <i data-lucide="user-plus" size="14"></i> Sign Up
                    </a>
                    <a href="{{ url('/admin/login') }}" class="nav-link flex items-center gap-1.5">
                        <i data-lucide="log-in" size="14"></i> Sign In
                    </a>
                </div>
                <button class="md:hidden text-white" @click="$refs.mobileNav.classList.toggle('hidden')">
                    <i data-lucide="menu" size="22"></i>
                </button>
            </div>
            <div x-ref="mobileNav" class="hidden md:hidden navbar-pill rounded-2xl mt-2 p-4 space-y-2">
                <a href="{{ url('/admin/signup') }}" class="nav-link block py-2" style="color:#fff;">Sign Up</a>
                <a href="{{ url('/admin/login') }}" class="nav-link block py-2">Sign In</a>
            </div>
        </div>

        <!-- Welcome text -->
        <div class="text-center pt-10 px-6" style="padding-bottom: 240px;">
            <h1 class="font-black tracking-tight mb-2" style="color: #ffffff; font-size: 28px;">Welcome!</h1>
            <p class="text-sm max-w-md mx-auto leading-relaxed" style="color: rgba(255,255,255,0.9); font-weight: 500;">
                Join with Tour Raja,<br>We have wide range of travel category!
            </p>
        </div>
    </div>

    <!-- ─── Floating Card ─── -->
    <div class="flex-1 flex flex-col items-center justify-start px-4"
        style="margin-top: -180px; position: relative; z-index: 10;">
        <div class="card-form bg-white w-full max-w-md p-8 md:p-10" x-data="{ remember: true, showPassword: false }">

            <h2 class="text-center text-2xl font-extrabold tracking-tight mb-6" style="color:#e85d26;">Sign Up</h2>

            <!-- Social Buttons Removed -->

            <!-- Flash Messages -->
            @if(session('success'))
                <div
                    class="mb-5 p-4 bg-green-50 border border-green-100 rounded-xl text-green-700 text-xs font-semibold flex items-center gap-2">
                    <i data-lucide="check-circle-2" size="14" class="shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div
                    class="mb-5 p-4 bg-red-50 border border-red-100 rounded-xl text-red-600 text-xs font-semibold flex flex-col gap-1">
                    @foreach($errors->all() as $error)
                        <span>— {{ $error }}</span>
                    @endforeach
                </div>
            @endif

            <!-- Form -->
            <form action="{{ url('/signup/submit') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}" />

                <!-- Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Name</label>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                            placeholder="Your first name" class="input-field" />
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                            placeholder="Your last name" class="input-field" />
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Email*</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="Your email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Please enter a valid email address"
                        class="input-field" />
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Password*</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password" required placeholder="Your password" class="input-field pr-10" />
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#e85d26]">
                            <span x-show="!showPassword"><i data-lucide="eye" class="w-4 h-4"></i></span>
                            <span x-show="showPassword" style="display:none;"><i data-lucide="eye-off" class="w-4 h-4"></i></span>
                        </button>
                    </div>
                </div>

                <!-- Remember me toggle -->
                <div class="flex items-center gap-3 pt-2">
                    <div @click="remember = !remember" :class="remember ? 'on' : 'off'" class="toggle-track">
                        <div class="toggle-knob"></div>
                    </div>
                    <span class="text-xs font-semibold text-gray-600">Remember me</span>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="cursor-pointer btn-signup w-full text-white rounded-xl py-3.5 font-bold text-sm uppercase tracking-widest shadow-lg mt-1">
                    Sign Up
                </button>

                <!-- Sign-in link -->
                <p class="text-xs text-gray-500 text-center pt-2">
                    Already have an account?
                    <a href="{{ url('/admin/login') }}" class="font-bold hover:underline" style="color:#e85d26;">Sign
                        in</a>
                </p>
            </form>
        </div>
    </div>

    <!-- ─── Footer ─── -->
    <footer class="mt-auto py-8 px-6">
        <div class="max-w-5xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-[11px] text-gray-400 font-medium">
                Copyright © 2026 Tour Raja Private Limited, India. All rights reserved.
            </p>
            <div class="flex items-center gap-6">
                <a href="{{ url('/about') }}"
                    class="text-[11px] font-semibold text-gray-400 hover:text-gray-600 transition-colors">About Us</a>
                <a href="{{ url('/terms-and-conditions') }}"
                    class="text-[11px] font-semibold text-gray-400 hover:text-gray-600 transition-colors">License</a>
                <a href="{{ url('/terms-and-conditions') }}"
                    class="text-[11px] font-semibold text-gray-400 hover:text-gray-600 transition-colors">Terms of
                    Services</a>
                <a href="{{ url('/privacy-policy') }}"
                    class="text-[11px] font-semibold text-gray-400 hover:text-gray-600 transition-colors">Privacy
                    Policy</a>
            </div>
        </div>
    </footer>

    <script>
        window.onpageshow = function (e) { if (e.persisted) window.location.reload(); };
        lucide.createIcons();
    </script>
</body>

</html>
