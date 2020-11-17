<?php
namespace App\Helpers\OS\Financial;

use App\Models\ExpenseAccountCategory;

class CategoryHelper
{

    public static function GetAllCategorys(){

        if(!app()->bound('categorys')){

            $categorys = ExpenseAccountCategory::orderBy('category','ASC')->get();

            app()->instance('categorys', $categorys);

            return app()->make('categorys');
        }else{
            return app()->make('categorys');
        }
    }

    public static function GetAllAssets(){
        return self::GetAllCategorys()
                ->whereIn('type', ['asset', 'a&l']);
                //->with(['subcategories' => function($query){ $query->withTrashed(); }])
                //->orderBy('category','ASC');
    }

    public static function GetAllLiabilitys(){
        return self::GetAllCategorys()
            ->whereIn('type', ['liability', 'a&l']);
        //->with(['subcategories' => function($query){ $query->withTrashed(); }])
        //->orderBy('category','ASC');
    }

    public static function GetAllEquitys(){
        return self::GetAllCategorys()
            ->whereIn('type', ['equity']);
        //->with(['subcategories' => function($query){ $query->withTrashed(); }])
        //->orderBy('category','ASC');
    }

    public static function GetAllExpenses(){
        return self::GetAllCategorys()
            ->whereIn('type', ['expense', 'both']);
        //->with(['subcategories' => function($query){ $query->withTrashed(); }])
        //->orderBy('category','ASC');
    }

    public static function GetAllIncomes(){
        return self::GetAllCategorys()
            ->whereIn('type', ['income', 'both']);
        //->with(['subcategories' => function($query){ $query->withTrashed(); }])
        //->orderBy('category','ASC');
    }

    /*
     * $assetcatagorys = ExpenseAccountCategory::whereIn('type', ['asset', 'a&l'])->with(['subcategories' => function($query){ $query->withTrashed(); }])->orderBy('category','ASC')->get();
        $liabilitycatagorys = ExpenseAccountCategory::whereIn('type', ['liability', 'a&l'])->with(['subcategories' => function($query){ $query->withTrashed(); }])->orderBy('category','ASC')->get();
        $equitycatagorys = ExpenseAccountCategory::whereIn('type', ['equity'])->with(['subcategories' => function($query){ $query->withTrashed(); }])->orderBy('category','ASC')->get();
        $expensecategorys = ExpenseAccountCategory::whereIn('type',['expense', 'both'])->with(['subcategories' => function($query){ $query->withTrashed(); }])->orderBy('category','ASC')->get();
        $incomecategorys = ExpenseAccountCategory::whereIn('type',['income', 'both'])->with(['subcategories' => function($query){ $query->withTrashed(); }])->orderBy('category','ASC')->get();
     */

}