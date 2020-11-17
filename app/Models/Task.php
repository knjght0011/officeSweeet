<?php

namespace App\Models;

use Carbon\Carbon;

use App\Helpers\FormatingHelper;

use App\Helpers\OS\NotificationHelper;

class Task extends CustomBaseModel
{
    protected $table = 'tasklist';
    protected $dates = ['date'];

    public function scheduler()
    {
        return $this->belongsTo('App\Models\OS\Scheduler' , 'duedate_id', 'event_id' )->withTrashed();
    }


    public function formatDate()
    {
        
        if($this->completedate === null){
            return "";
        }else{
            $carbon = Carbon::parse($this->completedate);
            return FormatingHelper::DateISO($carbon);
        }
        
    }
    
    public function returnString()
    {
        $returnstring = $this->id;
        $returnstring .= "|";
        $returnstring .= $this->user_id;
        $returnstring .= "|";
        $returnstring .= $this->taskname;
        $returnstring .= "|";
        $returnstring .= $this->description;
        $returnstring .= "|";
        $returnstring .= $this->formatDate_created_at_iso();
        $returnstring .= "|";
        $returnstring .= $this->getDueDate();
        $returnstring .= "|";
        $returnstring .= $this->formatDate();
        $returnstring .= "|";
        $returnstring .= $this->status;
        $returnstring .= "|";
        $returnstring .= $this->getDueDateAll();
        
        return $returnstring;
    }
    
    public function statusCSS()
    {
        switch ($this->status) {
            case "Important":
                return "info";
            case "Urgent":
                return "warning";
            case "Critical":
                return "danger";
            case "Complete":
                return "success";
            default:
                return "success";
        }
    }

    public function getDueDate(){

        if(count($this->scheduler) === 1){
            return $this->scheduler->start->toDateString();
        }else{
            return "None Set";
        }
        
    }

    public function getDueDateAll(){

        if(count($this->scheduler) === 1){
            return $this->scheduler->start;
        }else{
            return "None Set";
        }

    }
    
    public function Notify(){
        NotificationHelper::CreateNotification('New Item On Your Tasklist', 'Description: ' . $this->description, '', 'tasklist', $this->user_id);
    }
}