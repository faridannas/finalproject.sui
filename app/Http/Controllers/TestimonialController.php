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
        $testimonials = Testimonial::with('user', 'product')->latest()->paginate(10);

        // Check if this is an admin request
        if (request()->routeIs('admin.testimonials.index')) {
            return view('admin.testimonials.index', compact('testimonials'));
        }

        return view('testimonials.index', compact('testimonials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($productId)
    {
        // ... (kode create tetap sama)
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

        // Check if user has purchased the product and order is completed
        $hasPurchased = \App\Models\Order::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->whereHas('orderItems', function ($query) use ($request) {
                $query->where('product_id', $request->product_id);
            })
            ->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'Anda harus membeli produk ini dan menyelesaikan pesanan terlebih dahulu sebelum memberikan ulasan.');
        }

        Testimonial::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('testimonials.index')->with('success', 'Terima kasih atas ulasan Anda!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        // Allow admin to delete any testimonial, or user to delete their own
        if (!Auth::user()->is_admin && $testimonial->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $testimonial->delete();

        return redirect()->back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
