<?php

use Illuminate\Database\Seeder;
use App\Models\Address;

class AddressTableSeeder extends Seeder
{

public function run()
{
    Eloquent::unguard();
    DB::table('users')->delete();
    Address::create(array(
        'address1' => '1990 Main St',
        'address2' => '#750',
        'city' => 'Sarasota',
        'state' => 'FL',
        'zip' => '34236',
        'type' => 'Work',
    ));
    Address::create(array(
        'address1' => '2710 Newton Court',
        'address2' => '',
        'city' => 'Blacksburg',
        'state' => 'VA',
        'zip' => '24060',
        'type' => 'Home',
    ));   
    Address::create(array(
        'address1' => '4200 E Mission Blvd',
        'address2' => '',
        'city' => 'Ontario',
        'state' => 'CA',
        'zip' => '91761',
        'type' => 'Business',
    ));
    Address::create(array(
        'address1' => '2050 Bamako Place',
        'address2' => '',
        'city' => 'Washington',
        'state' => 'DC',
        'zip' => '20521-2050',
        'type' => 'Work',
    ));
    Address::create(array(
        'address1' => '795 E DRAGRAM',
        'address2' => '',
        'city' => 'TUCSON',
        'state' => 'AZ',
        'zip' => '85705',
        'type' => 'Home',
    ));
    Address::create(array(
        'address1' => '799 E DRAGRAM',
        'address2' => 'SUITE 5A',
        'city' => 'TUCSON',
        'state' => 'AZ',
        'zip' => '85705',
        'type' => 'Business',
    ));
    Address::create(array(
        'address1' => '300 BOYLSTON AVE E',
        'address2' => '',
        'city' => 'SEATTLE',
        'state' => 'WA',
        'zip' => '98102',
        'type' => 'Home',
    ));
    Address::create(array(
        'address1' => 'Bobs Burgers',
        'address2' => 'Ocean Avenue ',
        'city' => 'Seymours Bay',
        'state' => 'WA',
        'zip' => '98102',
        'type' => 'Home',
    ));
    
}

}