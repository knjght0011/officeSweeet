<?php

namespace App\Http\Controllers;

use App\Helpers\OS\Financial\ClientOverviewHelper;
use App\Helpers\OS\Financial\JournalHelper;
use App\Helpers\OS\SettingHelper;
use App\Helpers\OS\UI\TextHelper;
use App\Helpers\OS\Users\UserHelper;
use App\Models\MonthEnd;
use App\Models\OS\Financial\Depreciation;
use App\Models\ProductLibrary;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Response;

use Illuminate\Support\Facades\DB;

use App\Models\Quote;
use App\Models\Client;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Receipt;
use App\Models\Check;
use App\Models\OS\Financial\Asset;
use App\Models\ExpenseAccountCategory;
use App\Models\ExpenseAccountSubcategory;


use App\Helpers\FormatingHelper;
use App\Helpers\OS\ReportingHelper;
use App\Helpers\OS\PChartHelper;

use Khill\Lavacharts\Lavacharts;


#use \Khill\Lavacharts\DataTables\DataFactory;


class ReportingController extends Controller
{

    public function ShowReportsPage()
    {
        $startdate = FormatingHelper::DateISO(Carbon::now()->addMonths(-1));
        $enddate = FormatingHelper::DateISO(Carbon::now());

        $employees = UserHelper::GetAllUsers();

        return View::make('Reporting.view')
            ->with('startdate', $startdate)
            ->with('enddate', $enddate)
            ->with('employees', $employees);
    }

    public function ShowReport($subdomain, $reporttype, $startdate, $enddate, $option, $output)
    {
        if ($output === "html") {
            $producepdf = false;
        } else {
            $producepdf = true;
        }

        switch ($reporttype) {
            case "1099":
                return $this->Show1099Report($startdate, $enddate, $producepdf, $option);
            case "ProfitLoss":
                return $this->ShowProfitLossReport($startdate, $enddate, $producepdf, $option);
            case "PaymentsAndAdjustments":
                return $this->ShowPaymentsAndAdjustmentsReport($startdate, $enddate, $producepdf, $option);
            case "PaymentsClient":
                return $this->ShowPaymentsClientReport($startdate, $enddate, $producepdf, $option);
            case "Invoices":
                return $this->ShowInvoicesReport($startdate, $enddate, $producepdf, $option);
            case "Sales":
                return $this->ShowSalesReport($startdate, $enddate, $producepdf);
            case "Timesheets":
                return $this->ShowTimesheetsReport($startdate, $enddate, $producepdf, $option);
            case "Ageing":
                return $this->ShowAgeingReport($producepdf, $option);
            case "BalanceSheet":
                return $this->ShowBalanceSheetReport($startdate, $enddate, $producepdf);
            case "Inventory":
                return $this->ShowInventoryReport($producepdf);
            case "InventoryRestock":
                return $this->ShowInventoryRestockReport($producepdf);
            case "ProspectList":
                return $this->ShowProspectListReport($producepdf);
            case "ProspectActivityList":
                return $this->ShowProspectActivityListReport($producepdf);
            case "ClientList":
                return $this->ShowClientListReport($producepdf);
            case "ClientActivityList":
                return $this->ShowClientActivityListReport($startdate, $enddate,$producepdf);
            case "VendorList":
                return $this->ShowVendorListReport($producepdf);
            case "ExpensesByVendor":
                return $this->ShowExpensesByVendorReport($startdate, $enddate, $producepdf);
            case "CityTax":
                return $this->ShowCityTaxReport($startdate, $enddate, $producepdf);
            case "SalesTax":
                return $this->ShowSalesTaxReport($startdate, $enddate, $producepdf);
            case "GeneralLedger":
                return $this->ShowGeneralLedgerReport($startdate, $enddate, $producepdf);
            case "ProspectConversions":
                return $this->ShowProspectConversionsReport($startdate, $enddate, $producepdf);
            case "AccountTransactions":
                return $this->ShowAccountTransactionsReport($startdate, $enddate, $producepdf);
            case "LavaTest":
                return $this->LavaTest($startdate, $enddate, $producepdf);
            case "PChartTest":
                return $this->PChartTest($startdate, $enddate, $producepdf);
            case "IncomeOverTimeGraph":
                return $this->IncomeOverTimeGraph($startdate, $enddate, $producepdf);
            case "CheckRegister":
                return $this->ShowCheckRegisterReport($startdate, $enddate, $producepdf);
            case "AccountOverview":
                return $this->ShowAccountOverview($startdate, $enddate, $producepdf);
            case "AccountsDetailed":
                return $this->ShowAccountsDetailed($startdate, $enddate, $producepdf);
            case "AccountsYTD":
                return $this->ShowAccountsYTD($startdate, $enddate, $producepdf);
            case "AccountsRestricted":
                return $this->ShowAccountsRestricted($startdate, $enddate, $producepdf);
            case "PatientList":
                return $this->ShowPatientList($producepdf);
            default:
                return "Unknown Report";
        }
    }

    public function DrawReport($producepdf, $view, $data, $aspectratio = 'portrait')
    {
        if ($producepdf == true) {

            $filepath = app_path() . '/TestFiles/';
            $tempfilename = hash('sha1', Auth::user()->email) . "-" . hash('sha1', date(DATE_RFC2822)) . '.pdf';

            $file = $filepath . $tempfilename;

            $pdf = PDF::loadView($view, $data)->setPaper('A4', $aspectratio)->save($file);

            return response()->file($file)->deleteFileAfterSend(true);

        } else {

            return View::make($view, $data);

        }
    }

    public function ShowPatientList($producepdf, $option){

        $vendors = Vendor::where('track_1099', 1)->get();

        $expencetotals = array();
        foreach ($vendors as $vendor){

            $expences = Receipt::where('vendor_id', $vendor->id)->whereDate('date', '>', $startdate)->whereDate('date', '<', $enddate)->get();
            $total = 0.00;

            foreach ($expences as $expence){
                $total = $total + $expence->amount;
            }

            $expencetotals[$vendor->id] = $total;

        }

        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "1099";

        $data = compact('companyinfo', 'companyaddress', 'reportname', 'startdate', 'enddate', 'vendors', 'expencetotals');

        return $this->DrawReport($producepdf, "pdf.Reports.1099", $data);

    }

    public function Show1099Report($startdate, $enddate, $producepdf, $option){

        $vendors = Vendor::where('track_1099', 1)->get();

        $expencetotals = array();
        foreach ($vendors as $vendor){

            $expences = Receipt::where('vendor_id', $vendor->id)->whereDate('date', '>', $startdate)->whereDate('date', '<', $enddate)->get();
            $total = 0.00;

            foreach ($expences as $expence){
                $total = $total + $expence->amount;
            }

            $expencetotals[$vendor->id] = $total;

        }

        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "1099";

        $data = compact('companyinfo', 'companyaddress', 'reportname', 'startdate', 'enddate', 'vendors', 'expencetotals');

        return $this->DrawReport($producepdf, "pdf.Reports.1099", $data);

    }

    public function ShowExpensesByVendorReport($startdate, $enddate, $producepdf)
    {
        $items = Vendor::with('receipts')->get();

        $name = "Expenses by Vendor";

        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Expenses by Vendor";

        $data = compact('companyinfo', 'companyaddress', 'reportname', 'items', 'name', 'startdate', 'enddate');

        return $this->DrawReport($producepdf, "pdf.Reports.ExpensesByVendor", $data);

    }

    public function ShowGeneralLedgerReport($startdate, $enddate, $producepdf)
    {
        $deposits = Deposit::whereDate('date', '>', $startdate)->whereDate('date', '<', $enddate)->get();
        $checks = Check::whereDate('date', '>', $startdate)->whereDate('date', '<', $enddate)->where('printed', '!=', null)->get();
        $receipts = Receipt::whereDate('date', '>', $startdate)->whereDate('date', '<', $enddate)->get();
        $assets = Asset::where('type', "a")->whereDate('date', '>', $startdate)->whereDate('date', '<', $enddate)->get();
        $liabilitys = Asset::where('type', "l")->whereDate('date', '>', $startdate)->whereDate('date', '<', $enddate)->get();
        //$equitys = Asset::where('type', "e")->whereDate('date', '>', $startdate)->whereDate('date', '<', $enddate)->get();


        $array = array();
        foreach($deposits as $deposit){

            $categorys = "";
            foreach($deposit->catagorys as $key => $value){
                $categorys = $categorys . $key . ", ";
            }

            $temp = array(
                'date' => $deposit->dateforinput(),
                'type' => 'Deposit',
                'category' => substr($categorys, 0, -2),
                'tofrom' => $deposit->getFrom() . " " . $deposit->getInvoiceNumbers(),
                'amount' => $deposit->formatedAmount(),
            );
            $array[] = $temp;
        }
        foreach($checks as $check){

            $categorys = "";
            foreach($check->catagorys as $key => $value){
                $categorys = $categorys . $key . ", ";
            }


            $temp = array(
                'date' => $check->dateforinput(),
                'type' => 'Check Number: ' . $check->checknumber,
                'category' => substr($categorys, 0, -2),
                'tofrom' => $check->payto,
                'amount' => $check->formatedAmount() . "-",
            );
            $array[] = $temp;
        }
        foreach($receipts as $receipt){

            $categorys = "";
            foreach($receipt->catagorys as $key => $value){
                $categorys = $categorys . $key . ", ";
            }

            $temp = array(
                'date' => $receipt->DateString(),
                'type' => 'Expense',
                'category' => substr($categorys, 0, -2),
                'tofrom' => $receipt->LinkedAccountName(),
                'amount' => $receipt->formatedAmount() . "-",
            );
            $array[] = $temp;
        }

        foreach($assets as $asset){

            $categorys = "";
            foreach($asset->catagorys as $key => $value){
                $categorys = $categorys . $key . ", ";
            }

            $temp = array(
                'date' => $asset->DateString(),
                'type' => 'Asset',
                'category' => substr($categorys, 0, -2),
                'tofrom' => $asset->name,
                'amount' => $asset->getAmount() . "-",
            );
            $array[] = $temp;
        }

        foreach($liabilitys as $liability){

            $categorys = "";
            foreach($liability->catagorys as $key => $value){
                $categorys = $categorys . $key . ", ";
            }

            $temp = array(
                'date' => $liability->DateString(),
                'type' => 'Liability',
                'category' => substr($categorys, 0, -2),
                'tofrom' => $liability->name,
                'amount' => $liability->getAmount() . "-",
            );
            $array[] = $temp;
        }

        /*
        foreach($equitys as $equity){

            $categorys = "";
            foreach($equity->catagorys as $key => $value){
                $categorys = $categorys . $key . ", ";
            }

            $temp = array(
                'date' => $equity->DateString(),
                'type' => 'Expense',
                'category' => substr($categorys, 0, -2),
                'tofrom' => $equity->LinkedAccountName(),
                'amount' => $equity->formatedAmount() . "-",
            );
            $array[] = $temp;
        }
        */
        usort($array, 'self::date_compare');


        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "General Ledger";

        $data = compact('companyinfo', 'companyaddress', 'startdate', 'enddate', 'reportname', 'array');

        return $this->DrawReport($producepdf, "pdf.Reports.GeneralLedger", $data, "landscape");

    }

    private function ShowAccountTransactionsReport($startdate, $enddate, $producepdf)
    {

        $deposits = Deposit::whereDate('date', '>', $startdate)->whereDate('date', '<', $enddate)->get();
        $checks = Check::whereDate('date', '>', $startdate)->whereDate('date', '<', $enddate)->where('printed', '!=', null)->get();
        $receipts = Receipt::whereDate('date', '>', $startdate)->whereDate('date', '<', $enddate)->get();

        $array = array();
        foreach($deposits as $deposit){
            $temp = array(
                'date' => $deposit->dateforinput(),
                'type' => 'Deposit',
                'tofrom' => $deposit->getFrom() . " " . $deposit->getInvoiceNumbers(),
                'income' => $deposit->formatedAmount(),
                'expense' => "",
            );
            $array[] = $temp;
        }
        foreach($checks as $check){
            $temp = array(
                'date' => $check->dateforinput(),
                'type' => 'Check Number: ' . $check->checknumber,
                'tofrom' => $check->payto,
                'income' => "",
                'expense' => $check->formatedAmount(),
            );
            $array[] = $temp;
        }
        foreach($receipts as $receipt){
            $temp = array(
                'date' => $receipt->DateString(),
                'type' => 'Expense',
                'tofrom' => $receipt->LinkedAccountName(),
                'income' => "",
                'expense' => $receipt->formatedAmount(),
            );
            $array[] = $temp;
        }

        usort($array, 'self::date_compare');


        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Account Transactions";

        $data = compact('companyinfo', 'companyaddress', 'startdate', 'enddate', 'reportname', 'array');

        return $this->DrawReport($producepdf, "pdf.Reports.AccountTransactions", $data, "landscape");

    }

    private function ShowAccountOverview($startdate, $enddate, $producepdf)
    {

        $assets = Asset::where('type', 'a')
        ->where('DisplayInReports', '=', '1')
        ->get();


        $array = array();
        foreach($assets as $asset){
            $temp = array(
                'name' => $asset->name,
                'amount' => $asset->formatedAmount(),
                'catagorys' => $asset->catagorys,
                'comments' => $asset->comments,
            );
            $array[] = $temp;
        }



        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Account Overview";

        $data = compact('companyinfo', 'companyaddress', 'startdate', 'enddate', 'reportname', 'array');

        return $this->DrawReport($producepdf, "pdf.Reports.AccountOverview", $data, "landscape");

    }


    private function GetCheckingBalance($BalanceDate)
    {
        $date = $BalanceDate;
        $BalDate = date_create($BalanceDate);
        $month = $BalDate->format("m");
        $year = $BalDate->format("Y");

        $firstmonthend = MonthEnd::first();

        if(strtotime($date) < strtotime($firstmonthend->date)){
            $deposits = Deposit::whereDate('date', '<', $firstmonthend->date)->get();
            $checks = Check::whereDate('date', '<', $firstmonthend->date)->where('printed', '!=', null)->get();
            $receipts = Receipt::whereDate('date', '<', $firstmonthend->date)->get();

            $equitys = Asset::whereDate('date', '<', $firstmonthend->date)
                ->where('journal','=', 1)
                ->get();

            $beginningbalance = floatval(0);

            $endingbalance = $firstmonthend->endingbalence;

        }else{
            $deposits = Deposit::whereMonth('date', '=', $month)
                ->whereYear('date', '=', $year)
                ->get();
            $checks = Check::whereMonth('date', '=', $month)
                ->whereYear('date', '=', $year)
                ->where('printed', '!=', null)
                ->get();
            $receipts = Receipt::whereMonth('date', '=', $month)
                ->whereYear('date', '=', $year)
                ->get();

            $equitys = Asset::whereMonth('date', '=', $month)
                ->whereYear('date', '=', $year)
                ->where('journal','=', 1)
                ->get();

            $beginningbalance = floatval(0);

            if($month === "01"){
                $endmonth = 12;
                $endyear = intval(substr($year, -2)) - 1;
            }else{
                $endmonth = intval($month) - 1;
                $endyear = intval(substr($year, -2));
            }

            $lastmonthend = MonthEnd::where('year', '=', $endyear)
                ->where('month', '=', $endmonth)
                ->first();

            switch (count($lastmonthend)) {
                case 0:
                    $beginningbalance = "Unknown";
                    $endingbalance = null;
                    break;
                case 1:
                    $beginningbalance = floatval($lastmonthend->endingbalence);
                    $endingbalance = floatval(0);
                    break;
                default:
                    #shouldnt ever get here added for completness and debugging
                    $beginningbalance = "Error!!!!!!!!";
                    $endingbalance = null;
            }
        }

        $credits = floatval(0);
        $debits = floatval(0);

        foreach($deposits as $deposit){
            $credits = $credits + $deposit->amount;
        }

        foreach($equitys as $equity){
            $credits = $credits + $equity->amount;
        }

        foreach($checks as $check){
            $debits = $debits + $check->amount;
        }

        foreach($receipts as $receipt){
            $debits = $debits + $receipt->amount;
        }

        if($endingbalance !== null){
            $endingbalance = $beginningbalance + $credits - $debits;
        }

        return $endingbalance;
    }

    private function ShowAccountsYTD($startdate, $enddate, $producepdf)
    {
        $startID = SettingHelper::GetSetting("DetailedRecordFrom");
        $EndID = SettingHelper::GetSetting("d");
        $TodaysDate = date("Y-m-d");

        $ReportDateTime = date_create($enddate);

        date_default_timezone_set("America/New_York");
        $ReportTime = date("h:i a");

        if ($startID == null)
        {
            $startID = 1;
        }

        if ($EndID == null)
        {
            $EndID = 999999;
        }

        $ExpenseAccountCategory = ExpenseAccountCategory::where('id', '>=', $startID)
            ->where('id', '<=', $EndID)
            ->where('ShowInReport', '=', '1')
            ->where('type', '=', 'income')
            ->where('IsRestricted', '<>', '1')->orWhereNull('IsRestricted')
            ->orderBy('category', 'ASC')
            ->get();


        $IncomeArray = array();
        foreach($ExpenseAccountCategory as $Cat) {
            $temp = array(
                'Account' => $Cat->category,
                'budget' => $Cat->formatedBudget(),
                'MTD' => $Cat->MTDFormated($ReportDateTime),
                'YTD' => $Cat->YTDFormated($ReportDateTime),
                'Remaining' => "$" . number_format(($Cat->CurrentBudget /100) - $Cat->YTD($ReportDateTime), 2, '.', ',' ),
            );
            $IncomeArray[] = $temp;

            //GetSubCatagories and add

            $ExpenseAccountSubCategory = ExpenseAccountSubCategory::where('expenseaccountcategories_id', '=', $Cat->id)
                ->where('ShowInReport', '=', '1')
                ->where('IsRestricted', '<>', '1')->orWhereNull('IsRestricted')
                ->get();

            foreach ($ExpenseAccountSubCategory as $SubCat) {
                $temp2 = array(
                    'Account' => ' -- '.$SubCat->subcategory,
                    'budget' => $SubCat->formatedBudget(),
                    'MTD' => $SubCat->MTDFormated($ReportDateTime),
                    'YTD' => $SubCat->YTDFormated($ReportDateTime),
                    'Remaining' => "$" . number_format(($SubCat->CurrentBudget /100) - $SubCat->YTD($ReportDateTime), 2, '.', ',' ),
                );
                $ArraySize = count($temp2);
                if ($ArraySize > 0) {
                    $IncomeArray[] = $temp2;
                }
            }
        }

        $ExpenseAccountCategory = ExpenseAccountCategory::where('id', '>=', $startID)
            ->where('id', '<=', $EndID)
            ->where('ShowInReport', '=', '1')
            ->whereIn('type', ['expense', 'both'])
            ->where('IsRestricted', '<>', '1')->orWhereNull('IsRestricted')
            ->orderBy('category', 'ASC')
            ->get();


        $ExpenseArray = array();
        foreach($ExpenseAccountCategory as $Cat) {
            $temp = array(
                'Account' => $Cat->category,
                'budget' => $Cat->formatedBudget(),
                'MTD' => $Cat->MTDFormated($ReportDateTime),
                'YTD' => $Cat->YTDFormated($ReportDateTime),
                'Remaining' => "$" . number_format(($Cat->CurrentBudget /100) - $Cat->YTD($ReportDateTime), 2, '.', ',' ),
            );
            $ExpenseArray[] = $temp;

            //GetSubCatagories and add

            $ExpenseAccountSubCategory = ExpenseAccountSubCategory::where('expenseaccountcategories_id', '=', $Cat->id)
                ->where('ShowInReport', '=', '1')
                ->where('IsRestricted', '<>', '1')->orWhereNull('IsRestricted')
                ->get();

            foreach ($ExpenseAccountSubCategory as $SubCat) {
                $temp2 = array(
                    'Account' => ' -- '.$SubCat->subcategory,
                    'budget' => $SubCat->formatedBudget(),
                    'MTD' => $SubCat->MTDFormated($ReportDateTime),
                    'YTD' => $SubCat->YTDFormated($ReportDateTime),
                    'Remaining' => "$" . number_format(($SubCat->CurrentBudget /100) - $SubCat->YTD($ReportDateTime), 2, '.', ',' ),
                );
                $ArraySize = count($temp2);
                if ($ArraySize > 0) {
                    $ExpenseArray[] = $temp2;
                }
            }
        }


        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Analysis of Revenue & Expenses";

        $endingbalance = "$" . number_format($this->GetCheckingBalance($enddate) , 2, '.', ',');

        if (strlen($endingbalance) == 0)
        {
            $endingbalance = "$0.00";
        }

        $data = compact('companyinfo', 'companyaddress', 'startdate', 'enddate', 'reportname', 'TodaysDate', 'ReportTime', 'IncomeArray', 'ExpenseArray', 'endingbalance');

        return $this->DrawReport($producepdf, "pdf.Reports.AccountsYTD", $data, "Portrait");

    }


    private function ShowAccountsRestricted($startdate, $enddate, $producepdf)
    {
        $startID = SettingHelper::GetSetting("DetailedRecordFrom");
        $EndID = SettingHelper::GetSetting("d");
        $TodaysDate = date("Y-m-d");

        $ReportDateTime = date_create($enddate);

        date_default_timezone_set("America/New_York");
        $ReportTime = date("h:i a");

        if ($startID == null)
        {
            $startID = 1;
        }

        if ($EndID == null)
        {
            $EndID = 999999;
        }

        $ExpenseAccountCategory = ExpenseAccountCategory::where('id', '>=', $startID)
            ->where('id', '<=', $EndID)
            ->where('ShowInReport', '=', '1')
            ->where('type', '=', 'income')
            ->where('IsRestricted', '=', '1')
            ->orderBy('category', 'ASC')
            ->get();


        $Array = array();


        $ExpenseAccountSubCategory = ExpenseAccountSubCategory::where('ShowInReport', '=', '1')
            ->where('IsRestricted', '=', '1')
            ->get();

        $BalanceTotal = 0;
        $RevenueTotal = 0;
        $ExpenseTotal = 0;
        $EbalanceTotal = 0;

        foreach ($ExpenseAccountSubCategory as $SubCat) {
            $temp2 = array(
                'Account' => $SubCat->subcategory,
                'BBalance' => $SubCat->GetBalanceOnDateFormated($startdate),
                'Revenue' => $SubCat->RevenueForPeriodFormated($startdate, $enddate),
                'Expenses' => $SubCat->ExpensesForPeriodFormated($startdate, $enddate),
                'EBalance' => "$" . number_format($SubCat->GetBalanceOnDate($startdate) + $SubCat->RevenueForPeriod($startdate, $enddate) - $SubCat->ExpensesForPeriod($startdate, $enddate), 2, '.', ',' ),
            );

            $BalanceTotal += $SubCat->GetBalanceOnDate($startdate);
            $RevenueTotal += $SubCat->RevenueForPeriod($startdate, $enddate);
            $ExpenseTotal += $SubCat->ExpensesForPeriod($startdate, $enddate);
            $EbalanceTotal += $SubCat->GetBalanceOnDate($startdate) + $SubCat->RevenueForPeriod($startdate, $enddate) - $SubCat->ExpensesForPeriod($startdate, $enddate);

            $ArraySize = count($temp2);
            if ($ArraySize > 0) {
                $Array[] = $temp2;
            }
        }


        $BalanceTotal = "$" .  number_format($BalanceTotal, 2, '.', ',' );
        $RevenueTotal = "$" . number_format($RevenueTotal, 2, '.', ',' );
        $ExpenseTotal = "$" . number_format($ExpenseTotal, 2, '.', ',' );
        $EbalanceTotal = "$" . number_format($EbalanceTotal, 2, '.', ',' );



        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Summary of Restricted Accounts";

        $data = compact('companyinfo', 'companyaddress', 'startdate', 'enddate', 'reportname', 'TodaysDate', 'ReportTime', 'Array', 'AccountBalance', 'BalanceTotal', 'RevenueTotal', 'ExpenseTotal', 'EbalanceTotal' );

        return $this->DrawReport($producepdf, "pdf.Reports.AccountsRestricted", $data, "Portrait");

    }



    private function ShowAccountsDetailed($startdate, $enddate, $producepdf)
    {
        $startID = SettingHelper::GetSetting("DetailedRecordFrom");
        $EndID = SettingHelper::GetSetting("d");

        if ($startID == null)
        {
            $startID = 1;
        }

        if ($EndID == null)
        {
            $EndID = 999999;
        }

        $ExpenseAccountCategory = ExpenseAccountCategory::where('id', '>=', $startID)
            ->where('id', '<=', $EndID)
            ->get();


        $array = array();
        foreach($ExpenseAccountCategory as $Cat){
            $temp = array(
                'category' => $Cat->category,
                'type' => $Cat->type,
            );
            $array[] = $temp;
        }

        $deposits = Deposit::where('date','>=', $startdate)
                           ->where('date','<=', $enddate)
                           ->get();

        $array2 = array();
        foreach($deposits as $deposit){
            $temp2 = array(
                'date' => $deposit->FormatDate(),
                'amount' => $deposit->formatedAmount(),
                'method' => $deposit->method,
                'comments' => $deposit->comments,
                'catagorys' => $deposit->catagorys,
            );
            $array2[] = $temp2;
        }

        $checks = Check::where('date','>=', $startdate)
                        ->where('date','<=', $enddate)
                        ->get();

        $array3 = array();
        foreach($checks as $check){
            $temp3 = array(
                'date' => $check->dateforinput(),
                'amount' => $check->formatedAmount(),
                'payto' => $check->payto,
                'comments' => $check->comments,
                'catagorys' => $check->catagorys,
            );
            $array3[] = $temp3;
        }

        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Accounts Detailed";

        $data = compact('companyinfo', 'companyaddress', 'startdate', 'enddate', 'reportname', 'array', 'array2', 'array3');

        return $this->DrawReport($producepdf, "pdf.Reports.AccountsDetailed", $data, "portrait");

    }

    private function ShowCheckRegisterReport($startdate, $enddate, $producepdf)
    {


        $checks = Check::whereDate('date', '>', $startdate)->whereDate('date', '<', $enddate)->where('printed', '!=', null)->get();


        $array = array();
        foreach($checks as $check){
            $temp = array(
                'date' => $check->dateforinput(),
                'type' => 'Check Number: ' . $check->checknumber,
                'tofrom' => $check->payto,
                'catagorys' => $check->catagorys,
                'memo' => $check->memo,
                'expense' => $check->formatedAmount(),
            );
            $array[] = $temp;
        }

        usort($array, 'self::date_compare');


        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Check Register";

        $data = compact('companyinfo', 'companyaddress', 'startdate', 'enddate', 'reportname', 'array');

        return $this->DrawReport($producepdf, "pdf.Reports.CheckRegister", $data, "landscape");

    }

    private static function date_compare($a, $b)
    {
        $t1 = strtotime($a['date']);
        $t2 = strtotime($b['date']);
        return $t1 - $t2;
    }

    public function ShowProspectConversionsReport($startdate, $enddate, $producepdf)
    {
        $users = UserHelper::GetAllUsers();
        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Prospect Conversions";

        $data = compact('companyinfo', 'companyaddress', 'reportname', 'startdate', 'enddate', 'users');
        return $this->DrawReport($producepdf, "pdf.Reports.ProspectConversions", $data);
    }

    public function ShowSalesTaxReport($startdate, $enddate, $producepdf)
    {
        $invoices = $this->data($startdate, $enddate, Quote::class, 'finalizeddate');

        $totaltax = array();
        $total = array();
        $overalltaxtotal = 0;
        $overalltotal = 0;

        foreach ($invoices as $invoice) {
            foreach ($invoice->quoteitem as $item) {
                if (array_key_exists($item->tax, $total)) {
                    $totaltax[$item->tax] = $totaltax[$item->tax] + $item->getTaxRAW();
                    $total[$item->tax] = $total[$item->tax] + $item->getSubTotalRAW();
                } else {
                    $totaltax[$item->tax] = $item->getTaxRAW();
                    $total[$item->tax] = $item->getSubTotalRAW();
                }
                $overalltaxtotal = $overalltaxtotal + $item->getTaxRAW();
                $overalltotal = $overalltotal + $item->getSubTotalRAW();
            }
        }



        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Sales Tax";

        $data = compact('companyinfo', 'companyaddress', 'reportname', 'startdate', 'enddate', 'invoices', 'total', 'totaltax', 'overalltotal', 'overalltaxtotal');

        return $this->DrawReport($producepdf, "pdf.Reports.SalesTax", $data);

    }

    public function ShowCityTaxReport($startdate, $enddate, $producepdf)
    {
        $invoices = $this->data($startdate, $enddate, Quote::class, 'finalizeddate');

        $totaltax = array();
        $total = array();
        $overalltaxtotal = 0;
        $overalltotal = 0;

        foreach ($invoices as $invoice) {
            foreach ($invoice->quoteitem as $item) {
                if (array_key_exists($item->citytax, $total)) {
                    $totaltax[$item->citytax] = $totaltax[$item->citytax] + $item->getCityTaxRAW();
                    $total[$item->citytax] = $total[$item->citytax] + $item->getSubTotalRAW();
                } else {
                    $totaltax[$item->citytax] = $item->getCityTaxRAW();
                    $total[$item->citytax] = $item->getSubTotalRAW();
                }
                $overalltaxtotal = $overalltaxtotal + $item->getCityTaxRAW();
                $overalltotal = $overalltotal + $item->getSubTotalRAW();
            }
        }



        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "City Tax";

        $data = compact('companyinfo', 'companyaddress', 'reportname', 'startdate', 'enddate', 'invoices', 'total', 'totaltax', 'overalltotal', 'overalltaxtotal');

        return $this->DrawReport($producepdf, "pdf.Reports.CityTax", $data);

    }

    public function ShowProspectListReport($producepdf)
    {
        $items = Client::all();
        foreach ($items as $key => $item) {
            if ($item->getStatus() === "Client") {
                $items->forget($key);
            }
        }

        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Prospect List";

        $name = "Prospect";

        $data = compact('companyinfo', 'companyaddress', 'reportname', 'items', 'name');

        return $this->DrawReport($producepdf, "pdf.Reports.ProspectList", $data);

    }

    public function ShowProspectActivityListReport($producepdf)
    {
        $items = Client::all();
        foreach ($items as $key => $item) {
            if ($item->getStatus() === "Client") {
                $items->forget($key);
            }
        }

        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Prospect Activity List";

        $name = "Prospect";

        $data = compact('companyinfo', 'companyaddress', 'reportname', 'items', 'name');

        return $this->DrawReport($producepdf, "pdf.Reports.ProspectActivityList", $data, "landscape");

    }

    public function ShowClientActivityListReport($startdate, $enddate, $producepdf)
    {
        $items = Client::all();
        foreach ($items as $key => $item) {
            if ($item->getStatus() === "Prospect") {
                $items->forget($key);
            }
        }

        $name = "Client";

        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Client Activity List";

        $data = compact('companyinfo', 'companyaddress', 'startdate', 'enddate', 'reportname', 'items', 'name');

        return $this->DrawReport($producepdf, "pdf.Reports.List", $data);

    }

    public function ShowClientListReport($producepdf)
    {
        $items = Client::all();
        foreach ($items as $key => $item) {
            if ($item->getStatus() === "Prospect") {
                $items->forget($key);
            }
        }

        $name = "Client";

        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Client List";

        $data = compact('companyinfo', 'companyaddress', 'reportname', 'items', 'name');

        return $this->DrawReport($producepdf, "pdf.Reports.ClientList", $data);

    }

    public function ShowVendorListReport($producepdf)
    {
        $items = Vendor::all();

        $name = "Vendor";

        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Vendor List";

        $data = compact('companyinfo', 'companyaddress', 'reportname', 'items', 'name');

        return $this->DrawReport($producepdf, "pdf.Reports.ClientList", $data);

    }

    private function data($startdate, $enddate, $model, $field)
    {

        return $model::whereBetween($field, [Carbon::parse($startdate), Carbon::parse($enddate)])->get();

    }

    ##Business Financials
    public function ShowAgeingReport($producepdf, $option)
    {

        switch ($option) {
            case "all":
                $clients = Client::with('primarycontact')
                    ->withTrashed()
                    ->with('quotes')
                    ->get();
                foreach ($clients as $key => $value) {
                    if ($value->getBalence(false) == 0) {
                        $clients->forget($key);
                    }
                }
                break;
            default:
                $clients = Client::where('id', $option)
                    ->with('quotes')
                    ->with('primarycontact')
                    ->get();
        }

        #return var_dump($clients);

        $TotalLessThan30 = 0.00;
        $Total31to60 = 0.00;
        $Total61to90 = 0.00;
        $Total90plus = 0.00;
        $TotalBalence = 0.00;

        foreach ($clients as $client) {
            $TotalLessThan30 += $client->getBalenceLessThan30(false);
            $Total31to60 += $client->getBalence31to60(false);
            $Total61to90 += $client->getBalence61to90(false);
            $Total90plus += $client->getBalence90plus(false);
            $TotalBalence += $client->getBalence(false);
        }

        $date["0"] = \Carbon\Carbon::now()->format('Y-m-d');
        $date["30"] = \Carbon\Carbon::now()->addDays(-30)->format('Y-m-d');
        $date["60"] = \Carbon\Carbon::now()->addDays(-60)->format('Y-m-d');
        $date["90"] = \Carbon\Carbon::now()->addDays(-90)->format('Y-m-d');

        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Aging Receivables";
        $startdate = "";
        $enddate = date("d-M-Y");


        $data = compact('companyinfo', 'companyaddress', 'startdate', 'enddate', 'reportname', 'clients', 'TotalLessThan30', 'Total31to60', 'Total61to90', 'Total90plus', 'TotalBalence', 'date');

        return $this->DrawReport($producepdf, "pdf.Reports.Ageing", $data, 'landscape');

    }


    private function ShowBalanceSheetReport($startdate, $enddate, $producepdf)
    {

        $a = Asset::where('type', '=', "a")->get();

        $assets = array();
        #$assetstotal = floatval(0);
        if (count($a) > 0) {
            foreach ($a as $asset) {
                foreach ($asset->catagorys as $name => $amount) {
                    $split = explode ( "-", $name);
                    if(count($split) === 2){
                        $catagory = trim($split[0]);
                        $subcatagory = trim($split[1]);
                    }else{
                        $catagory = trim($split[0]);
                        $subcatagory = "Miscellaneous";
                    }

                    if (array_key_exists($catagory, $assets)) {
                        $subcatagoryarray = $assets[$catagory];
                    } else {
                        $subcatagoryarray = array();
                    }


                    if (array_key_exists($subcatagory, $subcatagoryarray)) {
                        $subcatagoryarray[$subcatagory] = $subcatagoryarray[$subcatagory] + floatval($amount);
                    } else {
                        $subcatagoryarray[$subcatagory] = floatval($amount);
                    }

                    $assets[$catagory] = $subcatagoryarray;

                    #$assetstotal = floatval($assetstotal) + floatval($amount);
                }
            }
        }

        $l = Asset::where('type', '=', "l")->get();

        $liabilitys = array();
        #$liabilitystotal = floatval(0);
        if (count($l) > 0) {
            foreach ($l as $lsset) {
                foreach ($lsset->catagorys as $name => $lmount) {
                    $split = explode ( "-", $name);
                    if(count($split) === 2){
                        $catagory = $split[0];
                        $subcatagory = $split[1];
                    }else{
                        $catagory = $split[0];
                        $subcatagory = "Miscellaneous";
                    }

                    if (array_key_exists($catagory, $liabilitys)) {
                        $subcatagoryarray = $liabilitys[$catagory];
                    } else {
                        $subcatagoryarray = array();
                    }


                    if (array_key_exists($subcatagory, $subcatagoryarray)) {
                        $subcatagoryarray[$subcatagory] = $subcatagoryarray[$subcatagory] + floatval($lmount);
                    } else {
                        $subcatagoryarray[$subcatagory] = floatval($lmount);
                    }

                    $liabilitys[$catagory] = $subcatagoryarray;

                    #$liabilitystotal = floatval($liabilitystotal) + floatval($lmount);
                }
            }
        }

        $checks = Check::where('printqueue', '1')->get();

        $unprintedchecks = 0.00;
        foreach($checks as $check){
            $unprintedchecks = $unprintedchecks + $check->amount;
        }

        if(array_key_exists('Current Liabilities', $liabilitys)){
            if(array_key_exists( 'Payables', $liabilitys['Current Liabilities'])){
                $liabilitys['Current Liabilities']['Payables'] = $liabilitys['Current Assets']['Business Checking'] + floatval($unprintedchecks);
            }else{
                $liabilitys['Current Liabilities']['Payables'] = floatval($unprintedchecks);
            }
        }else{
            $liabilitys['Current Liabilities']['Payables'] = floatval($unprintedchecks);
        }

        $journalbalence = JournalHelper::CurrentBalance();

        if($journalbalence != null) {
            if(array_key_exists('Current Assets', $assets)){
                if(array_key_exists( 'Business Checking', $assets['Current Assets'])){
                    $assets['Current Assets']['Business Checking'] = $assets['Current Assets']['Business Checking'] + floatval($journalbalence);
                }else{
                    $assets['Current Assets']['Business Checking'] = floatval($journalbalence);
                }
            }else{
                $assets['Current Assets']['Business Checking'] = floatval($journalbalence);
            }
        }

        $clients = Client::all();

        $recevables = 0.00;
        //$quotevalue = 0.00;

        foreach($clients as $client){
            if($client->getStatus() === "Client"){
                $recevables += $client->getBalence(false);
            //}else{
            //    $quotevalue += $client->getOpenQuoteValue(false);
            }
        }

        if($recevables > 0) {
            if(array_key_exists('Current Assets', $assets)){
                if(array_key_exists( 'Receivables', $assets['Current Assets'])){
                    $assets['Current Assets']['Receivables'] = $assets['Current Assets']['Receivables'] + floatval($recevables);
                }else{
                    $assets['Current Assets']['Receivables'] = floatval($recevables);
                }
            }else{
                $assets['Current Assets']['Receivables'] = floatval($recevables);
            }
        }

        $inventory = ProductLibrary::all();
        $inventorytotal  = 0;
        foreach($inventory as $product){
            if($product->companyuse === 0 & $product->trackstock === 1){
                $inventorytotal = $inventorytotal + $product->CurrentStockValue();
            }
        }

        $assets['Current Assets']['Inventory'] = floatval($inventorytotal);

        //sort arrays
        ksort($assets);

        foreach($assets as $key => $value){
            ksort($value);

            if(array_key_exists("Miscellaneous", $value)){
                $v = $value['Miscellaneous'];
                unset($value['Miscellaneous']);
                $value['Miscellaneous'] = $v;
            }

            $assets[$key] = $value;
        }

        ksort($liabilitys);

        foreach($liabilitys as $key => $value){
            ksort($value);

            if(array_key_exists("Miscellaneous", $value)){
                $v = $value['Miscellaneous'];
                unset($value['Miscellaneous']);
                $value['Miscellaneous'] = $v;
            }

            $liabilitys[$key] = $value;

        }

        $depriciations = Depreciation::all();
        $totaldepriciation = 0.00;
        foreach ($depriciations as $depriciation) {
            $totaldepriciation = $totaldepriciation + $depriciation->amount;
        }

        $totalassets = 0;
        foreach ($assets as $catagory => $subcatagorys){
            foreach($subcatagorys as $subcatagory => $value){
                $totalassets = $totalassets + floatval($value);
            }
        }

        $totalassets = $totalassets - $totaldepriciation;

        $totalliabilitys = 0;
        foreach ($liabilitys as $catagory => $subcatagorys){
            foreach($subcatagorys as $subcatagory => $value){
                $totalliabilitys = $totalliabilitys + floatval($value);
            }
        }

        $totalequity = $totalassets - $totalliabilitys;

        $e = Asset::where('type', '=', "e")->get();
        $equitycatagorytotals = 0;
        $equitys = array();
        foreach ($e as $equity) {
            foreach ($equity->catagorys as $catagory => $amount) {
                if (array_key_exists($catagory, $equitys)) {
                    $equitys[$catagory] = $equitys[$catagory] + floatval($amount);
                } else {
                    $equitys[$catagory] = floatval($amount);
                }

                $equitycatagorytotals = $equitycatagorytotals +  floatval($amount);
            }
        }

        $retainedearnings = $totalequity - $equitycatagorytotals;

        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Balance Sheet";

        $data = compact('companyinfo', 'companyaddress', 'reportname', 'assets', 'totalassets', 'liabilitys', 'totalliabilitys', 'equitys', 'totalequity', 'retainedearnings', 'totaldepriciation');

        return $this->DrawReport($producepdf, "pdf.Reports.BalanceSheet", $data);

    }

    #Transactions
    public function ShowPaymentsAndAdjustmentsReport($startdate, $enddate, $producepdf, $option)
    {

        switch ($option) {
            case "all":
                $payments = $this->data($startdate, $enddate, Deposit::class, 'date');

                break;
            case "onlycashandcheque":
                $payments = Deposit::whereBetween('date', [Carbon::parse($startdate), Carbon::parse($enddate)])
                    ->where('method', "=", "check")
                    ->orWhere('method', "=", "cash")
                    ->get();

                #$paymentstemp = $this->data($startdate, $enddate, Deposit::class , 'date');
                #$payments  = $paymentstemp     
                #            ->where('method', "=", "check")
                #            ->orWhere('method', "=", "cash");

                break;
            case "nocashandcheque":
                $paymentstemp = $this->data($startdate, $enddate, Deposit::class, 'date');
                $payments = $paymentstemp
                    ->where('method', "!=", "Check")
                    ->where('method', "!=", "Cash");
                break;
        }

        $total = 0;

        foreach ($payments as $payment) {
            $total = $total + $payment->amount;
        }

        $data = compact('total', 'payments');

        return $this->DrawReport($producepdf, "pdf.Reports.PaymentsAndAdjustments", $data);

    }

    #PaymentsClient

    public function ShowPaymentsClientReport($startdate, $enddate, $producepdf, $option)
    {

        $client = Client::where('id', '=', $option)->first();

        if(count($client) === 1){

            $reportdata = ClientOverviewHelper::Data($client, null, null, true);

            $companyinfo = ReportingHelper::generateCompanyInfoArray();
            $companyaddress = ReportingHelper::generateCompanyAddressArray();
            $reportname = "Client Statement";

            $data = compact('companyinfo', 'companyaddress', 'reportname', 'total', 'reportdata', 'client', 'startbalence');

            return $this->DrawReport($producepdf, "pdf.Reports.PaymentsClient", $data);
        }else{
            return "Error: Unknown Client";
        }
    }

    public function ShowInvoicesReport($startdate, $enddate, $producepdf, $option)
    {

        switch ($option) {
            case "all":
                $invoices = $this->data($startdate, $enddate, Quote::class, 'finalizeddate');

                break;
            default:
                $invoices = $this->data($startdate, $enddate, Quote::class, 'finalizeddate');
                $invoices = $invoices->Where('finalizedbyuser', $option);
        }

        $Total = 0;
        foreach ($invoices as $invoice) {
            $Total += $invoice->getTotalFloat();
        }

        $data = compact('invoices', 'Total');

        return $this->DrawReport($producepdf, "pdf.Reports.Invoices", $data);

    }

    public function ShowSalesReport($startdate, $enddate, $producepdf)
    {



        $data = compact('invoices', 'Total');

        return $this->DrawReport($producepdf, "pdf.Reports.Invoices", $data);

    }

    #Employees/Contractors
    public function ShowTimesheetsReport($startdate, $enddate, $producepdf, $option)
    {

        $carbonstart = Carbon::parse($startdate)->startOfDay();

        $carbonend = Carbon::parse($enddate)->endOfDay();

        switch ($option) {
            case "all":
                $employees = User::with(array('clocks' => function ($query) use ($carbonstart, $carbonend) {
                    $query->whereBetween('in', [$carbonstart, $carbonend]);
                }))
                    ->get();


                foreach ($employees as $key => $value) {
                    if (count($value->clocks) == 0) {
                        $employees->forget($key);
                    }
                }
                break;
            default:
                $employees = User::where('id', $option)
                    ->with(array('clocks' => function ($query) use ($carbonstart, $carbonend) {
                        $query->whereBetween('in', [$carbonstart, $carbonend]);
                    }
                    ))->get();
        }

        $data = compact('employees', 'carbonstart', 'carbonend');

        return $this->DrawReport($producepdf, "pdf.Reports.Timesheets", $data);

    }

    public function ShowInventoryReport($producepdf)
    {

        $products = ProductLibrary::where("stock", ">" , 0 )->get();

        $inventorytotal  = 0;
        foreach($products as $product){
            $inventorytotal = $inventorytotal + $product->CurrentStockValue();
        }

        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Inventory Report";

        $startdate = "";
        $enddate = date('Y-m-d');

        $data = compact('companyinfo', 'companyaddress', 'startdate', 'enddate', 'reportname', 'products', 'inventorytotal');

        return $this->DrawReport($producepdf, "pdf.Reports.Inventory", $data);

    }

    public function ShowInventoryRestockReport($producepdf)
    {

        #$products = ProductLibrary::where("stock", "<=" , "reorderlevel"  )->get();#where('reorderlevel' , '>', 0 )->
        $sql = 'SELECT * FROM productlibrary WHERE reorderlevel > 0 AND stock <= reorderlevel';
        $products = DB::select($sql);


        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Inventory Report";

        $startdate = "";
        $enddate = date('Y-m-d');

        $data = compact('companyinfo', 'companyaddress', 'startdate', 'enddate', 'reportname', 'products');

        return $this->DrawReport($producepdf, "pdf.Reports.InventoryRestock", $data);

    }

    #interactive reports
    public function ShowQuoteReport()
    {
        $quotes = Quote::where('finalized', 0)
            ->with('quoteitem')
            ->get();

        $clients = Client::all();

        return View::make('OS.Quotes.report')
            ->with('clients', $clients)
            ->with('quotes', $quotes);

    }

    public function ShowInvoiceReport()
    {
        $quotes = Quote::where('finalized', 1)
            ->with('quoteitem')
            ->get();

        $clients = Client::all();

        return View::make('OS.Invoices.report')
            ->with('clients', $clients)
            ->with('quotes', $quotes);

    }

    private function LavaTest($startdate, $enddate, $producepdf)
    {

        $lava = new Lavacharts;

        $popularity = $lava->DataTable();

        $data = array();

        array_push($data, ['India', 500], ['Italy', 400], ['United States', 300], ['Germany', 200], ['France', 100]);

        $popularity->addStringColumn('Country')
            ->addNumberColumn('Popularity')
            ->addRows($data);
        #->addRow(['Check Reviews', 5])
        #->addRow(['Watch Trailers', 2])
        #->addRow(['See Actors Other Work', 4])
        #->addRow(['Settle Argument', 89]);

        $lava->PieChart('Popularity', $popularity, [
            'title' => 'Reasons I visit IMDB',
            'is3D' => true,

        ]);

        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "LavaTest";
        $data = compact('companyinfo', 'companyaddress', 'startdate', 'enddate', 'reportname', 'lava');

        return $this->DrawReport(false, "pdf.Reports.LavaTest", $data);

    }

    private function PChartTest($startdate, $enddate, $producepdf)
    {
        $b64 = PChartHelper::PChart();

        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "LavaTest";
        $data = compact('companyinfo', 'companyaddress', 'startdate', 'enddate', 'reportname', 'b64');

        return $this->DrawReport(true, "pdf.Reports.PChartTest", $data);

    }

    private function ShowProfitLossReport($startdate, $enddate, $producepdf, $option)
    {

        $deposits = $this->data($startdate, $enddate, Deposit::class, 'date');

        $receipts = $this->data($startdate, $enddate, Receipt::class, 'date');

        $cheques = $this->data($startdate, $enddate, Check::class, 'date');

        $depreciations = Depreciation::whereBetween('date', [Carbon::parse($startdate), Carbon::parse($enddate)])->get();

        $depreciationtotal = 0.00;
        foreach ($depreciations as $depreciation){
            $depreciationtotal = $depreciationtotal + $depreciation->amount;
        }


        $expences = array();
        $expencestotal = floatval($depreciationtotal);
        if (count($receipts) > 0) {
            foreach ($receipts as $receipt) {
                foreach ($receipt->catagorys as $name => $amount) {
                    if (array_key_exists($name, $expences)) {
                        $expences[$name] = $expences[$name] + floatval($amount);
                    } else {
                        $expences[$name] = floatval($amount);
                    }
                    $expencestotal = floatval($expencestotal) + floatval($amount);
                }
            }
        }

        if (count($cheques) > 0) {
            foreach ($cheques as $cheque) {
                if ($cheque->printed != null) {
                    foreach ($cheque->catagorys as $name => $amount) {
                        if (array_key_exists($name, $expences)) {
                            $expences[$name] = $expences[$name] + floatval($amount);
                        } else {
                            $expences[$name] = floatval($amount);
                        }
                        $expencestotal = floatval($expencestotal) + floatval($amount);
                    }
                }
            }
        }



        $profitcatagorys = array();
        $incometotal = floatval(0);
        foreach ($deposits as $deposit) {
            foreach ($deposit->catagorys as $name => $amount) {
                if (array_key_exists($name, $profitcatagorys)) {
                    $profitcatagorys[$name] = $profitcatagorys[$name] + floatval($amount);
                } else {
                    $profitcatagorys[$name] = floatval($amount);
                }
                $incometotal = floatval($incometotal) + floatval($amount);
            }
        }

        $profitloss = $incometotal - $expencestotal;

        ksort($profitcatagorys);
        ksort($expences);

        if($option === "graphs"){
            $expencespie = PChartHelper::ExpensePage($expences, "Expenses");
            $incomepie = PChartHelper::ExpensePage($profitcatagorys, "Income");
        }else{
            $expencespie = null;
            $incomepie = null;
        }


        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = TextHelper::GetText("Profit and Loss") . " Statement";

        $data = compact('companyinfo', 'companyaddress', 'startdate', 'enddate', 'reportname', 'incometotal', 'profitloss', 'profitcatagorys', 'expences', 'expencestotal', 'expencespie', 'incomepie', 'depreciationtotal');

        return $this->DrawReport($producepdf, "pdf.Reports.ProfitLoss", $data);

    }


    public function TestPalatte(){

        $palattes = array();
        for ($x = 1; $x <= 770; $x++) {
            $palattes[] = PChartHelper::Palette($x);
        }

        return View::make('testpage')
            ->with('palattes', $palattes);

    }

    public function IncomeOverTimeGraph($startdate, $enddate, $producepdf)
    {

        $date = Carbon::parse($startdate);
        $end = Carbon::parse($enddate);

        $startdate = $date->firstOfMonth()->toDateString();
        $enddate = $end->lastOfMonth()->toDateString();

        if ($date->gt($end)) {
            return "Start Date Must Be Before End Date.";
        }

        $data = array();

        while ($date->lt($end)) {
            $deposits = Deposit::whereMonth('date', '=', $date->month)
                ->whereYear('date', '=', $date->year)
                ->get();

            $total = 0.0;
            foreach ($deposits as $deposit) {
                $total = $total + $deposit->amount;
            }

            $data[$date->format('M y')] = $total;

            $date->addMonths(1);
        }

        $chart = PChartHelper::LineChart($data, "$", "");

        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "IncomeOverTimeGraph";

        $data = compact('companyinfo', 'companyaddress', 'startdate', 'enddate', 'reportname', 'chart');

        return $this->DrawReport($producepdf, "pdf.Reports.ChartLandscape", $data, 'landscape');
    }

    public function ShowInteractiveProfitAndLossReport($subdomain, $startdate = null, $enddate = null)
    {
        if ($startdate === null) {
            $enddate = Carbon::now();
            $enddate->hour = 23;
            $enddate->minute = 59;
            $enddate->second = 59;

            $startdate = Carbon::now();
            $startdate->subMonth();
            $startdate->hour = 00;
            $startdate->minute = 00;
            $startdate->second = 00;
        } else {
            $enddate = Carbon::parse($enddate);
            $enddate->hour = 23;
            $enddate->minute = 59;
            $enddate->second = 59;

            $startdate = Carbon::parse($startdate);
            $startdate->hour = 00;
            $startdate->minute = 00;
            $startdate->second = 00;
        }

        $deposits = Deposit::whereBetween('date', [Carbon::parse($startdate), Carbon::parse($enddate)])->get();

        $receipts = Receipt::whereBetween('date', [Carbon::parse($startdate), Carbon::parse($enddate)])->get();

        $cheques = Check::whereBetween('date', [Carbon::parse($startdate), Carbon::parse($enddate)])->get();

        $depreciations = Depreciation::whereBetween('date', [Carbon::parse($startdate), Carbon::parse($enddate)])->get();

        $depreciationtotal = 0.00;
        foreach ($depreciations as $depreciation){
            $depreciationtotal = $depreciationtotal + $depreciation->amount;
        }

        $expencecatagorys = array();
        $expencecatagorystotal = array();;
        $expencestotal = floatval($depreciationtotal);
        if (count($receipts) > 0) {
            foreach ($receipts as $receipt) {
                foreach ($receipt->catagorys as $name => $amount) {
                    if (array_key_exists($name, $expencecatagorys)) {

                        $expencecatagorys[$name][] = array(
                            'type' => 'Expense',
                            'link' => '/Reciepts/Edit/' . $receipt->id,
                            'linkedto' => $receipt->LinkedAccountName(),
                            //'linkedid' => $receipt->getClientID(),
                            'date' => $receipt->date->toDateString(),
                            'amount' => $amount,
                        );

                        $expencecatagorystotal[$name] = $expencecatagorystotal[$name] + floatval($amount);
                    } else {

                        $expencecatagorys[$name][] = array(
                            'type' => 'Expense',
                            'link' => '/Reciepts/Edit/' . $receipt->id,
                            'linkedto' => $receipt->LinkedAccountName(),
                            //'linkedid' => $receipt->getClientID(),
                            'date' => $receipt->date->toDateString(),
                            'amount' => $amount,
                        );

                        $expencecatagorystotal[$name] = floatval($amount);
                    }
                    $expencestotal = floatval($expencestotal) + floatval($amount);

                }
            }
        }

        if (count($cheques) > 0) {
            foreach ($cheques as $key => $value) {
                if ($value->printed === null) {
                    //discard any checks not printed
                    $cheques->forget($key);
                } else {
                    foreach ($value->catagorys as $name => $amount) {
                        if (array_key_exists($name, $expencecatagorys)) {

                            $expencecatagorys[$name][] = array(
                                'type' => 'Check',
                                'link' => '/Checks/Edit/' . $value->id,
                                'linkedto' => $value->LinkedAccountName(),
                                //'linkedid' => $value->getClientID(),
                                'date' => $value->date->toDateString(),
                                'amount' => $amount,
                            );

                            $expencecatagorystotal[$name] = $expencecatagorystotal[$name] + floatval($amount);
                        } else {

                            $expencecatagorys[$name][] = array(
                                'type' => 'Check',
                                'link' => '/Checks/Edit/' . $value->id,
                                'linkedto' => $value->LinkedAccountName(),
                                //'linkedid' => $value->getClientID(),
                                'date' => $value->date->toDateString(),
                                'amount' => $amount,
                            );

                            $expencecatagorystotal[$name] = floatval($amount);
                        }
                        $expencestotal = floatval($expencestotal) + floatval($amount);
                    }
                }
            }
        }


        $profitcatagorys = array();
        $profitcatagorystotal = array();
        $incometotal = floatval(0);
        foreach ($deposits as $deposit) {
            foreach ($deposit->catagorys as $name => $amount) {
                if (array_key_exists($name, $profitcatagorys)) {

                    $profitcatagorys[$name][] = array(
                        'linkedto' => $deposit->getFrom(),
                        'id' => $deposit->id,
                        'date' => $deposit->date->toDateString(),
                        'amount' => $amount,
                    );

                    $profitcatagorystotal[$name] = $profitcatagorystotal[$name] + floatval($amount);
                } else {

                    $profitcatagorys[$name][] = array(
                        'linkedto' => $deposit->getFrom(),
                        'id' => $deposit->id,
                        'date' => $deposit->date->toDateString(),
                        'amount' => $amount,
                    );

                    $profitcatagorystotal[$name] = floatval($amount);
                }
                $incometotal = floatval($incometotal) + floatval($amount);
            }
        }

        ksort($profitcatagorys);
        ksort($expencecatagorys);

        $profitloss = $incometotal - $expencestotal;

        $data = compact('startdate', 'enddate', 'incometotal', 'profitloss', 'profitcatagorys', 'profitcatagorystotal', 'expencecatagorys', 'expencecatagorystotal', 'expencestotal', 'receipts', 'cheques', 'depreciationtotal');

        return View::make('Reporting.Interactive.profitandloss', $data);

    }


    /*

    private function ShowProfitLossGraphReport($startdate, $enddate, $producepdf)
    {

        $deposits = $this->data($startdate, $enddate, Deposit::class, 'date');

        $receipts = $this->data($startdate, $enddate, Receipt::class, 'date');

        $cheques = $this->data($startdate, $enddate, Check::class, 'date');

        $expences = array();
        $expencestotal = floatval(0);
        if (count($receipts) > 0) {
            foreach ($receipts as $receipt) {
                foreach ($receipt->catagorys as $name => $amount) {
                    if (array_key_exists($name, $expences)) {
                        $expences[$name] = $expences[$name] + floatval($amount);
                    } else {
                        $expences[$name] = floatval($amount);
                    }
                    $expencestotal = floatval($expencestotal) + floatval($amount);
                }
            }
        }

        if (count($cheques) > 0) {
            foreach ($cheques as $cheque) {
                if ($cheque->printed != null) {
                    foreach ($cheque->catagorys as $name => $amount) {
                        if (array_key_exists($name, $expences)) {
                            $expences[$name] = $expences[$name] + floatval($amount);
                        } else {
                            $expences[$name] = floatval($amount);
                        }
                        $expencestotal = floatval($expencestotal) + floatval($amount);
                    }
                }
            }
        }


        $expencespie = PChartHelper::ExpensePage($expences, "Expenses");

        #return $expencespie;
        #$expencesbar = PChartHelper::BarChart($expences, "Cost", "Expences");

        $sales = floatval(0);
        foreach ($deposits as $deposit) {
            if (count($deposit->depositlinks) > 0) {
                $sales = floatval($sales) + floatval($deposit->amount);
            }
        }

        $incometotal = $sales;

        $profitloss = $incometotal - $expencestotal;

        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Profit & Loss Statement";

        $data = compact('companyinfo', 'companyaddress', 'startdate', 'enddate', 'reportname', 'incometotal', 'profitloss', 'sales', 'expencespie', 'expencestotal', 'expences');

        ini_set('memory_limit', '-1');

        return $this->DrawReport($producepdf, "pdf.Reports.ProfitLossGraph", $data);

    }*/
}