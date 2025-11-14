<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Category;

Route::get('/sitemap.xml', function () {
    $products = Product::all();
    $categories = Category::all();

    return response()->view('sitemap', compact('products', 'categories'))->header('Content-Type', 'text/xml');
});
