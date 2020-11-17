<?php

namespace App\Http\Controllers;

use App\Helpers\OS\Financial\JournalHelper;
use App\Helpers\OS\Users\UserHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
#use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
#use Illuminate\Support\Facades\Mail;
#use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
#use File;
use Carbon\Carbon;

use \App\Providers\EventLog;

use App\Helpers\CheckHelper;


use App\Models\Vendor;
use App\Models\Client;
use App\Models\User;
use App\Models\Check;
use App\Models\CheckSettings;
use App\Models\ExpenseAccountCategory;

use App\Models\MonthEnd;

class CheckController extends Controller {
    
    #should replace ShowVendorCheckForm($id) & ShowClientCheckForm($id)
    public function ShowNewCheckForm($subdomain, $type = null, $id = null)
    {

        $clients = Client::with('primarycontact')->get();
        $vendors = Vendor::with('primarycontact')->get();
        $employees = UserHelper::GetAllUsers();

        $ExpenseAccountCategorys = ExpenseAccountCategory::with('subcategories')->orderBy('category','ASC')->get();

        $currency = "$";

        return View::make('Checks.check')
            ->with('type', $type)
            ->with('id', $id)
            ->with('clients', $clients)
            ->with('vendors', $vendors)
            ->with('employees', $employees)
            ->with('currency', $currency)
            ->with('ExpenseAccountCategorys', $ExpenseAccountCategorys);
    }

    public function ShowEditCheckForm($subdomain, $id)
    {

        $check = Check::where('id', $id)
            ->with('vendor')
            ->first();

        if(count($check) === 1) {

            $clients = Client::get();
            $vendors = Vendor::get();
            $employees = UserHelper::GetAllUsers();

            $ExpenseAccountCategorys = ExpenseAccountCategory::with('subcategories')->orderBy('category', 'ASC')->get();

            $currency = "$";

            return View::make('Checks.check')
                ->with('clients', $clients)
                ->with('vendors', $vendors)
                ->with('employees', $employees)
                ->with('check', $check)
                ->with('currency', $currency)
                ->with('ExpenseAccountCategorys', $ExpenseAccountCategorys);

        }else{
            return "check does not exist";
        }
    }
    
    public function SaveCheckToQueue()
    {
        //retrieve data
        $checkdata = array(
            'id' => Input::get('id'),
            'data_id' => Input::get('data_id'),
            'type' => Input::get('linktype'),
            'payto' => Input::get('payto'),
            'date' => Carbon::parse(Input::get('date')),
            'checknumber' => CheckHelper::GetNextCheckNumber(),
            'memo' => Input::get('memo'),
            'amount' => Input::get('amount'),
            'catagorys' => Input::get('catagorys'),
            'printqueue' => 1,
            'printed' => null,
            'comments' => Input::get('comment'),
            'fileid' => Input::get('fileid'),
        );
        
        
        $validator = CheckHelper::ValidateCheckInput($checkdata);
        
        if ($validator->fails()){
            return $validator->errors()->toArray();
            
        } else {
            return CheckHelper::SaveCheckToQueue($checkdata);
        }
    }    
    
    

    public function showCheckQueue()
    {
        $endingbalance = JournalHelper::CurrentBalance();

        $checks = Check::where('printqueue', '1')->get();

        $total = 0.00;
        foreach($checks as $check){
            $total = $total + $check->amount;
        }
        
        return View::make('Checks.queue')
                ->with('endingbalance', $endingbalance)
                ->with('total', $total)
                ->with('checks', $checks);
    }
    
    
    public function CheckPrinted($subdomain, $id, $number)
    {   
        $check = Check::where('id', $id)->first();
        if(count($check) === 1){
            $check->checknumber = $number;
            $check->printqueue = "0";
            $check->printed = Carbon::now();
            $check->save();
            return "done";
        }else{
            return "Check Not Found";
        }
    }
    
    public function DeleteCheck()
    {   
        $id = Input::get('id');
        $check = Check::where('id', $id)->first();
        if(count($check) === 1){
            if($check->printed === null){
                 $check->delete();
                 return "saved";
            }else{
                if(MonthEnd::get()->last()->IsBeforeThis($check->date)){
                    return "Cannot delete with given date as a month end has allready been actioned after that date";
                }
                $check->delete();

                return "saved";
            }
        }else{
            return "Check Not Found";
        }
    }

    
    public function showPdf($subdomain, $id, $order = "top")
    {

        $check_ids = explode( "_" , $id );
        
        if($order === "top"){
            $checks = Check::findMany($check_ids);
        }else{
            $checks = Check::findMany($check_ids)->reverse();
        }
        
        $InitialCheckSettings = CheckSettings::all();

        $CheckSettings = array();

        foreach($InitialCheckSettings as $s){
                $CheckSettings[$s->Name] = $s->Value;
            }
   
        $debughtml = true;//set this to true to get a html output instead of a PDF for debugging layout

        if ($debughtml == false){
            return View::make('pdf.Checks.viewcheck')
                ->with('checks', $checks)
                ->with('currency', "$")
                ->with('checksettings', $CheckSettings);
        }else{
            $pdf = $this->GeneratePDF($checks, $CheckSettings);

            return response()->file($pdf)->deleteFileAfterSend(true);
        }
    }


            
    public function GeneratePDF($checks, $checksettings)
    {  
       
        $currency = "$";

        $filepath = app_path().'/TestFiles/';
        $tempfilename = hash('sha1', Auth::user()->email)."-".hash('sha1', date(DATE_RFC2822)).'.pdf';
        
        $file = $filepath . $tempfilename;

        PDF::loadView('pdf.Checks.viewcheck', compact('checks', 'data', 'currency', 'checksettings'))->setPaper('letter', 'portrait')->save($file);
        return $file;

    }
}