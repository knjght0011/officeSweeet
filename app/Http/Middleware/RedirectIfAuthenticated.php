<?php

namespace App\Http\Middleware;

use App\Helpers\OS\Users\UserHelper;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Redirect;

use App\Models\Task;
use App\Models\User;
use App\Models\ExpenseAccountCategory;

class RedirectIfAuthenticated
{
    private $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() === false) {

            $uri = $request->path();

            if($request->ajax()){
                return Redirect('/loginPOST');
            }else{
                return Redirect('/login')->with('uri', $uri); // redirect the user to the login screen
            }

        }else{
            return $next($request);
        }
    }
}
