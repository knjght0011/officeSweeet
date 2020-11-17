<?php
namespace App\Models\OS\Training;

use App\Models\OS\Scheduler;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingRequest extends Model
{
    use SoftDeletes;

    public $ScheduleModel = null;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function TrainingModule()
    {
        return $this->belongsTo('App\Models\OS\Training\TrainingModule', 'training_id')->withTrashed();
    }

    public function Schedule()
    {
        return $this->hasOne('App\Models\OS\SchedulerParent', 'training_request_id', 'id');
    }

    public function getScheduleDetails(){
        if($this->ScheduleModel === null) {
            $this->load('Schedule');
            if($this->Schedule === null){
                return null;
            }else{
                $schedule = Scheduler::where('parent_id', $this->Schedule->id)->first();
                if(count($schedule) === 1){
                    $this->ScheduleModel = $schedule;
                    return $this->ScheduleModel;
                }else{
                    return null;
                }
            }
        }else{
            return $this->ScheduleModel;
        }
    }

    public function Status()
    {
        if($this->deleted_at === null){
            if ($this->status === null) {
                return "Pending";
            } else {
                return $this->status;
            }
        }else{
            return "Deleted";
        }
    }
}
