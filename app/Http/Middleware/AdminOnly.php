<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        // All authenticated users can access admin panel
        // Specific resource permissions are handled by policies
        return $next($request);
    }
}
