<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testimonials = Testimonial::with('user', 'product')->paginate(10);
        return view('testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($productId)
    {
        $product = Product::findOrFail($productId);
        // Check if user has purchased this product
        $hasPurchased = Auth::user()->orders()
            ->whereHas('orderItems', function($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->where('status', 'done')
            ->exists();

        if (!$hasPurchased) {
            return redirect()->back()->with('error', 'You can only review products you have purchased.');
        }

        return view('testimonials.create', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        $productId = $request->product_id;

        // Check if user has already reviewed this product
        $existing = Testimonial::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'You have already reviewed this product.');
        }

        // Check if user has purchased this product
        $hasPurchased = Auth::user()->orders()
            ->whereHas('orderItems', function($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->where('status', 'done')
            ->exists();

        if (!$hasPurchased) {
            return redirect()->back()->with('error', 'You can only review products you have purchased.');
        }

        Testimonial::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('products.show', $productId)->with('success', 'Review submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $testimonial = Testimonial::where('user_id', Auth::id())->findOrFail($id);
        return view('testimonials.edit', compact('testimonial'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $testimonial = Testimonial::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        $testimonial->update($request->only(['rating', 'comment']));

        return redirect()->route('products.show', $testimonial->product_id)->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $testimonial = Testimonial::where('user_id', Auth::id())->findOrFail($id);
        $testimonial->delete();

        return redirect()->back()->with('success', 'Review deleted successfully.');
    }
}
