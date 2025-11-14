<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('admin.dashboard');
    }
}