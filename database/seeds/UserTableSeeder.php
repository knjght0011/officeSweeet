<?php

use Illuminate\Database\Seeder;
use App\Models\User;


class UserTableSeeder extends Seeder
{

public function run()
{
    Eloquent::unguard();
    DB::table('users')->delete();
    User::create(array(
        'employeeid'    => '',
        'firstname'    => '',
        'middlename'    => '',
        'lastname'    => '',
        'ssn'    => '',
        'driverslicense'    => '',
        'department'    => '',
        'type'    => '',
        'comments'    => '',
        'email'    => 'support@officesweeet.com',
        'password' => Hash::make('fifth;STARS;call;TRIANGLE'),
        'passwordlastchanged'  => Carbon\Carbon::now(),
        'locked'    => 0,
        'failedattempts'    => 0,
        'disabled'    => 0,
        'canlogin'    => 1,
        'accessacp'    => 1,
        'usermanagement'  => 1,
        'createcustomtables'    => 1,
        'editcompanyinfo'    => 1,
        'branch_id'    => 1,
        'address_id'    => 1,
        'timezone'    => '700',
    ));
}

}