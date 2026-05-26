<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role_id == 1) { // 1 is Admin
            return $next($request);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['error' => 'Bạn không có quyền thực hiện chức năng này.'], 403);
        }

        return redirect()->route('admin.dashboard')->with('error', 'Chỉ quản trị viên mới có quyền thực hiện chức năng này.');
    }
}
