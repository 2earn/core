<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user() && (User::isSuperAdmin() || Auth::user()->getRoleNames()->first() == "Moderateur" || Auth::user()->getRoleNames()->first() == "Admin")) {
            return $next($request);
        }
        return redirect()->route('login', app()->getLocale())->with('danger', Lang::get('You have not admin access'));
    }
}
