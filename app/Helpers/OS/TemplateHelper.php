<?php
namespace App\Helpers\OS;

use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;

use App\Models\OS\Report;
use App\Models\ClientReport;
use App\Models\VendorReport;

class TemplateHelper
{

    public static function MigrateOldTables(){

        $clientreports = ClientReport::All();
        foreach($clientreports as $report){

            $newreport = New Report;
            $newreport->name = $report->name;
            $newreport->created_by = $report->user_id;
            $newreport->reportdata = $report->reportdata;
            $newreport->client_id = $report->client_id;
            $newreport->vendor_id = null;
            $newreport->user_id = null;
            $newreport->deleted_at = $report->deleted_at;
            $newreport->created_at = $report->created_at;
            if($report->originalreport_id === $report->id){
                $newreport->originalreport_id = null;
            }else{
                $newreport->originalreport_id = $report->originalreport_id;
            }

            $newreport->save();

        }

        $vendorreports = VendorReport::All();
        foreach($vendorreports as $report){

            $newreport = New Report;
            $newreport->name = $report->name;
            $newreport->created_by = $report->user_id;
            $newreport->reportdata = $report->reportdata;
            #$newreport->originalreport_id = $report->originalreport_id;
            $newreport->client_id = null;
            $newreport->vendor_id = $report->vendor_id;
            $newreport->user_id = null;
            $newreport->deleted_at = $report->deleted_at;
            $newreport->created_at = $report->created_at;

            if($report->originalreport_id === $report->id){
                $newreport->originalreport_id = null;
            }else{
                $newreport->originalreport_id = $report->originalreport_id;
            }

            $newreport->save();

        }

    }

    /**  now unused?
    public static function GeneratePDF($data)
    {

        $filepath = app_path().'/TestFiles/';
        if(Auth::check() == false){
            $tempfilename = hash('sha1', "public")."-".hash('sha1', date(DATE_RFC2822)).'.pdf';
        }else{
            $tempfilename = hash('sha1', Auth::user()->email)."-".hash('sha1', date(DATE_RFC2822)).'.pdf';
        }

        $file = $filepath . $tempfilename;

        $pdf = PDF::loadView('pdf.Templates.Report', compact('data'));

        $pdf->save($file);

        return $file;

    }
    **/
}