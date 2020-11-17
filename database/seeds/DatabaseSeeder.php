<?php

use Illuminate\Database\Seeder;
use UserTableSeeder;
use AddressTableSeeder;
use ClientTableSeeder;
use ContactsTableSeeder;
use EmpolyeesTableSeeder;
use VendorsTableSeeder;
use Vendor_ContactsTableSeeder;



class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
		$this->call('UserTableSeeder');
                $this->call('AddressTableSeeder');

                $this->call('ClientTableSeeder');
                $this->call('ClientContactsTableSeeder');

                $this->call('EmpolyeesTableSeeder');

                $this->call('VendorsTableSeeder');
                $this->call('Vendor_ContactsTableSeeder');
                #$this->call('VendorsTableSeeder');
	}
}
