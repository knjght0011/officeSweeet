<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
#use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
#use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
#use Carbon\Carbon;

#use \App\Providers\EventLog;
use App\Models\Management\Log;
use App\Models\Management\Account;

class LogController extends Controller {
    
    public function showSearch($subdomain2, $subdomain)
    {
        $account = Account::where('subdomain', '=', $subdomain)->first();

        config(['database.connections.deployment.username' => $account->username]);
        config(['database.connections.deployment.password' => $account->password]);
        config(['database.connections.deployment.port' => $account->port]);
        config(['database.connections.deployment.database' => $account->database]);
        \DB::connection('deployment')->reconnect();

        $logs = Log::all();
        
        return View::make('Management.Logs.search')
                ->with('subdomain',$subdomain)
                ->with('logs',$logs);

    }
    
    public function showView($subdomain2,$subdomain, $id)
    {
        $account = Account::where('subdomain', '=', $subdomain)->first();

        config(['database.connections.deployment.username' => $account->username]);
        config(['database.connections.deployment.password' => $account->password]);
        config(['database.connections.deployment.port' => $account->port]);
        config(['database.connections.deployment.database' => $account->database]);
        \DB::connection('deployment')->reconnect();

        $log = Log::find($id);

        return View::make('Management.Logs.view')
                ->with('log',$log);
    }

    public function showException($subdomain2,$subdomain, $id)
    {
        $account = Account::where('subdomain', '=', $subdomain)->first();

        config(['database.connections.deployment.username' => $account->username]);
        config(['database.connections.deployment.password' => $account->password]);
        config(['database.connections.deployment.port' => $account->port]);
        config(['database.connections.deployment.database' => $account->database]);
        \DB::connection('deployment')->reconnect();

        $log = Log::find($id);

        return "<table>" . $log->context['exception']['xdebug_message'] . "</table>";
    }
    
}
