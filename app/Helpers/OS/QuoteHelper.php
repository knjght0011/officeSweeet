<?php
namespace App\Helpers\OS;

use Barryvdh\DomPDF\Facade as PDF;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

use \App\Providers\EventLog;

use App\Models\ProductLibrary;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Receipt;

use App\Helpers\OS\EventHelper;

use App\Mail\SendQuote;
        
class QuoteHelper
{

    public static function ValidateQuoteInput($data)
    {
        $rules = array(
            'client_id' => 'required|exists:clients,id',
            'contact_id' => 'required|exists:clientcontacts,id',
            'branch_id' => 'required|exists:branches,id',
            'comments' => 'string',
            'createdbyuser' => 'required|exists:users,id',
        );

        $validator = Validator::make($data, $rules);
        
        return $validator;

    }
    
    
    public static function ValidateQuoteItemInput($data)
    {
        $rules = array(
            'description' => 'string',
            'comments' => 'string',
            'costperunit' => 'numeric',
            'units' => 'numeric',
            'quote_id' => '',
            'user_id' => 'exists:users,id',

        );  
        
        $validator = Validator::make($data, $rules);
        
        return $validator;
    }
    
    public static function SaveQuote($data)
    {
        if ($data['id'] == 0)
        {
            $quote = new Quote;
        }else{
            $quote = Quote::find($data['id']);
                
            if ($quote->finalized === 1){
                return "Quote has been finalised, Unable to save";
            }
        }

        
        $quote->client_id = $data['client_id'];
        $quote->contact_id = $data['contact_id'];
        $quote->branch_id = $data['branch_id'];
        $quote->quotenumber = "";
        $quote->comments = $data['comments'];            
        $quote->createdbyuser = $data['createdbyuser'];
        $quote->finalized = 0;
        $quote->finalizedbyuser = null;
        $quote->finalizeddate = null;
        $quote->save();

        $quote->quotenumber = $quote->id + 100000;
        $quote->save();

        EventLog::add('New quote created ID:'.$quote->id.' for client ID:'.$quote->client_id);

        return $quote;
    }    
    
    public static function SaveRowItem($row, $quoteid){
        
        if ($row['id'] == 0)
        {
            $quoteitem = new QuoteItem;
        }
        else
        {
            $quoteitem = QuoteItem::find($row['id']);
        }
        
        $quoteitem->description = $row['description'];
        $quoteitem->SKU = $row['sku'];
        $quoteitem->quote_id = $quoteid;
        $quoteitem->costperunit = $row['costperunit'];
        $quoteitem->units = $row['units'];
        $quoteitem->tax = $row['tax-percent'];
        $quoteitem->citytax = $row['cityTax'];
        $quoteitem->user_id = Auth::user()->id;

        if(isset($row['type'])){
            switch ($row['type']) {
                case "Product":
                    $quoteitem->productlibrary_id = $row['productid'];
                    break;
                case "Service":
                    $quoteitem->service_libraries_id = $row['productid'];
                    break;
                case "Expense":
                    $quoteitem->receipts_id = $row['productid'];
                    break;
                case "Billable Hours":
                    $quoteitem->billablehours_id = $row['productid'];
                    break;
            }
        }

        $quoteitem->save();

        $explode = explode("-" , $quoteitem->SKU);
        
        EventHelper::add(var_export($explode, true));
        
        if($explode[0] === "Expense"){            
            $expense = Receipt::where('id' , $explode[1])->first();
            EventHelper::add(var_export($expense, true));
            $expense->quoteitem_id = $quoteitem->id;
            $expense->save();
        }
                
        return $quoteitem->id; 
                
    }

    public static function DeleteQuote($id){
        $quote = Quote::where('id', '=', $id)->first();

        if(count($quote) === 1) {
            if ($quote->finalized === 1) {
                return false;
            } else {
                $quote->delete();
                return true;
            }
        }else{
            return false;
        }
    }

    public static function DestroyExtraRows($quoteid, $quoteitemskeep)
    {
        
        $quoteitems = QuoteItem::where('quote_id', $quoteid)->get();

        foreach($quoteitems as $item)
        {
            if(!in_array($item->id, $quoteitemskeep))
            {
                QuoteItem::destroy($item->id);
            }
        }        

    }
    
    
    public static function CheckStock(Quote $quote)
    {
        $array = array();
        foreach($quote->quoteitem as $item){
            $product = ProductLibrary::where('SKU' , '=' , $item->SKU)->first();
            if(count($product) === 1){
                if($item->units > $product->stock){
                    $array[$item->SKU] = $item->SKU . "/" . $item->description . "/" . $product->stock . "/" . intval($item->units) . "/Not Enough Stock";
                    #Notify here
                    
                }else{
                    $array[$item->SKU] = $item->SKU . "/" . $item->description . "/" . $product->stock . "/" . intval($item->units) . "/Enough Stock";
                }
            }else{
                $array[$item->SKU] = $item->SKU . "/" . $item->description . "/Unknown/" . intval($item->units) . "/Item not located in product library";
            }
        }
        
        return $array;
    }



}