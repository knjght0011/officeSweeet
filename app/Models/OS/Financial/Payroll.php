<?php
namespace App\Models\OS\Financial;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
     protected $table = 'payroll';
    
     protected $dates = ['start','end'];
     
    public function payrollusers()
    {
        return $this->hasMany('App\Models\OS\Financial\PayrollUser', 'payroll_id', 'id');
    }

    
    public function daysInPeriod()
    {
        switch ($this->mode) {
            case "weekly":
                Return 7;
            case "biweekly":
                Return 14;
            case "semimonthly":
                Return $this->start->diffInDays($this->end) + 1;
            case "monthly":
               Return $this->start->diffInDays($this->end) + 1;
        }
    } 
    
    public function PayrollForUser($id){
        $records = $this->payrollusers->where('user_id', $id);
        if(count($records) > 0){
            return $records;
        }else{
            return false;
        }
    }
    
    public function PayrollForUserTotal($id){
        $temp = $this->PayrollForUser($id);
        if($temp === false){
            return "$0.00";
        }else{
            $total = 0.00;
            foreach($temp as $t){
                $total = $total + $t->total;
            }
            return "$" . number_format($total, 2, '.', '');
        }
    }    
    public function PayrollForUserGlyphicon($id){
        if($this->PayrollForUser($id) === false){
            return "glyphicon-remove";
        }else{
            return "glyphicon-ok";
        }
    }
    
    public function PayrollForUserGlyphiconColor($id){
        if($this->PayrollForUser($id) === false){
            return "red";
        }else{
            return "green";
        }
    }
}
