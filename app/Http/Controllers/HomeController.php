<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class HomeController extends Controller
{
    /**
     * Show the user dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();

        $cartCount = Cart::where('user_id', $user->id)->sum('quantity');
        
        // Fetch active promos
        $promos = \App\Models\Promo::where('valid_until', '>=', now())->get();

        return view('dashboard', compact('user', 'cartCount', 'promos'));
    }
}
