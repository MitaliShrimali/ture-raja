@extends('layouts.app')

@section('content')
    <!-- Custom styling to match the reference design perfectly -->
    <style>
        .agent-hero {
            position: relative;
            height: 380px;
            background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('https://images.unsplash.com/photo-1548574505-5e239809ee19?auto=format&fit=crop&q=80&w=1600');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 80px; /* offset navbar */
        }
        
        .agent-hero-title {
            color: #ffffff;
            font-size: 3.5rem;
            font-weight: 900;
            text-align: center;
            font-family: 'Outfit', sans-serif;
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
            margin-top: -120px; /* overlap hero */
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
            font-family: 'Outfit', sans-serif;
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
            color: #3b82f6; /* premium sky blue matching typical details */
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
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
            color: #4b5563;
            transition: all 0.2s;
        }

        .agent-social-btn:hover {
            background: #3b82f6;
            color: #ffffff;
            transform: translateY(-2px);
        }

        .agent-social-btn.insta:hover {
            background: #e1306c;
        }

        .agent-social-btn.fb:hover {
            background: #1877f2;
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
            font-family: 'Outfit', sans-serif;
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
        <div class="container-custom relative z-10 text-center">
            <h1 class="agent-hero-title">{{ $agent->name }}</h1>
        </div>
    </div>

    <!-- Agent Profile details section precisely matched to image layout -->
    <div class="agent-profile-section">
        <div class="container-custom">
            <div class="flex flex-col lg:flex-row gap-8 items-start lg:items-center">
                <!-- White Logo Card overlapping hero -->
                <div class="agent-logo-container">
                    @php
                        $agentLogoSeed = urlencode($agent->name);
                        $agentLogoUrl = 'https://api.dicebear.com/7.x/initials/svg?seed=' . $agentLogoSeed;
                        
                        // Special matching logo for Miths Holidays
                        if (strtolower($agent->name) === 'miths holidays') {
                            $agentLogoUrl = 'https://api.dicebear.com/7.x/initials/svg?seed=MH';
                        }
                    @endphp
                    <img src="{{ $agentLogoUrl }}" alt="{{ $agent->name }}" class="agent-logo-img">
                </div>

                <!-- Info Grid -->
                <div class="flex-1 space-y-4 w-full">
                    <div class="agent-info-grid w-full">
                        <!-- Phone -->
                        <div class="agent-info-item">
                            <svg class="agent-info-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.4 2 2 0 0 1 3.6 1.21h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.79a16 16 0 0 0 6 6l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21.73 16z"/></svg>
                            <a href="tel:{{ $agent->phone }}">{{ $agent->phone }}</a>
                        </div>
                        <!-- Social links far-right (on larger screens) -->
                        <div class="hidden lg:block"></div>

                        <!-- Email -->
                        <div class="agent-info-item">
                            <svg class="agent-info-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <a href="mailto:{{ $agent->email }}">{{ $agent->email }}</a>
                        </div>
                        <!-- Empty slot to balance grid -->
                        <div class="hidden lg:block"></div>

                        <!-- Website -->
                        <div class="agent-info-item">
                            <svg class="agent-info-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                            <a href="https://www.{{ strtolower(str_replace(' ', '', $agent->name)) }}.com" target="_blank">www.{{ strtolower(str_replace(' ', '', $agent->name)) }}.com</a>
                        </div>
                        <!-- Empty slot to balance grid -->
                        <div class="hidden lg:block"></div>

                        <!-- Google Reviews -->
                        <div class="agent-info-item">
                            <svg class="agent-info-icon text-yellow-500" width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <span>Google Reviews <span class="font-black ml-1 text-gray-800">4.5</span></span>
                        </div>
                        <!-- Empty slot to balance grid -->
                        <div class="hidden lg:block"></div>

                        <!-- Address -->
                        <div class="agent-info-item lg:col-span-2">
                            <svg class="agent-info-icon text-red-500" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span>{{ $agent->region }}</span>
                        </div>
                    </div>
                </div>

                <!-- Far-Right Social Media Buttons precisely matching image -->
                <div class="agent-social-links shrink-0 w-full lg:w-auto pt-4 lg:pt-0 border-t lg:border-none border-gray-100">
                    <a href="#" class="agent-social-btn insta" title="Instagram">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                    <a href="#" class="agent-social-btn fb" title="Facebook">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Tour Packages Showcase Grid -->
    <div class="bg-[#FAFAFA]">
        <div class="container-custom">
            <div class="showcase-grid">
                @forelse($packages as $pkg)
                    @php
                        $p = (array) $pkg;
                        $pkgId = $p['id'] ?? 1;
                        $slug = $p['slug'] ?? 'package-' . $pkgId;
                        
                        // Parse duration dynamically to format like 4N/5D
                        $durationText = strtolower($p['duration'] ?? '');
                        $durBadge = '3N/4D';
                        if (preg_match('/(\d+)\s*n/', $durationText, $mN) && preg_match('/(\d+)\s*d/', $durationText, $mD)) {
                            $durBadge = $mN[1] . 'N/' . $mD[1] . 'D';
                        } elseif (preg_match('/(\d+)\s*d/', $durationText, $mD)) {
                            $days = intval($mD[1]);
                            $nights = max(1, $days - 1);
                            $durBadge = $nights . 'N/' . $days . 'D';
                        }
                        
                        // Categories and locations
                        $category = $p['category'] ?? 'Tour';
                        $location = $p['location'] ?? 'India';
                        
                        // Determine flight/bus/land badge dynamically
                        $isInternational = in_array(strtolower($category), ['international', 'tropical', 'city']) || str_contains(strtolower($location), 'bali') || str_contains(strtolower($location), 'paris') || str_contains(strtolower($location), 'dubai') || str_contains(strtolower($location), 'monaco');
                        
                        $isFlight = $isInternational || str_contains(strtolower($p['title']), 'flight') || str_contains(strtolower($p['title']), 'swiss') || str_contains(strtolower($p['title']), 'dubai') || str_contains(strtolower($p['title']), 'vietnam') || str_contains(strtolower($p['title']), 'monaco');
                        $isBus = str_contains(strtolower($p['title']), 'somnath') || str_contains(strtolower($p['title']), 'yatra') || str_contains(strtolower($p['title']), 'shirdi');
                        
                        $transportBadge = 'Land Package';
                        $transportIcon = '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>';
                        if ($isFlight) {
                            $transportBadge = 'Flight Package';
                            $transportIcon = '<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>';
                        } elseif ($isBus) {
                            $transportBadge = 'Bus Package';
                            $transportIcon = '<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M4 18h2v2H4z"/><path d="M18 18h2v2h-2z"/><path d="M12 4v12"/>';
                        }
                    @endphp

                    <div class="pkg-card">
                        <!-- Top Image and Duration Tag -->
                        <div class="pkg-img-wrapper">
                            <img src="{{ $p['image'] ?? 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=800' }}" alt="{{ $p['title'] }}" class="pkg-img">
                            <span class="pkg-duration-badge">{{ $durBadge }}</span>
                        </div>

                        <!-- Action Row (Agent Logo and View Package Button) -->
                        <div class="pkg-action-row">
                            <div class="pkg-agent-avatar">
                                <img src="{{ $agentLogoUrl }}" alt="{{ $agent->name }}">
                            </div>
                            <a href="{{ url('/packages/' . $slug) }}" class="pkg-view-btn">View Package</a>
                        </div>

                        <!-- Card Body Details -->
                        <div class="pkg-body">
                            <p class="pkg-category">{{ $category }}</p>
                            <h3 class="pkg-title">
                                @if(str_contains(strtolower($p['title']), 'somnath') || str_contains(strtolower($p['title']), 'gir'))
                                    ▲ {{ $p['title'] }}
                                @else
                                    {{ $p['title'] }}
                                @endif
                            </h3>
                            
                            <div class="pkg-location-row">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <span>{{ $location }} @if(str_contains(strtolower($location), 'dwarka')) (2N) @endif</span>
                            </div>

                            <!-- Bottom Badge Pills matching image -->
                            <div class="pkg-badges-row">
                                <span class="pkg-badge-pill">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                    {{ $isInternational ? 'International' : 'Domestic' }}
                                </span>
                                <span class="pkg-badge-pill">
                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">{!! $transportIcon !!}</svg>
                                    {{ $transportBadge }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                            <i data-lucide="package-x" size="28"></i>
                        </div>
                        <h4 class="text-lg font-black text-gray-800">No Packages Found</h4>
                        <p class="text-sm text-gray-500 mt-1">This agent hasn't published any packages yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
