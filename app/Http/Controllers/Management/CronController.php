<?php

namespace App\Http\Controllers\Management;

#use Session;
use App\Helpers\Management\SubscriptionHelper;
use App\Helpers\Management\TaskHelpers\NightlyDataHelper;
use App\Helpers\Management\TaskHelpers\ScheduleNightlyHelper;
use App\Helpers\OS\Mailgun\MailgunHelper;
use App\Models\Management\TaskQueue;
#use Illuminate\Support\Facades\View;
#use Illuminate\Support\Facades\Validator;
#use App\Models\Participant;
#use Illuminate\Support\Facades\Input;
#use Illuminate\Support\Facades\Auth;
#use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
#use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

use Cache;
use Request;

use App\Helpers\OS\EventHelper;
#use App\Helpers\AccountHelper;
use App\Helpers\Management\AlertHelper;
use App\Helpers\TransnationalHelper;
use App\Helpers\OS\RecurringInvoiceHelper;
#use App\Helpers\EmployeeHelper;

use App\Models\Management\Account;
use App\Models\Management\Subscription;
use App\Models\OS\Financial\TransnationalTransaction;

use App\Http\Controllers\Controller;
 
class CronController extends Controller
{
    public function CronHandler()
    {
        if(Request::header('X-Appengine-Cron') === "true"){
            env('DB_LOG_CONNECTION' , "management");
            switch (Request::input('job')) {
                case "RunTaskQueue":
                    return $this->RunTaskQueue();
                    break;
                case "QueueRecurringInvoices":
                    $this->QueueNightlyJobs();
                    return "Done";
                    break;
                case "TNrefresh":
                    return $this->TNrefresh();
                    break;
                default:
                    return "no matching job";
            }
        }else{
            EventHelper::add("Non-Ligit Cron Request");
            return Response::make(view('errors.404'), 404);
        }
    }

    public function TNrefresh(){

        #$subscription->accounts = Account::where('subscription_id' , '!=', null)->get();

        $subscriptions = Subscription::with('account')->get();

        TransnationalHelper::SetDeploymentToLLS();
        
        foreach($subscriptions as $subscription) {

            foreach ($subscription->TNSubscription() as $TNdata) {

                $transaction = SubscriptionHelper::GetTransaction($TNdata['transaction_id'], $TNdata);

                switch ($transaction->responsetext) {
                    case "complete":
                        if ($transaction->deposit_id === null) {
                            try{
                                $subscription->account->active = Carbon::now()->addDays(30);
                                $subscription->account->save();

                                $client = $subscription->account->FindLLSClient();

                                $date = Carbon::parse($TNdata['action']['date']);

                                $quote = TransnationalHelper::MakeQuoteOnLLS($client, "OfficeSweeet Subscription for " . $subscription->account->licensedusers . " users.", $TNdata['action']['amount'], $date);
                                $deposit = TransnationalHelper::MakeDepositOnLLS($quote, "OfficeSweeet Subscription for " . $subscription->account->licensedusers . " users.", $TNdata['action']['amount'], $date);
                                $transaction->deposit_id = $deposit->id;
                                $transaction->save();

                                AlertHelper::NewAlert($subscription->account->subdomain, "Subscription Billed", "Subscription billed for $" . $TNdata['action']['amount'] . ".");
                            }catch (\Throwable $t) {
                                AlertHelper::NewAlert($subscription->account->subdomain, "Subscription Error", $t->getMessage() . " " . $t->getCode() . " " . $t->getFile() . " " . $t->getLine() . " " . $t->getTraceAsString());
                                app('sentry')->captureException($t);
                            }
                        }
                        break;
                    case "failed":
                        //$subscription->delete();
                        if ($transaction->emailed === 0) {
                            AlertHelper::NewAlert($subscription->account->subdomain, "Subscription Error: card declined", "Subscription Error: card declined");
                            $transaction->emailed = 1;
                            $transaction->save();
                        }
                        break;

                }
            }
        }

        return "done";
    }

    public function RunTaskQueue()
    {
        $task = TaskQueue::where('started_at', '=', null)->first();
        if(count($task) === 1){
            $output = array();
            $task->Pickup();
            foreach($task->jobname as $jobname){
                try{
                    $output[$jobname] = $task->Process($jobname);
                }catch (\Throwable $t) {
                    $output[$jobname] = $t->getMessage() . " " . $t->getCode() . " " . $t->getFile() . " " . $t->getLine() . " " . $t->getTraceAsString();
                }
            }
            $task->SetOutput($output);
            $task->Done();
        }else{
            return "no job to run";
        }
    }

    public function TestTaskQueue($subdomain, $id)
    {
        $task = TaskQueue::where('id', '=', $id)->first();
        if(count($task) === 1){
            $task->Pickup();
            if($task->Process()){
                $task->Done();
                return "job id:" . $task->id . " done.";
            }else{
                return "job id:" . $task->id . " failed.";
            }
        }else{
            return "no job to run";
        }
    }

    public function QueueNightlyJobs()
    {
        $jobs = array('CheckRecurringInvoices','NightlyData','ScheduleYesterday','ScheduleToday', 'ScheduleReminder');

        $accounts = Account::where('installstage', '=', 7)->get();
        foreach($accounts as $account){
            if($account->subdomain != "local" and $account->subdomain != "lls"){
                if($account->subdomain != "disabled") {
                    $newtask = new Taskqueue;
                    $newtask->jobname = $jobs;
                    $newtask->account_id = $account->id;
                    $newtask->save();
                }
            }
        }

        $newtask = new Taskqueue;
        $newtask->jobname = array('CheckRecurringInvoices','ScheduleYesterday','ScheduleToday','ScheduleReminder');
        $newtask->account_id = 3;
        $newtask->save();

    }

    public function AddMigrationsToQueue()
    {
        $accounts = Account::whereNotNull('installstage')->get();
        foreach($accounts as $account){
            if($account->subdomain != "local"){
                if($account->subdomain != "disabled") {
                    $newtask = new Taskqueue;
                    $newtask->jobname = array("RunMigrations");
                    $newtask->account_id = $account->id;
                    $newtask->save();
                }
            }
        }
    }

    public function AddRollbackToQueue()
    {
        $accounts = Account::whereNotNull('installstage')->get();
        foreach($accounts as $account){
            if($account->subdomain != "local"){
                if($account->subdomain != "disabled") {
                    $newtask = new Taskqueue;
                    $newtask->jobname = array("RunRollback");
                    $newtask->account_id = $account->id;
                    $newtask->save();
                }
            }
        }
    }

    public function Test(){

        $account = Account::where('subdomain', 'lls')->first();
        return ScheduleNightlyHelper::SendReminders($account);

    }

    public function Test1()
    {


    }
















    /**
    public function QueueRecurringInvoices($date = null)
    {
        $accounts = Account::where('installstage', '=', 7)->get();
        foreach($accounts as $account){
            if($account->subdomain != "local"){
                if($account->subdomain != "disabled") {
                    $newtask = new Taskqueue;
                    $newtask->jobname = "CheckRecurringInvoices";
                    $newtask->account_id = $account->id;
                    if ($date != null) {
                        $newtask->parameters = array('date' => $date);
                    }
                    $newtask->save();
                }
            }
        }

        $newtask = new Taskqueue;
        $newtask->jobname = "LiveDemoDataDump";
        $newtask->account_id = 52;
        $newtask->save();

    }

    public function QueueNightlyData()
    {
        $accounts = Account::where('installstage', '=', 7)->get();
        foreach($accounts as $account){
            if($account->subdomain != "local" and $account->subdomain != "lls"){
                if($account->subdomain != "disabled") {
                    $newtask = new Taskqueue;
                    $newtask->jobname = "NightlyData";
                    $newtask->account_id = $account->id;
                    $newtask->save();
                }
            }
        }
    }
     **/





}
