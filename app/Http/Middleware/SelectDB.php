<?php

namespace App\Http\Middleware;

use Request;
use App\Models\Management\Account;
use Closure;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class SelectDB
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

        if (Request::is('cron*'))
        {
            return $next($request);
        }

        if (!$request->secure() && env('APP_ENV') !== 'local') {
            return redirect()->secure($request->getRequestUri());
        }

        $pieces = explode('.', $request->getHost());
        $subdomain = $pieces[0];

        switch ($subdomain) {
            case "google":
                $split = explode(",", Input::get('state'));
                $url = "http://" . $split[0] . ".officesweeet.com/Google/Auth?state=".Input::get('state')."&code=".Input::get('code')."&scope=".Input::get('scope')."1";
                return Redirect::away($url);
            case "api":
                $account = Account::where('subdomain', '=', "lls")->first();
                break;
            case "apitest":
                $account = Account::where('subdomain', '=', "lls")->first();
                break;
            case "promotionstest":
                $account = Account::where('subdomain', '=', "lls")->first();
                break;
            case "promotions":
                $account = Account::where('subdomain', '=', "lls")->first();
                break;
            case "local":
                if (\App::environment('local')) {
                    $account = Account::where('subdomain', '=', "local")->first();
                }else{
                    $account = null;
                }
                break;
            case "10":
                if (\App::environment('local')) {
                    $account = Account::where('subdomain', '=', "local")->first();
                }else{
                    $account = null;
                }
                break;
            default:
                $account = Account::where('subdomain', '=', $subdomain)->first();
        }

        if($account === null){
            #return $next($request);
            return Response::make(view('errors.404'), 404);
        }

        view()->share('subdomain', $subdomain);

        app()->instance('account', $account);

        config(['database.connections.subdomain.username' => $account->username]);
        config(['database.connections.subdomain.password' => $account->password]);
        config(['database.connections.subdomain.port' => $account->port]);
        config(['database.connections.subdomain.database' => $account->database]);
        \DB::connection()->reconnect();


        return $next($request);
        
    }
}
