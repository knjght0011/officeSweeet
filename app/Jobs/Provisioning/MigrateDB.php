<?php

namespace App\Jobs\Provisioning;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Management\Account;
use App\Helpers\AccountHelper;

class MigrateDB implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $account;
    public $install;
    public $tries = 1;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Account $account, $install)
    {
        $this->account = $account;
        $this->install = $install;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        AccountHelper::Elevate();
        AccountHelper::SwitchConnection($this->account->database, $this->account->username, $this->account->password);
        AccountHelper::MigrateDB();
        AccountHelper::SwitchConnectionBack();
        AccountHelper::Deelevate();


        if($this->install === true){
           $account = $this->account;
           
           $array = $account->installstage;
           $array['MigrateDB'] = 1;
           $account->installstage = $array;
           $account->save();

        }        
    }
}
