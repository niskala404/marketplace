<?php

namespace App\Http\Middleware;

use App\Models\AdminAuditLog;
use Closure;
use Illuminate\Http\Request;

class AdminAuditLogger
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            $user = $request->user();
            if ($user && $user->isAdmin() && !in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true)) {
                AdminAuditLog::create([
                    'admin_user_id' => $user->id,
                    'method' => $request->method(),
                    'route_name' => optional($request->route())->getName(),
                    'path' => $request->path(),
                    'ip' => $request->ip(),
                    'user_agent' => (string) $request->userAgent(),
                    'payload' => [
                        'status' => $response->getStatusCode(),
                        'input' => $request->except(['password', 'password_confirmation']),
                    ],
                ]);
            }
        } catch (\Throwable $e) {
            // never break request
        }

        return $response;
    }
}
