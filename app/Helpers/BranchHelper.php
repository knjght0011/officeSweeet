<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Validator;

use App\Models\Branch;
use \App\Providers\EventLog;

class BranchHelper
{

    public static function ValidateBranchInput($data){
        $rules = array(
            'number'    => 'string',
            'address1'    => 'string',
            'address2'    => 'string',
            'city'    => 'string',
            'region'    => 'string',
            'state'    => 'string',
            'zip'    => 'required|string',
            'phonenumber' => 'string',
            'faxnumber' => 'string'
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);
        
        Return $validator; // send back all errors
    }
    
    public static function SaveBranch($data){
        if($data['id'] === "0"){
            $model = new Branch;
            Self::UpdateBranch($model, $data);
            Self::UpdateBranchStatus($model, $data);
            return $model->id;
        }else{
            $model = Branch::where('id', $data['id'])->withTrashed()->first();
            Self::UpdateBranch($model, $data);
            Self::UpdateBranchStatus($model, $data);
            return $model->id;#Self::UpdateBranch($statusdonemodel, $data);
        }       
    }

    public static function GetCityTax($BranchID)
    {
        $branch = Branch::where('id', $BranchID)->first();
        return $branch->citytax;
    }
    
    private static function UpdateBranch($model, $data){
        
        $model->number = $data['number'];
        $model->address1 = $data['address1'];
        $model->address2 = $data['address2'];
        $model->city = $data['city'];
        $model->region = $data['region'];
        $model->state = $data['state'];
        $model->zip = $data['zip'];
        $model->phonenumber = $data['phonenumber'];
        $model->faxnumber = $data['faxnumber'];
        $model->save();

        EventLog::add('branch updated/created ID:'.$model->id.' Address:'.$model->number.' '.$model->address1.' '.$model->state);

    }
    
    private static function UpdateBranchStatus($model, $data){
        
        if(count(Branch::all()) === 0){
            //if there are no branches at present make this the main branch
            $model->status = "1";
        }else{
            switch ($data['status']) {
                case "Active":                                      
                    $model->default = null;
                    if($model->deleted_at !== null){
                        $model->restore();
                    }
                    $model->save();
                    break;
                case "Main":
                    $mains = Branch::where('default', '!=', null )->withTrashed()->get();
                    foreach($mains as $main){
                        $main->default = null;
                        $main->save();
                    }
                    
                    $model->default = 1;
                    if($model->deleted_at !== null){
                        $model->restore();
                    }
                    
                    $model->save();
                    break;
                case "Disabled":
                    $model->default = null;
                    if($model->deleted_at === null){
                        $model->delete();
                    }
                    
                    $model->save();
                    break;
                default:
                    $model->status = null;
                    $model->save();
            }            
        }
    }
    
}