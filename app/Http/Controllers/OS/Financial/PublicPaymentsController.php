<?php

namespace App\Http\Controllers\OS\Financial;

#use Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
#use Illuminate\Support\Facades\Auth;
#use Illuminate\Support\Facades\Redirect;
#use Illuminate\Support\Facades\View;
#use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
#use Barryvdh\DomPDF\Facade as PDF;

//Models
use App\Models\OS\Financial\TransnationalTransaction;
#use App\Models\User;
#use App\Models\OS\Financial\Payroll;
#use App\Models\Receipt;
#use App\Models\Client;
#use App\Models\Deposit;
#use App\Models\MonthEnd;
#use App\Models\ExpenseAccountCategory;


use App\Models\Quote;
#use App\Helpers\OS\SettingHelper;
use App\Helpers\OS\Financial\CardPaymentHelper;
use App\Helpers\OS\Financial\DepositHelper;

class PublicPaymentsController extends Controller {
    
    public function MakePayment()
    {   
        $data = array(
            'cardNumber' => Input::get('cardNumber'),
            'cardExpiryMonth' => Input::get('cardExpiryMonth'),
            'cardExpiryYear' => Input::get('cardExpiryYear'),
            'cardCVC' => Input::get('cardCVC'),
            'amount' => Input::get('amount'),
            'quotetoken' => Input::get('quotetoken'),
            'ipaddress' => Request::ip(),
            'tax' => '0',
            'amount' => Input::get('amount'),
            
            'firstname' => Input::get('address1'),
            'lastname' => Input::get('address1'),
            'email' => Input::get('address1'),
            
            'address1' => Input::get('address1'),
            'address2' => Input::get('address2'),
            'city' => Input::get('city'),
            'state' => Input::get('state'),
            'zip' => Input::get('zip'),
            'country' => Input::get('country'),
        );        
        
        $quote = Quote::where('token', $data['quotetoken'])->first();
        if(count($quote) === 1){
            $data['orderid'] = $quote->id;
            $data['orderdescription'] = "Invoice";
        }else{
            $data['orderid'] = "Unknown";
            $data['orderdescription'] = "Unknown"; 
        }
        
        
        $valid = CardPaymentHelper::ValidateCardData($data);
        
        if($valid === "valid"){
            //do card payemnt
            $transaction = CardPaymentHelper::ProcessPayment($data);
            if($transaction === false){
                return "4:notninfo";
            }else{
                if($transaction->response === "1"){
                    $comment = "Payment via transnational, Deposit assigned to invoice: " . $quote->quotenumber;
                    $depositid = DepositHelper::InvoiceDeposit($quote->id, $data['amount'],  "Credit/Debit Card",$comment, Carbon::now());
                    $transaction->deposit_id = $depositid;
                    $transaction->save();
                }
                
                return $transaction->response . ":" . $transaction->responsetext;
            }
        }else{
            return $valid;
        }
    }
}


