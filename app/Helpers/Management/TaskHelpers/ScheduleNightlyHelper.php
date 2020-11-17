<?php
namespace App\Helpers\Management\TaskHelpers;

use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

use App\Models\ClientNote;
use App\Models\OS\Scheduler;
use App\Models\User;
use App\Models\VendorNote;

use App\Mail\SchedulerDailyMail;
use App\Mail\ScheduleReminder;

class ScheduleNightlyHelper
{
    public static function SwitchConnection($database, $username, $password, $port = "3306")
    {
        config(['database.connections.subdomain.username' => $username]);
        config(['database.connections.subdomain.password' => $password]);
        config(['database.connections.subdomain.port' => $port]);
        config(['database.connections.subdomain.database' => $database]);
        \DB::connection('subdomain')->reconnect();
    }

    public static function RunYesterday($account)
    {
        self::SwitchConnection($account->database, $account->username, $account->password, $account->port);

        $data['start'] = Carbon::now()->addDays(-1)->startOfDay();
        $data['end'] = Carbon::now()->addDays(-1)->endOfDay();

        $events = Scheduler::where(function ($query) use ($data) {
                                    $query->whereBetween('start', [$data['start'], $data['end']])
                                        ->orWhereBetween('end', [$data['start'], $data['end']]);
                                })
                                    ->with('schedulerparent')
                                    ->get();


        foreach($events as $event){
            if($event->schedulerparent->client_id != null){
                $note = new ClientNote;
                $note->client_id = $event->schedulerparent->client_id;
                $note->user_id = null;
                $note->note = $event->getNote();
                $note->save();
            }
            if($event->schedulerparent->vendor_id != null){
                $note = new VendorNote;
                $note->vendor_id = $event->schedulerparent->vendor_id;
                $note->user_id = null;
                $note->note = $event->getNote();
                $note->save();
            }
        }

        return "Done";
    }

    public static function RunToday($account)
    {
        self::SwitchConnection($account->database, $account->username, $account->password, $account->port);

        $data['start'] = Carbon::now()->startOfDay();
        $data['end'] = Carbon::now()->endOfDay();
        $events = Scheduler::where(function ($query) use ($data) {
            $query->whereBetween('start', [$data['start'], $data['end']])
                ->orWhereBetween('end', [$data['start'], $data['end']]);})
            ->with('schedulerparent')
            ->get();

        //var_dump(count($events));
        $users = User::all();

        $sent = 0;
        foreach($users as $user){
            if($user->Option('ScheduleEmail')){

                $usersevents = $events->where('user_id', $user->id);

                Mail::to($user->email)->send(new SchedulerDailyMail($user, $usersevents));

                $sent++;
            }
        }


        return "Sent " . $sent . " emails";

    }

    public static function SendReminders($account){

        self::SwitchConnection($account->database, $account->username, $account->password, $account->port);

        $emails = 0;
        $events = Scheduler::whereDate('reminderdate', date('Y-m-d'))
                            ->with('schedulerparent')
                            ->get();

        foreach($events as $event){
            if($event->reminderemails != null){
                foreach($event->reminderemails as $email){
                    try{
                        Mail::to($email)->send(new ScheduleReminder($event));
                        $emails = $emails + 1;
                    }catch(\Throwable $t){
                        app('sentry')->captureException($t);
                        //return "error";
                        return $t->getMessage() . " " . $t->getCode() . " " . $t->getFile() . " " . $t->getLine() . " " . $t->getTraceAsString();
                    }
                }
            }
        }

        return "Sent " . $emails . " emails";

    }

}