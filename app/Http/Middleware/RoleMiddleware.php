<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Sentinel;
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {        
        $roles = explode("|", $role);
        if (!in_array(Sentinel::getUser()->role->name, $roles)) {
            return redirect('/');
        }
        
        return $next($request);
    }
}
