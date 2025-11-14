<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orders Report</title>
    <style>
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        h1 { color: #333; font-size: 18px; }
        .header { margin-bottom: 20px; }
        .total-row { background-color: #e8f4f8; font-weight: bold; }
        .status-pending { color: #f59e0b; }
        .status-paid { color: #10b981; }
        .status-shipped { color: #3b82f6; }
        .status-done { color: #059669; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Seblak Umi AI - Orders Report</h1>
        <p>Generated on: {{ now()->format('Y-m-d H:i:s') }}</p>
        <p>Total Orders: {{ $orders->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total Amount</th>
                <th>Promo Discount</th>
                <th>Final Amount</th>
                <th>Status</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td>Rp {{ number_format($order->total_price ?? $order->total_amount, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($order->promo_discount ?? 0, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format(($order->total_price ?? $order->total_amount) - ($order->promo_discount ?? 0), 0, ',', '.') }}</td>
                    <td class="status-{{ $order->status }}">{{ ucfirst($order->status) }}</td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
            @if($orders->count() > 0)
                <tr class="total-row">
                    <td colspan="2"><strong>Total</strong></td>
                    <td><strong>Rp {{ number_format($orders->sum('total_price') ?? $orders->sum('total_amount'), 0, ',', '.') }}</strong></td>
                    <td><strong>Rp {{ number_format($orders->sum('promo_discount'), 0, ',', '.') }}</strong></td>
                    <td><strong>Rp {{ number_format(($orders->sum('total_price') ?? $orders->sum('total_amount')) - $orders->sum('promo_discount'), 0, ',', '.') }}</strong></td>
                    <td colspan="2"></td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
