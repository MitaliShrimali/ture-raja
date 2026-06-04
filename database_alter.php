<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "Altering packages table...\n";

Schema::table('packages', function (Blueprint $table) {
    if (!Schema::hasColumn('packages', 'gallery')) {
        $table->longText('gallery')->nullable();
        echo "Added gallery column.\n";
    }
    if (!Schema::hasColumn('packages', 'brochure')) {
        $table->string('brochure')->nullable();
        echo "Added brochure column.\n";
    }
    if (!Schema::hasColumn('packages', 'included')) {
        $table->text('included')->nullable();
        echo "Added included column.\n";
    }
    if (!Schema::hasColumn('packages', 'excluded')) {
        $table->text('excluded')->nullable();
        echo "Added excluded column.\n";
    }
    if (!Schema::hasColumn('packages', 'itinerary')) {
        $table->longText('itinerary')->nullable();
        echo "Added itinerary column.\n";
    }
});

echo "Alter completed successfully.\n";
