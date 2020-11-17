<?php
namespace App\Helpers\Management\TaskHelpers;

#use Barryvdh\DomPDF\Facade as PDF;
#use Illuminate\Support\Facades\Validator;
#use \App\Providers\EventLog;
#use App\Models\ProductLibrary;
#use App\Models\Receipt;
#use App\Helpers\OS\EventHelper;
use App\Helpers\OS\QuoteHelper;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\OS\RecurringInvoice;
        
class RecurringInvoiceHelper
{
    public static function SwitchConnection($database, $username, $password, $port = "3306")
    {
        config(['database.connections.subdomain.username' => $username]);
        config(['database.connections.subdomain.password' => $password]);
        config(['database.connections.subdomain.port' => $port]);
        config(['database.connections.subdomain.database' => $database]);
        \DB::connection('subdomain')->reconnect();
    }

    public static function CurrentDate(){
        $now = Carbon::now();
        $now->hour = 00;
        $now->minute = 00;
        $now->second = 00;

        return $now;
    }

    public static function ParseDate($date){
        $now = Carbon::parse($date);
        $now->hour = 00;
        $now->minute = 00;
        $now->second = 00;

        return $now;
    }

    public static function CheckRecurringInvoices($account, $date = null)
    {
        self::SwitchConnection($account->database, $account->username, $account->password, $account->port);

        if($date === null){
            $now = self::CurrentDate();
        }else{
            $now = self::ParseDate($date);
        }

        $noend = RecurringInvoice::where('end', '=', null )->with('quote')->with('invoices')->get();
        $indate = RecurringInvoice::whereDate('end', '>', $now->toDateString())->with('quote')->with('invoices')->get();

        $count = 0;

        foreach($noend as $recurringinvoice){
            if(self::CheckRecurringInvoice($recurringinvoice, $now)){
                $count++;
            }
        }

        foreach($indate as $recurringinvoice){
            if(self::CheckRecurringInvoice($recurringinvoice, $now)){
                $count++;
            }
        }

        return $count;
    }

    public static function CheckRecurringInvoice($recurringinvoice, $checkfordate)
    {
        if($checkfordate->eq($recurringinvoice->NextDate())){
            //duplicate quote

            $newquote = self::DuplicateInvoice($recurringinvoice->id, $recurringinvoice->quote, $checkfordate, $recurringinvoice->NextNumber());

            $recurringinvoice->lastrun = $checkfordate;
            $recurringinvoice->save();

            if($recurringinvoice->email_to != null){
                QuoteHelper::SendEmail($recurringinvoice->email_contact->email, "This is a notice that an invoice has been generated on " . $newquote->formatDate_created_at_iso(), "Customer Invoice" ,$newquote, $newquote->client);
            }

            return true;
        }else{
            return false;
        }
    }


    public static function DuplicateInvoice($recurringinvoiceid, $quote, $now, $quotenumbersuffix)
    {
        $newquote = new Quote;
        $newquote->client_id = $quote->client_id;
        $newquote->contact_id = $quote->contact_id;
        $newquote->quotenumber = $quote->quotenumber . "-" . $quotenumbersuffix;
        $newquote->comments = $quote->comments;
        $newquote->createdbyuser = $quote->createdbyuser;
        $newquote->finalized = 1;
        $newquote->finalizedbyuser = $quote->finalizedbyuser;
        $newquote->finalizeddate = $now;
        $newquote->branch_id = $quote->branch_id;
        $newquote->recurringinvoice_id = $recurringinvoiceid;
        $newquote->save();

        foreach($quote->quoteitem as $olditem){
            $newquoteitem = new QuoteItem;
            $newquoteitem->description = $olditem->description;
            $newquoteitem->comments = $olditem->comments;
            $newquoteitem->costperunit = $olditem->costperunit;
            $newquoteitem->units = $olditem->units;
            $newquoteitem->tax = $olditem->tax;
            $newquoteitem->quote_id = $newquote->id;
            $newquoteitem->user_id = $olditem->user_id;
            $newquoteitem->SKU = $olditem->SKU;
            $newquoteitem->save();
        }

        return $newquote;
    }

    public static function SetupRecurringInvoice($quote, $startdate, $enddate, $repeat_freq, $repeat_number, $email_to)
    {
        $recurringinvoice = new RecurringInvoice;
        $recurringinvoice->start = $startdate;
        $recurringinvoice->end = $enddate;
        $recurringinvoice->repeat_freq = $repeat_freq;
        if($repeat_freq === "7"){
            $recurringinvoice->repeat_number = $repeat_number;
        }else{
            $recurringinvoice->repeat_number = "";
        }
        if($email_to === "0"){
            $recurringinvoice->email_to = null;
        }else{
            $recurringinvoice->email_to = $email_to;
        }
        $recurringinvoice->save();

        $quote->finalized = "2";
        $quote->finalizedbyuser = Auth::user()->id;
        $quote->finalizeddate = $startdate;
        $quote->is_recurring = $recurringinvoice->id;
        $quote->save();

        self::DuplicateInvoice($recurringinvoice->id, $quote, $startdate, "1");
    }

    public static function StopRecurring($id){
        $recurring = RecurringInvoice::where('id', '=', $id)->first();

        if(count($recurring) === 1){
            if($recurring->Canend()){
                $recurring->end = Carbon::now();
                $recurring->save();
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}