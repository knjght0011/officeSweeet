<?php

namespace App\Http\Controllers;

use App\Helpers\OS\SettingHelper;
use App\Helpers\OS\Users\UserHelper;
use App\Http\Controllers\Controller;


use App\Models\User;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use \App\Providers\EventLog;

# models
use App\Models\Client;
use App\Models\Vendor;
use App\Models\CalendarEvent;
use App\Models\SchedulerEvents;

use App\Models\OS\Scheduler;
use App\Models\OS\SchedulerParent;

use App\Helpers\OS\Scheduler\ScheduleHelper;

use DataTables;

class SchedulingController extends Controller {
                
    public function ShowSchedule($subdomain, $userid = null)
    {
        if(!app('mobile-detect')->isMobile()){
            return self::ShowScheduleDesktop();
        }else{
            return self::ShowScheduleMobile($userid);
        }
    }

    public function GetScheduleJSON()
    {
        return DataTables(scheduler::orderBy('start', 'DESC')->Get())->toJson();
    }

    public function ShowScheduleMobile($userid){

        $user = UserHelper::GetOneUserByID($userid);
        if(count($user) === 1){
            $mobileresourceid = $user->id;
            $mobileresourcetitle = $user->getShortName();

            if(isset(Auth::user()->options['DefaultScheduleView'])){
                $view = Auth::user()->options['DefaultScheduleView'];
            }else{
                $view = "agendaFourDay";
            }

            //$scheduleusers = null;
        }else{
            if($userid === "0"){
                $view = "agendaDay";
            }else{
                $view = "agendaFourDay";
            }
            //$scheduleusers = User::all();
            $mobileresourceid = Auth::user()->id;
            $mobileresourcetitle = Auth::user()->getShortName();
        }

        $generalevents = SchedulerEvents::where("user_id", null)->get();

        $clients = Client::with('primarycontact')->get();
        $vendors = Vendor::with('primarycontact')->get();

        return View::make('Scheduling.mobile')
            ->with('view', $view)
            //->with('scheduleusers', $scheduleusers)
            ->with('mobileresourceid', $mobileresourceid)
            ->with('mobileresourcetitle', $mobileresourcetitle)
            ->with('clients', $clients)
            ->with('vendors', $vendors)
            ->with('generalevents',$generalevents);
    }

    public function ShowScheduleDesktop(){

        if(count(Auth::user()->ScheduleDepartmentsArray()) === 0){
            if(isset($users)){
                $scheduleusers = $users;
            }else{
                $scheduleusers = UserHelper::GetAllUsers();
            }
        }else{
            $scheduleusers = User::whereIn('department', Auth::user()->ScheduleDepartmentsArray())->where('os_support_permission', "0")->get();
        }

        if(isset(Auth::user()->options['DefaultScheduleView'])){
            $view = Auth::user()->options['DefaultScheduleView'];
        }else{
            $view = "agendaDay";
        }

        $generalevents = SchedulerEvents::where("user_id", null)->get();

        $clients = Client::with('primarycontact')->get();
        $vendors = Vendor::with('primarycontact')->get();

        return View::make('Scheduling.desktop')
            ->with('scheduleusers', $scheduleusers)
            ->with('clients', $clients)
            ->with('vendors', $vendors)
            ->with('generalevents',$generalevents)
            ->with('view', $view);

    }

    public function ShowScheduleDay($subdomain, $type, $date)
    {

        if(count(Auth::user()->ScheduleDepartmentsArray()) === 0){
            if(isset($users)){
                $scheduleusers = $users;
            }else{
                $scheduleusers = UserHelper::GetAllUsers();
            }
        }else{
            $scheduleusers = User::whereIn('department', Auth::user()->ScheduleDepartmentsArray())->where('os_support_permission', "0")->get();
        }

        $generalevents = SchedulerEvents::where("user_id", null)->get();
        $yourevents = Auth::user()->SchedulerEvents;
        #$users = User::where('id', '>', 0)->where('os_support_permission', 0)->get();
        $events = CalendarEvent::all();

        $clients = Client::with('primarycontact')->get();
        $vendors = Vendor::with('primarycontact')->get();
        
        #$tasks = Task::all();

        if($type != null){
            if($type != ""){
                $view = $type;
            }else{
                $view = "agendaFourDay";
            }
        }else{
            $view = "agendaFourDay";
        }

        $mobileresourceid = Auth::user()->id;
        $mobileresourcetitle = Auth::user()->getShortName();
        
        return View::make('Scheduling.desktop')
                ->with('scheduleusers', $scheduleusers)
                ->with('clients', $clients)
                ->with('vendors', $vendors)                
                ->with('generalevents',$generalevents)
                ->with('yourevents',$yourevents)
                ->with('events',$events)
                ->with('view', $view)
                ->with('date', $date)
                ->with('mobileresourceid', $mobileresourceid)
                ->with('mobileresourcetitle', $mobileresourcetitle);
    }

    public function SaveView(){

        $options = Auth::user()->options;
        $options['schedule-view'] = Input::get('schedule-view');
        Auth::user()->options = $options;
        Auth::user()->save();

        return ['status' => 'OK'];
    }

    public function SaveEvent()
    {
        $DST = SettingHelper::GetSetting("DST");
        $DSTOffset = 0;
        if ($DST == 'true')
        {
            $DSTOffset += 60;
        }
        $event = Input::get('event');
        #$event['start'] = Auth::user()->AdjustmentCarbonTimezone(Carbon::createFromFormat('Y-m-d H:i:s', $event['start']));
        #$event['end'] = Auth::user()->AdjustmentCarbonTimezone(Carbon::createFromFormat('Y-m-d H:i:s', $event['end']));
        $event['start'] = Carbon::createFromFormat('Y-m-d H:i:s', $event['start'])->addMinutes(Auth::user()->timezoneoffset + $DSTOffset);
        $event['end'] = Carbon::createFromFormat('Y-m-d H:i:s', $event['end'])->addMinutes(Auth::user()->timezoneoffset + $DSTOffset);

        if($event['userid'] === ""){
            $event['userid'] = Auth::user()->id;
        }

        if($event['id'] === "0"){
            $scheduler = new Scheduler;
            $schedulerparent = new SchedulerParent;

            $id = ScheduleHelper::UpdateEventFull($schedulerparent, $scheduler, $event);
            
            #EventLog::add('New event added to userId: '.$schedulerparent->user_id.' Title: '.$scheduler->title.' start: '.$scheduler->start.' id: '.$scheduler->id );

            return ['status' => 'OK', 'id' => $id];

        }else{

            $scheduler = Scheduler::where('event_id', $event['id'])->first();
            $schedulerparent = SchedulerParent::where('id', $scheduler->parent_id)->first();

            if($schedulerparent->repeats === 0){


                if($event['repeats'] === "0"){
                    //not repeating, update everything
                    $id = ScheduleHelper::UpdateEventFull($schedulerparent, $scheduler, $event);
                    return ['status' => 'OK', 'id' => $id];
                }else {
                    $event['repeat_till'] = Carbon::parse($event['repeat_till']);

                    //becomeing a repeater
                    if($event['repeat_till'] > $event['start']){
                        $id = ScheduleHelper::UpdateEventFull($schedulerparent, $scheduler, $event);

                        if(ScheduleHelper::MakeRepeat($event['repeat_freq'], $event['repeat_till'], $schedulerparent, $event['note'])){
                            return ['status' => 'OK', 'id' => $scheduler->event_id];
                        }else{
                            return ['status' => 'error'];
                        }
                    }else{
                        return ['status' => 'error'];
                    }
                }
            }else {
                //repeating, just update this instance
                $id = ScheduleHelper::UpdateEventHalf($schedulerparent, $scheduler, $event);

                return ['status' => 'OK', 'id' => $id];

            }
        }
    }
    
    public function ValidateIDInput($data)
    {
        $rules = array( 
            'id' => 'exists:calendar_events,id',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);
        
        return $validator;
    }
    
    public function ValidateUserEventInput($data)
    {
        $rules = array( 
            'title' => 'required',
            #'start' => 'date',
            #'end' => 'date',
             'userid' => 'exists:users,id',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);
        
        return $validator;
    }

    public function ValidateEventInput($data)
    {
        $rules = array( 
            'title' => 'required',
            #'start' => 'date',
            #'end' => 'date',
           
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);
        
        return $validator;
    }

    public function DeleteEvent()
    {
        $data = array(
            'id' => Input::get('id'),
            'repeat' => Input::get('repeat'),
        );


        $event = Scheduler::where('event_id', $data['id'])->with('schedulerparent')->first();


        if(count($event) === 1){
            if($data['repeat'] === "1"){
                #$schedulerparent = SchedulerParent::where('id', $event->parent_id)->with('scheduler')->get();
                foreach($event->schedulerparent->scheduler as $scheduler){
                    $scheduler->delete();
                }
                $event->schedulerparent->delete();
            }else{
                $event->delete();
            }

            EventLog::add('event deleted for userId: '.$event->user_id.' Title: '.$event->title.' start: '.$event->start.' id: '.$event->id);

            return "done";
                
        } else {
            return "error;";
        }
    }

    public function JsonFeed(){

        $data = array(
            'start' => Input::get('start'),
            'end' => Input::get('end'),
            '_' => Input::get('_'),
            'me' => Input::get('me'),
        );

        //cant query scheduleer table by schduler_parent fields and still retain modal functions so have to query all and filter after
        $events = Scheduler::where(function ($query) use ($data) {
                    $query->whereBetween('start', [$data['start'], $data['end']])
                          ->orWhereBetween('end', [$data['start'], $data['end']]);})
                    ->with('schedulerparent')
                    ->get();


        $arrayofevents = array();

        foreach($events as $event){
            if($data['me'] === "me"){
                if($event->schedulerparent->user_id === Auth::user()->id){
                    array_push($arrayofevents ,self::FormatEvent($event));
                }
            }else{
                array_push($arrayofevents ,self::FormatEvent($event));
            }
        }

        return json_encode($arrayofevents);

    }

    public function TrainingFeed(){

        $data = array(
            'start' => Input::get('start'),
            'end' => Input::get('end'),
            '_' => Input::get('_'),
            'me' => Input::get('me'),
        );

        //cant query scheduleer table by schduler_parent fields and still retain modal functions so have to query all and filter after
        $events = Scheduler::
            where(function ($query) use ($data) {
            $query->whereBetween('start', [$data['start'], $data['end']])
                ->orWhereBetween('end', [$data['start'], $data['end']]);})
            ->with('schedulerparent')
            ->get();

        $arrayofevents = array();

        foreach($events as $event){
            if($event->schedulerparent->user_id === Auth::user()->id){
                if($event->schedulerparent->training_request_id != null){
                    array_push($arrayofevents ,self::FormatEvent($event));
                }
            }
        }

        return json_encode($arrayofevents);

    }

    private function FormatEvent($event){
        $DST = SettingHelper::GetSetting("DST");
        $DSTOffset = 0;
        if ($DST == 'true')
        {
            $DSTOffset += 60;
        }
        $array = array(
            'id' => $event->event_id,
            'title' => $event->title,
            'start' => $event->start->subMinutes(Auth::user()->timezoneoffset + $DSTOffset)->toDateTimeString(),
            'end' => $event->end->subMinutes(Auth::user()->timezoneoffset + $DSTOffset)->toDateTimeString(),
            'note' => $event->contents,
            'reminderdate' => $event->fromatReminderDate(),
            'reminderemails' => $event->reminderemails,
            'resourceId' => $event->schedulerparent->user_id,
            'patient_id' => $event->schedulerparent->patient_id,
            'client_id' => $event->schedulerparent->client_id,
            'vendor_id' => $event->schedulerparent->vendor_id,
            'linkid' => $event->schedulerparent->linkid,
            'linkname' => $event->schedulerparent->linkname,
            'linktype' => $event->schedulerparent->linktype,
            'useremail' => $event->schedulerparent->useremail,
            'backgroundColor' => $event->schedulerparent->getBackgroundColor(),
            'repeats' => $event->schedulerparent->repeats,
            'repeat_till' => $event->schedulerparent->repeat_freq,
            'parent' => $event->schedulerparent->id,
        );

        if($event->schedulerparent->linktype === "training"){
            $array['traininglink'] = $event->schedulerparent->TrainingRequest->TrainingModule->FormatedLink();
            $array['trainingquiz'] = $event->schedulerparent->TrainingRequest->TrainingModule->FormatedQuiz();
            $array['trainingcomments'] = $event->schedulerparent->TrainingRequest->TrainingModule->comments;
        }

        return $array;
    }

    public function Notify(){

        $id = Input::get('id');

        $event = Scheduler::where('event_id', '=', $id)->first();

        if(count($event) ===  1){
            $event->Notify();

            return ['status' => 'OK'];
        }else{
            return ['status' => 'EventNotFound'];
        }
    }


    public function saveSchedulerEvent()
    {
        $data = new SchedulerEvents;
        $data->eventname = Input::get('name');
        $data->user_id = Input::get('user_id');
        $data->save();

        return ['status' => 'OK', 'id' => $data->id, 'eventname' => $data->eventname];
    }

    public function deleteSchedulerEvent()
    {
        $data = SchedulerEvents::where('id', Input::get('id'))->First();
        if(count($data) === 1){
            $data->delete();
            return ['status' => 'OK', 'id' => $data->id];
        }else{
            return ['status' => 'notfound'];
        }
    }

    public function DragableDuration(){
        $data = SchedulerEvents::where('id', Input::get('id'))->First();
        if(count($data) === 1){
            $data->duration = Input::get('duration');
            $data->save();
            return ['status' => 'OK', 'id' => $data->id];
        }else{
            return ['status' => 'notfound'];
        }
    }


    public function saveFilter(){

        $data = Input::all();

        unset($data['_token']);

        $options = Auth::user()->options;
        $options["ScheduleDepartments"] = $data;
        Auth::user()->options = $options;
        Auth::user()->save();

        return ['status' => 'OK'];

    }

    public function resetFilter(){

        $data = Input::all();

        unset($data['_token']);

        $options = Auth::user()->options;
        unset($options["ScheduleDepartments"]);
        Auth::user()->options = $options;
        Auth::user()->save();

        return ['status' => 'OK'];

    }


    /*##TaskList fucntions
    
    public function SaveTask()
    {
       $data = array(
            'id' => Input::get('id'),
            'user_id' => Input::get('user_id'),
            'taskname' => Input::get('taskname'),
            //'date' => Carbon::createFromFormat('Y-m-d H:i:s', Input::get('date')),
            'description' => Input::get('description'),
            'status' => Input::get('status'),
        );
       
        $task = $this->GetTask($data['id']);
        $task->user_id = $data['user_id'];
        $task->taskname = $data['taskname'];
        //$task->date = $data['date'];
        $task->description = $data['description'];
        if($task->status != "Complete"){
            if($data['status'] === "Complete"){
                $task->completedate = Carbon::now();
            }
        }
        
        $task->status = $data['status'];
        $task->save();
        
        return $task->returnString();
    }
    
    private function GetTask($id)
    {
        if($id === "0"){
            return new Task;
        }else{
            return Task::find($id);
        }
    }
    */
    

}
