<?php
namespace App\Helpers\OS\Vendor;

use App\Models\VendorContact;
use Illuminate\Support\Facades\Validator;

use \App\Providers\EventLog;


class ContactHelper
{
    public static function ValidateContactsInput($data)
    {

        $rules = array(
            'firstname'    => 'required', #|unique:address,address1',
            'middlename'    => '',
            'lastname'    => 'required',
            'ssn'    => '',
            'driverslicense'    => '',
            'email'    => 'required|email',
            'contacttype' => '',
            'ref1'    => '',
            'ref2'    => '',
            'ref3'    => '',
            'comments'    => '',
            'officenumber'    => 'string',
            'mobilenumber'    => 'string',
            'homenumber'    => 'string',
            'primaryphonenumber'    => 'in:1,2,3',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        return $validator;

    }

    public static function SaveContactToDB($data)
    {
        if ($data['id'] == 0)
        {
            $contact_data = new VendorContact;

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

            if ($data['email'] != null) {
                $contact_data->email = $data['email'];
            }

            if ($data['contacttype'] != null) {
                $contact_data->contacttype = $data['contacttype'];
            }

            if ($data['vendor_id'] != null) {
                $contact_data->vendor_id = $data['vendor_id'];
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
            EventLog::add('New Vendor Contact ID:'.$contact_data->id.' Name:'.$contact_data->firstname.' '.$contact_data->middlename.' '.$contact_data->lastname.' Vendor ID:'.$contact_data->vendor_id);

            return $contact_data;

        }else{

            $contact_data = VendorContact::find($data['id']);

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

            if ($data['email'] != null) {
                $contact_data->email = $data['email'];
            }

            if ($data['contacttype'] != null) {
                $contact_data->contacttype = $data['contacttype'];
            }

            if ($data['vendor_id'] != null) {
                $contact_data->vendor_id = $data['vendor_id'];
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
            EventLog::add('Vendor Contact edited ID:'.$contact_data->id.' Name:'.$contact_data->firstname.' '.$contact_data->middlename.' '.$contact_data->lastname.' Vendor ID:'.$contact_data->vendor_id);

            return $contact_data;

        }
    }

}