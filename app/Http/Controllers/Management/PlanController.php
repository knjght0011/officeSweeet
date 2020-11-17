<?php
namespace App\Http\Controllers\Management;

use App\Helpers\OS\Users\UserHelper;
use App\Helpers\TransnationalHelper;
use App\Http\Controllers\Controller;

use App\Models\Management\Promotion;
use Illuminate\Support\Facades\Auth;
#use Illuminate\Support\Facades\Redirect;
#use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
#use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
#use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class PlanController extends Controller
{

    public function showOverview(){

        $plans = Promotion::withTrashed()->get();

        return View::make('Management.Plans.overview')
            ->with('plans', $plans)
            ->with('plans', $plans);


    }

    public function addPlan(){

        $data = Array(
            'name' => Input::get('name'),
            'description' => Input::get('description'),
            'tn_plan_name' => Input::get('tn_plan_name'),
            'numusers' => Input::get('numusers'),
            'cost' => Input::get('cost'),
            'showonpublic' => Input::get('showonpublic'),
            'showinsubs' => Input::get('showinsubs'),
            'starts_at' => Input::get('starts_at'),
            'expires_at' => Input::get('expires_at'),
        );


        $validator = $this->ValidatePlan($data);

        if ($validator->fails()) {
            return ['status' => 'validation' , 'errors' => $validator->errors()->toArray()];
        }else{

            $plan = TransnationalHelper::AddPlan($data['tn_plan_name'], $data['cost']);

            switch ($plan) {
                case "1":
                    $promotion = new Promotion;
                    $promotion->name = $data['name'];
                    $promotion->description = $data['description'];
                    $promotion->tn_plan_name = $data['tn_plan_name'];
                    $promotion->numusers = $data['numusers'];
                    $promotion->cost = $data['cost'];
                    $promotion->showonpublic = $data['showonpublic'];
                    $promotion->showinsubs = $data['showinsubs'];

                    if($data['starts_at'] === ""){
                        $promotion->starts_at = "2019-01-01";
                    }else{
                        $promotion->starts_at = $data['starts_at'];
                    }
                    $promotion->starts_at = $promotion->starts_at->startOfDay()->addHours(7);

                    if($data['expires_at'] === ""){
                        $promotion->expires_at = "2019-01-01";
                    }else{
                        $promotion->expires_at = $data['expires_at'];
                    }
                    $promotion->expires_at = $promotion->expires_at->endOfDay();

                    $promotion->save();

                    return ['status' => 'OK', 'plan' => $promotion];
                    break;
                case "2":
                    return ['status' => 'unknowntnresponce', 'responce' => $plan];
                    break;
                case "3":
                    return ['status' => 'plannameallreadyexists'];
                    break;
                default:
                    return ['status' => 'unknowntnresponce', 'responce' => $plan];
            }

        }
    }


    public function ValidatePlan($data)
    {
        $rules = array(
            'name' => 'string',
            'description' => 'string',
            'tn_plan_name' => 'string',
            'numusers' => 'numeric',
            'cost' => 'numeric',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        return $validator;
    }

    public function editPlan(){

        $data = array(
            'id' => Input::get('id'),
            'name' => Input::get('name'),
            'description' => Input::get('description'),
            'showonpublic' => Input::get('showonpublic'),
            'showinsubs' => Input::get('showinsubs'),
            'starts_at' => Input::get('starts_at'),
            'expires_at' => Input::get('expires_at'),
            'status' => Input::get('status'),
        );

        $plan = Promotion::where('id', $data['id'])->first();

        if(count($plan) === 1){

            $plan->name = $data['name'];
            $plan->description = $data['description'];
            $plan->showonpublic = $data['showonpublic'];
            $plan->showinsubs = $data['showinsubs'];
            $plan->starts_at = $data['starts_at'];
            $plan->starts_at = $plan->starts_at->startOfDay()->addHours(7);
            $plan->expires_at = $data['expires_at'];
            $plan->expires_at = $plan->expires_at->endOfDay();

            if($data['status'] === "1"){
                $plan->deleted_at = null;
            }else{
                $plan->deleted_at = Carbon::now();
            }

            $plan->save();

            return ['status' => 'OK', 'plan' => $plan, 'table' => []];
        }else{
            return ['status' => 'notfound'];
        }


    }

}
