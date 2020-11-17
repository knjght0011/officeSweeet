<?php

namespace App\Http\Middleware;

use App\Models\Management\Account;
use Closure;

class management
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
        $account = Account::where('subdomain', '=', "lls")->first();

        config(['database.connections.subdomain.username' => $account->username]);
        config(['database.connections.subdomain.password' => $account->password]);
        config(['database.connections.subdomain.port' => $account->port]);
        config(['database.connections.subdomain.database' => $account->database]);
        \DB::connection()->reconnect();
        
        return $next($request);
    }
}
