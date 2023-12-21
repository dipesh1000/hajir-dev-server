<?php

namespace SuperAdmin\Http\Middleware;

use Brian2694\Toastr\Facades\Toastr;
use Closure;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{

    public function handle($request, Closure $next)
    {
        if (Auth::guard('web')->check()) {
            $user = auth()->user();
            if ($user->hasRole('superadmin')) {
                return $next($request);
            }
            Toastr::error('You Do Not Have Permission');
            return redirect()->back();
        }
        Toastr::error('Please Log In First');
        return redirect()->route('backend.login');
    }
}
