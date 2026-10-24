<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Top management admin has complete access to all modules
        if ($user->role === 'admin') {
            return $next($request);
        }

        if (!empty($roles) && !in_array($user->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'عذراً، لا تمتلك الصلاحية الكافية لتنفيذ هذا الإجراء.'
                ], 403);
            }

            return redirect()->route('dashboard')->with(
                'error',
                'عذراً، هذا القسم غير مصرح لحسابك (' . $user->role_title . '). يرجى الالتزام بالمهام والصلاحيات المخصصة لقسمك فقط.'
            );
        }

        return $next($request);
    }
}
