<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Response;

class CheckBroker
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
        if(app()->make('account')->plan_name === "BROKER"){
            return $next($request);
        }else {
            if (app()->make('account')->subdomain === "local") {
                return $next($request);
            }else{
                return Response::make(view('errors.404'), 404);
            }
        }

    }
}
