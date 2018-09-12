<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class FilledProfileOnly
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
        if (!Auth::user()->hasFilledProfile()) {
            return redirect()
                ->route('cabinet.profile.home')
                ->with('error', 'Please fill your profile and verify your phone');
        }

        return $next($request);
    }
}
