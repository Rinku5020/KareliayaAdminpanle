<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckLogin
{
    
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('loggedIn') || !session('loggedIn')) {
            return redirect()->route('showLogin');
            
        }

        return $next($request);
    }
}
