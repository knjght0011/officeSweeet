<?php
namespace App\Helpers\OS\Financial;

use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade as PDF;

use App\Helpers\OS\ReportingHelper;

use App\Models\Deposit;
use App\Models\Client;
use App\Models\Quote;

use App\Mail\SendQuote;

class ClientOverviewHelper
{

    public static function Data($client, $startdate = null, $enddate = null, $itemized = false)
    {

        #$client = Client::where('id', '=', $clientid)->first();

        if(count($client) === 1){

            $total = floatval(0);
            $startbalence = floatval(0);
            $data = array();

            if($startdate === null){
                #$quotes = $client->getInvoices();
                $quotes = Quote::where('finalized', '=', '1')
                    ->where('client_id', '=', $client->id)
                    ->get();

                $payments = Deposit::get();
            }else{
                $quotes = Quote::whereBetween('finalizeddate', [Carbon::parse($startdate), Carbon::parse($enddate)])
                    ->where('client_id', '=', $client->id)
                    ->get();

                $payments = Deposit::whereBetween('date', [Carbon::parse($startdate), Carbon::parse($enddate)])
                    ->get();
            }

            if($itemized){
                foreach ($quotes as $quote) {
                    foreach ($quote->quoteitem as $item){
                        $total = $total - $item->getTotalRAW();

                        $type = 'Invoice ' . $quote->getQuoteNumber() . ' - ' . $item->description;

                        $array = array(
                            'info' => 'd',
                            'date' => $quote->finalizeddate->toDateString(),
                            'type' => $type,
                            'credit' => "",
                            'debt' => "$" . $item->getTotal(),
                            'amount' => $item->getTotalRAW(),
                            'symbol' => '-'
                        );

                        $data[] = $array;
                    }
                }
            }else{
                foreach ($quotes as $quote) {

                    $total = $total - $quote->getTotalFloat();

                    $array = array(
                        'info' => 'd',
                        'date' => $quote->finalizeddate->toDateString(),
                        'type' => 'Invoice - ' . $quote->getQuoteNumber(),
                        'credit' => "",
                        'debt' => "$" . number_format($quote->getTotalFloat() , 2),
                        'amount' => $quote->getTotalFloat(),
                        'symbol' => '-'
                    );

                    $data[] = $array;

                }
            }



            foreach ($payments as $key => $value) {
                if($value->getClientID() != $client->id){
                    $payments->forget($key);
                }else{
                    $total = $total + $value->amount;

                    if($value->method === "Check"){
                        if($value->identifier != null){
                            $type = 'Payment - Check Number: ' . $value->identifier;
                        }else{
                            $type = 'Payment - ' . $value->method;
                        }
                    }else{
                        $type = 'Payment - ' . $value->method;
                    }

                    $array = array(
                        'info' => 'p',
                        'date' => $value->date->toDateString(),
                        'type' => $type,
                        'debt' => "",
                        'credit' => "$" . number_format($value->amount , 2),
                        'amount' => $value->amount,
                        'symbol' => ''
                    );

                    $data[] = $array;
                }
            }

            usort($data, 'self::date_compare');

            $running = floatval(0);

            foreach($data as $key => $value){
                if($value['info'] === 'p'){
                    $running = $running + floatval($value['amount']);
                }else{
                    $running = $running - floatval($value['amount']);
                }

                $value['running'] = $running;

                $data[$key] = $value;
            }

            return $data;

        }else{
            return "Error: Unknown Client";
        }
    }


    private static function date_compare($a, $b)
    {
        $t1 = strtotime($a['date']);
        $t2 = strtotime($b['date']);
        return $t1 - $t2;
    }

    //should not longer be used.
    public static function Email($ClientID, $contact, $body, $subject){

        $client = Client::where('id', '=', $ClientID)->first();

        $reportdata = self::Data($client);

        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Client Statement";

        $data = compact('companyinfo', 'companyaddress', 'reportname', 'total', 'reportdata', 'client', 'startbalence');

        $pdf = PDF::loadView('pdf.Reports.PaymentsClient', $data);

        Mail::to($contact->email)->send(new SendQuote($body, $subject, $pdf, null, "Statement"));

    }

    public static function PDF($client){

        $reportdata = self::Data($client, null, null, true);

        $companyinfo = ReportingHelper::generateCompanyInfoArray();
        $companyaddress = ReportingHelper::generateCompanyAddressArray();
        $reportname = "Client Statement";

        $data = compact('companyinfo', 'companyaddress', 'reportname', 'total', 'reportdata', 'client', 'startbalence');

        return PDF::loadView('pdf.Reports.PaymentsClient', $data);

    }

    public static function PDFBase64($client){
        return base64_encode(self::PDF($client)->stream());
    }


}
