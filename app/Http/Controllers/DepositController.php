<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;

use Carbon\Carbon;

use App\Models\Deposit;
use App\Models\DepositLink;
use App\Models\Client;
use App\Models\MonthEnd;
use App\Models\Quote;
use App\Models\ExpenseAccountCategory;

use App\Helpers\OS\Financial\DepositHelper;
use App\Helpers\OS\FileStoreHelper;
use App\Helpers\OS\Financial\CardPaymentHelper;
use App\Helpers\OS\SettingHelper;
use \App\Providers\EventLog;

class DepositController extends Controller {
    
    #return $this->Error('', '');
    public function Error($error, $debug)
    {
        if (is_array($error)){
            return View::make('error')
                ->with('errors', $error)
                ->with('debug', $debug);
        }else{
            return View::make('error')
                ->with('error', $error)
                ->with('debug', $debug);
        }
    }
    
    public function EditDeposit($subdomain, $id)
    {
        $deposit = Deposit::where('id', $id)->with('depositlinks')->first();

        $ExpenseAccountCategorys = ExpenseAccountCategory::where('type', '!=', 'expense')
            ->with('subcategories')->orderBy('category','ASC')->get();
        
        if($deposit !== null)
        {
            return View::make('Deposits.edit')
                ->with('ExpenseAccountCategorys', $ExpenseAccountCategorys)
                ->with('deposit', $deposit);
        } else {
            return $this->Error("Invalid Deposit ID","Invalid Deposit ID");
        }
        
    }
    
    
    public function Delete()
    {
        $data = array(
            'id' => Input::get('id'),
            'reason' => Input::get('reason'),
        );
        
        $deposit = Deposit::where('id', $data['id'])->with('depositlinks')->first();
        
        if($deposit !== null){
            if($deposit->CantEdit() === true){
                
                return "monthend";
                
            }else{
                $deposit->comments = $deposit->comments . " Deleted by " . Auth::user()->getShortName() . " Reason: " . $data['reason']; 
                $deposit->save();
                $deposit->delete();
                foreach($deposit->depositlinks as $depositlink){
                    $depositlink->delete();
                } 

                EventLog::add('Deposit deleted ID:'.$deposit->id.' Reason: '.$data['reason']);

                return "success";
            }
        }else{
            return "norecordfound";
        }        
    }
    
    public function Save()
    {
        $data = array(
            'id' => Input::get('id'),
            'amount' => Input::get('amount'),
            'date' => Carbon::createFromFormat('Y-m-d', Input::get('date')),
            'method' => Input::get('method'),
            'comments' => Input::get('comments'),
            'catagorys' => Input::get('catagorys'),
            'image' => Input::get('image'),
        );

        $lastmonthend = MonthEnd::get()->last();
        if (count($lastmonthend) === 1) {
            if ($lastmonthend->IsBeforeThis($data["date"])) {
                $error["error"] = "Cannot save with given date as a month end has allready been actioned after that date";
                return $error;
            }
        }


        $deposit = Deposit::where('id', $data['id'])->with('depositlinks')->first();
        
        if($deposit !== null){           
            if($deposit->CantEdit() === false){
                if($data['amount'] !== null){
                    $deposit->amount = $data['amount'];
                }
                if($data['date'] !== null){
                    $deposit->date = $data['date'];
                }                           
            }
            
            if($data['method'] !== null){
                $deposit->method = $data['method'];
            }
            
            if($data['comments'] !== null){
                $deposit->comments = $data['comments'];
            }

            if(is_array($data['catagorys'])){
                $deposit->catagorys = $data['catagorys'];
            }else{
                $deposit->catagorys = null;
            }

            //$deposit->catagorys = $data['catagorys'];

            if($data['image'] !== ""){
                $filedata = array(
                    'file' => Input::get('image'),
                    'client_id' => $deposit->getClientIDnull(),
                    'vendor_id' => null,
                    'description' => "Image linked to deposit",
                    'upload_user' => Auth::user()->id
                );

                $deposit->filestore_id = FileStoreHelper::StoreFile($filedata);

            }

            $deposit->save();
            EventLog::add('Deposit edited ID: '.$deposit->id);

            return "success";
            
        }else{
            return "norecordfound";
        }        
    }
    
    #return $this->Error('', '');
    public function AddMiscDeposit()
    {
                //retrieve data
        $data = array(
            'date' => Carbon::parse(Input::get('date')),
            //'date' => Carbon::createFromFormat('Y-m-d', Input::get('date')),
            'amount' => Input::get('amount'),
            'type' => Input::get('type'),
            'method' => Input::get('method'),
            'comments' => Input::get('comments'),
            'file' => Input::get('file'),
            'catagorys' => Input::get('catagorys'),
            'user_id' => Auth::user()->id,
        );
        
        $monthendcheck = $this->MonthendDateCheck($data["date"]);
        
        $validator = DepositHelper::ValidateDepositInput($data);
        
        if ($validator->fails() or $monthendcheck != true){
            if($validator->fails()){
                $errors = $validator->errors()->toArray();
            }
            if($monthendcheck != true){
                $errors["monthendcheck"] = $monthendcheck;
            }
            return ['status' => 'validation', 'errors' => $errors];
        } else {

            if($data['file'] != ""){
                $filedata = array(
                    'file' => $data['file'],
                    'client_id' => null,
                    'vendor_id' => null,
                    'description' => "Image linked to deposit",
                    'upload_user' => $data['user_id'],
                );

                $data['filestore_id'] = FileStoreHelper::StoreFile($filedata);
            }else{
                $data['filestore_id'] = null;
            }
            
            $payment = new Deposit;
            $payment->user_id = $data['user_id'];
            $payment->date = $data['date'];
            $payment->amount = $data['amount'];
            $payment->type = "misc";
            $payment->method = $data['method'];
            $payment->comments = $data['comments'];
            $payment->filestore_id = $data['filestore_id'];

            if(is_array($data['catagorys'])){
                $payment->catagorys = $data['catagorys'];
            }else{
                $payment->catagorys = null;
            }

            //$payment->catagorys = $data['catagorys'];
            $payment->save();
            
            EventLog::add('New misc deposit added ID:'.$payment->id);

            return ['status' => 'OK', 'id' => $payment->id];

        }
    }
    
    public function AddClientDeposit()
    {
        //retrieve data
        $data = array(
            'client_id' => Input::get('client_id'),
            'date' => Carbon::createFromFormat('Y-m-d', Input::get('date')),
            'amount' => floatval(Input::get('amount')),
            'type' => Input::get('type'),
            'method' => Input::get('method'),
            'comments' => Input::get('comments'),
            'file' => Input::get('file'),
            'user_id' => Auth::user()->id,
        );
        
        $monthendcheck = $this->MonthendDateCheck($data["date"]);
        
        $validator = DepositHelper::ValidateDepositInput($data);
        
        if ($validator->fails() or $monthendcheck != true){
            if($validator->fails()){
                $errors = $validator->errors()->toArray();
            }
            if($monthendcheck != true){
                $errors["monthendcheck"] = $monthendcheck;
            }
            return $errors; 
        } else {
            
            $client = Client::where('id', $data['client_id'])
                    ->with('quotes')
                    ->first();
            
            if(floatval($client->getBalence(false)) < $data['amount']){
                
                $error['error'] = "Clients balance is less than the amount you have entered.";
                return $error;
                
            }else{

                if($data['file'] != ""){
                    $filedata = array(
                        'file' => $data['file'],
                        'client_id' => $data['client_id'],
                        'vendor_id' => null,
                        'description' => "Image linked to deposit",
                        'upload_user' => $data['user_id'],
                    );

                    $data['filestore_id'] = FileStoreHelper::StoreFile($filedata);
                }else{
                    $data['filestore_id'] = null;
                }
            
                $payment = new Deposit;
                $payment->user_id = $data['user_id'];
                $payment->date = $data['date'];
                $payment->amount = $data['amount'];
                $payment->type = "client";
                $payment->method = $data['method'];
                $payment->filestore_id = $data['filestore_id'];
                #$payment->comments = $data['comments'];
                $payment->catagorys = array('Sales Income' => $data['amount']);
                
                $payment->save();
                
                $autocomment = " Deposit assigned to invoices: ";
                
                foreach($client->getInvoices() as $invoice){
                    if($invoice->getBalenceFloat() > 0){
                        if($invoice->getBalenceFloat() < $data['amount']){
                            $link = new DepositLink;
                            $link->amount = $invoice->getBalenceFloat();
                            $link->deposit_id = $payment->id;
                            $link->quote_id = $invoice->id;
                            $link->save();
                            
                            $data['amount'] = $data['amount'] - $invoice->getBalenceFloat();
                            
                            $autocomment = $autocomment . $invoice->quotenumber . ", ";
                            
                        }else{
                            $link = new DepositLink;
                            $link->amount = $data['amount'];
                            $link->deposit_id = $payment->id;
                            $link->quote_id = $invoice->id;
                            $link->save();
                            
                            $data['amount'] = 0;
                            
                            $autocomment = $autocomment . $invoice->quotenumber . ".";
                            
                            break;
                        }
                    }
                }
                
                $payment->comments = $data['comments'] . $autocomment;
                
                $payment->save();

                EventLog::add('New client deposit added ID:'.$payment->id);

                return $payment->id;
            }
        }
    }
    
    public function AddInvoiceDeposit()
    {

        $invoice = Quote::where('id', Input::get('quote_id'))
            ->first();

        if(count($invoice) !=  1){
            return ['status' => 'notfound'];
        }

        if(Input::get('method') === "Debit/Credit Card" and SettingHelper::GetSetting('transnational-username') != null){
            //retrieve data
            $data = array(
                'client_id' => Input::get('client_id'),
                'date' => Carbon::now(),
                'amount' => floatval(Input::get('amount')),
                'type' => Input::get('type'),
                'method' => Input::get('method'),
                'comments' => Input::get('comments'),
                'file' => Input::get('file'),
                'user_id' => Auth::user()->id,
            );

            $transactioninfo = array(
                'cardNumber' => Input::get('cardNumber'),
                'cardExpiryMonth' => Input::get('cardExpiryMonth'),
                'cardExpiryYear' => Input::get('cardExpiryYear'),
                'cardCVC' => Input::get('cardCVC'),
                'amount' =>  $data['amount'],

                'ipaddress' => Request::ip(),
                'tax' => '0',
                'orderid' => '',
                'orderdescription' => 'Invoice Number: ' . $invoice->quotenumber,

                'firstname' => Input::get('firstname'),
                'lastname' => Input::get('lastname'),
                'address1' => '',
                'address2' => '',
                'city' => '',
                'state' => '',
                'zip' => '',
                'country' => '',
                'email' => '',
            );


        }else{
            //retrieve data
            $data = array(
                'quote_id' => Input::get('quote_id'),
                'client_id' => Input::get('client_id'),
                'date' => Carbon::createFromFormat('Y-m-d', Input::get('date')),
                'amount' => floatval(Input::get('amount')),
                'type' => Input::get('type'),
                'method' => Input::get('method'),
                'comments' => Input::get('comments'),
                'file' => Input::get('file'),
                'user_id' => Auth::user()->id,
            );

            $monthendcheck = $this->MonthendDateCheck($data["date"]);

            if($monthendcheck != true){
                return ['status' => 'monthend'];
            }

        }

        if(floatval($invoice->getBalenceFloat()) < $data['amount']){
            //$error['error'] = "";
            return ['status' => 'amounttohigh'];
        }

        $validator = DepositHelper::ValidateDepositInput($data);

        if ($validator->fails()){
            return ['status' => 'validation', 'errors' => $validator->errors()->toArray()];
        } else {

            if(isset($transactioninfo)){
                $transaction = CardPaymentHelper::ProcessPayment($transactioninfo);

                if($transaction != false){
                    if($transaction->response === "1"){

                        if($data['file'] != ""){
                            $filedata = array(
                                'file' => $data['file'],
                                'client_id' => $data['client_id'],
                                'vendor_id' => null,
                                'description' => "Image linked to deposit",
                                'upload_user' => $data['user_id'],
                            );

                            $data['filestore_id'] = FileStoreHelper::StoreFile($filedata);
                        }else{
                            $data['filestore_id'] = null;
                        }

                        $autocomment = $data['comments'] . " Deposit assigned to invoice: " . $invoice->quotenumber;
                        $payment = DepositHelper::InvoiceDeposit($invoice->id,  $data['amount'], $data['method'], $autocomment, $data['date'], $type = "invoice", $data['filestore_id']);

                        return ['status' => 'OK' , 'TNresponse' => $transaction->response, 'TNresponsetext' => $transaction->responsetext, 'depositid' => $payment];
                    }

                    return ['status' => 'OK' , 'TNresponse' => $transaction->response, 'TNresponsetext' => $transaction->responsetext];
                }else{
                    return ['status' => 'notninfo'];
                }
            }else{
                if($data['file'] != ""){
                    $filedata = array(
                        'file' => $data['file'],
                        'client_id' => $data['client_id'],
                        'vendor_id' => null,
                        'description' => "Image linked to deposit",
                        'upload_user' => $data['user_id'],
                    );

                    $data['filestore_id'] = FileStoreHelper::StoreFile($filedata);
                }else{
                    $data['filestore_id'] = null;
                }

                $autocomment = $data['comments'] . " Deposit assigned to invoice: " . $invoice->quotenumber;
                $payment = DepositHelper::InvoiceDeposit($invoice->id,  $data['amount'], $data['method'], $autocomment, $data['date'], $type = "invoice", $data['filestore_id']);

                return ['status' => 'OK', 'depositid' => $payment];
            }

        }
    }
    
    private function MonthendDateCheck($date){
        $monthend = MonthEnd::get()->last();
        if($monthend === null){
            return true;
        }else{
            if($monthend->IsBeforeThis($date)){
                $error["error"] = "Cannot create with given date as a month end has allready been actioned after that date";
                return $error;
            }else{
                return true;
            }
        }
    }


    #Input Validation
    /*
    public function ValidateDepositInput($data)
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
     * 
     */
    
}

