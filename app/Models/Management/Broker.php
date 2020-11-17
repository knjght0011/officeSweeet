<?php

namespace App\Models\Management;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

use App\Helpers\AccountHelper;
use App\Helpers\TransnationalHelper;

use App\Jobs\Provisioning\CreateDB;
use App\Jobs\Provisioning\MigrateDB;
use App\Jobs\Provisioning\SeedDB;

use App\Models\Client;

use App\Mail\WelcomeEmail;
use App\Mail\SignupEmail;

class Broker extends Model
{
    protected $connection = 'management';
    
    protected $table = "brokers";

    //protected $casts = [
    //    'installinfo' => 'array',
    //];

    //protected $dates = ['active'];


}
