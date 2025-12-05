<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function __invoke(Request $request)
    {
        $featuredProducts = Product::with('category')->take(6)->get();
        $testimonials = Testimonial::with('user')->take(3)->get();

        return view('welcome', compact('featuredProducts', 'testimonials'));
    }
}
