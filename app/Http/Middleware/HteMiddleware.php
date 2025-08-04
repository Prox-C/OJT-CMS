<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HteMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated and has an HTE relationship
        if (!auth()->check() || !auth()->user()->hte) {
            return redirect()->route('hte.login')->with('error', 'Unauthorized access - HTE account required');
        }
        
        
        return $next($request);
    }
}