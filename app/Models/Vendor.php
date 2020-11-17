<?php
namespace App\Models;

use App\Models\Address;
use App\Models\VendorContact;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;

class Vendor extends CustomBaseModel
{
    protected $table = 'vendors';
    
    public $with = ['address','primarycontact'];

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
            $address_data = Address::where('id', $this->address_id)->first();
            return $address_data;
        }
    }
    
    public function contacts()
    {
        return $this->hasMany('App\Models\VendorContact', 'vendor_id', 'id');
    }
    
    public function trashedcontacts()
    {
        return $this->hasMany('App\Models\VendorContact', 'vendor_id', 'id')->onlyTrashed();
    }
    
    public function checks()
    {
        return $this->hasMany('App\Models\Check', 'vendor_id', 'id');
    }
    
    public function reports()
    {
        return $this->hasMany('App\Models\OS\Report', 'vendor_id', 'id');
    }

    public function signing()
    {
        return $this->hasMany('App\Models\OS\Templates\Signing', 'vendor_id', 'id');
    }
    
    public function notes1()
    {
        return $this->hasMany('App\Models\VendorNote', 'vendor_id', 'id');
    }
    
    public function primarycontact()
    {
        return $this->belongsTo('App\Models\VendorContact', 'primarycontact_id', 'id');
    }

    public function getprimarycontactAttribute($value)
    {
        if($this->primarycontact_id === null){
            $contact = new VendorContact;
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

    public function products()
    {
        return $this->hasMany('App\Models\ProductLibrary', 'vendor_id', 'id');
    }

    public function receipts()
    {
        return $this->hasMany('App\Models\Receipt', 'vendor_id', 'id');
    }

    public function calendarevents()
    {
        return $this->hasMany('App\Models\CalendarEvents', 'vendor_id', 'id');
    }

    public function files()
    {
        return $this->hasMany('App\Models\OS\FileStore', 'vendor_id', 'id');
    }

    public function purchaseorders()
    {
        return $this->hasMany('App\Models\OS\PurchaseOrders\PurchaseOrder', 'vendor_id', 'id');
    }

    public function getName() 
    {
        if(is_null($this->name))
        {
            return $this->primarycontact->firstname . " " . $this->primarycontact->lastname;
        }else{
            return $this->name ;
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
    
    public function TotalExpenses($startdate, $enddate)
    {
        $VendorTotal = 0;
        foreach ($this->receipts as $receipt)
        {
            if ($receipt->date->between(Carbon::parse($startdate), Carbon::parse($enddate)))
            {
                $VendorTotal += $receipt->amount;
            }
        }
        return $VendorTotal;
    }
    
    public function TotalExpensesFormated($startdate, $enddate)
    {
        return "$" . number_format($this->TotalExpenses($startdate, $enddate), 2, '.', '');
    }
    
    
    public function getDeleted() 
    {
        if($this->deleted_at === null){
            return "Active";
        }else{
            return "Inactive";
        }
    }

    public function PoTotal(){
        $total = 0.00;
        foreach($this->purchaseorders as $order){
            $total = $total + $order->Total();
        }
        return $total;
    }

    public function getFutureSchedule(){

        //$schedule = Scheduler::whereDate('start', '>=', Carbon::today()->toDateString())->where('');
        $schedule = DB::table('scheduler')
            ->join('scheduler_parent', 'scheduler_parent.id', '=', 'scheduler.parent_id')
            ->join('users', 'users.id', '=', 'scheduler_parent.user_id')
            ->where('scheduler_parent.vendor_id', $this->id)
            ->whereDate('scheduler.start', '>', Carbon::today()->toDateString())
            ->get();

        return $schedule;

    }

    public function getCurrentSchedule(){

        //$schedule = Scheduler::whereDate('start', '>=', Carbon::today()->toDateString())->where('');
        $schedule = DB::table('scheduler')
            ->join('scheduler_parent', 'scheduler_parent.id', '=', 'scheduler.parent_id')
            ->join('users', 'users.id', '=', 'scheduler_parent.user_id')
            ->where('scheduler_parent.vendor_id', $this->id)
            ->whereDate('scheduler.start', '=', Carbon::today()->toDateString())
            ->get();

        return $schedule;

    }

    public function getPreviousSchedule(){

        //$schedule = Scheduler::whereDate('start', '>=', Carbon::today()->toDateString())->where('');
        $schedule = DB::table('scheduler')
            ->join('scheduler_parent', 'scheduler_parent.id', '=', 'scheduler.parent_id')
            ->join('users', 'users.id', '=', 'scheduler_parent.user_id')
            ->where('scheduler_parent.vendor_id', $this->id)
            ->whereDate('scheduler.start', '<', Carbon::today()->toDateString())
            ->get();

        return $schedule;

    }

    public function EmailJson(){

        $string = "{";

        foreach ($this->contacts as $contact){

            $string = $string . '"' . $contact->id . '":"'. $contact->email .'",';

        }

        $string = rtrim($string,',');

        $string = $string . "}";

        return $string;
    }

}
