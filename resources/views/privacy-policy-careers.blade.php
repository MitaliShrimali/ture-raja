@extends('layouts.app')

@section('content')
<div class="bg-gray-50 pb-12" style="padding-top: 48px;">
    <div class="container-custom">
        <div class="w-full">
            <h1 class="font-black text-primary mb-6 uppercase" style="font-size: 30px; line-height: 1.2;">Privacy Policy – Careers</h1>
            
            <div class="prose prose-lg max-w-none text-text-muted">
                <p class="font-bold text-gray-800 uppercase tracking-wider bg-gray-100 inline-block px-3 py-1 rounded-lg text-sm mb-6">Last Updated: {{ date('F j, Y') }}</p>
                <p>At Tour Raja, we value your privacy and are committed to protecting the personal information you share while applying for career opportunities through our website.</p>
                
                <h4 class="font-black text-gray-900 text-lg mt-8 uppercase tracking-wide">1. Information We Collect</h4>
                <p>When you apply for a position at Tour Raja, we may collect the following information:</p>
                <ul class="list-disc pl-5 space-y-1.5 marker:text-[#E8460A]">
                    <li>Full Name</li>
                    <li>Email Address</li>
                    <li>Mobile Number</li>
                    <li>Current City & Country</li>
                    <li>Resume/CV</li>
                    <li>Portfolio or Website Links</li>
                    <li>Cover Letter (if provided)</li>
                    <li>Work Experience</li>
                    <li>Educational Qualifications</li>
                    <li>Skills and Certifications</li>
                    <li>Any additional information voluntarily shared during the recruitment process</li>
                </ul>

                <h4 class="font-black text-gray-900 text-lg mt-8 uppercase tracking-wide">2. How We Use Your Information</h4>
                <p>Your information is collected solely for recruitment purposes, including:</p>
                <ul class="list-disc pl-5 space-y-1.5 marker:text-[#E8460A]">
                    <li>Evaluating your application</li>
                    <li>Scheduling interviews</li>
                    <li>Contacting you regarding job opportunities</li>
                    <li>Verifying qualifications</li>
                </ul>

                <h4 class="font-black text-gray-900 text-lg mt-8 uppercase tracking-wide">3. Data Sharing & Security</h4>
                <p>We do not sell, rent, or share your personal data with third parties for marketing purposes. Your data is stored securely and is only accessible to our HR team and relevant hiring managers. We may share your information if required by law.</p>

                <h4 class="font-black text-gray-900 text-lg mt-8 uppercase tracking-wide">4. Retention of Information</h4>
                <p>If you are hired, your information will become part of your employee record. If you are not selected, we may retain your resume for up to 12 months to consider you for future opportunities. You can request the deletion of your data at any time.</p>

                <h4 class="font-black text-gray-900 text-lg mt-8 uppercase tracking-wide">5. Your Rights</h4>
                <p>You have the right to access, update, or request the deletion of your personal information. To do so, please contact us at <a href="mailto:careers@tour raja.com" class="text-[#E8460A] font-bold hover:underline">careers@tour raja.com</a>.</p>

                <h4 class="font-black text-gray-900 text-lg mt-8 uppercase tracking-wide">6. Changes to this Policy</h4>
                <p>We may update this Privacy Policy occasionally. Any changes will be reflected on this page with the updated date.</p>
            </div>
            
            <div class="mt-10 pt-6 border-t border-gray-200">
                <a href="{{ route('career') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors text-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Careers
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
