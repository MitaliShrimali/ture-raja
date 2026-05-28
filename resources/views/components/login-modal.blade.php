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
        class="absolute inset-0 bg-black/50 backdrop-blur-sm" 
        @click="showLoginModal = false"
    ></div>

    <!-- Modal Content (Overlap Layout) -->
    <div 
        x-show="showLoginModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="relative w-full max-w-5xl flex flex-row items-center justify-center px-4 md:px-0"
        style="height: 550px;"
        @click.stop
    >
        <!-- Image Side (Left) - Shorter to give overlapping effect -->
        <div style="height: 480px; background: #f3f4f6 url('https://6a0bf3ee063c0d21459114f4.imgix.net/istockphoto-1141191468-612x612.jpg') left center/cover no-repeat;"
             class="rounded-xl relative z-0 hidden md:block w-full md:w-[45%] shadow-sm">
        </div>

        <!-- Form Side (Right) - Taller with shadow and negative margin to overlap image -->
        <div class="bg-white rounded-3xl p-8 md:p-10 relative flex flex-col justify-center h-full max-h-[550px] shadow-[-10px_0_30px_rgba(0,0,0,0.15)] z-10 md:-ml-8 w-full md:w-[58%]" 
             x-data="{ 
                showPassword: false,
                loading: false,
                errorMessage: '',
                async submitLogin(e) {
                    this.loading = true;
                    this.errorMessage = '';
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
            
            <!-- Close Button -->
            <button 
                @click="showLoginModal = false" 
                class="absolute top-6 right-6 z-10 w-8 h-8 flex items-center justify-center rounded-full border-2 text-primary hover:bg-primary/10 transition-colors"
                style="border-color: #E8460A; color: #E8460A;"
            >
                <i data-lucide="x" size="18" stroke-width="2.5"></i>
            </button>

            <div class="space-y-6">
                <div class="space-y-1 pb-4 border-b border-gray-100">
                    <h3 class="font-bold text-black text-sm">Welcome to Tour Raja</h3>
                    <h2 class="font-extrabold text-black" style="font-size: 28px; line-height: 1.2;">Enter Your Email & Password</h2>
                </div>

                <form @submit.prevent="submitLogin" action="/login/submit" method="POST" class="space-y-5">
                    @csrf
                    <!-- Redirect back to the page they were on -->
                    <input type="hidden" name="redirect" value="{{ request()->fullUrl() }}">

                    <div x-show="errorMessage" x-text="errorMessage" class="bg-red-50 text-red-600 p-3 rounded-lg text-sm font-semibold border border-red-200" style="display: none;"></div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-800">Email*</label>
                        <input required type="email" name="email" placeholder="Enter Email" class="w-full bg-white border border-gray-300 rounded-lg py-3 px-4 outline-none focus:ring-1 transition-colors text-gray-800 placeholder:text-gray-400 text-sm" style="border-color: #e5e7eb; outline-color: #E8460A;" />
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-800">Password*</label>
                        <div class="relative">
                            <input required :type="showPassword ? 'text' : 'password'" name="password" placeholder="Enter Password" class="w-full bg-white border border-gray-300 rounded-lg py-3 px-4 pr-12 outline-none focus:ring-1 transition-colors text-gray-800 placeholder:text-gray-400 text-sm" style="border-color: #e5e7eb; outline-color: #E8460A;" />
                            <button @click="showPassword = !showPassword" type="button" class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center justify-center text-gray-800 hover:text-black transition-colors">
                                <template x-if="!showPassword">
                                    <i data-lucide="eye" size="18"></i>
                                </template>
                                <template x-if="showPassword">
                                    <i data-lucide="eye-off" size="18"></i>
                                </template>
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex justify-end pt-1">
                        <a href="#" class="text-sm font-bold hover:underline" style="color: #E8460A;">Forgot Password?</a>
                    </div>
                    
                    <div class="pt-4">
                        <button type="submit" :disabled="loading" class="w-full text-white rounded-lg py-3.5 font-bold text-base shadow-sm transition-colors hover:opacity-90 disabled:opacity-70 flex items-center justify-center gap-2" style="background-color: #E8460A;">
                            <span x-show="!loading">Login</span>
                            <span x-show="loading">Logging in...</span>
                        </button>
                    </div>
                    
                    <p class="text-sm font-medium text-black text-center mt-4">
                        Don't Have an Account? <a href="{{ url('/signup') }}" class="font-bold hover:underline" style="color: #E8460A;">create new account</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
