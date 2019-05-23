<?php

namespace App\Http\Middleware;

use Closure;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Method: 1
        // if (auth()->check()) {
        //     if (auth()->user()->is_admin) {
        //         return $next($request);
        //     }
        //     return redirect()->back();
        // }
        // return redirect()->back();

        // Method: 2
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }
        return redirect()->back();
    }
}
