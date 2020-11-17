<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Helpers\OS\SettingHelper;

use Carbon\Carbon;

class User extends Authenticatable
{

    use SoftDeletes;

    public $TrainingStatus = null;

    protected $connection = 'subdomain';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'GoogleAccessToken' => 'array',
        'options' => 'array',
        'permissions' => 'array',
    ];

    protected $appends = ['is_me'];

    //protected $dates = ['start_date', 'end_date'];

    public function getIsMeAttribute()
    {
        if($this->id === Auth::user()->id){
            return '1';
        }else{
            return '0';
        }
    }


    public static function boot()
    {
        parent::boot();

        self::creating(function($instance){
            $length = 16;

            $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

            $str = '';
            $max = strlen($chars) - 1;

            for ($i=0; $i < $length; $i++){
                $str .= $chars[random_int(0, $max)];
            }

            $instance->token = $str;
        });

        self::created(function($model){
            // ... code here
        });

        self::saving(function($model){
            $model->name = $model->firstname . " " . $model->lastname;
        });

        self::updated(function($model){
            // ... code here
        });

        self::deleting(function($model){
            // ... code here
        });

        self::deleted(function($model){
            // ... code here
        });
    }

    public function TrainingRequests()
    {
        return $this->hasMany('App\Models\OS\Training\TrainingRequest', 'user_id', 'id')->withTrashed();
    }

    public function SchedulerEvents()
    {
        return $this->hasMany('App\Models\SchedulerEvents', 'user_id', 'id');
    }
    
    public function branch()
    {
        return $this->belongsTo('App\Models\Branch', 'branch_id', 'id');
    }

    
    public function getBranchAttribute()
    {        
        if($this->branch_id === null)
        {
            $mainbranch = Branch::where('default', "1")->first();
            if(count($mainbranch) === 1){
                return $mainbranch;
            }else{
                $address_data = new Branch;
                return $address_data;
            }
            
        }else{
            return $this->getRelation('branch');
        }
    }

    public function getBranchCityTax()
    {
        $MyBranch = Branch::where('id' , '=' , $this->branch_id)->first();
        return $MyBranch->citytax;
    }

    
    public function address()
    {
        return $this->belongsTo('App\Models\Address');
    }
    
    public function receiptsentered()
    {
        return $this->hasMany('App\Models\Receipt', 'entered_by_user_id', 'id');
    }
    
    public function receipts()
    {
        return $this->hasMany('App\Models\Receipt', 'user_id', 'id');
    }
    
    public function clocks()
    {
        return $this->hasMany('App\Models\Clock', 'user_id', 'id');
    }
    
    public function checks()
    {
        return $this->hasMany('App\Models\Check', 'user_id', 'id');
    }
    
    public function passwordresets()
    {
        return $this->hasMany('App\Models\OS\PasswordReset', 'user_id', 'id');
    }

    public function reports()
    {
        return $this->hasMany('App\Models\OS\Report', 'user_id', 'id');
    }

    public function signing()
    {
        return $this->hasMany('App\Models\OS\Templates\Signing', 'user_id', 'id');
    }

    public function reportscreatedby()
    {
        return $this->hasMany('App\Models\OS\Report', 'created_by', 'id');
    }

    public function billablehours()
    {
        return $this->hasMany('App\Models\OS\BillableHour', 'user_id', 'id');
    }

    public function getName() 
    {
        return $this->firstname . " " . $this->lastname;
    }
    
    public function HashEmail() 
    {
        return hash('ripemd160', $this->email);
    }

    public function notes1()
    {
        return $this->hasMany('App\Models\EmployeeNote', 'user_id', 'id');
    }

    public function files()
    {
        return $this->hasMany('App\Models\OS\FileStore', 'user_id', 'id');
    }

    public function uploads()
    {
        return $this->hasMany('App\Models\OS\FileStore', 'upload_user', 'id');
    }

    public function typeword() 
    {
        switch ($this->type) {
        case 1:
            return "Owner";
        case 2:
            return "Employee";
        case 3:
            return "Contractor";
        case 4:
            return "Agent";
        }
    }
    
    public function getShortName()
    {
        $email = $this->email;
        $array = explode('@', $email);
        return $array[0];
    }
    
    protected function asDateTime($value)
    {
        if ($value instanceof Carbon) {
            return $value;
        }
        $me = \Auth::user();
        $tz = $this->timezone;        
        $value = parent::asDateTime($value);

        return $value->addHours($tz);
    }
        
    public function TotalHours()
    {
        $seconds = 0;
        foreach($this->clocks as $clock){
            $difference = $clock->timedifferenceseconds();
            if($difference !== "Unknown"){
                $seconds += $difference;
            }
        }
        #return gmdate('H:i', $total);
        $hours = floor($seconds / 3600);
        $mins = floor($seconds / 60 % 60);
        #$secs = floor($seconds % 60);
        return sprintf('%02d:%02d', $hours, $mins);
    }
    
    public function hasPermission($permission)
    {
        if(app()->make('account')->plan_name === "SOLO"){

            #solo user permissions
            $permissions = array(
                'acp_subscription_permission',
                'acp_manage_custom_tables_permission',
                'acp_company_info_permission',
                'acp_import_export_permission',
                'acp_permission',
                'client_permission',
                'vendor_permission',
                'reporting_permission',
                'journal_permission',
                'deposits_permission',
                'checks_permission',
                'reciepts_permission',
                'assets_permission',
            );

            if(in_array($permission , $permissions)){
                return true;
            }else{
                return false;
            }

        }else {

            if ($this->os_support_permission === 1) {
                return true;
            } else {
                if (isset($this->permissions[$permission])) {
                    if ($this->permissions[$permission] > 0) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }
    }

    public function hasPermissionMulti($permission, $level)
    {
        if(app()->make('account')->plan_name === "SOLO"){

            #solo user permissions
            $permissions = array(
                'multi_assets_permission',
            );

            if(in_array($permission , $permissions)){
                return true;
            }else{
                return false;
            }

        }else {

            if ($this->os_support_permission === 1) {
                return true;
            } else {
                if (isset($this->permissions[$permission])) {
                    if ($this->permissions[$permission] >= intval($level)) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }
    }

    public function getPermission($permission){
        if (isset($this->permissions[$permission])) {
            return $this->permissions[$permission];
        }else{
            return 0;
        }
    }

    public function RateFrequencyText()
    {
        return "$" . number_format($this->rate , 2, '.', '') . " per " . $this->frequency;
    }    
    public function AdjustmentCarbonTimezone($datestamp)
    {
        $me = \Auth::user();
        return $datestamp->addMinutes(intval($me->timezoneoffset));
        
    
    }

    public function Gettimezoneoffset()
    {
        $DST = SettingHelper::GetSetting("DST");
        if ($DST == 'true')
        {
            $Offset += 60;
        }
        return $Offset;
    }

    public function timezoneAdjustment()
    {     
        $offset = \Auth::user()->timezoneoffset;

        $hours = $offset / 60;
        
        if(substr($hours , 0 ,1 ) === "-"){
            $hours = sprintf("%03d", $hours);
        }else{
            $hours = sprintf("%02d", $hours);
            $hours = "+" . $hours;
        }
        
        
        return "(UTC " . $hours .":00)";

    } 
    
    #payroll related functions
    public function HoursBetweenDates($start, $end){
        
        
        $clocks = Clock::where('user_id', $this->id)->whereBetween('in', [$start, $end])->get();
        
        $seconds = 0;
        foreach($clocks as $clock){
            $difference = $clock->timedifferenceseconds();
            if($difference !== "Unknown"){
                $seconds += $difference;
            }
        }
        #return gmdate('H:i', $total);
        $hours = floor($seconds / 3600);
        $mins = floor($seconds / 60 % 60);
        #$secs = floor($seconds % 60);
        
        $decimal = floatval(100/60);
        
        $decmin = floatval($decimal * $mins);
        
        $total = sprintf('%02d.%02d', $hours, $decmin);
        
        return $total;
    }
    
    public function NumberOfClocks($start, $end){
        $clocks = Clock::where('user_id', $this->id)->whereBetween('in', [$start, $end])->get();
        
        return count($clocks);
    }
    
    public function PayrollQuantity($start, $end, $daysinperiod){
        switch ($this->frequency ) {
            case "hour":
                Return $this->HoursBetweenDates($start, $end);
                #Return $this->NumberOfClocks($start, $end);
            case "day":
                Return $this->NumberOfClocks($start, $end);
            case "week":
                if(SettingHelper::GetSetting('Payroll-Frequency') === "weekly"){
                    return "1";
                }
                if(SettingHelper::GetSetting('Payroll-Frequency') === "biweekly"){
                    return "2";
                }
            case "month":
                if(SettingHelper::GetSetting('Payroll-Frequency') === "monthly"){
                    return "1";
                }
                if(SettingHelper::GetSetting('Payroll-Frequency') === "semimonthly"){
                    return $daysinperiod;
                }
        }
    } 
    
    public function PayrollAmount($daysinmonth = 30){ //$daysinmonth needs sorting
        switch ($this->frequency ) {
            case "hour":
            case "day":
            case "week":
                Return number_format($this->rate , 2, '.', '');
            case "month":
                if(SettingHelper::GetSetting('Payroll-Frequency') === "monthly"){
                    return number_format($this->rate , 2, '.', '');
                }
                if(SettingHelper::GetSetting('Payroll-Frequency') === "semimonthly"){
                    return  number_format($this->rate / $daysinmonth, 2, '.', '');
                }
        }
    }
    
    public function ClientConversions($startdate, $enddate)
    {
        
        $Clients = Client::all();
        foreach ($Clients as $key => $value) 
        {
            $date = $value->ConversionDate();
            $by = $value->Convertedby();

            if($date == null){
                $Clients->forget($key);
            }else if ($date->lt(Carbon::parse($startdate))){
                $Clients->forget($key);
            }else if($date->gt(Carbon::parse($enddate))){
                $Clients->forget($key);
            }elseif ($by != $this->id) {
                $Clients->forget($key);
            }
        }
        return count($Clients);
    }

    public function PermissionsArray(){
        $array = array();

        foreach($this->attributesToArray() as $key => $value){
            $length = strlen("_permission");
            if(substr($key, -$length) === "_permission"){
                $array[$key] = $value;
            }
        }

        return $array;
    }

    public function CanSendGmail(){
        if($this->GoogleAccessToken === null){
            return false;
        }else{
            if(SettingHelper::GetSetting('gmail-per-user') === null){
                return false;
            }else{
                return true;
            }
        }
    }


    public function getDeleted()
    {
        if($this->deleted_at === null){
            return "Active";
        }else{
            return "Inactive";
        }
    }


    /**
     * A user can have many messages
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chatmessage()
    {
        return $this->hasMany('App\Models\OS\Chat\ChatMessage');
    }

    public function chatparticipant()
    {
        return $this->hasMany('App\Models\OS\Chat\ChatParticipants');
    }

    public function Option($value){
        if(isset($this->options[$value])){
            if($this->options[$value] === "1"){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function GetOption($value){
        if(isset($this->options[$value])){
            return $this->options[$value];
        }else{
            return "";
        }
    }

    public function SetOption($key, $value){
        $options = $this->options;
        $options[$key] = $value;
        $this->options = $options;
        $this->save();
    }

    public function getHomeColOptions($type){

        if(isset($this->options['HomeCols'][$type])){
            return $this->options['HomeCols'][$type];
        }else{
            return [];
        }

    }

    public function ScheduleDepartments($department){

        if(isset($this->options["ScheduleDepartments"])){
            if(isset($this->options["ScheduleDepartments"][$department])) {
                if ($this->options["ScheduleDepartments"][$department] === "1") {
                    return true;
                } else {
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

    public function ScheduleDepartmentsArray(){
        $array = array();
        if(isset($this->options["ScheduleDepartments"])) {
            foreach ($this->options["ScheduleDepartments"] as $key => $value) {
                if ($value === "1") {
                    $array[] = $key;
                }
            }
            if (isset($this->options["ScheduleDepartments"]["None"])) {
                if ($this->options["ScheduleDepartments"]["None"] === "1") {
                    $array[] = "";
                }
            }
        }
        return $array;
    }

    public function CheckTraining($ModuleID){

        if($this->TrainingStatus === null) {
            $trainingRequest = $this->TrainingRequests->where('training_id', $ModuleID);

            if (count($trainingRequest) === 0) {
                $this->TrainingStatus = "Never";
                return $this->TrainingStatus;
            } else {
                if (count($trainingRequest) === 1) {
                    $this->TrainingStatus = $trainingRequest->first()->Status();
                    return $this->TrainingStatus;
                } else {
                    $pending = 0;
                    $completed = 0;
                    $deleted = 0;

                    foreach ($trainingRequest as $Request) {
                        switch ($Request->Status()) {
                            case "Pending":
                                $pending = $pending + 1;
                                break;
                            case "Deleted":
                                $deleted = $deleted + 1;
                                break;
                            default:
                                $completed = $completed + 1;
                                break;
                        }
                    }

                    if ($completed > 0) {
                        $this->TrainingStatus = "Completed";
                        return $this->TrainingStatus;
                    } else if ($pending > 0) {
                        $this->TrainingStatus = "Pending";
                        return $this->TrainingStatus;
                    } else {
                        $this->TrainingStatus = "Never";
                        return $this->TrainingStatus;
                    }
                }
            }
        }else{
            return $this->TrainingStatus;
        }

    }

}