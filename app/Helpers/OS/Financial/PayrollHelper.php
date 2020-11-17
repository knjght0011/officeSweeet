<?php
namespace App\Helpers\OS\Financial;

use Carbon\Carbon;

use App\Models\OS\Financial\Payroll;
use App\Models\OS\Financial\PayrollUser;

class PayrollHelper
{
    public static function SaveEmployeePayrollRow($userid, $payrollid, $row)
    {
        if($row['id'] === "0"){
            $data = new PayrollUser;
        }else{
            $data = PayrollUser::where('id', $row['id'])->first();
        }
        $data->user_id = intval($userid);
        $data->payroll_id = intval($payrollid);
        $data->final = 0;
        $data->description = $row['description'];
        $data->comment = $row['comment'];
        $data->taxable = $row['tax'];
        $data->netpay = floatval(str_replace(",","",$row['amount']));
        $data->units = $row['qty'];
        $data->save();
        
        return $data->id;
    }
    
    public static function FinaliseEmployeePayrollRow($id)
    {

        $data = PayrollUser::where('user_id', $id)->get();
        foreach($data as $temp){
            $temp->final = 1;
            $temp->save();
        }
        return true;
    }
    
    public static function DeletedRow ($payrollid){
        
         $payrolluser = PayrollUser::where('id', $payrollid)->first();
         
         if(count($payrolluser) === 1){
            if($payrolluser->final === 0){
               $payrolluser->delete();
               return true;
            }else{
               return false;
            }
         }else{
             return false;
         }
    }

    public static function GetNextPayPeriod($freqency, $option, $first)
    {
        $lastpayroll = Payroll::latest()->first();
        if(count($lastpayroll) === 0){
            //make first ever payroll
            
            $payroll = new Payroll;
            $payroll->mode = $freqency;
            $payroll->end = self::GetFirstEndDate($first);
            $payroll->start = self::GetFirstStartDate(self::GetFirstEndDate($first), $freqency, $option);
            $payroll->final = 0;
            $payroll->save();

            return $payroll;
        }else{
            if($lastpayroll->final === 0){
                //still have open payroll continue with that one.
                return $lastpayroll;
            }else{
                //last payroll finalised, make next one
                $payroll = new Payroll;
                $payroll->mode = $freqency;
                $payroll->final = 0;
                $payroll->start = self::StartDate($lastpayroll->end);
                $payroll->end = self::EndDate(self::StartDate($lastpayroll->end), $lastpayroll->end, $freqency, $option);
                $payroll->save();
                
                return $payroll;
            }
        }
    }
    
    public static function StartDate($lastend)
    {
        $start = $lastend->addday();
        $start->hour = 00;
        $start->minute = 00;
        $start->second = 00;
        Return $start;
    }
    
    public static function EndDate($start, $lastend, $frequency, $option)
    {
        switch ($frequency) {
            case "weekly":
                $end = $lastend;
                $end->next();
                $end->hour = 23;
                $end->minute = 59;
                $end->second = 59;
                Return $end;
            case "biweekly":
                $end = $lastend;
                $end->next();
                $end->next();
                $end->hour = 23;
                $end->minute = 59;
                $end->second = 59;
                Return $end;
            case "semimonthly":
                if(intval($start->format("j")) === 1){
                    //1st of month
                    $date = Carbon::createFromDate($start->format("Y"), $start->format("m"), self::MonthlySplit($option));
                    $date->hour = 00;
                    $date->minute = 00;
                    $date->second = 00;
                    
                    return $date;
                }else{
                    //first of the month
                    $date = Carbon::createFromDate($start->format("Y"), $start->format("m"), 1, 00);
                    $date->lastOfMonth();
                    $date->hour = 00;
                    $date->minute = 00;
                    $date->second = 00;
                    
                    return $date;
                }
            case "monthly":
                if($option === "last"){
                    $date = Carbon::createFromDate($start->format("Y"), $start->format("m"), 1, 00);
                    $date->endOfMonth();
                    $date->hour = 00;
                    $date->minute = 00;
                    $date->second = 00;
                    
                    return $date;
                }else{
                    $date = Carbon::createFromDate($start->format("Y"), $start->format("m") + 1, self::MonthlySplit($option), 00);
                    #$date->endOfMonth();
                    $date->hour = 00;
                    $date->minute = 00;
                    $date->second = 00;
                    
                    return $date;
                }
        }
    }
    
    
    public static function GetFirstEndDate($first)
    {
        
        if(false){

        }else {
            
            $date = Carbon::parse($first);
            $date->hour = 23;
            $date->minute = 59;
            $date->second = 59;

            return $date;
        }
    }
    

    public static function GetFirstStartDate($end, $freqency, $option)
    {
        switch ($freqency) {
            case "weekly":
                $date = $end;
                $date->subDays(7);
                $date->hour = 00;
                $date->minute = 00;
                $date->second = 00;
                return $date;
            case "biweekly":
                $date = $end;
                $date->subDays(14);
                $date->hour = 00;
                $date->minute = 00;
                $date->second = 00;
                return $date;
            case "semimonthly":
                if(intval($end->format("j")) > self::MonthlySplit($option)){
                    //2nd date in option
                    $date = Carbon::createFromDate($end->format("Y"), $end->format("m"), self::MonthlySplit($option, false));
                    $date->hour = 00;
                    $date->minute = 00;
                    $date->second = 00;
                    
                    return $date;
                }else{
                    //first of the month
                    $date = Carbon::createFromDate($end->format("Y"), $end->format("m"), 1, 00);
                    $date->hour = 00;
                    $date->minute = 00;
                    $date->second = 00;
                    
                    return $date;
                }
            case "monthly":
                if($option === "last"){
                    $date = $end;
                    $date->subMonths(1);
                    $date->hour = 00;
                    $date->minute = 00;
                    $date->second = 00;
                    return $date;
                    
                }else{
                    $date = $end;
                    $date->subMonths(1);
                    $date->hour = 00;
                    $date->minute = 00;
                    $date->second = 00;
                    $date->addday();
                    return $date; 
                }
        }
    }
    
    public static function daysInPeriod($freqency, $start, $end)
    {
        switch ($freqency) {
            case "weekly":
                Return 7;
            case "biweekly":
                Return 14;
            case "semimonthly":
                Return $start->diffInDays($end) + 1;
            case "monthly":
                Return $start->diffInDays($end) + 1;
        }
    }    
    
    public static function MonthlySplit($option, $end = true)
    {
        $split = explode("/", $option);
        if($end === true){
            return intval($split[0]);
        }else{
            return intval($split[1]);
        }
    }

    public static function convertoption($freq, $option){
        switch ($freq) {
            case "weekly":
            case "biweekly":
                switch ($option) {
                    case "Monday":
                        return "1";
                    case "Tuesday":
                        return "2";
                    case "Wednesday":
                        return "3";
                    case "Thursday":
                        return "4";
                    case "Friday":
                        return "5";
                    case "Saturday":
                        return "6";
                    case "Sunday":
                        return "7";
                }
            case "monthly":
            case "semimonthly":
                switch ($option) {
                    case "1st/2nd":
                        return "1";
                    case "2nd/3rd":
                        return "2";
                    case "3rd/4th":
                        return "3";
                    case "5th/6th":
                        return "4";
                    case "5th/6th":
                        return "5";
                    case "6th/7th":
                        return "6";
                    case "7th/8th":
                        return "7";
                    case "8th/9th":
                        return "8";
                    case "9th/10th":
                        return "9";
                    case "10th/11th":
                        return "10";
                    case "11th/12th":
                        return "11";
                    case "12th/13th":
                        return "12";         
                    case "13th/14th":
                        return "13";
                    case "14th/15th":
                        return "14";
                    case "15th/16th":
                        return "15";
                    case "16th/17th":
                        return "16";
                    case "17th/18th":
                        return "17";
                    case "18th/19th":
                        return "18"; 
                    case "19th/20th":
                        return "19";
                    case "20th/21st":
                        return "20";
                    case "21st/22nd":
                        return "21";
                    case "22nd/23rd":
                        return "22";
                    case "23rd/24th":
                        return "23";
                    case "24th/25th":
                        return "24"; 
                    case "25th/26th":
                        return "25";
                    case "26th/27th":
                        return "26";
                    case "27th/28th":
                        return "27";
                    case "Last":
                        return "Last";                       
                }
        }        
    }    
}
