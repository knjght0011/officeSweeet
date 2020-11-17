<?php
namespace App\Http\Controllers\OS\PurchaseOrders;

use App\Models\Branch;
use App\Models\OS\PurchaseOrders\PurchaseOrder;
use App\Models\OS\PurchaseOrders\PurchaseOrderItem;
use App\Models\ProductLibrary;
use App\Models\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

use App\Models\Client;
use App\Models\Quote;

use App\Helpers\OS\QuoteHelper;
use App\Helpers\OS\RecurringInvoiceHelper;
use App\Helpers\OS\NotificationHelper;
use App\Helpers\OS\SettingHelper;

class PurchaseOrdersController extends Controller {

    public function new($subdomain, $sourcevendor)
    {
        $vendors = Vendor::all();
        $vendor = Vendor::where('id', $sourcevendor)->first();

        $order = null;
        if(count($vendor) === 1){

            $branches = Branch::all();
            $products = ProductLibrary::where('vendor_id', $vendor->id)->get();
            $clients = Client::all();

            return View::make('OS.PurchaseOrders.edit')
                ->with('products', $products)
                ->with('branches', $branches)
                ->with('vendors', $vendors)
                ->with('vendor', $vendor)
                ->with('clients', $clients)
                ->with('order',$order)
                ->with('sourcevendor', $sourcevendor);
        }else{
            return Response::make(view('errors.404'), 404);
        }

    }

    public function edit($subdomain, $id)
    {
        $order = PurchaseOrder::where('id', '=', $id)->first();

        if(count($order) === 1){

            if($order->status > 2){
                return Response::make(view('errors.404'), 404);
            }else{
                $vendor = Vendor::where('id', $order->vendor_id)->first();
                $vendors = Vendor::all();
                $branches = Branch::all();
                $products = ProductLibrary::all();
                $clients = Client::all();

                return View::make('OS.PurchaseOrders.edit')
                    ->with('products', $products)
                    ->with('branches', $branches)
                    ->with('vendor', $vendor)
                    ->with('vendors', $vendors)
                    ->with('clients', $clients)
                    ->with('order', $order);
            }
        } else {
            return Response::make(view('errors.404'), 404);
        }
    }

    public function Save()
    {
        //retrieve data
        $data = array(
            'id' => Input::get('id'),
            'comment' => Input::get('comment'),
            'branchid' => Input::get('branchid'),
            'vendorid' => Input::get('vendorid'),
            'items' => Input::get('items'),
            'shipping' => Input::get('shipping'),
            'taxpercent' => Input::get('taxpercent'),
        );

        $order =  PurchaseOrder::where('id', '=', $data['id'])
                                    ->with('items')
                                    ->first();

        if(count($order) === 1){

            $order->comments = $data['comment'];
            $order->number = "";
            $order->shipping = $data['shipping'];
            $order->taxpercent = $data['taxpercent'];
            $order->createdby_id = Auth::user()->id;
            $order->vendor_id = $data['vendorid'];
            $order->branch_id = $data['branchid'];
            $order->save();

            $itemskeep = array();

            foreach($data['items'] as $item){

                $poitem = PurchaseOrderItem::where('id', '=', $item['itemid'])->first();

                if(count($poitem) != 1){
                    $poitem = new PurchaseOrderItem;
                }

                $poitem->vendorref = $item['vendorref'];
                $poitem->description = $item['description'];
                $poitem->units = $item['units'];
                $poitem->unitcost = $item['unitcost'];
                $poitem->product_id = $item['productid'];
                $poitem->purchaseorder_id = $order->id;
                $poitem->save();

                array_push($itemskeep, $poitem->id);

            }

            $allitems = PurchaseOrderItem::where('purchaseorder_id', '=', $order->id)->get();

            foreach($allitems as $item)
            {
                if(!in_array($item->id, $itemskeep))
                {
                    $item->delete();
                }
            }

            $order->load('items');

            return ['status' => 'OK', 'PurchaseOrder' => $order];

        }else{

            $order = new PurchaseOrder;
            $order->comments = $data['comment'];
            $order->number = "";
            $order->shipping = $data['shipping'];
            $order->taxpercent = $data['taxpercent'];
            $order->createdby_id = Auth::user()->id;
            $order->vendor_id = $data['vendorid'];
            $order->branch_id = $data['branchid'];
            $order->save();

            foreach($data['items'] as $item){

                $poitem = new PurchaseOrderItem;
                $poitem->vendorref = $item['vendorref'];
                $poitem->description = $item['description'];
                $poitem->units = $item['units'];
                $poitem->unitcost = $item['unitcost'];
                $poitem->product_id = $item['productid'];
                $poitem->purchaseorder_id = $order->id;
                $poitem->save();

            }

            $order->load('items');

            return ['status' => 'OK', 'PurchaseOrder' => $order];

        }

    }

    public function PDF($subdomain, $id){

        $order = PurchaseOrder::where('id', '=', $id)
                ->with('vendor')
                ->with('branch')
                ->with('items')
                ->first();

        if(count($order) === 1){

            if(true){
                return $order->PDF()->stream();
            }else{
                $mainbranch = Branch::where('default', '=', '1')->first();
                return View::make('pdf.PurchaseOrder.view')
                    ->with('currency', '$')
                    ->with('mainbranch', $mainbranch)
                    ->with('order', $order);
            }

        } else {
            return $this->Error('invalid Purchase Order id', $id);
        }

    }

    public function Overview(){

        $PurchaseOrders = PurchaseOrder::all();

        $vendors = Vendor::all();

        return View::make('OS.PurchaseOrders.overview')
            ->with('vendors', $vendors)
            ->with('PurchaseOrders', $PurchaseOrders);


    }

    public function UpdateStatus(){

        $data = array(
            'id' => Input::get('id'),
            'status' => Input::get('status'),
            );

        $PO = PurchaseOrder::where('id', $data['id'])->first();

        if(count($PO) === 1){

            $PO->status = $data['status'];
            $PO->save();

            return ['status' => 'OK', 'newstatus' => $PO->getStatus()];
        }else{
            return ['status' => 'notfound'];
        }

    }

    public function Status($s, $id){

        $PurchaseOrder = PurchaseOrder::where('id', $id)->first();

        if(count($PurchaseOrder) === 1){

            if($PurchaseOrder->status === 1 or $PurchaseOrder->status === 2 or $PurchaseOrder->getStatus() === "Back Ordered"){
                return View::make('OS.PurchaseOrders.status')
                    ->with('PurchaseOrder', $PurchaseOrder);
            }else{
                return Response::make(view('errors.404'), 404);
            }
        }else{
            return Response::make(view('errors.404'), 404);
        }

    }

    public function MarkReceived(){

        $data = array(
            'id' => Input::get('id'),
            'table' => Input::get('table'),
        );

        $PO = PurchaseOrder::where('id', $data['id'])->first();

        if(count($PO) === 1){

            $valid = true;
            foreach($PO->items as $item){

                $received = $data['table'][$item->id];

                if($received > $item->UnitsLeft()){
                    $valid = false;
                }

            }

            if($valid){
                foreach($PO->items as $item){

                    $received = floatval($data['table'][$item->id]);

                    $item->received = $item->received + $received;
                    $item->save();

                    if($item->product_id != null){
                        if($item->product->trackstock === 1){
                            $item->product->stock = $item->product->stock + $received;
                            $item->product->save();
                        }
                    }

                }

                $PO->status = 4;
                $PO->save();

                return ['status' => 'OK'];
            }else{
                return ['status' => 'receivederror'];
            }
        }else{
            return ['status' => 'notfound'];
        }

    }

}


