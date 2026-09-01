<?php
$files = [
    'c:/Users/tusha/Downloads/Tour_raja/resources/views/admin/plans-create.blade.php',
    'c:/Users/tusha/Downloads/Tour_raja/resources/views/admin/plans-edit.blade.php',
    'c:/Users/tusha/Downloads/Tour_raja/app/Http/Controllers/AdminController.php',
    'c:/Users/tusha/Downloads/Tour_raja/resources/views/agent/pages/payment.blade.php'
];

// 1. Update Create View
$createFile = $files[0];
$createContent = file_get_contents($createFile);
$createContent = str_replace(
    '<span class="text-xs font-bold text-gray-700">{{ $feat[\'label\'] }}</span>',
    '<div class="flex items-center gap-2">
                            <input type="checkbox" name="visible_features[]" value="{{ $feat[\'key\'] }}" class="w-4 h-4 text-[#ea580c] rounded border-gray-300 focus:ring-[#ea580c]" checked title="Show on pricing page">
                            <span class="text-xs font-bold text-gray-700">{{ $feat[\'label\'] }}</span>
                        </div>',
    $createContent
);
file_put_contents($createFile, $createContent);

// 2. Update Edit View
$editFile = $files[1];
$editContent = file_get_contents($editFile);
$editContent = str_replace(
    '<span class="text-xs font-bold text-gray-700">{{ $feat[\'label\'] }}</span>',
    '@php $visible = in_array($feat[\'key\'], json_decode($plan->features ?? \'[]\', true) ?? []); @endphp
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="visible_features[]" value="{{ $feat[\'key\'] }}" class="w-4 h-4 text-[#ea580c] rounded border-gray-300 focus:ring-[#ea580c]" {{ $visible ? \'checked\' : \'\' }} title="Show on pricing page">
                            <span class="text-xs font-bold text-gray-700">{{ $feat[\'label\'] }}</span>
                        </div>',
    $editContent
);
file_put_contents($editFile, $editContent);

// 3. Update Controller
$controllerFile = $files[2];
$controllerContent = file_get_contents($controllerFile);
$controllerContent = preg_replace(
    '/\$features = \[\];\s*if \(\$request->filled\(\'features\'\)\) \{\s*\$features = array_filter\(array_map\(\'trim\', explode\("\\\\n", \$request->features\)\)\);\s*\} else \{\s*\$features = \[\$request->package_limit \. \' package listings\'\];\s*\}/s',
    '$features = $request->input(\'visible_features\', []);',
    $controllerContent
);
file_put_contents($controllerFile, $controllerContent);

// 4. Update Payment View
$paymentFile = $files[3];
$paymentContent = file_get_contents($paymentFile);
$paymentContent = preg_replace(
    '/\$planPerms = \$plan->permissions \? \$plan->permissions->keyBy\(\'permission_key\'\) : collect\(\);\s*@endphp\s*@foreach\(\$features as \$feat\)/s',
    '$planPerms = $plan->permissions ? $plan->permissions->keyBy(\'permission_key\') : collect();
                        $visibleKeys = json_decode($plan->features, true) ?? [];
                    @endphp

                    @foreach($features as $feat)
                        @if(!in_array($feat[\'key\'], $visibleKeys)) @continue @endif',
    $paymentContent
);
file_put_contents($paymentFile, $paymentContent);

echo "Done";
