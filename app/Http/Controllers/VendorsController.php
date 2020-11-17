<?php

namespace App\Http\Controllers;

use App\Helpers\OS\Address\AddressHelper;
use App\Helpers\OS\SettingHelper;
use App\Helpers\OS\Vendor\ContactHelper;
use App\Helpers\OS\Vendor\VendorHelper;
use App\Http\Controllers\Controller;
use App\Models\Check;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
#use Session;
use Illuminate\Validation\Rule;

use \App\Providers\EventLog;

use App\Models\Vendor;
use App\Models\VendorNote;
use App\Models\VendorContact;
use App\Models\Address;
use App\Models\CustomTables;

use DataTables;

class VendorsController extends Controller {
        
    #Generate Pages
    public function showSearch()
    {
        $vendors = Vendor::with('primarycontact')->get();
        $trashed = Vendor::onlyTrashed()->with('primarycontact')->get();
        
        return View::make('Vendors.search')
            ->with('vendors', $vendors)
            ->with('trashed', $trashed);
    }

    public function showMain(){

        $vendors = Vendor::with('primarycontact')->withTrashed()->with('address')->get();

        $checks = Check::where('printqueue', '1')->get();

        $unprintedchecks = 0.00;
        foreach($checks as $check){
            $unprintedchecks = $unprintedchecks + $check->amount;
        }

        return View::make('Vendors.main')
            ->with('unprintedchecks', $unprintedchecks)
            ->with('vendors', $vendors);

    }


    public function showAdd()
    {
        $address = Address::all();
        return View::make('Vendors.add')->with('address', $address);
    }
    
    public function showView($subdomain, $id, $tab = null){
        $selected_vendor = intval($id);
        if ($selected_vendor !== 0) {
           
            $vendor = Vendor::where('id', $selected_vendor)
                    ->with('address')
                    ->with('contacts')
                    ->with('primarycontact')
                    ->with('trashedcontacts')
                    ->with('notes1')
                    ->with('reports')
                    ->with('signing')
                    ->with('receipts')
                    ->with('checks')
                    ->with('files')
                    ->with('purchaseorders')
                    ->first();
            
            $tables = CustomTables::where('type', 'vendor')->get();
            
            return View::make('Vendors.view')
                ->with('vendor', $vendor)
                ->with('tables', $tables)
                ->with('tab', $tab);
            
        } else {
            return Redirect::to('/Vendor.Search');
        }
    }
    
    public function showEdit($subdomain, $id)
    {
        $selected_vendor = intval($id);
        if ($selected_vendor !== 0) {
           
            $vendor = Vendor::where('id', $selected_vendor)
                ->withTrashed()
                ->with('address')
                ->with('primarycontact')   
                ->first();
            
            return View::make('Vendors.edit')
                ->with('vendor', $vendor);
            
        } else {
            return Response::make(view('errors.404'), 404);
        }
    }
    #Process Inputs
    public function DisableVendor()
    {
        $data = array(
            'VendorID' => Input::get('VendorID'),
            'Action' => Input::get('Action'),
        );
        
        $vendor = Vendor::where('id', $data['VendorID'])->withTrashed()->first();
        
        switch ($data['Action']) {
            case 'disable':
                $vendor->delete();
                EventLog::add('Vendor disabled ID:'.$vendor->id.' Name:'.$vendor->name);
                break;
            case 'enable':
                $vendor->restore();
                EventLog::add('Vendor restored ID:'.$vendor->id.' Name:'.$vendor->name);
                break;
        }

        return $vendor->id;
    }

    public function GetVendorJSON()
    {
        if (Auth::user()->hasPermission("vendor_permission") ){
            return DataTables(Vendor::leftjoin('address', 'vendors.address_id', '=', 'address.id')
                ->leftjoin('vendorcontacts', 'vendors.primarycontact_id', '=', 'vendorcontacts.id')
                ->withTrashed()
                ->select('vendors.*', 'address.number as number', 'address.address1 as address1', 'address.address2 as address2', 'address.city as city', 'address.state as state', 'address.zip as zip', 'vendorcontacts.firstname as firstname', 'vendorcontacts.lastname as lastname', 'vendorcontacts.email as email', 'vendorcontacts.officenumber as officenumber'))
                ->editColumn('id', function($vendor) {
                    return $vendor->id;
                })
                ->editColumn('name', function($vendor) {
                    if($vendor->category != "") {
                        $category = "({$vendor->category})";
                    }else{
                        $category = "";
                    }
                    return "{$vendor->getName()} {$category}";
                })
                ->editColumn('phone_number', function($vendor) {
                    if(is_null($vendor->primarycontact_id)) {
                        return "None";
                    }else{
                        return $vendor->primarycontact->Getprimaryphonenumber();
                    }
                })
                ->editColumn('phone_number_raw', function($vendor) {

                    if(is_null($vendor->primarycontact_id)) {
                        return "None";
                    }else{
                        return $vendor->primarycontact->GetprimaryphonenumberRAW();
                    }
                })
                ->editColumn('status', function($vendor) {
                    return $vendor->getDeleted();
                })
                ->toJson();
        }else{
            return Response::make(view('errors.404'), 404);
        }
    }
    
//    public function CreateNewVendor()
//    {
//        $vendordata = array(
//            'address_id' => Input::get('address_id'),
//        );
//        
//        if (Input::get('name') === ""){
//            $vendordata['name'] = null;
//            $validator = $this->ValidateVendorInputWithoutName($vendordata);
//        }else{
//            $vendordata['name'] = Input::get('name');
//            $validator = $this->ValidateVendorInputWithName($vendordata);
//        }
//        
//        if ($validator->fails()){
//            
//            return $validator->errors()->toArray();
//            
//        } else {
//            
//            $vendor = new Vendor;
//            $vendor->name = $vendordata['name'];
//            $vendor->address_id = $vendordata['address_id'];
//            $vendor->save();
//            
//            EventLog::add('New vendor created ID:'.$vendor->id.' Name:'.$vendor->name);
//            
//            return $vendor->id;
//
//        }
//    }
    public function CreateNewVendor()
    {
        $vendordata = array(
            'id' => Input::get('id'),
            'name' => Input::get('name'),
            'category' => Input::get('category'),
            'address_id' => Input::get('address_id'),
            '1099' => Input::get('1099'),
            'tax_id_number' => Input::get('tax_id_number'),
            'phonenumber' => Input::get('phonenumber'),
            'email' => Input::get('email'),
            'custom' => Input::get('custom'),
        );

        $contactdata = array(
            'id' => 0,
            'hascontact' => Input::get('hascontact'),
            'firstname' => Input::get('firstname'),
            'middlename' => Input::get('middlename'),
            'lastname' => Input::get('lastname'),
            'address_id' => null,
            'ssn' => Input::get('ssn'),
            'driverslicense' => Input::get('driverslicense'),
            'email' => Input::get('email'),
            'contacttype' => Input::get('contacttype'),
            'ref1' => Input::get('ref1'),
            'ref2' => Input::get('ref2'),
            'ref3' => Input::get('ref3'),
            'comments' => Input::get('comments'),
            'officenumber' => Input::get('officenumber'),
            'mobilenumber' => Input::get('mobilenumber'),
            'homenumber' => Input::get('homenumber'),
            'primaryphonenumber' => Input::get('primaryphonenumber'),
        );

        $addressdata = array(
            'number' => Input::get('number'),
            'address1' => Input::get('address1'),
            'address2' => Input::get('address2'),
            'city' => Input::get('city'),
            'region' => Input::get('region'),
            'state' => Input::get('state'),
            'zip' => Input::get('zip'),
            'type' => "",
        );

        SettingHelper::SetSetting('vendor-custom-label', Input::get('customfieldlabel'));

        $addressvalidator = AddressHelper::ValidateAddressInput($addressdata);

        if($contactdata['hascontact'] === "1"){
            $vendorvalidator = VendorHelper::ValidateVendorInput($vendordata, false);
            $contactvalidator = ContactHelper::ValidateContactsInput($contactdata);
            $contactvalid =  $contactvalidator->fails();
        }else{
            $vendorvalidator = VendorHelper::ValidateVendorInput($vendordata, true);
            $contactvalid = false;
        }


        if ($vendorvalidator->fails() or $contactvalid or $addressvalidator->fails()){
            if($contactdata['hascontact'] === 1){
                $errors = array_merge ( $contactvalidator->errors()->toArray(), $vendorvalidator->errors()->toArray(), $addressvalidator->errors()->toArray());
            }else{
                $errors = array_merge ( $vendorvalidator->errors()->toArray(), $addressvalidator->errors()->toArray());
            }

            return ['status' => 'validation', 'errors' => $errors];
        } else {

            //Save Address
            if($addressdata['address1'] === "NOADDRESS"){
                $vendordata['address_id'] = null;
            }else{
                $address = AddressHelper::SaveAddress($addressdata);
                $vendordata['address_id'] = $address->id;
            }

            $vendor = VendorHelper::SaveVendorToDB($vendordata);

            if($contactdata['hascontact'] === "1"){
                $contactdata['vendor_id'] = $vendor->id;
                $contact = ContactHelper::SaveContactToDB($contactdata);
                $vendor->primarycontact_id = $contact->id;
                $vendor->save();
            }

            return ['status' => 'OK', 'id' => $vendor->id];

        }

    }
    
    public function UpdateVendor()
    {
        $vendordata = array(
            'id' => Input::get('id'),
            'name' => Input::get('name'),
            'category' => Input::get('category'),
            '1099' => Input::get('1099'),
            'tax_id_number' => Input::get('tax_id_number'),
            'phonenumber' => Input::get('phonenumber'),
            'email' => Input::get('email'),
            'custom' => Input::get('custom'),
        );

        $addressdata = array(
            'number' => Input::get('number'),
            'address1' => Input::get('address1'),
            'address2' => Input::get('address2'),
            'city' => Input::get('city'),
            'region' => Input::get('region'),
            'state' => Input::get('state'),
            'zip' => Input::get('zip'),
            'type' => "",
        );


        $vendor = Vendor::where('id', $vendordata['id'])->withTrashed()->first();

        if(count($vendor) === 1){

            if($addressdata['address1'] === "NOADDRESS"){
                $vendor['address_id'] = null;
            }else{
                $addressvalidator = AddressHelper::ValidateAddressInput($addressdata);
                if ($addressvalidator->fails()){
                    return ['status' => 'validation', 'errors' => $addressvalidator->errors()->toArray()];
                } else {
                    $address = AddressHelper::SaveAddress($addressdata);
                    $vendor['address_id'] = $address->id;
                }
            }

            if($vendordata['name'] === ""){
                if($vendor->primarycontact_id === null){
                    return ['status' => 'namerequired'];
                }else{
                    $vendor->name = null;
                }
            }else{
                $vendor->name = $vendordata['name'];
            }

            $vendor->track_1099 = $vendordata['1099'];
            if($vendordata['1099'] === "1"){
                if($vendordata['tax_id_number'] === ""){
                    return ['status' => 'taxidrequired'];
                }else{
                    $vendor->tax_id_number = $vendordata['tax_id_number'];
                }
            }

            $vendor->category = $vendordata['category'];

            $vendor->phonenumber = $vendordata['phonenumber'];
            $vendor->email = $vendordata['email'];
            $vendor->custom = $vendordata['custom'];

            $vendor->save();

            EventLog::add('vendor edited ID:'.$vendor->id.' Name:'.$vendor->name);

            return ['status' => 'OK', 'id' => $vendor->id];
        }else{
            return ['status' => 'notfound'];
        }

    }
    
    public function GetVendorInput()
    {       
        $data = array(
            'name' => Input::get('name'),
            'category' => Input::get('category')
        );
        
        
        if(Input::get('address_id') === "0" || Input::get('address_id') === ""){
            $data['address_id'] = null;
        }else{
            $data['address_id'] = Input::get('address_id');
        }
        
        if(null !== Input::get('id')){
            $data['id'] = Input::get('id');
        }
        
        return $data;
    }
    
    public function GetContactInput2()
    {
        $contact = Input::get('contact');
        
        return $contact;
    }
    
    public function ValidateVendorInput($data)
    {

        $rules = array(
            //'name' => 'required',
            'notes' => '',
        );
        
        if($data['address_id'] !== null){
            $rules['address_id'] = 'exists:address,id';
        }
        
        if(isset($data['id'])){
            $rules['id'] = 'required|exists:vendors,id';
        }
        

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    }
    
    public function ValidateNewVendorInput($data)
    {

        $rules = array(
            'notes' => '',
        );
        
        if($data['address_id'] !== null){
            $rules['address_id'] = 'exists:address,id';
        }
        
        if($data['name'] !== null){
            $rules['name'] = 'required|unique:vendors,name';
        }
        
        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    }
    
        
    public function SaveVendorToDB($data)
    {
        $vendor = new Vendor;
        $vendor->name = $data['name'];
        $vendor->address_id = $data['address_id'];
        $vendor->save();

        EventLog::add('New vendor created ID:'.$vendor->id.' Name:'.$vendor->name);

        return $vendor;
    }
    
    public function UpdateVendorInDB($data)
    {
        $vendor = Vendor::where('id', $data['id'])->withTrashed()->first();
        if($data['name'] === ""){
            $vendor->name = null;
        }else{
            $vendor->name = $data['name'];
        }
        $vendor->category = $data['category'];
        $vendor->address_id = $data['address_id'];
        $vendor->save();

        EventLog::add('Vendor edited ID:'.$vendor->id.' Name:'.$vendor->name);

        return $vendor;
    }

    
//    public function ValidateVendorInputWithName($data){
//        $rules = array(
//            'name' => 'required|unique:vendors,name', 
//            'address_id' => 'exists:address,id', 
//            'notes' => '',
//        );
//
//        // run the validation rules on the inputs from the form
//        $validator = Validator::make($data, $rules);
//        
//        Return $validator; // send back all errors
//
//    }
//    public function ValidateVendorInputWithoutName($data){
//        $rules = array(
//            'address_id' => 'exists:address,id', 
//            'notes' => '',
//        );
//
//        // run the validation rules on the inputs from the form
//        $validator = Validator::make($data, $rules);
//        
//        Return $validator; // send back all errors
//    }
    
     public function AddNote()
    {   
        
        $notedata = array(
            'vendorid' => Input::get('vendorid'),
            'note' => Input::get('note'),
        );
                
        $validator = $this->ValidateNoteInput($notedata);
        
        
        if ($validator->fails()){
            
            return $validator->errors()->toArray();
            
        } else {
            
            $note_data = new VendorNote;
            $note_data->user_id = Auth::id();
            $note_data->vendor_id = $notedata['vendorid'];
            $note_data->note = $notedata['note'];
            $note_data->save();
            
            EventLog::add('Note added to vendor ID:'.$note_data->vendor_id);
            
            #return $note_data->id;
            return "success";

        }

    }
    
    public function ValidateNoteInput($data)
    {
        $rules = array(
            'vendor_id' => 'exists:vendors,id', 
            'note' => 'required',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);
        
        return $validator;
    }
    

        
    public function ChangePrimary()
    {
        $data = array(
            'id' => Input::get('vendor_id'), 
            'primarycontact_id' => Input::get('contact_id'), 
        );
    
        $validator = $this->ValidatePrimaryContact($data);
        
        if ($validator->fails()){
            
            return $validator->errors()->toArray();
            
        } else {
            
            $result = $this->ChangePrimaryInDB($data["id"], $data["primarycontact_id"] );
            return $result;

        }
    }
    
    public function ValidatePrimaryContact($data)
    {
        $rules = array(
            'id' => 'exists:vendors,id', 
            'primarycontact_id' => 'exists:vendorcontacts,id', 

        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    }
    
    public function ChangePrimaryInDB($clientID, $contactID)
    {
    
        $client = Vendor::find($clientID);
        $client->primarycontact_id = $contactID;
        $client->save();
            
        return "success";

    }
    
    
    
    
    public function showContact($subdomain, $id)
    {


        $contact = VendorContact::where('id', $id)
                ->withTrashed()
            ->with('address')
            ->first();

        if(count($contact) === 1){

            $addresss = Address::all();

            $vendor = $contact->vendor;

            return View::make('Vendors.contact')
                ->with('addresss', $addresss)
                ->with('vendor', $vendor)
                ->with('contact', $contact);
            
        } else {
            return Response::make(view('errors.404'), 404);
        }
    }
    

    
    #Route::get('Clients/Contact/New/{id}', array('uses' => 'ClientsController@newContact'));
    public function newContact($subdomain, $id)
    {
        $vendor = Vendor::where('id' , '=', $id)->first();

        $addresss = Address::all();

        if(count($vendor) === 1){

            $addresss = Address::all();

            return View::make('Vendors.contact')
                ->with('addresss', $addresss)
                ->with('vendor', $vendor)
                ->with('vendor_id', $vendor->id);
            
        } else {
            return Response::make(view('errors.404'), 404);
        }
    }
    
    public function SaveContact()
    {
        
        $data = $this->GetContactInput();
        
        $validator = ContactHelper::ValidateContactsInput($data);
        
        if ($validator->fails()){
            
            return ['status' => 'validation', 'errors' => $validator->errors()->toArray()];
            
        } else {
            
            $contact = ContactHelper::SaveContactToDB($data);
            
            return ['status' => 'OK', 'contactid' => $contact->id];
        }   
    }
    
    public function GetContactInput()
    {
        $data = array(
            'id' => Input::get('id'),
            'firstname' => Input::get('firstname'),
            'middlename' => Input::get('middlename'),
            'lastname' => Input::get('lastname'),
            'address_id' => Input::get('address_id'),
            'ssn' => Input::get('ssn'),
            'driverslicense' => Input::get('driverslicense'),
            'email' => Input::get('email'),
            'contacttype' => Input::get('contacttype'),
            'vendor_id' => Input::get('vendor_id'),
            'officenumber' => Input::get('officenumber'),
            'mobilenumber' => Input::get('mobilenumber'),
            'homenumber' => Input::get('homenumber'),
            'primaryphonenumber' => Input::get('primaryphonenumber'),
        );
        
        return $data;
    }

    
    public function DisableContact()
    {
        $data = array(
            'ContactID' => Input::get('ContactID'),
            'Action' => Input::get('Action'),
        );
        
        $contact = VendorContact::where('id', $data['ContactID'])->withTrashed()->first();
        
        switch ($data['Action']) {
            case 'disable':
                $contact->delete();
                EventLog::add('Vendor Contact disabled ID:'.$contact->id.' Name:'.$contact->name);
                break;
            case 'enable':
                $contact->restore();
                EventLog::add('Vendor Contact restored ID:'.$contact->id.' Name:'.$contact->name);
                break;
        }

        return $contact->id;
    }
}