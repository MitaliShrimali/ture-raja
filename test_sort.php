<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$agentsMap = \Illuminate\Support\Facades\DB::table('agents')
    ->select('id', 'name', 'service_guaranteed', 'plan_id')
    ->get()
    ->keyBy(function($item) {
        return strtolower(trim($item->name));
    })
    ->toArray();

$packages = \App\Models\Package::where('status', 'Active')->get();
$packages = $packages->shuffle()->sortByDesc(function ($p) use ($agentsMap) {
    $pkg = (array) $p->toArray();
    $tier = 1; // Default: unpaid / basic account
    
    // 3: Ad Placement
    if (!empty($pkg['ad_placement'])) {
        $tier = max($tier, 2);
    }
    
    // 2: Boosted Package
    if (!empty($pkg['is_boosted'])) {
        $tier = max($tier, 3);
    }
    
    $agentName = null;
    $agentData = $pkg['agent'] ?? null;
    if (is_string($agentData)) {
        $decoded = json_decode($agentData, true);
        if (is_array($decoded) && isset($decoded['name'])) {
            $agentName = $decoded['name'];
        } else {
            $agentName = $agentData;
        }
    } elseif (is_array($agentData) && isset($agentData['name'])) {
        $agentName = $agentData['name'];
    } elseif (is_object($agentData) && isset($agentData->name)) {
        $agentName = $agentData->name;
    }
    
    $agentKey = $agentName ? strtolower(trim($agentName)) : null;
    if ($agentKey && isset($agentsMap[$agentKey])) {
        $agentInfo = $agentsMap[$agentKey];
        // 2: Paid plan
        if (!empty($agentInfo->plan_id) && $agentInfo->plan_id > 1) {
            $tier = max($tier, 3);
        }
        // 1: Verified / Service Guaranteed (Top priority)
        if (!empty($agentInfo->service_guaranteed)) {
            $tier = max($tier, 4);
        }
    }
    return $tier;
});

foreach($packages->take(10) as $p) {
    $a = is_string($p['agent']) ? json_decode($p['agent'], true) : $p['agent'];
    $name = $a['name'] ?? 'N/A';
    echo 'ID: ' . ($p['id']??'') . ' | Name: ' . $name . "\n";
}
