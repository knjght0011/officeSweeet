<?php namespace App\Models\OS;

use Illuminate\Database\Eloquent\Model;

use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends CustomBaseModel
{
    use SoftDeletes;

    protected $table = 'reports';

    public function createdby()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    }
    
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor');
    }

    public function revisions()
    {
        return $this->hasMany('App\Models\OS\Report', 'originalreport_id', 'id');
    }
    
    public function getUserCreatedBy()
    {
        $user_data = $this->createdby;
        #var_dump($user_data);
        if($user_data === null){
            return "Unknown";
        }else{
            return $user_data->email;
        }
    }

    public function dataID(){
        if($this->client_id != null){
            return $this->client_id;
        }
        if($this->vendor_id != null){
            return $this->vendor_id;
        }
        if($this->user_id != null){
            return $this->user_id;
        }
    }

    public function GetType(){
        if($this->client_id != null){
            return "client";
        }
        if($this->vendor_id != null){
            return "vendor";
        }
        if($this->user_id != null){
            return "employee";
        }
    }

    public function GetLast(){
        if(count($this->revisions) === 0){
            return $this;
        }else{
            return $this->revisions->last();
        }
    }

    public function PDFObject(){

        $data = $this->reportdata;

        $pdf = PDF::loadView('pdf.Templates.Report', compact('data'));

        return $pdf;
    }

    public function PDFBase64(){
        return base64_encode($this->PDFObject()->stream());
    }

    public function Contacts(){
        if($this->client_id != null){
            return $this->client->contacts;
        }else{
            if($this->vendor_id != null){
                return $this->vendor->contacts;
            }else{
                return [$this->user];
            }
        }
    }
    
}
