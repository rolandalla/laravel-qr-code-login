<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Sentinel;

class SentinelAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Sentinel::guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 403);
            } else {
                return redirect()->guest('login');
            }
        }

        return $next($request);
    }
}

