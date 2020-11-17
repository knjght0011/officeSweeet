<?php

namespace App\Http\Controllers;

#use Session;
use App\Http\Controllers\Controller;
#use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
#use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use DateTime;
use DateInterval;
use DatePeriod;

use \App\Providers\EventLog;

//Models
#use App\Models\PaymentsAdjustments;
use App\Models\Check;
use App\Models\Receipt;
#use App\Models\Client;
use App\Models\Deposit;
use App\Models\MonthEnd;
use App\Models\ExpenseAccountCategory;
use App\Models\OS\Financial\Asset;

class MonthEndController extends Controller {
    
    public function showSummery()
    {   
        $monthened = MonthEnd::get();
        
        if(count($monthened) === 0){
            return $this->FirstRun();
        }else{
            return $this->Summery($monthened);
        }
    }
    
    public function ConfirmRecentBalance()
    {
        $monthened = MonthEnd::get();
        
        if(count($monthened) === 0){
            return $this->ConfirmFirstBalance();
        }else{
            return $this->ConfirmBalance();
        }
    }
    
    private function ConfirmBalance()
    {   
        $date = date('Y') . "-" . date('m') . "-01";

        $monthened = MonthEnd::get()->last();
                
        if($monthened->MonthsOld() === "1"){
                
            return "none to do yet";

        }else{
                
            $month = $monthened->NextMonth();
            $year = $monthened->NextMonthYear();
            
            $deposits = Deposit::whereMonth('date', '=', $month)
                                ->whereYear('date', '=', $year)
                                ->get();
            $checks = Check::whereMonth('date', '=', $month)
                            ->whereYear('date', '=', $year)
                            ->where('printed', '!=', null)
                            ->get();
            $receipts = Receipt::whereMonth('date', '=', $month) 
                                ->whereYear('date', '=', $year)
                                ->get();

            $equitys = Asset::whereMonth('date', '=', $month)
                ->whereYear('date', '=', $year)
                ->where('journal','=', 1)
                ->get();

            $date = $year ."-" . $month . "-01";

            $beginningbalance = floatval($monthened->endingbalence);
            $credits = floatval(0);
            $debits = floatval(0);

            foreach($deposits as $deposit){
                $credits = $credits + $deposit->amount;
            }

            foreach($equitys as $equity) {
                $credits = $credits + $equity->amount;
            }

            foreach($checks as $check){
                $debits = $debits + $check->amount;
            }

            foreach($receipts as $receipt){
                $debits = $debits + $receipt->amount;
            }

            $endingbalance = $beginningbalance + $credits - $debits;
            
            $end = new MonthEnd;
            $end->endingbalence = $endingbalance;
            $end->month = $month;
            $end->year = substr($year, -2);
            $end->date = $date;
            $end->save();

            EventLog::add('Month end created with balance:'.$end->endingbalence.' balance date:'. $date);

            if($end->MonthsOld() === 1){
                return "noneleft";
            }else{
                return "done";
            }
        }
    }
    
    private function ConfirmFirstBalance()
    {   
        $date = date('Y') . "-" . date('m') . "-01";

        $deposits = Deposit::whereDate('date', '<', $date)->get();
        $checks = Check::whereDate('date', '<', $date)->where('printed', '!=', null)->get();
        $receipts = Receipt::whereDate('date', '<', $date)->get();

        $equitys = Asset::whereDate('date', '<', $date)
                        ->where('journal','=', 1)
                        ->get();

        $beginningbalance = floatval(0);
        $credits = floatval(0);
        $debits = floatval(0);


        foreach($deposits as $deposit){
            $credits = $credits + $deposit->amount;
        }

        foreach($equitys as $equity) {
            $credits = $credits + $equity->amount;
        }

        foreach($checks as $check){
            $debits = $debits + $check->amount;
        }

        foreach($receipts as $receipt){
            $debits = $debits + $receipt->amount;
        }

        $endingbalance = $beginningbalance + $credits - $debits;

        $end = new MonthEnd;
        $end->endingbalence = $endingbalance;
        $end->month = date('m') - 1;
        $end->year = date('y');
        $end->date = $date;
        $end->save();

        EventLog::add('Intitial month end created with balance:'.$end->endingbalence.' balance date:'. $date);

        return "done";
    }
    
    private function Summery($monthened)
    {   

        //how many months since first monthend
        $period = $monthened->first()->MonthsOldPeriod();
        
        $end = new DateTime();
        $end->modify('first day of this month');
        
        foreach($period as $dt){
            $month = $monthened->where('year', '=', $dt->format("y"))
                                ->where('month', '=', $dt->format("m"))
                                ->first();
            
            if(count($month) === 1){
                $ends[$dt->format("F Y")] = $month;
            }else{
                $interval = $dt->diff($end);
                
                $differnce = $interval->format("%m") + (12 * $interval->format("%y"));
                        
                switch ($differnce) {
                    case "0":
                        $ends[$dt->format("F Y")] = "next";
                        break;
                    case "1":
                        $ends[$dt->format("F Y")] = "pending";
                        break;
                    default:
                        $ends[$dt->format("F Y")] = "overdue";
                        break;
                }
            }
        }
        
        return View::make('Journal.MonthEnd.summery')
                    ->with('ends', $ends)
                    ->with('end', $end)
                    ->with('period', $period);

    }
    
    public function showNext()
    {   
        $monthened = MonthEnd::get()->last();
        
        if($monthened->MonthsOld() === "1"){

            return Redirect::to('/Journal/View');

        }else{
                
            $month = $monthened->NextMonth();
            $year = $monthened->NextMonthYear();

            $deposits = Deposit::whereMonth('date', '=', $month)
                                ->whereYear('date', '=', $year)
                                ->get();
            $checks = Check::whereMonth('date', '=', $month)
                            ->whereYear('date', '=', $year)
                            ->where('printed', '!=', null)
                            ->get();
            $receipts = Receipt::whereMonth('date', '=', $month) 
                                ->whereYear('date', '=', $year)
                                ->get();

            $equitys = Asset::whereMonth('date', '=', $month)
                ->whereYear('date', '=', $year)
                ->where('journal','=', 1)
                ->get();

            $beginningbalance = floatval($monthened->endingbalence);
            $credits = floatval(0);
            $debits = floatval(0);

            foreach($deposits as $deposit){
                $credits = $credits + $deposit->amount;
            }

            foreach($equitys as $equity) {
                $credits = $credits + $equity->amount;
            }

            foreach($checks as $check){
                $debits = $debits + $check->amount;
            }

            foreach($receipts as $receipt){
                $debits = $debits + $receipt->amount;
            }

            $endingbalance = $beginningbalance + $credits - $debits;

            $ExpenseAccountCategorysExpence = ExpenseAccountCategory::where('type', '!=', 'income')
                ->with('subcategories')->orderBy('category','ASC')->get();

            $ExpenseAccountCategorysDeposit = ExpenseAccountCategory::where('type', '!=', 'expense')
                ->with('subcategories')->orderBy('category','ASC')->get();


            return View::make('Journal.MonthEnd.run')
                ->with('beginningbalance', $beginningbalance)
                ->with('credits', $credits)
                ->with('debits', $debits)
                ->with('endingbalance', $endingbalance)                
                ->with('month', $monthened->NextMonthText())
                ->with('deposits', $deposits)
                ->with('checks', $checks)
                ->with('receipts', $receipts)
                ->with('equitys', $equitys)
                ->with('ExpenseAccountCategorysExpence', $ExpenseAccountCategorysExpence)
                ->with('ExpenseAccountCategorysDeposit', $ExpenseAccountCategorysDeposit);
        }
    }    
    
    public function undoLast()
    {   
        $monthened = MonthEnd::get();
                
        if(count($monthened) === 1){
            return "error";
        }else{
            $monthendlast = $monthened->last();
            if(count($monthendlast) === 1){
                $monthendlast->delete();

                return "rolledback";
            }else{
                return "error";
            }
        }
    }


    public function FirstRun()
    {

        $deposits = Deposit::All();
        $checks = Check::All();
        $receipts = Receipt::All();
        $equitys = Asset::where('journal','=', 1)->get();

        $count = count($deposits) + count($checks) + count($receipts) + count($equitys);

        $date = Carbon::now()->subDay();

        if($count === 0){

            return View::make('Journal.MonthEnd.first')
                ->with('date', $date);

        }else{

            foreach($deposits as $deposit){
                if($deposit->date->lt($date)){
                    $date = $deposit->date;
                }
            }

            foreach($equitys as $equity) {
                if($equity->date->lt($date)){
                    $date = $equity->date;
                }
            }

            foreach($checks as $check){
                if($check->date->lt($date)){
                    $date = $check->date;
                }
            }

            foreach($receipts as $receipt){
                if($receipt->date->lt($date)){
                    $date = $receipt->date;
                };
            }

            return View::make('Journal.MonthEnd.first')
                ->with('date', $date);
        }
    }

    public function First(){

        if(count(MonthEnd::all()) === 0){
            $data = array(
                'date' => Carbon::parse(Input::get('date')),
                'amount' => Input::get('amount'),
            );

            $date = $data['date']->startOfMonth();

            $data['date']->subMonth();

            $month = $data['date']->month;
            $year = $data['date']->format('y');

            $end = new MonthEnd;
            $end->endingbalence = $data['amount'];
            $end->month = $month;
            $end->year = $year;
            $end->date = $date;
            $end->save();

            return ['status' => 'OK'];
        }else{
            return ['status' => 'OK'];
        }

    }
}
