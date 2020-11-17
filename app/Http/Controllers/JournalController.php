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

//Models
use App\Models\PaymentsAdjustments;
use App\Models\Check;
use App\Models\Receipt;
use App\Models\Client;
use App\Models\Deposit;
use App\Models\MonthEnd;
use App\Models\ExpenseAccountCategory;
use App\Models\OS\Financial\Asset;

class JournalController extends Controller {
    
    public function showJournal()
    {   
        $firstmonthend = MonthEnd::first();
        if(count($firstmonthend) === 0){
            return Redirect::to("/Journal/MonthEnd/Summery");
        }else{

            $lastmonthend = MonthEnd::all()->sortByDesc('id')->first();

            if($lastmonthend->month === 12){
                $month = 1;
                $year = "20" . ($lastmonthend->year + 1);
            }else{
                $month = $lastmonthend->month + 1;
                $year = "20" . $lastmonthend->year;
            }

            if(strlen($month) === 1){
                $month = "0" . $month;
            }

            return $this->Journal($month, $year, $firstmonthend);
        }
    }
    
    public function showJournalWithDates($subdomain, $month, $year)
    {   
        $firstmonthend = MonthEnd::first();
        if(count($firstmonthend) === 0){
            return Redirect::to("/Journal/MonthEnd/Summery");
        }else{
            return $this->Journal($month, $year, $firstmonthend);
        }
        
    }
    
    private function Journal($month, $year, $firstmonthend)
    {   
        
        $date = $year . "-" . $month . "-01";

        $clients = Client::with('primarycontact')->get();
        
        if(strtotime($date) < strtotime($firstmonthend->date)){
            $deposits = Deposit::whereDate('date', '<', $firstmonthend->date)->get();
            $checks = Check::whereDate('date', '<', $firstmonthend->date)->where('printed', '!=', null)->get();
            $receipts = Receipt::whereDate('date', '<', $firstmonthend->date)->get();

            $equitys = Asset::whereDate('date', '<', $firstmonthend->date)
                        ->where('journal','=', 1)
                        ->get();
            
            $beginningbalance = floatval(0);

            $endingbalance = $firstmonthend->endingbalence;

        }else{
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
            
            $beginningbalance = floatval(0);
            
            if($month === "01"){
                $endmonth = 12;
                $endyear = intval(substr($year, -2)) - 1;
            }else{
                $endmonth = intval($month) - 1;
                $endyear = intval(substr($year, -2));
            }
            
            $lastmonthend = MonthEnd::where('year', '=', $endyear)
                                    ->where('month', '=', $endmonth)
                                    ->first();
            
            switch (count($lastmonthend)) {
                case 0:
                        $beginningbalance = "Unknown";
                        $endingbalance = null;
                    break;
                case 1:
                        $beginningbalance = floatval($lastmonthend->endingbalence);
                        $endingbalance = floatval(0);
                    break;
                default:
                        #shouldnt ever get here added for completness and debugging
                        $beginningbalance = "Error!!!!!!!!";
                        $endingbalance = null;
            }
        }
        
        $credits = floatval(0);
        $debits = floatval(0);
        
        foreach($deposits as $deposit){
            $credits = $credits + $deposit->amount;
        }

        foreach($equitys as $equity){
            $credits = $credits + $equity->amount;
        }
        
        foreach($checks as $check){
            $debits = $debits + $check->amount;
        }
        
        foreach($receipts as $receipt){
            $debits = $debits + $receipt->amount;
        }
        
        if($endingbalance !== null){
            $endingbalance = $beginningbalance + $credits - $debits;
        }

        $ExpenseAccountCategorysExpence = ExpenseAccountCategory::where('type', '!=', 'income')
            ->with('subcategories')->orderBy('category','ASC')->get();

        $ExpenseAccountCategorysDeposit = ExpenseAccountCategory::where('type', '!=', 'expense')
            ->with('subcategories')->orderBy('category','ASC')->get();
        
        return View::make('Journal.view')
            ->with('beginningbalance', $beginningbalance)
            ->with('credits', $credits)
            ->with('debits', $debits)
            ->with('endingbalance', $endingbalance)
            ->with('clients', $clients)
            ->with('month', $month)
            ->with('year', $year)
            ->with('deposits', $deposits)
            ->with('checks', $checks)
            ->with('receipts', $receipts)
            ->with('equitys', $equitys)
            ->with('ExpenseAccountCategorysExpence', $ExpenseAccountCategorysExpence)
            ->with('ExpenseAccountCategorysDeposit', $ExpenseAccountCategorysDeposit);
    }
    
    public function SaveComment()
    {   
        $data = array(
            'id' => Input::get('id'),
            'typedata' => Input::get('typedata'),
            'comment' => Input::get('comment'),
            'catagorys' => Input::get('catagorys'),
        );


        switch ($data['typedata']) {
            case "deposit":
                $payment = Deposit::where('id' , $data['id'])->first();
                $payment->comments = $data['comment'];
                if(is_array($data['catagorys'])){
                    $payment->catagorys = $data['catagorys'];
                }else{
                    $payment->catagorys = null;
                }
                $payment->save();
  
                return "saved";
            case "check":
                $check = Check::where('id' , $data['id'])->first();
                $check->comments = $data['comment'];
                if(is_array($data['catagorys'])){
                    $check->catagorys = $data['catagorys'];
                }else{
                    $check->catagorys = null;
                }
                //$check->catagorys = $data['catagorys'];
                $check->save();   
                
                return "saved";
            case "receipt":
                $receipt = Receipt::where('id' , $data['id'])->first();
                $receipt->description = $data['comment'];
                if(is_array($data['catagorys'])){
                    $receipt->catagorys = $data['catagorys'];
                }else{
                    $receipt->catagorys = null;
                }
                //$receipt->catagorys = $data['catagorys'];
                $receipt->save();
                
                return "saved";
            default:
                return "error";
        }
    }
        
}
