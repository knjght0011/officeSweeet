<?php
namespace App\Helpers\Management;

use Hash;

use App\Models\Address;
use App\Models\Branch;
use App\Models\Message;
use App\Models\Thread;
use App\Models\Participant;

use App\Models\Management\Account;
use App\Models\Setting;
use App\Models\ExpenseAccountCategory;
use App\Models\ExpenseAccountSubcategory;
use App\Models\CheckSettings;
use App\Models\SchedulerEvents;
use App\Models\User;

class SeedHelper
{

    //no longer used

    public static function SeedSupportUser(){

        #Support user adding
        $supportemployee = new User;
        $supportemployee->setConnection('deployment');
        $supportemployee->firstname ="support";
        $supportemployee->middlename = "support";
        $supportemployee->lastname = "support";

        $supportemployee->email = "support@officesweeet.com";
        $supportemployee->password = Hash::make("AlwaysUse12Avocados");

        $supportemployee->address_id = null;
        $supportemployee->branch_id = null;

        $supportemployee->phonenumber = "";
        $supportemployee->department = "";
        $supportemployee->ssn = "";
        $supportemployee->driverslicense = "";
        $supportemployee->type = "4";

        $supportemployee->os_support_permission = "1";
        $supportemployee->save();
    }

    public static function SeedBranchData(Account $account){
        $branch = new Branch;
        $branch->setConnection('deployment');
        $branch->number = "";
        $branch->address1 = $account->InstallInfo('address_buss');
        $branch->address2 ="";
        $branch->city = $account->InstallInfo('city_buss');
        $branch->region = "";
        $branch->state = $account->InstallInfo('state_buss');
        $branch->zip = $account->InstallInfo('zip_buss');

        $branch->phonenumber = $account->InstallInfo('telephone');
        $branch->faxnumber = "";

        $branch->default = 1;
        $branch->save();

        return $branch->id;
    }

    public static function SeedAddress(Account $account){
        $address = new Address;
        $address->setConnection('deployment');
        $address->number = "";
        $address->address1 = $account->InstallInfo('address');
        $address->address2 ="";
        $address->city = $account->InstallInfo('city');
        $address->region = "";
        $address->state = $account->InstallInfo('state');
        $address->zip = $account->InstallInfo('zip');
        $address->type = "";
        $address->save();

        return $address->id;
    }

    public static function SeedOwnerUser(Account $account, $addressid, $branchid){
        #Owner user adding
        $employee = new User;
        $employee->setConnection('deployment');
        $employee->firstname = $account->InstallInfo('fname');
        $employee->middlename = "";
        $employee->lastname = $account->InstallInfo('lname');

        $employee->email = $account->InstallInfo('email');
        $employee->password = Hash::make($account->InstallInfo('userpassword'));

        $employee->address_id = $addressid;
        $employee->branch_id = $branchid;

        $employee->phonenumber = "";
        $employee->department = "";
        $employee->ssn = "";
        $employee->driverslicense = "";
        $employee->type = "1";

        $employee->canlogin = "1";

        $employee->os_support_permission = "0";

        $permissions = array(
            'acp_subscription_permission' => 1,
            'acp_manage_custom_tables_permission' => 1,
            'acp_company_info_permission' => 1,
            'acp_import_export_permission' => 1,
            'acp_permission' => 1,
            'client_permission' => 1,
            'vendor_permission' => 1,
            'employee_permission' => 1,
            'login_management_permission' => 1,
            'reporting_permission' => 1,
            'journal_permission' => 1,
            'deposits_permission' => 1,
            'checks_permission' => 1,
            'reciepts_permission' => 1,
            'payroll_permission' => 1,
            'chat_permission' => 1,
            'scheduler_permission' => 1,
            'tasks_permission' => 1,
            'templates_permission' => 1,
            'assets_permission' => 3,
        );

        $employee->permissions = $permissions;

        $employee->save();

        return $employee->id;
    }

    public static function SeedSettings(Account $account){
        $settingdata = array(
            'returntop' => "20",
            'returnleft' => "10",
            'returnwidth' => "50",
            'returnheight' => "20",
            'totop' => "45",
            'toleft' => "20",
            'towidth' => "70",
            'toheight' => "20",
            'emailfromaddress' => "admin@officesweeet.com",
            'clientinvoicetemplate' => "Invoice template",
            'clientquotetemplate' => "Here is your quote",
            'companyname' => $account->InstallInfo('company'),
            'companylogo' => "",
            'companyemail' => $account->InstallInfo('email'),
            'Tax' => "0.0",
        );

        foreach($settingdata as $key => $value){
            $setting = new Setting;
            $setting->setConnection('deployment');
            $setting->name = $key;
            $setting->value = $value;
            $setting->save();
        }
    }

    public static function SeedCheckSettings(){
        $checksetttingdata = array(
            'ToTop' => "55",
            'ToLeft' => "27",
            'ToWidth' => "70",
            'ToHeight' => "30",
            'DateTop' => "18",
            'DateLeft' => "180",
            'DateWidth' => "55",
            'PayToTop' => "30",
            'PayToLeft' => "18",
            'PayToWidth' => "175",
            'AmountNumTop' => "30",
            'AmountNumLeft' => "180",
            'AmountNumWidth' => "50",
            'AmountTextTop' => "40",
            'AmountTextLeft' => "15",
            'AmountTextWidth' => "200",
            'memoTop' => "75",
            'memoLeft' => "30",
            'memoWidth' => "82",
        );


        foreach($checksetttingdata as $key => $value){
            $checksettting = new CheckSettings;
            $checksettting->setConnection('deployment');
            $checksettting->name = $key;
            $checksettting->value = $value;
            $checksettting->save();
        }
    }


    public static function SeedCatagorys(){
        $expencedata = array(
            'Advertising' => array(),
            'Utilities' => array("Phone", "Internet", "Electric"),
            'Travel' => array("Transporation", "Accomodations"),
            'Repair and Maintenance' => array(),
            'Office supplies' => array(),
            'Postage' => array(),
            'Penalties' => array(),
            'Promotions' => array(),
            'Entertainment' => array(),
            'Outside services' => array(),
            'Rent' => array(),
            'Legal and professional' => array(),
            'Marketing' => array(),
            'Donation' => array(),
            'Commissions and fees' => array(),
            'Bank charges' => array("Bank fees", "Credit card processing fees"),
        );

        foreach($expencedata as $key => $value){
            $catagory = new ExpenseAccountCategory;
            $catagory->setConnection('deployment');
            $catagory->category = $key;
            $catagory->save();
            foreach($value as $subcat){
                $subcatagory = new ExpenseAccountSubcategory;
                $subcatagory->setConnection('deployment');
                $subcatagory->subcategory = $subcat;
                $subcatagory->expenseaccountcategories_id = $catagory->id;
                $subcatagory->save();
            }
        }
    }

    public static function SeedSchedulerEvents(){
        $SchedulerEvents = array(
            'Client Estimate/Quote' => null,
            'Marketing Meeting' => null,
            'Staff Meeting' => null,
            'Meeting with Client' => null,
            'Out to Lunch' => null,
            'Not Available' => null,
        );

        foreach($SchedulerEvents as $key => $value){
            $event = new SchedulerEvents;
            $event->setConnection('deployment');
            $event->eventname = $key;
            $event->user_id = $value;
            $event->save();
        }
    }

    public static function SeedWelcomeMessage($employeeid){
        $thread = new Thread;
        $thread->setConnection('deployment');
        $thread->subject = "Welcome to OfficeSweeet";
        $thread->save();

        $message = new Message;
        $message->setConnection('deployment');
        $message->thread_id = $thread->id;
        $message->user_id = $employeeid;
        $message->body = "Welcome to OfficeSweeet.
        
         Now that you are logged in, you will want to change your password. Go to Contact Type and select Employee.  Your name will appear in the list as you will be the only employee so far. Click on your name and notice the tab labeled Login Management. Here you will be able to change your password. You can use any combination of letters and numbers. Once you have typed it in and confirmed it, click the Update button to the right.

Next, please watch the training video, Introduction to OfficeSweeet.  To find this video, notice the navigation bar on the left.  Towards the bottom you will see the Support link.  This will take you to a few tabs related to support.  Open the Video Tutorials tab and click the link to Introduction to OfficeSweeet. 

Also, feel free to send us any messages using Contact Support. We always want to hear your feedback. 
.
Wishing you Smooth Sailing!
OfficeSweeet Support Team";
        $message->save();

        $participant = new Participant;
        $participant->setConnection('deployment');
        $participant->thread_id = $thread->id;
        $participant->user_id = $employeeid;
        $participant->last_read = null;
        $participant->save();
    }

    
}
