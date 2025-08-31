<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role_id == 2) {
            return $next($request);
        }

        return redirect()->route('user.dashboard')
            ->with('error', 'คุณไม่มีสิทธิ์เข้าหน้านี้');
    }
}
