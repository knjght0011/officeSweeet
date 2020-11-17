<?php
namespace App\Helpers\OS;

use Carbon\Carbon;
use Google;
use Illuminate\Support\Facades\Auth;

class GoogleHelper
{
    const AuthConfig = 'client_secret_1029785995201-gt3d0hb541qllfh3ma151s5e3rka2i6p.apps.googleusercontent.com.json';

    public static function SendGmail1($accesstoken){
        var_dump($accesstoken);
    }

    public static function SendGmail($accesstoken, $to, $subject, $body){

        $client = Google::getClient();
        $client->setAuthConfig(base_path(self::AuthConfig));
        $client->addScope("https://www.googleapis.com/auth/gmail.send");
        $client->setAccessToken($accesstoken);

        $gmail = Google::make('Gmail');
        $message = Google::make('Gmail_Message');

        $strRawMessage = "To: <" . $to . ">\r\n";
        $strRawMessage .= 'Subject: =?utf-8?B?' . base64_encode($subject) . "?=\r\n";
        $strRawMessage .= "MIME-Version: 1.0\r\n";
        $strRawMessage .= "Content-Type: text/html; charset=utf-8\r\n";
        $strRawMessage .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
        $strRawMessage .= $body . "\r\n";

        $mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');

        $message->setRaw($mime);

        $gmail->users_messages->send("me", $message);

    }

    public static function TokenExpiryDate($token){
        return Carbon::createFromTimestamp($token['created'] + $token['expires_in']);
    }

    public static function TokenHasExpired($token){
        $expirey = self::TokenExpiryDate($token);
        $now = Carbon::now();
        if($expirey->lt($now)){
            return true;
        }else{
            return false;
        }

    }

    public static function GetTokenInfo($token){
        $url = "https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=" . $token['access_token'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);

        $obj = json_decode($result);
        return $obj;
    }

    public static function GetEmailAddress($token){
        $info = self::GetTokenInfo($token);
        return $info->email;
    }

    public static function GetToken($account){
        switch ($account) {
            case "1":
            return Auth::user()->GoogleAccessToken;
            case "0":
            return json_decode(SettingHelper::GetSetting('gmail-system'), true);
            case "unknown":
            if(SettingHelper::GetSetting('gmail-system') === null){
                return Auth::user()->GoogleAccessToken;
            }else{
                return json_decode(SettingHelper::GetSetting('gmail-system'), true);
            }
            break;
        }
    }

    public static function RevokeToken($token){

        $client = Google::getClient();
        $client->setAuthConfig(base_path(self::AuthConfig));
        $client->addScope("https://www.googleapis.com/auth/gmail.send");
        $client->setAccessToken($token);
        $client->revokeToken();
    }
}