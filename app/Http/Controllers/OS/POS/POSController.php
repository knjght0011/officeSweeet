<?php

namespace App\Http\Controllers\OS\POS;

use App\Helpers\OS\Financial\CardPaymentHelper;
use App\Helpers\OS\Financial\DepositHelper;
use App\Http\Controllers\Controller;

use App\Models\OS\Inventory\ServiceLibrary;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

use App\Models\Branch;
use App\Models\Client;
use App\Models\ProductLibrary;
use App\Models\Quote;
use App\Models\QuoteItem;

class POSController extends Controller {
    
    public function View($subdomain, $client, $products, $services)
    {
        $productsarray = explode(',' ,$products);
        $products = ProductLibrary::whereIn('id', $productsarray)->get();

        $servicesarray = explode(',', $services);
        $services = ServiceLibrary::whereIn('id', $servicesarray)->get();

        $client = Client::where('id', $client)->first();

        return View::make('OS.POS.view')
            ->with('products', $products)
            ->with('services', $services)
            ->with('client', $client);

    }

    public function Save(){

        $data = array(
            'clientid' => Input::get('clientid'),
            'method' => Input::get('method'),
            'identifier' => Input::get('identifier'),
            'depositcomments' => Input::get('depositcomments'),
            'quotecomments' => Input::get('quotecomments'),
            'table' => Input::get('table'),
            'salesperson' => Input::get('salesperson')

        );

        if($data['identifier'] === ""){
            $data['identifier'] = null;
        }

        $client = Client::where('id', $data['clientid'])->first();

        if(count($client) === 1){
            $quote = $this->CreateInvoice($data, $client);
            $depositid = DepositHelper::InvoiceDeposit($quote->id, $quote->getTotalFloat(),  $data['method'], $data['depositcomments'], Carbon::now(),"invoice", null, $data['identifier']);

            return ['status' => 'OK', 'invoiceid' => $quote->id];

        }else{
            return ['status' => 'notfound'];
        }


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

    public function SwipeDecode(){
        return ['status' => 'OK', 'swipe' => $this->CardDetails(Input::get('swipestring'))];
    }


    public function CardDetails($string){

        $tracks = explode ( '?' , $string);

        if(count($tracks) >= 2){
            if(isset($tracks[0])){
                $data['track1'] = $this->Track1($tracks[0]);
            }
            if(isset($tracks[1])){
                $data['track2'] = $this->Track2($tracks[1]);
            }

            return $data;
        }else{
            return false;
        }
    }

    public function Track1($string){

        $explode = explode ( '^' , $string);

        $data['CardNumber'] = substr($explode[0], 2);

        $names = explode ( '/' , $explode[1]);
        $data['FirstName'] = trim($names[1]);
        $data['LastName'] = trim($names[0]);

        $data['ExpireyYear'] = substr($explode[2] , 0, 2);
        $data['ExpireyMonth'] = substr($explode[2] , 2, 2);

        return $data;

    }

    public function Track2($string){

        $explode = explode ( '=' , $string);

        $data['CardNumber'] = substr($explode[0], 1);
        $data['ExpireyYear'] = substr($explode[1] , 0, 2);
        $data['ExpireyMonth'] = substr($explode[1] , 2, 2);

        return $data;
    }

    public function CreateInvoice($data, $client){
        $quote = new Quote;
        $quote->client_id = $client->id;
        if($client->primarycontact_id === null){
            $quote->contact_id = $client->contacts->first()->id;
        }else{
            $quote->contact_id = $client->primarycontact_id;
        }
        //$quote->quotenumber = $data['quotecomments'];
        $quote->comments = $data['quotecomments'];
        $quote->createdbyuser = $data['salesperson'];
        $quote->finalized = 1;
        $quote->finalizedbyuser = Auth::user()->id;
        $quote->finalizeddate = Carbon::now();
        if(Auth::user()->branch_id === null){
            $branch = Branch::where('default', "1")->first();
            if(count($branch) === 1){
                $quote->branch_id = $branch->id;
            }else{
                $quote->branch_id = Branch::all()->first()->id;
            }
        }else{
            $quote->branch_id = Auth::user()->branch_id;
        }

        $quote->save();

        foreach ($data['table'] as $row){
            $item = new QuoteItem;
            $item->SKU = $row['sku'];
            $item->description = $row['description'];
            $item->costperunit = $row['unitcost'];
            $item->units = $row['units'];
            $item->tax = $row['tax'];
            $item->quote_id = $quote->id;
            $item->user_id = Auth::user()->id;
            $item->citytax = $row['citytax'];
            $item->ReduceStock();
            $item->save();

        }

        return $quote;
    }

}
