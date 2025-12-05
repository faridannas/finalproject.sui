<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Category filter
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }

        $products = $query->paginate(12);
        $categories = Category::all();
        $title = 'Menu Seblak UMI - Varian Seblak Prasmanan Terlengkap';
        $description = 'Jelajahi aneka pilihan menu seblak dan topping di Seblak UMI. Nikmati sensasi seblak prasmanan dengan resep autentik dan bahan-bahan premium.';
        return view('products.index', compact('products', 'categories', 'title', 'description'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $product = Product::with('category', 'testimonials.user')->findOrFail($id);

        // SEO Meta Data
        $title = $product->name . ' - Seblak Premium | Seblak UMI';
        $description = 'Pesan ' . $product->name . ' - ' . Str::limit($product->desc, 150) . ' Harga: Rp ' . number_format($product->price, 0, ',', '.') . '. Seblak autentik dengan bahan premium!';
        $ogImage = $product->image ? asset('storage/' . $product->image) : asset('images/seblak-umi-og.jpg');

        // Log the request (assuming logger is set up elsewhere, but since it's not, we'll skip for now)
        // logger->info("Request received: " . $request->method() . " " . $request->path());

        return view('products.show', compact('product', 'title', 'description', 'ogImage', 'request'));
    }
}
