<?php
namespace App\Helpers;
#facades
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
#helpers
use App\Helpers\AccountHelper;
#models
use App\Models\Management\Email;
use App\Models\Management\Attachment;

class MailHelper
{
    
    const apikey = "key-6c0877e58c956c264928ba795e6de19d";
    
    public static function hashcheck($timestamp, $token, $signature)
    {   
        $hash = hash_hmac( "sha256" , $timestamp . $token , self::apikey  );
        
        if($hash === $signature){
            return true;
        }else{
            return false;
        }
    }
    
    public static function SaveMail($data){
        
        $email = new Email;
        $email->recipient = $data['recipient'];
        $email->sender = $data['sender'];
        $email->subject = $data['subject'];
        $email->from = $data['from'];
        $email->received = $data['received'];
        $email->message_id = $data['message_id'];
        $email->date = $data['date'];
        $email->user_agent = $data['user_agent'];
        $email->mime_version = $data['mime_version'];
        $email->to = $data['to'];
        $email->references = $data['references'];
        $email->in_reply_to = $data['in_reply_to'];
        $email->x_mailgun_variables = $data['x_mailgun_variables'];
        $email->content_type = $data['content_type'];
        $email->sender_two = $data['sender_two'];
        $email->message_headers = $data['message_headers'];
        $email->timestamp = $data['timestamp'];
        $email->token = $data['token'];
        $email->signature = $data['signature'];
        $email->body_plain = $data['body_plain'];
        $email->body_html = $data['body_html'];    
        $email->stripped_html = $data['stripped_html'];
        $email->stripped_text = $data['stripped_text'];
        $email->stripped_signature = $data['stripped_signature'];
        $email->content_id_map = $data['content_id_map']; 
        
        AccountHelper::Elevate();
        
        $email->save();
        
        AccountHelper::Deelevate();
      
        return $email;
    }
    
    public static function SaveAttachment($id, $file){
        if($file->isValid()){
            $attachment = new Attachment;
            $attachment->email_id = $id;
            $attachment->filename = $file->getClientOriginalName();
            $attachment->extension = $file->getClientOriginalExtension();
            $attachment->mimetype = $file->getMimeType();
            $attachment->size = $file->getSize();
            $attachment->file = file_get_contents($file->getRealPath());

            AccountHelper::Elevate();

            $attachment->save();

            AccountHelper::Deelevate();
        }
    }

    public static function unReadMail(){

        $count = 0;
        $email = Auth::user()->email;
        $mails = \App\Models\OS\Email\Email::where('email', 'like', '%'.$email.'%')->orWhere('sender', 'like', '%'.$email.'%')->orderBy('updated_at', 'desc')->get();
        foreach($mails as $mail){
            if((string)$mail->created_at === (string)$mail->updated_at){
                    $count = $count + 1;
            }
        }

        return $count;

    }
    public static function unReadInboxMail(){
        $count = 0;
        $email = Auth::user()->email;
        $mails = \App\Models\OS\Email\Email::where('email', 'like', '%'.$email.'%')->orderBy('updated_at', 'desc')->get();
        foreach($mails as $mail){
            if((string)$mail->created_at === (string)$mail->updated_at){
                    $count = $count + 1;
            }
        }
        return $count;
    }
    public static function unReadSentMail(){
        $count = 0;
        $email = Auth::user()->email;
        $mails = \App\Models\OS\Email\Email::where('sender', 'like', '%'.$email.'%')->orderBy('updated_at', 'desc')->get();
        foreach($mails as $mail){
            if((string)$mail->created_at === (string)$mail->updated_at){
                    $count = $count + 1;
            }
        }
        return $count;
    }
}