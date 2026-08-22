@extends('layouts.app')

@section('content')
<div class="bg-gray-50 pb-12" style="padding-top: 48px;">
    <div class="container-custom">
        <div class="w-full">
            <h1 class="font-black text-primary mb-6" style="font-size: 30px; line-height: 1.2;">{{ mb_strtoupper($page->title) }}</h1>
            
            <div class="prose prose-lg max-w-none text-text-muted">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</div>
@endsection
