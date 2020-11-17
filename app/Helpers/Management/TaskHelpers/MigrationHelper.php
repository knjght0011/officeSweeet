<?php
namespace App\Helpers\Management\TaskHelpers;

use App\Models\Management\Account;
use Illuminate\Support\Facades\DB;
use Artisan;
use Symfony\Component\Console\Output\StreamOutput;

class MigrationHelper
{
    private static function SwitchConnection($database, $username, $password, $port)
    {
        DB::purge('subdomain');
        config(['database.connections.subdomain.username' => $username]);
        config(['database.connections.subdomain.password' => $password]);
        config(['database.connections.subdomain.port' => $port]);
        config(['database.connections.subdomain.database' => $database]);
        DB::connection('subdomain')->reconnect();
    }
    
    private static function SwitchConnectionBack()
    {
        $account = Account::where('subdomain', '=', "lls")->first();
        self::SwitchConnection($account->database, $account->username, $account->password, $account->port);
    }
    
    public static function MigrateDB($account)
    {        
        if(count($account) === 1){
            
            self::SwitchConnection($account->database, $account->username, $account->password, $account->port);
            set_time_limit(6000);


            Artisan::call('migrate', array('--path' => 'database/migrations', '--force' => true, '--database' => 'subdomain', '--step' => ''));

            self::SwitchConnectionBack();
            return Artisan::output();
        }
    }
    
    public static function RollbackDB($account)
    {        
        if(count($account) === 1){
            
            self::SwitchConnection($account->database, $account->username, $account->password, $account->port);
            set_time_limit(6000);

            #$stream = fopen("php://output", "w");
            Artisan::call('migrate:rollback', array('--path' => 'database/migrations', '--force' => true, '--database' => 'subdomain'));

            self::SwitchConnectionBack();
            return Artisan::output();
        }
    }
}