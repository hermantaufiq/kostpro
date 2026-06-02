<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class LogAdminActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Log admin activities after request completes
        if ($request->user() && $request->user()->user_type === 'admin') {
            try {
                if ($request->isMethod(['post', 'put', 'patch', 'delete'])) {
                    AuditLog::create([
                        'user_id' => $request->user()->id,
                        'event' => strtoupper($request->getMethod()),
                        'model_type' => 'Admin',
                        'model_id' => 0,
                        'description' => $request->path(),
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }
            } catch (\Exception $e) {
                // Silent failure to avoid disrupting main flow
            }
        }

        return $response;
    }
}
