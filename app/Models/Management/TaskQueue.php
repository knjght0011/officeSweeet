<?php

namespace App\Models\Management;

use App\Helpers\EmployeeHelper;
use App\Helpers\Management\TaskHelpers\ScheduleNightlyHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
Use Exception;

use App\Helpers\Management\TaskHelpers\MigrationHelper;
use App\Helpers\Management\TaskHelpers\RecurringInvoiceHelper;
use App\Helpers\Management\TaskHelpers\LiveDemoHelper;
use App\Helpers\Management\TaskHelpers\NightlyDataHelper;

use App\Helpers\AccountHelper;




class TaskQueue extends Model
{
    protected $connection = 'management';

    protected $table = 'taskqueue';

    protected $dates = ['started_at', 'finshed_at'];

    protected $casts = [
        'jobname' => 'array',
        'output' => 'array',
        'parameters' => 'array',
    ];

    public function account()
    {
        return $this->belongsTo('App\Models\Management\Account' , 'account_id', 'id' );
    }

    public function Pickup(){
        $this->started_at = Carbon::now();
        $this->save();
    }

    public function Process($jobname){
        switch ($jobname) {
            case "CheckRecurringInvoices":
                if($this->parameters === null){
                    $count = RecurringInvoiceHelper::CheckRecurringInvoices($this->account);
                    return $this->account->subdomain . " Invoices Processed: " . $count;
                }else{
                    $count = RecurringInvoiceHelper::CheckRecurringInvoices($this->account, $this->parameters['date']);
                    return $this->account->subdomain . " Invoices Processed: " . $count . " for date: " . $this->parameters['date'];
                }

            case "NightlyData":
                return NightlyDataHelper::Run($this->account);
            case "ScheduleYesterday":
                return ScheduleNightlyHelper::RunYesterday($this->account);
            case "ScheduleToday":
                return ScheduleNightlyHelper::RunToday($this->account);
            case "ScheduleReminder":
                return ScheduleNightlyHelper::SendReminders($this->account);
            case "RunMigrations":
                return MigrationHelper::MigrateDB($this->account);
            case "RunRollback":
                return MigrationHelper::RollbackDB($this->account);

            case "Provision-CreateDB":
                AccountHelper::ProvisionCreateDB($this->account);
                $this->ChainTask("Provision-MigrateDB");
                return $this->account->subdomain;

            case "Provision-MigrateDB":
                AccountHelper::ProvisionMigrateDB($this->account);
                $this->ChainTask("Provision-SeedData");
                return $this->account->subdomain;

            case "Provision-SeedData":
                AccountHelper::ProvisionSeedData($this->account);
                return $this->account->subdomain;

            case "LiveDemoDataDump":
                return LiveDemoHelper::GatherData($this->account);

            default:
                    return "Unknown Job Name.";

        }
    }

    public function Done(){
        $this->finshed_at = Carbon::now();
        $this->save();
    }

    public function ChainTask($name, $account_id = null){
        if($account_id === null){
            $account_id = $this->account->id;
        }

        $newtask = new Taskqueue;
        $newtask->jobname = array($name);
        $newtask->account_id = $account_id;
        $newtask->save();
    }

    public function SetOutput($output){
        $this->output = $output;
        $this->save();
    }
    
    public function Error(\Throwable $t){
        $this->SetOutput($t->getMessage() . " " . $t->getCode() . " " . $t->getFile() . " " . $t->getLine() . " " . $t->getTraceAsString());
    }
}
