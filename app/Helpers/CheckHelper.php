<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Validator;

use App\Models\Check;
use \App\Providers\EventLog;


class CheckHelper
{
    public static function ValidateCheckInput($data)
    {

        $rules = array(//add validation later
            'type' => 'required|in:client,vendor,employee',
            'payto' => 'string',
            'date' => 'date',
            'checknumber' => 'integer',
            'memo' => 'string',
            'amount' => 'numeric',
            'catagorys' => 'array',
            'printqueue' => 'boolean',
            'printed' => 'date|nullable',
        );
        
        if($data['id'] !== "0"){
            $rules['id'] = 'exists:checks,id';
        }
        
        switch ($data['type']) {
            case "client":
                $rules['data_id'] = 'exists:clients,id';
                break;
            case "vendor":
                $rules['data_id'] = 'exists:vendors,id';
                break;
            case "employee":
                $rules['data_id'] = 'exists:users,id';
                break;
        }        

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    }
    
    public static function SaveCheckToQueue($data)
    {
        try {
            if ($data['id'] == 0){
                $check = new Check;
                $done = self::UpdateCheck($check, $data);
                if($done instanceof Exception){
                    return 'Caught exception: ' .  $done->getMessage() . "\n";
                }else{
                    return $check->id;
                }
            }else{
                $check = Check::find($data['id']);
                if($check->printed === null){
                    $done = self::UpdateCheck($check, $data);
                }else{
                    $done = self::UpdatePrintedCheck($check, $data);
                }

                if($done instanceof Exception){
                    return 'Caught exception: ' .  $done->getMessage() . "\n";
                }else{
                    return $check->id;
                }
            }
        }catch (Exception $e) {
            return 'Caught exception: ' .  $e->getMessage() . "\n";
        }
    }
    
    private static function UpdateCheck($check,$data){

        try {
            $check->payto = $data['payto'];                
            $check->date = $data['date'];
            $check->checknumber = $data['checknumber'];
            $check->memo = $data['memo'];            
            $check->amount = $data['amount'];
            $check->catagorys = $data['catagorys'];
            $check->printqueue = $data['printqueue'];
            $check->printed = $data['printed'];
            $check->comments = $data['comments'];
            if($data['fileid'] === "0"){
                $check->filestore_id = null;
            }else{
                $check->filestore_id = $data['fileid'];
            }

            switch ($data['type']) {
                case "client":
                    $check->client_id = $data['data_id'];
                    break;
                case "vendor":
                    $check->vendor_id = $data['data_id'];
                    break;
                case "employee":
                    $check->user_id = $data['data_id'];
                    break;
                default:
            }

            $check->save();

            EventLog::add('New check created ID:'.$check->id);
        }catch (Exception $e) {
            return $e;
        }
    }

    private static function UpdatePrintedCheck($check,$data){

        try {
            //$check->payto = $data['payto'];
            $check->catagorys = $data['catagorys'];
            //$check->printqueue = $data['printqueue'];
            //$check->printed = $data['printed'];
            $check->comments = $data['comments'];

            if($data['fileid'] === "0"){
                $check->filestore_id = null;
            }else{
                $check->filestore_id = $data['fileid'];
            }

            $check->save();

            EventLog::add('Updated check ID:'.$check->id);
        }catch (Exception $e) {
            return $e;
        }
    }

    public static function GetNextCheckNumber()
    {
        $NextCheckNum= Check::max('checknumber');

        //Check that this returned an actual result, if it did add one for next if not set value to 1
        if (is_numeric($NextCheckNum)) {
            $NextCheckNum = $NextCheckNum + 1;
        } else {
            $NextCheckNum = 1;
        }
        return $NextCheckNum;
    }
}