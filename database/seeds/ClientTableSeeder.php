<?php

use Illuminate\Database\Seeder;
use App\Models\Client;


class ClientTableSeeder extends Seeder
{

public function run()
{
    Eloquent::unguard();
    
    Client::create(array(
        'name' => 'Empower Tech',
        'address_id' => '1',
        'notes' => '',
    ));
    Client::create(array(
        'name' => 'The Belchers',
        'address_id' => '8',
        'notes' => '',
    ));
    
    Client::create(array(
        'name' => 'The Figgis Agency',
        'address_id' => '2',
        'notes' => '',
    ));
        
    Client::create(array(
        'name' => 'Nelson and Murcock',
        'address_id' => '4',
        'notes' => '',
    ));
            
    Client::create(array(
        'name' => 'Voyager',
        'address_id' => '5',
        'notes' => 'Lost in the Delta Quadrent',
    ));

    Client::create(array(
        'name' => 'Deep Space Nine',
        'address_id' => '6',
        'notes' => '',
    ));    

    Client::create(array(
        'name' => 'Enterpise',
        'address_id' => '7',
        'notes' => '',
    ));    
}

}