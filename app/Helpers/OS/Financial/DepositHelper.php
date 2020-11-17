<?php
namespace App\Helpers\OS\Financial;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


use App\Models\Deposit;
use App\Models\DepositLink;

use \App\Providers\EventLog;



class DepositHelper
{
    public static function ValidateDepositInput($data)
    {

        $rules = array(
            'amount' => 'numeric',
            'type' => 'string',
            'method' => 'string',
            'comments' => 'string',
        );
        
        if (array_key_exists('client_id', $data)) {
            $rules['client_id'] = 'exists:clients,id';
        }
        
        if (array_key_exists('quote_id', $data)) {
            $rules['quote_id'] = 'exists:quote,id';
        }        
        
        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    } 
    
    public static function InvoiceDeposit($quote_id, $amount, $method, $comment, $date, $type = "invoice", $fileid = null, $identifier = null)
    {
        $payment = new Deposit;
        if (Auth::check()) {
            $payment->user_id = Auth::user()->id;
        }else{
            $payment->user_id = null;
        }
        $payment->filestore_id = $fileid;
        $payment->date = $date;
        $payment->amount = $amount;
        $payment->type = $type;
        $payment->identifier = $identifier;
        $payment->method = $method;
        $payment->comments = $comment;
        $payment->catagorys = array('Sales Income' => $amount);
        $payment->save();

        $link = new DepositLink;
        $link->amount = $amount;
        $link->deposit_id = $payment->id;
        $link->quote_id = $quote_id;
        $link->save();

        EventLog::add('New invoice deposit added ID:'.$payment->id);

        return $payment->id;
    }

}
