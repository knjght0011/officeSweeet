<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
#use Illuminate\Support\Facades\Auth;
use App\Models\OS\FileStore;
use App\Models\OS\Training\TrainingModule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use \App\Providers\EventLog;

use App\Helpers\BranchHelper;
use App\Helpers\OS\SettingHelper;
use App\Helpers\TransnationalHelper;

use App\Models\User;
use App\Models\Setting;
use App\Models\Branch;
use App\Models\TemplateGroup;
use App\Models\SchedulerEvents;
use App\Models\ProductLibrary;
use App\Models\ExpenseAccountCategory;
use App\Models\ExpenseAccountSubcategory;
use App\Models\CustomTables;
use App\Models\CheckSettings;
use App\Models\Management\Account;


use App\Mail\TransnationalApplication;


class ACPController extends Controller {


    public function showAdmin($subdomain)
    {
        return Redirect::to('/ACP/General/');
    }

    public function showAdminGeneral($subdomain, $subtab = null)
    {
        $templategroup = TemplateGroup::all();
        $events = SchedulerEvents::where("user_id", null)->get();
        $ExpenseAccountCategorys = ExpenseAccountCategory::whereIn('type',['income', 'expense', 'both'])->withTrashed()->with(['subcategories' => function($query){ $query->withTrashed(); }])->orderBy('category','ASC')->get();
        #$ExpenseAccountCategorys = ExpenseAccountCategory::withTrashed()->with(['subcategories' => function($query){ $query->withTrashed(); }])->orderBy('category','ASC')->get();
        $assetCategorys = ExpenseAccountCategory::whereIn('type', ['asset', 'liability', 'equity'])->withTrashed()->with(['subcategories' => function($query){ $query->withTrashed(); }])->orderBy('category','ASC')->get();
        #$assetcatagorys = array();

        $TempCheckSettings = CheckSettings::all();
        $CheckSettings = array();
        foreach($TempCheckSettings as $s){
            $CheckSettings[$s->Name] = $s->Value;
        }

        $TrainingModules = TrainingModule::all();

        return View::make('ACP.Tabs.General.main')
            ->with('assetCategorys', $assetCategorys)
            ->with('tab', 'General')
            ->with('subtab', $subtab)
            ->with('events',$events)
            ->with('templategroup',$templategroup)
            ->with('ExpenseAccountCategorys', $ExpenseAccountCategorys)
            ->with('CheckSettings', $CheckSettings)
            ->with('TrainingModules', $TrainingModules);

    }
    
    public function showAdminImportExport($subdomain, $subtab = null)
    {
        return View::make('ACP.Tabs.ImportExport.main')
            ->with('tab', 'ImportExport')
            ->with('subtab', $subtab);
    }

    public function showAdminCompanyInfo($subdomain, $subtab = null)
    {
        $branches = Branch::all();
        $branchestrashed = Branch::onlyTrashed()->get();

        return View::make('ACP.Tabs.CompanyInfo.main')
            ->with('tab', 'CompanyInfo')
            ->with('subtab', $subtab)
            ->with('branches', $branches)
            ->with('branchestrashed', $branchestrashed);

    }

    public function showAdminCustomTabs($subdomain, $subtab = null)
    {
        $tables = CustomTables::withTrashed()->get();

        return View::make('ACP.Tabs.CustomTabs.main')
            ->with('tab', 'CustomTabs')
            ->with('subtab', $subtab)
            ->with('tables', $tables);

    }

    public function showMessages($subdomain, $subtab = null)
    {

        return View::make('ACP.Tabs.Messages.main')
            ->with('tab', 'Messages')
            ->with('subtab', $subtab);

    }

    public function showIntegration($subdomain, $subtab = null)
    {
        $branches = Branch::all();

        foreach($branches as $branch){
            if($branch->default !== null){
                $physicalbusinessaddress = $branch->number . " " . $branch->address1 . " " . $branch->address2 . " " . $branch->city . " " . $branch->region . " " . $branch->state . " " . $branch->zip;
                $businessphone = $branch->phonenumber;
            }
        }

        if(isset($physicalbusinessaddress)){
            return View::make('ACP.Tabs.Integration.main')
                ->with('tab', 'Integration')
                ->with('subtab', $subtab)
                ->with('physicalbusinessaddress', $physicalbusinessaddress)
                ->with('businessphone', $businessphone);
        }else{
            return View::make('ACP.Tabs.Integration.main')
                ->with('tab', 'Integration')
                ->with('subtab', $subtab);
        }

    }

    public function showAdminSubscription($subdomain, $subtab = null)
    {

        $account = app()->make('account');

        if (count($account) === 1) {

            $TNdata = $account->TNSubscription();

            if(count($TNdata) === 0){
                $date = "None";
                $lastbilledamount = "Unknown";
            }else{
                $date = Carbon::parse($TNdata[count($TNdata) - 1]['action']['date'])->toDateString();
                $lastbilledamount = "$" . $TNdata[count($TNdata) - 1]['action']['amount'];
            }

            if($account->subscription_id != null){
                $summeryarray = array(
                    'Licensed Users' => $account->licensedusers,
                    'Current Number of Users' => count(UserHelper::GetAllUsersCanLogin()),
                    'Number of times billed' => count($TNdata),
                    'Last time billed' => $date,
                    'Last billed amount' => $lastbilledamount,
                );
            }else{
                $summeryarray = array(
                    'Licensed Users' => $account->licensedusers,
                    'Current Number of Users' => count(UserHelper::GetAllUsersCanLogin()),
                    'Number of times billed' => 'No subscription',
                    'Last time billed' => 'No subscription',
                    'Last billed amount' => 'No subscription',
                );
            }


            return View::make('ACP.Tabs.Subscription.main')
                ->with('tab', 'Subscription')
                ->with('subtab', $subtab)
                ->with('account', $account)
                ->with('summeryarray', $summeryarray);

        }else{
            //should never get here but just incase something weird happens
            return "Error";
        }
    }

    public function BranchData()
    {   
        $branches = Branch::all();
        return $branches;
    }
            
    public function saveBranch()
    {
        $addressdata = array(
            'id' => Input::get('id'),            
            'number' => Input::get('number'),
            'address1' => Input::get('address1'),
            'address2' => Input::get('address2'),
            'city' => Input::get('city'),
            'region' => Input::get('region'),
            'state' => Input::get('state'),
            'zip' => Input::get('zip'),
            'phonenumber' => Input::get('phonenumber'),
            'faxnumber' => Input::get('faxnumber'), 
            'status' => Input::get('status'),
            'citytax' => Input::get('citytax')
        );
        
        $validator = BranchHelper::ValidateBranchInput($addressdata);

        if ($validator->fails()){
            
            return ['status' => 'validation', 'errors' => $validator->errors()->toArray()];
            
        } else {
            
            return ['status' => 'OK', 'data' => BranchHelper::SaveBranch($addressdata)];

        }

    }

    public function saveGeneralSettings()
    {
        $settings = Input::all();
        unset($settings["_token"]);

        foreach ($settings as $key => $value){
            SettingHelper::SetSetting($key, $value);
        }

        return "ok";
    }
    
    public function ImportExcelClients($ExcelFile)
    {
        if ($ExcelFile == null)
        {
            return "Error";
        }
        else
        { 
            return "ok";
        }
    }

    public function saveTemplateSubGroup()
    {
        $group = Input::get('group');
        $subgroup = Input::get('subgroup');
        
        $data = new TemplateGroup;
        $data->group = $group;
        $data->subgroup = $subgroup;
        $data->save();
        
        return $data->id;

    }

    public function deleteTemplateSubGroup()
    {
        $group = Input::get('group');
        $subgroup = Input::get('subgroup');
        
        $data = TemplateGroup::where('subgroup', $subgroup)
                            ->get();
        
        $deleted = false;
        foreach($data as $d){
            if($d->group == $group){
                $d->delete();
                $deleted = true;
            }
        }
        
        if ($deleted == true){
            return $subgroup;
        }else{
            return "fail";
        }
    }    
    
    public function saveEnvelope()
    {
        $data = array(
            'returntop' => Input::get('returntop'),
            'returnleft' => Input::get('returnleft'),
            'returnwidth' => Input::get('returnwidth'),
            'returnheight' => Input::get('returnheight'),
            'totop' => Input::get('totop'),
            'toleft' => Input::get('toleft'),
            'towidth' => Input::get('towidth'),
            'toheight' => Input::get('toheight'),
        );
        
        foreach ($data as $key => $value){
            
            $data = Setting::where('name', $key)->first();
            if(count($data) === 1)
            {
                $data->value = $value;
                $data->save();
            }else{
                $data = new Setting;
                $data->name = $key;
                $data->value = $value;
                $data->save();
            }

        }

        return "ok";

    }
    
    public function saveCheckSettings()
    {
        $data = array(
            'ToTop' => Input::get('ToTop'),
            'ToLeft' => Input::get('ToLeft'),
            'ToWidth' => Input::get('ToWidth'),
            'ToHeight' => Input::get('ToHeight'),
            'DateTop' => Input::get('DateTop'),
            'DateLeft' => Input::get('DateLeft'),
            'DateWidth' => Input::get('DateWidth'),
            'PayToTop' => Input::get('PayToTop'),
            'PayToLeft' => Input::get('PayToLeft'),
            'PayToWidth' => Input::get('PayToWidth'),
            'AmountNumTop' => Input::get('AmountNumTop'),
            'AmountNumLeft' => Input::get('AmountNumLeft'),
            'AmountNumWidth' => Input::get('AmountNumWidth'),
            'AmountTextTop' => Input::get('AmountTextTop'),
            'AmountTextLeft' => Input::get('AmountTextLeft'),
            'AmountTextWidth' => Input::get('AmountTextWidth'),
            'memoTop' => Input::get('memoTop'),
            'memoLeft' => Input::get('memoLeft'),
            'memoWidth' => Input::get('memoWidth'),
        );
        
        foreach ($data as $key => $value){
            
            $data = CheckSettings::where('name', $key)->first();
            if(count($data) === 1)
            {
                $data->value = $value;
                $data->save();
            }
        }
        return "ok";
    }
    
    public function saveSchedulerEvent()
    {
        $data = new SchedulerEvents;
        $data->eventname = Input::get('name');
        $data->save();
        
        return $data->id;
    }
    
    public function deleteSchedulerEvent()
    {
        $data = SchedulerEvents::where('eventname', Input::get('text'))->First();
        $data->delete();

        return $data->id;
    }
    
    public function SaveCatagory()
    {
        $data = array(
            'category' => Input::get('category'),
            'type' => Input::get('type'),
        );
        
        $rules = array(
            'category' => 'required|string|unique:expenseaccountcategories,category',
        );
        
        $validator = Validator::make($data, $rules);
        
        if ($validator->fails()){

            return $validator->errors()->toArray();

        } else {
            
            $category = new ExpenseAccountCategory;
            $category->category = $data['category'];
            $category->type = $data['type'];
            $category->save();

            EventLog::add('New expence catagory created, ID:'.$category->id.' Name:'.$category->category);

            return $category->id;
        }
        
    }

    public function ChangeCatagoryType()
    {
        $data = array(
            'id' => Input::get('id'),
            'type' => Input::get('type'),
        );

        $category = ExpenseAccountCategory::where('id', '=', $data['id'])->first();
        $category->type = $data['type'];
        $category->save();

        return $category->id;

    }

    public function UpdateMonthlyBudget()
    {
        $data = array(
            'id' => Input::get('id'),
            'MonthlyBudget' => Input::get('MonthlyBudget'),
        );

        $category = ExpenseAccountCategory::where('id', '=', $data['id'])->first();
        $category->MonthlyBudget = $data['MonthlyBudget'];
        $category->save();

        return $category->id;
    }
    
    public function SaveSubCatagory()
    {
        $data = array(
            'categoryid' => intval(Input::get('categoryid')),
            'subcategory' => Input::get('subcategory'),
        );
        
        $rules = array(
            'categoryid' => 'exists:expenseaccountcategories,id',
            'subcategory' => 'required|string',
        );
        
        $validator = Validator::make($data, $rules);
        
        if ($validator->fails()){

            return $validator->errors()->toArray();

        } else {
            
            $subcategory = new ExpenseAccountSubcategory;
            $subcategory->subcategory = $data['subcategory'];
            $subcategory->expenseaccountcategories_id = $data['categoryid'];
            $subcategory->save();

            EventLog::add('New expence subcatagory created, Catagory:'.$subcategory->expenseaccountcategories_id.' ID:'.$subcategory->id.' Name:'.$subcategory->category);

            return $subcategory->id;
        }   
    }

    public function SaveSubCatagorys()
    {
        $data = Input::all();

        $catagoryid = $data["catagoryid"];

        unset($data["_token"]);
        unset($data["catagoryid"]);

        foreach($data as $subcat => $id){
            if($id === "0"){
                $subcategory = new ExpenseAccountSubcategory;
                $subcategory->subcategory = $subcat;
                $subcategory->expenseaccountcategories_id = $catagoryid;
                $subcategory->save();
            }else{
                $subcategory = ExpenseAccountSubcategory::where('id', '=', $id)->first();
                if(count($subcategory) === 1){
                    $subcategory->subcategory = $subcat;
                    $subcategory->save();
                }
            }
        }

        $category = ExpenseAccountCategory::where('id', '=', $catagoryid)->first();

        return $category->subcatagoryJson();

    }

    public function DeleteCatagory()
    {
        $data = array(
            'categoryID' => Input::get('categoryID'),
        );
        
        $rules = array(
            'categoryID' => 'required|exists:expenseaccountcategories,id',
        );
        
        $validator = Validator::make($data, $rules);
        
        if ($validator->fails()){

            return $validator->errors()->toArray();

        } else {
            
            $category = ExpenseAccountCategory::withTrashed()->find($data['categoryID']);
            
            if($category->deleted_at === null){
                $category->delete();
                EventLog::add('Deleted expence catagory, ID:'.$category->id.' Name:'.$category->category);
                return "disabled";
            }else{
                $category->restore();
                EventLog::add('Restore expence catagory, ID:'.$category->id.' Name:'.$category->category);
                return "enabled";
            }
        }    
    }
    
    public function DeleteSubCatagory()
    {
        $data = array(
            'subcategoryID' => Input::get('subcategoryID'),
        );
        
        $rules = array(
            'subcategoryID' => 'required|exists:expenseaccountsubcategories,id',
        );
        
        $validator = Validator::make($data, $rules);
        
        if ($validator->fails()){

            return $validator->errors()->toArray();

        } else {
            
            $category = ExpenseAccountSubcategory::withTrashed()->find($data['subcategoryID']);
            
            if($category->deleted_at === null){
                $category->delete();
                EventLog::add('Deleted expence subcatagory, ID:'.$category->id.' Name:'.$category->category);
                return $category->id;
            }else{
                $category->restore();
                EventLog::add('Restore expence subcatagory, ID:'.$category->id.' Name:'.$category->category);
                return $category->id;
            }
        }
    }
    
    public function TransnationalApplication()
    {
        $data = array(
            'tna-corporate-name' => Input::get('tna-corporate-name'),
            'tna-dba-name' => Input::get('tna-dba-name'),
            'tna-physical-business-address' => Input::get('tna-physical-business-address'),
            'tna-business-phone' => Input::get('tna-business-phone'),
            'tna-owner-officer-name' => Input::get('tna-owner-officer-name'),
            'tna-owner-officer-email' => Input::get('tna-owner-officer-email'),
            'tna-federal-tax-id-number' => Input::get('tna-federal-tax-id-number'),
            'tna-business-type' => Input::get('tna-business-type'),
            'tna-bank-name' => Input::get('tna-bank-name'),
            'tna-bank-account-number' => Input::get('tna-bank-account-number'),
            'tna-bank-routing-number' => Input::get('tna-bank-routing-number'),
            'tna-owner-home-address' => Input::get('tna-owner-home-address'),
            'tna-owner-date-of-birth' => Input::get('tna-owner-date-of-birth'),
            'tna-owner-last-4-of-social-security-number' => Input::get('tna-owner-last-4-of-social-security-number'),
            'tna-estimated-monthly-credit-card-sales' => Input::get('tna-estimated-monthly-credit-card-sales'),
            'tna-typical-average-transaction-amount' => Input::get('tna-typical-average-transaction-amount'),
            'tna-estimated-largest-single-transaction-amount​' => Input::get('tna-estimated-largest-single-transaction-amount​'),
        );
        
        Mail::to("mgandolfo@gotnp.com")->send(new TransnationalApplication($data));
        
        return "sent";

    }

    public function GetSetting($subdomain, $value){
        return SettingHelper::GetSetting($value);

    }

    public function saveTraining(){

        $data = array(
            'id' => Input::get('id'),
            'title' => Input::get('title'),
            'comments' => Input::get('comments'),
            'link' => Input::get('link'),
            'quiz' => Input::get('quiz'),
            );

        $training = TrainingModule::where('id', $data['id'])->first();

        if(count($training) === 1){
            $training->title = $data['title'];
            $training->comments = $data['comments'];
            $training->link = $data['link'];
            $training->quiz = $data['quiz'];
            $training->save();
        }else{
            $training = new TrainingModule;
            $training->title = $data['title'];
            $training->comments = $data['comments'];
            $training->link = $data['link'];
            $training->quiz = $data['quiz'];
            $training->save();
        }

        return ['status' => 'OK', 'training' => $training];

    }

    public function DeleteTraining(){

        $data = array(
            'id' => Input::get('id'),
        );

        $training = TrainingModule::where('id', $data['id'])->first();

        if(count($training) === 1){
            $training->delete();

            return ['status' => 'OK', 'training' => $training];
        }else{
            return ['status' => 'NotFound'];
        }
    }
}
