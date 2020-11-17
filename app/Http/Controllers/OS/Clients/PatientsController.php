<?php

namespace App\Http\Controllers\OS\Clients;

use App\Helpers\OS\Address\AddressHelper;
use App\Helpers\OS\Scheduler\ScheduleHelper;
use App\Http\Controllers\Controller;

use App\Models\OS\Clients\Patient;
use App\Models\OS\Scheduler;
use App\Models\OS\SchedulerParent;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;


class PatientsController extends Controller {


    public static function ValidatePatientInput($data){

        $rules = array(
            'firstname'    => 'required',
            'lastname'    => 'required',
            'mobilenumber'    => '',
            'homenumber'    => 'required_without:mobilenumber',
            'email'    => 'email',
            'comments'    => '',
            'client_id' => '',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    }

    public static function ValidateDate($data){

        $rules = array(
            'date'    => 'required|date',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        Return $validator; // send back all errors

    }

    public function Save()
    {

        $data = array(
            'id' => Input::get('id'),
            'firstname' => Input::get('firstname'),
            'lastname' => Input::get('lastname'),
            'scheduled' => Input::get('scheduled'),
            'mobilenumber' => Input::get('mobilenumber'),
            'homenumber' => Input::get('homenumber'),
            'email' => Input::get('email'),
            'comments' => Input::get('comments'),
            'client_id' => Input::get('client_id'),
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

        //validate
        if($addressdata['zip'] === ""){
            $patientvalidator = $this->ValidatePatientInput($data);

            if ($patientvalidator->fails()) {
                $errors = $patientvalidator->errors()->toArray();
            }

        }else{
            $addressvalidator = AddressHelper::ValidateAddressInput($addressdata);
            $patientvalidator = $this->ValidatePatientInput($data);

            if ($patientvalidator->fails() or $addressvalidator->fails()) {
                $errors = array_merge($patientvalidator->errors()->toArray(), $addressvalidator->errors()->toArray());
            }

        }

        if (isset($errors)) {
            return ['status' => 'validation', 'errors' => $errors];
        } else {

            $patient = Patient::where('id', $data['id'])->first();
            if(count($patient) == 1){

                if($addressdata['zip'] === ""){
                    $data['address_id'] = NULL;
                }else{
                    $address = AddressHelper::SaveAddress($addressdata);
                    $data['address_id'] = $address->id;
                }

                $patient->firstname = $data['firstname'];
                $patient->lastname = $data['lastname'];
                $patient->scheduled = $data['scheduled'];
                $patient->mobilenumber = $data['mobilenumber'];
                $patient->homenumber = $data['homenumber'];
                $patient->email = $data['email'];
                $patient->comments = $data['comments'];
                $patient->address_id = $data['address_id'];
                $patient->client_id = $data['client_id'];

                $patient->save();

                return ['status' => 'OK', 'new' => 'false', 'data' => $patient->returnData()];

            }else{

                $address = AddressHelper::SaveAddress($addressdata);

                $patient = new Patient;
                $patient->firstname = $data['firstname'];
                $patient->lastname = $data['lastname'];
                $patient->scheduled = $data['scheduled'];
                $patient->mobilenumber = $data['mobilenumber'];
                $patient->homenumber = $data['homenumber'];
                $patient->email = $data['email'];
                $patient->comments = $data['comments'];
                $patient->address_id = $address->id;
                $patient->client_id = $data['client_id'];

                $patient->save();

                return ['status' => 'OK', 'new' => 'true', 'data' => $patient->returnData()];

            }



        }
    }

    public function Schedule(){

        $patient = Patient::where('id', Input::get('patient-id'))->first();

        if(count($patient) == 1){

            $datevalidator = $this->ValidateDate(Input::all());

            if ($datevalidator->fails()) {
                return ['status' => 'validation'];
            } else {
                $scheduler = new Scheduler;
                $schedulerparent = new SchedulerParent;

                $start = Carbon::parse(Input::get('date')." 19:00:00")->addMinutes(Auth::user()->timezoneoffset);
                $end = Carbon::parse(Input::get('date')." 19:20:00")->addMinutes(Auth::user()->timezoneoffset);

                $event['title'] = "{$patient->client->getName()} - {$patient->getName()}";
                $event['start'] = $start;
                $event['end'] = $end;
                $event['linkedtype'] = "patient";
                $event['linkedid'] = $patient->id;
                $event['client_id'] = $patient->client_id;

                if($patient->client->assigned_to == null){
                    $event['userid'] = "0";
                }else{
                    $event['userid'] = $patient->client->assigned_to;
                }

                $event['note'] = "";

                $id = ScheduleHelper::UpdateEventFull($schedulerparent, $scheduler, $event);

                sleep(3);

                $patient->scheduled = 'YES';
                $patient->save();
                return ['status' => 'OK', 'time' => strtotime(Input::get('date')." 19:00:00")];
            }
        }else{
            return ['status' => 'notfound'];
        }




    }

    public function View()
    {
        $patient = Patient::get();
        return View::make('Patients.view')
            ->with('patient', $patient);

    }
}