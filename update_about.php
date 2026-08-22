<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$aboutContent = <<<HTML
<p>Welcome to <span class="font-bold text-black">www.tour raja.com</span> [“Tour raja”] – your trusted platform connecting travel agents with customers through seamless brochure uploads and easy access to contact details.</p>
<p>At Tour raja, we specialize in offering travel agents a user-friendly space to showcase their brochures, upload their latest travel offerings, and manage essential contact details. Our platform is designed to make it easier for both travel agents and customers to find what they need, when they need it.</p>

<div class="mt-6 space-y-2"> 
    <h3 class="text-lg font-bold text-black">For Travel Agents:</h3>
    <p>We simplify the process of sharing your brochures with potential clients, providing a hassle-free solution for managing and displaying your travel products and services online. You can easily upload, update, and organize your brochures, making them accessible to your customers in just a few clicks.</p>
</div>

<div class="mt-6 space-y-2">
    <h3 class="text-lg font-bold text-black">For Customers:</h3>
    <p>Our platform makes it easier than ever to explore a wide range of travel options, from brochures to contact details. Whether you're planning your next vacation or researching travel services, you’ll find all the information you need in one place.</p>
</div>

<p class="mt-6">Our mission is to bridge the gap between travel agents and customers by providing a straightforward, efficient platform that saves time and enhances business opportunities. Whether you’re a travel agent looking to showcase your offerings or a customer seeking personalized travel information, Tour raja is here to make the process smooth and seamless for everyone.</p>

<p class="mt-4 font-bold text-black">Join Tour raja today and experience the convenience of connecting, sharing, and discovering travel options with ease.</p>
HTML;

\Illuminate\Support\Facades\DB::table('cms_pages')->where('slug', 'about-us')->update(['content' => $aboutContent]);
echo "Updated about-us content\n";
