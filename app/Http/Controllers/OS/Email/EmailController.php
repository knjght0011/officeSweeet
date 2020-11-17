<?php

namespace App\Http\Controllers\OS\Email;

use App\Helpers\OS\Financial\ClientOverviewHelper;
use App\Helpers\OS\NotificationHelper;
use App\Http\Controllers\Controller;

use App\Mail\EmailNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;

use App\Helpers\OS\Users\UserHelper;
use App\Helpers\OS\SettingHelper;

use App\Models\Client;
use App\Models\Template;
use App\Models\Vendor;
use App\Models\OS\Email\EmailTemplate;
use App\Models\OS\Email\Email;
use mysql_xdevapi\Exception;


class EmailController extends Controller
{

    public function SendEmailFromPopupCompose()
    {

        $data = array(
            'contact_id' => Input::get('contact_id'),
            'recipient_id' => Input::get('recipient_id'),
            'email' => Input::get('email'),
            'contact_type' => Input::get('contact_type'),
            'subject' => Input::get('subject'),
            'body' => Input::get('body'),
            'link_id' => Input::get('link_id'),
            'type' => Input::get('type'),
        );

        $email = new Email;
        //$email->email = $data['email'];
        $email->subject = $data['subject'];
        $email->body = $data['body'];
        $email->type = $data['type'];
        $email->contact_id = $data['contact_id'];
        $email->contact_type = $data['contact_type'];
        $email->email = $data['email'];
        $email->linked_id = 1;
        $email->save();
        $email->Send();
        // Send Notifications
        NotificationHelper::CreateNotification('You have new Email!', 'Description: You have new email, please check it.', $data['recipient_id'], 'newEmail', Auth::user()->id);
        return ['status' => 'OK'];

    }

    public function SendEmail()
    {

        $data = array(
            'contact_id' => Input::get('contact_id'),
            'contact_type' => Input::get('contact_type'),
            'subject' => Input::get('subject'),
            'body' => Input::get('body'),
            'link_id' => Input::get('link_id'),
            'type' => Input::get('type'),
        );

        $email = new Email;
        //$email->email = $data['email'];
        $email->subject = $data['subject'];
        $email->body = $data['body'];
        $email->type = $data['type'];
        $email->contact_id = $data['contact_id'];
        $email->contact_type = $data['contact_type'];

        $email->email = $email->GetContact()->email;

        $email->linked_id = $data['link_id'];
        if ($email->linked_id === null) {

            $email->save();
            $email->Send();

            return ['status' => 'OK'];

        } else {

            $link = $email->GetLink();
            if (count($link) === 1) {

                if ($email->type === "Document" or $email->type === "PurchaseOrder" or $email->type === "Quote") {
                    $email->attachment = $link->PDFBase64();
                }

                if ($email->type === "Overview") {
                    $email->attachment = ClientOverviewHelper::PDFBase64($link);
                }

                $email->save();
                $email->Send();

                return ['status' => 'OK'];

            } else {
                return ['status' => 'linknotfound'];
            }
        }
    }

    public function SendPOEmail()
    {

        $data = array(
            'email' => Input::get('email'),
            'link_id' => Input::get('link_id'),
            'body' => Input::get('body'),
        );


        $email = new Email;
        $email->email = $data['email'];
        $email->subject = 'Attn: New Order';
        $email->body = $data['body'];;
        $email->type = 'PurchaseOrder';
        $email->linked_id = $data['link_id'];

        $link = $email->GetLink();
        if (count($link) === 1) {
            $email->attachment = $link->PDFBase64();
        }

        $email->save();
        $email->Send();

        return ['status' => 'OK'];
    }

    public function SendNotification()
    {
        $data = array(
            'email' => Input::get('email'),
            'subject' => Input::get('subject'),
            'body' => Input::get('body'),
        );

        try {
            Mail::raw('Test Email', function ($message) {
                $message->to('movian@gmx.com');
                $message->from('NoReply@officesweeet.com');
                $message->subject('Test Email');

            });
            return ['status' => 'OK'];
        } catch (Exception $e) {
            return ['status' => $e];
        }
    }

    public function BulkSend()
    {

        $data = array(
            'recipients' => Input::get('recipients'),
            'subject' => Input::get('subject'),
            'link_id' => Input::get('templateid'),
            'type' => "EmailTemplate",
        );

        $template = EmailTemplate::where('id', $data['link_id'])->first();

        if (count($template) === 1) {

            foreach ($data['recipients'] as $recipient) {

                $email = new Email;
                $email->email = $recipient['email'];
                $email->subject = $data['subject'];
                $email->body = $template->content;
                $email->type = $data['type'];
                $email->linked_id = $data['link_id'];

                $email->contact_id = $recipient['id'];
                $email->contact_type = $recipient['type'];

                $email->save();
                $email->Send();

            }

            return ['status' => 'OK'];

        } else {
            return ['status' => 'notfound'];
        }


    }

    public function TemplateTest($subdomain, $templateid, $emailaddress)
    {

        $data = array(
            'email' => $emailaddress,
            'subject' => "Template Test",
            'body' => "",
            'link_id' => $templateid,
            'type' => "Template",
        );

        $email = new Email;
        $email->email = $data['email'];
        $email->subject = $data['subject'];
        $email->body = $data['body'];
        $email->type = $data['type'];

        $email->linked_id = $data['link_id'];
        if ($email->linked_id === null) {

            $email->save();
            $email->Send();

            return ['status' => 'OK'];

        } else {

            $link = $email->GetLink();
            if (count($link) === 1) {
                $email->save();
                $email->Send();

                return ['status' => 'OK'];

            } else {
                return ['status' => 'linknotfound'];
            }
        }
    }

    public function ResendEmail()
    {

        $email = Email::where('id', Input::get('id'))->first();
        if (count($email) === 1) {
            $email->Send();
            return ['status' => 'OK'];
        } else {
            return ['status' => 'notfound'];
        }
    }

    public function PreviewEmail($subdomain, $id)
    {

        $email = Email::where('id', $id)->first();
        if (count($email) === 1) {
            switch ($email->type) {
                case "Quote":
                case "Overview":
                case "SignedDocument":
                case "Document":
                case "PurchaseOrder":

                    return View::make('Emails.Customer.file')
                        ->with('body', $email->body)
                        ->with('token', $email->token);


                case "Invoice":

                    return View::make('Emails.Customer.quote')
                        ->with('body', $email->body)
                        ->with('token', $email->GetLink()->token)
                        ->with('type', $email->GetLink()->getType());

                case "EmailTemplate":

                    return View::make('Emails.Customer.emailtemplate')
                        ->with('content', $email->body);

                case "SigningRequest":

                    return View::make('Emails.Customer.signingrequest')
                        ->with('contact', $email->GetContact())
                        ->with('signing', $email->GetLink());

                default:
                    return Response::make(view('errors.404'), 404);
            }
        } else {
            return Response::make(view('errors.404'), 404);
        }

    }

    public function ViewAttachment($subdomain, $id)
    {

        $email = Email::where('id', $id)->first();
        if (count($email) === 1) {
            if ($email->attachment === null) {
                return Response::make(view('errors.404'), 404);
            } else {
                return response($email->Attachment())->header('content-type', 'application/pdf')->header('Content-Disposition', 'inline; filename="file.pdf"');
            }
        } else {
            return Response::make(view('errors.404'), 404);
        }
    }

    /*
    public function GroupSend(){

        $clients = Client::all();
        $vendors = Vendor::all();
        $employees = UserHelper::GetAllUsers();

        $templates = EmailTemplate::all();

        return View::make('OS.Email.view')
                    ->with('clients', $clients)
                    ->with('vendors', $vendors)
                    ->with('employees', $employees)
                    ->with('templates', $templates);
    }
    */

    public function Overview()
    {

        $emails = Email::orderBy('id', 'desc')->get();

        if (Auth::user()->hasPermission('bulk_email_permission')) {

            $clients = Client::all();
            $vendors = Vendor::all();
            $employees = UserHelper::GetAllUsers();

            $templates = EmailTemplate::all();

            return View::make('OS.Email.main')
                ->with('clients', $clients)
                ->with('vendors', $vendors)
                ->with('employees', $employees)
                ->with('templates', $templates)
                ->with('emails', $emails);

        } else {

            return View::make('OS.Email.main')
                ->with('emails', $emails);
        }


    }

    public function CompanyLogo()
    {
        $base64 = \App\Helpers\OS\SettingHelper::GetSetting('companylogo');
        if ($base64 === null) {
            return "none";
        } else {
            $split = explode(",", $base64);
            #return var_dump($split);
            $image = base64_decode($split[1]);
            return response($image)->header('content-type', 'image/png')->header('Content-Disposition', 'inline; filename="CompanyLogo.png"');
        }
    }

    public function viewAttachmentPublic($subdomain, $token)
    {
        return View::make('OS.Public.Email.document')
            ->with('token', $token);
    }

    public function viewAttachmentPublicPDF($subdomain, $token)
    {

        $email = Email::where('token', $token)->first();
        if (count($email) === 1) {
            if ($email->attachment === null) {
                return Response::make(view('errors.404'), 404);
            } else {
                return response($email->Attachment())->header('content-type', 'application/pdf')->header('Content-Disposition', 'inline; filename="file.pdf"');
            }
        } else {
            return Response::make(view('errors.404'), 404);
        }
    }

}

