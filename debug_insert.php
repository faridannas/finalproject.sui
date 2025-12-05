<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

try {
    \Illuminate\Support\Facades\DB::table('order_items')->insert([
        'order_id' => 1,
        'product_id' => 1,
        'quantity' => 2,
        'subtotal' => 50000
    ]);
    echo "Success";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
