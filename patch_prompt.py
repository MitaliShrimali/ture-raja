import os
import re

files = [
    'resources/views/agent/pages/create-package.blade.php',
    'resources/views/agent/pages/edit-package.blade.php',
    'resources/views/admin/packages-create.blade.php',
    'resources/views/admin/packages-edit.blade.php'
]

for file in files:
    if os.path.exists(file):
        with open(file, 'r', encoding='utf-8') as f:
            content = f.read()
            
        # 1. change `function itineraryFormat(type) {` to `async function itineraryFormat(type) {`
        content = content.replace('function itineraryFormat(type) {', 'async function itineraryFormat(type) {')
        
        # 2. change `const url = prompt('Enter URL (e.g. https://example.com):');` to `const url = await window.customPrompt('Enter URL (e.g. https://example.com):');`
        content = content.replace("prompt('Enter URL (e.g. https://example.com):');", "await window.customPrompt('Enter URL (e.g. https://example.com):');")
        
        with open(file, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f'Updated {file}')
