<?php

$pagesDir = __DIR__ . '/tra/pages';
$destDir = __DIR__ . '/resources/views/agent/pages';

if (!is_dir($destDir)) {
    mkdir($destDir, 0777, true);
}

$files = glob($pagesDir . '/*.php');

foreach ($files as $file) {
    $content = file_get_contents($file);
    $basename = basename($file, '.php');
    
    // Remove header include
    $content = preg_replace('/<\?php\s+\$base_path\s*=\s*[\'"]\.\.\/[\'"];\s*include\s*[\'"]\.\.\/components\/header\.php[\'"];\s*\?>/s', '', $content);
    $content = preg_replace('/<\?php\s*include\s*[\'"]\.\.\/components\/header\.php[\'"];\s*\?>/s', '', $content);
    
    // Remove sidebar, main, navbar opens
    $content = preg_replace('/<div class="flex min-h-screen bg-gray-50">\s*<!-- Sidebar -->\s*<\?php include \'[^(]+\/components\/sidebar\.php\'; \?>\s*<!-- Main Content -->\s*<main[^>]+>\s*<!-- Navbar -->\s*<\?php include \'[^(]+\/components\/navbar\.php\'; \?>/s', '', $content);
    
    // Remove footer, main, div closes
    $content = preg_replace('/<footer.*?<\/footer>\s*<\/main>\s*<\/div>\s*<\?php include \'[^(]+\/components\/footer\.php\'; \?>/s', '', $content);

    // Some pages might not have matched perfectly, let's also remove standalone includes just in case
    $content = preg_replace('/<\?php include \'[^(]+\/components\/(sidebar|navbar|footer|header)\.php\'; \?>/s', '', $content);

    // Replace <div class="flex min-h-screen bg-gray-50">
    $content = preg_replace('/<div class="flex min-h-screen bg-gray-50">/s', '', $content);
    $content = preg_replace('/<main class="flex-grow min-w-0 ml-0 lg:ml-72 p-4 sm:p-6 lg:p-8 overflow-x-hidden transition-all duration-300">/s', '', $content);

    // Wrap in layout
    $title = ucwords(str_replace('-', ' ', $basename));
    $bladeContent = "@extends('agent.layouts.app')\n\n@section('title', '{$title} - Tour Raja Agent')\n\n@section('content')\n" . trim($content) . "\n@endsection\n";

    // Clean up extra closing tags if they didn't match the regex
    $bladeContent = str_replace("</main>\n</div>", "", $bladeContent);

    // Replace static image paths
    $bladeContent = str_replace('../assets/images/', "{{ asset('agent/assets/images/') }}/", $bladeContent);

    file_put_contents($destDir . '/' . $basename . '.blade.php', $bladeContent);
    echo "Converted: " . $basename . "\n";
}

echo "All done!\n";
