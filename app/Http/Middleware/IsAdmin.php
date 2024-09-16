<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle(Request $request, Closure $next)
    {
        if (Auth::user() && (Auth::user()->getRoleNames()->first() == User::SUPER_ADMIN_ROLE_NAME || Auth::user()->getRoleNames()->first() == "Moderateur" || Auth::user()->getRoleNames()->first() == "Admin")) {

            return $next($request);
        }
        return redirect()->route('login', app()->getLocale())->with('error', Lang::get('You have not admin access'));
    }
}
