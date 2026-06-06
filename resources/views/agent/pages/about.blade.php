@extends('agent.layouts.app')

@section('title', 'About - Tour Raja Agent')

@section('content')
<div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-xs text-gray-400 font-medium">Pages / Why US</p>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">About Us</h2>
            </div>
        </div>

        <div class="space-y-24 max-w-6xl mx-auto">
            <!-- Our Mission -->
            <div class="bg-white p-12 rounded-[48px] shadow-sm border border-gray-100 flex flex-col lg:flex-row items-center gap-12">
                <div class="w-full lg:w-1/2">
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/female-tourist-walking-with-travel-bag-5621458-4682021.png" class="w-full rounded-[32px] bg-blue-50/50 p-8">
                </div>
                <div class="w-full lg:w-1/2 space-y-6">
                    <h3 class="text-4xl font-bold text-gray-800 tracking-tight">Our Mission</h3>
                    <p class="text-xs text-gray-400 font-medium leading-relaxed">At the heart of Tour Raja lies a singular conviction: the best travel memories are made when human expertise meets local passion. In an era of automated booking engines and generic itineraries, we champion the <strong>Tour Raja Pvt. Ltd.</strong></p>
                    <p class="text-xs text-gray-400 font-medium leading-relaxed">Our mission is to restore the "human touch" to global exploration. We bridge the gap between luxury and authenticity, ensuring every journey is as unique as the person taking it. By vetting our partners with rigorous standards, we ensure that every Tour Raja experience is seamless, safe, and profoundly inspiring.</p>
                    <div class="pt-6 flex items-center">
                        <div class="h-[1px] w-12 bg-orange-800 mr-4"></div>
                        <p class="text-[10px] font-bold text-orange-800 uppercase tracking-widest">Authenticity. Reliability. Discovery.</p>
                    </div>
                </div>
            </div>

            <!-- The Story -->
            <div class="flex flex-col lg:flex-row gap-12 items-center">
                <div class="w-full lg:w-1/2 space-y-8">
                    <h3 class="text-4xl font-bold text-gray-800 tracking-tight">The Tour Raja Story</h3>
                    <p class="text-xs text-gray-400 font-medium leading-relaxed">Born from a garage in 2018, Tour Raja started as a simple directory of local guides. We realized that travelers weren't just looking for "where to go," but "who to trust."</p>
                    <p class="text-xs text-gray-400 font-medium leading-relaxed">Today, we've grown into a global ecosystem powering over 50,000 journeys annually. Our platform serves as the bridge between thousands of independent agents and explorers seeking the extraordinary.</p>
                    
                    <div class="grid grid-cols-3 gap-8 pt-8 border-t border-gray-100">
                        <div>
                            <p class="text-2xl font-bold text-orange-800 mb-1">120+</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Countries</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-orange-800 mb-1">5k+</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Experts</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-orange-800 mb-1">98%</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Trust Rating</p>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 grid grid-cols-2 gap-4">
                    <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=300" class="w-full aspect-square object-cover rounded-[32px]">
                    <div class="bg-cyan-100/50 rounded-[32px] p-6 flex flex-col justify-center items-center text-center">
                        <i class="fas fa-map-marked-alt text-cyan-600 text-4xl mb-4"></i>
                        <p class="text-xs font-bold text-cyan-800">Global Coverage</p>
                    </div>
                    <img src="https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=300" class="w-full aspect-square object-cover rounded-[32px]">
                    <img src="https://images.unsplash.com/photo-1501785888041-af3ef285b470?q=80&w=300" class="w-full aspect-square object-cover rounded-[32px]">
                </div>
            </div>

            <!-- Why Trust Section -->
            <div class="text-center space-y-4">
                <h3 class="text-4xl font-bold text-gray-800 tracking-tight">Why Trust Tour Raja?</h3>
                <p class="text-xs text-gray-400 font-medium">The standard for excellence in travel advisory services.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-12">
                    <!-- Card 1 -->
                    <div class="bg-white p-10 rounded-[48px] shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-gray-200/50 transition-all text-left group">
                        <div class="w-12 h-12 bg-orange-50 text-orange-800 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-orange-800 group-hover:text-white transition-colors">
                            <i class="fas fa-shield-alt text-xl"></i>
                        </div>
                        <h4 class="text-sm font-bold text-gray-800 mb-4">Strict Vetting</h4>
                        <p class="text-[10px] text-gray-400 font-medium leading-relaxed">Every agent on our platform undergoes a multi-stage verification process, including background checks and portfolio reviews.</p>
                    </div>
                    <!-- Card 2 -->
                    <div class="bg-white p-10 rounded-[48px] shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-gray-200/50 transition-all text-left group">
                        <div class="w-12 h-12 bg-orange-50 text-orange-800 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-orange-800 group-hover:text-white transition-colors">
                            <i class="fas fa-search-location text-xl"></i>
                        </div>
                        <h4 class="text-sm font-bold text-gray-800 mb-4">Local Deep-Knowledge</h4>
                        <p class="text-[10px] text-gray-400 font-medium leading-relaxed">We prioritize experts who live in or have extensive first-hand experience with the destinations they represent.</p>
                    </div>
                    <!-- Card 3 -->
                    <div class="bg-white p-10 rounded-[48px] shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-gray-200/50 transition-all text-left group">
                        <div class="w-12 h-12 bg-orange-50 text-orange-800 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-orange-800 group-hover:text-white transition-colors">
                            <i class="fas fa-headset text-xl"></i>
                        </div>
                        <h4 class="text-sm font-bold text-gray-800 mb-4">24/7 Human Concierge</h4>
                        <p class="text-[10px] text-gray-400 font-medium leading-relaxed">Beyond the booking, our agents are your advocates. From delays to last-minute pivots, we've got your back.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
@endsection
