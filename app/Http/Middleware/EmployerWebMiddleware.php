<?php

namespace App\Http\Middleware;

use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployerWebMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (Auth::guard('web')->check() ) {
            $user = Auth::guard('web')->user();
          
            if ($user->hasRole('employer')) {

                return $next($request);
            }
            Toastr::error('You Do Not Have Permission.');
            return redirect()->back();
        }
        
        Toastr::error('Please Log In First.');
        return redirect()->route('employer.register');
    }
}
