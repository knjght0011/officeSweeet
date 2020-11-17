<?php
namespace App\Http\Controllers\OS\Inventory;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

use \App\Providers\EventLog;
use App\Models\OS\Inventory\ServiceLibrary;

class ServiceLibraryController extends Controller
{

    public function SaveProduct()
    {
        $data = array(
            'id' => Input::get('id'),
            'sku' => Input::get('sku'),
            'description' => Input::get('description'),
            'charge' => Input::get('charge'),
            'cost' => Input::get('cost'),
            'taxable' => boolval(Input::get('taxable')),
        );

        $validator = $this->ValidateServiceInput($data);

        if ($validator->fails()){

            return ['status' => 'validation', 'errors' => $validator->errors()->toArray()];

        } else {

            $service = ServiceLibrary::where('id', $data['id'])->first();
            if(count($service) != 1){
                $service = new ServiceLibrary;
                $mode = "new";
            }else{
                $mode = "old";
            }

            $service->sku = $data['sku'];
            $service->description = $data['description'];
            $service->charge = $data['charge'];
            $service->cost = $data['cost'];
            $service->taxable = $data['taxable'];
            $service->save();

            EventLog::add('New/Edit service created ID:'.$service->id.' Name:'.$service->productname);

            return ['status' => 'OK', 'id' => $service->id, 'service' => $service, 'mode' => $mode];

        }
    }

    public function ValidateServiceInput($data)
    {

        $rules = array(
            'sku' => 'required|unique:service_libraries,sku,' . $data['id'],
            'description' => 'required|unique:service_libraries,description,'  . $data['id'],
            'charge' => 'required|numeric',
            'cost' => 'required|numeric',
            'taxable' => 'required|boolean',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules);

        return $validator;

    }


}