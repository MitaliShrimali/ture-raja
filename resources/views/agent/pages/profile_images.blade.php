@extends('agent.layouts.app')

@section('title', 'Profile Images - Tour Raja Agent')
@section('content')
<div class="px-6 py-8">
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Profile Images</h2>
            <p class="text-sm font-medium text-gray-500 mt-1">Upload images to showcase on your agent profile.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 flex items-center gap-3">
            <i class="fas fa-check-circle"></i>
            <p class="text-sm font-bold">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-100 flex items-center gap-3">
            <i class="fas fa-exclamation-circle"></i>
            <p class="text-sm font-bold">{{ session('error') }}</p>
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-100">
            <ul class="list-disc pl-5 text-sm font-bold">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100 mb-8">
        <form action="{{ route('agent.profile-images.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4 max-w-md">
            @csrf
            <div>
                <label class="block text-[9px] font-bold text-gray-400 uppercase mb-2 tracking-widest">Select Image <span class="text-red-500">*</span></label>
                <input type="file" name="image" required accept="image/*" class="w-full px-5 py-3.5 rounded-xl bg-gray-50 border border-gray-100 text-xs font-medium focus:ring-2 focus:ring-primary/20">
            </div>
            <button type="submit" class="px-6 py-3 bg-primary hover:bg-orange-600 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all w-fit">Upload Image</button>
        </form>
    </div>

    <div class="bg-white p-8 rounded-[32px] shadow-sm border border-gray-100">
        <div class="flex items-center mb-6">
            <div class="w-1.5 h-6 bg-orange-800 rounded-full mr-4"></div>
            <h4 class="text-lg font-bold text-gray-800 tracking-tight">Uploaded Images</h4>
        </div>

        @if($images->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($images as $img)
                    <div class="relative group rounded-2xl overflow-hidden border border-gray-200">
                        <img src="{{ asset($img->image_path) }}" alt="Profile Image" class="w-full h-48 object-cover">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <a href="{{ route('agent.profile-images.delete', $img->id) }}" onclick="return confirm('Are you sure you want to delete this image?')" class="w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                    <i class="fas fa-images text-2xl"></i>
                </div>
                <p class="text-sm font-medium text-gray-500">No profile images uploaded yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection
