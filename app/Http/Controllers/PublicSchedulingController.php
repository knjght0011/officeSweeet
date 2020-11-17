<?php

namespace App\Http\Controllers;

use App\Helpers\OS\Users\UserHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;


# models
use App\Models\Client;
use App\Models\Vendor;
use App\Models\CalendarEvent;
use App\Models\SchedulerEvents;
use App\Models\OS\Scheduler;
use App\Models\OS\SchedulerParent;
use App\Models\User;


# Helper
use App\Helpers\OS\SettingHelper;

use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event;
use Eluceo\iCal\Component\Alarm;

class PublicSchedulingController extends Controller
{

    public function ShowSchedule($subdomain, $userid = null)
    {
        if (SettingHelper::GetSetting("PublicSchedule") === 'True') {
            if (!app('mobile-detect')->isMobile()) {
                return self::ShowScheduleDesktop();
            } else {
                return self::ShowScheduleMobile($userid);
            }
        }
    }

    public function ical()
    {
        $vCalendar  = new Calendar('local.officesweeet.com/PublicSchedule/ical');

        date_default_timezone_set('Etc/UTC');

        $events = Scheduler::all();
        foreach($events as $event){

            $vEvent = new Event();

            $vEvent
                ->setDtStart(new \DateTime($event->start))
                ->setDtEnd(new \DateTime($event->end))
                ->setSummary($event->title)
                ->setDescription($event->contents);

            $vEvent->setUseTimezone(true);
            $vEvent->setTimezoneString('Etc/UTC');
            $vEvent->setMsBusyStatus('BUSY');

            $vAlarm = new Alarm();
            $vAlarm->setAction('DISPLAY');
            $vAlarm->setTrigger('-PT15M'); // 15 minutes before event
            $vAlarm->setDescription('Reminder');

            $vEvent->addComponent($vAlarm);

            $vCalendar->addComponent($vEvent);
        }

        $events = CalendarEvent::all();
        foreach($events as $event){
            $vEvent = new Event();

            $vEvent
                ->setDtStart(new \DateTime($event->start))
                ->setDtEnd(new \DateTime($event->end))
                ->setSummary($event->title)
                ->setDescription($event->contents);


            $vEvent->setMsBusyStatus('BUSY');

            $vEvent->setUseTimezone(true);
            $vEvent->setTimezoneString('Etc/UTC');

            $vAlarm = new Alarm();
            $vAlarm->setAction('DISPLAY');
            $vAlarm->setTrigger('-PT15M'); // 15 minutes before event
            $vAlarm->setDescription('Reminder');

            $vEvent->addComponent($vAlarm);

            $vCalendar->addComponent($vEvent);
        }
        return View::make('public.ical')->with('icalData',$vCalendar->render());

    }

    public function ShowScheduleMobile($userid){

        $scheduleusers = UserHelper::GetAllUsers();
        $generalevents = SchedulerEvents::all();

        $clients = Client::with('primarycontact')->get();
        $vendors = Vendor::with('primarycontact')->get();

        return View::make('public.mobile')
            ->with('scheduleusers', $scheduleusers)
            ->with('clients', $clients)
            ->with('vendors', $vendors)
            ->with('generalevents',$generalevents);
    }

    public function ShowScheduleDesktop(){

        $view = "agendaWeek";

        $generalevents = SchedulerEvents::all();

        $clients = Client::with('primarycontact')->get();
        $vendors = Vendor::with('primarycontact')->get();

        return View::make('public.desktop')

            ->with('clients', $clients)
            ->with('vendors', $vendors)
            ->with('generalevents',$generalevents)
            ->with('view', $view);

    }

    public function ShowScheduleDay($subdomain, $type, $date)
    {

        $generalevents = SchedulerEvents::where("user_id", null)->get();

        $events = CalendarEvent::all();

        $clients = Client::with('primarycontact')->get();
        $vendors = Vendor::with('primarycontact')->get();


        if($type != null){
            if($type != ""){
                $view = $type;
            }else{
                $view = "agendaFourDay";
            }
        }else{
            $view = "agendaFourDay";
        }

        return View::make('public.desktop')

            ->with('clients', $clients)
            ->with('vendors', $vendors)
            ->with('generalevents',$generalevents)

            ->with('events',$events)
            ->with('view', $view)
            ->with('date', $date);

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
            //if($data['me'] === "me")
            //{
                //if($event->schedulerparent->user_id === Auth::user()->id){
                    //array_push($arrayofevents ,self::FormatEvent($event));
                //}
            //}else{
                array_push($arrayofevents ,self::FormatEvent($event));
            //}
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

                if($event->schedulerparent->training_request_id != null){
                    array_push($arrayofevents ,self::FormatEvent($event));
                }

        }

        return json_encode($arrayofevents);

    }

    private function FormatEvent($event){

        $timezoneoffset = Input::get('timezone');

        if($timezoneoffset == null){
            $timezoneoffset = 0;
        }

        $array = array(
            'id' => $event->event_id,
            'title' => $event->title,
            'start' => $event->start->subMinutes($timezoneoffset)->toDateTimeString(),
            'end' => $event->end->subMinutes($timezoneoffset)->toDateTimeString(),
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

}
