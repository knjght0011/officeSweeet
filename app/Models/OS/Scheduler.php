<?php namespace App\Models\OS;

use App\Helpers\OS\NotificationHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scheduler extends Model
{
    use SoftDeletes;

    protected $table = 'scheduler';
    
    protected $dates = ['start', 'end', 'reminderdate'];
    
    protected $primaryKey = 'event_id';

    protected $appends = ['user_id'];

    protected $casts = ['reminderemails' => 'array'];

    public function getUserIdAttribute()
    {
        return $this->schedulerparent->user_id;
    }

    public function schedulerparent()
    {
        return $this->belongsTo('App\Models\OS\SchedulerParent', 'parent_id');
    }    

    /*
    function getuseridAttribute() {
        return $this->schedulerparent->user_id;
    }
    */

    public function fromatReminderDate(){
        if($this->reminderdate === null){
            return "";
        }else{
            return $this->reminderdate->toDateTimeString();
        }

    }

    function Notify(){

        NotificationHelper::CreateNotification('New Apointment: ' . $this->title, 'Start: ' . $this->start->toDateTimeString(), $this->start->timestamp, 'scheduler', $this->user_id);
    }

    public function getNote(){
        return "Sched: " . $this->title  . "<br>Notes: " . $this->contents . "<br>Date: " . $this->start->toDateString() . "<br>With: " . $this->schedulerparent->getUsersName();
    }
}
