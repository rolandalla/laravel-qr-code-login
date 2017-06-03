<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use Route;
use Request;
use Session;
class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $api="")
    {   
        $routeName = $request->route()->getName();
        if (empty($routeName) || Sentinel::hasAccess($routeName)) {
            return $next($request);
        }

        if (!empty($api)) {
            return response()->json(['message' => 'you_dont_have_permission_to_use_this_route'], 403);
        } else {

            Session::flash('message', 'Warning! Not enough permissions. Please contact Us for more');
            Session::flash('status', 'warning');
         return redirect()->back();
       }
        

        
    }
}
