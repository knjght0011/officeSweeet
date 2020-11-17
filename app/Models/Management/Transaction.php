<?php

namespace App\Models\Management;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

use App\Helpers\AccountHelper;
use App\Helpers\TransnationalHelper;

use App\Jobs\Provisioning\CreateDB;
use App\Jobs\Provisioning\MigrateDB;
use App\Jobs\Provisioning\SeedDB;

use App\Models\Client;

class Transaction extends Model
{
    protected $connection = 'management';
    
    protected $table = "transactions";

                  
}
