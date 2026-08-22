@extends('layouts.app')

@section('content')
<div class="bg-gray-50 pb-12" style="padding-top: 48px;">
    <div class="container-custom">
        <div class="w-full">
            <h1 class="font-black text-primary mb-6" style="font-size: 30px; line-height: 1.2;">{{ mb_strtoupper($page->title) }}</h1>
            
            <div class="prose prose-lg max-w-none text-text-muted cms-content">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</div>

<style>
    .cms-content h1 { font-size: 2.25rem; font-weight: 800; margin-bottom: 1rem; margin-top: 2rem; color: #1f2937; line-height: 1.2; }
    .cms-content h2 { font-size: 1.875rem; font-weight: 700; margin-bottom: 1rem; margin-top: 2rem; color: #374151; line-height: 1.3; }
    .cms-content h3 { font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem; margin-top: 1.5rem; color: #4b5563; }
    .cms-content p { margin-bottom: 1.25rem; line-height: 1.7; }
    .cms-content ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1.25rem; }
    .cms-content ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1.25rem; }
    .cms-content li { margin-bottom: 0.5rem; }
    .cms-content a { color: #e85d26; text-decoration: underline; }
    .cms-content strong { font-weight: 700; color: #111827; }
    .cms-content br { margin-bottom: 1rem; }
</style>
@endsection
