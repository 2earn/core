<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CloseAuth
{
    public function handle(Request $request, Closure $next)
    {

        $userAuth = Auth::user();
        $ses = 'Expired' . $userAuth->idUser;

        if (!Session::has($ses)) {
            Auth::logout();
            Cache::flush();
            return redirect()->route('login', getLangNavigation());
        }

        $value = Session::get($ses);
        try {
            $dt = Carbon::now()->diff($value);
            if ($dt->invert == 1) {
                Auth::logout();
                Cache::flush();
                return redirect()->route('login', getLangNavigation());
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            Auth::logout();
            Cache::flush();
            return redirect()->route('login', getLangNavigation());
        }
        return $next($request);
    }
}
