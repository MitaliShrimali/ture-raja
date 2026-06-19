import os

files = [
    'resources/views/layouts/admin.blade.php',
    'resources/views/layouts/app.blade.php'
]

for file in files:
    if os.path.exists(file):
        with open(file, 'r', encoding='utf-8') as f:
            content = f.read()
            
        if 'confirm-interceptor.js' not in content:
            # add it right after sweetalert2
            if 'sweetalert2' in content:
                content = content.replace(
                    '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>',
                    '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>\n    <script src="{{ asset(\'js/confirm-interceptor.js\') }}"></script>'
                )
            else:
                # add both before </body>
                content = content.replace(
                    '</body>',
                    '    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>\n    <script src="{{ asset(\'js/confirm-interceptor.js\') }}"></script>\n</body>'
                )
            
            with open(file, 'w', encoding='utf-8') as f:
                f.write(content)
            print(f'Updated {file}')
