<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InternMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->intern) {
            return redirect()->route('intern.login')->with('error', 'Unauthorized access');
        }
        
        return $next($request);
    }
}
