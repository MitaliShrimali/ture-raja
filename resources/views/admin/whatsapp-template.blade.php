@extends('layouts.admin')

@section('admin_title', 'Whatsapp Template')

@section('content')
@php
    $defaultWhatsapp = "Hi {{CustomerName}}! 👋\n\nThank you for choosing Tour raja for your next expedition. We are thrilled to confirm your booking {{BookingID}} for the upcoming adventure on {{TourDate}}.\n\nOur team is currently preparing all the details to ensure you have a premium experience. Your assigned expert, {{AgentName}}, will be in touch shortly with the full itinerary";
    $whatsappTemplate = $settings['whatsapp_message_template'] ?? $defaultWhatsapp;
@endphp
<div class="space-y-8 pb-12" x-data="{
    templateName: 'Booking Confirmation - Premium Tours',
    messageBody: {{ json_encode($whatsappTemplate) }},
    
    // Live preview token replacements
    get previewText() {
        let text = this.messageBody;
        
        // Wrap variables in blue colored spans to match mockup
        text = text.replace(/\{\{CustomerName\}\}/g, '<span class=&quot;text-[#0284c7] font-black&quot;>John Doe</span>');
        text = text.replace(/\{\{BookingID\}\}/g, '<span class=&quot;text-[#0284c7] font-black&quot;>#TR-8821</span>');
        text = text.replace(/\{\{TourDate\}\}/g, '<span class=&quot;text-[#0284c7] font-black&quot;>Oct 24, 2024</span>');
        text = text.replace(/\{\{AgentName\}\}/g, '<span class=&quot;text-[#0284c7] font-black&quot;>Sarah</span>');
        
        // Format bold *text*
        text = text.replace(/\*(.*?)\*/g, '<strong>$1</strong>');
        
        // Handle newlines
        return text.replace(/\n/g, '<br>');
    },

    insertVariable(variable) {
        const textarea = this.$refs.editorTextarea;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        const before = text.substring(0, start);
        const after = text.substring(end, text.length);
        this.messageBody = before + variable + after;
        
        this.$nextTick(() => {
            textarea.focus();
            textarea.selectionStart = textarea.selectionEnd = start + variable.length;
        });
    }
}">

    {{-- ===== TOP METRICS CARDS ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Card 1: Remaining Credits --}}
        <div class="bg-white rounded-[28px] border border-border-soft shadow-premium p-6 flex items-center justify-between hover:shadow-lg transition-shadow">
            <div class="space-y-1">
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-wider block">REMAINING CREDITS</span>
                <span class="text-3xl font-black text-gray-900 block leading-none">24,500</span>
                <span class="text-[9px] font-black text-[#b13c0b] block mt-1">⌁ Auto-refill enabled</span>
            </div>
            <div class="w-10 h-10 bg-[#FFF5F2] rounded-xl flex items-center justify-center text-[#b13c0b]">
                <i data-lucide="wallet" class="w-5 h-5"></i>
            </div>
        </div>

        {{-- Card 2: Messages Sent Today --}}
        <div class="bg-white rounded-[28px] border border-border-soft shadow-premium p-6 flex items-center justify-between hover:shadow-lg transition-shadow">
            <div class="space-y-1">
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-wider block">MESSAGES SENT TODAY</span>
                <span class="text-3xl font-black text-gray-900 block leading-none">1,842</span>
                <span class="text-[9px] font-black text-[#b13c0b] block mt-1">✓ 99.8% Delivery rate</span>
            </div>
            <div class="w-10 h-10 bg-[#FFF5F2] rounded-xl flex items-center justify-center text-[#b13c0b]">
                <i data-lucide="send" class="w-5 h-5"></i>
            </div>
        </div>

        {{-- Card 3: Avg Response Time --}}
        <div class="bg-white rounded-[28px] border border-border-soft shadow-premium p-6 flex items-center justify-between hover:shadow-lg transition-shadow">
            <div class="space-y-1">
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-wider block">AVG RESPONSE TIME</span>
                <span class="text-3xl font-black text-gray-900 block leading-none">12m</span>
                <span class="text-[9px] font-black text-gray-400 block mt-1">🕒 -2m from yesterday</span>
            </div>
            <div class="w-10 h-10 bg-[#FFF5F2] rounded-xl flex items-center justify-center text-[#b13c0b]">
                <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
        </div>
    </div>

    {{-- ===== MAIN GRID LAYOUT ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Left: Configuration & Message Content Editor --}}
        <form action="{{ url('admin/settings/whatsapp-template/update') }}" method="POST" class="lg:col-span-2 space-y-6">
            @csrf
            
            <div class="space-y-1 pl-2">
                <h2 class="text-2xl font-black text-gray-900 tracking-tight">Whatsapp Template</h2>
                <p class="text-xs text-gray-400 font-semibold">
                    Craft your automated WhatsApp communication with precision.
                </p>
            </div>

            {{-- Template Name --}}
            <div class="bg-white rounded-[28px] p-6 shadow-premium border border-border-soft space-y-2">
                <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest pl-1">TEMPLATE NAME</label>
                <input type="text" x-model="templateName" class="w-full bg-[#FFF5F2] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#b13c0b]/20 transition-all font-bold text-foreground shadow-sm" />
            </div>

            {{-- Insert Template Variables --}}
            <div class="bg-white rounded-[28px] p-6 shadow-premium border border-border-soft space-y-4">
                <div class="flex items-center justify-between">
                    <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest pl-1">INSERT TEMPLATE VARIABLES</label>
                    <span class="px-2 py-0.5 rounded bg-[#FFF5F2] text-[8px] font-black text-[#b13c0b]">4 AVAILABLE</span>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    <button type="button" @click="insertVariable('<?php echo '{{CustomerName}}'; ?>')" class="px-4 py-2.5 bg-[#FFF5F2] hover:bg-[#b13c0b]/5 rounded-full text-xs font-black text-gray-700 shadow-sm border border-gray-100 flex items-center gap-1.5 transition-all">
                        <i data-lucide="plus-circle" class="w-4 h-4 text-[#b13c0b]"></i> <?php echo '{{CustomerName}}'; ?>
                    </button>
                    <button type="button" @click="insertVariable('<?php echo '{{BookingID}}'; ?>')" class="px-4 py-2.5 bg-[#FFF5F2] hover:bg-[#b13c0b]/5 rounded-full text-xs font-black text-gray-700 shadow-sm border border-gray-100 flex items-center gap-1.5 transition-all">
                        <i data-lucide="plus-circle" class="w-4 h-4 text-[#b13c0b]"></i> <?php echo '{{BookingID}}'; ?>
                    </button>
                    <button type="button" @click="insertVariable('<?php echo '{{TourDate}}'; ?>')" class="px-4 py-2.5 bg-[#FFF5F2] hover:bg-[#b13c0b]/5 rounded-full text-xs font-black text-gray-700 shadow-sm border border-gray-100 flex items-center gap-1.5 transition-all">
                        <i data-lucide="plus-circle" class="w-4 h-4 text-[#b13c0b]"></i> <?php echo '{{TourDate}}'; ?>
                    </button>
                    <button type="button" @click="insertVariable('<?php echo '{{AgentName}}'; ?>')" class="px-4 py-2.5 bg-[#FFF5F2] hover:bg-[#b13c0b]/5 rounded-full text-xs font-black text-gray-700 shadow-sm border border-gray-100 flex items-center gap-1.5 transition-all">
                        <i data-lucide="plus-circle" class="w-4 h-4 text-[#b13c0b]"></i> <?php echo '{{AgentName}}'; ?>
                    </button>
                </div>
            </div>

            {{-- Message Content Editor --}}
            <div class="bg-white rounded-[28px] overflow-hidden border border-border-soft shadow-premium">
                <div class="bg-[#FCF8F7] px-6 py-4 flex items-center justify-between border-b border-border-soft text-xs text-gray-500 font-bold">
                    <div class="flex items-center gap-4">
                        <button type="button" @click="insertVariable('*')" title="Bold formatting" class="hover:text-primary transition-colors flex items-center gap-1">
                            <i data-lucide="bold" class="w-4 h-4 text-gray-600"></i>
                        </button>
                        <button type="button" @click="insertVariable('_')" title="Italic formatting" class="hover:text-primary transition-colors flex items-center gap-1">
                            <i data-lucide="italic" class="w-4 h-4 text-gray-600"></i>
                        </button>
                        <button type="button" class="hover:text-primary transition-colors"><i data-lucide="link" class="w-4 h-4 text-gray-600"></i></button>
                        <button type="button" class="hover:text-primary transition-colors"><i data-lucide="list" class="w-4 h-4 text-gray-600"></i></button>
                        <button type="button" class="hover:text-primary transition-colors"><i data-lucide="smile" class="w-4 h-4 text-gray-600"></i></button>
                        <button type="button" class="hover:text-primary transition-colors"><i data-lucide="paperclip" class="w-4 h-4 text-gray-600"></i></button>
                    </div>
                </div>

                <div class="p-6">
                    <textarea 
                        x-ref="editorTextarea" 
                        x-model="messageBody" 
                        name="whatsapp_message_template"
                        rows="12" 
                        class="w-full border-none outline-none resize-none font-bold text-gray-700 text-sm bg-transparent focus:ring-0 leading-relaxed"
                        placeholder="Write your WhatsApp template body here..."
                    ></textarea>
                </div>
            </div>

            {{-- Footer Action Bar --}}
            <div class="flex items-center justify-between pt-4">
                <a href="{{ url('admin/settings') }}" class="text-xs font-black text-gray-400 hover:text-gray-600 uppercase tracking-widest pl-2">
                    Discard Draft
                </a>
                <button type="submit" style="background-color: #b13c0b;" class="hover:opacity-90 text-white px-8 py-3.5 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl transition-all">
                    Save Template
                </button>
            </div>

            {{-- Bottom Meta Alert Info --}}
            <div class="bg-gray-50 rounded-3xl p-6 border border-gray-100 flex items-start gap-4">
                <div class="p-2 bg-[#FFF5F2] text-[#b13c0b] rounded-xl shrink-0">
                    <i data-lucide="info" class="w-4 h-4"></i>
                </div>
                <p class="text-[11px] text-gray-500 font-semibold leading-relaxed">
                    Templates are synced from Meta Business Manager. Any changes to variables must match the approved template structure to avoid delivery failure.
                </p>
            </div>

        </form>

        {{-- Right: Live Preview & Compliance --}}
        <div class="space-y-6">
            
            <div class="space-y-3">
                <span class="text-[10px] font-black text-muted-text uppercase tracking-widest block pl-2">REAL-TIME PREVIEW</span>
                
                {{-- WhatsApp Phone Mockup --}}
                <div class="bg-slate-100 rounded-[44px] p-4 border-[8px] border-slate-900 shadow-2xl relative max-w-sm mx-auto overflow-hidden">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-6 bg-slate-900 rounded-b-2xl z-20 flex items-center justify-center">
                        <span class="w-2 h-2 bg-slate-800 rounded-full"></span>
                    </div>
                    
                    <div class="bg-[#efeae2] rounded-[30px] overflow-hidden min-h-[480px] flex flex-col justify-between relative pt-8 pb-4">
                        
                        {{-- Chat Top Bar --}}
                        <div class="absolute top-0 left-0 right-0 bg-[#075e54] text-white p-3 pt-6 flex items-center justify-between z-10 shadow-sm">
                            <div class="flex items-center gap-2">
                                <i data-lucide="arrow-left" class="w-4 h-4 text-white/80"></i>
                                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                                    <i data-lucide="user" class="w-4 h-4 text-white"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-black flex items-center gap-1">
                                        Tour raja Concierge
                                        <span class="bg-[#128c7e] text-white p-0.5 rounded-full text-[6px]">✓</span>
                                    </span>
                                    <span class="text-[8px] text-white/70">Online</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 text-white/80">
                                <i data-lucide="video" class="w-4 h-4"></i>
                                <i data-lucide="phone" class="w-4 h-4"></i>
                                <i data-lucide="more-vertical" class="w-4 h-4"></i>
                            </div>
                        </div>

                        {{-- Chat History --}}
                        <div class="flex-1 p-3 overflow-y-auto space-y-4 pt-16">
                            
                            {{-- Day indicator --}}
                            <div class="flex justify-center">
                                <span class="bg-white/70 text-[9px] font-black text-gray-500 px-3 py-1 rounded-lg uppercase tracking-wider">
                                    TODAY
                                </span>
                            </div>

                            {{-- Chat Bubble --}}
                            <div class="bg-white rounded-2xl shadow-sm max-w-[85%] border-t border-l border-gray-100 p-3 text-[11px] text-gray-700 leading-relaxed font-semibold relative flex flex-col">
                                <p x-html="previewText"></p>
                                <span class="text-[7px] text-gray-400 font-bold block text-right mt-1">12:45 PM</span>
                            </div>

                        </div>

                        {{-- Chat Input Mockup --}}
                        <div class="px-2 flex items-center gap-2">
                            <div class="flex-1 bg-white rounded-full py-2 px-4 flex items-center justify-between text-gray-400">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="smile" class="w-4 h-4"></i>
                                    <span class="text-[10px] font-semibold">Type a message</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i data-lucide="paperclip" class="w-4 h-4"></i>
                                    <i data-lucide="camera" class="w-4 h-4"></i>
                                </div>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-[#075e54] flex items-center justify-center text-white shrink-0">
                                <i data-lucide="mic" class="w-4 h-4"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Compliance Check --}}
            <div class="bg-white rounded-[32px] p-6 border border-border-soft space-y-4 shadow-sm">
                <span class="text-[10px] font-black text-muted-text uppercase tracking-widest block pl-1">COMPLIANCE CHECK</span>
                
                <div class="space-y-4">
                    {{-- Row 1 --}}
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="check" class="w-3 h-3"></i>
                        </div>
                        <div>
                            <span class="text-xs font-black text-gray-800 block">No promotional language in header</span>
                            <span class="text-[9px] font-semibold text-gray-400 block mt-0.5">Header meets transactional guidelines.</span>
                        </div>
                    </div>

                    {{-- Row 2 --}}
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0 mt-0.5">
                            <i data-lucide="check" class="w-3 h-3"></i>
                        </div>
                        <div>
                            <span class="text-xs font-black text-gray-800 block">Valid placeholders detected</span>
                            <span class="text-[9px] font-semibold text-gray-400 block mt-0.5">All variables mapped to active library.</span>
                        </div>
                    </div>

                    {{-- Warning alert --}}
                    <div class="bg-red-50 rounded-2xl p-4 flex gap-3 items-start border border-red-100">
                        <div class="p-1 bg-red-100 text-red-600 rounded-lg shrink-0 mt-0.5">
                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                        </div>
                        <div>
                            <span class="text-xs font-black text-red-700 block">Opt-out link recommended</span>
                            <span class="text-[9px] text-red-600 font-semibold leading-normal block mt-0.5">
                                Meta recommends including a 'STOP' keyword for compliance.
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
