<?php

namespace App\Http\Middleware;

use App\Helpers\OS\Users\UserHelper;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


use App\Models\Task;
use App\Models\User;
use App\Models\ExpenseAccountCategory;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $permission, $level = null)
    {
        if($level === null){
            if (Auth::user()->hasPermission($permission) ){
                return $next($request);
            }else{
                return response('Unauthorized.', 401);
            }
        }else{
            if (Auth::user()->hasPermissionMulti($permission, $level) ){
                return $next($request);
            }else{
                return response('Unauthorized.', 401);
            }
        }

    }
}
