<!-- Login Modal Component -->
<div 
    x-show="showLoginModal" 
    style="display: none;" 
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 md:p-6"
>
    <!-- Backdrop -->
    <div 
        x-show="showLoginModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-black/60 backdrop-blur-sm" 
        @click="showLoginModal = false"
    ></div>

    <!-- Modal Content (With Nature Background) -->
    <div 
        x-show="showLoginModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-8"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-8"
        class="relative w-full max-w-4xl rounded-3xl overflow-hidden shadow-2xl flex h-auto min-h-[520px]"
        @click.stop
    >
        <!-- Background Image for the entire modal -->
        <div class="absolute inset-0 z-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');">
            <!-- Subtle dark gradient overlay to make sure text and card pop -->
            <div class="absolute inset-0 bg-gradient-to-r from-black/40 to-black/20"></div>
        </div>

        <!-- Global Close Button -->
        <button 
            @click="showLoginModal = false" 
            class="absolute top-4 right-4 md:top-6 md:right-6 z-30 w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full bg-black/20 hover:bg-black/40 backdrop-blur-md text-white transition-all border border-white/20"
        >
            <i data-lucide="x" class="w-4 h-4 md:w-5 md:h-5" stroke-width="2.5"></i>
        </button>

        <!-- Content Area -->
        <div class="relative z-10 w-full h-full flex flex-col md:flex-row items-center p-4 md:p-10">
            
            <!-- Form Card (Glassmorphism) -->
            <div class="w-full max-w-md mx-auto md:mx-0 bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl p-8 md:p-10 overflow-y-auto max-h-[85vh] md:max-h-full"
                 x-data="{ 
                showPassword: false,
                showForgotPassword: false,
                loading: false,
                errorMessage: '',
                successMessage: '',
                async submitForm(e) {
                    if (this.showForgotPassword) {
                        return this.submitForgotPassword(e);
                    }
                    return this.submitLogin(e);
                },
                async submitForgotPassword(e) {
                    this.loading = true;
                    this.errorMessage = '';
                    this.successMessage = '';
                    const formData = new FormData(e.target);
                    try {
                        const response = await fetch('/forgot-password', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json'
                            },
                            body: formData
                        });
                        const data = await response.json();
                        if (response.ok) {
                            this.successMessage = data.message || 'If your email is registered, a reset link has been sent.';
                            setTimeout(() => {
                                this.showForgotPassword = false;
                                this.successMessage = '';
                                e.target.reset();
                            }, 4000);
                        } else {
                            this.errorMessage = data.message || 'Failed to send reset link.';
                        }
                    } catch (error) {
                        this.errorMessage = 'An error occurred. Please try again.';
                    } finally {
                        this.loading = false;
                    }
                },
                async submitLogin(e) {
                    this.loading = true;
                    this.errorMessage = '';
                    this.successMessage = '';
                    const formData = new FormData(e.target);
                    try {
                        const response = await fetch('/login/submit', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json'
                            },
                            body: formData
                        });
                        const data = await response.json();
                        if (response.ok && data.success) {
                            window.location.href = data.redirect || '/';
                        } else {
                            if (response.status === 422) {
                                this.errorMessage = Object.values(data.errors)[0][0];
                            } else {
                                this.errorMessage = data.message || 'Login failed.';
                            }
                        }
                    } catch (err) {
                        this.errorMessage = 'An error occurred. Please try again.';
                    }
                    this.loading = false;
                }
             }">

            <div class="space-y-6 w-full max-w-sm mx-auto">
                <div class="space-y-2 text-center md:text-left">
                    <h3 class="font-bold uppercase tracking-widest text-xs" style="color: #E8460A;">Welcome Back</h3>
                    <h2 class="font-extrabold text-gray-900 text-3xl" x-text="showForgotPassword ? 'Reset Password' : 'Login to Account'"></h2>
                </div>

                <form @submit.prevent="submitForm" class="space-y-5">
                    @csrf
                    <input type="hidden" name="redirect" value="{{ request()->fullUrl() }}">

                    <div x-show="showForgotPassword" style="display: none;" class="text-sm text-gray-500">
                        Enter your email address and we'll send you a link to reset your password.
                    </div>

                    <div x-show="errorMessage" x-text="errorMessage" class="bg-red-50 text-red-600 p-3 rounded-xl text-sm font-semibold border border-red-100" style="display: none;"></div>
                    <div x-show="successMessage" x-text="successMessage" class="bg-green-50 text-green-600 p-3 rounded-xl text-sm font-semibold border border-green-100" style="display: none;"></div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-gray-700 ml-1 tracking-wider uppercase">Email Address</label>
                        <input required type="email" name="email" placeholder="hello@tour raja.com" class="w-full bg-gray-50 border border-transparent rounded-xl py-3.5 px-4 outline-none focus:bg-white focus:border-[#E8460A] focus:ring-4 focus:ring-[#E8460A]/10 transition-all text-gray-800 placeholder:text-gray-400 text-sm font-medium" />
                    </div>

                    <div x-show="!showForgotPassword" class="space-y-1.5">
                        <div class="flex justify-between items-center ml-1">
                            <label class="text-[11px] font-bold text-gray-700 tracking-wider uppercase">Password</label>
                            <a href="#" @click.prevent="showForgotPassword = true; errorMessage = ''; successMessage = '';" class="text-xs font-bold hover:underline" style="color: #E8460A;">Forgot?</a>
                        </div>
                        <div class="relative">
                            <input :required="!showForgotPassword" :type="showPassword ? 'text' : 'password'" name="password" placeholder="••••••••" class="w-full bg-gray-50 border border-transparent rounded-xl py-3.5 px-4 pr-12 outline-none focus:bg-white focus:border-[#E8460A] focus:ring-4 focus:ring-[#E8460A]/10 transition-all text-gray-800 placeholder:text-gray-400 text-sm font-medium tracking-wider" />
                            <button @click="showPassword = !showPassword" type="button" class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center justify-center text-gray-400 hover:text-gray-700 transition-colors">
                                <template x-if="!showPassword"><i data-lucide="eye" size="18"></i></template>
                                <template x-if="showPassword"><i data-lucide="eye-off" size="18"></i></template>
                            </button>
                        </div>
                    </div>
                    
                    <div class="pt-2">
                        <button type="submit" :disabled="loading" class="w-full text-white rounded-xl py-3.5 font-bold text-sm shadow-md transition-all hover:shadow-lg hover:-translate-y-0.5 disabled:opacity-70 flex items-center justify-center gap-2" style="background-color: #E8460A;">
                            <span x-show="!loading" x-text="showForgotPassword ? 'Send Reset Link' : 'Sign In'"></span>
                            <span x-show="loading" x-text="showForgotPassword ? 'Sending...' : 'Signing in...'"></span>
                        </button>
                    </div>

                    <p x-show="showForgotPassword" style="display: none;" class="text-sm font-medium text-gray-600 text-center mt-6">
                        Remember your password? <a href="#" @click.prevent="showForgotPassword = false; errorMessage = ''; successMessage = '';" class="font-bold hover:underline text-gray-900">Back to login</a>
                    </p>
                    
                    <p x-show="!showForgotPassword" class="text-sm font-medium text-gray-600 text-center mt-6">
                        Don't have an account? <a href="{{ url('/signup') }}" class="font-bold hover:underline" style="color: #E8460A;">Create one</a>
                    </p>
                </form>
            </div>
        </div>

        <!-- Text area on the right (Desktop only) -->
        <div class="hidden md:flex flex-col justify-end p-8 text-white ml-auto w-[45%] h-full">
            <h3 class="text-3xl md:text-4xl font-bold mb-3 drop-shadow-lg leading-tight" style="color: #E8460A;">Explore the World</h3>
            <p class="text-sm md:text-base font-medium leading-relaxed drop-shadow-md text-white/90">Join Tour Raja today and discover premium travel experiences curated just for you.</p>
        </div>
    </div>
    </div>
</div>
