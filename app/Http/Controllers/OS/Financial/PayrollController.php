<?php

namespace App\Http\Controllers\OS\Financial;

#use Session;
use App\Helpers\OS\Users\UserHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
#use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
#use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;

//Models
use App\Models\Setting;
use App\Models\User;
use App\Models\OS\Financial\Payroll;
#use App\Models\Receipt;
#use App\Models\Client;
#use App\Models\Deposit;
#use App\Models\MonthEnd;
#use App\Models\ExpenseAccountCategory;


use App\Helpers\OS\Financial\PayrollHelper;
use App\Helpers\OS\SettingHelper;
use App\Helpers\OS\ReportingHelper;

class PayrollController extends Controller {
    
    public function showPayroll()
    {   
        $freqencysetting = Setting::where('name' , 'Payroll-Frequency')->first();
        $optionsetting = Setting::where('name' , 'Payroll-Option')->first();
        $firstsetting = Setting::where('name' , 'Payroll-First')->first();
        if(count($freqencysetting) > 0){
            if(count($firstsetting) > 0){
                switch ($freqencysetting->value) {
                    case "weekly":
                        $array = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
                        if(in_array ($optionsetting->value , $array)){

                            return $this->Payroll($freqencysetting->value, $optionsetting->value, $firstsetting->value);
                        }else{
                            return View::make('OS.Payroll.setup');
                        }
                    case "biweekly":
                        $array = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
                        if(in_array ($optionsetting->value , $array)){

                            return $this->Payroll($freqencysetting->value, $optionsetting->value, $firstsetting->value);
                        }else{
                            return View::make('OS.Payroll.setup');
                        }                        
                    case "semimonthly":
                        $array = array("1st/2nd", "2nd/3rd", "3rd/3rd", "3rd/4th", "4th/5th", "5th/6th", "6th/7th", "", "8th/9th", "9th/10th", "10th/11th", "11th/12th", "12th/13th", "13th/14th", "14th/15th", "15th/16th", "16th/17th", "17th/18th", "18th/19th", "19th/20th", "20th/21st", "21st/22nd", "22nd/23rd", "23rd/24th", "24th/25th", "25th/26th", "26th/27th", "27th/28th");
                        if(in_array ($optionsetting->value , $array)){
                            
                            return $this->Payroll($freqencysetting->value, $optionsetting->value, $firstsetting->value);
                        }else{
                            return View::make('OS.Payroll.setup');
                        } 
                    case "monthly":
                        $array = array("1st/2nd", "2nd/3rd", "3rd/3rd", "3rd/4th", "4th/5th", "5th/6th", "6th/7th", "", "8th/9th", "9th/10th", "10th/11th", "11th/12th", "12th/13th", "13th/14th", "14th/15th", "15th/16th", "16th/17th", "17th/18th", "18th/19th", "19th/20th", "20th/21st", "21st/22nd", "22nd/23rd", "23rd/24th", "24th/25th", "25th/26th", "26th/27th", "27th/28th", "Last");
                        if(in_array ($optionsetting->value , $array)){
                            return $this->Payroll($freqencysetting->value, $optionsetting->value, $firstsetting->value);
                        }else{
                            return View::make('OS.Payroll.setup');
                        }
                }
            }else{
                    $option = PayrollHelper::convertoption($freqencysetting->value, $optionsetting->value);
                
                
                return View::make('OS.Payroll.setup2')
                    ->with('freqencysetting', $freqencysetting)
                    ->with('option', $option);
            }
        }else{
            return View::make('OS.Payroll.setup');
        }
    }

    public function Payroll($freqency, $option, $first)
    {   
        $employees = UserHelper::GetAllUsersWhere("type", "2");
        $payroll =  PayrollHelper::GetNextPayPeriod($freqency, $option, $first);
        
        $completepayrolls = Payroll::where('final' , 1)->get();
        
        return View::make('OS.Payroll.view')
                ->with('completepayrolls', $completepayrolls)
                #->with('daysinperiod', $daysinperiod)
                ->with('payroll', $payroll)
                #->with('start', $start)
                ->with('employees', $employees);
    }    
    
    public function showReport($subdomain, $id)
    { 
        $producepdf = true;
                
        $payroll = Payroll::where('id' , $id)->with('payrollusers')->first(); 
        
        $employees = UserHelper::GetAllUsersWhere("type", "2");
 
        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Payroll";
        $startdate = $payroll->start->toDateString();
        $enddate = $payroll->end->toDateString();
        
        
        if($producepdf == true){
            
            $filepath = app_path().'/TestFiles/';
            $tempfilename = hash('sha1', Auth::user()->email)."-".hash('sha1', date(DATE_RFC2822)).'.pdf';

            $file = $filepath . $tempfilename;
            
            $pdf = PDF::loadView('pdf.Reports.Payroll', compact('companyinfo', 'companyaddress', 'startdate', 'enddate', 'reportname', 'payroll', 'employees'))->setPaper('A4', 'portrait')->save($file);

            return response()->file($file)->deleteFileAfterSend(true);
            
        }else{
            
            return View::make('pdf.Reports.Payroll')
                ->with('reportname', $reportname)
                ->with('startdate', $startdate)
                ->with('enddate', $enddate)
                ->with('companyinfo', $companyinfo)
                ->with('companyaddress', $companyaddress)
                ->with('payroll', $payroll)
                ->with('employees', $employees);
            
        }
        
    }
    
    #posts
    public function Setup()
    {   
        $frequency = Input::get('frequency');
        $option = Input::get('option');

        SettingHelper::SetSetting('Payroll-Frequency', $frequency);
        SettingHelper::SetSetting('Payroll-Option', $option);
        
        return "done";
    }
    
    public function Setup2()
    {   
        $data = array(
            'start' => Input::get('start'),
        );

        $rules = array(
            'start' => 'date',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        if($validator->fails()){
            return ['status' => 'validation', 'errors' => $validator->errors()->toArray()];
        }else{
            SettingHelper::SetSetting('Payroll-First', $data['start']);
            return ['status' => 'OK'];
        }
    }
    
    public function SaveEmployeePayroll()
    {   
        $data = array(
            'userid' => Input::get('userid'),
            'payrollid' => Input::get('payrollid'),
            'tabledata' => Input::get('data'),
        );
        
        $returnstring = "";
        foreach($data['tabledata'] as $row){
            $id = PayrollHelper::SaveEmployeePayrollRow($data['userid'], $data['payrollid'], $row);
            $returnstring = $returnstring . $id . "/";
        }

        return $returnstring;
    }   
    
    public function DeleteRow(){
        $data = array(
            'userid' => Input::get('userid'),
            'payrollid' => Input::get('payrollid'),
        );
        
        if(PayrollHelper::DeletedRow($data['payrollid'])){
            return "done";
        }else{
            return "finalised";
        } 
    } 
    
    public function Finalise(){
        $data = array(
            'id' => Input::get('id'),
            'yes' => Input::get('yes'),
        );
        
        #return var_dump($data);
        
        foreach($data['yes'] as $key => $value){
            PayrollHelper::FinaliseEmployeePayrollRow($key);
        }
        
        $payroll = Payroll::where('id', $data['id'])->first();
        $payroll->final = 1;
        $payroll->save();
        
        return "done";
    }  
}
