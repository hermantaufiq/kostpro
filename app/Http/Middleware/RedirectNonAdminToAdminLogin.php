<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectNonAdminToAdminLogin
{
    /**
     * Handle an incoming request.
     * If a user is authenticated but is NOT an admin, log them out
     * of the web guard and redirect to the admin login page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            // Check if the authenticated user can access admin panel
            $isAdmin = $user->user_type === 'admin'
                && $user->hasRole(['super_admin', 'admin_operasional', 'admin_keuangan']);

            if (!$isAdmin) {
                return redirect()->route('dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman Admin.');
            }
        }

        return $next($request);
    }
}
