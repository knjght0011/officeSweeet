<?php
namespace App\Models;

use Carbon\Carbon;

class Receipt extends CustomBaseModel
{
    protected $table = 'receipts';
    
    protected $dates = ['date'];
    
    protected $casts = [
        'catagorys' => 'array',
    ];
    
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    
    public function entereduser()
    {
        return $this->belongsTo('App\Models\User', 'entered_by_user_id', 'id');
    }
    
    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id', 'id');
    }
    
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id');
    }
    
    public function quoteitem()
    {
        return $this->belongsTo('App\Models\Quoteitem', 'quoteitem_id', 'id');
    }

    public function filestore()
    {
        return $this->belongsTo('App\Models\OS\FileStore', 'filestore_id', 'id');
    }

    public function getFile(){
        if($this->filestore_id === null){
            return "";
        }else{
            return $this->filestore->file;
        }
    }

    public function LinkedAccountID() 
    {
        if(!is_null($this->vendor)){
            return $this->vendor->id;
        }
        
        if(!is_null($this->client)){
            return $this->client->id;
        }
        
        return "0";
    }
    
    public function LinkedAccountName() 
    {
        if(!is_null($this->vendor)){
            return $this->vendor->getName();
        }
        
        if(!is_null($this->client)){
            return $this->client->getName();
        }
        
        return "Miscellaneous Expense";
    }
    
    public function LinkedAccount() 
    {
        if(!is_null($this->vendor)){
            return $this->vendor;
        }
        
        if(!is_null($this->client)){
            return $this->client;
        }
        
        return "Miscellaneous Expense";
    }
    
    public function LinkedType() 
    {
        if(!is_null($this->vendor)){
            return "vendor";
        }
        
        if(!is_null($this->client)){
            return "client";
        }
        
        return "Miscellaneous Expense";
    }
    
    public function GetUserID() 
    {
        if(is_null($this->user_id)){
            return 0;
        }else{
            return $this->user_id;
        }
    }
    
    public function formatedAmount() 
    {
        return "$" . number_format($this->amount , 2, '.', '');
    }
    
    public function getUser() 
    {
        if(is_null($this->user)){
            return "None";
        }else{
            return $this->user->getShortName();
        }
    }
    
    public function getEnteredUser() 
    {
        $user_data = $this->entereduser;
        return $user_data->getShortName();
    }
    
    public function DateString() 
    {       
        return $this->date->toDateString();
    }
    
    public function CantEdit() 
    {
        $lastmonthend = MonthEnd::get()->last();
        if(count($lastmonthend) === 1){
            return $lastmonthend->IsBeforeThis($this->date);
        }else{
            return false;
        }
    }
    
    public function HasAttachment() 
    {
        if($this->filestore_id === null){
            return false;
        }else{
            return true;
        }
    }
    
    public function formatDate() 
    {
        return $this->date->format('jS M Y');
    }
    
    public function CatagoryString(){
        $string = "";
        foreach($this->catagorys as $key => $value){
            $string = $string . $key . ", ";
        }
        return $string;
    }
    
    public function getQuoteNumber(){
        if($this->quoteitem_id === null){
            return "";
        }else{
            return $this->quoteitem->getQuoteNumber();
        }
    }
    
    public function getQuoteID(){
        if($this->quoteitem_id === null){
            return "";
        }else{
            return $this->quoteitem->quote->id;
        }
    }
    //if catagorys are of old format, change them to be the new format
    //public function getcatagorysAttribute($value)
    //{
    //    reset($value);
    //    $first_key = key($value);
    //    $split = $this->amount / count($value);
     //   
    //    if(is_int($first_key)){
    //        $array = array ();
    //        foreach($value as $key  => $value){
    //            $array[$key] = $split;
    //        }
    //        return $array;
    //    }else{
    //        return $value;
    //    }
    //    
    //}
}
