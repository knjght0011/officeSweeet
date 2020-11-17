<?php
namespace App\Helpers\OS;

use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Models\Branch;

class ReportingHelper
{
    public static function generateCompanyAddressArray(){
        $branch = Branch::where('id',  Auth::user()->branch_id )->first();
        
        return $branch;
    }   
    
    public static function generateCompanyInfoArray(){
        $companyinfo = array();
        
        $cname = Setting::where('name', 'companyname')->first();
        if(count($cname) == 1){
            $companyinfo['name'] = $cname->value;
        }else{
            $companyinfo['name'] = "Not Set";
        }
        
        $cemail = Setting::where('name', 'companyemail')->first();
        if(count($cemail) == 1){
            $companyinfo['email'] = $cemail->value;
        }else{
            $companyinfo['email'] = "Not Set";
        } 
        
        return $companyinfo;
    }
}