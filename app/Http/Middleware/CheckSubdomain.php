<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Response;

class CheckSubdomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $subdomain)
    {
        $account = app()->make('account');

        if($account->subdomain === "local"){
            return $next($request);
        }

        if($account->subdomain === $subdomain){
            return $next($request);
        }

        return Response::make(view('errors.404'), 404);

    }
}
