@extends('layouts.app')

@section('content')
<div x-data="{ activeTab: new URLSearchParams(window.location.search).get('tab') || 'both' }">
    <!-- FontAwesome CDN for social media icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom styling to match the reference design perfectly -->
    <style>
        .agent-hero {
            position: relative;
            height: 380px;
            background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('{{ !empty($agent->banner) ? asset($agent->banner) : 'https://images.unsplash.com/photo-1548574505-5e239809ee19?auto=format&fit=crop&q=80&w=1600' }}');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .agent-hero-title {
            color: #ffffff;
            font-size: 3.5rem;
            font-weight: 900;
            text-align: center;
            font-family: 'Poppins', sans-serif;
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.6);
            letter-spacing: -1px;
        }

        .agent-profile-section {
            background: #ffffff;
            padding: 2.5rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .agent-logo-container {
            width: 170px;
            height: 170px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid #ebebeb;
            padding: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: -120px;
            /* overlap hero */
            position: relative;
            z-index: 20;
        }

        .agent-logo-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 6px;
        }

        .agent-info-grid {
            display: grid;
            grid-template-cols: 1fr;
            gap: 12px;
            font-family: 'Poppins', sans-serif;
        }

        @media (min-width: 768px) {
            .agent-info-grid {
                grid-template-cols: repeat(2, 1fr);
            }
        }

        .agent-info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #4b5563;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .agent-info-item a {
            color: #4b5563;
            transition: color 0.2s;
        }

        .agent-info-item a:hover {
            color: #f97316;
        }

        .agent-info-icon {
            color: #3b82f6;
            /* premium sky blue matching typical details */
            flex-shrink: 0;
        }

        .agent-social-links {
            display: flex;
            gap: 16px;
            justify-content: flex-start;
        }

        @media (min-width: 1024px) {
            .agent-social-links {
                justify-content: flex-end;
            }
        }

        .agent-social-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .agent-social-btn.fb { color: #1877f2 !important; }
        .agent-social-btn.twitter { color: #1da1f2 !important; }
        .agent-social-btn.linkedin { color: #0077b5 !important; }
        .agent-social-btn.insta { color: #e1306c !important; }

        .agent-social-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            color: #ffffff !important;
        }

        .agent-social-btn.fb:hover {
            background: #1877f2 !important;
        }

        .agent-social-btn.twitter:hover {
            background: #1da1f2 !important;
        }

        .agent-social-btn.linkedin:hover {
            background: #0077b5 !important;
        }

        .agent-social-btn.insta:hover {
            background: #e1306c !important;
        }

        /* Package Cards Grid styling precisely matching picture */
        .showcase-grid {
            display: grid;
            grid-template-cols: 1fr;
            gap: 2rem;
            padding: 4rem 0;
        }

        @media (min-width: 768px) {
            .showcase-grid {
                grid-template-cols: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .showcase-grid {
                grid-template-cols: repeat(3, 1fr);
            }
        }

        .pkg-card {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #f0f0f0;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .pkg-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .pkg-img-wrapper {
            position: relative;
            height: 240px;
            overflow: hidden;
        }

        .pkg-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .pkg-card:hover .pkg-img {
            transform: scale(1.05);
        }

        .pkg-duration-badge {
            position: absolute;
            top: 16px;
            left: 16px;
            background: #ffffff;
            color: #f97316;
            font-size: 0.7rem;
            font-weight: 900;
            padding: 5px 10px;
            border-radius: 6px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-transform: uppercase;
        }

        /* Middle row with agent avatar and view package button */
        .pkg-action-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid #f7f7f7;
        }

        .pkg-agent-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #ffffff;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            background: #ffffff;
        }

        .pkg-agent-avatar img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .pkg-view-btn {
            background: #f97316;
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 800;
            padding: 8px 18px;
            border-radius: 8px;
            transition: background 0.2s;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .pkg-view-btn:hover {
            background: #ea580c;
        }

        .pkg-body {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-family: 'Poppins', sans-serif;
        }

        .pkg-title {
            font-size: 1.15rem;
            font-weight: 900;
            color: #111827;
            line-height: 1.3;
            min-height: 48px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .pkg-category {
            font-size: 0.75rem;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .pkg-location-row {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #4b5563;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .pkg-location-row svg {
            color: #3b82f6;
        }

        .pkg-badges-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: auto;
            padding-top: 12px;
            border-top: 1px solid #f7f7f7;
        }

        .pkg-badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #4b5563;
            padding: 5px 10px;
            border-radius: 6px;
            background: #f3f4f6;
        }

        .pkg-badge-pill svg {
            color: #3b82f6;
        }
    </style>

    <!-- Hero Banner with Snow Mountain Background & Massive Dynamic Agent Title -->
    <div class="agent-hero">
        <div class="container-custom relative z-10 text-center flex flex-col items-center">
            <div class="flex items-center gap-3">
                <h1 class="agent-hero-title">{{ $agent->name }}</h1>
                @if(!empty($agent->service_guaranteed))
                    <i data-lucide="check-circle" class="text-blue-500 w-10 h-10 mt-2"
                        style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));" title="Trusted Agent"></i>
                @endif
            </div>
        </div>

        <!-- Tab Navigation embedded at the bottom of the banner -->
        <div class="absolute bottom-0 left-0 w-full bg-black/40 backdrop-blur-sm border-t border-white/20 z-20">
            <div class="container-custom flex justify-end gap-1 sm:gap-6 overflow-x-auto pb-1 sm:pb-0">
                <button type="button" @click="activeTab = 'profile'; window.history.pushState({}, '', '?agent_id={{ $agent->id }}&tab=profile');"
                    class="py-3 px-3 sm:px-6 border-b-[3px] font-black text-[13px] sm:text-base uppercase tracking-wider transition-colors whitespace-nowrap"
                    :class="activeTab === 'profile' || activeTab === 'both' ? 'border-[#e85d26] text-white bg-black/40' : 'border-transparent text-gray-200 hover:text-white hover:bg-white/10'">
                    <i class="fas fa-user mr-1 sm:mr-2"></i> Profile
                </button>
                <button type="button" @click="activeTab = 'packages'; window.history.pushState({}, '', '?agent_id={{ $agent->id }}&tab=packages');"
                    class="py-3 px-3 sm:px-6 border-b-[3px] font-black text-[13px] sm:text-base uppercase tracking-wider transition-colors whitespace-nowrap"
                    :class="activeTab === 'packages' ? 'border-[#e85d26] text-white bg-black/40' : 'border-transparent text-gray-200 hover:text-white hover:bg-white/10'">
                    <i class="fas fa-box-open mr-1 sm:mr-2"></i> Packages
                </button>
            </div>
        </div>
    </div>

    <!-- Agent Profile details section precisely matched to image layout -->
    <div class="agent-profile-section" x-show="activeTab === 'profile' || activeTab === 'both'" x-cloak>
        <div class="container-custom">
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                <!-- White Logo Card overlapping hero -->
                <div class="agent-logo-container">
                    @php
                        $rawLogo = $agent->logo ?? null;
                        if ($rawLogo && !str_starts_with($rawLogo, 'http') && !str_starts_with($rawLogo, '//')) {
                            $rawLogo = asset(ltrim($rawLogo, '/'));
                        }
                        $agentLogoUrl = $rawLogo ?? 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($agent->name);
                    @endphp
                    <img src="{{ $agentLogoUrl }}" alt="{{ $agent->name }}" class="agent-logo-img">
                </div>

                <!-- Info Grid -->
                <div class="flex-1 space-y-4 w-full">
                    <div class="agent-info-grid w-full">
                        <!-- Phone Numbers (Primary & Secondary beside each other) -->
                        <div
                            class="agent-info-item md:col-span-2 lg:col-span-1 flex flex-wrap gap-x-4 gap-y-1 items-center">
                            <div class="flex items-center gap-3">
                                <svg class="agent-info-icon" width="18" height="18" fill="none" stroke="currentColor"
                                    stroke-width="2.5" viewBox="0 0 24 24">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.21h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.79a16 16 0 0 0 6 6l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16z" />
                                </svg>
                                <a href="tel:{{ $agent->phone }}">{{ $agent->phone }}</a>
                            </div>
                            @if(!empty($agent->secondary_phone))
                                <div class="flex items-center gap-3 border-l border-gray-200 pl-4">
                                    <svg class="agent-info-icon" width="18" height="18" fill="none" stroke="currentColor"
                                        stroke-width="2.5" viewBox="0 0 24 24">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.21h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.79a16 16 0 0 0 6 6l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16z" />
                                    </svg>
                                    <a href="tel:{{ $agent->secondary_phone }}">{{ $agent->secondary_phone }} (Secondary)</a>
                                </div>
                            @endif
                        </div>
                        <!-- Landline -->
                        <div class="agent-info-item">
                            @if(!empty($agent->landline))
                                <svg class="agent-info-icon" width="18" height="18" fill="none" stroke="currentColor"
                                    stroke-width="2.5" viewBox="0 0 24 24">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.21h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.79a16 16 0 0 0 6 6l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16z" />
                                </svg>
                                <a href="tel:{{ $agent->landline }}">{{ $agent->landline }} (Landline)</a>
                            @endif
                        </div>

                        <!-- Email -->
                        <div class="agent-info-item">
                            <svg class="agent-info-icon" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                <polyline points="22,6 12,13 2,6" />
                            </svg>
                            <a href="mailto:{{ $agent->email }}">{{ $agent->email }}</a>
                        </div>

                        <!-- Website -->
                        <div class="agent-info-item">
                            <svg class="agent-info-icon" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2.5" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="2" y1="12" x2="22" y2="12" />
                                <path
                                    d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                            </svg>
                            @if(!empty($agent->website))
                                <a href="{{ $agent->website }}"
                                    target="_blank">{{ preg_replace('(^https?://)', '', $agent->website) }}</a>
                            @else
                                <a href="https://www.{{ strtolower(str_replace(' ', '', $agent->name)) }}.com"
                                    target="_blank">www.{{ strtolower(str_replace(' ', '', $agent->name)) }}.com</a>
                            @endif
                        </div>

                        <!-- Reviews -->
                        <div class="agent-info-item">
                            <svg class="agent-info-icon text-yellow-500" width="18" height="18" fill="currentColor"
                                viewBox="0 0 24 24">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                            </svg>
                            <span>Reviews <span class="font-black ml-1 text-gray-800">4.9</span></span>
                        </div>

                        <!-- Empty slot to balance grid -->
                        <div class="hidden lg:block"></div>

                        <!-- Address -->
                        <div class="agent-info-item lg:col-span-2">
                            <svg class="agent-info-icon text-red-500" width="18" height="18" fill="none"
                                stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            <span>
                                @php
                                    $fullAddr = $agent->address ?? $agent->region ?? '';
                                    $cityState = [];
                                    if (!empty($agent->city))
                                        $cityState[] = $agent->city;
                                    if (!empty($agent->state))
                                        $cityState[] = $agent->state;
                                    if (!empty($agent->country))
                                        $cityState[] = $agent->country;
                                    if (!empty($cityState)) {
                                        $fullAddr .= ' - ' . implode(', ', $cityState);
                                    }
                                @endphp
                                {{ $fullAddr }}
                            </span>
                        </div>

                        <!-- Inline Branches Locations -->
                        @if(isset($branches) && count($branches) > 0)
                            <div class="agent-info-item lg:col-span-2 mt-1 flex flex-wrap gap-2 items-center">
                                <svg class="agent-info-icon text-orange-500" width="18" height="18" fill="none"
                                    stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                                <span class="font-bold text-gray-700 text-[13px] mr-1">Branches:</span>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($branches as $b)
                                        <span class="px-2.5 py-1 bg-orange-100 text-orange-800 rounded-lg text-[10px] font-bold"
                                            title="{{ $b->address }} ({{ $b->phone }})">
                                            {{ $b->location }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <!-- Far-Right Social Media Buttons precisely matching image -->
                <div
                    class="agent-social-links shrink-0 w-full lg:w-auto pt-4 lg:pt-0 border-t lg:border-none border-gray-100 flex gap-2.5 items-center">
                    @if(!empty($agent->facebook))
                        <a href="{{ $agent->facebook }}" target="_blank" class="agent-social-btn fb" title="Facebook">
                            <i class="fab fa-facebook-f text-sm"></i>
                        </a>
                    @endif
                    @if(!empty($agent->twitter))
                        <a href="{{ $agent->twitter }}" target="_blank" class="agent-social-btn twitter" title="Twitter">
                            <i class="fab fa-twitter text-sm"></i>
                        </a>
                    @endif
                    @if(!empty($agent->linkedin))
                        <a href="{{ $agent->linkedin }}" target="_blank" class="agent-social-btn linkedin" title="LinkedIn">
                            <i class="fab fa-linkedin-in text-sm"></i>
                        </a>
                    @endif
                    @if(!empty($agent->instagram))
                        <a href="{{ $agent->instagram }}" target="_blank" class="agent-social-btn insta" title="Instagram">
                            <i class="fab fa-instagram text-sm"></i>
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- Branches -->
            @if(isset($branches) && count($branches) > 0)
                <div class="mt-8 pt-6 pb-6 border-t border-b border-gray-100 flex flex-col items-start gap-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                        @foreach($branches as $index => $b)
                            @php
                                $bgColor = ($index % 2 == 0) ? 'bg-blue-50' : 'bg-orange-50';
                            @endphp
                            <div class="p-6 rounded-lg {{ $bgColor }} flex flex-col w-full">
                                <span class="text-xl font-bold text-gray-900 mb-4">{{ $b->agency_name }} - {{ $b->location }}</span>
                                
                                <div class="text-base text-gray-800 font-medium flex items-center gap-3 mb-3">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                    <span>{{ $b->phone }}</span>
                                </div>
                                
                                <div class="text-base text-gray-800 font-medium flex items-start gap-3">
                                    <svg class="mt-[2px] shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                    <span>{{ $b->address }}{{ !empty($b->state) ? ', ' . $b->state : '' }}{{ !empty($b->country) ? ', ' . $b->country : '' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Profile Images -->
            @if(isset($profile_images) && count($profile_images) > 0)
                <div class="mt-4 pt-6 pb-6 border-b border-gray-100 flex flex-col items-start gap-4">
                    <h3 class="text-3xl font-extrabold text-[#1f2937] mb-6">Gallery</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 w-full">
                        @foreach($profile_images as $img)
                            <div class="rounded-xl overflow-hidden aspect-square border border-gray-100 shadow-sm transition-transform hover:scale-105 duration-300">
                                <img src="{{ asset($img->image_path) }}" alt="Gallery Image" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Customer Feedback Section (Below Gallery) -->
            @if(isset($feedbacks) && $feedbacks->count() > 0)
                <div class="mt-8 border-b border-gray-100 pb-6"
                     x-data="{ 
                        intervalId: null,
                        startScroll() {
                            this.intervalId = setInterval(() => {
                                let container = this.$refs.feedbackCarousel;
                                if (!container) return;
                                let maxScroll = container.scrollWidth - container.clientWidth;
                                if (container.scrollLeft >= maxScroll - 10) {
                                    container.scrollTo({ left: 0, behavior: 'smooth' });
                                } else {
                                    // Try to get width of first child, fallback to 300
                                    let cardWidth = container.children.length > 0 ? container.children[0].offsetWidth : 300;
                                    container.scrollBy({ left: cardWidth + 24, behavior: 'smooth' });
                                }
                            }, 3000);
                        },
                        stopScroll() {
                            if (this.intervalId) clearInterval(this.intervalId);
                        }
                     }"
                     x-init="startScroll()"
                     @mouseenter="stopScroll()"
                     @mouseleave="startScroll()">
                     
                    <h3 class="text-3xl font-extrabold text-[#1f2937] mb-6">Customer Feedback</h3>
                    
                    <div x-ref="feedbackCarousel" class="flex overflow-x-auto gap-6 snap-x snap-mandatory pb-4 hide-scrollbar" style="scrollbar-width: none;">
                        @foreach($feedbacks as $feedback)
                            <div x-data="{ showModal: false }" 
                                 class="flex-shrink-0 bg-white border border-gray-200 rounded-xl shadow-sm snap-start flex flex-col hover:shadow-md transition-shadow overflow-hidden"
                                 style="height: 420px; width: 320px; min-width: 320px; max-width: 320px;">
                                 
                                @if($feedback->image_path)
                                    <div class="w-full h-44 bg-gray-100 shrink-0 border-b border-gray-100">
                                        <img src="{{ asset($feedback->image_path) }}" alt="Customer Photo" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-full h-44 shrink-0 border-b border-gray-100">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($feedback->customer_name) }}&size=400&font-size=0.4" alt="Customer Initials" class="w-full h-full object-cover">
                                    </div>
                                @endif
                                
                                <div class="p-5 flex flex-col flex-grow">
                                    <div class="flex text-yellow-400 text-[11px] mb-3">
                                        @for($i=0; $i<$feedback->rating; $i++) <i class="fas fa-star"></i> @endfor
                                        @for($i=$feedback->rating; $i<5; $i++) <i class="text-gray-200 fas fa-star"></i> @endfor
                                    </div>

                                    <div class="relative mb-4 flex-grow flex flex-col overflow-hidden">
                                        @php
                                            $text = $feedback->message;
                                        @endphp
                                        <div x-data="{ isOverflowing: false }"
                                             x-init="setTimeout(() => { isOverflowing = $refs.msgText.scrollHeight > $refs.msgText.clientHeight }, 100)"
                                             class="relative w-full">
                                             
                                            <div x-ref="msgText" 
                                                 class="overflow-hidden text-[#4b5563] text-[14px] leading-relaxed break-words whitespace-pre-wrap" 
                                                 style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; max-height: 69px;">{{ $text }}</div>

                                            <button x-show="isOverflowing" x-cloak 
                                                    @click="showModal = true" 
                                                    class="absolute bottom-0 right-0 text-blue-500 font-semibold text-[14px] hover:underline pl-8 pr-1 text-right focus:outline-none"
                                                    style="background: linear-gradient(90deg, transparent 0%, white 35%, white 100%); line-height: 1.625;">
                                                ...more
                                            </button>
                                        </div>

                                        <!-- Full Review Modal -->
                                        <div x-show="showModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
                                            <div @click.away="showModal = false" class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col relative" style="animation: modalPop 0.3s ease-out;">
                                                <!-- Header -->
                                                <div class="flex items-center justify-between p-5 border-b border-gray-100">
                                                    <h2 class="text-xl font-bold text-gray-900">{{ $feedback->customer_name }}</h2>
                                                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                                                        <i class="fas fa-times text-xl"></i>
                                                    </button>
                                                </div>
                                                <!-- Body -->
                                                <div class="p-6 overflow-y-auto text-gray-700 leading-relaxed whitespace-pre-wrap text-[15px]">
                                                    {{ $text }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-100 my-2"></div>
                                    
                                    <div class="mt-auto pt-2 flex items-center gap-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($feedback->customer_name) }}&color=fff" class="w-10 h-10 rounded-full object-cover border border-gray-100 shrink-0">
                                        <div>
                                            <h4 class="font-extrabold text-[#111827] text-[15px] capitalize">{{ $feedback->customer_name }}</h4>
                                            <span class="text-[12px] text-gray-500 block">{{ $feedback->created_at->format('F Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <style>
                    .hide-scrollbar::-webkit-scrollbar { display: none; }
                    @keyframes modalPop {
                        0% { opacity: 0; transform: scale(0.95); }
                        100% { opacity: 1; transform: scale(1); }
                    }
                </style>
            @endif

            <!-- Why Us Section (Below Gallery) -->
            @if(!empty($agent->why_us))
                <div x-data="{ expanded: true }" class="mt-8 border border-gray-200 rounded-xl bg-white w-full shadow-sm">
                    <div @click="expanded = !expanded" class="flex justify-between items-center px-6 py-5 cursor-pointer select-none">
                        <h3 class="text-lg font-extrabold text-[#111827]">Why Us</h3>
                        <svg class="w-5 h-5 text-[#4b5563] transition-transform duration-300" :class="expanded ? '' : 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                    </div>
                    <div x-show="expanded" x-collapse.duration.300ms class="border-t border-gray-100"></div>
                    <div x-show="expanded" x-collapse.duration.300ms>
                        <div class="px-6 py-6 text-[#4b5563] text-[14.5px] leading-relaxed whitespace-pre-wrap">{{ $agent->why_us }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Featured Tour Packages Showcase Grid -->
    <div class="bg-[#FAFAFA] py-12" x-show="activeTab === 'packages' || activeTab === 'both'" x-cloak>
        <div class="container-custom">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php $adIndex = 0; @endphp
                @forelse($packages as $index => $pkg)
                    <x-package-card :pkg="$pkg" :hideAgent="true" />

                    {{-- 6-3-3 Pattern (6, 3, 3, 6, 3, 3...) for Ads --}}
                    @php
                        $showAd = false;
                        $localIndex = $index % 12;
                        if ($localIndex == 5 || $localIndex == 8 || $localIndex == 11) {
                            $showAd = true;
                        }
                    @endphp

                    @if($showAd && isset($sidebarAds) && $sidebarAds->count() > $adIndex)
                        @php
                            $inlineAd = $sidebarAds[$adIndex];
                            $adIndex++;
                        @endphp
                        @if(!empty($inlineAd->image))
                            <div class="col-span-full w-full rounded-2xl overflow-hidden shadow-sm relative group transition-all"
                                style="display: flex; justify-content: center; align-items: center; background: transparent;">
                                <a href="{{ route('ad.click', $inlineAd->id) }}" target="_blank" class="block w-full relative">
                                    <img src="{{ asset($inlineAd->image) }}" alt="{{ $inlineAd->campaign_name }}"
                                        class="w-full h-auto object-contain rounded-2xl">
                                    <span
                                        class="absolute top-2 left-2 bg-black/60 text-white font-extrabold uppercase text-[10px] tracking-widest px-2 py-1 rounded-md backdrop-blur-xs">AD</span>
                                </a>
                            </div>
                        @endif
                    @endif

                @empty
                    <div class="col-span-full py-16 text-center">
                        <div
                            class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                            <i data-lucide="package-x" size="28"></i>
                        </div>
                        <h4 class="text-lg font-black text-gray-800">No Packages Found</h4>
                        <p class="text-sm text-gray-500 mt-1">This agent hasn't published any packages yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection