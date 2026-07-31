import re
import os

filepath = r'app/Http/Controllers/AdminController.php'
with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

# Add imports
if 'use App\\Models\\AgentMedia;' not in content:
    content = re.sub(r'(use Illuminate\\Support\\Facades\\Auth;)', r'\1\nuse App\\Models\\AgentMedia;\nuse Illuminate\\Support\\Facades\\File;', content)

methods = '''
    public function gallery(Request $request)
    {
        $agentId = 0; // Admin global gallery
        $parentId = $request->query('folder', null);

        // Current folder if any
        $currentFolder = null;
        $breadcrumbs = [];
        
        if ($parentId) {
            $currentFolder = AgentMedia::where('agent_id', $agentId)
                ->where('type', 'folder')
                ->where('id', $parentId)
                ->first();
                
            if (!$currentFolder) {
                return redirect()->route('admin.gallery')->with('error', 'Folder not found.');
            }

            // Build breadcrumbs
            $parent = $currentFolder;
            while ($parent) {
                array_unshift($breadcrumbs, $parent);
                $parent = $parent->parent;
            }
        }

        // Fetch contents
        $media = AgentMedia::where('agent_id', $agentId)
            ->where('parent_id', $parentId)
            ->orderBy('type') // Folders first
            ->orderBy('name')
            ->get();
            
        $folders = $media->where('type', 'folder');
        $images = $media->where('type', 'image');

        // All folders for the "Move to" dropdown
        $allFolders = AgentMedia::where('agent_id', $agentId)->where('type', 'folder')->orderBy('name')->get();

        return view('admin.gallery', [
            'page_title'      => 'Gallery',
            'page_breadcrumb' => 'Pages / Gallery',
            'folders'         => $folders,
            'images'          => $images,
            'currentFolder'   => $currentFolder,
            'breadcrumbs'     => $breadcrumbs,
            'allFolders'      => $allFolders,
        ]);
    }

    public function apiGallery(Request $request)
    {
        $agentId = 0;
        $parentId = $request->query('folder', null);

        $media = AgentMedia::where('agent_id', $agentId)
            ->where('parent_id', $parentId)
            ->orderBy('type') // Folders first
            ->orderBy('name')
            ->get();
            
        $folders = $media->where('type', 'folder')->values();
        $images = $media->where('type', 'image')->values();

        $breadcrumbs = [];
        if ($parentId) {
            $currentFolder = AgentMedia::where('agent_id', $agentId)
                ->where('type', 'folder')
                ->where('id', $parentId)
                ->first();
                
            if ($currentFolder) {
                $parent = $currentFolder;
                while ($parent) {
                    array_unshift($breadcrumbs, [
                        'id' => $parent->id,
                        'name' => $parent->name
                    ]);
                    $parent = $parent->parent;
                }
            }
        }

        return response()->json([
            'folders' => $folders,
            'images' => $images,
            'breadcrumbs' => $breadcrumbs
        ]);
    }


    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:agent_media,id'
        ]);

        $agentId = 0;
        // Check if parent folder belongs to admin
        if ($request->parent_id) {
            $parent = AgentMedia::where('id', $request->parent_id)->where('agent_id', $agentId)->first();
            if (!$parent) {
                return redirect()->back()->with('error', 'Invalid parent folder.');
            }
        }

        AgentMedia::create([
            'agent_id' => $agentId,
            'type' => 'folder',
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->back()->with('success', 'Folder created successfully!');
    }

    public function uploadMedia(Request $request)
    {
        $request->validate([
            'files.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'parent_id' => 'nullable|exists:agent_media,id'
        ]);

        $agentId = 0;
        
        if ($request->parent_id) {
            $parent = AgentMedia::where('id', $request->parent_id)->where('agent_id', $agentId)->first();
            if (!$parent) {
                return redirect()->back()->with('error', 'Invalid folder.');
            }
        }

        if ($request->hasFile('files')) {
            $uploadPath = public_path('uploads/admin_gallery');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0775, true);
            }

            foreach ($request->file('files') as $file) {
                if ($file->isValid()) {
                    $originalName = $file->getClientOriginalName();
                    $size = $file->getSize();
                    $mimeType = $file->getClientMimeType();
                    $fileName = time() . '_' . rand(1000, 9999) . '_' . $originalName;
                    
                    $file->move($uploadPath, $fileName);
                    
                    AgentMedia::create([
                        'agent_id'  => $agentId,
                        'type'      => 'image',
                        'name'      => $originalName,
                        'file_path' => 'uploads/admin_gallery/' . $fileName,
                        'size'      => $size,
                        'mime_type' => $mimeType,
                        'parent_id' => $request->parent_id,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Images uploaded successfully!');
    }

    public function moveMedia(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array',
            'target_folder_id' => 'nullable' // null means root
        ]);

        $agentId = 0;
        
        $targetId = $request->target_folder_id === 'root' ? null : $request->target_folder_id;

        if ($targetId) {
            $folder = AgentMedia::where('id', $targetId)->where('agent_id', $agentId)->where('type', 'folder')->first();
            if (!$folder) {
                return redirect()->back()->with('error', 'Target folder not found.');
            }
        }

        AgentMedia::whereIn('id', $request->selected_ids)
            ->where('agent_id', $agentId)
            ->update(['parent_id' => $targetId]);

        return redirect()->back()->with('success', 'Items moved successfully!');
    }

    public function deleteMedia(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|array'
        ]);

        $agentId = 0;
        
        $items = AgentMedia::whereIn('id', $request->selected_ids)
            ->where('agent_id', $agentId)
            ->get();

        foreach ($items as $item) {
            $this->deleteMediaItemRecursively($item);
        }

        return redirect()->back()->with('success', 'Selected items deleted successfully!');
    }

    private function deleteMediaItemRecursively(AgentMedia $item)
    {
        if ($item->isFolder()) {
            foreach ($item->children as $child) {
                $this->deleteMediaItemRecursively($child);
            }
        } else {
            // Delete physical file
            $filePath = public_path($item->file_path);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
        $item->delete();
    }
'''

# Insert methods before the closing brace of the class
# The class ends right before `// Reusable custom timing function for activity feed`
content = re.sub(r'(\n}\n\s*// Reusable custom timing function)', '\n' + methods + r'\1', content)

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)

print("Updated AdminController successfully")
