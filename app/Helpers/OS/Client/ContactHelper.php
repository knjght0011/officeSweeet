<?php
namespace App\Helpers\OS\Client;

use Illuminate\Support\Facades\Validator;

use \App\Providers\EventLog;

use App\Models\ClientContact;

class ContactHelper
{
    public static function ValidateContactsInput($data)
    {
        if($data['firstname'] === "NOCONTACT"){
            $rules = array();
        }else {
            $rules = array(
                'firstname' => 'required', #|unique:address,address1',
                'middlename' => '',
                'lastname' => 'required',
                'ssn' => '',
                'driverslicense' => '',
                'contactemail' => 'required|email',
                'contacttype' => '',
                'ref1' => '',
                'ref2' => '',
                'ref3' => '',
                'comments' => '',
                'officenumber' => 'string',
                'mobilenumber' => 'string',
                'homenumber' => 'string',
                'primaryphonenumber' => 'in:1,2,3',
            );
        }

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        return $validator;

    }

    public static function SaveContactToDB($data)
    {
        if ($data['id'] == 0)
        {
            $contact_data = new ClientContact;

            if ($data['firstname'] != null) {
                $contact_data->firstname = $data['firstname'];
            }

            if ($data['middlename'] != null) {
                $contact_data->middlename = $data['middlename'];
            }

            if ($data['lastname'] != null) {
                $contact_data->lastname = $data['lastname'];
            }

            if ($data['address_id'] === "null") {
                $contact_data->address_id = null;
            }else{
                $contact_data->address_id = $data['address_id'];
            }

            if ($data['ssn'] != null) {
                $contact_data->ssn = $data['ssn'];
            }

            if ($data['driverslicense'] != null) {
                $contact_data->driverslicense = $data['driverslicense'];
            }

            if ($data['contactemail'] != null) {
                $contact_data->email = $data['contactemail'];
            }

            if ($data['contacttype'] != null) {
                $contact_data->contacttype = $data['contacttype'];
            }

            if ($data['client_id'] != null) {
                $contact_data->client_id = $data['client_id'];
            }

            if ($data['ref1'] != null) {
                $contact_data->ref1 = $data['ref1'];
            }

            if ($data['ref2'] != null) {
                $contact_data->ref2 = $data['ref2'];
            }

            if ($data['ref3'] != null) {
                $contact_data->ref3 = $data['ref3'];
            }

            if ($data['comments'] != null) {
                $contact_data->comments = $data['comments'];
            }

            if ($data['officenumber'] != null) {
                $contact_data->officenumber = $data['officenumber'];
            }

            if ($data['mobilenumber'] != null) {
                $contact_data->mobilenumber = $data['mobilenumber'];
            }

            if ($data['homenumber'] != null) {
                $contact_data->homenumber = $data['homenumber'];
            }

            if ($data['primaryphonenumber'] != null) {
                $contact_data->primaryphonenumber = $data['primaryphonenumber'];
            }

            $contact_data->save();

            //log event
            EventLog::add('New Client Contact ID:'.$contact_data->id.' Name:'.$contact_data->firstname.' '.$contact_data->middlename.' '.$contact_data->lastname.' Client ID:'.$contact_data->client_id);

            return $contact_data;

        }else{

            $contact_data = ClientContact::find($data['id']);

            if ($data['firstname'] != null) {
                $contact_data->firstname = $data['firstname'];
            }

            if ($data['middlename'] != null) {
                $contact_data->middlename = $data['middlename'];
            }

            if ($data['lastname'] != null) {
                $contact_data->lastname = $data['lastname'];
            }

            if ($data['address_id'] === "null") {
                $contact_data->address_id = null;
            }else{
                $contact_data->address_id = $data['address_id'];
            }

            if ($data['ssn'] != null) {
                $contact_data->ssn = $data['ssn'];
            }

            if ($data['driverslicense'] != null) {
                $contact_data->driverslicense = $data['driverslicense'];
            }

            if ($data['contactemail'] != null) {
                $contact_data->email = $data['contactemail'];
            }

            if ($data['contacttype'] != null) {
                $contact_data->contacttype = $data['contacttype'];
            }

            if ($data['client_id'] != null) {
                $contact_data->client_id = $data['client_id'];
            }

            if ($data['ref1'] != null) {
                $contact_data->ref1 = $data['ref1'];
            }

            if ($data['ref2'] != null) {
                $contact_data->ref2 = $data['ref2'];
            }

            if ($data['ref3'] != null) {
                $contact_data->ref3 = $data['ref3'];
            }

            if ($data['comments'] != null) {
                $contact_data->comments = $data['comments'];
            }

            if ($data['officenumber'] != null) {
                $contact_data->officenumber = $data['officenumber'];
            }

            if ($data['mobilenumber'] != null) {
                $contact_data->mobilenumber = $data['mobilenumber'];
            }

            if ($data['homenumber'] != null) {
                $contact_data->homenumber = $data['homenumber'];
            }

            if ($data['primaryphonenumber'] != null) {
                $contact_data->primaryphonenumber = $data['primaryphonenumber'];
            }

            $contact_data->save();

            //log event
            EventLog::add('Client Contact edited ID:'.$contact_data->id.' Name:'.$contact_data->firstname.' '.$contact_data->middlename.' '.$contact_data->lastname.' Client ID:'.$contact_data->client_id);

            return $contact_data;

        }
    }

}