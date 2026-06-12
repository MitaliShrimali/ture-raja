@extends('layouts.admin')

@section('admin_title', 'Email Template Management')

@section('content')
<div class="space-y-8 pb-12" x-data="{
    templateTitle: 'Premium Safari Welcome Sequence',
    subjectLine: 'Your Journey to the Wild Begins...',
    category: 'Pre-Departure',
    imageUrl: 'https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?auto=format&fit=crop&q=80&w=800',
    messageBody: 'Your expedition to the Serengeti is more than just a trip—it\'s a narrative waiting to be written. You\'ve finalized your itinerary for the {TripTitle} starting on {StartDate}.\n\n“The only man I envy is the man who has not yet been to Africa - for he has so much to look forward to.”\n\nPlease review your documents in the client portal. Our team is standing by to ensure every detail of your horizon is perfectly framed.',
    buttonText: 'View Your Dashboard',
    layout: 'hero_banner',
    
    // Live preview token helper
    get previewText() {
        let text = this.messageBody;
        text = text.replace(/{TravelerName}/g, 'John Doe');
        text = text.replace(/{TripTitle}/g, 'Premium Serengeti Odyssey');
        text = text.replace(/{StartDate}/g, 'July 12, 2026');
        text = text.replace(/{Destination}/g, 'Tanzania');
        text = text.replace(/{AgentName}/g, 'Miths Holidays');
        text = text.replace(/{ItineraryLink}/g, 'tourraja.com/itinerary/129');
        return text.replace(/\n/g, '<br>');
    },

    insertToken(token) {
        const textarea = this.$refs.bodyTextarea;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        const before = text.substring(0, start);
        const after = text.substring(end, text.length);
        this.messageBody = before + token + after;
        
        // Restore cursor
        this.$nextTick(() => {
            textarea.focus();
            textarea.selectionStart = textarea.selectionEnd = start + token.length;
        });
    }
}">

    {{-- ===== BREADCRUMB & HEADER ===== --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1">
            <div class="flex items-center gap-2 text-xs font-black text-gray-400 uppercase tracking-widest">
                <span>Campaigns</span>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-[#b13c0b]">Email Templates</span>
            </div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight mt-1">Email Template Management</h2>
            <p class="text-xs text-gray-400 font-semibold leading-relaxed max-w-xl">
                Design personalized communication for your premium travelers. Use high-contrast editorial layouts to maintain brand prestige.
            </p>
        </div>
        
        <div class="flex items-center gap-3 w-full md:w-auto">
            <button type="button" class="w-1/2 md:w-auto border border-[#b13c0b] text-[#b13c0b] hover:bg-[#b13c0b]/5 px-6 py-3.5 rounded-2xl font-black text-xs transition-all flex items-center justify-center gap-2 uppercase tracking-wider">
                Preview Template
            </button>
            <button type="button" style="background-color: #b13c0b;" class="w-1/2 md:w-auto hover:opacity-90 text-white px-6 py-3.5 rounded-2xl font-black text-xs transition-all shadow-xl flex items-center justify-center gap-2 uppercase tracking-wider">
                Save Template
            </button>
        </div>
    </div>

    {{-- ===== MAIN GRID LAYOUT ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Left & Center: Fields & Editor --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Fields --}}
            <div class="bg-white rounded-[32px] p-8 shadow-premium border border-border-soft space-y-6">
                
                {{-- Template Title --}}
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Template Title</label>
                    <input type="text" x-model="templateTitle" class="w-full bg-[#FFF5F2] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-bold text-foreground shadow-sm" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Subject Line --}}
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Subject Line</label>
                        <input type="text" x-model="subjectLine" class="w-full bg-[#FFF5F2] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-bold text-[#b13c0b] shadow-sm" />
                    </div>

                    {{-- Category --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Category</label>
                        <select x-model="category" class="w-full bg-[#FFF5F2] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-bold text-foreground shadow-sm">
                            <option value="Pre-Departure">Pre-Departure</option>
                            <option value="Welcome Sequence">Welcome Sequence</option>
                            <option value="Feedback">Feedback</option>
                        </select>
                    </div>
                </div>

            </div>

            {{-- Editor Workspace --}}
            <div class="bg-white rounded-[32px] overflow-hidden border border-border-soft shadow-premium">
                
                {{-- Toolbar --}}
                <div class="bg-[#FCF8F7] px-6 py-4 flex items-center justify-between border-b border-border-soft text-xs text-gray-500 font-bold">
                    <div class="flex items-center gap-4">
                        <button type="button" class="hover:text-primary transition-colors"><i data-lucide="bold" class="w-4 h-4"></i></button>
                        <button type="button" class="hover:text-primary transition-colors"><i data-lucide="italic" class="w-4 h-4"></i></button>
                        <button type="button" class="hover:text-primary transition-colors"><i data-lucide="underline" class="w-4 h-4"></i></button>
                        <span class="text-gray-300">|</span>
                        <button type="button" class="hover:text-primary transition-colors"><i data-lucide="list" class="w-4 h-4"></i></button>
                        <button type="button" class="hover:text-primary transition-colors"><i data-lucide="list-ordered" class="w-4 h-4"></i></button>
                        <span class="text-gray-300">|</span>
                        <button type="button" class="hover:text-primary transition-colors"><i data-lucide="image" class="w-4 h-4"></i></button>
                        <button type="button" class="hover:text-primary transition-colors"><i data-lucide="link" class="w-4 h-4"></i></button>
                    </div>
                    <div class="flex items-center gap-4">
                        <span>AUTOSAVED 2M AGO</span>
                        <button type="button" @click="messageBody = ''" class="text-[#b13c0b] hover:opacity-80 transition-opacity">CLEAR ALL</button>
                    </div>
                </div>

                {{-- Preview Canvas Editor Container --}}
                <div class="p-8 space-y-6">
                    
                    {{-- Banner Image --}}
                    <div class="w-full h-48 rounded-2xl overflow-hidden bg-gray-50">
                        <img :src="imageUrl" class="w-full h-full object-cover" />
                    </div>

                    {{-- Salutation --}}
                    <h3 class="text-2xl font-black text-foreground">Hello {TravelerName},</h3>

                    {{-- Textarea Body Editor --}}
                    <textarea 
                        x-ref="bodyTextarea" 
                        x-model="messageBody" 
                        rows="8" 
                        class="w-full border-none outline-none resize-none font-medium text-gray-700 text-sm bg-transparent focus:ring-0 leading-relaxed"
                        placeholder="Write your email body copy..."
                    ></textarea>

                    {{-- CTA Button Preview --}}
                    <div class="pt-4">
                        <button type="button" style="background-color: #b13c0b;" class="text-white px-6 py-3 rounded-xl text-xs font-black uppercase tracking-wider shadow-sm">
                            <span x-text="buttonText"></span>
                        </button>
                    </div>

                </div>

            </div>

        </div>

        {{-- Right Side: Parameters & Templates --}}
        <div class="space-y-6">
            
            {{-- Smart Tokens --}}
            <div class="bg-[#FFF5F2] rounded-[32px] p-6 border border-border-soft space-y-4">
                <div class="flex items-center gap-2 text-[#b13c0b]">
                    <i data-lucide="variable" class="w-4 h-4"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">SMART TOKENS</span>
                </div>
                <p class="text-[10px] text-gray-400 font-bold leading-relaxed">
                    Click a token to insert it at your cursor position. These automatically pull traveler data.
                </p>
                <div class="flex flex-wrap gap-2">
                    <button type="button" @click="insertToken('{TravelerName}')" class="px-3 py-1.5 bg-white hover:bg-gray-100 rounded-full text-[10px] font-black text-gray-700 shadow-sm border border-gray-100 transition-all">
                        {TravelerName}
                    </button>
                    <button type="button" @click="insertToken('{TripTitle}')" class="px-3 py-1.5 bg-white hover:bg-gray-100 rounded-full text-[10px] font-black text-gray-700 shadow-sm border border-gray-100 transition-all">
                        {TripTitle}
                    </button>
                    <button type="button" @click="insertToken('{StartDate}')" class="px-3 py-1.5 bg-white hover:bg-gray-100 rounded-full text-[10px] font-black text-gray-700 shadow-sm border border-gray-100 transition-all">
                        {StartDate}
                    </button>
                    <button type="button" @click="insertToken('{Destination}')" class="px-3 py-1.5 bg-white hover:bg-gray-100 rounded-full text-[10px] font-black text-gray-700 shadow-sm border border-gray-100 transition-all">
                        {Destination}
                    </button>
                    <button type="button" @click="insertToken('{AgentName}')" class="px-3 py-1.5 bg-white hover:bg-gray-100 rounded-full text-[10px] font-black text-gray-700 shadow-sm border border-gray-100 transition-all">
                        {AgentName}
                    </button>
                    <button type="button" @click="insertToken('{ItineraryLink}')" class="px-3 py-1.5 bg-white hover:bg-gray-100 rounded-full text-[10px] font-black text-gray-700 shadow-sm border border-gray-100 transition-all">
                        {ItineraryLink}
                    </button>
                </div>
            </div>

            {{-- Performance predictor --}}
            <div class="bg-white rounded-[32px] p-6 border border-border-soft space-y-4 shadow-sm">
                <span class="text-[10px] font-black text-muted-text uppercase tracking-widest block">PERFORMANCE PREDICTOR</span>
                
                <div class="space-y-4">
                    <div class="space-y-1">
                        <div class="flex justify-between items-center text-xs font-bold">
                            <span class="text-gray-500">Subject Strength</span>
                            <span class="text-foreground">84%</span>
                        </div>
                        <div class="w-full bg-gray-100 h-1.5 rounded-full overflow-hidden">
                            <div class="h-full rounded-full" style="background-color: #b13c0b; width: 84%;"></div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center text-xs font-bold pt-2 border-t border-gray-50">
                        <span class="text-gray-500">Readability Score</span>
                        <span class="text-foreground">Grade 7</span>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-4 flex gap-3 items-start mt-2">
                        <div class="p-1.5 bg-[#FFF5F2] text-[#b13c0b] rounded-lg shrink-0">
                            <i data-lucide="trending-up" class="w-4 h-4"></i>
                        </div>
                        <p class="text-[10px] text-gray-500 font-bold leading-normal">
                            <strong class="text-foreground block mb-0.5">GROWTH TIP</strong>
                            Shortening the first paragraph by 10 words could increase click-through by 5%.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Change layout --}}
            <div class="space-y-3">
                <span class="text-[10px] font-black text-muted-text uppercase tracking-widest block pl-2">CHANGE LAYOUT</span>
                
                <div class="grid grid-cols-2 gap-4">
                    {{-- Hero Banner active --}}
                    <div @click="layout = 'hero_banner'" :class="layout === 'hero_banner' ? 'border-[#b13c0b] ring-2 ring-[#b13c0b]/20' : 'border-gray-200'" class="bg-[#FFF5F2] border rounded-2xl p-4 flex flex-col items-center justify-center text-center cursor-pointer transition-all h-24">
                        <span class="text-[10px] font-black uppercase text-[#b13c0b]">Hero Banner</span>
                    </div>
                    {{-- Split Side --}}
                    <div @click="layout = 'split_side'" :class="layout === 'split_side' ? 'border-[#b13c0b] ring-2 ring-[#b13c0b]/20' : 'border-gray-200'" class="bg-white border rounded-2xl p-4 flex flex-col items-center justify-center text-center cursor-pointer transition-all h-24">
                        <span class="text-[10px] font-black uppercase text-gray-500">Split Side</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
