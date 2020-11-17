<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;

use App\Models\Branch;
use App\Models\Client;
use App\Models\ProductLibrary;
use App\Models\Quote;
use App\Models\Receipt;
use App\Models\OS\BillableHour;

use App\Helpers\OS\QuoteHelper;
use App\Helpers\OS\RecurringInvoiceHelper;
use App\Helpers\OS\NotificationHelper;
use App\Helpers\OS\SettingHelper;

class QuoteController extends Controller {
       
    #Route::get('Clients/Quote/New/{id}', array('uses' => 'QuoteController@showQuote'));
    public function newQuote($subdomain, $id)
    {
        $branches = Branch::all();
        if(count($branches) === 0){
            return View::make('OS.WarningScreens.NoBranch');
        }

        $client_id = intval($id);
        if ($client_id !== 0) {
            
            $client = Client::where('id', $client_id)
                ->with('address')
                ->with('contacts')
                ->first();
            
            $products = ProductLibrary::where('companyuse', 0)->get();
            $branches = Branch::all();

            $expenses = Receipt::where('client_id' , $client->id)->get();

            $hours = BillableHour::where('client_id' , $client->id)->get();
            
            $currency = "$";
            
            return View::make('OS.QuotesOld.quote')
                ->with('client', $client)
                ->with('products', $products)
                ->with('expenses', $expenses)
                ->with('hours', $hours)
                ->with('branches', $branches)
                ->with('currency', $currency);
            
        } else {
            return $this->Error('invalid client id', $client_id);
        }
    }
    
    #Route::get('Clients/Quote/Edit/{id}', array('uses' => 'QuoteController@editQuote'));
    public function editQuote($subdomain, $id)
    {

        $quote = Quote::where('id', $id)
            ->with('quoteitem')
            ->first();

        if(count($quote) === 1){

            if ($quote->finalized === 1){
                $this->Error('Quote has been finalised, Unable to edit', 'Quote has been finalised, Unable to edit');
            }
            
            $client = Client::where('id', $quote->client_id)
                ->with('address')
                ->with('contacts')
                ->first();
            
            $products = ProductLibrary::all();
            $branches = Branch::all();

            $expenses = Receipt::where('client_id' , $client->id)->get();

            $hours = BillableHour::where('client_id' , $client->id)->get();
            
            $currency = "$";
            
            return View::make('OS.QuotesOld.quote')
                ->with('quote', $quote)    
                ->with('client', $client)
                ->with('products', $products)
                ->with('expenses', $expenses)
                ->with('hours', $hours)
                ->with('branches', $branches)
                ->with('currency', $currency);
            
        } else {
            return $this->Error('invalid client id', $id);
        }
    }
    
    #Route::post('Client/Quote/Save', array('uses' => 'QuoteController@SaveQuote'));
    public function SaveQuote()
    {
        //retrieve data
        $quotedata = array(
            'id' => Input::get('id'),
            'client_id' => Input::get('client_id'),
            'branch_id' => Input::get('branch_id'),
            'contact_id' => Input::get('contact_id'),
            'quotenumber' => Input::get('quotenumber'),
            'comments' => Input::get('comments'),
            'createdbyuser' => Auth::user()->id,
        );
        
        $tabledata = Input::get('tabledata');
        
        $validator[0] = QuoteHelper::ValidateQuoteInput($quotedata);
        
        Foreach($tabledata as $data){
            array_push($validator, QuoteHelper::ValidateQuoteItemInput($data));
        }
        
        $valid = true;
        $errors = array();
        foreach($validator as $v)
        {
            if ($v->fails()){
                $valid = false;
                foreach ($v->errors()->toArray() as $error)
                {
                    array_push($errors, $error);
                }
            }
        }
        
        if ($valid == false){
            
            return $errors;
            
        } else {
            
            $quote = QuoteHelper::SaveQuote($quotedata);
            
            $returnstring = $quote->id;
            $quoteitemskeep = array();
            
            foreach ($tabledata as $row){
                $id = QuoteHelper::SaveRowItem($row, $quote->id);
                array_push($quoteitemskeep, $id);
                $returnstring = $returnstring ."/". $id;
            }
            
            $array1[0] = "ok";
            $array1[1] = $returnstring;
            
            QuoteHelper::DestroyExtraRows($quote->id, $quoteitemskeep);
            
            return array_merge($array1, QuoteHelper::CheckStock($quote));
        }
    }

    public function Delete(){
        $id = Input::get('id');

        if(QuoteHelper::DeleteQuote($id)){
            return "done";
        }else{
            return "fail";
        }

    }
    
    public function ShowFinalize($subdomain, $id)
    {
        $quote = Quote::where('id', $id)
                ->where('finalized' , '0')
                ->with('quoteitem')
                ->first();
        
        if(count($quote) === 1){
            
            $canfinal = true;
            foreach($quote->quoteitem as $item){
                if($item->getProductStatus() === "Out of stock.") {
                    $canfinal = false;
                }
            }

            $client = Client::where('id', $quote->client_id)
                ->with('address')
                ->with('contacts')
                ->first();


            return View::make('OS.QuotesOld.final')
                ->with('client', $client)
                ->with('quote', $quote);

            /*
            if($canfinal === true){
                $quote->finalized = 1;
                $quote->finalizedbyuser = Auth::user()->id;
                $quote->finalizeddate = Carbon::now();
                $quote->save();

                foreach($quote->quoteitem as $item){
                    $product = $item->getProduct();
                    if($product !== false){
                        $product->stock = intval($product->stock) - intval($item->units);
                        $product->save();
                    }        
                }
                
                return Redirect::to(url('/Clients/View/' . $quote->client_id . '/transactions'));
            }else{
            */


            //}
        }else{
            //error
            return "error";
        }
    }

    
    public function FinalizeQuote()
    {
        $end = Input::get('enddate');
        if($end === "Forever."){
            $end = null;
        }else{
            $end = Carbon::parse(Input::get('enddate'));
        }

        $data = array(
            'id' => Input::get('id'),
            'repeat' => Input::get('repeat'),
            'startdate' => Carbon::parse(Input::get('startdate')),
            'enddate' => $end,
            'repeat_freq' => Input::get('repeatschedule'),
            'repeat_number' => Input::get('repeatdays'),
            'email_to' => Input::get('repeatemail'),
        );

        $quote = Quote::where('id', $data['id'])->where('finalized', '0')->with('quoteitem')->first();

        if (count($quote) === 1) {

            if ($data['repeat'] === 'yes') {
                RecurringInvoiceHelper::SetupRecurringInvoice($quote, $data['startdate'], $data['enddate'], $data['repeat_freq'], $data['repeat_number'], $data['email_to']);
            } else {
                $quote->finalized = 1;
                $quote->finalizedbyuser = Auth::user()->id;
                $quote->finalizeddate = Carbon::now();
                $quote->save();
            }


            foreach ($quote->quoteitem as $item) {
                $product = $item->getProduct();
                if ($product !== false) {
                    if ($product->trackstock === 1) {
                        $product->stock = intval($product->stock) - intval($item->units);
                        $product->save();
                    }

                }
            }

            if (SettingHelper::GetSetting("inventorymanagerid") != null){
                NotificationHelper::CreateNotification('Inventory Issue!', 'There is an issue on the stock level of some products, Please see the Inventory Report in the Reporting section for more details.', '', 'inventory', SettingHelper::GetSetting("inventorymanagerid"));
            }

            return ['status' => 'OK'];
        }else{    
            return ['status' => 'quotenotfound'];
        }
    }              
}

/*
 * $stockdata = Input::get('data');
foreach($quote->quoteitem as $item){
    if($item->getProductStatus() === "Out of stock.") {
        if(array_key_exists( $item->id , $stockdata )){
            switch ($stockdata[$item->id]) {
                case "1":
                    //Put Stock On Back Order


                    break;
                case "2":
                    //Reduce Quote to X
                    $item->units = $item->getProduct()->stock;

                    break;
                default:
                    return "Error:optioninvalid";
            }
        }else{
            return "Error:nooption";
        }
    }
}
*/

