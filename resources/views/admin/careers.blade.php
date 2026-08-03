@extends('layouts.admin')

@section('admin_title', 'Careers')

@section('content')

<script>
function doDeleteDept(id, name) {
    if (!confirm('Delete department "' + name + '"?\nThis will also delete all its job positions.')) return;
    window.location.href = '/admin/careers/departments/delete/' + id;
}
function doDeleteLoc(id, name) {
    if (!confirm('Delete location "' + name + '" permanently?')) return;
    window.location.href = '/admin/careers/locations/delete/' + id;
}
</script>
<!-- Custom Scoped Styles for Orange Toggle Switch and Settings -->
<style>
    /* Orange Toggle Switch styling */
    .orange-switch {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 24px;
    }
    .orange-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .orange-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #e2e8f0;
        transition: .3s;
        border-radius: 24px;
        border: 1px solid #cbd5e1;
    }
    .orange-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        transition: .3s;
        border-radius: 50%;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    input:checked + .orange-slider {
        background-color: #e85d26 !important;
        border-color: #e85d26 !important;
    }
    input:focus + .orange-slider {
        box-shadow: 0 0 1px #e85d26;
    }
    input:checked + .orange-slider:before {
        transform: translateX(24px);
    }
    
    /* Settings Form Styles */
    .settings-checkbox-card {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .settings-checkbox-card:hover {
        border-color: #ffd8cc;
        background-color: #fff7f5;
    }
</style>

<div class="space-y-10 pb-12" x-data="{ 
    activeTab: 'applications', 
    showViewModal: false, 
    showPositionModal: false, 
    showDeptModal: false, 
    showLocModal: false, 
    editMode: false,
    activeApp: { role: '', resume_path: '', first_name: '', middle_name: '', last_name: '', email: '', phone: '', location: '', location_other: '', notice_period: '', gender: '', education: '', total_exp: '', relevant_exp: '', current_ctc: '', expected_ctc: '' },
    posForm: { id: '', title: '', department_id: '', locations: [], experience: '', job_type: 'Full Time', salary: '', status: 'Active' }
}">
    
    <!-- Top Header and Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="space-y-2">
            <h2 class="font-black text-foreground tracking-tight">Careers Portal</h2>
            <p class="text-muted-text font-medium">Manage open job positions and review candidate submissions.</p>
        </div>
        
        <!-- Toggle Career Application Form Settings -->
        <div class="bg-white p-4 rounded-2xl border border-border-soft flex items-center gap-4 shadow-sm">
            <form action="{{ route('admin.careers.settings.update') }}" method="POST" class="flex items-center gap-3">
                @csrf
                <label class="orange-switch">
                    <input type="checkbox" name="career_form_enabled" value="1" {{ $careerFormEnabled ? 'checked' : '' }} onchange="this.form.submit()">
                    <span class="orange-slider"></span>
                </label>
                <div class="text-xs font-bold text-gray-700">
                    <p class="m-0">Application Form: <span class="{{ $careerFormEnabled ? 'text-[#e85d26]' : 'text-red-500' }}">{{ $careerFormEnabled ? 'Visible' : 'Hidden' }}</span></p>
                    <p class="text-[10px] text-gray-400 font-medium">Toggles application form on frontend</p>
                </div>
            </form>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex border-b border-border-soft gap-6">
        <button 
            @click="activeTab = 'applications'" 
            :class="activeTab === 'applications' ? 'border-primary text-primary font-black' : 'border-transparent text-muted-text hover:text-foreground font-bold'"
            class="pb-4 border-b-2 text-sm uppercase tracking-wider transition-all"
        >
            Applications ({{ count($applications) }})
        </button>
        <button 
            @click="activeTab = 'positions'" 
            :class="activeTab === 'positions' ? 'border-primary text-primary font-black' : 'border-transparent text-muted-text hover:text-foreground font-bold'"
            class="pb-4 border-b-2 text-sm uppercase tracking-wider transition-all"
        >
            Manage Job Openings ({{ count($positions) }})
        </button>
        <button 
            @click="activeTab = 'editForm'" 
            :class="activeTab === 'editForm' ? 'border-primary text-primary font-black' : 'border-transparent text-muted-text hover:text-foreground font-bold'"
            class="pb-4 border-b-2 text-sm uppercase tracking-wider transition-all"
        >
            Edit Application Form
        </button>
    </div>

    <!-- ================= APPLICATIONS TAB ================= -->
    <div x-show="activeTab === 'applications'" class="space-y-6">
        <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
            <div class="p-8 border-b border-border-soft">
                <h3 class="text-xl font-black">Incoming Resumes</h3>
            </div>

            <div class="admin-table-container">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                            <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">APPLICANT</th>
                            <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">ROLE</th>
                            <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">EXPERIENCE</th>
                            <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">RESUME</th>
                            <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft">
                        @forelse($applications as $index => $app)
                            @php
                                $srNo = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                            @endphp
                            <tr class="group hover:bg-gray-50/30 transition-colors">
                                <td class="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{{ $srNo }}</td>
                                <td class="py-6 px-10">
                                    <div class="space-y-1">
                                        <p class="text-sm font-black text-foreground">{{ $app->first_name }} {{ $app->last_name }}</p>
                                        <div class="flex items-center gap-3 text-[10px] text-muted-text font-medium">
                                            <span class="flex items-center gap-1"><i data-lucide="mail" size="10"></i> {{ $app->email }}</span>
                                            <span class="flex items-center gap-1"><i data-lucide="phone" size="10"></i> {{ $app->phone }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-10 text-sm font-bold text-foreground">{{ $app->role }}</td>
                                <td class="py-6 px-10 text-sm font-medium text-muted-text">{{ $app->total_exp }} Years</td>
                                <td class="py-6 px-10">
                                    @if($app->resume_path)
                                        <a href="{{ asset('storage/' . $app->resume_path) }}" target="_blank" class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 px-3 py-1.5 rounded-xl text-xs font-bold hover:bg-blue-100 transition-colors">
                                            <i data-lucide="file-text" size="14"></i> View Resume
                                        </a>
                                    @else
                                        <span class="text-xs font-medium text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="py-6 px-10 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button 
                                            @click="showViewModal = true; activeApp = { role: '{{ addslashes($app->role) }}', first_name: '{{ addslashes($app->first_name) }}', middle_name: '{{ addslashes($app->middle_name) }}', last_name: '{{ addslashes($app->last_name) }}', email: '{{ addslashes($app->email) }}', phone: '{{ addslashes($app->phone) }}', location: '{{ addslashes($app->location) }}', location_other: '{{ addslashes($app->location_other) }}', notice_period: '{{ addslashes($app->notice_period) }}', gender: '{{ addslashes($app->gender) }}', education: '{{ addslashes($app->education) }}', total_exp: '{{ addslashes($app->total_exp) }}', relevant_exp: '{{ addslashes($app->relevant_exp) }}', current_ctc: '{{ addslashes($app->current_ctc) }}', expected_ctc: '{{ addslashes($app->expected_ctc) }}', resume_path: '{{ $app->resume_path ? asset('storage/' . $app->resume_path) : '' }}' }"
                                            class="p-2 text-muted-text hover:text-primary transition-all"
                                        >
                                            <i data-lucide="eye" size="18"></i>
                                        </button>
                                        <a 
                                            href="{{ url('/admin/careers/delete/' . $app->id) }}" 
                                            onclick="return confirm('Are you sure you want to delete this application?');"
                                            class="p-2 text-muted-text hover:text-red-500 transition-all"
                                        >
                                            <i data-lucide="trash-2" size="20"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-sm font-bold text-muted-text">No career applications found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================= OPEN POSITIONS TAB ================= -->
    <div x-show="activeTab === 'positions'" class="space-y-6" style="display: none;">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-black">Active Openings</h3>
            <button 
                @click="showPositionModal = true; editMode = false; posForm = { id: '', title: '', department_id: '', locations: [], experience: '2-4 Years', job_type: 'Full Time', salary: '', status: 'Active' }"
                class="bg-[#e85d26] text-white px-6 py-3 rounded-2xl text-xs font-black uppercase tracking-wider hover:bg-[#d63f08] hover:-translate-y-0.5 transition-all shadow-md"
            >
                Add Open Role +
            </button>
        </div>

        <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
            <div class="admin-table-container">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">POSITION NAME</th>
                            <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">DEPARTMENT</th>
                            <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">LOCATIONS</th>
                            <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">EXPERIENCE</th>
                            <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SALARY (CTC)</th>
                            <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                            <th class="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-soft">
                        @forelse($positions as $pos)
                            <tr class="group hover:bg-gray-50/30 transition-colors">
                                <td class="py-6 px-10">
                                    <div class="space-y-1">
                                        <p class="text-sm font-black text-foreground">{{ $pos->title }}</p>
                                        <p class="text-[10px] text-muted-text font-bold uppercase tracking-wider">{{ $pos->job_type }}</p>
                                    </div>
                                </td>
                                <td class="py-6 px-10 text-sm font-bold text-foreground">{{ $pos->department->name ?? 'N/A' }}</td>
                                <td class="py-6 px-10 text-xs font-semibold text-muted-text">
                                    @php
                                        $locs = is_array($pos->locations) ? $pos->locations : json_decode($pos->locations, true);
                                    @endphp
                                    {{ is_array($locs) ? implode(', ', $locs) : $pos->locations }}
                                </td>
                                <td class="py-6 px-10 text-sm font-medium text-muted-text">{{ $pos->experience }}</td>
                                <td class="py-6 px-10 text-sm font-medium text-muted-text">{{ $pos->salary ?? 'Not Specified' }}</td>
                                <td class="py-6 px-10">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $pos->status === 'Active' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-500' }}">
                                        {{ $pos->status }}
                                    </span>
                                </td>
                                <td class="py-6 px-10 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button 
                                            @click="showPositionModal = true; editMode = true; posForm = { id: '{{ $pos->id }}', title: '{{ addslashes($pos->title) }}', department_id: '{{ $pos->department_id }}', locations: {{ json_encode($locs ?? []) }}, experience: '{{ $pos->experience }}', job_type: '{{ $pos->job_type }}', salary: '{{ $pos->salary }}', status: '{{ $pos->status }}' }"
                                            class="p-2 text-muted-text hover:text-primary transition-all"
                                        >
                                            <i data-lucide="edit" size="18"></i>
                                        </button>
                                        <a 
                                            href="{{ url('/admin/careers/positions/delete/' . $pos->id) }}" 
                                            onclick="return confirm('Are you sure you want to delete this open position?');"
                                            class="p-2 text-muted-text hover:text-red-500 transition-all"
                                        >
                                            <i data-lucide="trash-2" size="20"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-sm font-bold text-muted-text">No active job openings listed.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ================= EDIT APPLICATION FORM TAB ================= -->
    <div x-show="activeTab === 'editForm'" class="space-y-6" style="display: none;">
        <div class="bg-white rounded-[40px] shadow-premium border border-border-soft p-8 md:p-10 space-y-8">
            <div>
                <h3 class="text-xl font-black text-foreground">Edit Application Form Fields</h3>
                <p class="text-xs text-muted-text font-medium mt-1">Configure which input fields are displayed to job applicants on the careers webpage.</p>
            </div>

            <form action="{{ route('admin.careers.settings.update') }}" method="POST" class="space-y-8">
                @csrf
                <input type="hidden" name="career_form_enabled" value="{{ $careerFormEnabled ? '1' : '0' }}">

                <!-- Form Header Title Customization -->
                <div class="space-y-2 max-w-md">
                    <label class="text-xs font-bold text-gray-700 pl-1">Application Section Title</label>
                    <input required type="text" name="career_form_title" value="{{ $careerFormTitle }}" placeholder="E.g. Application Form" class="w-full bg-[#f8fafc] border border-gray-200 rounded-2xl py-3.5 px-5 outline-none focus:border-primary/50 text-sm font-bold text-gray-700">
                </div>

                <!-- Checkboxes Group -->
                <div class="space-y-4">
                    <label class="text-xs font-black uppercase tracking-wider text-muted-text">Optional Form Fields</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @php
                            $availableFields = [
                                ['key' => 'middle_name', 'label' => 'Middle Name', 'desc' => 'Allows applicant to add a middle name'],
                                ['key' => 'phone', 'label' => 'Phone Country Code Selector', 'desc' => 'Enables country flag dropdown code'],
                                ['key' => 'gender', 'label' => 'Gender Field', 'desc' => 'Displays Male/Female select input'],
                                ['key' => 'education', 'label' => 'Educational Qualification', 'desc' => 'Allows adding highest degree details'],
                                ['key' => 'notice_period', 'label' => 'Notice Period Input', 'desc' => 'Displays notice duration dropdown select'],
                                ['key' => 'current_ctc', 'label' => 'Current CTC Salary', 'desc' => 'Displays current salary input field'],
                                ['key' => 'expected_ctc', 'label' => 'Expected CTC Salary', 'desc' => 'Displays expected salary input field'],
                                ['key' => 'relevant_exp', 'label' => 'Relevant Experience duration', 'desc' => 'Displays experience in similar roles'],
                            ];
                        @endphp

                        @foreach($availableFields as $field)
                            <label class="bg-gray-50 border border-gray-100 rounded-2xl p-5 flex items-start gap-4 settings-checkbox-card select-none">
                                <input type="checkbox" name="career_form_fields[]" value="{{ $field['key'] }}" {{ in_array($field['key'], $careerFormFields) ? 'checked' : '' }} class="rounded border-gray-300 text-[#e85d26] focus:ring-[#e85d26]/20 w-5 h-5 shrink-0 mt-0.5">
                                <div class="space-y-1">
                                    <p class="text-xs font-black text-gray-800">{{ $field['label'] }}</p>
                                    <p class="text-[10px] text-gray-400 font-medium leading-normal">{{ $field['desc'] }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Custom Fields Section -->
                <div class="space-y-4 pt-6 border-t border-gray-100" x-data="{ showCustomInput: false, newLabel: '' }">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-black uppercase tracking-wider text-muted-text">Custom Form Fields</label>
                        <button type="button" @click="showCustomInput = !showCustomInput" class="text-xs text-primary font-black hover:underline">+ Add Custom Field</button>
                    </div>

                    <!-- Inline Add Custom Field Input -->
                    <div x-show="showCustomInput" class="max-w-md p-4 bg-gray-50 rounded-2xl border border-gray-100 flex gap-3 items-center" style="display: none;">
                        <input type="text" x-model="newLabel" placeholder="E.g. Skype ID or Portfolio Link" class="flex-grow bg-white border border-gray-200 rounded-xl px-4 py-2 text-xs font-bold">
                        <button type="button" @click="
                            if (!newLabel.trim()) return;
                            const key = 'custom_' + newLabel.toLowerCase().replace(/[^a-z0-9]/g, '_');
                            
                            const container = document.getElementById('custom-fields-grid-list');
                            const card = document.createElement('div');
                            card.className = 'bg-gray-50 border border-gray-200 rounded-2xl p-5 flex items-start justify-between gap-4';
                            card.innerHTML = `
                                <div class='flex items-start gap-4'>
                                    <input type='checkbox' name='career_form_fields[]' value='${key}' checked class='rounded border-gray-300 text-[#e85d26] w-5 h-5 mt-0.5'>
                                    <div class='space-y-1'>
                                        <p class='text-xs font-black text-gray-800'>${newLabel}</p>
                                        <p class='text-[10px] text-gray-400 font-medium'>Custom field input</p>
                                    </div>
                                </div>
                                <input type='hidden' name='career_custom_fields[]' value='${key}:${newLabel}'>
                            `;
                            container.appendChild(card);
                            
                            newLabel = '';
                            showCustomInput = false;
                        " class="bg-[#e85d26] text-white px-4 py-2 rounded-xl text-xs font-bold">Add</button>
                        <button type="button" @click="showCustomInput = false; newLabel = ''" class="bg-gray-200 text-gray-600 px-4 py-2 rounded-xl text-xs font-bold">Cancel</button>
                    </div>

                    <!-- Custom Fields Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="custom-fields-grid-list">
                        @foreach($careerCustomFields as $labelVal)
                            @php
                                if (strpos($labelVal, ':') !== false) {
                                    list($fKey, $fLabel) = explode(':', $labelVal, 2);
                                } else {
                                    $fKey = 'custom_' . strtolower(str_replace(' ', '_', $labelVal));
                                    $fLabel = $labelVal;
                                }
                            @endphp
                            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 flex items-start justify-between gap-4 group/custom" id="custom-field-row-{{ $fKey }}">
                                <div class="flex items-start gap-4">
                                    <input type="checkbox" name="career_form_fields[]" value="{{ $fKey }}" {{ in_array($fKey, $careerFormFields) ? 'checked' : '' }} class="rounded border-gray-300 text-[#e85d26] focus:ring-[#e85d26]/20 w-5 h-5 shrink-0 mt-0.5">
                                    <div class="space-y-1">
                                        <p class="text-xs font-black text-gray-800">{{ $fLabel }}</p>
                                        <p class="text-[10px] text-gray-400 font-medium">Custom field input</p>
                                    </div>
                                </div>
                                <input type="hidden" name="career_custom_fields[]" value="{{ $fKey }}:{{ $fLabel }}">
                                
                                <button type="button" @click="document.getElementById('custom-field-row-{{ $fKey }}').remove();" class="text-red-500 hover:text-red-700 p-0.5 opacity-0 group-hover/custom:opacity-100 transition-all">
                                    <i data-lucide="trash-2" size="20"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-100 flex items-center gap-3">
                    <button type="submit" class="bg-[#e85d26] hover:bg-[#d63f08] text-white px-8 py-3.5 rounded-2xl text-xs font-black uppercase tracking-wider transition-all shadow-md">Save Settings</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODALS ================= -->

    <!-- Modal 1: View Application Modal -->
    <div 
        x-show="showViewModal" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        style="display: none;"
    >
        <div @click.away="showViewModal = false" class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-2xl w-full overflow-hidden p-10 space-y-8 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-border-soft pb-4">
                <div class="space-y-1">
                    <h3 class="text-xl font-black text-foreground" x-text="'Application for ' + activeApp.role"></h3>
                    <p class="text-xs text-muted-text font-medium" x-text="'Applicant: ' + activeApp.first_name + ' ' + (activeApp.middle_name ? activeApp.middle_name + ' ' : '') + activeApp.last_name"></p>
                </div>
                <button @click="showViewModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4 text-xs font-bold text-muted-text">
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Email</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeApp.email"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Phone</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeApp.phone || 'N/A'"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Location</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeApp.location + (activeApp.location_other ? ' (' + activeApp.location_other + ')' : '')"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Gender</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeApp.gender"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Education</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeApp.education"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Total Experience</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeApp.total_exp + ' Years'"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Relevant Experience</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeApp.relevant_exp + ' Years' || 'N/A'"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Notice Period</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeApp.notice_period"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Current CTC</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeApp.current_ctc || 'N/A'"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Expected CTC</p>
                        <p class="bg-gray-50 p-3 rounded-xl text-foreground" x-text="activeApp.expected_ctc"></p>
                    </div>
                    <div class="space-y-1 col-span-2">
                        <p class="uppercase text-[9px] pl-1 tracking-widest text-muted-text/50">Resume</p>
                        <div class="bg-gray-50 p-3 rounded-xl">
                            <a x-show="activeApp.resume_path" :href="activeApp.resume_path" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 w-max">
                                <i data-lucide="file-text" size="16"></i> View Resume
                            </a>
                            <span x-show="!activeApp.resume_path">N/A</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-end pt-4">
                <button type="button" @click="showViewModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Close</button>
            </div>
        </div>
    </div>

    <!-- Modal 2: Create / Edit Open Position Modal -->
    <div 
        x-show="showPositionModal" 
        class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        style="display: none;"
    >
        <div class="bg-white rounded-[40px] shadow-premium border border-border-soft max-w-xl w-full overflow-hidden p-10 space-y-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-border-soft pb-4">
                <h3 class="text-xl font-black text-foreground" x-text="editMode ? 'Edit Open Position' : 'Add New Open Position'"></h3>
                <button @click="showPositionModal = false" class="p-2 text-muted-text hover:text-primary transition-colors">
                    <i data-lucide="x" size="20"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.careers.positions.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="id" x-model="posForm.id">
                
                <!-- Position Title -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 pl-1">Position Name <span class="text-red-500">*</span></label>
                    <input required type="text" name="title" x-model="posForm.title" placeholder="E.g. Senior Travel Consultant" class="w-full bg-[#f8fafc] border border-gray-200 rounded-2xl py-3 px-5 outline-none focus:border-primary/50 text-sm font-semibold text-gray-700">
                </div>

                <!-- Department Selector — custom dropdown with inline delete -->
                <div class="space-y-1" x-data="{
                    showDeptInput: false,
                    newDeptName: '',
                    deptOpen: false,
                    selectedDeptId: '',
                    selectedDeptName: 'Select Department',
                    dropLeft: 0, dropTop: 0, dropWidth: 0,
                    openDept() {
                        const btn = this.$refs.deptBtn;
                        const r = btn.getBoundingClientRect();
                        this.dropLeft = r.left;
                        this.dropTop = r.bottom + 4;
                        this.dropWidth = r.width;
                        this.deptOpen = true;
                    },
                    selectDept(id, name) {
                        this.selectedDeptId = id;
                        this.selectedDeptName = name;
                        this.deptOpen = false;
                        posForm.department_id = id;
                    }
                }" x-init="$watch('posForm.department_id', v => { if(!v){ selectedDeptId=''; selectedDeptName='Select Department'; } })"
                @keydown.escape.window="deptOpen = false">

                    <label class="text-xs font-bold text-gray-700 pl-1 flex items-center justify-between">
                        <span>Department <span class="text-red-500">*</span></span>
                        <button type="button" @click="showDeptInput = !showDeptInput" class="text-xs text-primary font-black hover:underline">+ Add Department</button>
                    </label>

                    <!-- Hidden real input for form submission -->
                    <input type="hidden" name="department_id" :value="selectedDeptId">

                    <!-- Trigger button styled like the original select -->
                    <button type="button" x-ref="deptBtn"
                        @click="deptOpen ? deptOpen=false : openDept()"
                        class="w-full bg-[#f8fafc] border border-gray-200 rounded-2xl py-3 px-5 outline-none text-sm font-semibold text-gray-700 flex items-center justify-between text-left"
                        :class="deptOpen ? 'border-primary/50' : ''">
                        <span x-text="selectedDeptName" :class="selectedDeptId ? 'text-gray-700' : 'text-gray-400'"></span>
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform" :class="{'rotate-180': deptOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Backdrop to close on outside click -->
                    <div x-show="deptOpen" @click="deptOpen=false" class="fixed inset-0 z-[290]" style="background:transparent;"></div>

                    <!-- Dropdown portal — fixed position, truly above everything -->
                    <div x-show="deptOpen" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        :style="`position:fixed; left:${dropLeft}px; top:${dropTop}px; width:${dropWidth}px; z-index:300;`"
                        class="bg-white border border-gray-200 rounded-2xl shadow-2xl overflow-hidden">
                        <div class="max-h-52 overflow-y-auto py-1">
                            <div class="px-4 py-2 text-[10px] font-black text-gray-400 uppercase tracking-wider border-b border-gray-100">Select Department</div>
                            @foreach($departments as $dept)
                            <div id="dept-dd-row-{{ $dept->id }}"
                                class="flex items-center justify-between px-4 py-2.5 hover:bg-gray-50 transition-colors group/dd cursor-pointer"
                                @click="selectDept('{{ $dept->id }}', '{{ addslashes($dept->name) }}')">
                                <span class="text-sm font-semibold text-gray-700">{{ $dept->name }}</span>
                                <a href="{{ url('/admin/careers/departments/delete/' . $dept->id) }}"
                                    onclick="event.preventDefault(); event.stopPropagation(); deleteDept('{{ $dept->id }}', '{{ addslashes($dept->name) }}', 'dept-dd-row-{{ $dept->id }}');"
                                    class="text-red-500 hover:text-red-700 p-1 opacity-80 hover:opacity-100 transition-all flex-shrink-0"
                                    style="line-height:0;">
                                    <i data-lucide="trash-2" size="20"></i>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Inline Mini Form for Department -->
                    <div x-show="showDeptInput" x-transition class="mt-2 p-3 bg-gray-50 rounded-xl border border-gray-200">
                        <div class="flex gap-2 items-center">
                            <input type="text" x-model="newDeptName" placeholder="Enter department name..." class="flex-grow bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold">
                            <button type="button" @click="
                                if(!newDeptName.trim()) return;
                                const fd = new FormData();
                                fd.append('name', newDeptName);
                                fd.append('_token', '{{ csrf_token() }}');
                                fetch('{{ route('admin.careers.departments.store') }}', {
                                    method: 'POST',
                                    body: fd,
                                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        newDeptName = '';
                                        showDeptInput = false;
                                        window.location.reload();
                                    } else {
                                        alert('Error adding department.');
                                    }
                                })
                                .catch(err => alert('Department already exists or invalid.'));
                            " class="bg-[#e85d26] text-white px-3 py-2 rounded-xl text-xs font-bold">Add</button>
                            <button type="button" @click="showDeptInput = false; newDeptName = ''" class="bg-gray-200 text-gray-600 px-3 py-2 rounded-xl text-xs font-bold">Cancel</button>
                        </div>
                    </div>
                </div>

                <!-- Locations selector -->
                <div class="space-y-1" x-data="{ showLocInput: false, newLocName: '' }">
                    <label class="text-xs font-bold text-gray-700 pl-1 flex items-center justify-between">
                        <span>Select Locations <span class="text-red-500">*</span></span>
                        <button type="button" @click="showLocInput = !showLocInput" class="text-xs text-primary font-black hover:underline">+ Add Location</button>
                    </label>
                    <div id="modal-locations-checkbox-list" class="grid grid-cols-2 gap-3 bg-[#f8fafc] border border-gray-200 rounded-2xl p-4 max-h-36 overflow-y-auto">
                        @foreach($locations as $loc)
                            <div class="flex items-center justify-between hover:bg-gray-50/50 p-1.5 rounded-lg transition-all group/loc" id="loc-row-{{ $loc->id }}">
                                <label class="flex items-center gap-2.5 text-xs font-bold text-gray-700 cursor-pointer select-none flex-grow">
                                    <input type="checkbox" name="locations[]" value="{{ $loc->name }}" x-model="posForm.locations" class="rounded border-gray-300 text-primary focus:ring-primary/20 w-4 h-4">
                                    <span>{{ $loc->name }}</span>
                                </label>
                                <a href="{{ url('/admin/careers/locations/delete/' . $loc->id) }}"
                                    onclick="event.preventDefault(); event.stopPropagation(); deleteLoc('{{ $loc->id }}', '{{ addslashes($loc->name) }}', 'loc-row-{{ $loc->id }}');"
                                    class="text-red-500 hover:text-red-700 p-0.5 opacity-80 hover:opacity-100 transition-all"
                                    style="line-height:0;">
                                    <i data-lucide="trash-2" size="20"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    <!-- Inline Mini Form for Location -->
                    <div x-show="showLocInput" x-transition class="mt-2 p-3 bg-gray-50 rounded-xl border border-gray-200 flex gap-2 items-center">
                        <input type="text" x-model="newLocName" placeholder="Enter location name (e.g. Pune)..." class="flex-grow bg-white border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold">
                        <button type="button" @click="
                            if(!newLocName.trim()) return;
                            const fd = new FormData();
                            fd.append('name', newLocName);
                            fd.append('_token', '{{ csrf_token() }}');
                            fetch('{{ route('admin.careers.locations.store') }}', {
                                method: 'POST',
                                body: fd,
                                headers: { 'X-Requested-With': 'XMLHttpRequest' }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    // Refresh page in background to show delete option in checklist row
                                    window.location.reload();
                                } else {
                                    alert('Error adding location.');
                                }
                            })
                            .catch(err => alert('Location already exists or invalid.'));
                        " class="bg-[#e85d26] text-white px-3 py-2 rounded-xl text-xs font-bold">Add</button>
                        <button type="button" @click="showLocInput = false; newLocName = ''" class="bg-gray-200 text-gray-600 px-3 py-2 rounded-xl text-xs font-bold">Cancel</button>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Experience required -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 pl-1">Experience Required <span class="text-red-500">*</span></label>
                        <select required name="experience" x-model="posForm.experience" class="w-full bg-[#f8fafc] border border-gray-200 rounded-2xl py-3 px-5 outline-none focus:border-primary/50 text-sm font-semibold text-gray-700">
                            <option value="Fresher">Fresher</option>
                            <option value="1-2 Yrs">1-2 Years</option>
                            <option value="2-4 Yrs">2-4 Years</option>
                            <option value="2-6 Yrs">2-6 Years</option>
                            <option value="3-5 Yrs">3-5 Years</option>
                            <option value="3-7 Years">3-7 Years</option>
                            <option value="5+ Years">5+ Years</option>
                        </select>
                    </div>

                    <!-- Job Type -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 pl-1">Job Type <span class="text-red-500">*</span></label>
                        <select required name="job_type" x-model="posForm.job_type" class="w-full bg-[#f8fafc] border border-gray-200 rounded-2xl py-3 px-5 outline-none focus:border-primary/50 text-sm font-semibold text-gray-700">
                            <option value="Full Time">Full Time</option>
                            <option value="Part Time">Part Time</option>
                            <option value="Contract">Contract</option>
                            <option value="Internship">Internship</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- CTC (Optional) -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 pl-1">CTC Salary (Optional)</label>
                        <input type="text" name="salary" x-model="posForm.salary" placeholder="E.g. 3.6 - 5.0 LPA or Competitive" class="w-full bg-[#f8fafc] border border-gray-200 rounded-2xl py-3 px-5 outline-none focus:border-primary/50 text-sm font-semibold text-gray-700">
                    </div>

                    <!-- Status -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-gray-700 pl-1">Status <span class="text-red-500">*</span></label>
                        <select required name="status" x-model="posForm.status" class="w-full bg-[#f8fafc] border border-gray-200 rounded-2xl py-3 px-5 outline-none focus:border-primary/50 text-sm font-semibold text-gray-700">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-border-soft pt-4">
                    <button type="button" @click="showPositionModal = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                    <button type="submit" class="px-6 py-3 bg-[#e85d26] hover:bg-[#d63f08] text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-md">Save Position</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const _csrf = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

async function deleteDept(id, name, rowId) {
    if (!confirm('Delete department: "' + name + '"?\nThis will also delete all its job positions.')) return;
    try {
        const fd = new FormData();
        fd.append('_token', _csrf);
        const res = await fetch('/admin/careers/departments/delete/' + id, {
            method: 'POST',
            credentials: 'same-origin',
            body: fd
        });
        const text = await res.text();
        let data;
        try { data = JSON.parse(text); } catch(e) {
            console.error('Response was not JSON:', text.substring(0, 200));
            alert('Server error — check console for details.');
            return;
        }
        if (data.success) {
            const row = document.getElementById(rowId);
            if (row) row.remove();
        } else {
            alert('Could not delete: ' + (data.error || 'Unknown error'));
        }
    } catch (e) {
        alert('Network error: ' + e.message);
    }
}

async function deleteLoc(id, name, rowId) {
    if (!confirm('Delete location: "' + name + '" permanently?')) return;
    try {
        const fd = new FormData();
        fd.append('_token', _csrf);
        const res = await fetch('/admin/careers/locations/delete/' + id, {
            method: 'POST',
            credentials: 'same-origin',
            body: fd
        });
        const text = await res.text();
        let data;
        try { data = JSON.parse(text); } catch(e) {
            console.error('Response was not JSON:', text.substring(0, 200));
            alert('Server error — check console for details.');
            return;
        }
        if (data.success) {
            const row = document.getElementById(rowId);
            if (row) row.remove();
        } else {
            alert('Could not delete: ' + (data.error || 'Unknown error'));
        }
    } catch (e) {
        alert('Network error: ' + e.message);
    }
}
</script>
@endpush
