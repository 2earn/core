<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class IsSuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user() && Auth::user()->getRoleNames()->contains(User::SUPER_ADMIN_ROLE_NAME)) {
            return $next($request);
        }

        return redirect()->route('home', app()->getLocale())->with('danger', Lang::get('You have not super admin access'));
    }
}
