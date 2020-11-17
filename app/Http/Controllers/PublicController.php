<?php

namespace app\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
#use Illuminate\Foundation\Auth\Access\AuthorizesResources; AuthorizesResources

class PublicController extends BaseController
{
    use DispatchesJobs;
}
