<?php namespace App\Models\OS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchedulerParent extends Model
{
    use SoftDeletes;

    protected $table = 'scheduler_parent';


    
    public function client()
    {
        return $this->belongsTo('App\Models\Client')->withTrashed();
    }

    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor')->withTrashed();
    }

    public function TrainingRequest()
    {
        return $this->belongsTo('App\Models\OS\Training\TrainingRequest', 'training_request_id')->withTrashed();
    }

    public function patient()
    {
        return $this->belongsTo('App\Models\OS\Clients\Patient');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User')->withTrashed();
    } 
    
    public function scheduler()
    {
        return $this->hasMany('App\Models\OS\Scheduler', 'parent_id', 'id');
    }
    
    function getlinknameAttribute() {
        if($this->patient_id !== null){
            return "";
        }else if($this->vendor_id !== null){
            return $this->vendor->getName();
        }else if($this->training_request_id !== null){
            return $this->TrainingRequest->TrainingModule->title;
        }else if($this->client_id !== null){
            return $this->client->getName();
        }else{
            return "None";
        }
    }

    function getlinkidAttribute() {

        if($this->patient_id !== null){
            return $this->patient_id;
        }else if($this->vendor_id !== null){
            return $this->vendor_id;
        }else if($this->training_request_id !== null){
            return $this->training_request_id;
        }else if($this->client_id !== null){
            return $this->client_id;
        }else{
            return "None";
        }
    }

    function getlinktypeAttribute() {
        if($this->patient_id !== null){
            return "patient";
        }else if($this->vendor_id !== null){
            return "vendor";
        }else if($this->training_request_id !== null){
            return "training";
        }else if($this->client_id !== null){
            return "client";
        }else{
            return "None";
        }
    }

    function getuseremailAttribute() {
       
        if($this->user_id === null | $this->user_id === "0"){
            return "None"; 
        }else{
            return $this->user->email;
        }
    }
    
    
    public function getBackgroundColor()
    {

        $colors = array("#000000", "#FF34FF", "#FF4A46", "#008941", "#006FA6", "#A30059",
            "#7A4900", "#0000A6", "#C61518", "#004D43", "#8FB0FF", "#997D87",
            "#5A0007", "#809693", "#1B4400", "#4FC601", "#3B5DFF", "#4A3B53", "#FF2F80",
            "#61615A", "#BA0900", "#6B7900", "#00C2A0", "#B903AA", "#D16100",
            "#000035", "#7B4F4B", "#A1C299", "#300018", "#0AA6D8", "#013349", "#00846F",
            "#372101", "#FFB500", "#A079BF", "#CC0744", "#001E09",
            "#00489C", "#6F0062", "#0CBD66", "#456D75", "#B77B68", "#7A87A1", "#788D66",
            "#885578", "#FF8A9A", "#D157A0", "#BEC459", "#456648", "#0086ED", "#886F4C",
            "#34362D", "#B4A8BD", "#00A6AA", "#452C2C", "#636375", "#FF913F", "#938A81",
            "#575329", "#00FECF", "#B05B6F", "#8CD0FF", "#3B9700", "#04F757", "#C8A1A1", "#1E6E00",
            "#7900D7", "#A77500", "#6367A9", "#A05837", "#6B002C", "#772600", "#D790FF", "#9B9700",
            "#549E79", "#201625", "#72418F", "#BC23FF", "#99ADC0", "#3A2465", "#922329",
            "#5B4534", "#404E55", "#0089A3", "#CB7E98", "#A4E804", "#324E72", "#6A3A4C",
            "#83AB58", "#001C1E", "#004B28", "#A3A489", "#806C66", "#222800",
            "#BF5650", "#E83000", "#66796D", "#DA007C", "#FF1A59", "#1E0200", "#5B4E51",
            "#C895C5", "#320033", "#FF6832", "#66E1D3", "#CFCDAC", "#D0AC94", "#7ED379", "#012C58" );
        
        return $colors[$this->user_id];
    }
    
    public function getUserIdAttribute($value)
    {
        if($value === null){
            return "0";
        }else{
            return $value;
        }
    }

    public function getUsersName(){
        if($this->user_id === null | $this->user_id === "0"){
            return "None";
        }else{
            return $this->user->name;
        }


    }
}
