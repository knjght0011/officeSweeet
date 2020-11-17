<?php
namespace App\Helpers\OS\Financial;

use Carbon\Carbon;

use App\Models\Deposit;
use App\Models\Receipt;
use App\Models\Check;
use App\Models\OS\Financial\Asset;
use App\Models\MonthEnd;

class JournalHelper
{
    public static function CurrentBalance()
    {
        $lastmonthend = MonthEnd::all()->sortByDesc('id')->first();

        if(count($lastmonthend) === 1) {
            $now = Carbon::now();
            if ($lastmonthend->date->endOfMonth()->diffInMonths($now) > 1) {
                return null;
            } else {

                $deposits = Deposit::whereMonth('date', '=', $now->month)
                    ->whereYear('date', '=', $now->year)
                    ->get();
                $checks = Check::whereMonth('date', '=', $now->month)
                    ->whereYear('date', '=', $now->year)
                    ->where('printed', '!=', null)
                    ->get();
                $receipts = Receipt::whereMonth('date', '=', $now->month)
                    ->whereYear('date', '=', $now->year)
                    ->get();

                $equitys = Asset::whereMonth('date', '=', $now->month)
                    ->whereYear('date', '=', $now->year)
                    ->where('journal','=', 1)
                    ->get();

                $credits = floatval(0);
                $debits = floatval(0);

                foreach ($deposits as $deposit) {
                    $credits = $credits + $deposit->amount;
                }

                foreach($equitys as $equity){
                    $credits = $credits + $equity->amount;
                }

                foreach ($checks as $check) {
                    $debits = $debits + $check->amount;
                }

                foreach ($receipts as $receipt) {
                    $debits = $debits + $receipt->amount;
                }

                return $lastmonthend->endingbalence + $credits - $debits;

            }
        }else{
            return null;
        }
    }
}
