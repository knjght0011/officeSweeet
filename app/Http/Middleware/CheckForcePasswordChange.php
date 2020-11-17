<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Closure;

class CheckForcePasswordChange
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
        if(Auth::user()->force_password_change === 1){
            if($request->getRequestUri() === "/Account/Password"){
                return $next($request);
            }else{
                return Response::make(view('OS.Account.forcechangepassword'));
            }
        }else{
            return $next($request);
        }
    }
}
