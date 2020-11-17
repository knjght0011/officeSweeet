<?php
namespace App\Http\Controllers\OS\Support;

use Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class SupportController extends Controller {

    public static function ShowSupport(){
        return View::make('Support.index');
    }
}
