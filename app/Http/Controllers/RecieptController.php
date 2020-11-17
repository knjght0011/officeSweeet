<?php

namespace App\Http\Controllers;

use App\Helpers\OS\Users\UserHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
#use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Illuminate\Http\Response;
#use Illuminate\Support\Facades\Mail;
#use Barryvdh\DomPDF\Facade as PDF;
#use File;


use \App\Providers\EventLog;

use App\Models\Client;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Receipt;
use App\Models\ExpenseAccountCategory;

use App\Helpers\OS\FileStoreHelper;


class RecieptController extends Controller {

    public function ShowNewRecieptForm($subdomain, $id)
    {
        $user_id = intval($id);

        $clients = Client::with('primarycontact')->get();
        $vendors = Vendor::with('primarycontact')->get();

        $employees = UserHelper::GetAllUsers();

        $ExpenseAccountCategorys = ExpenseAccountCategory::where('type', '!=', 'income')
            ->with('subcategories')->orderBy('category','ASC')->get();


        $currency = "$";

        return View::make('Receipts.add')
            ->with('employees', $employees)  
            ->with('user_id', $user_id)   
            ->with('clients', $clients)
            ->with('vendors', $vendors)
            ->with('currency', $currency)
            ->with('ExpenseAccountCategorys', $ExpenseAccountCategorys);
            
    }
    
    public function ShowEditRecieptForm($subdomain, $id)
    {
        $clients = Client::with('primarycontact')->get();
        $vendors = Vendor::with('primarycontact')->get();

        $employees = UserHelper::GetAllUsers();

        $ExpenseAccountCategorys = ExpenseAccountCategory::where('type', '!=', 'income')
            ->with('subcategories')->orderBy('category','ASC')->get();

        $currency = "$";
        
        $receipt = Receipt::where('id', $id)
            ->with('filestore')
            ->first();
        
        if(count($receipt) === 1 ){
            return View::make('Receipts.add')
                ->with('receipt', $receipt) 
                ->with('employees', $employees)
                ->with('clients', $clients)
                ->with('vendors', $vendors)
                ->with('currency', $currency)
                ->with('ExpenseAccountCategorys', $ExpenseAccountCategorys);
        }else{
            return "error";
        }
    }
    
    public function ShowAttachment($subdomain, $id)
    {  
        
        $receipt = Receipt::find($id);
        
        if(count($receipt) === 1 ){
            $type = $this->getType($receipt->image);
        
            switch ($type["file"]) 
            {
                case 'application':
                    switch ($type["type"]) 
                    {
                        case 'pdf':
                            $split = explode( "," , $receipt->image);
                            #return var_dump($split);
                            $pdf = base64_decode($split[1]);
                            return response($pdf) ->header('content-type','application/pdf') ->header('Content-Disposition', 'inline; filename="file.pdf"');
                        default:
                            $error["error"] = "Unknown File Type" . $type["file"] . " " . $type["type"];
                            return $error;
                    }                    
                    break;
                case 'image':
                    return View::make('Receipts.image')
                        ->with('image', $receipt->image);
                default:
                    $error["error"] = "Unknown File Type" . $type["file"] . " " . $type["type"];
                    return $error;
            }
        }else{
            return "error";
        }
    }
    
    private function getType($upload){
        $type = preg_split("/\W/", $upload); // split on : / ; ,
        
        $return["file"] = $type[1];
        $return["type"] = $type[2];
        
        return $return;
    }

    public function Save()
    {
        $data = array(
            'id' => Input::get('id'),
            'date' => Input::get('date'),
            'amount' => Input::get('amount'),
            'linkedtype' => Input::get('linkedtype'),
            'linkedid' => Input::get('linkedid'),
            'description' => Input::get('description'),
            'catagorys' => Input::get('catagorys'),
            'image' => Input::get('image'),
            'employee' => Input::get('employee'),
            'reimbursement' => Input::get('reimbursement'),
            );

        switch ($data['linkedtype']) {
            case "client":
                $data['client_id'] = $data['linkedid'];
                $data['vendor_id'] = null;
                break;
            case "vendor":
                $data['vendor_id'] = $data['linkedid'];
                $data['client_id'] = null;
                break;
            default:
                $data['vendor_id'] = null;
                $data['client_id'] = null;
        }

        if($data['employee'] == "0"){
            $data['employee'] = null;
        }

        if($data['catagorys'] == null){
            $data['catagorys'] = "";
        }

        if($data['image'] !== ""){
            $filedata = array(
                'file' => Input::get('image'),
                'client_id' => $data['client_id'],
                'vendor_id' => $data['vendor_id'],
                'description' => "Image linked to expense",
                'upload_user' => Auth::user()->id,
                'updatetype' => "",
            );

            $data['fileid'] = FileStoreHelper::StoreFile($filedata);

        }else{
            $data['fileid'] = null;
        }
        
        $validator = $this->ValidateReceiptInput($data);
        
        if ($validator->fails()){
            
            return $validator->errors()->toArray();
            
        } else {
            if($data["id"] == 0)
            {
                $receipt = new Receipt;
                    
                $receipt->date = new Carbon($data['date']);
                
                if($receipt->CantEdit() === true){
                    $error["error"] = "Cannot create with given date as a month end has allready been actioned after that date";
                    return $error;
                }
                
                $receipt->amount = $data['amount'];
                $receipt->description = $data['description'];
                if(is_array($data['catagorys'])){
                    $receipt->catagorys = $data['catagorys'];
                }else{
                    $receipt->catagorys = null;
                }
                $receipt->reimbursement = $data['reimbursement'];
                $receipt->image = "";

                $receipt->entered_by_user_id = Auth::id();
                $receipt->client_id = $data['client_id'];
                $receipt->vendor_id = $data['vendor_id'];
                $receipt->user_id = $data['employee'];
                $receipt->filestore_id = $data['fileid'];

                $receipt->save();

                EventLog::add('New Receipt created ID:'.$receipt->id);
                
                return $receipt->id;
                
            }else{
                $receipt = Receipt::find($data['id']);
                
                if($receipt->CantEdit() !== true){
                    $receipt->date = new Carbon($data['date']);
                    $receipt->amount = $data['amount'];
                }

                $receipt->description = $data['description'];
                if(is_array($data['catagorys'])){
                    $receipt->catagorys = $data['catagorys'];
                }else{
                    $receipt->catagorys = null;
                }
                $receipt->reimbursement = $data['reimbursement'];
                $receipt->image = "";

                $receipt->entered_by_user_id = Auth::id();
                $receipt->client_id = $data['client_id'];
                $receipt->vendor_id = $data['vendor_id'];
                $receipt->user_id = $data['employee'];

                if($data['fileid'] != null){//if its null no need to change it
                    $receipt->filestore_id = $data['fileid'];
                }

                
                $receipt->save();
                
                EventLog::add('Receipt updated ID:'.$receipt->id);

                return $receipt->id;
            }
        }
    }
    
    public function ValidateReceiptInput($data)
    {

        $rules = array(//add validation later
            'id' => '',
            'date' => 'required|date',
            'amount' => 'required|numeric',
            'linkedtype' => '',
            'linkedid' => '',
            'description' => 'string',
            'categorys' => '',
            'image' => '',
        );
        

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    }
       
    public function Delete()
    {
        $receipt = Receipt::find(Input::get('id'));
        
        if(count($receipt) === 1){
            if($receipt->CantEdit()){
                return "error:monthend";#error: month end
            }else{
                $receipt->delete();#delete
                return "success";
            }
        }else{
            return "error:norecord";#error no record
        }
    }


    public function Convert()
    {
        $receipts = Receipt::all();

        foreach($receipts as $receipt){

            if($receipt->image != ""){
                $filedata = array(
                    'file' => $receipt->image,
                    'client_id' => $receipt->client_id,
                    'vendor_id' => $receipt->vendor_id,
                    'description' => "Image linked to expense",
                    'upload_user' => $receipt->entered_by_user_id,
                    'updatetype' => "",
                );

                $receipt->filestore_id = FileStoreHelper::StoreFile($filedata);
                $receipt->image = "";
                $receipt->save();
            }

        }

        return "done";
    }
            
    /*
    public function Convert()
    {
        $receipts = Receipt::all();
        
        foreach($receipts as $receipt){
                        
            if(is_array($receipt->catagorys)){
                //if catagorys are of old format, change them to be the new format
                $first_key = key($receipt->catagorys);
                $split = $receipt->amount / count($receipt->catagorys);

                if(is_int($first_key)){
                    $array = array ();
                    foreach($receipt->catagorys as $key  => $value){
                        $array[$value] = $split;
                    }
                    $receipt->catagorys = $array;
                    $receipt->save();
                }
            }
        }
        
        return "done";
    }
     * 
     */
}

