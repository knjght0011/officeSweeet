<?php

namespace App\Http\Controllers\OS\Templates;

use App\Helpers\OS\NotificationHelper;

use App\Models\OS\EmailSubjects;
use App\Models\OS\PurchaseOrders\PurchaseOrder;
use App\Models\OS\Report;
use App\Models\OS\Templates\Signing;
use App\Models\OS\Templates\Signature;
use App\Models\OS\Templates\SigningPage;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;

use Anam\PhantomMagick\Facades\Converter;
use Google;
use Imagick;
use Session;

use Elibyy\TCPDF\Facades\TCPDF as PDF;
use Carbon\Carbon;

class SigningController extends Controller {

    //Step 1: setup doc for signing
    public function Setup($subdomain, $id)
    {
        $report = Report::where('id', $id)->first();

        if(count($report) === 1){

            $pdf = $report->PDFBase64();

            $contacts = $report->Contacts();

            return View::make('OS.Signing.setup')
                ->with('report', $report)
                ->with('contacts', $contacts)
                ->with('pdf', $pdf);

        }else{
            return "Not Found";
        }
    }

    //Step 2: save setup doc and send to all relevent contacts
    public function SavePositions(){

        $report = Report::where('id', Input::get('report-id'))->first();

        if(count($report) === 1){

            $signing = new Signing;
            $signing->name = $report->name;
            $signing->file = "";
            $signing->originalreport_id = $report->id;
            $signing->created_by = Auth::user()->id;
            $signing->token = "";
            $signing->client_id = $report->client_id;
            $signing->vendor_id = $report->vendor_id;
            $signing->user_id = $report->user_id;
            $signing->sign = 1;
            $signing->save();

            $pages = Input::get('pages');
            $signingpages = [];
            foreach($pages as $key => $value){

                $signingpages[$key] = new SigningPage;
                $signingpages[$key]->file = $value;
                $signingpages[$key]->pageindex = $key;
                $signingpages[$key]->signing_id = $signing->id;
                $signingpages[$key]->save();

            }

            $markers = Input::get('markers');
            $signatures = [];
            foreach($markers as $key => $marker){

                $signatures[$key] = new Signature;
                $signatures[$key]->left = $marker['left'];
                $signatures[$key]->top = $marker['top'];
                $signatures[$key]->width = $marker['width'];
                $signatures[$key]->height = $marker['height'];

                $signatures[$key]->signingpage_id = $signingpages[$marker['page'] - 1]->id;

                if(isset($marker['contactid'])){
                    switch ($marker['type']) {
                        case "client":
                            $signatures[$key]->clientcontact_id = $marker['contactid'];
                            break;
                        case "vendor":
                            $signatures[$key]->vendorcontact_id = $marker['contactid'];
                            break;
                        case "employee":
                            $signatures[$key]->user_id = $marker['contactid'];
                            break;
                    }
                }

                $signatures[$key]->save();

            }

            //need to send mail to these contacts
            foreach($signing->AllSignees() as $contact){
                $signing->SendSigningRequest($contact);
                if ($contact instanceof User) {
                    NotificationHelper::CreateNotification("Signing Request", "Signing Request", "/Public/Document/Signing/". $signing->token ."/". $contact->token, "link", $contact->id);
                }
            }

            return ['status' => 'OK'];

        }else{
            return ['status' => 'notfound'];
        }
    }

    //Step 3: Public viewing portal for signing docs
    public function viewSignDocumentPublic($subdomain, $documenttoken, $contacttoken)
    {
        $signing = Signing::where('token', $documenttoken)->first();

        if(count($signing) === 1){
            if($signing->sign === 1){
                $contact = $signing->HasContact($contacttoken, true);
                if($contact != null) {

                    return View::make('OS.Public.Documents.sign')
                        ->with('contact', $contact)
                        ->with('signing', $signing);

                }else{
                    return Response::make(view('errors.404'), 404);
                }
            }else{
                return Response::make(view('errors.404'), 404);
            }
        }else{
            return Response::make(view('errors.404'), 404);
        }
    }

    //Step 4: Save signature info
    public function SubmitSignatures(){

        $data = array(
                    'signatures' => Input::get('signatures'),
                    'signtoken' => Input::get('signtoken'),
                    'contacttoken' => Input::get('contacttoken'),
                );

        foreach($data['signatures'] as $signature){

            $signaturetable = Signature::where('id', $signature['id'])->first();

            if(count($signaturetable) === 1){
                if($signaturetable->CheckTokens($data['signtoken'],$data['contacttoken'])){

                    if($signature['signed'] === "true"){
                        $signaturetable->signature = $signature['signature'];
                        $signaturetable->signeddate = Carbon::parse($signature['date']);
                        $signaturetable->digittype = $signature['digittype'];
                        $signaturetable->digits = $signature['digits'];
                        $signaturetable->save();
                    }
                }
            }
        }

        $signing = Signing::where('token', $data['signtoken'])->first();

        if(count($signing) === 1){
            NotificationHelper::CreateNotification('Document Signed', 'Document Signed', $signing->id, 'docsigned', $signing->user_id);
        }

        return ['status' => 'OK'];

    }

    //Step 5: Approve Signatures
    public function ApproveSignatures($subdomain, $id)
    {

        $signing = Signing::where('id', $id)
            ->with('client')
            ->with('client.contacts')
            ->first();

        if(count($signing) === 1){

            return View::make('OS.Signing.approve')
                ->with('signing', $signing);

        }else{
            return "Not Found";
        }
    }

    //Step 6: Save signature images
    public function SignatureImage(){

        $signature = Signature::where('id', Input::get('id'))->first();

        if(count($signature) === 1){

            $signature->image = Input::get('image');
            $signature->save();

            return ['status' => 'OK'];
        }else{
            return ['status' => 'notfound'];
        }


    }

    //Step 7: Create Final Document
    public function Approve(){

        $signing = Signing::where('id', Input::get('id'))->first();

        if(count($signing) === 1){

            if($signing->AllApproved()){

                //Start PDF
                PDF::SetAutoPageBreak(false, 0);

                foreach($signing->pages as $page){
                    //make image for page
                    $pageimage = new Imagick();
                    $pageimage->readImageBlob($page->Base64Decode());

                    //get page dimentions
                    $width = $pageimage->getImageWidth();
                    $height = $pageimage->getImageHeight();

                    foreach($page->signatures as $signature){
                        //make image for signature
                        $overlay = new Imagick();
                        $overlay->setSize($signature->ElementWidth($width), $signature->ElementHeight($height));
                        $overlay->readImageBlob($signature->Base64Decode());

                        //overlay signature
                        $pageimage->compositeImage($overlay, Imagick::COMPOSITE_DEFAULT, $signature->LeftPosition($width), $signature->TopPosition($height));
                        $pageimage->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
                    }

                    PDF::AddPage();
                    PDF::Image('@'.$pageimage->getImagesBlob(), 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
                    PDF::setPageMark();

                    $page->file = "data:image/png;base64," . base64_encode($pageimage->getImagesBlob());
                    $page->save();
                }

                $signing->file = base64_encode(PDF::Output('quotation.pdf', 'S'));
                $signing->sign = 2;
                $signing->save();

                //need to send mail to these contacts
                foreach($signing->AllSignees() as $contact){
                    $signing->SendMeToNew($contact);
                }

                return ['status' => 'OK'];
            }else{
                return ['status' => 'notready'];
            }
        }else{
            return ['status' => 'notfound'];
        }
    }



    //basic emailing document functions for Unsigned documents
    /* should be moved to the new email table */
    public static function EmailDocument(){

        $data = array(
            'reportid'=> Input::get('reportid'),
            'email'=> Input::get('email'),
            'emailtest'=> "staylor@officesweeet.com",
            'subject'=> Input::get('subject'),
            'body'=> Input::get('body'),
            'mode' => Input::get('mode'),
        );

        $subject = EmailSubjects::where('subject', $data['subject'])->get();
        if(count($subject) === 0){
            $newsubject = new EmailSubjects;
            $newsubject->subject = $data['subject'];
            $newsubject->save();
        }

        switch ($data['mode']) {
            case "send":
                return self::SendDocument($data);
                break;
            case "sendpo":
                return self::SendPurchaseOrder($data);
                break;
            case "resend":
                return self::ResendDocument($data);
                break;
            default:
                return ['status' => 'nomode'];
        }
    }

    public static function SendPurchaseOrder($data){
        $po = PurchaseOrder::where('id', $data['reportid'])->first();

        if(count($po) === 1){

            $signing = new Signing;
            $signing->name = "Purchase Order number: " . $po->POnumber();
            $signing->file = $po->PDFBase64();
            $signing->email = $data['email'];
            $signing->created_by = Auth::user()->id;
            $signing->purchaseorder_id = $po->id;
            $signing->client_id = null;
            $signing->vendor_id = $po->vendor_id;
            $signing->user_id = null;
            $signing->token = "";
            $signing->save();

            $signing->SendMeTo($data['email'], $data['subject'], $data['body']);

            return ['status' => 'OK'];

        }else{
            return ['status' => 'reportnotfound'];
        }
    }

    public static function SendDocument($data){
        $report = Report::where('id', $data['reportid'])->first();

        if(count($report) === 1){

            $signing = new Signing;
            $signing->name = $report->name;
            $signing->file = $report->PDFBase64();
            $signing->email = $data['email'];
            $signing->created_by = Auth::user()->id;
            $signing->originalreport_id = $report->id;
            $signing->client_id = $report->client_id;
            $signing->vendor_id = $report->vendor_id;
            $signing->user_id = $report->user_id;
            $signing->token = "";
            $signing->save();

            $signing->SendMeTo($data['email'], $data['subject'], $data['body']);

            return ['status' => 'OK'];

        }else{
            return ['status' => 'reportnotfound'];
        }
    }

    public static function ResendDocument($data){
        $signing = Signing::where('id', $data['reportid'])->first();

        if(count($signing) === 1){

            $signing->email = $data['email'];

            $signing->SendMeTo($data['email'], $data['subject'], $data['body']);
            $signing->save();

            return ['status' => 'OK'];

        }else{
            return ['status' => 'signnotfound'];
        }
    }


    public function viewDocumentPublic($subdomain, $token)
    {
        return View::make('OS.Public.Documents.document')
            ->with('token', $token);
    }

    public function viewDocumentPublicPDF($subdomain, $token){

        $signing = Signing::where('token', $token)->first();
        if(count($signing) === 1){
            if($signing->file === ""){
                return Response::make(view('errors.404'), 404);
            }else{
                return response($signing->File())->header('content-type','application/pdf') ->header('Content-Disposition', 'inline; filename="file.pdf"');
            }
        }else{
            return Response::make(view('errors.404'), 404);
        }
    }

    public function ShowSignPDF($subdomain, $id){
        $signing = Signing::where('id', $id)->first();
        if(count($signing) === 1){
            return response($signing->File())->header('content-type','application/pdf') ->header('Content-Disposition', 'inline; filename="file.pdf"');
        }else{
            return "Unknown ID";
        }
    }

    /**
     *  Shitty Tests saved fro prosterity
     *
        public static function ShowSignTest(){

        //$path =

        //$report = Report::where('id',"68")->first();
        //$data = $report->reportdata;
        //$html =  View::make('pdf.Templates.Report', compact('data'))->render();

        #$browsershot = new Browsershot();
        #$browsershot->html($html);
        #$browsershot->save($path);


        $config = new \Illuminate\Config\Repository;
        $filesystem = new \Illuminate\Filesystem\FilesystemManager;

        $browsershot = new \BrianFaust\Browsershot\Browsershot($config, $filesystem);
        $browsershot
        ->setUri('http://www.google.com')
        ->setPreset('apple_iphone_7')
        // ->setWidth(375)
        // ->setHeight(667)
        ->setTimeout(5000)
        ->save(storage_path('app/browsershots/google.jpg'));

        $contents = base64_encode(file_get_contents($path));


        //$conv = new \Anam\PhantomMagick\Converter();
        //$conv->addPage($html)
        //   ->toPng();
        #->save('/your/destination/path/google.png');
        //$conv->serve();

        //$file = FileStore::where('id', '38')->first();
        //$decoded = base64_decode($file->Base64Decode());

        //$fp_pdf = fopen(app_path() . "/TestFiles/document.pdf",'rb');

        //$imagick = new Imagick();
        // Reads image from PDF
        //$imagick->readImageFile($fp_pdf);
        // Writes an image
        //$imagick->writeImages(app_path() . "/TestFiles/image.jpeg", true);

        //$data = file_get_contents(app_path() . "/TestFiles/image.jpeg");
        //$base64 = base64_encode($data);

        $signing = Signing::where('id', '9')->first();

        return View::make('OS.Signing.view')
        ->with('signing', $signing);

    }
 **/



}
