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

use App\Models\OS\Chat\ChatNotification;

class SeedHelper2
{

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

    public static function SeedBranchData($number, $address1, $address2, $city, $region, $state, $zip, $phonenumber){
        $branch = new Branch;
        $branch->setConnection('deployment');
        $branch->number = $number;
        $branch->address1 = $address1;
        $branch->address2 = $address2;
        $branch->city = $city;
        $branch->region = $region;
        $branch->state = $state;
        $branch->zip = $zip;

        $branch->phonenumber = $phonenumber;
        $branch->faxnumber = "";

        $branch->default = 1;
        $branch->save();

        return $branch->id;
    }

    public static function SeedAddress(Account $account){
        $address = new Address;
        $address->setConnection('deployment');
        $address->number = "";
        $address->address1 = $account->InstallInfo('address1');
        $address->address2 ="";
        $address->city = $account->InstallInfo('city');
        $address->region = "";
        $address->state = $account->InstallInfo('state');
        $address->zip = $account->InstallInfo('zip');
        $address->type = "";
        $address->save();

        return $address->id;
    }

    public static function SeedAddressPassed($number, $address1, $address2, $city, $region, $state, $zip, $type){

        $address = new Address;
        $address->setConnection('deployment');
        $address->number = $number;
        $address->address1 = $address1;
        $address->address2 = $address2;
        $address->city = $city;
        $address->region = $region;
        $address->state = $state;
        $address->zip = $zip;
        $address->type = $type;
        $address->save();

        return $address->id;
    }

    public static function SeedOwnerUser(Account $account, $addressid, $branchid){
        #Owner user adding
        $employee = new User;
        $employee->setConnection('deployment');
        $employee->firstname = $account->InstallInfo('firstname');
        $employee->middlename = "";
        $employee->lastname = $account->InstallInfo('lastname');

        $employee->email = $account->InstallInfo('email');
        $employee->password = Hash::make($account->InstallInfo('UserPassword'));

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
            'multi_assets_permission' => 3,
        );

        $employee->permissions = $permissions;

        if($account->InstallInfo('version') === "BROKER"){
            $employee->force_password_change = 1;
        }

        $employee->save();

        return $employee->id;
    }

    public static function SeedSettings(Account $account){
        #Settings
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
            'clientinvoicetemplate' => "Thank you for your Business!",
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
            'Refunds' => array(),
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
            'Sales Income' => array(),  //21
            'Capital Gains' => array('Short term Investments','Long term investments'),
            'Royalties' => array(),
            'Miscellaneous Income' => array(),
            'Current Assets' => array('Business Checking', 'Petty Cash'),
            'Long Term Investments' => array(),
            'Property, Plant and Equipment' => array(),
            'Intangibles' => array(),
            'Current Liabilities' => array(),
            'Long Term Liabilities' => array(),
            'Equity' => array('Member Investment'),
            'Business meetings' => array(),
            'Permanent Restricted Funds' => array(),
            'Temporarily Restricted Funds' => array(),
        );

        $expencetype = array(
            'Advertising' => 'expense',
            'Utilities' => 'expense',
            'Travel' => 'expense',
            'Repair and Maintenance' => 'expense',
            'Refunds' => 'both',
            'Office supplies' => 'expense',
            'Postage' => 'expense',
            'Penalties' => 'expense',
            'Promotions' => 'expense',
            'Entertainment' => 'expense',
            'Outside services' => 'expense',
            'Rent' => 'expense',
            'Legal and professional' => 'expense',
            'Marketing' => 'expense',
            'Donation' => 'expense',
            'Commissions and fees' => 'both',
            'Bank charges' => 'expense',
            'Sales Income' => 'income',
            'Capital Gains' => 'income',
            'Royalties' => 'both',
            'Miscellaneous Income' => 'both',
            'Current Assets' => 'asset',
            'Long Term Investments' => 'asset',
            'Property, Plant and Equipment' => 'asset',
            'Intangibles' => 'asset',
            'Current Liabilities' => 'liability',
            'Long Term Liabilities' => 'liability',
            'Equity' => 'equity',
            'Business meetings' => 'expense',
            'Permanent Restricted Funds' => 'asset',
            'Temporarily Restricted Funds' => 'asset',
        );

        foreach($expencedata as $key => $value){
            $catagory = new ExpenseAccountCategory;
            $catagory->setConnection('deployment');
            $catagory->category = $key;
            $catagory->type = $expencetype[$key];
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

    public static function SeedSoloWelcomeNote(){

        $text = "<p>To get started, please watch the training video, <a href='https://youtu.be/aoeyxWQNgVY' target=\"_blank\">Introduction to OfficeSweeet</a>.  This is a short video to familiarize you with how to navigate OfficeSweeet.</p>".
                "<p>Additional training videos are found in the Navigation bar to the left under Training Videos link.</p>".
                "<p>You will want to <strong>white list all emails</strong> from @officesweeet.com to avoid important messages going to spam by mistake.".
                "<p>We will be sending you our <strong>\"3 Minutes to Success\"</strong> videos each day for 9 days. They are honestly only 3 minutes long and we highly recommend taking a few minutes to watch. You'll be up and running in no time.</p>".
                "<p>Additionally, you can always respond to these emails if you have any questions or concerns. We are here for you every step of the way.</p>".
                "<p>To your success... Your OfficeSweeet Support Team</p>";

        $note = new ChatNotification;
        $note->setConnection('deployment');
        $note->title = "Welcome to OfficeSweeet";
        $note->text = $text;
        $note->link = "";
        $note->code = "popuplarge";
        $note->user_id = null;
        $note->save();

    }

    public static function SeedTrialWelcomeNote(){

        $text = "<p>To get started, please watch the training video, <a href='https://youtu.be/aoeyxWQNgVY' target=\"_blank\">Introduction to OfficeSweeet</a>.  This is a short video to familiarize you with how to navigate OfficeSweeet.</p>".
            "<p>Additional training videos are found in the Navigation bar to the left under Training Videos link.</p>".
            "<p>You will want to <strong>white list all emails</strong> from @officesweeet.com to avoid important messages going to spam by mistake.".
            "<p>We will be sending you our <strong>\"3 Minutes to Success\"</strong> videos each day for 9 days. They are honestly only 3 minutes long and we highly recommend taking a few minutes to watch. You'll be up and running in no time.</p>".
            "<p>Additionally, you can always respond to these emails if you have any questions or concerns. We are here for you every step of the way.</p>".
            "<p>To your success... Your OfficeSweeet Support Team</p>";

        $note = new ChatNotification;
        $note->setConnection('deployment');
        $note->title = "Welcome to OfficeSweeet";
        $note->text = $text;
        $note->link = "";
        $note->code = "popuplarge";
        $note->user_id = null;
        $note->save();

    }

}
