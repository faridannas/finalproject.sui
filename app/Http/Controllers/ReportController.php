<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function exportOrders(Request $request)
    {
        try {
            $query = Order::with(['user', 'orderItems.product', 'payment']);

            // Apply filters if provided
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', Carbon::parse($request->start_date));
            }

            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', Carbon::parse($request->end_date));
            }

            if ($request->filled('date')) {
                $query->whereDate('created_at', $request->date);
            }

            $orders = $query->orderBy('created_at', 'desc')->get();

            if ($orders->isEmpty()) {
                return back()->with('error', 'No orders found for the selected criteria.');
            }

            $pdf = Pdf::loadView('reports.orders', compact('orders'));

            $filename = 'orders_report_' . now()->format('Y-m-d_H-i-s') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate orders report: ' . $e->getMessage());
        }
    }

    public function exportProducts(Request $request)
    {
        try {
            $query = Product::with('category');

            // Apply filters if provided
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            if ($request->filled('stock_status')) {
                if ($request->stock_status === 'in_stock') {
                    $query->where('stock', '>', 0);
                } elseif ($request->stock_status === 'out_of_stock') {
                    $query->where('stock', '=', 0);
                } elseif ($request->stock_status === 'low_stock') {
                    $query->where('stock', '>', 0)->where('stock', '<=', 10);
                }
            }

            $products = $query->orderBy('name')->get();

            if ($products->isEmpty()) {
                return back()->with('error', 'No products found for the selected criteria.');
            }

            $pdf = Pdf::loadView('reports.products', compact('products'));

            $filename = 'products_report_' . now()->format('Y-m-d_H-i-s') . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate products report: ' . $e->getMessage());
        }
    }
}
