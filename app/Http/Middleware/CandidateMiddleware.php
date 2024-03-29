<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidateMiddleware
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

        if (Auth::check()) {

            $user = auth()->user();

            if ($user->hasRole('candidate')) {
                return $next($request);
            }
            auth()->user()->token()->revoke();
            return response([
                'message' => 'You Do Not Have Permission.',
                'status' => 'error',
            ],401);
        }
        return response([
            'message' => 'Please Log In.',
            'status' => 'error',
        ],401);
    }
}
