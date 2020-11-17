<?php

namespace App\Http\Controllers;

use App\Helpers\OS\Financial\CardPaymentHelper;
use App\Helpers\OS\Financial\DepositHelper;
use App\Models\Client;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;


class WebPaymentsController extends Controller
{

    public function ShowPaymentPage($subdomain)
    {
        $seting = Setting::where("name", "companyname")->first();
        if($seting === null){
            $companyname = $subdomain;
        }else{
            $companyname = $seting->value;
        }

        return View::make('public.webpayment')->with('companyname', $companyname);
    }

    public function Swipe(){

        $data = array(
            'clientid' => Input::get('clientid'),
            'method' => Input::get('method'),
            'depositcomments' => Input::get('depositcomments'),
            'quotecomments' => Input::get('quotecomments'),
            'table' => Input::get('table'),
            'salesperson' => Input::get('salesperson')
        );

        switch (Input::get('mode')) {
            case "manual":
                $transactioninfo = array(
                    'cardNumber' => Input::get('cardNumber'),
                    'cardExpiryMonth' =>Input::get('cardExpiryMonth'),
                    'cardExpiryYear' => Input::get('cardExpiryYear'),
                    'cardCVC' => Input::get('cardCVC'),
                    'amount' =>  substr(Input::get('amount'), 1),

                    'ipaddress' => Request::ip(),
                    'tax' => '0',
                    'orderid' => '',
                    'orderdescription' => '',

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
                break;
            case "swipe":
                $carddetails = $this->CardDetails(Input::get('swipestring'));
                $transactioninfo = array(
                    'cardNumber' => $carddetails['track1']['CardNumber'],
                    'cardExpiryMonth' => $carddetails['track1']['ExpireyMonth'],
                    'cardExpiryYear' => $carddetails['track1']['ExpireyYear'],
                    'cardCVC' => '',
                    'amount' =>  substr(Input::get('amount'), 1),

                    'ipaddress' => Request::ip(),
                    'tax' => '0',
                    'orderid' => '',
                    'orderdescription' => '',

                    'firstname' => $carddetails['track1']['FirstName'],
                    'lastname' => $carddetails['track1']['LastName'],
                    'address1' => '',
                    'address2' => '',
                    'city' => '',
                    'state' => '',
                    'zip' => '',
                    'country' => '',
                    'email' => '',

                );
                break;
            default:
                return ['status' => 'missingmode'];
                break;
        }


        $client = Client::where('id', $data['clientid'])->first();
        if(count($client) === 1){
            $transaction = CardPaymentHelper::ProcessPayment($transactioninfo);

            if($transaction != false){
                if($transaction->response === "1"){

                    $quote = $this->CreateInvoice($data, $client);

                    $comment = "Payment via transnational, Deposit assigned to invoice: " . $quote->quotenumber;
                    $depositid = DepositHelper::InvoiceDeposit($quote->id, $transactioninfo['amount'], "Credit/Debit Card", $comment, Carbon::now());

                    $transaction->deposit_id = $depositid;
                    $transaction->save();

                    return ['status' => 'OK' , 'TNresponse' => $transaction->response, 'TNresponsetext' => $transaction->responsetext, 'invoiceid' => $quote->id];
                }else{
                    return ['status' => 'OK' , 'TNresponse' => $transaction->response, 'TNresponsetext' => $transaction->responsetext];
                }

            }else{
                return ['status' => 'notninfo'];
            }
        }else{
            return ['status' => 'notfound'];
        }
    }

}