<?php
namespace App\Http\Controllers\OS\Training;

use App\Helpers\OS\NotificationHelper;
use App\Helpers\OS\Scheduler\ScheduleHelper;
use App\Helpers\OS\Users\UserHelper;
use App\Http\Controllers\Controller;
use App\Models\OS\Scheduler;
use App\Models\OS\SchedulerParent;
use App\Models\OS\Training\TrainingModule;
use App\Models\OS\Training\TrainingRequest;
use App\Models\User;
use Illuminate\Support\Facades\Input;

class TrainingController extends Controller
{
    public function index()
    {
        $trainingModules = TrainingModule::all();

        return view('OS.Training.index')
                ->with('trainingModules', $trainingModules);
    }

    public function DepartmentSearch($subdomain, $department, $trainingModuleID){

        $trainingModules = TrainingModule::all();
        if($department === "all"){
            $DepartmentUsers = UserHelper::GetAllUsers();
        }else if($department === "none"){
            $DepartmentUsers = UserHelper::GetAllUsersWhere("department", "");
        }else{
            $DepartmentUsers = UserHelper::GetAllUsersWhere("department", $department);
        }

        return view('OS.Training.index')
            ->with('trainingModules', $trainingModules)
            ->with('DepartmentUsers', $DepartmentUsers)
            ->with('trainingModuleID', $trainingModuleID);

    }

    public function addTrainingBulk(){

        $trainingmodule = TrainingModule::where('id', Input::get('module_id'))->first();

        if(count($trainingmodule) === 1){
            foreach (Input::get('user_ids') as $userids){

                $currentuser =  UserHelper::GetOneUserByID($userids);
                if(count($currentuser) === 1){
                    $data = array(
                        'training-schedule-date' => Input::get('training-schedule-date'),
                        'training-due-date' => Input::get('training-due-date'),
                        'training-reminder-date' => Input::get('training-reminder-date'),
                    );

                    $status = $this->addTrainingAction($data, $trainingmodule, $currentuser);

                    NotificationHelper::CreateNotification('New Training', 'New Training ' . $trainingmodule->title . ' added to your schedule', '', 'training', $currentuser->id);
                }
            }

            return ['status' => 'OK'];
        }else{
            return ['status' => 'TrainingModuleNotFound'];
        }

    }

    public function addTraining(){
        $data = array(
            'user_id' => Input::get('user_id'),
            'trainingmodule_id' => Input::get('trainingmodule_id'),
            'training-schedule-date' => Input::get('training-schedule-date'),
            'training-due-date' => Input::get('training-due-date'),
            'training-reminder-date' => Input::get('training-reminder-date'),
        );

        $trainingmodule = TrainingModule::where('id', $data['trainingmodule_id'])->first();
        if(count($trainingmodule) === 1){
            $user = UserHelper::GetOneUserByID($data['user_id']);
            if(count($user) === 1){
                return $this->addTrainingAction($data, $trainingmodule, $user);
            }else{
                return ['status' => 'UserNotFound'];
            }
        }else{
            return ['status' => 'TrainingModuleNotFound'];
        }

    }


    public function addTrainingAction($data, $trainingmodule, $user){

        $trainingrequest = new TrainingRequest;

        $trainingrequest->user_id = $user->id;
        $trainingrequest->training_id = $trainingmodule->id;

        if($data['training-due-date'] != ""){
            $trainingrequest->due = $data['training-due-date'];
        }else{
            $trainingrequest->due = null;
        }

        $trainingrequest->status = null;
        $trainingrequest->save();

        if($data['training-schedule-date'] != ""){
            $scheduler = new Scheduler;
            $schedulerparent = new SchedulerParent;

            $event['title'] = "Training " . $trainingrequest->TrainingModule->title;
            $event['start'] = $data['training-schedule-date'].":00";
            $event['end'] = $data['training-schedule-date'].":00"; //might not work
            $event['linkedtype'] = "training";
            $event['linkedid'] = $trainingrequest->id;
            $event['userid'] = $user->id;
            $event['note'] = "";

            if($data['training-reminder-date'] != ""){
                $event['reminderemails'] = [$user->email];
                $event['reminderdate'] = $data['training-reminder-date'];
            }

            $id = ScheduleHelper::UpdateEventFull($schedulerparent, $scheduler, $event);
            return ['status' => 'OK', 'TrainingRequest' => $trainingrequest, 'TrainingModule' => $trainingmodule, 'TrainingSchedule' => $trainingrequest->getScheduleDetails()];
        }else{
            return ['status' => 'OK', 'TrainingRequest' => $trainingrequest, 'TrainingModule' => $trainingmodule, 'TrainingSchedule' => null];
        }

    }

    public function completeTraining(){

        $trainingrequest = TrainingRequest::where('id', Input::get('id'))->first();
        if(count($trainingrequest) === 1){
            $trainingrequest->status = Carbon::now();
            $trainingrequest->save();

            return ['status' => 'OK', 'date' => $trainingrequest->status->toDateString()];
        }else{
            return ['status' => 'NotFound'];
        }
    }

    public function deleteTraining(){

        $trainingrequest = TrainingRequest::where('id', Input::get('id'))->first();
        if(count($trainingrequest) === 1){
            $trainingrequest->delete();

            return ['status' => 'OK', 'date' => $trainingrequest->deleted_at->toDateString()];
        }else{
            return ['status' => 'NotFound'];
        }
    }

    public function editTraining(){

        $data = array(
            'request-id' => Input::get('id'),
            'training-schedule-date' => Input::get('training-schedule-date'),
            'training-due-date' => Input::get('training-due-date'),
            'training-reminder-date' => Input::get('training-reminder-date'),
        );


        $trainingrequest = TrainingRequest::where('id', $data['request-id'])->first();
        if(count($trainingrequest) === 1){

            if($data['training-due-date'] != ""){
                $trainingrequest->due = $data['training-due-date'];
            }else{
                $trainingrequest->due = null;
            }

            $trainingrequest->save();

            if($data['training-schedule-date'] != ""){

                if(count($trainingrequest->Schedule) === 1){

                    $Schedule = $trainingrequest->getScheduleDetails();
                    $Schedule->start = $data['training-schedule-date'].":00";
                    $Schedule->end = $data['training-schedule-date'].":00";
                    if($data['training-reminder-date'] != ""){
                        $Schedule->reminderemails = [$trainingrequest->user->email];
                        $Schedule->reminderdate = $data['training-reminder-date'];
                    }

                    $Schedule->save();

                    return ['status' => 'OK', 'TrainingRequest' => $trainingrequest, 'TrainingSchedule' => $Schedule];

                }else{
                    $scheduler = new Scheduler;
                    $schedulerparent = new SchedulerParent;

                    $event['title'] = "Training " . $trainingrequest->TrainingModule->title;
                    $event['start'] = $data['training-schedule-date'].":00";
                    $event['end'] = $data['training-schedule-date'].":00"; //might not work
                    $event['linkedtype'] = "training";
                    $event['linkedid'] = $trainingrequest->id;
                    $event['userid'] = $trainingrequest->user_id;
                    $event['note'] = "";

                    if($data['training-reminder-date'] != ""){
                        $event['reminderemails'] = [$trainingrequest->user->email];
                        $event['reminderdate'] = $data['training-reminder-date'];
                    }

                    $id = ScheduleHelper::UpdateEventFull($schedulerparent, $scheduler, $event);
                    return ['status' => 'OK', 'TrainingRequest' => $trainingrequest, 'TrainingSchedule' => $trainingrequest->getScheduleDetails()];
                }

            }

            return ['status' => 'OK', 'TrainingRequest' => $trainingrequest,];
        }else{
            return ['status' => 'TrainingRequestNotFound'];
        }

    }
}