<?php
namespace App\Helpers\OS\Scheduler;

use Carbon\Carbon;

use App\Models\OS\Scheduler;
use App\Models\OS\SchedulerParent;

class ScheduleHelper
{
    public static function UpdateEventFull(SchedulerParent $schedulerparent ,Scheduler $scheduler , $event)
    {
        $schedulerparent->title = $event['title'];
        $schedulerparent->weekday = "0";#not sure if need
        $schedulerparent->start_date = $event['start'];
        $schedulerparent->start_time = $event['start'];
        $schedulerparent->end_time = $event['end'];
        $schedulerparent->repeats = 0;
        $schedulerparent->repeat_freq = 0;

        switch ($event['linkedtype']) {
            case "client":
                $schedulerparent->client_id = $event['linkedid'];
                $schedulerparent->vendor_id = null;
                $schedulerparent->training_request_id = null;
                $schedulerparent->patient_id = null;
                break;
            case "vendor":
                $schedulerparent->vendor_id = $event['linkedid'];
                $schedulerparent->client_id = null;
                $schedulerparent->training_request_id = null;
                $schedulerparent->patient_id = null;
                break;
            case "training":
                $schedulerparent->vendor_id = null;
                $schedulerparent->client_id = null;
                $schedulerparent->training_request_id = $event['linkedid'];
                $schedulerparent->patient_id = null;
                break;

            case "patient":
                $schedulerparent->vendor_id = null;
                $schedulerparent->client_id = $event['client_id'];
                $schedulerparent->training_request_id = null;
                $schedulerparent->patient_id = $event['linkedid'];
                break;

            default:
                $schedulerparent->vendor_id = null;
                $schedulerparent->client_id = null;
                $schedulerparent->training_request_id = null;
                $schedulerparent->patient_id = null;
        }

        if($event['userid'] === "0"){
            $schedulerparent->user_id = null;
        }else{
            $schedulerparent->user_id = $event['userid'];
        }

        $schedulerparent->save();

        $scheduler->parent_id = $schedulerparent->id;
        $scheduler->title = $event['title'];

        if($event['start'] instanceof Carbon){
            $scheduler->start = $event['start'];
        }else{
            $scheduler->start = Carbon::createFromFormat('Y-m-d H:i:s', $event['start']);
        }
        if($event['end'] instanceof Carbon){
            $scheduler->end = $event['end'];
        }else{
            $scheduler->end = Carbon::createFromFormat('Y-m-d H:i:s', $event['end']);
        }

        $scheduler->contents = $event['note'];

        if(isset($event['reminderemails'])){
            if(count($event['reminderemails']) > 0){
                $scheduler->reminderemails = $event['reminderemails'];
            }
        }

        if(isset($event['reminderdate'])){
            if($event['reminderdate'] != "Click here to set date."){
                $scheduler->reminderdate = Carbon::parse($event['reminderdate']);
            }else{
                $scheduler->reminderdate = null;
            }
        }

        $scheduler->save();

        return $scheduler->event_id;
    }

    public static function UpdateEventHalf($schedulerparent, $scheduler, $event)
    {
        switch ($event['linkedtype']) {
            case "client":
                $schedulerparent->client_id = $event['linkedid'];
                $schedulerparent->vendor_id = null;
                break;
            case "vendor":
                $schedulerparent->vendor_id = $event['linkedid'];
                $schedulerparent->client_id = null;
                break;
            default:
                $schedulerparent->vendor_id = null;
                $schedulerparent->client_id = null;
        }

        if($event['userid'] === "0"){
            $schedulerparent->user_id = null;
        }else{
            $schedulerparent->user_id = $event['userid'];
        }

        $schedulerparent->save();

        $scheduler->title = $event['title'];
        $scheduler->start = Carbon::createFromFormat('Y-m-d H:i:s', $event['start']);
        $scheduler->end = Carbon::createFromFormat('Y-m-d H:i:s', $event['end']);
        $scheduler->contents = $event['note'];

        if(isset($event['reminderemails'])){
            if(count($event['reminderemails']) > 0){
                $scheduler->reminderemails = $event['reminderemails'];
            }
        }

        if(isset($event['reminderdate'])){
            if($event['reminderdate'] != "Click here to set date."){
                $scheduler->reminderdate = $event['reminderdate'];
            }
        }

        $scheduler->save();

        return $scheduler->event_id;
    }

    public static function MakeRepeat($type, $end, $schedulerparent, $note){
        switch ($type) {
            case "1":
                return self::MakeRepeatDaily($end, $schedulerparent, $note);
            case "2":
                return self::MakeRepeatDailyWeekday($end, $schedulerparent, $note);
                break;
            case "3":
                return self::MakeRepeatWeekly($end, $schedulerparent, $note);
                break;
            case "4":
                return self::MakeRepeatMontly($end, $schedulerparent, $note);
                break;
            case "5":
                return self::MakeRepeatYearly($end, $schedulerparent, $note);
                break;
        }
    }

    private static function MakeRepeatDaily($end, $schedulerparent, $note)
    {
        $newstart = $schedulerparent->start_date;
        $newstart->addDay();

        $end->hour = $newstart->hour;
        $end->minute = $newstart->minute;
        $end->second = $newstart->second;

        while ($newstart->lte($end)) {

            $newend = $newstart->copy();
            $newend->hour = $schedulerparent->end_time->hour;
            $newend->minute = $schedulerparent->end_time->minute;
            $newend->second = $schedulerparent->end_time->second;

            $scheduler = new Scheduler;
            $scheduler->parent_id = $schedulerparent->id;
            $scheduler->title = $schedulerparent->title;
            $scheduler->start = $newstart;
            $scheduler->end = $newend;
            $scheduler->contents = $note;
            $scheduler->save();

            $newstart->addDay();
        }

        $schedulerparent->repeats = 1;
        $schedulerparent->repeat_freq = 1;
        $schedulerparent->save();

        return true;
    }

    private static function MakeRepeatDailyWeekday($end, $schedulerparent, $note)
    {
        $newstart = $schedulerparent->start_date;
        $newstart->addDay();

        $end->hour = $newstart->hour;
        $end->minute = $newstart->minute;
        $end->second = $newstart->second;

        while ($newstart->lte($end)) {

            if($newstart->isWeekday()) {

                $newend = $newstart->copy();
                $newend->hour = $schedulerparent->end_time->hour;
                $newend->minute = $schedulerparent->end_time->minute;
                $newend->second = $schedulerparent->end_time->second;

                $scheduler = new Scheduler;
                $scheduler->parent_id = $schedulerparent->id;
                $scheduler->title = $schedulerparent->title;
                $scheduler->start = $newstart;
                $scheduler->end = $newend;
                $scheduler->contents = $note;
                $scheduler->save();
            }

            $newstart->addDay();
        }

        $schedulerparent->repeats = 1;
        $schedulerparent->repeat_freq = 2;
        $schedulerparent->save();

        return true;
    }

    private static function MakeRepeatWeekly($end, $schedulerparent, $note)
    {
        $newstart = $schedulerparent->start_date;
        $newstart->addWeek();

        $end->hour = $newstart->hour;
        $end->minute = $newstart->minute;
        $end->second = $newstart->second;

        while ($newstart->lte($end)) {

            $newend = $newstart->copy();
            $newend->hour = $schedulerparent->end_time->hour;
            $newend->minute = $schedulerparent->end_time->minute;
            $newend->second = $schedulerparent->end_time->second;

            $scheduler = new Scheduler;
            $scheduler->parent_id = $schedulerparent->id;
            $scheduler->title = $schedulerparent->title;
            $scheduler->start = $newstart;
            $scheduler->end = $newend;
            $scheduler->contents = $note;
            $scheduler->save();

            $newstart->addWeek();
        }

        $schedulerparent->repeats = 1;
        $schedulerparent->repeat_freq = 3;
        $schedulerparent->save();

        return true;
    }

    private static function MakeRepeatMontly($end, $schedulerparent, $note)
    {
        $newstart = $schedulerparent->start_date;
        $newstart->addMonth();

        $end->hour = $newstart->hour;
        $end->minute = $newstart->minute;
        $end->second = $newstart->second;

        while ($newstart->lte($end)) {

            $newend = $newstart->copy();
            $newend->hour = $schedulerparent->end_time->hour;
            $newend->minute = $schedulerparent->end_time->minute;
            $newend->second = $schedulerparent->end_time->second;

            $scheduler = new Scheduler;
            $scheduler->parent_id = $schedulerparent->id;
            $scheduler->title = $schedulerparent->title;
            $scheduler->start = $newstart;
            $scheduler->end = $newend;
            $scheduler->contents = $note;
            $scheduler->save();

            $newstart->addMonth();
        }

        $schedulerparent->repeats = 1;
        $schedulerparent->repeat_freq = 4;
        $schedulerparent->save();

        return true;
    }

    private static function MakeRepeatYearly($end, $schedulerparent, $note)
    {

        $newstart = $schedulerparent->start_date;
        $newstart->addYear();

        $end->hour = $newstart->hour;
        $end->minute = $newstart->minute;
        $end->second = $newstart->second;

        while ($newstart->lte($end)) {

            $newend = $newstart->copy();
            $newend->hour = $schedulerparent->end_time->hour;
            $newend->minute = $schedulerparent->end_time->minute;
            $newend->second = $schedulerparent->end_time->second;

            $scheduler = new Scheduler;
            $scheduler->parent_id = $schedulerparent->id;
            $scheduler->title = $schedulerparent->title;
            $scheduler->start = $newstart;
            $scheduler->end = $newend;
            $scheduler->contents = $note;
            $scheduler->save();

            $newstart->addYear();
        }

        $schedulerparent->repeats = 1;
        $schedulerparent->repeat_freq = 5;
        $schedulerparent->save();

        return true;
    }
}