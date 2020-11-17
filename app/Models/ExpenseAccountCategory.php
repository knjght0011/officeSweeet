<?php

namespace App\Models;

use App\Models\Check;
use App\Models\Deposit;
use App\Models\Receipt;

class ExpenseAccountCategory extends CustomBaseModel
{
    
    protected $table = 'expenseaccountcategories';
    
    public function subcategories()
    {
        return $this->hasMany('App\Models\ExpenseAccountSubcategory', 'expenseaccountcategories_id', 'id');
    }

    public function subcatagoryJson()
    {
        $array = array();
        foreach($this->subcategories as $subcategorie){
            $array[$subcategorie->subcategory] = $subcategorie->id;
        }
        return json_encode($array);
    }

    public function formatedBudget()
    {
        return "$" . number_format(($this->CurrentBudget /100) , 2, '.', ',');
    }

    public function MonthlyBudgetFormated()
    {
        return number_format(($this->MonthlyBudget /100) , 2, '.', ',');
    }

    public function MTD($enddate)
    {
        $total = 0;
        if ($this->type == 'income')
        {
            $Deposits = Deposit::whereYear('date', $enddate->format("Y"))
                ->whereMonth('date', $enddate->format("m"))
                ->whereDay('date', '<=', $enddate->format("d"))
                ->get();

            foreach ($Deposits as $Deposit)
            {
                foreach($Deposit['catagorys'] as $key => $value) {
                    if ($this->category == $key) {
                        $total += $value;
                    }
                }
            }
        }else{
            $Checks = Check::whereYear('created_at', $enddate->format("Y"))
                ->whereMonth('date', $enddate->format("m"))
                ->whereDay('date', '<=', $enddate->format("d"))
                ->get();

            foreach ($Checks as $Check)
            {
                foreach($Check['catagorys'] as $key => $value) {
                    if ($this->category == $key) {
                        $total += $value;
                    }
                }
            }

            $receipts = Receipt::whereYear('date', $enddate->format("Y"))
                ->whereMonth('date', $enddate->format("m"))
                ->whereDay('date', '<=', $enddate->format("d"))
                ->get();

            foreach ($receipts as $receipt)
            {
                foreach($receipt['catagorys'] as $key => $value) {
                    if ($this->category == $key) {
                        $total += $value;
                    }
                }
            }
        }
        return $total;
    }

    public function MTDFormated($enddate)
    {
        return "$" . number_format($this->MTD($enddate) , 2, '.', ',');
    }

    public function YTD($enddate)
    {
        $total = 0;
        if ($this->type == 'income')
        {
            $Deposits = Deposit::whereYear('date', $enddate->format("Y"))
                ->whereMonth('date', '<=', $enddate->format("m"))
                ->whereDay('date', '<=', $enddate->format("d"))
                ->get();

            foreach ($Deposits as $Deposit)
            {
                foreach($Deposit['catagorys'] as $key => $value) {
                    if ($this->category == $key) {
                        $total += $value;
                    }
                }
            }
        }else{
            $Checks = Check::whereYear('date', $enddate->format("Y"))
                ->whereMonth('date', '<=', $enddate->format("m"))
                ->whereDay('date', '<=', $enddate->format("d"))
                ->get();

            foreach ($Checks as $Check)
            {
                foreach($Check['catagorys'] as $key => $value) {
                    if ($this->category == $key) {
                        $total += $value;
                    }
                }
            }

            $receipts = Receipt::whereYear('date', $enddate->format("Y"))
                ->whereMonth('date', '<=', $enddate->format("m"))
                ->whereDay('date', '<=', $enddate->format("d"))
                ->get();

            foreach ($receipts as $receipt)
            {
                foreach($receipt['catagorys'] as $key => $value) {
                    if ($this->category == $key) {
                        $total += $value;
                    }
                }
            }
        }
        return $total;

    }

    public function YTDFormated($enddate)
    {
        return "$" . number_format($this->YTD($enddate) , 2, '.', ',');
    }

}
