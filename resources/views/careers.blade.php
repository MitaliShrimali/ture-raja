@extends('layouts.app')

@section('content')
    <style>
        .wave-bg {
            background: linear-gradient(135deg, #FF6B35 0%, #E8460A 100%);
            position: relative;
        }

        .wave-bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .wave-bottom svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 80px;
        }

        .wave-bottom .shape-fill {
            fill: #FFFFFF;
        }

        .wave-top {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            transform: rotate(180deg);
        }

        .wave-top svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 60px;
        }

        .wave-top .shape-fill {
            fill: #FFFFFF;
        }

        .progress-bar {
            height: 8px;
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
            overflow: hidden;
            margin-top: 8px;
        }

        .progress-fill {
            height: 100%;
            background-color: #FFFFFF;
            border-radius: 4px;
        }

        /* Form Styles */
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 14px;
            color: #111827;
            transition: all 0.3s;
            background-color: #F9FAFB;
        }

        .form-input:focus {
            outline: none;
            border-color: #E8460A;
            ring: 2px;
            ring-color: rgba(232, 70, 10, 0.2);
            background-color: #FFFFFF;
        }

        /* Careers Custom Grid and Orange Theme Styles */
        .careers-grid-container {
            display: grid !important;
            grid-template-columns: 300px 1fr !important;
            gap: 40px !important;
            width: 100% !important;
            align-items: start !important;
        }
        @media (max-width: 1024px) {
            .careers-grid-container {
                grid-template-columns: 1fr !important;
                gap: 24px !important;
            }
        }
        .career-job-card {
            background: #ffffff !important;
            border-radius: 16px !important;
            border: 1px solid #f1f5f9 !important;
            padding: 24px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.01) !important;
            transition: all 0.3s ease !important;
            cursor: pointer !important;
        }
        .career-job-card:hover {
            border-color: #E8460A !important;
            box-shadow: 0 10px 30px rgba(232, 70, 10, 0.08) !important;
            transform: translateY(-2px) !important;
        }
        .career-job-card-active {
            border-color: #E8460A !important;
            border-width: 2px !important;
            box-shadow: 0 10px 30px rgba(232, 70, 10, 0.08) !important;
        }
        .career-arrow-btn {
            width: 36px !important;
            height: 36px !important;
            border-radius: 50% !important;
            border: 1px solid #e2e8f0 !important;
            color: #94a3b8 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.3s ease !important;
            flex-shrink: 0 !important;
        }
        .career-job-card:hover .career-arrow-btn {
            background-color: #E8460A !important;
            border-color: #E8460A !important;
            color: #ffffff !important;
        }
        .dept-badge {
            background-color: #fff7f5 !important;
            color: #E8460A !important;
            border: 1px solid #ffd8cc !important;
            font-size: 10px !important;
            font-weight: 800 !important;
            padding: 2px 10px !important;
            border-radius: 9999px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
        }
    </style>

    <!-- Hero Section -->
    <div class="relative w-full flex items-center justify-center wave-bg" style="min-height: 400px; padding-top: 16px;">
        <div class="relative z-10 text-center w-full max-w-7xl px-6 mb-12">
            <h1 style="font-size: 38px;" class="font-extrabold text-white mb-4 tracking-tight">Careers</h1>
            <div class="text-white/80 font-medium text-sm flex items-center justify-center gap-2">
                <a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a>
                <span>></span>
                <a href="{{ url('/about') }}" class="hover:text-white transition-colors">About</a>
                <span>></span>
                <span class="text-white">Careers</span>
            </div>
        </div>

        <!-- Bottom Wave -->
        <div class="wave-bottom">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C52.16,93.87,100.68,78.27,150.3,65.65,206.83,51.27,264.4,66.86,321.39,56.44Z"
                    class="shape-fill"></path>
            </svg>
        </div>
    </div>

    <!-- Our Team Intro Section -->
    <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="flex flex-col md:flex-row gap-12 items-center">
            <div class="w-full md:w-1/2">
                <h2 style="font-size: 40px;" class="font-black text-gray-900 mb-6 tracking-tight">Our Team</h2>
                <p class="text-gray-600 font-medium leading-relaxed text-sm lg:text-base">
                    At Tourraja, we specialize in offering travel agents a user-friendly space to showcase their brochures,
                    upload their latest travel offerings, and manage essential contact details. Our platform is designed to
                    make it easier for both travel agents and customers to find what they need, when they need it.
                </p>
            </div>
            <div class="w-full md:w-1/2 flex flex-col gap-4">
                <div class="bg-orange-50 border border-orange-100 p-6 rounded-2xl shadow-sm">
                    <h4 class="text-[#E8460A] font-black text-sm tracking-widest mb-2 uppercase">Vision</h4>
                    <p class="text-gray-700 font-medium italic">“To inspire exploration by making premium travel accessible,
                        seamless, and deeply personal.”</p>
                </div>
                <div class="bg-orange-50 border border-orange-100 p-6 rounded-2xl shadow-sm">
                    <h4 class="text-[#E8460A] font-black text-sm tracking-widest mb-2 uppercase">Mission</h4>
                    <p class="text-gray-700 font-medium italic">“We craft extraordinary travel experiences by blending
                        innovation, local expertise, and personalized service.”</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Our Team Skills Section -->
    <div class="relative w-full wave-bg py-20 lg:py-28 mt-8">
        <!-- Top Wave -->
        <div class="wave-top">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C52.16,93.87,100.68,78.27,150.3,65.65,206.83,51.27,264.4,66.86,321.39,56.44Z"
                    class="shape-fill"></path>
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10 flex flex-col md:flex-row items-center gap-12 lg:gap-20">
            <!-- Left: Illustration -->
            <div class="w-full md:w-1/2 flex justify-center">
                <!-- Using an unsplash image to represent teamwork/skills since we don't have the exact illustration -->
                <div class="bg-white/10 p-4 rounded-[32px] backdrop-blur-sm border border-white/20">
                    <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&q=80&w=800"
                        alt="Team Collaboration" class="rounded-[24px] shadow-2xl object-cover w-full h-[300px]">
                </div>
            </div>

            <!-- Right: Skills Content -->
            <div class="w-full md:w-1/2 space-y-8">
                <div>
                    <h2 style="font-size: 40px;" class="font-black text-white mb-4 tracking-tight">Our Team Skills</h2>
                    <p class="text-white/80 font-medium leading-relaxed text-sm lg:text-base">
                        Tour Raja makes it easy to find reliable travel agents offering packages for destinations across
                        India and around the world. Browse, compare, and contact agents directly—all in one place.
                    </p>
                </div>

                <div class="space-y-6">
                    <!-- Skill 1 -->
                    <div>
                        <div class="flex justify-between items-end mb-1">
                            <span class="text-white font-bold text-sm tracking-wide">Customer Service Excellence</span>
                            <span class="text-white font-black text-xs bg-white/20 px-2 py-0.5 rounded-md">98%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 98%;"></div>
                        </div>
                    </div>

                    <!-- Skill 2 -->
                    <div>
                        <div class="flex justify-between items-end mb-1">
                            <span class="text-white font-bold text-sm tracking-wide">Sales & Global Reach</span>
                            <span class="text-white font-black text-xs bg-white/20 px-2 py-0.5 rounded-md">95%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 95%;"></div>
                        </div>
                    </div>

                    <!-- Skill 3 -->
                    <div>
                        <div class="flex justify-between items-end mb-1">
                            <span class="text-white font-bold text-sm tracking-wide">Tour Planning & Management</span>
                            <span class="text-white font-black text-xs bg-white/20 px-2 py-0.5 rounded-md">92%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 92%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>
    </div>

    <!-- Open Positions Section -->
    <div class="max-w-7xl mx-auto px-6 py-16 border-b border-gray-100">
        <div class="text-center mb-12">
            <h2 style="font-size: 36px;" class="font-black text-gray-900 mb-4 tracking-tight">Open Positions</h2>
            <p class="text-gray-500 font-medium text-sm">Explore our current opportunities and find the right role for you.</p>
        </div>

        <div class="careers-grid-container">
            <!-- Left: Sidebar Filters -->
            <div style="width: 100%;">
                <div class="bg-white rounded-3xl border border-gray-100 p-6 space-y-6 shadow-[0_4px_20px_rgba(0,0,0,0.02)] sticky top-24">
                    <h4 class="text-sm font-black text-gray-900 uppercase tracking-wider pb-3 border-b border-gray-100 flex items-center justify-between">
                        <span>Filters</span>
                        <button id="btn-clear-filters" class="text-[10px] text-[#E8460A] font-black uppercase hover:underline hidden">Clear All</button>
                    </h4>

                    <!-- Search Input -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-600">Search</label>
                        <div class="relative">
                            <input type="text" id="filter-search" placeholder="Search positions..." class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#E8460A] transition-all">
                            <i data-lucide="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        </div>
                    </div>

                    <!-- Department Filter -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-600">Department</label>
                        <select id="filter-department" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#E8460A] transition-all text-gray-600">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Location Filter -->
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-gray-600">Location</label>
                        <select id="filter-location" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#E8460A] transition-all text-gray-600">
                            <option value="">All Locations</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->name }}">{{ $loc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Right: Dynamic Positions List -->
            <div style="width: 100%;" class="space-y-10" id="positions-list-container">
                <!-- Dynamically filled by JS -->
            </div>
        </div>
    </div>

    <!-- Join Our Team Section -->
    <div class="max-w-7xl mx-auto px-6 py-16 lg:py-24" id="application-form-section">
        @if(!$careerFormEnabled)
            <div class="text-center py-12 bg-gray-50 rounded-[32px] border border-gray-100 p-8 space-y-4">
                <div class="w-16 h-16 bg-orange-50 text-[#E8460A] rounded-full flex items-center justify-center mx-auto shadow-sm">
                    <i data-lucide="info" class="w-8 h-8"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900">Applications Closed</h3>
                <p class="text-gray-500 font-medium text-sm max-w-md mx-auto">We are currently not accepting new applications. Please check back later or check our open roles list above.</p>
            </div>
        @else
        <div class="flex flex-col lg:flex-row gap-16 lg:gap-12">

            <!-- Left: Info -->
            <div class="w-full lg:w-[35%] space-y-6 lg:sticky lg:top-32 self-start">
                <h2 style="font-size: 40px;" class="font-black text-gray-900 leading-[1.1] tracking-tight">Join Our Team
                </h2>
                <p class="text-gray-500 font-medium text-sm leading-relaxed">
                    Ready to embark on a new adventure? Tour Raja is always looking for passionate individuals who love
                    travel as much as we do. Fill out the application form to apply for open positions.
                </p>
                <div class="hidden lg:block pt-8">
                    <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&q=80&w=600"
                        alt="Join Us" class="rounded-[24px] shadow-md object-cover h-[250px] w-full">
                </div>
            </div>

            <!-- Right: Application Form -->
            <div class="w-full lg:w-[65%]">
                <div class="bg-white rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 p-8 md:p-10" id="application-form-card">
                    <h3 class="text-2xl font-black text-gray-900 mb-8 border-b border-gray-100 pb-4">{{ $careerFormTitle }}</h3>

                    @if(session('success'))
                        <div
                            class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-bold flex items-center gap-3">
                            <i data-lucide="check-circle" size="20" class="shrink-0"></i>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(session('error'))
                        <div
                            class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-bold flex items-center gap-3">
                            <i data-lucide="alert-circle" size="20" class="shrink-0"></i>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('career.submit') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6" onsubmit="
                            const btn = this.querySelector('button[type=submit]');
                            if(btn.dataset.submitting) return false;
                            btn.dataset.submitting = 'true';
                            btn.classList.add('opacity-90', 'cursor-not-allowed');
                            btn.innerHTML = '<svg class=\'animate-spin inline-block mr-2 h-4 w-4 text-white\' xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\'><circle class=\'opacity-25\' cx=\'12\' cy=\'12\' r=\'10\' stroke=\'currentColor\' stroke-width=\'4\'></circle><path class=\'opacity-75\' fill=\'currentColor\' d=\'M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z\'></path></svg> Submitting...';
                        ">
                        @csrf

                        <!-- Role & Resume -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Applying to which role <span class="text-red-500">*</span></label>
                                <select name="role" id="application-role-select" required class="form-input">
                                    <option value="" disabled selected>Select a role</option>
                                    @foreach($positions as $pos)
                                        <option value="{{ $pos->title }}">{{ $pos->title }}</option>
                                    @endforeach
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Upload Resume <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="file" id="resume_upload" name="resume" required accept=".pdf,.doc,.docx"
                                        class="form-input p-2.5 bg-white cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-[#E8460A]/10 file:text-[#E8460A] hover:file:bg-[#E8460A]/20 transition-all">
                                </div>
                                <p class="text-[11px] text-gray-400 mt-1 font-medium">Please upload resume (File size should
                                    be <= 5 MB)</p>

                                        <!-- File Preview Container -->
                                        <div id="resume_preview_container"
                                            class="hidden mt-3 p-3 bg-orange-50 border border-orange-100 rounded-xl items-center justify-between">
                                            <div class="flex items-center gap-2 overflow-hidden">
                                                <i data-lucide="file-text" size="16" class="text-[#E8460A] shrink-0"></i>
                                                <a href="#" id="resume_preview_link" target="_blank"
                                                    class="text-sm font-bold text-gray-700 hover:text-[#E8460A] truncate max-w-[200px] sm:max-w-[300px]"></a>
                                            </div>
                                            <button type="button"
                                                onclick="document.getElementById('resume_upload').value=''; document.getElementById('resume_preview_container').classList.add('hidden'); document.getElementById('resume_preview_container').classList.remove('flex');"
                                                class="text-gray-400 hover:text-red-500 transition-colors shrink-0 p-1">
                                                <i data-lucide="x" size="16"></i>
                                            </button>
                                        </div>
                            </div>
                        </div>

                        <!-- Name Details -->
                        <div class="grid grid-cols-1 {{ in_array('middle_name', $careerFormFields) ? 'md:grid-cols-3' : 'md:grid-cols-2' }} gap-6 items-end">
                            <div>
                                <label class="form-label">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" required placeholder="First Name" class="form-input">
                            </div>
                            @if(in_array('middle_name', $careerFormFields))
                            <div>
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" placeholder="Middle Name" class="form-input">
                            </div>
                            @endif
                            <div>
                                <label class="form-label">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" required placeholder="Last Name" class="form-input">
                            </div>
                        </div>

                        <!-- Contact Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" required placeholder="Email Address" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">Phone Number <span class="text-red-500">*</span></label>
                                <div class="flex gap-2 items-center">
                                    @if(in_array('phone', $careerFormFields))
                                    <div class="relative w-28 shrink-0">
                                        <select
                                            class="phone-country-code w-full px-3 py-3 rounded-md border border-gray-200 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400">
                                            <option value="+91" data-len="10" selected>🇮🇳 +91</option>
                                            <option value="+1" data-len="10">🇺🇸 +1</option>
                                            <option value="+44" data-len="10">🇬🇧 +44</option>
                                            <option value="+62" data-len="11">🇮🇩 +62</option>
                                            <option value="+65" data-len="8">🇸🇬 +65</option>
                                            <option value="+971" data-len="9">🇦🇪 +971</option>
                                            <option value="+61" data-len="9">🇦🇺 +61</option>
                                            <option value="+66" data-len="9">🇹🇭 +66</option>
                                            <option value="+60" data-len="10">🇲🇾 +60</option>
                                        </select>
                                    </div>
                                    @endif
                                    <div class="relative flex-grow">
                                        <input type="tel" required placeholder="Phone *"
                                            class="phone-number-val w-full px-4 py-3 rounded-md border border-gray-200 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-orange-300 focus:border-orange-400 placeholder-gray-400">
                                    </div>
                                </div>
                                <input type="hidden" class="phone-full-val" name="phone">
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Current Location <span class="text-red-500">*</span></label>
                                <select name="location" required class="form-input">
                                    <option value="" disabled selected>Select location</option>
                                    <option value="Delhi">Delhi</option>
                                    <option value="Mumbai">Mumbai</option>
                                    <option value="Bangalore">Bangalore</option>
                                    <option value="Pune">Pune</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Current Location (if Other)</label>
                                <input type="text" name="location_other" placeholder="Specify if Other" class="form-input">
                            </div>
                        </div>

                        <!-- Notice Period & Gender -->
                        @if(in_array('notice_period', $careerFormFields) || in_array('gender', $careerFormFields))
                        <div class="grid grid-cols-1 {{ (in_array('notice_period', $careerFormFields) && in_array('gender', $careerFormFields)) ? 'md:grid-cols-2' : 'md:grid-cols-1' }} gap-6 items-end">
                            @if(in_array('notice_period', $careerFormFields))
                            <div>
                                <label class="form-label">Notice Period <span class="text-red-500">*</span></label>
                                <select name="notice_period" required class="form-input">
                                    <option value="" disabled selected>Select notice period</option>
                                    <option value="Immediate">Immediate</option>
                                    <option value="15 Days">15 Days</option>
                                    <option value="1 Month">1 Month</option>
                                    <option value="2 Months">2 Months</option>
                                    <option value="3+ Months">3+ Months</option>
                                </select>
                            </div>
                            @endif
                            @if(in_array('gender', $careerFormFields))
                            <div>
                                <label class="form-label">Gender <span class="text-red-500">*</span></label>
                                <select name="gender" required class="form-input">
                                    <option value="" disabled selected>Select gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                    <option value="Prefer not to say">Prefer not to say</option>
                                </select>
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- Education & Experience -->
                        @php
                            $showEducation = in_array('education', $careerFormFields);
                            $showRelevantExp = in_array('relevant_exp', $careerFormFields);
                            
                            $gridCols = 1;
                            if ($showEducation && $showRelevantExp) {
                                $gridCols = 3;
                            } elseif ($showEducation || $showRelevantExp) {
                                $gridCols = 2;
                            }
                        @endphp
                        <div class="grid grid-cols-1 {{ $gridCols === 3 ? 'md:grid-cols-3' : ($gridCols === 2 ? 'md:grid-cols-2' : 'md:grid-cols-1') }} gap-6 items-end">
                            @if($showEducation)
                            <div>
                                <label class="form-label">Highest Educational Qualification <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="education" required placeholder="E.g. MBA, B.Tech"
                                    class="form-input">
                            </div>
                            @endif
                            <div>
                                <label class="form-label">Total Experience (in years) <span
                                        class="text-red-500">*</span></label>
                                <input type="number" step="0.5" name="total_exp" required placeholder="E.g. 5"
                                    class="form-input">
                            </div>
                            @if($showRelevantExp)
                            <div>
                                <label class="form-label">Total Relevant Experience</label>
                                <input type="number" step="0.5" name="relevant_exp" placeholder="E.g. 3" class="form-input">
                            </div>
                            @endif
                        </div>

                        <!-- CTC -->
                        @if(in_array('current_ctc', $careerFormFields) || in_array('expected_ctc', $careerFormFields))
                        <div class="grid grid-cols-1 {{ (in_array('current_ctc', $careerFormFields) && in_array('expected_ctc', $careerFormFields)) ? 'md:grid-cols-2' : 'md:grid-cols-1' }} gap-6 items-end">
                            @if(in_array('current_ctc', $careerFormFields))
                            <div>
                                <label class="form-label">Current CTC</label>
                                <input type="text" name="current_ctc" placeholder="E.g. 5 LPA" class="form-input">
                            </div>
                            @endif
                            @if(in_array('expected_ctc', $careerFormFields))
                            <div>
                                <label class="form-label">Expected CTC <span class="text-red-500">*</span></label>
                                <input type="text" name="expected_ctc" required placeholder="E.g. 8 LPA" class="form-input">
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- Custom Fields -->
                        @php
                            $activeCustomFields = [];
                            foreach ($careerCustomFields as $labelVal) {
                                if (strpos($labelVal, ':') !== false) {
                                    list($fKey, $fLabel) = explode(':', $labelVal, 2);
                                } else {
                                    $fKey = 'custom_' . strtolower(str_replace(' ', '_', $labelVal));
                                    $fLabel = $labelVal;
                                }
                                if (in_array($fKey, $careerFormFields)) {
                                    $activeCustomFields[] = ['key' => $fKey, 'label' => $fLabel];
                                }
                            }
                        @endphp

                        @if(count($activeCustomFields) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2 items-end">
                            @foreach($activeCustomFields as $cf)
                                <div>
                                    <label class="form-label">{{ $cf['label'] }} <span class="text-red-500">*</span></label>
                                    <input type="text" name="custom_fields[{{ $cf['key'] }}]" required placeholder="Enter {{ $cf['label'] }}" class="form-input">
                                </div>
                            @endforeach
                        </div>
                        @endif

                        <div class="pt-6 border-t border-gray-100">
                            <button type="submit"
                                class="w-full sm:w-auto px-10 py-4 bg-[#E8460A] text-white rounded-xl font-black text-sm uppercase tracking-widest shadow-xl shadow-orange-500/20 hover:bg-[#d63f08] hover:-translate-y-0.5 transition-all duration-300">
                                Apply for Position
                            </button>
                            <div class="text-[11px] text-gray-500 font-medium text-center sm:text-left mt-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <span class="font-bold text-gray-700">Privacy Notice:</span> By submitting your application, you consent to TourRaja collecting and processing your personal information solely for recruitment purposes. Your information will be handled securely and will not be shared with third parties except where required for the hiring process or by law.
                                <br><br>
                                Read our full <a href="{{ url('/privacy-policy-careers') }}" target="_blank" class="text-[#E8460A] hover:underline font-bold">Privacy Policy – Careers</a>.
                            </div>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Resume Upload Preview
            const resumeInput = document.getElementById('resume_upload');
            const previewContainer = document.getElementById('resume_preview_container');
            const previewLink = document.getElementById('resume_preview_link');

            if (resumeInput) {
                resumeInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        previewLink.textContent = file.name;
                        const objectUrl = URL.createObjectURL(file);
                        previewLink.href = objectUrl;
                        previewContainer.classList.remove('hidden');
                        previewContainer.classList.add('flex');
                    } else {
                        previewContainer.classList.add('hidden');
                        previewContainer.classList.remove('flex');
                    }
                });
            }

            // Dynamic Job Positions Filtering & Rendering
            const positions = @json($positions);
            const container = document.getElementById('positions-list-container');
            const searchInput = document.getElementById('filter-search');
            const deptSelect = document.getElementById('filter-department');
            const locSelect = document.getElementById('filter-location');
            const clearBtn = document.getElementById('btn-clear-filters');

            function calculateDaysAgo(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diffTime = Math.abs(now - date);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if (diffDays <= 1) return 'Today';
                if (diffDays === 2) return '1 day ago';
                return `${diffDays} days ago`;
            }

            function selectRoleForApplication(roleTitle) {
                const selectEl = document.getElementById('application-role-select');
                if (selectEl) {
                    selectEl.value = roleTitle;
                    
                    // Scroll to form card
                    const target = document.getElementById('application-form-section');
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    
                    // Highlight effect
                    const card = document.getElementById('application-form-card');
                    if (card) {
                        card.style.transition = 'all 0.4s ease';
                        card.style.borderColor = '#E8460A';
                        card.style.boxShadow = '0 0 25px rgba(232, 70, 10, 0.15)';
                        setTimeout(() => {
                            card.style.borderColor = 'rgba(226, 232, 240, 1)';
                            card.style.boxShadow = '0 8px 30px rgb(0,0,0,0.04)';
                        }, 2000);
                    }
                }
            }

            // Expose globally for onclick
            window.selectRoleForApplication = selectRoleForApplication;

            function renderPositions() {
                const searchVal = searchInput.value.toLowerCase().trim();
                const selectedDept = deptSelect.value;
                const selectedLoc = locSelect.value;

                // Toggle Clear Button
                if (searchVal || selectedDept || selectedLoc) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }

                // Filter
                const filtered = positions.filter(pos => {
                    const matchesSearch = pos.title.toLowerCase().includes(searchVal);
                    const matchesDept = !selectedDept || pos.department_id == selectedDept;
                    
                    let matchesLoc = true;
                    if (selectedLoc) {
                        const posLocs = Array.isArray(pos.locations) ? pos.locations : JSON.parse(pos.locations || '[]');
                        matchesLoc = posLocs.includes(selectedLoc);
                    }

                    return matchesSearch && matchesDept && matchesLoc;
                });

                // Clear view
                container.innerHTML = '';

                if (filtered.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-16 bg-white border border-gray-100 rounded-3xl space-y-3">
                            <i data-lucide="info" class="w-10 h-10 text-gray-400 mx-auto"></i>
                            <h4 class="text-base font-black text-gray-900">No Job Openings Found</h4>
                            <p class="text-xs text-gray-500 font-medium max-w-sm mx-auto">Try refining your search terms or selecting different filters.</p>
                        </div>
                    `;
                    lucide.createIcons();
                    return;
                }

                // Group by department name
                const groups = {};
                filtered.forEach(pos => {
                    const deptName = pos.department ? pos.department.name : 'Other Department';
                    if (!groups[deptName]) groups[deptName] = [];
                    groups[deptName].push(pos);
                });

                // Generate HTML for each group
                for (let [deptName, groupJobs] of Object.entries(groups)) {
                    const groupSection = document.createElement('div');
                    groupSection.className = 'space-y-6';

                    // Group Header
                    const header = document.createElement('div');
                    header.className = 'flex items-center gap-3 border-b border-gray-100 pb-3';
                    header.innerHTML = `
                        <h3 class="text-lg font-black text-gray-900 tracking-tight" style="font-size: 1.25rem;">${deptName}</h3>
                        <span class="dept-badge">${groupJobs.length} ${groupJobs.length === 1 ? 'job' : 'jobs'}</span>
                    `;
                    groupSection.appendChild(header);

                    // Jobs list in group
                    const jobsList = document.createElement('div');
                    jobsList.className = 'space-y-4';

                    groupJobs.forEach(job => {
                        const card = document.createElement('div');
                        card.className = 'career-job-card';
                        card.setAttribute('onclick', `window.selectRoleForApplication('${job.title}')`);
                        
                        const locs = Array.isArray(job.locations) ? job.locations : JSON.parse(job.locations || '[]');
                        const locationStr = locs.join(', ');
                        const daysAgo = calculateDaysAgo(job.created_at);

                        card.innerHTML = `
                            <div class="space-y-2">
                                <div class="flex items-center gap-3 flex-wrap">
                                    <h4 class="text-sm font-black text-gray-900 group-hover/card:text-[#E8460A] transition-colors" style="font-size: 0.95rem;">${job.title}</h4>
                                    <span class="text-[10px] text-gray-400 font-bold" style="font-size: 0.75rem;">${daysAgo}</span>
                                </div>
                                <div class="flex items-center gap-4 text-xs font-semibold text-gray-400" style="font-size: 0.75rem;">
                                    <span class="flex items-center gap-1"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i> ${locationStr}</span>
                                    <span>•</span>
                                    <span>${job.experience}</span>
                                    <span>•</span>
                                    <span>${job.job_type}</span>
                                    ${job.salary ? `<span>•</span> <span>${job.salary}</span>` : ''}
                                </div>
                            </div>
                            
                            <!-- Arrow Button -->
                            <div class="career-arrow-btn">
                                <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
                            </div>
                        `;
                        jobsList.appendChild(card);
                    });

                    groupSection.appendChild(jobsList);
                    container.appendChild(groupSection);
                }

                lucide.createIcons();
            }

            // Setup Listeners
            searchInput.addEventListener('input', renderPositions);
            deptSelect.addEventListener('change', renderPositions);
            locSelect.addEventListener('change', renderPositions);
            
            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                deptSelect.value = '';
                locSelect.value = '';
                renderPositions();
            });

            // Initial Draw
            renderPositions();
        });
    </script>
@endsection