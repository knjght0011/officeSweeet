<?php

namespace App\Models\OS;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Models\Quote;
use App\Helpers\FormatingHelper;

class RecurringInvoice extends Model
{
    protected $table = 'recurringinvoice';

    protected $dates = ['start', 'end', 'lastrun'];

    public function quote()
    {
        return $this->hasOne('App\Models\Quote', 'is_recurring', 'id');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Quote', 'recurringinvoice_id', 'id');
    }

    public function email_contact()
    {
        return $this->belongsTo('App\Models\ClientContact' , 'email_to', 'id' );
    }


    public function StartIso(){
        return FormatingHelper::DateISO($this->start);
    }

    public function Canend(){
        if($this->end === null){
            return true;
        }else{
            if($this->end->gt(Carbon::now())){
                return true;
            }else{
                return false;
            }
        }
    }

    public function EndIso(){
        if($this->end === null){
            return "Forever";
        }else{
            return FormatingHelper::DateISO($this->end);
        }
    }

    public function NextNumber(){
        return $this->Number() + 1;
    }

    public function Number(){
        return count($this->invoicces) + 1;
    }
    /**
    1 - Daily - Every Day
    2 - Daily - Weekday Only
    3 - Weekly
    4 - Monthly
    5 - Last day of Month
    6 - Yearly
    7 - Every X Days
     */

    public function NextDate()
    {
        if($this->lastrun === null){
            $date = $this->start;
        }else{
            $date = $this->lastrun;
        }

        switch ($this->repeat_freq) {
            case 1:
                $nextdate = $date->addDay();
                break;
            case 2:
                if($date->dayOfWeek === 5){
                    $nextdate = $date->addDays(3);
                }else{
                    if($date->dayOfWeek === 6){
                        $nextdate = $date->addDays(2);
                    }else{
                        $nextdate = $date->addDay();
                    }
                }
                break;
            case 3:
                $nextdate = $date->addWeek();
                break;
            case 4:
                $nextdate = $date->addMonth();
                break;
            case 5:
                $nextdate = $date->modify('last day of next month');
                break;
            case 6:
                $nextdate = $date->addYear();
                break;
            case 7:
                $nextdate = $date->addDays($this->repeat_number);
                break;
        }

        return $nextdate;
    }
}
