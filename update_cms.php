<?php
$aboutHtml = '<p>Welcome to <span class="font-bold text-black">www.tour raja.com</span> [“Tour raja”] – your trusted platform connecting travel agents with customers through seamless brochure uploads and easy access to contact details.</p>
<p>At Tour raja, we specialize in offering travel agents a user-friendly space to showcase their brochures, upload their latest travel offerings, and manage essential contact details. Our platform is designed to make it easier for both travel agents and customers to find what they need, when they need it.</p>
<div class="mt-6 space-y-2"> 
<h3 class="text-lg font-bold text-black">For Travel Agents:</h3>
<p>We simplify the process of sharing your brochures with potential clients, providing a hassle-free solution for managing and displaying your travel products and services online. You can easily upload, update, and organize your brochures, making them accessible to your customers in just a few clicks.</p>
</div>
<div class="mt-6 space-y-2">
<h3 class="text-lg font-bold text-black">For Customers:</h3>
<p>Our platform makes it easier than ever to explore a wide range of travel options, from brochures to contact details. Whether you\'re planning your next vacation or researching travel services, you’ll find all the information you need in one place.</p>
</div>
<p class="mt-6">Our mission is to bridge the gap between travel agents and customers by providing a straightforward, efficient platform that saves time and enhances business opportunities. Whether you’re a travel agent looking to showcase your offerings or a customer seeking personalized travel information, Tour raja is here to make the process smooth and seamless for everyone.</p>
<p class="mt-4 font-bold text-black">Join Tour raja today and experience the convenience of connecting, sharing, and discovering travel options with ease.</p>';

DB::table('cms_pages')->updateOrInsert(
    ['slug' => 'about-us'],
    ['title' => 'About Tour Raja', 'content' => $aboutHtml, 'status' => 'Published', 'created_at' => now(), 'updated_at' => now()]
);

$termsHtml = '<div>
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
</ul>
</div>';

DB::table('cms_pages')->updateOrInsert(
    ['slug' => 'terms-and-conditions'],
    ['title' => 'Terms of Service', 'content' => $termsHtml, 'status' => 'Published', 'created_at' => now(), 'updated_at' => now()]
);

echo "Updated!\n";
