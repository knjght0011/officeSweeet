<?php

namespace App\Models;

use Carbon\Carbon;
use App\Helpers\FormatingHelper;

class Clock extends CustomBaseModel
{
    protected $table = 'clock';
    
    protected $dates = ['in', 'out'];
    public function statusword() 
    {
        switch ($this->status) {
        case 0:
            return "Out";
        case 1:
            return "In";
        }
    }
    
    public function timedifference() 
    {
        if ($this->out == null){
            return "Unknown";
        }else{
            
            $in = new Carbon($this->in);
            $out = new Carbon($this->out);
            $diff = $in->diffInSeconds($out);
            return gmdate('H:i', $diff);
        }
        
    }
    
    public function timedifferenceseconds() 
    {
        if ($this->out == null){
            return "Unknown";
        }else{
            
            $in = new Carbon($this->in);
            $out = new Carbon($this->out);
            $diff = $in->diffInSeconds($out);
            return $diff;
        }
        
    }
    
    public function inforjava() //2013-03-18T13:00
    {
        if($this->in == null){
            return "";
        }else{
            return date("Y-m-d\TH:i",strtotime ($this->in));
        }
    }
    
    public function indayofweek() 
    {
        if($this->in == null){
            return "Unknown";
        }else{
            return date("l",strtotime ($this->in));
        }
    }
    
    public function indate() 
    {
        if($this->in == null){
            return "Unknown";
        }else{
            return FormatingHelper::DateISO($this->in);
            #return date("jS F Y",strtotime ($this->in));
        }
    }
    
    public function intime() 
    {
        if($this->in == null){
            return "Unknown";
        }else{
            return date("G:i",strtotime ($this->in));
        }
    }
    
    public function informatedtime() 
    {
        if($this->in == null){
            return "Unknown";
        }else{
            return date("jS F Y G:i",strtotime ($this->in));
        }
    }
    
    public function informateddate() 
    {
        if($this->in == null){
            return "Unknown";
        }else{
            return FormatingHelper::DateTimeTimeSheet($this->in);#date("jS F Y G:i",strtotime ($this->in));
        }
    }
    
    public function outforjava() //2013-03-18T13:00
    {
        if($this->out === null){
            return "";
        }else{
            return date("Y-m-d\TH:i",strtotime ($this->out));
        }
    }
    
    public function outdayofweek() 
    {
        
        if ($this->out == null){
            return "Unknown";
        }else{
            return date("l",strtotime ($this->out));
        }
        
    }
    
    public function outdate() 
    {
        if ($this->out == null){
            return "Unknown";
        }else{

            return FormatingHelper::DateISO($this->out);
        }
        
    }
    
    public function outtime() 
    {
        if ($this->out == null){
            return "Unknown";
        }else{
            return date("G:i",strtotime ($this->out));
        }
    }
    
    public function outformatedtime() 
    {
        if ($this->out == null){
            return "Unknown";
        }else{
            return date("jS F Y G:i",strtotime ($this->out));
        }
    }
    
    
    public function outformateddate() 
    {
        if($this->out == null){
            return "Unknown";
        }else{
            return FormatingHelper::DateTimeTimeSheet($this->out);#date("jS F Y G:i",strtotime ($this->in));
        }
    }
    
}
