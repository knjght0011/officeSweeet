<?php

use Illuminate\Database\Seeder;

use App\Models\Employee;

class EmpolyeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Employee::create(array(
            'employeeid'    => 'staylor090685',
            'firstname' => 'Samuel',
            'middlename' => 'Keith',        
            'lastname'  => 'Taylor',
            'address_id'  => '1',
            'ssn'  => '123456789',
            'driverslicense'  => 'DL1249',
            'email'  => 'staylor@nomomredictation.com',
            'department'  => 'Software Development',
            'comments'  => 'Blarg!',            
        ));
        
        Employee::create(array(
            'employeeid'    => '',
            'firstname' => 'Richard',
            'middlename' => '',       
            'lastname'  => 'Burgess',            
            'address_id'  => '2',
            'ssn'  => '124368',
            'driverslicense'  => 'DL9876',
            'email'  => 'rburgess@nomomredictation',
            'department'  => 'Software Development',
            'comments'  => 'Wizard',            
        ));
                        
     
    }
}
