@extends('layouts.admin')

@section('admin_title', 'Edit Page - ' . $page->title)

@section('content')
<div class="space-y-10 pb-12">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="space-y-2">
            <h2 class="font-black text-foreground tracking-tight">Edit Page Content</h2>
            <p class="text-muted-text font-medium">Update the details for <span class="text-primary">{{ $page->title }}</span>.</p>
        </div>
        <a href="{{ url('/admin/cms') }}" class="bg-gray-100 hover:bg-gray-200 text-muted-text px-8 py-4 rounded-2xl font-black text-sm transition-all flex items-center gap-3">
            <i data-lucide="arrow-left" size="20"></i> Back to Pages
        </a>
    </div>

    <div class="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden p-10">
        <form action="{{ url('/admin/cms/update') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="id" value="{{ $page->id }}">
            
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Page Title<span class="text-primary">*</span></label>
                    <input required type="text" name="title" value="{{ $page->title }}" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">URL Slug (Read-only)</label>
                    <input type="text" value="{{ $page->slug }}" readonly class="w-full bg-gray-100 border-none rounded-2xl py-4 px-6 outline-none text-muted-text cursor-not-allowed font-medium shadow-inner" />
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Status</label>
                <select name="status" class="w-full bg-gray-50 border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-foreground shadow-sm appearance-none">
                    <option value="Published" {{ $page->status == 'Published' ? 'selected' : '' }}>Published</option>
                    <option value="Draft" {{ $page->status == 'Draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            
            <div class="space-y-2">
                <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Content Editor</label>
                <input type="hidden" name="content" id="content-input">
                <div id="editor-container" class="w-full bg-gray-50 border-none rounded-2xl p-4 min-h-[300px]">
                    {!! $page->content !!}
                </div>
            </div>
            
            <div class="flex items-center gap-4 pt-4 border-t border-border-soft">
                <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white font-black py-4 rounded-2xl transition-all shadow-xl shadow-primary/20">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link', 'clean']
                ]
            }
        });

        document.querySelector('form').onsubmit = function() {
            var contentInput = document.getElementById('content-input');
            contentInput.value = quill.root.innerHTML;
        };
    });
</script>
<style>
    .ql-toolbar.ql-snow {
        border: none;
        background-color: #f9fafb;
        border-radius: 1rem 1rem 0 0;
        border-bottom: 1px solid #e5e7eb;
    }
    .ql-container.ql-snow {
        border: none;
        border-radius: 0 0 1rem 1rem;
        background-color: #f9fafb;
        font-family: inherit;
        font-size: 1rem;
    }
    .ql-editor {
        min-height: 300px;
    }
</style>
@endsection
