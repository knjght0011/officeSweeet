<?php

namespace App\Http\Controllers\OS;

use App\Helpers\OS\Scheduler\ScheduleHelper;
use App\Http\Controllers\Controller;
use App\Models\OS\Scheduler;
use App\Models\OS\SchedulerParent;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

use App\Models\Task;



class TaskManagerController extends Controller
{
    public function ShowTasksMobile()
    {
        $tasks = Task::all();

        return View::make('OS.TaskList.view')
            ->with('tasks', $tasks);
    }

    public function SaveTask()
    {
        $data = array(
            'id' => Input::get('id'),
            'user_id' => Input::get('user_id'),
            'taskname' => Input::get('taskname'),
            'description' => Input::get('description'),
            'status' => Input::get('status'),
            'duedate' => Input::get('duedate'),
            'isproject' => Input::get('isproject'),
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

        $task->IsProject = $data['isproject'];

        $task->status = $data['status'];

        if($data['duedate'] === ""){
            $task->duedate_id = null;
        }else{
            if($task->duedate_id === null){
                $scheduler = new Scheduler;
                $schedulerparent = new SchedulerParent;

                $event['title'] = "Deadline: " . $task->taskname;
                if(strlen($data['duedate']) === 16){
                    $event['start'] = $data['duedate'].":00";
                    $event['end'] = $data['duedate'].":00"; //might not work
                }else{
                    $event['start'] = $data['duedate'];
                    $event['end'] = $data['duedate']; //might not work
                }

                $event['userid'] = $task->user_id;
                $event['note'] = $task->description;

                $event['linkedtype'] = "";

                //if($data['training-reminder-date'] != ""){
                //    $event['reminderemails'] = [$user->email];
                //    $event['reminderdate'] = $data['training-reminder-date'];
                //}

                $task->duedate_id = ScheduleHelper::UpdateEventFull($schedulerparent, $scheduler, $event);

            }else{

                $scheduler = $task->scheduler;
                $schedulerparent = $scheduler->schedulerparent;

                $event['title'] = "Deadline: " . $task->taskname;
                if(strlen($data['duedate']) === 16){
                    $event['start'] = $data['duedate'].":00";
                    $event['end'] = $data['duedate'].":00"; //might not work
                }else{
                    $event['start'] = $data['duedate'];
                    $event['end'] = $data['duedate']; //might not work
                }
                $event['userid'] = $task->user_id;
                $event['note'] = $task->description;

                $event['linkedtype'] = "";

                $task->duedate_id = ScheduleHelper::UpdateEventFull($schedulerparent, $scheduler, $event);




            }
        }

        $task->save();

        if($data['id'] === "0"){
            $task->Notify();
        }


        $workingstart = Carbon::tomorrow()->setTime("11","00", "00");
        $workingend = Carbon::tomorrow()->setTime("11","30", "00");

        do  {
            $scheduler = new Scheduler;
            $schedulerparent = new SchedulerParent;

            $event['title'] = $task->taskname;

            $event['start'] = $workingstart;
            $event['end'] = $workingend;

            $event['userid'] = $task->user_id;
            $event['note'] = $task->description;

            $event['linkedtype'] = "";
            $event['title'] =  "Project: " .$task->taskname;

            $task->duedate_id = ScheduleHelper::UpdateEventFull($schedulerparent, $scheduler, $event);

            $workingstart = $workingstart->addDays(1);
            $workingend = $workingend->addDays(1);

        }while ($workingstart <= $data['duedate']);

        return ['status' => 'complete', 'returnString' => $task->returnString()];

    }

    public function MarkComplete(){

        $id = Input::get('id');

        $task = Task::where('id', $id)->first();
        if(count($task) === 1){

            $task->status = "Complete";
            $task->completedate = Carbon::now();
            $task->save();

            return ['status' => 'OK', 'returnString' => $task->returnString()];
        }else{
            return ['status' => 'notfound'];
        }

    }

    private function GetTask($id)
    {
        if($id === "0"){
            return new Task;
        }else{
            return Task::find($id);
        }
    }

}
