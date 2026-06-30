@extends('agent.layouts.app')

@section('title', 'Gallery - Tour Raja Agent')

@section('content')

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul class="list-disc pl-5 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 mb-6 text-sm text-gray-500 font-medium">
        <a href="{{ route('agent.gallery') }}" class="hover:text-primary transition-colors"><i class="fas fa-home"></i> Gallery</a>
        @foreach($breadcrumbs as $crumb)
            <span>/</span>
            <a href="{{ route('agent.gallery', ['folder' => $crumb->id]) }}" class="hover:text-primary transition-colors">{{ $crumb->name }}</a>
        @endforeach
    </div>

    <!-- Search Bar (Placeholder for now) -->
    <div class="bg-white p-2 rounded-2xl shadow-sm border border-gray-100 flex items-center mb-8">
        <div class="flex-grow flex items-center px-4">
            <i class="fas fa-search text-gray-300 mr-3"></i>
            <input type="text" placeholder="Search Image" class="w-full bg-transparent border-none outline-none text-sm text-gray-600 placeholder:text-gray-300">
        </div>
        <button class="bg-primary text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-lg shadow-orange-100">
            <i class="fas fa-search"></i>
        </button>
    </div>

    <form id="gallery-form" method="POST">
        @csrf
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-8">
            <div>
                <h3 class="text-lg font-bold text-gray-800">{{ $currentFolder ? $currentFolder->name : 'Uploaded images' }}</h3>
                <p class="text-[10px] text-gray-400 font-medium">{{ $images->count() }} Images, {{ $folders->count() }} Folders</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <p class="text-[9px] font-bold text-gray-400 uppercase mr-2 tracking-widest w-full sm:w-auto mb-2 sm:mb-0 hidden" id="selection-count-text">
                    <span id="selected-count">0</span> Items Selected 
                    <span class="text-primary ml-2 cursor-pointer" onclick="clearSelection()">Clear Selection</span>
                </p>
                
                <div class="relative group hidden" id="move-dropdown">
                    <button type="button" class="bg-white border border-gray-100 px-4 py-2 rounded-lg text-[10px] font-bold text-gray-500 flex items-center hover:bg-gray-50 transition-all">
                        <i class="fas fa-folder-plus mr-2"></i> Move to folder
                    </button>
                    <div class="absolute right-0 pt-2 w-48 hidden group-hover:block z-50">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                            <ul class="py-2 text-sm text-gray-700">
                                @if($currentFolder)
                                    <li>
                                        <button type="button" onclick="moveItems('root')" class="block w-full text-left px-4 py-2 hover:bg-gray-50">Root Gallery</button>
                                    </li>
                                @endif
                                @foreach($allFolders as $af)
                                    @if(!$currentFolder || $af->id != $currentFolder->id)
                                        <li>
                                            <button type="button" onclick="moveItems({{ $af->id }})" class="block w-full text-left px-4 py-2 hover:bg-gray-50">
                                                <i class="fas fa-folder text-yellow-400 mr-2"></i> {{ $af->name }}
                                            </button>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="target_folder_id" id="target_folder_id">

                <button type="button" onclick="deleteSelected()" id="btn-delete" class="hidden bg-white border border-red-50 px-4 py-2 rounded-lg text-[10px] font-bold text-red-400 flex items-center hover:bg-red-50 transition-all">
                    <i class="far fa-trash-alt mr-2"></i> Delete
                </button>
                
                <button type="button" onclick="document.getElementById('folder-modal').classList.remove('hidden')" class="bg-white border border-gray-100 px-4 py-2 rounded-lg text-[10px] font-bold text-gray-500 flex items-center hover:bg-gray-50 transition-all">
                    <i class="fas fa-folder-plus mr-2"></i> New Folder
                </button>

                <button type="button" onclick="document.getElementById('upload-modal').classList.remove('hidden')" class="bg-primary text-white px-6 py-2 rounded-lg text-[10px] font-bold flex items-center shadow-lg shadow-orange-100 hover:scale-105 transition-all">
                    + Add Image
                </button>
            </div>
        </div>

        <!-- Gallery Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            
            <!-- Render Folders first -->
            @foreach($folders as $folder)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 group relative">
                    <a href="{{ route('agent.gallery', ['folder' => $folder->id]) }}" class="block p-6 text-center hover:bg-gray-50 transition-colors">
                        <i class="fas fa-folder text-yellow-400 text-5xl mb-3 group-hover:scale-110 transition-transform"></i>
                        <p class="text-[11px] font-bold text-gray-800 truncate">{{ $folder->name }}</p>
                    </a>
                    <div class="absolute top-2 left-2 z-10">
                        <input type="checkbox" name="selected_ids[]" value="{{ $folder->id }}" class="item-checkbox w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/20 cursor-pointer shadow-sm bg-white" onchange="updateSelection()">
                    </div>
                </div>
            @endforeach

            <!-- Render Images -->
            @foreach($images as $img)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 group relative">
                    <div class="relative aspect-[4/3] overflow-hidden">
                        <img src="{{ asset($img->file_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-2 left-2 z-10">
                            <input type="checkbox" name="selected_ids[]" value="{{ $img->id }}" class="item-checkbox w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/20 cursor-pointer shadow-sm bg-white" onchange="updateSelection()">
                        </div>
                    </div>
                    <div class="p-3">
                        <p class="text-[9px] font-bold text-gray-800 truncate" title="{{ $img->name }}">{{ $img->name }}</p>
                        <p class="text-[7px] text-gray-400 font-medium uppercase mt-0.5">{{ number_format($img->size / 1024, 1) }} KB</p>
                    </div>
                </div>
            @endforeach

            @if($folders->isEmpty() && $images->isEmpty())
                <div class="col-span-full py-12 text-center text-gray-400">
                    <i class="fas fa-folder-open text-4xl mb-3 opacity-50"></i>
                    <p class="text-sm font-bold">This folder is empty.</p>
                </div>
            @endif

        </div>
    </form>


    <!-- Modals -->

    <!-- Create Folder Modal -->
    <div id="folder-modal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-[32px] p-8 w-full max-w-md shadow-2xl relative">
            <button onclick="document.getElementById('folder-modal').classList.add('hidden')" class="absolute top-6 right-6 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors">
                <i class="fas fa-times"></i>
            </button>
            <h3 class="text-xl font-black text-gray-800 mb-6 font-syne">Create New Folder</h3>
            <form action="{{ route('agent.gallery.create-folder') }}" method="POST">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $currentFolder ? $currentFolder->id : '' }}">
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Folder Name</label>
                        <input type="text" name="name" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none" placeholder="e.g. Summer 2026">
                    </div>
                </div>
                <div class="mt-8">
                    <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white rounded-xl py-3 font-bold text-sm transition-all shadow-lg shadow-primary/30">Create Folder</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Upload Image Modal -->
    <div id="upload-modal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center backdrop-blur-sm">
        <div class="bg-white rounded-[32px] p-8 w-full max-w-lg shadow-2xl relative">
            <button onclick="document.getElementById('upload-modal').classList.add('hidden')" class="absolute top-6 right-6 w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors">
                <i class="fas fa-times"></i>
            </button>
            <h3 class="text-xl font-black text-gray-800 mb-6 font-syne">Upload Images</h3>
            <form action="{{ route('agent.gallery.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $currentFolder ? $currentFolder->id : '' }}">
                
                <div class="border-2 border-dashed border-gray-200 rounded-2xl p-10 text-center bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer relative">
                    <input type="file" name="files[]" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" id="file-input" required onchange="updateFileName(this)">
                    <i class="fas fa-cloud-upload-alt text-4xl text-primary mb-4"></i>
                    <p class="text-sm font-bold text-gray-800">Click or drag images to upload</p>
                    <p class="text-[10px] text-gray-400 mt-2 uppercase tracking-widest" id="file-name-display">Supports JPG, PNG, WEBP (Max 5MB)</p>
                </div>

                <div class="mt-8">
                    <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white rounded-xl py-3 font-bold text-sm transition-all shadow-lg shadow-primary/30">Upload Now</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script for Selection Logic -->
    <script>
        function updateFileName(input) {
            const display = document.getElementById('file-name-display');
            if (input.files && input.files.length > 0) {
                display.innerText = input.files.length + " file(s) selected";
            } else {
                display.innerText = "Supports JPG, PNG, WEBP (Max 5MB)";
            }
        }

        function updateSelection() {
            const checkboxes = document.querySelectorAll('.item-checkbox:checked');
            const count = checkboxes.length;
            
            const countText = document.getElementById('selection-count-text');
            const moveDropdown = document.getElementById('move-dropdown');
            const btnDelete = document.getElementById('btn-delete');
            const countDisplay = document.getElementById('selected-count');

            if (count > 0) {
                countDisplay.innerText = count;
                countText.classList.remove('hidden');
                moveDropdown.classList.remove('hidden');
                btnDelete.classList.remove('hidden');
            } else {
                countText.classList.add('hidden');
                moveDropdown.classList.add('hidden');
                btnDelete.classList.add('hidden');
            }
        }

        function clearSelection() {
            const checkboxes = document.querySelectorAll('.item-checkbox');
            checkboxes.forEach(cb => cb.checked = false);
            updateSelection();
        }

        function moveItems(folderId) {
            document.getElementById('target_folder_id').value = folderId;
            const form = document.getElementById('gallery-form');
            form.action = "{{ route('agent.gallery.move') }}";
            form.submit();
        }

        function deleteSelected() {
            if(confirm("Are you sure you want to delete the selected items? (Folders will be deleted with all their contents)")) {
                const form = document.getElementById('gallery-form');
                form.action = "{{ route('agent.gallery.delete') }}";
                form.submit();
            }
        }
    </script>
@endsection
