<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated first
        if (!Auth::check()) {
            return redirect()->guest(route('admin.login'));
        }

        // Check if user has admin relation
        if (!Auth::user()->admin) {
            Auth::logout();
            return redirect()->route('admin.login')->withErrors([
                'access' => 'You are not authorized to access this area.'
            ]);
        }

        return $next($request);
    }
}