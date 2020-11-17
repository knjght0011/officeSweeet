<?php

namespace App\Models;

use App\Models\Check;
use App\Models\Deposit;
use App\Models\Receipt;

class ExpenseAccountSubcategory extends CustomBaseModel
{
    
    protected $table = 'expenseaccountsubcategories';
    
    public function category()
    {
        return $this->belongsTo('App\Models\ExpenseAccountCategory');
    }

    public function formatedBudget()
    {
        return "$" . number_format(($this->CurrentBudget /100) , 2, '.', ',');
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
                    if ($this->subcategory == $key) {
                        $total += $value;
                    }
                }
            }
        }else{
            $Checks = Check::whereYear('date', $enddate->format("Y"))
                ->whereMonth('date', $enddate->format("m"))
                ->whereDay('date', '<=', $enddate->format("d"))
                ->get();

            foreach ($Checks as $Check)
            {
                foreach($Check['catagorys'] as $key => $value) {
                    if ($this->subcategory == $key) {
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
                    if ($this->subcategory == $key) {
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
                    if ($this->subcategory == $key) {
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

    public function ExpensesForPeriod($start, $end)
    {
        $total = 0;

        $Checks = Check::where('date', '<=', $end)
            ->where('date', '>=', $start)
            ->get();

        foreach ($Checks as $Check)
        {
            foreach($Check['catagorys'] as $key => $value) {
                if ($this->subcategory == $key) {
                    $total -= $value;
                }
            }
        }

        return $total;
    }

    public function ExpensesForPeriodFormated($startdate, $enddate)
    {
        return "$" . number_format($this->ExpensesForPeriod($startdate, $enddate) , 2, '.', ',');
    }

    public function RevenueForPeriod($start, $end)
    {
        $total = 0;
        $Deposits = Deposit::where('date', '<=', $end)
            ->where('date', '>=', $start)
            ->get();

        foreach ($Deposits as $Deposit)
        {
            foreach($Deposit['catagorys'] as $key => $value) {
                if ($this->subcategory == $key) {
                    $total += $value;
                }
            }
        }

        return $total;
    }

    public function RevenueForPeriodFormated($startdate, $enddate)
    {
        return "$" . number_format($this->RevenueForPeriod($startdate, $enddate) , 2, '.', ',');
    }

    public function GetBalanceOnDate($BalanceDate)
    {
        $total = $this->CurrentBudget;

        $Checks = Check::where('date', '<', $BalanceDate)
            ->get();

        foreach ($Checks as $Check)
        {
            foreach($Check['catagorys'] as $key => $value) {
                if ($this->subcategory == $key) {
                    $total -= $value;
                }
            }
        }

        $Deposits = Deposit::where('date', '<', $BalanceDate)->get();

        foreach ($Deposits as $Deposit)
        {
            foreach($Deposit['catagorys'] as $key => $value) {
                if ($this->subcategory == $key) {
                    $total += $value;
                }
            }
        }

        return $total /100;
    }

    public function GetBalanceOnDateFormated($startdate)
    {
        return "$" . number_format($this->GetBalanceOnDate($startdate) , 2, '.', ',');
    }


    public function YTDFormated($enddate)
    {
        return "$" . number_format($this->YTD($enddate) , 2, '.', ',');
    }
}
