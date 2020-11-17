<?php

namespace App\Models;

class Branch extends CustomBaseModel
{
    protected $table = 'branches';
    
    public function isDisabled()
    {
        if($this->deleted_at === null){
            return false;
        }else{
            return true;
        }
           
    }
    public function getStatus()
    {
        if($this->deleted_at === null){
            if($this->default === null){
                return "Active";
            }else{
                return "Main";
            }
        }else{
            return "Disabled";
        }
           
    }
    
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }
    
    public function numberOfUsers()
    {
        return count($this->users);
    }
    
    public function ListOfUsers()
    {
        $string = "";
        foreach($this->users as $user){
            $string = $string . $user->getShortName() . ", ";
        }
        
        return $string;
    }

    public function AddressString(){

        $string = "";

        if($this->number != ""){
            $string = $string . $this->number . " ";
        }
        if($this->address1 != ""){
            $string = $string . $this->address1 . " ";
        }
        if($this->address2 != ""){
            $string = $string . $this->address2 . " ";
        }
        if($this->city != ""){
            $string = $string . $this->city . " ";
        }
        if($this->region != ""){
            $string = $string . $this->region . " ";
        }
        if($this->state != ""){
            $string = $string . $this->state . " ";
        }
        if($this->zip != ""){
            $string = $string . $this->zip;
        }

        return $string;

    }
}
