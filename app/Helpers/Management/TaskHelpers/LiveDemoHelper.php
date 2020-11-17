<?php
namespace App\Helpers\Management\TaskHelpers;

use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

use App\Mail\LiveDemoDailyReport;

use App\Models\User;

class LiveDemoHelper
{
    public static function SwitchConnection($database, $username, $password, $port = "3306")
    {
        config(['database.connections.subdomain.username' => $username]);
        config(['database.connections.subdomain.password' => $password]);
        config(['database.connections.subdomain.port' => $port]);
        config(['database.connections.subdomain.database' => $database]);
        \DB::connection('subdomain')->reconnect();
    }

    public static function GatherData($account){
        self::SwitchConnection($account->database, $account->username, $account->password, $account->port);

        $from = Carbon::now()->addDays(-3);
        $to = Carbon::now();

        $users = User::where('canlogin', '=', '0')->whereBetween('created_at', array($from, $to))->get();

        Mail::to("jstagl@officesweeet.com","John Stagl")->send(new LiveDemoDailyReport($users));
        Mail::to("leads@btwebgroup.com", "btwebgroup")->send(new LiveDemoDailyReport($users));
        Mail::to("staylor@officesweeet.com", "Sam Taylor")->send(new LiveDemoDailyReport($users));

        return count($users);
    }
}
