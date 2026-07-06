<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up – TourRaja</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <!-- <script src="https://unpkg.com/@tailwindcss/browser@4"></script> -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; margin: 0; }

        .hero-bg {
            background-color: #e85d26;
            background-image: url("{{ asset('images/tourraja-bg.png') }}");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            position: relative;
        }

        /* Floating pill navbar */
        .navbar-pill {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.18);
        }

        .nav-link {
            color: rgba(255,255,255,0.85);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            transition: color 0.2s;
        }
        .nav-link:hover { color: #fff; }

        .card-form {
            border-radius: 24px;
            box-shadow: 0 20px 60px -15px rgba(0,0,0,0.12);
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
        .input-field::placeholder { color: #b0b0b0; font-weight: 400; }
        .input-field:focus { border-color: #e85d26; }

        .btn-signup {
            background-color: #e85d26;
            transition: background-color 0.2s, transform 0.15s;
        }
        .btn-signup:hover {
            background-color: #d04d18;
            transform: translateY(-1px);
        }

        /* Toggle switch */
        .toggle-track {
            width: 44px; height: 24px;
            border-radius: 999px;
            position: relative;
            cursor: pointer;
            transition: background 0.25s;
        }
        .toggle-track.on  { background: #e85d26; }
        .toggle-track.off { background: #d1d5db; }
        .toggle-knob {
            width: 18px; height: 18px;
            border-radius: 999px;
            background: #fff;
            position: absolute;
            top: 3px;
            transition: left 0.25s;
            box-shadow: 0 1px 3px rgba(0,0,0,0.15);
        }
        .toggle-track.on  .toggle-knob { left: 23px; }
        .toggle-track.off .toggle-knob { left: 3px;  }

        /* Social buttons */
        .social-btn {
            width: 52px; height: 52px;
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
            box-shadow: 0 2px 12px rgba(232,93,38,0.12);
        }
    </style>
</head>
<body class="bg-[#f0f0f0] min-h-screen flex flex-col">

    @php
        $type = $type ?? 'customer';
    @endphp

    <!-- ─── Orange Hero ─── -->
    <div class="hero-bg">
        <!-- Floating Navbar -->
        <div class="max-w-5xl mx-auto px-6 pt-6 pb-4">
            <div class="navbar-pill rounded-full px-6 py-3 flex items-center justify-between">
                <x-logo white="true" class="h-8 w-auto" />
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ url('/admin/dashboard') }}" class="nav-link flex items-center gap-1.5">
                        <i data-lucide="layout-dashboard" size="14"></i> Dashboard
                    </a>
                    <a href="{{ url('/profile') }}" class="nav-link flex items-center gap-1.5">
                        <i data-lucide="user" size="14"></i> Profile
                    </a>
                    <a href="{{ url('/signup') }}" class="nav-link flex items-center gap-1.5" style="color:#fff;">
                        <i data-lucide="user-plus" size="14"></i> Sign Up
                    </a>
                    <a href="{{ url('/login') }}" class="nav-link flex items-center gap-1.5">
                        <i data-lucide="log-in" size="14"></i> Sign In
                    </a>
                </div>
                <a href="{{ url('/contact') }}" class="hidden md:inline-flex items-center gap-2 bg-[#e85d26] hover:bg-[#d04d18] text-white text-[11px] font-bold uppercase tracking-wider px-5 py-2.5 rounded-full transition-all shadow-lg shadow-black/10">
                    Contact Admin
                </a>
                <!-- Mobile menu toggle -->
                <button class="md:hidden text-white" @click="$refs.mobileNav.classList.toggle('hidden')">
                    <i data-lucide="menu" size="22"></i>
                </button>
            </div>
            <!-- Mobile nav dropdown -->
            <div x-ref="mobileNav" class="hidden md:hidden navbar-pill rounded-2xl mt-2 p-4 space-y-2">
                <a href="{{ url('/admin/dashboard') }}" class="nav-link block py-2">Dashboard</a>
                <a href="{{ url('/profile') }}" class="nav-link block py-2">Profile</a>
                <a href="{{ url('/signup') }}" class="nav-link block py-2" style="color:#fff;">Sign Up</a>
                <a href="{{ url('/login') }}" class="nav-link block py-2">Sign In</a>
                <a href="{{ url('/contact') }}" class="nav-link block py-2">Contact Admin</a>
            </div>
        </div>

        <!-- Welcome text -->
        <div class="text-center pt-10 px-6" style="padding-bottom: 240px;">
            <h1 class="font-black tracking-tight mb-2" style="color: #ffffff; font-size: 28px;">Welcome!</h1>
            <p class="text-sm max-w-md mx-auto leading-relaxed" style="color: rgba(255,255,255,0.9); font-weight: 500;">
                Join with TourRaja,<br>We have wide range of travel category!
            </p>
        </div>
    </div>

    <!-- ─── Floating Card ─── -->
    <div class="flex-1 flex flex-col items-center justify-start px-4" style="margin-top: -180px; position: relative; z-index: 10;">
        <div class="card-form bg-white w-full max-w-md p-8 md:p-10" x-data="{ remember: true }">

            <h2 class="text-center text-2xl font-extrabold tracking-tight mb-6" style="color:#e85d26;">Sign Up</h2>

            <!-- Social Buttons -->
            <div class="flex items-center justify-center gap-5 mb-5">
                <button type="button" class="social-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#1877F2"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12z"/></svg>
                </button>
                <button type="button" class="social-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="#000"><path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.53 4.08zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.32 2.32-2.13 4.47-3.74 4.25z"/></svg>
                </button>
                <button type="button" class="social-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                </button>
            </div>

            <p class="text-center text-xs text-gray-400 mb-6">or</p>

            <!-- Flash Messages -->
            @if($errors->any())
                <div class="mb-5 p-4 bg-red-50 border border-red-100 rounded-xl text-red-600 text-xs font-semibold flex flex-col gap-1">
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
                            placeholder="Your first name"
                            class="input-field" />
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                            placeholder="Your last name"
                            class="input-field" />
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Email*</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="Your email"
                        class="input-field" />
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Password*</label>
                    <input type="password" name="password" required
                        placeholder="Your password"
                        class="input-field" />
                </div>

                <!-- Remember me toggle -->
                <div class="flex items-center gap-3 pt-2">
                    <div
                        @click="remember = !remember"
                        :class="remember ? 'on' : 'off'"
                        class="toggle-track">
                        <div class="toggle-knob"></div>
                    </div>
                    <span class="text-xs font-semibold text-gray-600">Remember me</span>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="btn-signup w-full text-white rounded-xl py-3.5 font-bold text-sm uppercase tracking-widest shadow-lg mt-1">
                    Sign Up
                </button>

                <!-- Sign-in link -->
                <p class="text-xs text-gray-500 text-center pt-2">
                    Already have an account?
                    <a href="{{ url('/login') }}" class="font-bold hover:underline" style="color:#e85d26;">Sign in</a>
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
                <a href="{{ url('/about') }}" class="text-[11px] font-semibold text-gray-400 hover:text-gray-600 transition-colors">About Us</a>
                <a href="{{ url('/terms-and-conditions') }}" class="text-[11px] font-semibold text-gray-400 hover:text-gray-600 transition-colors">License</a>
                <a href="{{ url('/terms-and-conditions') }}" class="text-[11px] font-semibold text-gray-400 hover:text-gray-600 transition-colors">Terms of Services</a>
                <a href="{{ url('/privacy-policy') }}" class="text-[11px] font-semibold text-gray-400 hover:text-gray-600 transition-colors">Privacy Policy</a>
            </div>
        </div>
    </footer>

    <script>
        window.onpageshow = function(e) { if (e.persisted) window.location.reload(); };
        lucide.createIcons();
    </script>
</body>
</html>
