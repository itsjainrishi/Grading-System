<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfNotStudent
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
        if (!(Auth::user()->role==='student')) {
            return redirect('home');
        }

        return $next($request);
    }
}
