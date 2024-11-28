<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ShipperAuth
{
    public function handle($request, Closure $next)
    {
        // Kiểm tra nếu người dùng đã đăng nhập và có vai trò shipper (role = 3)
        if (Auth::check() && Auth::user()->role == 4) {
            return $next($request);
        }

        // Nếu không phải shipper, chuyển hướng về trang không có quyền truy cập
        return redirect('/home')->with('error', 'You do not have access to this section.');
    }
}
