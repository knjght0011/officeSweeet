<?php

namespace App\Http\Controllers;

use Mail;

use Illuminate\Support\Facades\View;
use mysql_xdevapi\Exception;

class TestingController extends Controller
{

    public function ShowTestingPage()
    {
        return View::make('testing/testing');

    }

    public function SendTestEmail()
    {
        try {
            Mail::raw('Test Email', function ($message) {
                $message->to('movian@gmx.com');
                $message->from('NoReply@officesweeet.com');
                $message->subject('Test Email');

            });
            return ['status' => 'OK'];
        }catch (Exception $e)
        {
            return ['status' => $e];
        }
    }


}