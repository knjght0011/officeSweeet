<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response;

class SoloAdverts
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
        if($request->method() === "POST"){
            return $next($request);
        }

        //if(app()->make('account')->subdomain !== "local"){
        if(app()->make('account')->plan_name !== "SOLO"){
            return $next($request);
        }


        if(isset(Auth::user()->options['AdvertCount'])){
            if(Auth::user()->options['AdvertCount'] > 15){
                $array = Auth::user()->options;
                $array['AdvertCount'] = 0;
                Auth::user()->options = $array;
                Auth::user()->save();

                return Response::make(view('OS.SoloAdvert.view')->with('url', $request->getRequestUri()));

            }else{
                $array = Auth::user()->options;
                $array['AdvertCount']++;
                Auth::user()->options = $array;
                Auth::user()->save();
                return $next($request);
            }
        }else{
            $array = Auth::user()->options;
            $array['AdvertCount'] = 1;
            Auth::user()->options = $array;
            Auth::user()->save();
            return $next($request);
        }
    }
}
