<footer class="bg-primary pt-24 pb-12 text-white overflow-hidden relative">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full blur-2xl translate-y-1/2 -translate-x-1/2"></div>

    <div class="container-custom relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 mb-20">
            <!-- Brand Column -->
            <div class="space-y-8">
                <a href="{{ url('/') }}" class="flex items-center group">
                    <x-logo class="h-10 sm:h-12 w-auto text-white" />
                </a>
                <p class="text-white/80 leading-relaxed font-medium">
                    Experience the world like never before. We curate premium travel experiences tailored to your desires. Your global adventure starts with TourRaja.
                </p>
                <div class="flex items-center gap-5">
                    <a href="#" class="w-10 h-10 bg-white/10 hover:bg-white text-white hover:text-primary rounded-full flex items-center justify-center transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/10 hover:bg-white text-white hover:text-primary rounded-full flex items-center justify-center transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/10 hover:bg-white text-white hover:text-primary rounded-full flex items-center justify-center transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Company -->
            <div class="space-y-8">
                <h4 class="text-xl font-bold font-heading">Company</h4>
                <ul class="space-y-5 text-white/80 font-medium">
                    <li><a href="#" class="hover:text-white transition-colors flex items-center gap-2">About Us</a></li>
                    <li><a href="#" class="hover:text-white transition-colors flex items-center gap-2">Destinations</a></li>
                    <li><a href="#" class="hover:text-white transition-colors flex items-center gap-2">Our Agents</a></li>
                    <li><a href="#" class="hover:text-white transition-colors flex items-center gap-2">Contact Us</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div class="space-y-8">
                <h4 class="text-xl font-bold font-heading">Support</h4>
                <ul class="space-y-5 text-white/80 font-medium">
                    <li><a href="#" class="hover:text-white transition-colors">Help Center</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Safety Guides</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="space-y-8">
                <h4 class="text-xl font-bold font-heading">Get in Touch</h4>
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center shrink-0">
                            <i data-lucide="map-pin" size="20"></i>
                        </div>
                        <p class="text-white/80 font-medium pt-1">123 Travel Suite, <br/>Adventure Bay, World 45678</p>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center shrink-0">
                            <i data-lucide="phone" size="20"></i>
                        </div>
                        <p class="text-white/80 font-medium pt-2">+1 (234) 567-890</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-12 border-t border-white/20 flex flex-col md:flex-row items-center justify-between gap-6 text-white/60 text-sm font-bold">
            <p>© {{ date('Y') }} TourRaja Global. All rights reserved.</p>
            <div class="flex items-center gap-8">
                <a href="#" class="hover:text-white transition-colors">Privacy</a>
                <a href="#" class="hover:text-white transition-colors">Terms</a>
                <a href="#" class="hover:text-white transition-colors">Cookies</a>
            </div>
        </div>
    </div>
</footer>
