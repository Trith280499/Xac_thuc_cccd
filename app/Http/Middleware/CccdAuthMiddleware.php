<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CccdAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('cccd_authenticated')) {
            return redirect('/');
        }
        return $next($request);
    }
}