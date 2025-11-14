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

        return view('products.index', compact('products'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('category', 'testimonials')->findOrFail($id);

        // SEO Meta Data
        $title = $product->name . ' - Seblak Premium | Seblak UMI';
        $description = 'Pesan ' . $product->name . ' - ' . Str::limit($product->desc, 150) . ' Harga: Rp ' . number_format($product->price, 0, ',', '.') . '. Seblak autentik dengan bahan premium!';
        $ogImage = $product->image ? asset('storage/' . $product->image) : asset('images/seblak-umi-og.jpg');

        return view('products.show', compact('product', 'title', 'description', 'ogImage'));
    }
}
