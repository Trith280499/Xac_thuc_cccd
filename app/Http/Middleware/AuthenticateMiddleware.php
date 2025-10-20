<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard = 'web'): Response
    {
        // if(!auth()->guard('web')->check()){
        if(!Auth::guard($guard)->check()){
            return redirect('/home');
        }
        return $next($request);
    }
}


//for use in routes/web.php
// use App\Http\Middleware\AuthenticateMiddleware;

// Route::middleware([AuthenticateMiddleware::class])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     });
// });