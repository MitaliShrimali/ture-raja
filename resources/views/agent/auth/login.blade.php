@extends('agent.layouts.guest')

@section('title', 'Sign In - Tour Raja Agent')

@section('content')
<div class="min-h-screen flex flex-col">
    <div class="flex-grow flex">
        <!-- Left Side: Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-24 bg-white">
            <div class="w-full max-w-md">
                <h1 class="text-4xl font-bold text-primary mb-2">Sign In</h1>
                <p class="text-gray-400 mb-8 font-medium">Enter your email and password to sign in!</p>

                <form id="signInForm" class="space-y-6" action="{{ route('agent.dashboard') }}" method="GET">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email<span class="text-primary">*</span></label>
                        <input type="email" name="email" value="admin@tourraja.com" placeholder="mail@simmmple.com" required
                               class="w-full px-4 py-4 rounded-2xl border border-gray-200 focus:border-primary focus:ring-0 outline-none transition-all placeholder:text-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password<span class="text-primary">*</span></label>
                        <div class="relative">
                            <input type="password" name="password" id="password" value="password123" placeholder="Min. 8 characters" required
                                   class="w-full px-4 py-4 rounded-2xl border border-gray-200 focus:border-primary focus:ring-0 outline-none transition-all placeholder:text-gray-300">
                            <button type="button" id="togglePassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-primary transition-colors">
                                <i class="fa-regular fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center text-sm font-medium text-gray-700 cursor-pointer">
                            <input type="checkbox" class="w-4 h-4 rounded text-primary border-gray-300 focus:ring-primary mr-2">
                            Keep me logged in
                        </label>
                        <a href="#" class="text-sm font-bold text-primary hover:underline">Forget password?</a>
                    </div>

                    <button type="submit" class="w-full bg-primary text-white py-4 rounded-2xl font-bold shadow-lg shadow-orange-200 hover:scale-[1.02] active:scale-95 transition-all">
                        Sign In
                    </button>
                </form>

                <p class="mt-8 text-sm text-gray-500 font-medium text-center lg:text-left">
                    Not registered yet? <a href="#" class="text-primary font-bold hover:underline">Create an Account</a>
                </p>

                <div class="mt-20 text-xs text-gray-400 font-medium text-center lg:text-left">
                    Copyright © 2026 Tour Raja Private Limited, India. All rights reserved.
                </div>
            </div>
        </div>
        <!-- Right Side: Brand Pattern -->
        <div class="hidden lg:flex w-1/2 bg-pattern items-center justify-center relative">
            
            <!-- White Border Overlay -->
            <div class="absolute inset-0 border-[30px] border-white pointer-events-none rounded-bl-[100px]"></div>

            <div class="text-center z-10 flex flex-col items-center justify-center">

                <!-- SVG Logo -->
                <div class="mb-6">
                    <img 
                        src="{{ asset('agent/assets/images/logo.svg') }}" 
                        alt="TourRaja Logo"
                        class="w-[420px] h-auto object-contain drop-shadow-2xl"
                    >
                </div>

                <!-- Bottom Links -->
                <div class="absolute bottom-10 left-0 right-0 flex justify-center space-x-8 text-white/80 text-sm font-medium">
                    <a href="#" class="hover:text-white transition-colors">About Us</a>
                    <a href="#" class="hover:text-white transition-colors">License</a>
                    <a href="#" class="hover:text-white transition-colors">Terms of Services</a>
                    <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                </div>
            </div>

            <!-- Chat Icon Floating -->
            <div class="absolute bottom-8 right-8 w-16 h-16 bg-white rounded-full shadow-2xl flex items-center justify-center text-blue-500 text-2xl border-4 border-blue-500/10">
                <i class="fas fa-comment-dots"></i>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    });
</script>
@endpush
