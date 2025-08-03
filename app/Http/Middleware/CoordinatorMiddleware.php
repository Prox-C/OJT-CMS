<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CoordinatorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and is a coordinator
        if (!Auth::check() || !Auth::user()->coordinator) {
            return redirect()->route('coordinator.login')->with('error', 'Unauthorized access');
        }

        return $next($request);
    }
}