<?php

namespace App\Http\Controllers;


use App\Helpers\AccountHelper;
use App\Helpers\OS\Address\AddressHelper;
use App\Helpers\OS\Client\ContactHelper;
use App\Http\Controllers\Controller;
use App\Models\Management\TaskQueue;
use App\Models\OS\BillableHour;
use App\Models\OS\Inventory\ServiceLibrary;
use App\Models\ProductLibrary;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

use \App\Providers\EventLog;
use App\Helpers\OS\Client\ClientHelper;

use App\Models\Client;
use App\Models\ClientContact;
use App\Models\Address;
use App\Models\ClientNote;
use App\Models\CustomTables;
use App\Models\Management\Account;

use App\Models\Management\Broker;

use DataTables;

class ClientsController extends Controller {
    #return $this->Error('', '');
    public function Error($error, $debug)
    {
        if (is_array($error)){
            return View::make('error')
                ->with('errors', $error)
                ->with('debug', $debug);
        }else{
            return View::make('error')
                ->with('error', $error)
                ->with('debug', $debug);
        }
    }

    public function showMainProspects(){

        $clients = Client::with('primarycontact')->withTrashed()->get();

        $recevables = 0.00;
        $quotevalue = 0.00;

        foreach($clients as $client){
            if($client->getStatus() === "Client"){
                $recevables += $client->getBalence(false);
            }else{
                $quotevalue += $client->getOpenQuoteValue(false);
            }
        }

        return View::make('Clients.main')
            ->with('recevables', $recevables)
            ->with('quotevalue', $quotevalue)
            ->with('clients', $clients)
            ->with('mode', 'prospects');

    }

    public function showMain(){

        $clients = Client::with('primarycontact')->withTrashed()->get();

        $recevables = 0.00;
        $quotevalue = 0.00;

        foreach($clients as $client){
            if($client->getStatus() === "Client"){
                $recevables += $client->getBalence(false);
            }else{
                $quotevalue += $client->getOpenQuoteValue(false);
            }
        }

        return View::make('Clients.main')
            ->with('recevables', $recevables)
            ->with('quotevalue', $quotevalue)
            ->with('clients', $clients)
            ->with('mode', 'clients');

    }

    public function ResendWelcomeEmail($subdomain, $id, $email = null)
    {
        if($subdomain === "local" || $subdomain === "lls"){
            $account = Account::where('id', '=', $id)->first();

            if($email === null){
                $account->SendWelcomeEmailInstall();
            }else{
                $account->SendWelcomeEmail($email);
            }

            return "done";

        }else{
            return Response::make(view('errors.404'), 404);
        }
    }

    public function GetClientJSON()
    {
        if (Auth::user()->hasPermission("client_permission") ){
            return DataTables(Client::leftjoin('address', 'clients.address_id', '=', 'address.id')
                ->leftjoin('clientcontacts', 'clients.primarycontact_id', '=', 'clientcontacts.id')
                ->where('existingclient', '=', '1')
                ->withTrashed()
                ->select('clients.id','clients.name','clients.category', 'clients.created_at', 'clients.phonenumber', 'clients.email', 'clients.primarycontact_id', 'address.number', 'address.address1', 'address.address2', 'address.city', 'address.state', 'address.zip', 'clientcontacts.firstname', 'clientcontacts.lastname', 'clientcontacts.email', 'clientcontacts.officenumber'))
                ->editColumn('id', function($client) {
                    return $client->id;
                })
                ->editColumn('name', function($client) {

                    if($client->category != "") {
                        $category = "({$client->category})";
                    }else{
                        $category = "";
                    }
                    return "{$client->getName()} {$category} {$client->getNameAddon()}";
                })
                ->editColumn('phone_number', function($client) {

                    if(is_null($client->primarycontact_id)) {
                        return "None";
                    }else{
                        return $client->primarycontact->Getprimaryphonenumber();
                    }
                })
                ->editColumn('phone_number_raw', function($client) {

                    if(is_null($client->primarycontact_id)) {
                        return "None";
                    }else{
                        return $client->primarycontact->GetprimaryphonenumberRAW();
                    }
                })
                ->editColumn('acctive_date', function($client) {
                    if($client->getAccount() != null) {
                        return $client->getAccount()->activeLastNoteDate;
                    }else{
                        return "";
                    }
                })
                ->editColumn('last_accessed', function($client) {

                    if($client->accessed_at === null){
                        return "Never";
                    }else{
                        return $client->accessed_at->toDateString();
                    }
                })
                ->editColumn('last_note_date', function($client) {
                    return $client->LastNoteDate();
                })
                ->editColumn('last_note', function($client) {
                    return $client->LastNoteDate();
                })
                ->editColumn('LastNoteContent', function($client) {
                    return $client->LastNoteContent();
                })
                ->editColumn('LastNote', function($client) {
                    return $client->LastNoteGetNote();
                })
                ->editColumn('type', function($client) {
                    return $client->getStatus();
                })
                ->editColumn('status', function($client) {
                    return $client->getDeleted();
                })
                ->toJson();
        }else{
            return Response::make(view('errors.404'), 404);
        }
    }

    public function GetProspectJSON()
    {
        if (Auth::user()->hasPermission("client_permission") ){
            return DataTables(Client::leftjoin('address', 'clients.address_id', '=', 'address.id')
                ->leftjoin('clientcontacts', 'clients.primarycontact_id', '=', 'clientcontacts.id')
                ->where('existingclient', '=', '0')
                ->withTrashed()
                ->select('clients.id','clients.name','clients.category', 'clients.created_at', 'clients.phonenumber', 'clients.email', 'clients.primarycontact_id', 'address.number', 'address.address1', 'address.address2', 'address.city', 'address.state', 'address.zip', 'clientcontacts.firstname', 'clientcontacts.lastname', 'clientcontacts.email', 'clientcontacts.officenumber'))
                ->editColumn('id', function($prospect) {
                    return $prospect->id;
                })
                ->editColumn('name', function($prospect) {
                    if($prospect->category != "") {
                        $category = "({$prospect->category})";
                    }else{
                        $category = "";
                    }
                    return "{$prospect->getName()} {$category} {$prospect->getNameAddon()}";
                })
                ->editColumn('phone_number', function($prospect) {

                    if(is_null($prospect->primarycontact_id)) {
                        return "None";
                    }else{
                        return $prospect->primarycontact->Getprimaryphonenumber();
                    }
                })
                ->editColumn('phone_number_raw', function($prospect) {

                    if(is_null($prospect->primarycontact_id)) {
                        return "None";
                    }else{
                        return $prospect->primarycontact->GetprimaryphonenumberRAW();
                    }
                })
                ->editColumn('acctive_date', function($prospect) {
                    if($prospect->getAccount() != null) {
                        return $prospect->getAccount()->activeLastNoteDate;
                    }else{
                        return "";
                    }
                })
                ->editColumn('last_accessed', function($prospect) {

                    if($prospect->accessed_at === null){
                        return "Never";
                    }else{
                        return $prospect->accessed_at->toDateString();
                    }
                })
                ->editColumn('last_note_date', function($prospect) {
                    return $prospect->LastNoteDate();
                })
                ->editColumn('last_note', function($prospect) {
                    return $prospect->LastNoteDate();
                })
                ->editColumn('LastNoteContent', function($prospect) {
                    return $prospect->LastNoteContent();
                })
                ->editColumn('LastNote', function($prospect) {
                    return $prospect->LastNoteGetNote();
                })
                ->editColumn('type', function($prospect) {
                    return $prospect->getStatus();
                })
                ->editColumn('status', function($prospect) {
                    return $prospect->getDeleted();
                })
                ->toJson();

        }else{
            return Response::make(view('errors.404'), 404);
        }
    }



    public function SaveBillableHours(){

        $data = array(
            'client_id' => Input::get('client_id'),
            'user_id' => Auth::user()->id,
            'rate' => Input::get('rate'),
            'hours' => Input::get('hours'),
            'comment' => Input::get('comment'),
        );

        $hours = New BillableHour;
        $hours->client_id = $data['client_id'];
        $hours->user_id = $data['user_id'];
        $hours->rate = $data['rate'];
        $hours->hours = $data['hours'];
        $hours->comment = $data['comment'];
        $hours->save();

        return  $hours->id . "," . $hours->user->getName() . ",$" . number_format($hours->rate , 2) . "," . floatval($hours->hours) . ",$" . number_format($hours->Total() , 2) . "," . $hours->comment . "," . $hours->created_at;

    }

    #get /Clients/Add
    public function showAdd()
    {
        //Check if SOLO client limit reached
        $account = app()->make('account');
        $address = Address::all();
        $clientcount = Client::withTrashed()->count();

        if ($account->plan_name != '47FREE') {
        {
            return View::make('Clients.add')->with('address', $address);
        }
        }else {
            if ($clientcount >= 47) {
                return View::make('Clients.limit');
            }else{
                return View::make('Clients.add')->with('address', $address);
            }
        }

    }
    
    #get /Clients/View/{id}
    public function showView( Request $request ,$subdomain, $id, $tab = null)
    {
        $selected_client = intval($id);
        $type = '';
        $child = '';
        if ($request->type != '') {
            $type = $request->type;
        }
        if ($request->child != '') {
            $child = $request->child;
        }
        if ($selected_client !== 0) {
           
            $client = Client::where('id', $selected_client)
                ->with( 'address',
                        'contacts',
                        'trashedcontacts',
                        'primarycontact',
                        'receipts',
                        'checks',
                        'reports',
                        'signing',
                        'quotes',
                        'notes1',
                        'billablehours',
                        'files')
                        ->withTrashed()
                        ->first();

            $client->accessed_at = Carbon::now();
            $client->save();

            $tables = CustomTables::where('type', 'client')->get();

            if($subdomain === "lls"){
                $llsclientinfo = Account::where('client_id' , $client->id)->first();
                if (count($llsclientinfo) === 1) {
                    if($llsclientinfo->installstage > 5){
                        if($llsclientinfo->subdomain != "disabled") {

                            $clientusers = $llsclientinfo->GetLogins();

                            $usercount = 0;
                            foreach ($clientusers as $user) {
                                if ($user->canlogin = 1) {
                                    $usercount = $usercount + 1;
                                }
                            }

                            return View::make('Clients.view')
                                ->with('products', ProductLibrary::where('companyuse', 0)->get())
                                ->with('services', ServiceLibrary::all())
                                ->with('clientusers', $clientusers)
                                ->with('usercount', $usercount)
                                ->with('llsclientinfo', $llsclientinfo)
                                ->with('client', $client)
                                ->with('tables', $tables)
                                ->with('type', $type)
                                ->with('child', $child)
                                ->with('tab', $tab);
                        }
                    }
                }
            }

            return View::make('Clients.view')
                ->with('products', ProductLibrary::where('companyuse', 0)->get())
                ->with('services', ServiceLibrary::all())
                ->with('client', $client)
                ->with('tables', $tables)
                ->with('type', $type)
                ->with('child', $child)
                ->with('tab', $tab);

        } else {
            return $this->Error('invalid clientid', $selected_client);
        }
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = clients::select('*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return view('Clientstab');
    }
    
    public function showEdit($subdomain, $id)
    {
        $selected_client = intval($id);
        if ($selected_client !== 0) {
           
            $client = Client::where('id', $selected_client)
                ->withTrashed()
                ->with('address')
                ->with('primarycontact')   
                ->first();
            
            return View::make('Clients.edit')
                ->with('client', $client);
            
        } else {
            return $this->Error('invalid clientid', $selected_client);
        }
    }
    
    public function showInvoice()
    {
        return View::make('Clients.invoice');
    }

    public function DisableClient()
    {
        $data = array(
            'ClientID' => Input::get('ClientID'),
            'Action' => Input::get('Action'),
        );
        
        $client = Client::where('id', $data['ClientID'])->withTrashed()->first();
        
        switch ($data['Action']) {
            case 'disable':
                $client->delete();
                EventLog::add('Client disabled ID:'.$client->id.' Name:'.$client->name);
                break;
            case 'enable':
                $client->restore();
                EventLog::add('Client restored ID:'.$client->id.' Name:'.$client->name);
                break;
        }

        return $client->id;
    }
        
    #Process Inputs
    #post /Clients/AddClient
    public function CreateNewClient()
    {

        $clientdata = array(
            'id' => Input::get('id'),
            'name' => Input::get('name'),
            'email' => Input::get('email'),
            'phonenumber' => Input::get('phonenumber'),
            'category' => Input::get('category'),
            'address_id' => Input::get('address_id'),
            'existingclient' => Input::get('existingclient'),
        );

        $contactdata = array(
            'id' => Input::get('id'),
            'firstname' => Input::get('firstname'),
            'middlename' => Input::get('middlename'),
            'lastname' => Input::get('lastname'),
            'address_id' => null,
            'ssn' => Input::get('ssn'),
            'driverslicense' => Input::get('driverslicense'),
            'contactemail' => Input::get('contactemail'),
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

        $addressvalidator = AddressHelper::ValidateAddressInput($addressdata);
        $clientvalidator = ClientHelper::ValidateClientInput($clientdata);
        $contactvalidator = ContactHelper::ValidateContactsInput($contactdata);
        
        if ($clientvalidator->fails() or $contactvalidator->fails() or $addressvalidator->fails()){
            return ['status' => 'validation', 'errors' => array_merge ( $contactvalidator->errors()->toArray(), $clientvalidator->errors()->toArray(), $addressvalidator->errors()->toArray())];
        } else {

            //LLS Broker creation stuff
            if(app()->make('account')->subdomain === "lls" or app()->make('account')->subdomain === "local" ){
                //create broker
                if(Input::get('broker') === "1"){
                    if($clientdata['name'] === ""){
                        return ['status' => 'clientname'];
                    }else{

                        $data['subdomain'] = AccountHelper::seoUrl($clientdata['name']);
                        $data['UserPassword'] = AccountHelper::GeneratePassword();
                        $data['DBPassword'] = AccountHelper::GeneratePassword();

                        $data['version'] = "BROKER";
                        $data['number_of_users'] = 1000;
                        $data['plan_name'] = "BROKER";
                        $data['subscription_id'] = null;
                        $data['transaction_id'] = "";
                        $data['token'] = "";

                        $data['branch'] = $addressdata;

                        $data['email'] = $contactdata['email'];
                        $data['firstname'] = $contactdata['firstname'];
                        $data['lastname'] = $contactdata['lastname'];

                        $data['active'] = null;

                        if($data['subdomain'] === "error") {
                            return ['status' => 'subdomainerror'];
                        }else{

                            $account = AccountHelper::AddToAccountTable($data['subdomain'], $data['subdomain'], $data['subdomain'], $data['DBPassword'], $data);

                            $broker = new Broker;
                            $broker->account_id = $account->id;
                            $broker->save();

                            $newtask = new Taskqueue;
                            $newtask->jobname = ["Provision-CreateDB"];
                            $newtask->account_id = $account->id;
                            $newtask->save();

                        }
                    }
                }
            }

            //Save Address
            if($addressdata['address1'] === "NOADDRESS"){
                $clientdata['address_id'] = null;
            }else{
                $address = AddressHelper::SaveAddress($addressdata);
                $clientdata['address_id'] = $address->id;
            }

            //Save Client
            $client = ClientHelper::SaveClientToDB($clientdata);
            $contactdata['client_id'] = $client->id;

            //Save Contact
            if($contactdata['firstname'] != "NOCONTACT"){
                $contact = ContactHelper::SaveContactToDB($contactdata);
                $clientdata['primarycontact_id'] = $contact->id;
                $client->save();
            }

            if(app()->make('account')->subdomain === "lls" or app()->make('account')->subdomain === "local" ){
                if(Input::get('broker') === "1") {

                    $client->category = "BROKER";
                    $client->save();

                    $account->client_id = $client->id;
                    $account->save();
                }
            }

            return ['status' => 'OK', 'clientid' => $client->id];
        }
    }

    public function UpdateClient()
    {
        $clientdata = array(
            'id' => Input::get('id'),
            'name' => Input::get('name'),
            //'address_id' => Input::get('address_id'),
            'existingclient' => Input::get('existingclient'),
            'category' => Input::get('category'),
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

        $addressvalidator = AddressHelper::ValidateAddressInput($addressdata);
        $clientvalidator = ClientHelper::ValidateClientInput($clientdata);
        
        if ($clientvalidator->fails()) {
            return ['status' => 'Client validation', 'errors' =>  $clientvalidator->errors()->toArray()];
        }else if ($addressvalidator->fails()) {
            return ['status' => 'Address validation', 'errors' => $addressvalidator->errors()->toArray()];
            
        } else {

            $client = Client::where('id', $clientdata['id'])->withTrashed()->first();
            if($clientdata['name'] === ""){
                $client->name = null;
            }else{
                $client->name = $clientdata['name'];
            }

            $client->category = $clientdata['category'];

            if($addressdata['address1'] === "NOADDRESS"){
                $client['address_id'] = null;
            }else{
                $address = AddressHelper::SaveAddress($addressdata);
                $client['address_id'] = $address->id;
            }

            //$client->address_id = $data['address_id'];
            $client->existingclient = $clientdata['existingclient'];
            $client->save();

            EventLog::add('client edited ID:'.$client->id.' Name:'.$client->name);
            
            return $client->id;
        }
    }

    
    #post /Clients/AddNote
    public function AddNote()
    {   
        
        $notedata = array(
            'clientid' => Input::get('clientid'),
            'note' => Input::get('note'),
        );
                
        $validator = $this->ValidateNoteInput($notedata);
        
        
        if ($validator->fails()){
            
            return ['status' => 'validation', 'errors' =>  $validator->errors()->toArray()];
            
        } else {
            
            $note_data = new ClientNote;
            $note_data->user_id = Auth::id();
            $note_data->client_id = $notedata['clientid'];
            $note_data->note = $notedata['note'];
            $note_data->save();
            
            EventLog::add('Note added to client ID:'.$note_data->client_id);

            return ['status' => 'OK'];

        }

    }
    
    public function ValidateNoteInput($data)
    {
        $rules = array(
            'client_id' => 'exists:clients,id', 
            'note' => 'required',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);
        
        return $validator;
    }
    
    #Route::post('Client/Contact/ChangePrimary', array('uses' => 'ClientsController@ChangePrimary'));
    public function ChangePrimary()
    {
        $data = array(
            'id' => Input::get('client_id'), 
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
            'id' => 'exists:clients,id', 
            'primarycontact_id' => 'exists:clientcontacts,id', 

        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    }
    
    public function ChangePrimaryInDB($clientID, $contactID)
    {
    
        $client = Client::find($clientID);
        $client->primarycontact_id = $contactID;
        $client->save();
            
        return "success";

    }

    public function UpdateDetails()
    {
        $data = array(
            'id' => Input::get('id'),
            'date_of_introduction' => Input::get('date_of_introduction'),            
            'current_solution' => Input::get('current_solution'),
            'budget' => Input::get('budget'),
            'decision_maker' => Input::get('decision_maker'),
            'referral_source' => Input::get('referral_source'),
            'assigned_to' => Input::get('assigned_to'),
            'problem_pain' => Input::get('problem_pain'),
            'resistance_to_change' => Input::get('resistance_to_change'),
            'priorities' => Input::get('priorities'),
            'comments' => Input::get('comments'),
            'customfieldlabel' => Input::get('customfieldlabel'),
            'customfieldtext' => Input::get('customfieldtext'),
            'customfieldlabel2' => Input::get('customfieldlabel2'),
            'customfieldtext2' => Input::get('customfieldtext2'),
            'followupdate' => Input::get('followupdate'),
            'mainnumber' => Input::get('mainnumber'),
            'email' => Input::get('mainemail'),
        );
        
        if($data['assigned_to'] === "none"){
            $data['assigned_to'] = null;
        }
        
        $validator = ClientHelper::ValidateDetailsInput($data);
        
        if ($validator->fails()){
            return $validator->errors()->toArray();
        } else {
            return ClientHelper::UpdateDetails($data);
        }
    }    
    
    #Contacts related functions
    #
    #
    #
    #
    #
    #
    #
    #
    #
    #
    #
    #Route::get('Clients/Contact/{id}', array('uses' => 'ClientsController@showContact'));
    public function showContact($subdomain, $id)
    {
        $contact = ClientContact::where('id', $id)
            ->withTrashed()
            ->with('address')
            ->first();

        if(count($contact) === 1){

            $client = Client::where('id' , $contact->client_id)->first();

            $addresss = Address::all();
            
            return View::make('Clients.contact')
                ->with('addresss', $addresss)
                ->with('client', $client)     
                ->with('contact', $contact);
            
        } else {
            return Response::make(view('errors.404'), 404);
        }
    }
    
    #Route::get('Clients/Contact/New/{id}', array('uses' => 'ClientsController@newContact'));
    public function newContact($subdomain, $id)
    {

        $client = Client::where('id' , $id)->first();

        if(count($client) === 1){

            $addresss = Address::all();

            return View::make('Clients.contact')
                ->with('addresss', $addresss)
                ->with('client', $client)
                ->with('client_id', $client->id);
            
        } else {
            return Response::make(view('errors.404'), 404);
        }
    }
    
    #Route::post('Client/Contact/Save', array('uses' => 'ClientsController@SaveContact'));
    public function SaveContact()
    {
        
        $data = $this->GetContactInput();
        
        $validator = ContactHelper::ValidateContactsInput($data);
        
        if ($validator->fails()){
            
            return $validator->errors()->toArray();
            
        } else {
            
            $contact = ContactHelper::SaveContactToDB($data);
            
            return $contact->id;
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
            'contactemail' => Input::get('contactemail'),
            'contacttype' => Input::get('contacttype'),
            'client_id' => Input::get('client_id'),
            'ref1' => Input::get('ref1'),
            'ref2' => Input::get('ref2'),
            'ref3' => Input::get('ref3'),
            'comments' => Input::get('comments'),
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
        
        $contact = ClientContact::where('id', $data['ContactID'])->withTrashed()->first();
        
        switch ($data['Action']) {
            case 'disable':
                $contact->delete();
                EventLog::add('Client Contact disabled ID:'.$contact->id.' Name:'.$contact->name);
                break;
            case 'enable':
                $contact->restore();
                EventLog::add('Client Contact restored ID:'.$contact->id.' Name:'.$contact->name);
                break;
        }

        return $contact->id;
    }

    #Generate Pages
    #Get /Clients/Search
    /**
    public function showSearch()
    {
    $clients = Client::with('primarycontact')->get();
    $trashed = Client::onlyTrashed()->with('primarycontact')->get();

    return View::make('Clients.search')
    ->with('clients', $clients)
    ->with('trashed', $trashed);
    }
     * */


}