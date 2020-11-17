<?php

namespace App\Http\Controllers\OS\Quotes;

use App\Http\Controllers\Controller;

use App\Models\OS\Inventory\ServiceLibrary;
use App\Models\OS\PurchaseOrders\PurchaseOrder;
use App\Models\OS\PurchaseOrders\PurchaseOrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
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


    public function New($subdomain, $sourceclient)
    {
        $client = Client::where('id', $sourceclient)->first();
        if(count($client) === 1){

            $branches = Branch::all();

            $products = ProductLibrary::where('companyuse', 0)->get();
            $services = ServiceLibrary::all();
            $expenses = Receipt::where('client_id' , $client->id)->get();
            $hours = BillableHour::where('client_id' , $client->id)->get();

            return View::make('OS.Quotes.edit')
                ->with('products', $products)
                ->with('services', $services)
                ->with('expenses', $expenses)
                ->with('hours', $hours)
                ->with('branches', $branches)
                ->with('client', $client)
                ->with('sourceclient', $sourceclient);

        }else{
            return Response::make(view('errors.404'), 404);
        }

    }

    public function Edit($subdomain, $quote_id)
    {
        $quote = Quote::where('id', $quote_id)
                        ->with('quoteitem')
                        ->first();
        if(count($quote) === 1){

            $client = $quote->client;

            $branches = Branch::all();

            $products = ProductLibrary::where('companyuse', 0)->get();
            $services = ServiceLibrary::all();
            $expenses = Receipt::where('client_id' , $client->id)->get();
            $hours = BillableHour::where('client_id' , $client->id)->get();

            return View::make('OS.Quotes.edit')
                ->with('quote', $quote)
                ->with('products', $products)
                ->with('services', $services)
                ->with('expenses', $expenses)
                ->with('hours', $hours)
                ->with('branches', $branches)
                ->with('client', $client)
                ->with('sourceclient', $client->id);

        }else{
            return Response::make(view('errors.404'), 404);
        }

    }

    public function SaveQuote()
    {
        //retrieve data
        $quotedata = array(
            'id' => Input::get('id'),
            'client_id' => Input::get('client_id'),
            'branch_id' => Input::get('branch_id'),
            'contact_id' => Input::get('contact_id'),
            'comments' => Input::get('comment'),
            'createdbyuser' => Auth::user()->id,
        );

        $tabledata = Input::get('items');

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

            return ['status' => 'validation', 'errors' => $errors];

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

            $quote->load('quoteitem');

            return ['status' => 'OK', 'Quote' => $quote];
            //return array_merge($array1, QuoteHelper::CheckStock($quote));
        }
    }

    public function showPdf($subdomain, $id)
    {
        $quote = Quote::where('id', $id)
                        ->with('quoteitem')
                        ->withTrashed()
                        ->first();
        if (count($quote) === 1) {
            return $quote->PDF()->stream();
        } else {
            return Response::make(view('errors.404'), 404);
        }
    }

    public function ShowFinal($subdomain, $id){

        $quote = Quote::where('id', $id)
            ->with('quoteitem')
            ->first();
        if(count($quote) === 1){
            if($quote->finalized === 1){
                return View::make('OS.Quotes.summery')
                    ->with('quote', $quote);
            }else{
                return View::make('OS.Quotes.final')
                    ->with('quote', $quote);
            }
        }else{
            return Response::make(view('errors.404'), 404);
        }

    }

    public function DoFinal(){

        $data = array(
            'quoteid' => Input::get('quoteid'),
            'po' => Input::get('po'),
            'inventory' => Input::get('inventory'),
        );

        $quote = Quote::where('id', $data['quoteid'])->first();

        if(count($quote) === 1){

            $posbyvendor = array();
            if($data['po'] != null) {
                foreach ($data['po'] as $key => $value) {
                    if ($value === "1") {
                        $item = $quote->quoteitem->where('id', $key)->first();

                        $posbyvendor = $this->MakePO($posbyvendor, $quote, $item);
                        if ($item->getStock() != "Product Not held in Inventory") {
                            $this->ReduceStock($item);
                        }
                    }
                }
            }

            if($data['inventory'] != null){
                foreach($data['inventory'] as $key => $value){
                    if($value === "1") {
                        $this->ReduceStock($quote->quoteitem->where('id', $key)->first());
                    }
                }
            }

            $quote->finalized = 1;
            $quote->finalizedbyuser = Auth::user()->id;
            $quote->finalizeddate = Carbon::now();
            $quote->save();

            return ['status' => 'OK'];
        }else{
            return ['status' => 'notfound'];
        }
    }

    public function MakePO($posbyvendor, $quote, $item){

        if(isset($posbyvendor[$item->getVendorID()])){

            $poitem = new PurchaseOrderItem;
            $poitem->vendorref = $item->productlibrary->vendorref;
            $poitem->description = $item->productlibrary->productname;
            $poitem->units = $item->units;
            $poitem->unitcost = $item->productlibrary->cost;
            $poitem->product_id = $item->productlibrary->id;
            $poitem->purchaseorder_id = $posbyvendor[$item->getVendorID()]->id;
            $poitem->save();

        }else{
            $posbyvendor[$item->getVendorID()] = new PurchaseOrder;
            $posbyvendor[$item->getVendorID()]->comments = "Auto Generated from Invoice: " . $quote->quotenumber;
            $posbyvendor[$item->getVendorID()]->number = "";
            $posbyvendor[$item->getVendorID()]->shipping = 0;
            $posbyvendor[$item->getVendorID()]->taxpercent = 0;
            $posbyvendor[$item->getVendorID()]->createdby_id = Auth::user()->id;
            $posbyvendor[$item->getVendorID()]->vendor_id = $item->getVendorID();
            $posbyvendor[$item->getVendorID()]->branch_id = null;
            $posbyvendor[$item->getVendorID()]->quote_id = $quote->id;
            $posbyvendor[$item->getVendorID()]->save();

            $poitem = new PurchaseOrderItem;
            $poitem->vendorref = $item->productlibrary->vendorref;
            $poitem->description = $item->productlibrary->productname;
            $poitem->units = $item->units;
            $poitem->unitcost = $item->productlibrary->cost;
            $poitem->product_id = $item->productlibrary->id;
            $poitem->purchaseorder_id = $posbyvendor[$item->getVendorID()]->id;
            $poitem->save();

        }

        return $posbyvendor;

    }


    public function ReduceStock($quoteitem){
        if(count($quoteitem) === 1){
            $product = $quoteitem->productlibrary;

            if(count($product) === 1){
                $product->stock = $product->stock - $quoteitem->units;
                $product->save();

                return true;
            }else{
                return false;
            }
        }else{
            return false;
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

}


