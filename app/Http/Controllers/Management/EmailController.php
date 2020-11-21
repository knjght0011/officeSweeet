<?php

namespace App\Http\Controllers\Management;

#use Session;
use Illuminate\Support\Facades\View;
#use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
#use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Artisan;

use App\Models\Management\Email;
#use App\Models\User;

use Mail;

use App\Jobs\Provisioning\CreateDB;
use App\Jobs\Provisioning\MigrateDB;
use App\Jobs\Provisioning\SeedDB;

use App\Models\Management\Account;

use App\Helpers\MailHelper;
use App\Helpers\AccountHelper;
 
class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function IncomingMail()
    {
        $fields = "";
        foreach(Input::all() as $key => $value){
            $fields = $fields . "," . $key;
        }
        Cache::put('email-fields', $fields);
        
        $data = array(
            'recipient' => explode(',', Input::get('recipient')),
            'sender' => Input::get('sender'),
            'subject' => Input::get('subject'),
            'from' => Input::get('from'),
            'received' => Input::get('Received'),
            'message_id' => Input::get('Message-Id'),
            #'date' => Carbon::createFromFormat('D, d M Y H:i:s P', Input::get('Date')), #Fri, 26 Apr 2013 11:50:29 -0700
            'date' => Carbon::parse(Input::get('Date')), #Fri, 26 Apr 2013 11:50:29 -0700
            'user_agent' => Input::get('User-Agent'),
            'mime_version' => Input::get('Mime-Version'),
            'to' => explode(',', Input::get('To')),
            'references' => Input::get('References'),
            'in_reply_to' => Input::get('In-Reply-To'),
            'x_mailgun_variables' => json_decode(Input::get('X-Mailgun-Variables')),
            'content_type' => Input::get('Content-Type'),
            'sender_two' => Input::get('Sender'),
            'message_headers' => Input::get('message-headers'),
            'timestamp' => Input::get('timestamp'),
            'token' => Input::get('token'),
            'signature' => Input::get('signature'),
            'body_plain' => Input::get('body-plain'),
            'body_html' => Input::get('body-html'),    
            'stripped_html' => Input::get('stripped-html'),
            'stripped_text' => Input::get('stripped-text'),
            'stripped_signature' => Input::get('stripped-signature'),
            'content_id_map' => Input::get('content-id-map'), 
            'attachment_count' => intval(Input::get('attachment-count')), 
        );
        
        if(MailHelper::hashcheck($data['timestamp'], $data['token'], $data['signature'] )){
               

            $debug = var_export(Input::all(), true);
            Cache::forever('mail', $debug);
            
            $attachments = array();

            $email = MailHelper::SaveMail($data);

            if(Input::file('attachment-1') !== null){
                MailHelper::SaveAttachment($email->id, Input::file('attachment-1'));
            }

            #add any new subs to account table
            if($email->subject === "Request for subscription"){
                $infoarray = AccountHelper::ParseEmail($email->body_plain);
                if($infoarray !== false){
                    $account = AccountHelper::AddToAccountTable($infoarray['subdomain'], $infoarray['subdomain'], $infoarray['subdomain'], $infoarray['DBPassword'], $infoarray);
                }
            }
            
            #if($data['attachment_count'] > 0){
            #    for ($x = 1; $x <= $data['attachment_count']; $x++) {
            #        #
            #    } 
            #}

            return response('Success', 200);
            
        }else{
            
            return;
        }
    }    
    
    public function list()
    {
        $email = Auth::user()->email;
        $emailType = ['ReplyEmail','EmailFromPopupModalToClient','EmailFromPopupModalToEmployee','EmailFromPopupModalToVendor'];
        $mails = \App\Models\OS\Email\Email::where('email', 'like', '%'.$email.'%')
            ->whereIn('type',$emailType)
            ->orderBy('updated_at', 'desc')->get();
        return View::make('Emails.Inbox.index')
            ->with('email', $email)
            ->with('mails', $mails);
    }

    public function sentList()
    {
        $email = Auth::user()->email;
        $emailType = ['ReplyEmail','EmailFromPopupModalToClient','EmailFromPopupModalToEmployee','EmailFromPopupModalToVendor'];
        $mails = \App\Models\OS\Email\Email::where('sender', 'like', '%'.$email.'%')
            ->whereIn('type',$emailType)
            ->orderBy('updated_at', 'desc')->get();
        return View::make('Emails.Sent.index')
            ->with('email', $email)
            ->with('mails', $mails);
    }

    public function showById($subdomain, $id)
    {
        $mail = \App\Models\OS\Email\Email::find($id);
        $mail->touch();
        return View::make('Emails.Inbox.show')
            ->with('mail', $mail);
    }

    public function sentShowById($subdomain, $id)
    {
        $mail = \App\Models\OS\Email\Email::find($id);
        $mail->touch();
        return View::make('Emails.Sent.show')
            ->with('mail', $mail);
    }

    public function index($subdomain, $email)
    {
        #$users = User::all();
        $mails = Email::where('recipient', 'like', '%'.$email.'%')->get();

        return View::make('Management.Mail.index')
            ->with('email', $email)
            ->with('mails', $mails);
    }

    public function redirect()
    {
        return Redirect::to('/Mail/MailBox/' . Auth::user()->email );
    }
    
    public function show($subdomain, $id)
    {
        $mail = Email::find($id);
        $mail->MarkReadByMe();

        return View::make('Management.Mail.show')
            ->with('mail', $mail);
    }

    public function body($subdomain, $id)
    {
        $mail = Email::find($id);
        $mail->MarkReadByMe();

        if($mail->body_html === null) {
            return $mail->body_plain;
        }else {
            return $mail->body_html;
        }
    }
    
    public function send()
    {
        $data = array(
            'from' => Input::get('from'),
            'to' => Input::get('to'),
            'subject' => Input::get('subject'),
            'body' => Input::get('body'),
        );
        #$data = Array("test" => "blargh");
        Mail::send('Emails.generic', ['body' =>  $data['body']], function ($message) use($data) {
            $message->from($data['from']);
            $message->to($data['to']);
            $message->subject($data['subject']);
        });
        
        return "queued";
    }
    
    public function sendview()
    {
        
        return View::make('Management.Mail.send');
    }

    public function Cache()
    {
        return var_dump(Cache::get('mail'));
    }
    
    public function ParseEmail($subdomain, $id)
    {
        $email = Email::find($id);
        if(count($email) === 1){
            if($email->subject === "Request for subscription"){
                $infoarray = AccountHelper::ParseEmail($email->body_plain);
                if($infoarray !== false){
                    $account = AccountHelper::AddToAccountTable($infoarray['subdomain'], $infoarray['subdomain'], $infoarray['subdomain'], $infoarray['DBPassword'], $infoarray);
                    return Redirect::to('/Accounts/' . $account->id);
                }else{
                    return "error Parsing";
                }
            }else{
                return "Not Subscription Request";
            }
        }else{
            return "Mail Not found";
        }
    }
}
