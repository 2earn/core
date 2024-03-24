<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class setLocalLogin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle($request, Closure $next)
    {
        if (isset($request->locale))
        {
            app()->setLocale($request->locale);
        }
//            dd($request->locale);
//        app()->setLocale($request->segment(1));
//        if (count($request->route()->parameters())> 1)
//            dd($request->route()->parameters());

        return $next($request);
    }
}
