<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up – TourRaja</title>
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
            background-image: url("{{ asset('images/tourraja-bg.png') }}");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            position: relative;
        }

        /* Floating pill navbar */
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

        /* Toggle switch */
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

        /* Social buttons */
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
        $type = $type ?? 'customer';
    @endphp

    <!-- ─── Orange Hero ─── -->
    <div class="hero-bg">
        <!-- Floating Navbar -->
        <div class="max-w-5xl mx-auto px-6 pt-6 pb-4">
            <div class="navbar-pill rounded-full px-6 py-3 flex items-center justify-between">
                <x-logo white="true" class="h-8 w-auto" />
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ url('/signup') }}" class="nav-link flex items-center gap-1.5" style="color:#fff;">
                        <i data-lucide="user-plus" size="14"></i> Sign Up
                    </a>
                    <a href="{{ url('/login') }}" class="nav-link flex items-center gap-1.5">
                        <i data-lucide="log-in" size="14"></i> Sign In
                    </a>
                </div>
                <!-- Mobile menu toggle -->
                <button class="md:hidden text-white" @click="$refs.mobileNav.classList.toggle('hidden')">
                    <i data-lucide="menu" size="22"></i>
                </button>
            </div>
            <!-- Mobile nav dropdown -->
            <div x-ref="mobileNav" class="hidden md:hidden navbar-pill rounded-2xl mt-2 p-4 space-y-2">
                <a href="{{ url('/signup') }}" class="nav-link block py-2" style="color:#fff;">Sign Up</a>
                <a href="{{ url('/login') }}" class="nav-link block py-2">Sign In</a>
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
    <div class="flex-1 flex flex-col items-center justify-start px-4"
        style="margin-top: -180px; position: relative; z-index: 10;">
        <div class="card-form bg-white w-full max-w-md p-8 md:p-10 relative overflow-hidden" x-data="signupForm()">

            <h2 class="text-center text-2xl font-extrabold tracking-tight mb-6" style="color:#e85d26;">Sign Up</h2>

            <!-- Flash Messages -->
            @if($errors->any())
                <div
                    class="mb-5 p-4 bg-red-50 border border-red-100 rounded-xl text-red-600 text-xs font-semibold flex flex-col gap-1">
                    @foreach($errors->all() as $error)
                        <span>— {{ $error }}</span>
                    @endforeach
                </div>
            @endif

            <div x-show="formError" style="display: none;" class="mb-5 p-4 bg-red-50 border border-red-100 rounded-xl text-red-600 text-xs font-semibold flex items-center gap-2">
                <i data-lucide="alert-circle" size="14" class="shrink-0"></i>
                <span x-text="formError"></span>
            </div>

            <!-- Form -->
            <form id="signupForm" action="{{ url('/signup/submit') }}" method="POST" class="space-y-5" @submit.prevent="submitForm">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}" />

                <!-- Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Name</label>
                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                            placeholder="Your first name" class="input-field @error('first_name') !border-red-500 @enderror" />
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                            placeholder="Your last name" class="input-field @error('last_name') !border-red-500 @enderror" />
                    </div>
                    @error('first_name') <span class="text-[10px] text-red-500 font-medium block mt-1">{{ $message }}</span> @enderror
                    @error('last_name') <span class="text-[10px] text-red-500 font-medium block mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Email*</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="Your email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Please enter a valid email address"
                        class="input-field @error('email') !border-red-500 @enderror" />
                    @error('email') <span class="text-[10px] text-red-500 font-medium block mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Phone Number*</label>
                    <div style="display: flex; gap: 8px;">
                        <select name="country_code" class="input-field bg-white" style="width: 100px; flex-shrink: 0;" required>
                            <option value="+91">+91 (IN)</option>
                            <option value="+1">+1 (US/CA)</option>
                            <option value="+44">+44 (UK)</option>
                            <option value="+61">+61 (AU)</option>
                            <option value="+971">+971 (AE)</option>
                        </select>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required placeholder="Phone number" class="input-field @error('phone') !border-red-500 @enderror" style="flex: 1;" minlength="7" maxlength="15" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Please enter a valid phone number (digits only)" />
                    </div>
                    @error('phone') <span class="text-[10px] text-red-500 font-medium block mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Password*</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password" required placeholder="Your password" class="input-field pr-10 @error('password') !border-red-500 @enderror" />
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#e85d26] cursor-pointer" style="z-index: 10; display: flex; align-items: center; justify-content: center; height: 100%; padding: 0 10px;">
                            <span x-show="!showPassword"><i data-lucide="eye" class="w-5 h-5"></i></span>
                            <span x-show="showPassword" style="display:none;"><i data-lucide="eye-off" class="w-5 h-5"></i></span>
                        </button>
                    </div>
                    @error('password') <span class="text-[10px] text-red-500 font-medium block mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Confirm Password*</label>
                    <div class="relative">
                        <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" required placeholder="Re-enter password" class="input-field pr-10" />
                        <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#e85d26] cursor-pointer" style="z-index: 10; display: flex; align-items: center; justify-content: center; height: 100%; padding: 0 10px;">
                            <span x-show="!showConfirmPassword"><i data-lucide="eye" class="w-5 h-5"></i></span>
                            <span x-show="showConfirmPassword" style="display:none;"><i data-lucide="eye-off" class="w-5 h-5"></i></span>
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
                    class="cursor-pointer btn-signup w-full text-white rounded-xl py-3.5 font-bold text-sm uppercase tracking-widest shadow-lg mt-1 flex items-center justify-center gap-2">
                    <span x-show="loading" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                    <span x-text="loading ? 'Sending OTP...' : 'Sign Up'"></span>
                </button>

                <!-- Sign-in link -->
                <p class="text-xs text-gray-500 text-center pt-2">
                    Already have an account?
                    <a href="{{ url('/login') }}" class="font-bold hover:underline" style="color:#e85d26;">Sign in</a>
                </p>
            </form>

            <!-- OTP Modal Overlay -->
            <div x-show="showOtpModal" style="display: none;" class="absolute inset-0 bg-white z-50 flex flex-col items-center justify-center p-8 text-center transition-all">
                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-4 text-[#e85d26]">
                    <i data-lucide="smartphone" size="28"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-1">Verify Mobile</h3>
                <p class="text-xs text-gray-500 font-medium mb-6">Enter the 6-digit code sent to your phone.</p>

                <input type="text" x-model="otp" maxlength="6" class="text-center text-2xl font-black tracking-[0.5em] input-field mb-2" placeholder="------" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                
                <p x-show="errorMsg" x-text="errorMsg" class="text-[10px] text-red-500 font-bold mb-4"></p>

                <button @click="verifyOtp" class="btn-signup w-full text-white rounded-xl py-3.5 font-bold text-sm uppercase tracking-widest shadow-lg mb-4 flex items-center justify-center gap-2">
                    <span x-show="loading" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                    Verify & Create Account
                </button>

                <p class="text-xs font-semibold text-gray-400">
                    <span x-show="timer > 0">Resend in <span x-text="timer" class="text-[#e85d26]"></span>s</span>
                    <button x-show="timer === 0" @click="resendOtp" class="text-[#e85d26] hover:underline cursor-pointer">Resend OTP</button>
                </p>
                
                <button @click="showOtpModal = false; clearInterval(interval)" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
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

        function signupForm() {
            return {
                remember: true, 
                showPassword: false,
                showConfirmPassword: false,
                showOtpModal: false,
                otp: '',
                timer: 60,
                interval: null,
                loading: false,
                errorMsg: '',
                formError: '',

                submitForm(e) {
                    let password = document.querySelector('input[name="password"]').value;
                    let passwordConfirm = document.querySelector('input[name="password_confirmation"]').value;
                    
                    this.formError = '';
                    
                    if (password !== passwordConfirm) {
                        this.formError = 'Passwords do not match!';
                        return;
                    }
                    
                    let phone = document.querySelector('input[name="phone"]').value;
                    let countryCode = document.querySelector('select[name="country_code"]').value;
                    
                    if(!phone) return;
                    
                    this.loading = true;
                    this.errorMsg = '';
                    
                    fetch('/api/otp/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({ phone: countryCode + phone })
                    }).then(res => res.json())
                    .then(data => {
                        this.loading = false;
                        if(data.success) {
                            this.showOtpModal = true;
                            this.startTimer();
                        } else {
                            this.formError = data.message || 'Error sending OTP';
                        }
                    }).catch(err => {
                        this.loading = false;
                        this.formError = 'Failed to send OTP';
                    });
                },

                startTimer() {
                    this.timer = 60;
                    clearInterval(this.interval);
                    this.interval = setInterval(() => {
                        if(this.timer > 0) this.timer--;
                        else clearInterval(this.interval);
                    }, 1000);
                },

                resendOtp() {
                    if(this.timer > 0) return;
                    this.submitForm();
                },

                verifyOtp() {
                    if(this.otp.length < 6) {
                        this.errorMsg = 'Please enter 6-digit OTP';
                        return;
                    }
                    this.loading = true;
                    let phone = document.querySelector('select[name="country_code"]').value + document.querySelector('input[name="phone"]').value;
                    
                    fetch('/api/otp/verify', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({ phone: phone, otp: this.otp })
                    }).then(res => res.json())
                    .then(data => {
                        this.loading = false;
                        if(data.success) {
                            this.showOtpModal = false;
                            document.getElementById('signupForm').submit();
                        } else {
                            this.errorMsg = data.message || 'Invalid OTP';
                        }
                    }).catch(err => {
                        this.loading = false;
                        this.errorMsg = 'Failed to verify OTP';
                    });
                }
            }
        }
    </script>
</body>

</html>