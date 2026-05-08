@extends('layouts.app')

@section('content')
    <div class="pt-40 pb-20">
        <div class="container-custom">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                <span class="text-primary font-bold tracking-widest uppercase text-sm">Contact Us</span>
                <h1 class="text-5xl font-black font-syne">Let's Plan Your <span class="text-primary">Next Adventure</span></h1>
                <p class="text-xl text-gray-500 font-medium">
                    Have questions about our packages or need a custom itinerary? Our travel experts are here to help you 24/7.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Contact Info Cards -->
                <div class="lg:col-span-1 space-y-6">
                    @php
                        $contactItems = [
                            ['icon' => 'phone', 'title' => 'Call Us', 'content' => '+1 (234) 567-890', 'sub' => 'Mon-Fri from 8am to 8pm'],
                            ['icon' => 'mail', 'title' => 'Email Us', 'content' => 'hello@tourraja.com', 'sub' => "We'll respond within 24 hours"],
                            ['icon' => 'map-pin', 'title' => 'Visit Us', 'content' => '123 Travel St, World City', 'sub' => 'Come say hello in person'],
                        ];
                    @endphp
                    @foreach($contactItems as $item)
                        <div class="bg-white p-8 rounded-[32px] shadow-soft border border-gray-50 flex items-start gap-6 hover:shadow-card transition-all">
                            <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shrink-0">
                                <i data-lucide="{{ $item['icon'] }}" size="28"></i>
                            </div>
                            <div class="space-y-1">
                                <h4 class="text-lg font-bold">{{ $item['title'] }}</h4>
                                <p class="text-xl font-black text-foreground">{{ $item['content'] }}</p>
                                <p class="text-gray-400 text-sm font-medium">{{ $item['sub'] }}</p>
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="bg-foreground rounded-[32px] p-8 text-white space-y-6 relative overflow-hidden">
                        <div class="relative z-10 space-y-4">
                            <h4 class="text-2xl font-bold font-syne">Need Instant Help?</h4>
                            <p class="text-white/60">Chat with our AI assistant or a human agent right now.</p>
                            <button class="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-full font-bold transition-all flex items-center gap-3">
                                <i data-lucide="message-square" size="20"></i>
                                <span>Start Live Chat</span>
                            </button>
                        </div>
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-2 bg-white rounded-[40px] p-8 md:p-16 shadow-soft border border-gray-50">
                    <form class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @csrf
                        <div class="space-y-3">
                            <label class="text-sm font-bold uppercase tracking-widest text-gray-400">Full Name</label>
                            <input type="text" placeholder="John Doe" class="w-full bg-background border border-gray-100 rounded-2xl py-5 px-6 font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                        </div>
                        <div class="space-y-3">
                            <label class="text-sm font-bold uppercase tracking-widest text-gray-400">Email Address</label>
                            <input type="email" placeholder="john@example.com" class="w-full bg-background border border-gray-100 rounded-2xl py-5 px-6 font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                        </div>
                        <div class="space-y-3">
                            <label class="text-sm font-bold uppercase tracking-widest text-gray-400">Phone Number</label>
                            <input type="text" placeholder="+1 (234) 567-890" class="w-full bg-background border border-gray-100 rounded-2xl py-5 px-6 font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                        </div>
                        <div class="space-y-3">
                            <label class="text-sm font-bold uppercase tracking-widest text-gray-400">Subject</label>
                            <div class="relative">
                                <select class="w-full bg-background border border-gray-100 rounded-2xl py-5 px-6 font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none">
                                    <option>General Inquiry</option>
                                    <option>Booking Problem</option>
                                    <option>Custom Package</option>
                                    <option>Partner With Us</option>
                                </select>
                                <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" size="20"></i>
                            </div>
                        </div>
                        <div class="md:col-span-2 space-y-3">
                            <label class="text-sm font-bold uppercase tracking-widest text-gray-400">Your Message</label>
                            <textarea rows={6} placeholder="Tell us about your dream trip..." class="w-full bg-background border border-gray-100 rounded-2xl py-5 px-6 font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none"></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <button class="w-full bg-primary hover:bg-primary-hover text-white py-6 rounded-[24px] font-black text-xl transition-all shadow-lg shadow-primary/30 flex items-center justify-center gap-4">
                                Send Message Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
