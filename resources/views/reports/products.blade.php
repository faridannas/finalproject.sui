<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Products Report</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        h1 { color: #333; font-size: 18px; }
        .header { margin-bottom: 20px; }
        .total-row { background-color: #e8f4f8; font-weight: bold; }
        .low-stock { color: #dc2626; }
        .out-of-stock { color: #991b1b; background-color: #fef2f2; }
        .description { max-width: 200px; word-wrap: break-word; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Seblak Umi AI - Products Report</h1>
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>Total Products: {{ $products->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>#{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td class="description">{{ Str::limit($product->desc ?? '', 100) }}</td>
                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="{{ $product->stock <= 0 ? 'out-of-stock' : ($product->stock <= 10 ? 'low-stock' : '') }}">{{ $product->stock }}</td>
                    <td>{{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}</td>
                </tr>
            @endforeach
            @if($products->count() > 0)
                <tr class="total-row">
                    <td colspan="4"><strong>Summary</strong></td>
                    <td><strong>Rp {{ number_format($products->sum(function($product) { return $product->price * $product->stock; }), 0, ',', '.') }}</strong></td>
                    <td><strong>{{ $products->sum('stock') }}</strong></td>
                    <td><strong>{{ $products->where('stock', '>', 0)->count() }} in stock</strong></td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
