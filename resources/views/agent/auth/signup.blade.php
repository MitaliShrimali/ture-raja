<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="{{ \Illuminate\Support\Facades\DB::table('settings')->where('key', 'favicon')->value('value') ?? asset('favicon.ico') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Sign Up – Tour Raja</title>
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

    <!-- ─── Orange Hero ─── -->
    <div class="hero-bg">
        <!-- Floating Navbar -->
        <div class="max-w-5xl mx-auto px-6 pt-6 pb-4">
            <div class="navbar-pill rounded-full px-6 py-3 flex items-center justify-between">
                <x-logo white="true" class="h-8 w-auto" />
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('agent.signup') }}" class="nav-link flex items-center gap-1.5"
                        style="color:#fff;">
                        <i data-lucide="user-plus" size="14"></i> Sign Up
                    </a>
                    <a href="{{ route('agent.login') }}" class="nav-link flex items-center gap-1.5">
                        <i data-lucide="log-in" size="14"></i> Sign In
                    </a>
                </div>
            </div>
        </div>

        <!-- Welcome text -->
        <div class="text-center pt-10 px-6" style="padding-bottom: 240px;">
            <h1 class="font-black tracking-tight mb-2" style="color: #ffffff; font-size: 28px;">Agent Registration</h1>
            <p class="text-sm max-w-md mx-auto leading-relaxed" style="color: rgba(255,255,255,0.9); font-weight: 500;">
                Join Tour Raja as a partner agent and list your tour packages today!
            </p>
        </div>
    </div>

    <!-- ─── Floating Card ─── -->
    <div class="flex-1 flex flex-col items-center justify-start px-4 mb-12"
        style="margin-top: -180px; position: relative; z-index: 10;">
        <div class="card-form bg-white w-full max-w-md p-8 md:p-10 relative overflow-hidden" x-data="agentSignupForm()">

            <h2 class="text-center text-2xl font-extrabold tracking-tight mb-6" style="color:#e85d26;">Sign Up</h2>

            <!-- Flash Messages -->
            @if(session('error'))
                <div
                    class="mb-5 p-4 bg-red-50 border border-red-100 rounded-xl text-red-600 text-xs font-semibold flex items-center gap-2">
                    <i data-lucide="alert-circle" size="14" class="shrink-0"></i>
                    <span>{{ session('error') }}</span>
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

            <div x-show="formError" style="display: none;" class="mb-5 p-4 bg-red-50 border border-red-100 rounded-xl text-red-600 text-xs font-semibold flex items-center gap-2">
                <i data-lucide="alert-circle" size="14" class="shrink-0"></i>
                <span x-text="formError"></span>
            </div>

            <!-- Form -->
            <form id="agentSignupForm" action="{{ route('agent.signup.submit') }}" method="POST" class="space-y-5" @submit.prevent="submitForm">
                @csrf

                <!-- Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Your full name"
                        class="input-field" />
                </div>

                <!-- Agency Name -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Agency Name</label>
                    <input type="text" name="agency_name" value="{{ old('agency_name') }}" required
                        placeholder="Your travel agency name" class="input-field" />
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Agent Email*</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="agent@youragency.com" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Please enter a valid email address" class="input-field" />
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Mobile Number*</label>
                    <div class="flex gap-2 items-center">
                        <div class="relative w-28 shrink-0">
                            <select class="phone-country-code w-full border-none rounded-xl py-3.5 px-3 outline-none text-gray-700 text-xs font-medium"
                                style="background-color: #f3f4f6;">
                                <option value="+91" data-len="10" selected>🇮🇳 +91</option>
                                <option value="+1" data-len="10">🇺🇸 +1</option>
                                <option value="+44" data-len="10">🇬🇧 +44</option>
                                <option value="+62" data-len="11">🇮🇩 +62</option>
                                <option value="+65" data-len="8">🇸🇬 +65</option>
                                <option value="+971" data-len="9">🇦🇪 +971</option>
                                <option value="+61" data-len="9">🇦🇺 +61</option>
                                <option value="+66" data-len="9">🇹🇭 +66</option>
                                <option value="+60" data-len="10">🇲🇾 +60</option>
                            </select>
                        </div>
                        <div class="relative flex-grow">
                            <input type="tel" required placeholder="Mobile Number *"
                                class="phone-number-val w-full border-none rounded-xl py-3.5 px-4 outline-none text-gray-700 text-xs font-medium"
                                style="background-color: #f3f4f6;">
                        </div>
                    </div>
                    <input type="hidden" class="phone-full-val" name="phone" value="{{ old('phone') }}">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Password*</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password" required placeholder="Min. 8 characters" class="input-field pr-10" />
                        <button type="button" @click="showPassword = !showPassword" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#e85d26]">
                            <span x-show="!showPassword"><i data-lucide="eye" class="w-4 h-4"></i></span>
                            <span x-show="showPassword" style="display:none;"><i data-lucide="eye-off" class="w-4 h-4"></i></span>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Confirm Password*</label>
                    <div class="relative">
                        <input :type="showConfirmPassword ? 'text' : 'password'" name="password_confirmation" required placeholder="Re-enter password" class="input-field pr-10" />
                        <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#e85d26]">
                            <span x-show="!showConfirmPassword"><i data-lucide="eye" class="w-4 h-4"></i></span>
                            <span x-show="showConfirmPassword" style="display:none;"><i data-lucide="eye-off" class="w-4 h-4"></i></span>
                        </button>
                    </div>
                </div>

                <!-- Terms checkbox -->
                <label class="flex items-start gap-3 cursor-pointer group pt-1">
                    <input required type="checkbox" name="terms"
                        class="w-5 h-5 rounded-lg border-gray-300 text-[#e85d26] focus:ring-[#e85d26]/20 transition-all mt-0.5 shrink-0"
                        style="accent-color: #e85d26;" />
                    <span
                        class="text-xs font-semibold text-gray-600 group-hover:text-foreground transition-colors leading-relaxed">
                        I agree to the <a href="{{ url('/terms-and-conditions') }}" class="hover:underline font-bold"
                            style="color: #e85d26;">Terms & Conditions</a> and <a href="{{ url('/privacy-policy') }}"
                            class="hover:underline font-bold" style="color: #e85d26;">Privacy Policy</a>
                    </span>
                </label>

                <!-- Submit -->
                <button type="submit"
                    class="cursor-pointer btn-signup w-full text-white rounded-xl py-3.5 font-bold text-sm uppercase tracking-widest shadow-lg mt-1 flex items-center justify-center gap-2">
                    <span x-show="loading" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                    <span x-text="loading ? 'Sending OTP...' : 'Create Agent Account'"></span>
                </button>

                <!-- Sign-in link -->
                <p class="text-xs text-gray-500 text-center pt-2">
                    Already have an account?
                    <a href="{{ route('agent.login') }}" class="font-bold hover:underline" style="color:#e85d26;">Sign
                        in</a>
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

        document.addEventListener('DOMContentLoaded', () => {
            const select = document.querySelector('.phone-country-code');
            if (select) {
                const flexContainer = select.closest('.flex') || select.parentElement;
                const input = flexContainer.querySelector('.phone-number-val');
                const wrapper = flexContainer.parentElement;
                const hidden = wrapper.querySelector('.phone-full-val');

                if (hidden && hidden.value) {
                    const val = hidden.value;
                    const options = Array.from(select.options);
                    options.sort((a, b) => b.value.length - a.value.length);
                    for (let opt of options) {
                        if (val.startsWith(opt.value)) {
                            select.value = opt.value;
                            input.value = val.substring(opt.value.length);
                            break;
                        }
                    }
                }

                function validatePhone() {
                    const code = select.value;
                    const length = parseInt(select.options[select.selectedIndex].getAttribute('data-len') || '10');
                    let val = input.value.replace(/\D/g, '');
                    if (val.length > length) {
                        val = val.substring(0, length);
                    }
                    input.value = val;
                    if (val.length > 0 && val.length !== length) {
                        input.setCustomValidity(`Phone number must be exactly ${length} digits.`);
                    } else {
                        input.setCustomValidity('');
                    }
                    hidden.value = val ? (code + val) : '';
                }

                select.addEventListener('change', validatePhone);
                input.addEventListener('input', validatePhone);
                
                const form = select.closest('form');
                if (form) {
                    form.addEventListener('submit', validatePhone);
                }

                validatePhone();
            }
        });

        function agentSignupForm() {
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
                    
                    // Trigger manual validation update for hidden field
                    const select = document.querySelector('.phone-country-code');
                    const input = document.querySelector('.phone-number-val');
                    if(input && !input.checkValidity()) {
                        input.reportValidity();
                        return;
                    }

                    let phone = document.querySelector('.phone-full-val').value;
                    let email = document.querySelector('input[name="email"]').value;
                    if(!phone) return;
                    
                    this.loading = true;
                    this.errorMsg = '';
                    
                    fetch('{{ url('/api/otp/send') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({ phone: phone, email: email })
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
                    let phone = document.querySelector('.phone-full-val').value;
                    
                    fetch('{{ url('/api/otp/verify') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({ phone: phone, otp: this.otp })
                    }).then(res => res.json())
                    .then(data => {
                        this.loading = false;
                        if(data.success) {
                            this.showOtpModal = false;
                            document.getElementById('agentSignupForm').submit();
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
    <!-- Agent Support Chatbot -->
    <x-agent-support-chatbot />
</body>

</html>
