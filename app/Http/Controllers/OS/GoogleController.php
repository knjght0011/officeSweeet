<?php

namespace App\Http\Controllers\OS;

use Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use Google;

use App\Helpers\OS\SettingHelper;
use App\Helpers\OS\GoogleHelper;

class GoogleController extends Controller {

    public static function SetupPopup($subdomain, $location){
        switch ($location) {
            case "global":
                $token = SettingHelper::GetSetting('gmail-system');
            break;
            case "user":
                $token = Auth::user()->GoogleAccessToken;
            break;
            default:
                return "unknown location";
        }

        if($token === null){ #/Google/Auth?store=global
            return Redirect::to('/Google/Auth?store=' . $location);
        }else{
            switch ($location) {
                case "global":
                    SettingHelper::SetSetting('gmail-system', "");
                    break;
                case "user":
                    Auth::user()->GoogleAccessToken = null;
                    Auth::user()->save();
                    break;

            }

            GoogleHelper::RevokeToken($token);
            return "<script>window.close();</script>";
        }
    }

    public function GoogleAuth(){

        $data = array(
            'state' => Input::get('state'),
            'code' => Input::get('code'),
            'scope' => Input::get('scope'),
            'store' => Input::get('store'),
        );

        $client = Google::getClient();
        $client->setAuthConfig(base_path('client_secret_1029785995201-gt3d0hb541qllfh3ma151s5e3rka2i6p.apps.googleusercontent.com.json'));
        $client->setRedirectUri('http://google.officesweeet.com/google');
        $client->addScope("https://www.googleapis.com/auth/gmail.send");
        $client->addScope("email");


        if ($data['code'] === null) {

            $account = app()->make('account');

            $client->setState($account->subdomain . "," . $data['store']);
            $client->setAccessType("offline");
            $auth_url = $client->createAuthUrl();
            return Redirect::away($auth_url);
        } else {
            $client->authenticate($data['code']);
            $token = $client->getAccessToken();
            $token['email'] = GoogleHelper::GetEmailAddress($token);

            $split = explode(",", $data['state']);
            switch ($split[1]) {
                case "user":
                    Auth::user()->GoogleAccessToken = $token;
                    Auth::user()->save();
                    break;
                case "global":
                    SettingHelper::SetSetting('gmail-system', json_encode($token));
                    break;
            }

            return "<script>window.close();</script>";#Redirect::away($redirect_uri);
        }
    }

    public function PromptOff(){
        SettingHelper::SetSetting('email-dont-show-gmail-popup', '1');
        return "done";
    }

    public function SendGmail(){

        $data = array(
            'to' => Input::get('to'),
            'subject' => Input::get('subject'),
            'body' => Input::get('body'),
            'token' => GoogleHelper::GetToken(Input::get('account')),
        );

        $rules = array(
            'to' => 'email',
            'subject' => 'string',
            'body' => 'string',
            'token' => 'array'
        );

        $messages = [
            'token' => 'Unable to find google access token.',
        ];

        // run the validation rules on the inputs from the form
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()){
            return ['status' => 'validation', 'errors' => $validator->errors()->toArray()];
        }else{
            GoogleHelper::SendGmail($data['token'], $data['to'], $data['subject'], $data['body']);
            return ['status' => 'OK'];
        }

    }

    public function DeleteToken(){

        $tokendescriptor = Input::get('tokendescriptor');

        switch ($tokendescriptor) {
            case "user":
                $token = Auth::user()->GoogleAccessToken;

                Auth::user()->GoogleAccessToken = null;
                Auth::user()->save();
                break;

            case "global":
                $token = json_decode(SettingHelper::GetSetting('gmail-system'), true);
                SettingHelper::SetSetting('gmail-system', "");
                break;
        }

        if($token === null){
            return "none";
        }else{
            GoogleHelper::RevokeToken($token);
            return "done";
        }
    }

    public function Test(){

        var_dump(GoogleHelper::GetTokenInfo(Auth::user()->GoogleAccessToken));
    }













    public function TestDrive(){


        #$client->setAuthConfigFile('D:\client_secret_1029785995201-gt3d0hb541qllfh3ma151s5e3rka2i6p.apps.googleusercontent.com.json');
        #$client->setRedirectUri('http://google.officesweeet.com/google');



        $client = Google::getClient();
        $client->setAuthConfig('D:\client_secret_1029785995201-gt3d0hb541qllfh3ma151s5e3rka2i6p.apps.googleusercontent.com.json');
        $client->addScope("https://www.googleapis.com/auth/drive.metadata.readonly");
        $client->setState('local');

        if (Session::has('access_token')) {
            $client->setAccessToken(session('access_token'));
            $drive = Google::make('drive');
            $files = $drive->files->listFiles(array())->getFiles();
            foreach($files as $file){
                var_dump($file['name']);
            }
            #echo json_encode($files);
        } else {
            $redirect_uri = '/Google';
            return Redirect::away($redirect_uri);
        }

    }

    public function oAuth2TestDrive(){

        $data = array(
            'state' => Input::get('state'),
            'code' => Input::get('code'),
            'scope' => Input::get('scope'),
        );

        var_dump($data);

        $client = Google::getClient();
        $client->setAuthConfig('D:\client_secret_1029785995201-gt3d0hb541qllfh3ma151s5e3rka2i6p.apps.googleusercontent.com.json');
        $client->setRedirectUri('http://google.officesweeet.com/google');
        $client->addScope("https://www.googleapis.com/auth/drive.metadata.readonly");


        if ($data['code'] === null) {
            $client->setState('local');
            $auth_url = $client->createAuthUrl();
            return Redirect::away($auth_url);
        } else {
            $client->authenticate($data['code']);
            $token = $client->getAccessToken();;
            Session::put('access_token', $token);
            $redirect_uri = "/Test";
            return Redirect::away($redirect_uri);
        }


    }

}
