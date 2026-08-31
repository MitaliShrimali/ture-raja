@extends('layouts.app')

@section('content')
<div class="bg-gray-50 pb-12" style="padding-top: 48px;">
    <div class="container-custom">
        <div class="w-full">
            <h1 class="font-black text-primary mb-6" style="font-size: 30px; line-height: 1.2;">PRIVACY POLICY AND TERM OF USE</h1>
            
            <div class="prose prose-lg max-w-none text-text-muted cms-content">
                {!! \Illuminate\Support\Facades\DB::table('cms_pages')->where('slug', 'privacy-policy')->value('content') !!}
            </div>
        </div>
    </div>

    <!-- Newsletter Section -->
    <div class="mt-12">
        <section class="pb-12 lg:pb-20 bg-gray-50">
            <div class="container-custom mx-auto px-6">
                <div class="bg-[#FFF9F0] rounded-[48px] overflow-hidden flex flex-col lg:flex-row">
                    <!-- Left: Content -->
                    <div class="flex-1 p-8 lg:p-20 flex flex-col justify-center space-y-8">
                        <div class="space-y-6 max-w-lg">
                            <span class="inline-block px-4 py-1.5 rounded-full bg-primary text-white text-[10px] font-black uppercase tracking-widest shadow-glow">
                                Join our newsletter
                            </span>
                            <h2 class="font-black text-foreground leading-[1.1] tracking-tight font-heading" style="font-size: 38px;">
                                Subscribe to see secret deals prices drop the moment you sign up!
                            </h2>
                            
                            @if(session('success') && str_contains(session('success'), 'subscrib'))
                                <div class="p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 font-bold text-sm flex items-center gap-3">
                                    <i data-lucide="circle-check" size="20"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="relative max-w-md mt-10">
                                @csrf
                                <input type="email" name="email" placeholder="Your Email" required class="w-full h-16 pl-8 pr-40 rounded-full bg-white border-none shadow-soft text-foreground font-bold focus:ring-2 focus:ring-primary/20 placeholder:text-text-muted/50">
                                <button type="submit" class="absolute right-2 top-2 h-12 px-8 rounded-full bg-black text-white font-black text-xs uppercase tracking-widest hover:bg-primary transition-all duration-300">
                                    Subscribe
                                </button>
                            </form>
                            <p class="text-text-muted text-xs font-bold opacity-60">No ads. No trails. No commitments</p>
                        </div>
                    </div>

                    <!-- Right: Image -->
                    <div class="flex-1 min-h-[300px] md:min-h-[400px] lg:min-h-[500px] relative">
                        <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?auto=format&fit=crop&q=80&w=1200" class="absolute inset-0 w-full h-full object-cover">
                        <!-- Glass Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-[#FFF9F0] to-transparent h-32 w-full md:h-full md:w-32"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

<style>
    .cms-content h1 { font-size: 2.25rem; font-weight: 800; margin-bottom: 1rem; margin-top: 2rem; color: #1f2937; line-height: 1.2; }
    .cms-content h2 { font-size: 1.875rem; font-weight: 700; margin-bottom: 1rem; margin-top: 2rem; color: #374151; line-height: 1.3; }
    .cms-content h3 { font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem; margin-top: 1.5rem; color: #4b5563; }
    .cms-content p { margin-bottom: 1.25rem; line-height: 1.7; }
    .cms-content ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1.25rem; }
    .cms-content ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1.25rem; }
    .cms-content li { margin-bottom: 0.5rem; }
    .cms-content a { color: #e85d26; text-decoration: underline; }
    .cms-content strong { font-weight: 700; color: #111827; }
    .cms-content br { margin-bottom: 1rem; }
</style>
