<?php

namespace App\Models\OS\PurchaseOrders;

use App\Models\Branch;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use SoftDeletes;

    protected $table = 'purchaseorders';

    public function items()
    {
        return $this->hasMany('App\Models\OS\PurchaseOrders\PurchaseOrderItem', 'purchaseorder_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor', 'vendor_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id', 'id');
    }

    public function createdby()
    {
        return $this->belongsTo('App\Models\User', 'createdby_id', 'id');
    }

    public function quote()
    {
        return $this->belongsto('App\Models\Quote', 'quote_id', 'id');
    }

    public function PDF(){
        $order = $this;
        $currency = "$";

        $mainbranch = Branch::where('default', '=', '1')->first();

        return PDF::loadView('pdf.PurchaseOrder.view', compact('order', 'currency', 'mainbranch'));
    }

    public function PDFBase64(){
        return base64_encode($this->PDF()->stream());
    }

    public function POnumber(){

        $number = "000000" . $this->id;
        return substr($number, -6);

    }

    public function Subtotal(){
        $subtotal = 0.00;
        foreach($this->items as $item){
            $subtotal = $subtotal + $item->Total();
        }
        return $subtotal;

    }

    public function TaxAmount(){
        $percent = $this->Subtotal() / 100;
        return $percent * $this->taxpercent;
    }

    public function Total(){
        return $this->Subtotal() + $this->TaxAmount() + $this->shipping;
    }

    public function NumberOfItems(){

        $items = 0;
        foreach ($this->items as $item){
            $items+= $item->units;
        }
        return $items;

    }

    public function TotalReceived(){

        $items = 0;
        foreach ($this->items as $item){
            $items+= $item->received;
        }
        return $items;
    }

    public function getStatus(){

        switch ($this->status) {
            case 1:
                return "Created"; //When you create a purchase order, the status is Created.

            case 2:
                return "Ordered"; //When you Send the PO;

            case 3:
                return "Pending Delivery"; //Not Used ATM, Added encase there is some sort of acknolegment from vendor in future

            case 4:
                if($this->NumberOfItems() - $this->TotalReceived() == 0){
                    return "Received"; //Received and complete
                }else{
                    return "Back Ordered"; //Received and not complete
                }

            case 5:
                return "Cancelled"; //Canceled, Duh

            default:
                return "Error"; //Couldnt find a status, this is akward....
        }
    }

}
