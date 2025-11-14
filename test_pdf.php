<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

echo "Testing PDF generation\n";

try {
    $orders = Order::with(['user', 'orderItems.product', 'payment'])->get();
    $pdf = Pdf::loadView('reports.orders', compact('orders'));
    echo "Orders PDF generated successfully\n";

    $products = Product::with('category')->get();
    $pdf2 = Pdf::loadView('reports.products', compact('products'));
    echo "Products PDF generated successfully\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
