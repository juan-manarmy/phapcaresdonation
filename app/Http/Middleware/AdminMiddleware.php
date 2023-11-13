<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AdminMiddleware
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
        if(Auth::check()) {
            if(Auth::user()->role_id != 0) {
                return $next($request); 
            } else {
                Auth::logout();
                return redirect('/login')->with('admin-message','This is for PHAPCares Admin only');
            }
        }
    }
}
