<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CloseAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle(Request $request, Closure $next)
    {

        $userAuth = Auth::user();
        $ses ='Expired' .$userAuth->idUser ;
        if(!Session::has($ses))
        {

            Auth::logout();
            Cache::flush();
            return redirect()->route('login', getLangNavigation())->with('FromLogOut', 'FromLogOut ');
        }

        $value = Session::get($ses);

        try {
            $dt = Carbon::now()->diff($value);
            if ($dt->invert == 1) {
                Auth::logout();
                Cache::flush();
                return redirect()->route('login', getLangNavigation())->with('FromLogOut', 'FromLogOut ');
            }
        }catch (\Exception $ex)
        {
            dd('3333');
            Auth::logout();
            Cache::flush();
            return redirect()->route('login', getLangNavigation())->with('FromLogOut', 'FromLogOut ');
        }
        return $next($request);
    }
}

