<?php
namespace App\Models;

use Carbon\Carbon;

class MonthEnd extends CustomBaseModel
{
    protected $table = 'monthend';

    protected $dates = ['date'];
    
    public function formatedEndingBalence()
    {
        return "$" . number_format($this->endingbalence , 2, '.', '');
    }
    
    public function LastDayOfMonth()
    {
        $date = new \DateTime($this->year . "-" . $this->month ."-01");
        $date->modify('last day of this month');
        return $date;
    }
    
    public function LastDayOfMonthCarbon()
    {
        $temp = new Carbon($this->year . "-" . $this->month ."-01");
        $temp->endOfMonth();
        $temp->hour = 00;
        $temp->minute = 00;
        $temp->second = 00;
        return $temp;
    }
    
    public function FirstDayOfMonth()
    {
        $date = new \DateTime($this->year . "-" . $this->month ."-01");
        $date->modify('first day of this month');
        return $date;
    }
    
    public function NextMonth()
    {
        if($this->month === 12){
            $month = 1;
        }else{
            $month = intval($this->month) + 1;
        }

        if(strlen($month) === 1){
            $month = "0" . $month;
        }

        return $month;
    }

    public function NextMonthText()
    {
        switch ($this->NextMonth()) {
            case "01":
                return "January";
            case "02":
                return "Febuary";
            case "03":
                return "March";
            case "04":
                return "April";
            case "05":
                return "May";
            case "06":
                return "June";
            case "07":
                return "July";
            case "08":
                return "August";
            case "09":
                return "September";
            case "10":
                return "October";
            case "11":
                return "November";
            case "12":
                return "December";
        }

    }
    
    public function NextMonthYear()
    {
        if($this->month === 12){
            $year = "20" . intval($this->year) + 1;
        }else{
            $year = "20" . intval($this->year);
        }
        
        return $year;
    }
    
    //returns a dateperiod of how old this month end is. (maily used for working out number of monthends snce first month end.)
    public function MonthsOldPeriod()
    {
        $start = $this->FirstDayOfMonth();
        
        $end = new \DateTime();
        $end->modify('first day of this month');
        
        $interval = \DateInterval::createFromDateString('1 month');
        
        return new \DatePeriod($start, $interval, $end);
    }
    
    //returns exact number of months that this month end is old
    public function MonthsOld()
    {
        $start = $this->FirstDayOfMonth();

        $end = new \DateTime();
        $end->modify('first day of this month');
        
        $interval = $start->diff($end);

        return $interval->format("%m") + (12 * $interval->format("%y"));

    }
    
    public function IsBeforeThis($date)
    {
        if($date->lte($this->LastDayOfMonthCarbon())){
        //if($this->LastDayOfMonthCarbon()->diffInDays($date, false) < 1 ){
            return true;
        }else{
            return false;
        }
    }

}
