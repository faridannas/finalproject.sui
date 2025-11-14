<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        if ($user->role !== $role) {
            return redirect()
                ->route('dashboard')
                ->with('error', 'Access denied. You do not have the required role.');
        }

        return $next($request);
    }
}