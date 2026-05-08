<footer class="bg-primary pt-24 pb-12 text-white overflow-hidden relative">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full blur-2xl translate-y-1/2 -translate-x-1/2"></div>

    <div class="container-custom relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 mb-20">
            <!-- Brand Column -->
            <div class="space-y-8">
                <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-primary font-bold text-2xl shadow-xl transition-transform group-hover:rotate-6">
                        T
                    </div>
                    <span class="text-3xl font-bold tracking-tight font-heading text-white">
                        Tour<span class="text-white/80">Raja</span>
                    </span>
                </a>
                <p class="text-white/80 leading-relaxed font-medium">
                    Experience the world like never before. We curate premium travel experiences tailored to your desires. Your global adventure starts with TourRaja.
                </p>
                <div class="flex items-center gap-5">
                    <a href="#" class="w-10 h-10 bg-white/10 hover:bg-white text-white hover:text-primary rounded-full flex items-center justify-center transition-all duration-300">
                        <i data-lucide="facebook" size="20"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/10 hover:bg-white text-white hover:text-primary rounded-full flex items-center justify-center transition-all duration-300">
                        <i data-lucide="instagram" size="20"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-white/10 hover:bg-white text-white hover:text-primary rounded-full flex items-center justify-center transition-all duration-300">
                        <i data-lucide="twitter" size="20"></i>
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
