<?php

use Illuminate\Database\Seeder;

use App\Models\Vendor;


class VendorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
public function run()
{
    Eloquent::unguard();
    Vendor::create(array(
        'name' => 'Empower Tech-Vendor',
        'address_id' => '1',
        'notes' => '',
    ));
    Vendor::create(array(
        'name' => 'The Belchers-Vendor',
        'address_id' => '8',
        'notes' => '',
    ));
    
    Vendor::create(array(
        'name' => 'The Figgis Agency-Vendor',
        'address_id' => '3',
        'notes' => '',
    ));
        
    Vendor::create(array(
        'name' => 'Nelson and Murcock-Vendor',
        'address_id' => '4',
        'notes' => '',
    ));
            
    Vendor::create(array(
        'name' => 'Voyager-Vendor',
        'address_id' => '5',
        'notes' => 'Lost in the Delta Quadrent',
    ));

    Vendor::create(array(
        'name' => 'Deep Space Nine-Vendor',
        'address_id' => '6',
        'notes' => '',
    ));    

    Vendor::create(array(
        'name' => 'Enterpise-Vendor',
        'address_id' => '7',
        'notes' => '',
    ));    
}
}
