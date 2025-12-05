<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return redirect()->route('dashboard')
                    ->with('error', 'Access denied. Admin privileges required.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        // Get total orders
        $totalOrders = Order::count();

        // Get total revenue
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');

        // Get order data for chart (last 14 days)
        $orderData = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total_price) as revenue')
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates = $orderData->pluck('date');
        $orderCounts = $orderData->pluck('count');
        $revenues = $orderData->pluck('revenue');

        // Get Top 5 Best Selling Products (by Quantity)
        $topProducts = \Illuminate\Support\Facades\DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereIn('orders.status', ['paid', 'shipped', 'completed', 'done'])
            ->selectRaw('products.name, SUM(order_items.quantity) as total_sold')
            ->groupBy('products.name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $topProductNames = $topProducts->pluck('name');
        $topProductQuantities = $topProducts->pluck('total_sold');

        return view('admin.dashboard', compact(
            'totalOrders', 'totalRevenue', 'dates', 'orderCounts', 'revenues',
            'topProductNames', 'topProductQuantities'
        ));
    }

    public function getDashboardData()
    {
        // Get total orders
        $totalOrders = Order::count();

        // Get total revenue
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');

        // Get order data for chart (last 14 days)
        $orderData = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total_price) as revenue')
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $dates = $orderData->pluck('date');
        $orderCounts = $orderData->pluck('count');
        $revenues = $orderData->pluck('revenue');

        // Get Top 5 Best Selling Products (by Quantity)
        $topProducts = \Illuminate\Support\Facades\DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereIn('orders.status', ['paid', 'shipped', 'completed', 'done'])
            ->selectRaw('products.name, SUM(order_items.quantity) as total_sold')
            ->groupBy('products.name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $topProductNames = $topProducts->pluck('name');
        $topProductQuantities = $topProducts->pluck('total_sold');

        // Get recent orders HTML
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $recentOrdersHtml = '';
        foreach($recentOrders as $order) {
            $statusClass = match($order->status) {
                'completed' => 'bg-green-100 text-green-800',
                'pending' => 'bg-yellow-100 text-yellow-800',
                default => 'bg-gray-100 text-gray-800'
            };

            $recentOrdersHtml .= '<tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#'.$order->id.'</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">'.$order->user->name.'</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp '.number_format($order->total_price, 0, ',', '.').'</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full '.$statusClass.'">'.ucfirst($order->status).'</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">'.$order->created_at->format('d/m/Y H:i').'</td>
            </tr>';
        }

        // Get recent testimonials
        $recentTestimonials = \App\Models\Testimonial::with(['user', 'product'])->latest()->take(5)->get();
        $recentTestimonialsHtml = '';
        foreach($recentTestimonials as $testimonial) {
            $stars = '';
            for($i = 1; $i <= 5; $i++) {
                $color = $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300';
                $stars .= '<svg class="h-4 w-4 '.$color.' fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>';
            }

            $productName = $testimonial->product->name ?? 'Produk Dihapus';
            $userName = $testimonial->user->name ?? 'User Dihapus';
            $userAvatar = strtoupper(substr($userName, 0, 1));

            $recentTestimonialsHtml .= '<div class="flex space-x-4 p-4 hover:bg-gray-50 rounded-lg transition-colors border-b border-gray-100 last:border-0">
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                        '.$userAvatar.'
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-start">
                        <p class="text-sm font-medium text-gray-900 truncate">'.$userName.'</p>
                        <span class="text-xs text-gray-500">'.$testimonial->created_at->diffForHumans().'</span>
                    </div>
                    <p class="text-xs text-gray-500 mb-1">on <span class="font-medium text-indigo-600">'.$productName.'</span></p>
                    <div class="flex items-center mb-1">'.$stars.'</div>
                    <p class="text-sm text-gray-600 line-clamp-2">'.$testimonial->comment.'</p>
                </div>
            </div>';
        }

        return response()->json([
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'dates' => $dates,
            'orderCounts' => $orderCounts,
            'revenues' => $revenues,
            'topProductNames' => $topProductNames,
            'topProductQuantities' => $topProductQuantities,
            'recentOrdersHtml' => $recentOrdersHtml,
            'recentTestimonialsHtml' => $recentTestimonialsHtml
        ]);
    }
}
