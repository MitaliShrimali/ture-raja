<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$privacyContent = <<<HTML
<p>
                    In general, you can visit Tour raja’s websites/ Softwares/Mobile Application (hereinafter referred to as “Software”) without telling us who you are and without revealing any information about yourself. However, at times, we may need some relevant information about you. Our Web servers collect domain names of visitors to measure the number of visits, average time spent on the site, pages viewed, etc. We do not save their e-mail addresses unless used any of services provided by us to users or any inquiry done by the user.
                </p>
                <p>
                    We believe it is important you know how we treat the information we receive from you, on the Internet.
                </p>

                <h3 class="text-lg font-bold text-foreground mt-8 mb-3">1. Effective Date:</h3>
                <p>
                    This Privacy Policy on Personal Information (‘Policy’) is effective with immediate effect and it supersedes all existing polices on the subject matter.
                </p>

                <h3 class="text-lg font-bold text-foreground mt-8 mb-3">2. Applicability:</h3>
                <p>
                    This Policy applies to all Data collected electronically by Tour Raja Private Limited (“Tour raja”) or its subsidiaries or affiliated companies.
                </p>

                <h3 class="text-lg font-bold text-foreground mt-8 mb-3">3. Objective of Policy:</h3>
                <p>
                    Tour raja takes seriously the trust you place in us. To prevent unauthorized access or disclosure, to maintain data accuracy and to ensure the appropriate use of the information, Tour raja utilizes appropriate physical, technical and administrative procedures to safeguard the information we collect.
                </p>

                <h3 class="text-lg font-bold text-foreground mt-8 mb-3">4. Collection of Information</h3>
                <ul class="list-disc pl-5 space-y-1 mb-4">
                    <li><strong>4.1.</strong> We will collect personal information from Users only if they voluntarily submit such information to us.</li>
                    <li><strong>4.2.</strong> We may collect personal information from Users in a variety of ways while visit or use of our website or software or mobile application;</li>
                </ul>

                <h3 class="text-lg font-bold text-foreground mt-8 mb-3">5. Permitted Sharing and use of Information</h3>
                <p>
                    <strong>5.1.</strong> Tour raja does not share or use personal information about you with non-affiliated companies except:
                </p>
                <ul class="list-disc pl-5 space-y-2 mb-4 mt-2">
                    <li><strong>5.1.1.</strong> To provide products or services requested by you;</li>
                    <li><strong>5.1.2.</strong> To improve our Customer Support service;</li>
                    <li><strong>5.1.3.</strong> To personalize user experience;</li>
                    <li><strong>5.1.4.</strong> We may use such information in the aggregate to understand how our Users, as a group, utilize the services and resources available on our Site;</li>
                    <li><strong>5.1.5.</strong> To improve our Software or website Site or mobile application;</li>
                    <li><strong>5.1.6.</strong> We provide the information to trusted entities who work on behalf of or with Tour raja under strict confidentiality agreements. These entities may use your personal information to help Tour raja communicate with you about offers from Tour raja and our marketing partners. However, these companies do not have any independent right to further share or disseminate this information;</li>
                    <li><strong>5.1.7.</strong> To respond to subpoenas, court orders, or legal process, or to establish or exercise our legal rights or defend against legal claims;</li>
                    <li><strong>5.1.8.</strong> To protect or enforce Tour raja’s rights, usage terms, intellectual or physical property or for safety of Tour raja or associated parties;</li>
                    <li><strong>5.1.9.</strong> Aggregate tracking and site usage information that we gather automatically as you access our Site;</li>
                    <li><strong>5.1.10.</strong> To run a promotion, contest, survey or other advertising event;</li>
                    <li><strong>5.1.11.</strong> To send periodic emails;</li>
                    <li><strong>5.1.12.</strong> To improve our products and services;</li>
                    <li><strong>5.1.13.</strong> To process payments;</li>
                    <li><strong>5.1.14.</strong> We may send our Users the information they agreed to receive about the topics we think will be of interest to them.</li>
                </ul>
                <p class="mt-4">
                    We may use email addresses provided by our Users to send them the information and any updates on their order. Email addresses may also be used to respond to our Users’ inquiries, questions, and/or other requests. If the User decides to opt-in to our mailing list, they will receive emails that may include company news, updates, product or service-related information, etc.
                </p>
                <p>
                    Tour raja will not be liable to any unsolicited information provided by you. Your consent to Tour raja using such information shall be as per Tour raja’s Privacy policy.
                </p>
                <p>
                    We will make a sincere effort to respond in a timely manner to your requests to correct inaccuracies in your personal information. For this, please send message containing inaccuracies to us.
                </p>

                <h3 class="text-lg font-bold text-foreground mt-8 mb-3">6. Cookies and other technologies</h3>
                <p>
                    We sometimes collect anonymous information from visits to our sites to help us provide better customer service. For example, we keep track of the domains from which people visit and we also measure visitor activity on Tour raja Software, but we do so in ways that keep the information of the visitor anonymous. Tour raja or its affiliates or vendors may use this data to analyze trends and statistics and to help us provide better customer service. We maintain the highest levels of confidentiality for this information. Our affiliates and vendors follow the same high levels of confidentiality. This anonymous information is used and analyzed only at an aggregate level to help us understand trends and patterns. None of this information is reviewed at an individual level. If you do not want your transaction details used in this manner, you can either disable your cookies or opt-out at the download or request page. Alternatively, you can set your browser to intimate upon receiving a cookie. You may accordingly decide to opt out.
                </p>
                <p>
                    2.Tour raja may use “cookies” to enhance User experience. User’s web browser places cookies on their hard drive for the purpose of keeping records and tracking information about them. Users may choose to set their web-browser to refuse cookies, or to alert them when cookies are being sent. If they do so, Users need to remember that some parts of the website may not function properly.
                </p>

                <h3 class="text-lg font-bold text-foreground mt-8 mb-3">7. Mailers</h3>
                <p>
                    Tour raja may send direct mailers to you at the address given by you.
                </p>

                <h3 class="text-lg font-bold text-foreground mt-8 mb-3">8. Anti-Spam policy</h3>
                <p>
                    Tour raja recognizes the receipt, transmission or distribution of spam emails (i.e. unsolicited bulk emails) as a major concern and has taken reasonable measures to minimize the transmission and effect of spam emails in our computing environment. All emails received by Tour raja are subject to spam check. Any email identified as spam will be rejected with sufficient information to the sender for taking necessary action. With this measure, along with other technical spam reduction measures, Tour raja hopes to minimize the effect of spam emails. Tour raja reserves the right to reject and/or report any suspicious spam emails, to the authorities concerned, for necessary action.
                </p>

                <h3 class="text-lg font-bold text-foreground mt-8 mb-3">9. Links to Non- Tour raja/Other websites and Software</h3>
                <p>
                    Tour raja may provide links to third-party Software for your convenience and information. If you access those links through the Tour raja Software, you will leave the Tour raja Software. Tour raja does not control those sites or their privacy practices, which may differ from Tour raja’s practices. We do not endorse or make any representations about third-party Software. The personal data you choose to provide to or that is collected by these third parties, including any social media websites/Software featured on our websites/Software, is not covered by the Tour raja’s Privacy Policy. We encourage you to review the privacy policy of any Software before submitting your personal information.
                </p>
                <p>
                    2.Tour raja’s Software may contain links to other sites such as that of its partners and affiliates. While we try to link only to sites that share our high standards and respect privacy, we are NOT responsible for the content or the privacy practices employed by such other sites. User’s browsing to other site is subject to other Site’s own terms and policies defined.
                </p>
                <p>
                    3. We may also provide social media features on our Software that enable you to share Tour raja information with your social networks and to interact with Tour raja on various social media sites. Your use of these features may result in the collection or sharing of information about you, depending on the feature. We encourage you to review the privacy policies and settings on the social media sites with which you interact to make sure you understand the information that could be shared by those sites.
                </p>
                <p>
                    4.At times we conduct online surveys to better understand the needs and profiles of our visitors. When we conduct a survey, we will try (but are not obligated) to let you know how we will use the information at the time we collect information from you on the Internet.
                </p>
                <p>
                    5.Please be aware that in certain circumstances, it is possible that personal information may be subject to disclosure pursuant to judicial or other government subpoenas, warrants, or orders.
                </p>

                <h3 class="text-lg font-bold text-foreground mt-8 mb-3">10. Children’s Privacy</h3>
                <p>
                    Tour raja Softwares/ websites are not directed to children under the age of 13 and Tour raja does not knowingly collect personal information from children under the age of 13.
                </p>
HTML;

$termsContent = <<<HTML
<div>
                    <h3 class="text-lg font-bold text-foreground mt-4 mb-2">1. Introduction:</h3>
                    <p>
                        Welcome to www.tour raja.com [“Tour Raja”]. By listing your travel booking services on our platform, you agree to comply with and be bound by the following terms and conditions. These terms govern your relationship with Tour Raja and set out the rules and guidelines for listing and maintaining your services on our website.
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-foreground mt-6 mb-2">2. Eligibility:</h3>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>2.1.</strong> Be a legitimate travel service provider with valid licenses and registrations as required by law and having physical office situated in India.</li>
                        <li><strong>2.2.</strong> Provide accurate and up-to-date information about your business and services.</li>
                        <li><strong>2.3.</strong> Ensure compliance with all applicable laws, regulations, and industry standards.</li>
                        <li><strong>2.4.</strong> Franchisee holder of other travel agent are not allowed to create login.</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-foreground mt-6 mb-2">3. Account Creation and Management:</h3>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>3.1.</strong> You must create an account to list your services on our platform.</li>
                        <li><strong>3.2.</strong> You are responsible for maintaining the confidentiality of your account credentials and for all activities conducted through your account.</li>
                        <li><strong>3.3.</strong> Do not share your login credentials with others. Sharing credentials may lead to account suspension or termination.</li>
                        <li><strong>3.4.</strong> Any false, misleading, or incomplete information provided during account creation or listing may result in suspension or termination of your account.</li>
                        <li><strong>3.5.</strong> When creating an account, you must submit information that belongs to you only.</li>
                        <li><strong>3.6.</strong> You must be capable of entering into a legally binding contract.</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-foreground mt-6 mb-2">4. Content Guidelines:</h3>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>4.1.</strong> All content, including descriptions, images, pricing, and contact details, must be accurate, lawful, and not infringe on any third-party rights.</li>
                        <li><strong>4.2.</strong> Tour Raja reserves the right to review, edit, or remove content that violates these guidelines or is deemed inappropriate.</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-foreground mt-6 mb-2">5. Fees and Payments:</h3>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>5.1.</strong> Listing fees, subscription charges, or commission rates, if applicable, will be outlined during the registration process or in a separate agreement.</li>
                        <li><strong>5.2.</strong> Payments must be made promptly as per the agreed terms. Non-payment may result in suspension or removal of your listing.</li>
                        <li><strong>5.3.</strong> Subscription fees once paid are non-refundable.</li>
                        <li><strong>5.4.</strong> If an error occurs during online payment, and the amount is not received in our account, it shall be considered as non-receipt of payment.</li>
                        <li><strong>5.5.</strong> The subscription will only be activated upon receipt of payment in our account.</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-foreground mt-6 mb-2">6. Responsibilities of Service Providers:</h3>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>6.1.</strong> You must deliver the services as advertised and maintain high standards of customer satisfaction.</li>
                        <li><strong>6.2.</strong> You are solely responsible for managing customer inquiries, bookings, cancellations, and disputes related to your services.</li>
                        <li><strong>6.3.</strong> Travel agents shall be responsible for providing a customer care number, address, and email ID for contact and shall resolve all customer issues.</li>
                        <li><strong>6.4.</strong> Tour Raja shall not be responsible for providing any resolution to customers regarding services to be provided by travel agents.</li>
                        <li><strong>6.5.</strong> Tour Raja does not guarantee the provision for Security of any data or information uploaded on our platform. Please ensure you keep records at your end.</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-foreground mt-6 mb-2">7. Prohibited Activities:</h3>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>7.1.</strong> Posting false or misleading information about your services.</li>
                        <li><strong>7.2.</strong> Engaging in activities that harm the reputation of Tour Raja or other service providers.</li>
                        <li><strong>7.3.</strong> Using the platform for illegal or unauthorized purposes.</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-foreground mt-6 mb-2">8. Termination of Listing:</h3>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>8.1.</strong> Tour Raja reserves the right to suspend or terminate your listing at any time, with or without notice, for violation of these terms or any applicable laws.</li>
                        <li><strong>8.2.</strong> You may terminate your listing by providing written notice to Tour Raja.</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-foreground mt-6 mb-2">9. Limitation of Liability:</h3>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>9.1.</strong> Tour Raja is a platform for listing services and does not guarantee bookings or customer interactions.</li>
                        <li><strong>9.2.</strong> Tour Raja is not responsible for any loss, damage, or liability arising from your use of the platform or your dealings with customers.</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-foreground mt-6 mb-2">10. Indemnification:</h3>
                    <p>
                        You agree to indemnify and hold harmless Tour Raja, its affiliates, and employees from any claims, damages, or expenses arising from your use of the platform or violation of these terms. Tour Raja reserves the right to disclose information about your listing, activities, or account to government authorities or law enforcement agencies as required by law or to ensure compliance with these terms.
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-foreground mt-6 mb-2">11. Intellectual Property Rights:</h3>
                    <ul class="list-disc pl-5 space-y-1">
                        <li><strong>11.1.</strong> All intellectual property rights, including trademarks, copyrights, and proprietary information associated with Tour Raja, remain the sole property of Tour Raja.</li>
                        <li><strong>11.2.</strong> By submitting content for listing on the platform, you grant Tour Raja a non-exclusive, royalty-free, worldwide license to use, reproduce, modify, and display your content for the purposes of operating and promoting the platform.</li>
                        <li><strong>11.3.</strong> You warrant that you have the necessary rights to grant this license and that your content does not infringe on the intellectual property rights of any third party.</li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-foreground mt-6 mb-2">12. Changes to Terms and Conditions:</h3>
                    <p>
                        Tour Raja reserves the right to modify these terms at any time. Changes will be effective upon posting on the website. Continued use of the platform signifies acceptance of the updated terms.
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-foreground mt-6 mb-2">13. Governing Law:</h3>
                    <p>
                        These terms and conditions are governed by the laws of Republic of India. Any disputes will be subject to the exclusive jurisdiction of the courts in Rajkot in Gujarat.
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-foreground mt-6 mb-2">14. Grievance Redressal:</h3>
                    <p>
                        If you have any grievances regarding your listing or the platform, you may contact our grievance redressal team at <a href="mailto:grievance@tour raja.com" class="text-primary hover:underline">grievance@tour raja.com</a>. We will acknowledge your complaint within 48 hours and strive to resolve it within tentative 30 days of receipt.
                    </p>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-foreground mt-6 mb-2">15. Contact Information:</h3>
                    <p>
                        If you have any questions or concerns about these terms, please contact us at <a href="mailto:support@tour raja.com" class="text-primary hover:underline">support@tour raja.com</a>.
                    </p>
                </div>
HTML;

\Illuminate\Support\Facades\DB::table('cms_pages')->where('slug', 'privacy-policy')->update(['content' => $privacyContent]);
\Illuminate\Support\Facades\DB::table('cms_pages')->where('slug', 'terms-and-conditions')->update(['content' => $termsContent]);
echo "Updated privacy and terms content\n";
