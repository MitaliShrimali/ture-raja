import re

filepath = r'resources/views/admin/gallery.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace("@extends('agent.layouts.app')", "@extends('layouts.admin')")
content = content.replace("route('agent.gallery", "route('admin.gallery")

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)

print("Updated admin gallery blade successfully")
