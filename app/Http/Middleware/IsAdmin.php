<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role_id == 2) {
            // ถ้าเป็น admin → ผ่านเข้าไป
            return $next($request);
        }

        // ถ้าไม่ใช่ admin → redirect ไปที่ user dashboard
        return redirect()
            ->route('user.dashboard')
            ->with('error', 'คุณไม่มีสิทธิ์เข้าหน้านี้');
    }
}
