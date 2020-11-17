<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use File;

use App\Models\Client;
use App\Models\Quote;


use App\Helpers\OS\QuoteHelper;
use App\Helpers\OS\RecurringInvoiceHelper;

class InvoiceController extends Controller {
    
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
    

    public function viewInvoice($subdomain, $id)
    {

        $quote_id = intval($id);
        if ($quote_id !== 0) {
            
            $quote = Quote::where('id', $quote_id)
                ->with('quoteitem')
                ->with('DepositLink')
                ->withTrashed()
                ->first();
            
            if ($quote->finalized === 0){
                $this->Error('Quote has not been finalised', 'Quote has been finalised');
            }
            
            $client = Client::where('id', $quote->client_id)
                ->with('address')
                ->with('contacts')
                ->first();
            
            return View::make('Clients.invoice')
                ->with('quote', $quote)    
                ->with('client', $client)
                ->with('currency', "$");
            
        } else {
            return $this->Error('invalid id', $quote_id);
        }
    }
    
    public function viewGeneratedInvoice($subdomain, $id)
    {

        $quote_id = intval($id);
        if ($quote_id !== 0) {
            
            $quote = Quote::where('id', $quote_id)
                ->with('quoteitem')
                ->withTrashed()
                ->first();
            
            if ($quote->finalized === 0){
                $this->Error('Quote has not been finalised', 'Quote has been finalised');
            }
            
            $client = Client::where('id', $quote->client_id)
                ->with('address')
                ->with('contacts')
                ->first();
            
            return View::make('pdf.Invoice.viewinvoice')
                ->with('quote', $quote)    
                ->with('client', $client)
                ->with('currency', "$");
            
        } else {
            return $this->Error('invalid id', $quote_id);
        }
    }
    
    public function showPdf($subdomain, $id)
    {

        $quote_id = intval($id);
        if ($quote_id !== 0) {
            
            $quote = Quote::where('id', $quote_id)
                ->with('quoteitem')
                ->withTrashed()
                ->first();
            
            if ($quote->finalized === 0){
                $this->Error('Quote has not been finalised', 'Quote has not been finalised');
            }else{
                return $quote->PDF()->stream();
            }
            
        } else {
            return $this->Error('invalid id', $quote_id);
        }
    }

    public function PDFPage($subdomain, $id){

        $invoice = Quote::where('id', $id)->first();
        $client = $invoice->client;

        if(count($invoice) === 1){
            return View::make('OS.Invoices.pdfpage')
                ->with('client', $client)
                ->with('invoice', $invoice);
        }else{
            return "invalid invoice id";
        }

    }
    
    public function viewInvoicePublic($subdomain, $token)
    {
        $quote = Quote::where('token', $token)
                ->with('contact')
            ->with('quoteitem')
            ->with('DepositLink')
            ->withTrashed()
            ->first();

        if (count($quote) === 1) {
            
            $client = Client::where('id', $quote->client_id)
                ->with('address')
                ->with('contacts')
                ->first();

            $currency = "$";
            
            $now = Carbon::now();
            $years = array();
            
            array_push($years, $now->year);
            $x = 1; 

            while($x <= 10) {
                $now->addYear();
                array_push($years, $now->year);
                $x++;
            } 
            
            
            return View::make('OS.Public.invoice')
                ->with('years', $years)     
                ->with('token', $token) 
                ->with('quote', $quote)    
                ->with('client', $client)
                ->with('currency', $currency);
            
        } else {
            return "notfound";#$this->Error('invalid id', $token);
        }
    }
    
    public function viewInvoicePublicPDF($subdomain, $token)
    {
        $quote = Quote::where('token', $token)
            ->with('quoteitem')
            ->with('DepositLink')
            ->withTrashed()
            ->first();

        if (count($quote) === 1) {
            
            return $quote->PDF()->stream();
            
        } else {
            return $this->Error('invalid id', 'invalid id');
        }
    }

    public function Delete(){
        $id = Input::get('id');
        $reason = Input::get('reason');

        $quote = Quote::where('id', $id)
            ->where('finalized', '=', '1')
            ->with('quoteitem')
            ->first();

        if(count($quote) === 1){
            if($quote->getBalenceFloat() != 0){
                $quote->voidreason = $reason;
                $quote->save();
                $quote->delete();
                return "done";
            }else{
                return "fail";
            }
        }else{
            return "fail";
        }
    }

    public function RecurringStop(){
       if(RecurringInvoiceHelper::StopRecurring(Input::get('id'))){
           return "done";
       }else{
           return "fail";
       }
    }
    /*
     *   #return $this->Error('', '');
    public function AddPayment()
    {
                //retrieve data
        $paymentdata = array(
            'client_id' => Input::get('client_id'),
            'quote_id' => Input::get('quote_id'),
            'amount' => Input::get('amount'),
            'type' => Input::get('type'),
            'method' => Input::get('method'),
            'comments' => Input::get('comments'),
        );

        $validator = $this->ValidatePaymentInput($paymentdata);

        if ($validator->fails()){

            return $validator->errors()->toArray();

        } else {

            $payment = new PaymentsAdjustments;
            $payment->quote_id = $paymentdata['quote_id'];
            $payment->user_id = Auth::user()->id;
            $payment->client_id = $paymentdata['client_id'];
            $payment->amount = $paymentdata['amount'];
            $payment->type = $paymentdata['type'];
            $payment->method = $paymentdata['method'];
            $payment->comments = $paymentdata['comments'];
            $payment->save();

            EventLog::add('New payment added ID:'.$payment->id.' Quote:'.$payment->quote_id);

            return $payment->id;

        }

    }

        #Input Validation
    public function ValidatePaymentInput($data)
    {

        $rules = array(
            'client_id' => 'exists:clients,id',
            'quote_id' => 'exists:quote,id',
            'amount' => 'numeric',
            'type' => 'string',
            'method' => 'string',
            'comments' => 'string',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    }
     */


 }

