<?php
namespace App\Models;

use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Mail;

use App\Mail\SendQuote;

class Quote extends CustomBaseModel
{
    protected $table = 'quote';
    protected $dates = ['finalizeddate'];
    //public $with = ['recurringmaster'];
    
    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            $length = 255;

            $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.
                      '0123456789';

            $str = '';
            $max = strlen($chars) - 1;

            for ($i=0; $i < $length; $i++){
              $str .= $chars[random_int(0, $max)];
            }

            $model->token = $str;
        });

    } 
    
    public function getTokenAttribute($value)
    {
        if($value === null){
            $length = 255;

            $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.
                      '0123456789';

            $str = '';
            $max = strlen($chars) - 1;

            for ($i=0; $i < $length; $i++){
              $str .= $chars[random_int(0, $max)];
            }

            $this->token = $str;
            $this->save();
            
            return $str;
        }else{
            return $value;
        }
    }

    public function recurringmaster()
    {
        return $this->belongsTo('App\Models\OS\RecurringInvoice', 'is_recurring', 'id');
    }

    public function items()
    {
        return $this->hasMany('App\Models\QuoteItem', 'quote_id', 'id');
    }

    public function purchaseorders()
    {
        return $this->hasMany('App\Models\OS\PurchaseOrders\PurchaseOrder', 'quote_id', 'id');
    }
    
    public function quoteitem()
    {
        return $this->hasMany('App\Models\QuoteItem', 'quote_id', 'id');
    }
    
    public function contact()
    {
        return $this->belongsTo('App\Models\ClientContact');
    }

    public function depositlink()
    {
        return $this->hasMany('App\Models\DepositLink', 'quote_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id', 'id')->withTrashed();
    }
    
    public function getClientName() 
    {
       return $this->client->getName();
    }
    
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch');
    }
    
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'createdbyuser', 'id')->withTrashed();
    }
    
    public function getUser() 
    {
        $user_data = $this->user;
        if($user_data === null){
            return "Unknown";
        }else{
            return $user_data->email;
        }
    }
    
    public function finalizeduser()
    {
        return $this->belongsTo('App\Models\User', 'finalizedbyuser', 'id');
    }
        
    public function getFinalizedUser() 
    {
        $user_data = $this->finalizeduser;
        return $user_data->email;
    }
    
    public function getTotalPayments() 
    {
        return number_format($this->getTotalPaymentsRAW() , 2, '.', '');
    }
    
    public function getTotalPaymentsRAW()
    {
    $total = 0.00;
        foreach($this->depositlink as $payment){

            $total = floatval($total) + floatval($payment->amount);

        }

        return $total;
       
    }
    
    public function getBallence() 
    {
        #$total = $this->getTotal();
        #$payments = $this->getTotalPayments();
        $ballence = $this->getTotalFloat() - $this->getTotalPaymentsRAW();


        return number_format($ballence, 2, '.', '');
       
    }
    
    public function getBalenceFloat() 
    {
        $total = $this->getTotalFloat();
        $payments = $this->getTotalPaymentsRAW();
        $ballence = $total - $payments;
        
        return floatval($ballence);
       
    }
    
    public function getStatus() 
    {
        $balence = $this->getBallence();
        $end = Carbon::parse($this->finalizeddate);
        $now = Carbon::now();
        $length = $end->diffInDays($now);
        if($this->deleted_at === null){
            if(floatval($balence) === floatval(0)){
                return "paid";
            }else if($length > 30){
                return "overdue";
            }else{
                return "due";
            }
        }else{
            return "void";
        }
    }
    
    public function GetInvoiceDate()
    {
        return date("Y-m-d H:i:s", strtotime("$this->created_at"));
    }

    public function getSubTotalFloat()
        {

        $total = 0.00;
        foreach($this->quoteitem as $item){
            $total = $total + $item->getSubTotalRAW();
        }

        return floatval($total);
    }


    public function getTaxFloat()
    {

        $total = 0.00;
        foreach($this->quoteitem as $item){
            $total = $total + $item->getTaxRAW() ;
        }

        return floatval($total);
    }

    public function getCityTaxFloat()
    {
        $total = 0.00;
        foreach($this->quoteitem as $item){
            $total = $total + $item->getCityTaxRAW();
        }
        return floatval($total);
    }



    public function getCost(){

        $totalcost = 0.00;
        foreach($this->quoteitem as $item){
            $totalcost = $totalcost + $item->getCost();
        }

        return $totalcost;
    }

    public function getProfit(){
        return $this->getTotalFloat() - $this->getCost();
    }

    public function getSubTotal()
    {
        return number_format($this->getSubTotalFloat() , 2, '.', '');
    }

    public function getTax()
    {
        return number_format($this->getTaxFloat() , 2, '.', '');
    }

    public function getCityTax()
    {
        return number_format($this->getCityTaxFloat() , 2, '.', '');
    }

    public function getTotalFloat()
    {

        $total = 0.00;
        foreach($this->quoteitem as $item){
            $total += $item->getTotalRAW();
        }

        return floatval($total);
    }

    public function getTotal()
    {
        return number_format($this->getTotalFloat(), 2, '.', '');
    }

    public function getDeposits(){
        return DepositLink::where('quote_id', '=', $this->id)->with('deposit')->get();
    }

    public function PDF(){
        $quote = $this;
        $client = $this->client;
        $currency = "$";


        return PDF::loadView('pdf.Invoice.viewinvoice', compact('quote', 'client', 'currency'));
    }

    public function PDFBase64(){
        return base64_encode($this->PDF()->stream());
    }

    public function SendEmail($email, $body, $subject){

        Mail::to($email)->send(new SendQuote($body, $subject, $this->PDF(), $this->token, $this->getType()));
    }

    public function getType(){
        if($this->finalized === 1){
            return "invoice";
        }else{
            return "quote";
        }
    }

    public function GetProductItems(){
        return $this->quoteitem->where('productlibrary_id', '!=', null);
    }

    public function GetServiceItems(){
        return $this->quoteitem->where('service_libraries_id', '!=', null);
    }

    public function GetHoursItems(){
        return $this->quoteitem->where('billablehours_id', '!=', null);
    }

    public function GetExpenceItems(){
        return $this->quoteitem->where('receipts_id', '!=', null);
    }

    public function GetNoLinkItems(){
        return $this->quoteitem->where('receipts_id', null)->where('billablehours_id', null)->where('productlibrary_id', null)->where('service_libraries_id', null);
    }

    public function getQuoteNumber(){
        if($this->quotenumber === ""){
            return 100000 + $this->id;
        }else{
            return $this->quotenumber;
        }
    }
}
