import os
import glob
import re

directory = r"c:\Users\tusha\Downloads\Tour_raja\resources\views\admin"

for filepath in glob.glob(os.path.join(directory, "*.blade.php")):
    with open(filepath, "r", encoding="utf-8") as f:
        content = f.read()

    # Only process if it extends layouts.admin and doesn't already have admin_title
    if "@extends('layouts.admin')" in content and "@section('admin_title'" not in content:
        # Generate title from filename
        basename = os.path.basename(filepath).replace(".blade.php", "")
        # e.g., "packages-create" -> "Packages Create"
        title = " ".join([word.capitalize() for word in basename.split("-")])
        
        # Replace the first occurrence of @extends('layouts.admin')
        replacement = f"@extends('layouts.admin')\n\n@section('admin_title', '{title}')"
        new_content = content.replace("@extends('layouts.admin')", replacement, 1)
        
        with open(filepath, "w", encoding="utf-8") as f:
            f.write(new_content)
        print(f"Updated {basename}.blade.php")
