<?php
namespace App\Models;
use App\Models\ClientContact;

use App\Models\Management\Account;
use App\Models\OS\Email\Email;
use App\Models\OS\Scheduler;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Client extends CustomBaseModel {
    
    protected $table = 'clients';
        
    public $with = ['address','primarycontact'];

    public $dates = ['accessed_at'];
        
    public function address()
    {
        return $this->belongsTo('App\Models\Address');
    }

    public function getaddressAttribute($value)
    {
        if(is_null($this->address_id))
        {
            $address_data = new Address;
            return $address_data;
        }else{
            return $this->getRelation('address');
        }
    }

    public function primarycontact()
    {
        return $this->belongsTo('App\Models\ClientContact', 'primarycontact_id', 'id');
    }

    public function assigned_to_user()
    {
        return $this->belongsTo('App\Models\User', 'assigned_to', 'id');
    }

    public function getprimarycontactAttribute($value)
    {
        if($this->primarycontact_id === null){
            $contact = new ClientContact;
            $contact->firstname = "[[no primary contact set]]";
            $contact->middlename = "[[no primary contact set]]";
            $contact->lastname = "[[no primary contact set]]";
            $contact->ssn = "[[no primary contact set]]";
            $contact->driverslicense = "[[no primary contact set]]";
            $contact->email = "[[no primary contact set]]";
            $contact->contacttype = "[[no primary contact set]]";
            $contact->officenumber = "[[no primary contact set]]";
            $contact->mobilenumber = "[[no primary contact set]]";
            $contact->homenumber = "[[no primary contact set]]";
            return $contact;
        }else{
            return $this->getRelation('primarycontact');
        }
    }
    
    /*
    public function getnameAttribute($value)
    {
        if($value === null)
        {
            return $this->primarycontact->firstname . " " . $this->primarycontact->lastname;
        }else{
            return $value;
        }
    }
    */
    public function contacts()
    {
        return $this->hasMany('App\Models\ClientContact', 'client_id', 'id');
    }
    
    public function trashedcontacts()
    {
        return $this->hasMany('App\Models\ClientContact', 'client_id', 'id')->onlyTrashed();
    }
    
    
    public function checks()
    {
        return $this->hasMany('App\Models\Check', 'client_id', 'id');
    }
    
    public function reports()
    {
        return $this->hasMany('App\Models\OS\Report', 'client_id', 'id');
    }

    public function signing()
    {
        return $this->hasMany('App\Models\OS\Templates\Signing', 'client_id', 'id');
    }

    public function getEmails(){

        $array = [];
        foreach($this->contacts as $contact){
            array_push($array, $contact->id);
        }

        return Email::where('contact_type', 'Client')
                        ->whereIn('contact_id', $array)
                        ->get();

    }

    public function quotes()
    {
        return $this->hasMany('App\Models\Quote', 'client_id', 'id')->withTrashed();
    }
    
    public function getInvoices() 
    {
        return $this->quotes->where('finalized','=', 1);
    }

    public function getRecurringInvoices()
    {
        return $this->quotes->where('finalized','=', 2);
    }


    public function getQuotes() 
    {
        return $this->quotes->where('finalized','=', 0)->where('deleted_at' , '=', null);
    }   

    public function calendarevents()
    {
        return $this->hasMany('App\Models\CalendarEvents', 'client_id', 'id');
    }

    public function billablehours()
    {
        return $this->hasMany('App\Models\OS\BillableHour', 'client_id', 'id');
    }

    public function files()
    {
        return $this->hasMany('App\Models\OS\FileStore', 'client_id', 'id');
    }

    public function patient()
    {
        return $this->hasMany('App\Models\OS\Clients\Patient', 'client_id', 'id');
    }

    public function getStatus()
    {

        if($this->existingclient === 1){
             return "Client";
        }else{
            if(count($this->getInvoices()) === 0){
                return "Prospect";
            }else{
                return "Client";
            }
        }
    }


    public function getPreviousID($id){
        $clients = Client::where('id', '<', $id)->orderBy('id', 'desc')->get();
        if($clients !== null){
            foreach($clients as $client){
                if($client->getStatus() === $this->getStatus()){
                    return $client->id;
                }
            }
        }

        $clients = Client::orderBy('id', 'desc')->get();
        if($clients !== null){
            foreach($clients as $client){
                if($client->getStatus() === $this->getStatus()){
                    return $client->id;
                }
            }
        }

        return $this->id;
    }

    public function getNextID($id){
        $clients = Client::where('id', '>', $id)->get();
        if($clients !== null){
            foreach($clients as $client){
                if($client->getStatus() === $this->getStatus()){
                    return $client->id;
                }
            }
        }

        $clients = Client::get();
        if($clients !== null){
            foreach($clients as $client){
                if($client->getStatus() === $this->getStatus()){
                    return $client->id;
                }
            }
        }

        return $this->id;
    }
    /*
    public function getPreviousID1($id){
        $previousUserID = Client::where('id', '<', $id)->orderBy('id', 'desc')->first();
        if($previousUserID === null){
            $previousUserID = Client::orderBy('id', 'desc')->first();
            if($previousUserID->getStatus() === $this->getStatus()){
                return $previousUserID->id;
            }else{
                return $this->getPreviousID($previousUserID->id);
            }
        }else{
            if($previousUserID->getStatus() === $this->getStatus()){
                return $previousUserID->id;
            }else{
                return $this->getPreviousID($previousUserID->id);
            }
        }
    }

    public function getNextID1($id){
        $previousUserID = Client::where('id', '>', $id)->first();
        if($previousUserID === null){
            $previousUserID = Client::all()->first();
            if($previousUserID->getStatus() === $this->getStatus()){
                return $previousUserID->id;
            }else{
                return $this->getNextID($previousUserID->id);
            }
        }else{
            if($previousUserID->getStatus() === $this->getStatus()){
                return $previousUserID->id;
            }else{
                return $this->getNextID($previousUserID->id);
            }
        }
    }
    */

    public function ConversionDate()
    {
        if(count($this->getInvoices()) > 0)
        {
            $Invoice = $this->getInvoices()->first();
            return $Invoice->finalizeddate;
        }else{
            return null;
        }
    }
    
    public function ConvertedBy()
    {
        if(count($this->getInvoices()) > 0)
        {
            $Invoice = $this->getInvoices()->first();
            return $Invoice->finalizedbyuser;
        }else{
            return null;
        }
    }
        
    public function getDeleted() 
    {
        if($this->deleted_at === null){
            return "Active";
        }else{
            return "Inactive";
        }
    } 
    
    public function notes1()
    {
        return $this->hasMany('App\Models\ClientNote', 'client_id', 'id');
    }

    public function RecentNotes(){

        return $this->notes1->reverse()->take(5);

    }

    public function receipts()
    {
        return $this->hasMany('App\Models\Receipt', 'client_id', 'id');
    }
    
    public function getName() 
    {
        if(is_null($this->name))
        {
            return $this->primarycontact->firstname . " " . $this->primarycontact->lastname;
        }else{
            return $this->name;
        }
    }

    public function getNameAddon(){

        $account = $this->getAccount();

        if($account != null){
            if($account->plan_name === "SOLO"){
                return " (SOLO)";
            }

            if(count($account->subscriptions) === 0){
                return " (TRIAL)";
            }
        }
    }
    
    public function getPrimaryContactName() 
    {
        if($this->primarycontact_id === null){
            return "No Primary Contact Set";
        }else{
            return $this->primarycontact->firstname . " " . $this->primarycontact->middlename . " " . $this->primarycontact->lastname;
        }
    }

    public function getOpenQuoteValue($format)
    {
        $balence = 0;
        foreach($this->getQuotes() as $quote){
            $balence = $balence + $quote->getTotalFloat();
        }
        if($format == false){
            return $balence;
        }else{
            return number_format($balence , 2, '.', '');
        }
    }

    public function getBalence($format) 
    {
        $balence = 0;
        foreach($this->getInvoices() as $invoice){
            if($invoice->deleted_at === null){
                $balence = $balence + $invoice->getBalenceFloat();
            }
        }
        if($format == false){
            return $balence;
        }else{
            return number_format($balence , 2, '.', '');
        }
    }

    public function getTotalInvoiceAmountBetweenDates($startdate, $enddate)
    {
        $balence = 0;
        $start = Carbon::parse($startdate);
        $end = Carbon::parse($enddate);


        foreach($this->getInvoices() as $invoice){
            if($invoice->deleted_at === null){
                if($invoice->finalizeddate->gt($start)){
                    if($invoice->finalizeddate->lt($end)){
                        $balence = $balence + $invoice->getTotalFloat();
                    }
                }
            }
        }

        return number_format($balence , 2, '.', '');

    }

    public function getTotalInvoicePaymentsBetweenDates($startdate, $enddate)
    {
        $balence = 0;
        $start = Carbon::parse($startdate);
        $end = Carbon::parse($enddate);


        foreach($this->getInvoices() as $invoice){
            if($invoice->deleted_at === null){
                if($invoice->finalizeddate->gt($start)){
                    if($invoice->finalizeddate->lt($end)){
                        $balence = $balence + $invoice->getTotalPaymentsRAW();
                    }
                }
            }
        }

        return number_format($balence , 2);

    }

    public function getTotalInvoiceBalenceBetweenDates($startdate, $enddate)
    {
        $balence = 0;
        $start = Carbon::parse($startdate);
        $end = Carbon::parse($enddate);


        foreach($this->getInvoices() as $invoice){
            if($invoice->deleted_at === null){
                if($invoice->finalizeddate->gt($start)){
                    if($invoice->finalizeddate->lt($end)){
                        $balence = $balence + $invoice->getBalenceFloat();
                    }
                }
            }
        }

        return number_format($balence , 2, '.', '');

    }
    
    public function getBalenceLessThan30($format) 
    {
        $balence = 0;
        $first = \Carbon\Carbon::now();
        $second = \Carbon\Carbon::now()->addDays(-30);
        foreach($this->getInvoices() as $quote){
            $date = new \Carbon\Carbon($quote->finalizeddate);
            if($date->between($first, $second)){
                $balence = $balence + $quote->getBalenceFloat();
            }
        }
        
        if($format == false){
            return $balence;
        }else{
            return number_format($balence , 2, '.', '');
        }
    }
    
    public function getBalence31to60($format) 
    {
        $balence = 0;
        $first = \Carbon\Carbon::now()->addDays(-30);
        $second = \Carbon\Carbon::now()->addDays(-60);
        foreach($this->getInvoices() as $quote){
            $date = new \Carbon\Carbon($quote->finalizeddate);
            if($date->between($first, $second)){
                $balence = $balence + $quote->getBalenceFloat();
            }
        }
        
        if($format == false){
            return $balence;
        }else{
            return number_format($balence , 2, '.', '');
        }
        
       
    }
    
    public function getBalence61to90($format) 
    {
        $balence = 0;
        $first = \Carbon\Carbon::now()->addDays(-60);
        $second = \Carbon\Carbon::now()->addDays(-90);
        
        $numquotes = 0;
        $numfinal = 0;
        $numinbracket= 0;
        
        foreach($this->getInvoices() as $quote){
            $numquotes++;
            $numfinal++;
            $date = new \Carbon\Carbon($quote->finalizeddate);
            if($date->between($first, $second)){
                $numinbracket++;
                $balence = $balence + $quote->getBalenceFloat();
            }
        }
        
        if($format == false){
            return $balence;
        }else{
            return number_format($balence , 2, '.', '');
        }
        
    }
    
    public function getBalence90plus($format) 
    {
        $balence = 0;
        $first = \Carbon\Carbon::now()->addDays(-90);
        foreach($this->getInvoices() as $quote){
            $date = new \Carbon\Carbon($quote->finalizeddate);
            if($date <= $first){
                $balence = $balence + $quote->getBalenceFloat();
            }
        }
        
        if($format == false){
            return $balence;
        }else{
            return number_format($balence , 2, '.', '');
        }
       
    }

    public function assigned_to_user_name(){
        if($this->assigned_to === null){
            return "None";
        }else{
            return $this->assigned_to_user->getShortName();
        }
    }

    public function LastNoteTime(){
        if(count($this->notes1) > 0){
            return $this->notes1->last()->formatDate_created_at_no_time();
        }else{
            return "No Notes";
        }
    }

    public function LastNoteISOTime(){
        if(count($this->notes1) > 0){
            return $this->notes1->last()->formatDateTime_created_at_iso();
        }else{
            return "No Notes";
        }
    }

    public function LastNoteDate(){
        if(count($this->notes1) > 0){
            return $this->notes1->last()->created_at->toDateString();
        }else{
            return "No Notes";
        }
    }

    public function LastNoteContent(){
        if(count($this->notes1) > 0){
            return $this->notes1->last()->note;
        }else{
            return "No Notes";
        }
    }

    public function LastNoteGetNote(){
        if(count($this->notes1) > 0){
            return strip_tags($this->notes1->last()->getNote());
        }else{
            return "No Notes";
        }
    }

    public function getAccount(){

        if(app()->make('account')->subdomain === "local" || app()->make('account')->subdomain === "lls"){
            $account = Account::where('client_id', '=', $this->id)->first();
            if(count($account) === 1){
                return $account;
            }else{
                return null;
            }
        }else {
            return null;
        }

    }

    public function getFutureSchedule(){

        //$schedule = Scheduler::whereDate('start', '>=', Carbon::today()->toDateString())->where('');
        $schedule = DB::table('scheduler')
                        ->join('scheduler_parent', 'scheduler_parent.id', '=', 'scheduler.parent_id')
                        ->join('users', 'users.id', '=', 'scheduler_parent.user_id')
                        ->where('scheduler_parent.client_id', $this->id)
                        ->whereDate('scheduler.start', '>', Carbon::today()->toDateString())
                        ->get();

        return $schedule;

    }

    public function getCurrentSchedule(){

        //$schedule = Scheduler::whereDate('start', '>=', Carbon::today()->toDateString())->where('');
        $schedule = DB::table('scheduler')
            ->join('scheduler_parent', 'scheduler_parent.id', '=', 'scheduler.parent_id')
            ->join('users', 'users.id', '=', 'scheduler_parent.user_id')
            ->where('scheduler_parent.client_id', $this->id)
            ->whereDate('scheduler.start', '=', Carbon::today()->toDateString())
            ->get();

        return $schedule;

    }

    public function getPreviousSchedule(){

        //$schedule = Scheduler::whereDate('start', '>=', Carbon::today()->toDateString())->where('');
        $schedule = DB::table('scheduler')
            ->join('scheduler_parent', 'scheduler_parent.id', '=', 'scheduler.parent_id')
            ->join('users', 'users.id', '=', 'scheduler_parent.user_id')
            ->where('scheduler_parent.client_id', $this->id)
            ->whereDate('scheduler.start', '<', Carbon::today()->toDateString())
            ->get();

        return $schedule;

    }

    public function getFormatedPhoneNumber() {
        // add logic to correctly format number here
        // a more robust ways would be to use a regular expression
        $Number = $this->phonenumber;
        $Number = (int) preg_replace('/\D/', '', $Number);
        return "(".substr($Number, 0, 3).") ".substr($Number, 3, 3)."-".substr($Number,6);
    }

    public function getPrimaryContactFormatedPhoneNumber()
    {
        $data = "";
        if($this->primarycontact_id === null){
            $data = $this->phonenumber;
        }else {
            $contactSelector = $this->primarycontact->primaryphonenumber;
            if ($contactSelector == 1) {
                $data = $this->primarycontact->officenumber;
            } elseif ($contactSelector == 2) {
                $data = $this->primarycontact->mobilenumber;
            } elseif ($contactSelector == 3) {
                $data = $this->primarycontact->homenumber;
            }else{
                $data = $this->phonenumber;
            }
        }
        $data = (int)preg_replace('/\D/', '', $data);
        return "(".substr($data, 0, 3).") ".substr($data, 3, 3)."-".substr($data, 6);
    }
}