<?php

namespace App\Http\Controllers\OS;

use Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use Google;

use App\Helpers\OS\SettingHelper;
use App\Helpers\OS\GoogleHelper;

use App\Models\ExpenseAccountCategory;
use App\Models\ExpenseAccountSubcategory;

class TestController extends Controller {

    public static function CatagorysModal($subdomain){
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
            'Long-Term Investments' => array(),
            'Property, Plant and Equipment' => array(),
            'Intangibles' => array(),
            'Current Liabilities' => array(),
            'Long-Term Liabilities' => array(),
            'Equity' => array(),
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
            'Sales Income' => 'income',  //21
            'Capital Gains' => 'income',
            'Royalties' => 'both',
            'Miscellaneous Income' => 'both',
            'Current Assets' => 'asset',
            'Long-Term Investments' => 'asset',
            'Property, Plant and Equipment' => 'asset',
            'Intangibles' => 'asset',
            'Current Liabilities' => 'liability',
            'Long-Term Liabilities' => 'liability',
            'Equity' => 'liability',
        );

        foreach($expencedata as $key => $value){
            $catagory = new ExpenseAccountCategory;
            #$catagory->setConnection('deployment');
            $catagory->category = $key;
            $catagory->type = $expencetype[$key];
            $catagory->save();
            foreach($value as $subcat){
                $subcatagory = new ExpenseAccountSubcategory;
                #$subcatagory->setConnection('deployment');
                $subcatagory->subcategory = $subcat;
                $subcatagory->expenseaccountcategories_id = $catagory->id;
                $subcatagory->save();
            }
        }

        return "ok";
    }

}
