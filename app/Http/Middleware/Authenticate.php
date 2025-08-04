<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            if ($request->is('admin*')) {
                return route('admin.login');
            }
            if ($request->is('coordinator*')) {
                return route('coordinator.login');
            }
            if ($request->is('intern*')) {
                return route('intern.login');
            }
            if ($request->is('hte*')) {
                return route('hte.login');
            }
            return route('login');
        }
}
}
