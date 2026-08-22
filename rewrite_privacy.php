<?php
$content = file_get_contents('resources/views/privacy-policy.blade.php');
preg_match('/<div class="prose prose-lg max-w-none text-text-muted">(.*?)<\/div>\s*<\/div>\s*<\/div>\s*<!-- Newsletter Section -->/s', $content, $m);
if (isset($m[1])) {
    $html = trim($m[1]);
    DB::table('cms_pages')->updateOrInsert(
        ['slug' => 'privacy-policy'],
        ['title' => 'Privacy Policy', 'content' => $html, 'status' => 'Published', 'created_at' => now(), 'updated_at' => now()]
    );
}
