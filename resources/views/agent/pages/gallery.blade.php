@extends('agent.layouts.app')

@section('title', 'Gallery - Tour Raja Agent')

@section('content')
<div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-xs text-gray-400 font-medium">Pages / Gallery</p>
                <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Gallery</h2>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-100 flex items-center mb-12">
            <div class="flex-grow flex items-center px-4">
                <i class="fas fa-search text-gray-300 mr-3"></i>
                <input type="text" placeholder="Search Image" class="w-full bg-transparent border-none outline-none text-sm text-gray-600 placeholder:text-gray-300">
            </div>
            <button class="bg-primary text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-lg shadow-orange-100">
                <i class="fas fa-search"></i>
            </button>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-8">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Uploaded images</h3>
                <p class="text-[10px] text-gray-400 font-medium">40 Images</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <p class="text-[9px] font-bold text-gray-400 uppercase mr-2 tracking-widest w-full sm:w-auto mb-2 sm:mb-0">40 Images Selected <span class="text-primary ml-2 cursor-pointer">Clear Selection</span></p>
                <button class="bg-white border border-gray-100 px-4 py-2 rounded-lg text-[10px] font-bold text-gray-500 flex items-center hover:bg-gray-50 transition-all"><i class="fas fa-folder-plus mr-2"></i> Add to folder</button>
                <button class="bg-white border border-red-50 px-4 py-2 rounded-lg text-[10px] font-bold text-red-400 flex items-center hover:bg-red-50 transition-all"><i class="far fa-trash-alt mr-2"></i> Delete</button>
                <button class="bg-white border border-gray-100 p-2 rounded-lg text-gray-400 hover:bg-gray-50 transition-all"><i class="fas fa-th-large"></i></button>
                <button class="bg-primary text-white px-6 py-2 rounded-lg text-[10px] font-bold flex items-center shadow-lg shadow-orange-100 hover:scale-105 transition-all">+ Add Image</button>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            <?php 
            $images = [
                ['img' => 'https://images.unsplash.com/photo-1513836279014-a89f7a76ae86?q=80&w=300', 'name' => 'Image1.png', 'checked' => true],
                ['img' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?q=80&w=300', 'name' => 'Image1.png', 'checked' => false],
                ['img' => 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?q=80&w=300', 'name' => 'Image3.png', 'checked' => true],
                ['img' => 'https://images.unsplash.com/photo-1470770841072-f978cf4d019e?q=80&w=300', 'name' => 'Image4.png', 'checked' => true],
                ['img' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?q=80&w=300', 'name' => 'Image5.png', 'checked' => true],
                ['img' => 'https://images.unsplash.com/photo-1511884642898-4c92249e20b6?q=80&w=300', 'name' => 'Image8.png', 'checked' => true],
                ['img' => 'https://images.unsplash.com/photo-1502082553048-f009c37129b9?q=80&w=300', 'name' => 'Image7.png', 'checked' => true],
                ['img' => 'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?q=80&w=300', 'name' => 'Image12.png', 'checked' => true],
                ['img' => 'https://images.unsplash.com/photo-1518495973542-4542c06a5843?q=80&w=300', 'name' => 'Image5.png', 'checked' => true],
                ['img' => 'https://images.unsplash.com/photo-1501854140801-50d01698950b?q=80&w=300', 'name' => 'Image8.png', 'checked' => true],
                ['img' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=300', 'name' => 'Image7.png', 'checked' => true],
                ['img' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?q=80&w=300', 'name' => 'Image8.png', 'checked' => true],
                ['img' => 'https://images.unsplash.com/photo-1447752875215-b2761acb3c5d?q=80&w=300', 'name' => 'Image9.png', 'checked' => true],
                ['img' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=300', 'name' => 'Image10.png', 'checked' => true],
                ['img' => 'https://images.unsplash.com/photo-1512443191864-1418f1ffac2c?q=80&w=300', 'name' => 'Image11.png', 'checked' => true],
            ];
            foreach($images as $img): ?>
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 group relative">
                <div class="relative aspect-[4/3] overflow-hidden">
                    <img src="<?php echo $img['img']; ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-3 left-3">
                        <div class="w-4 h-4 rounded bg-white flex items-center justify-center border border-gray-200">
                            <?php if($img['checked']): ?>
                            <div class="w-3 h-3 bg-blue-600 rounded-sm flex items-center justify-center text-[8px] text-white"><i class="fas fa-check"></i></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="p-3">
                    <p class="text-[9px] font-bold text-gray-800"><?php echo $img['name']; ?></p>
                    <p class="text-[7px] text-gray-400 font-medium uppercase mt-0.5">Mar 11, 2026</p>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Upload Placeholder -->
            <div class="bg-gray-100/50 rounded-2xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center p-6 cursor-pointer hover:bg-gray-100 transition-all text-center">
                <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-gray-300 mb-3"><i class="fas fa-plus"></i></div>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Supports:</p>
                <p class="text-[7px] text-gray-300 font-medium mt-1 uppercase">PNG, JPG, JPEG, WebP</p>
            </div>
        </div>

        <!-- Footer -->
@endsection
