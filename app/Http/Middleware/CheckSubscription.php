<?php
namespace App\Http\Middleware;

use Illuminate\Support\Facades\Redirect;

use Closure;

class CheckSubscription
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

        $account = app()->make('account');

        if($account->IsActive()){
            return $next($request);
        }else{
            //account run out
            if($account->subscription_id === null){
                //no subscription, Probably trial, send to subscription screen
                return Redirect::to('/Subscription');
            }else{
                //they are subed so give 3 extra days for subscription to clear
                if($account->DaysLeft() > 3){
                    //past the 3 days so redirect
                    //new screen to be added to show more info as to wy subscriprion hasnt gon ahead this month
                    return Redirect::to('/Subscription');
                }else{
                    //within 3 days so let them go
                    //Post some alert accross top of screen to show problem
                    return $next($request);
                }
            }
        }
    }
}
