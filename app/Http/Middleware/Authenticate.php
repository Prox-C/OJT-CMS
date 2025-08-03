<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // Check if the route is admin-related
            if ($request->is('admin*')) {
                return route('admin.login');
            }
            if ($request->is('coordinator*')) {
                return route('coordinator.login');
            }
            if ($request->is('intern*')) {
                return route('intern.login');
            }
            return route('login'); // Default for other routes
        }
    }
}
