<?php

use Illuminate\Database\Seeder;
use App\Models\TemplateGroup;

class TemplateGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        TemplateGroup::create(array(
            'group' => 'client',
            'subgroup' => 'Contracts',
        ));
        TemplateGroup::create(array(
            'group' => 'client',
            'subgroup' => 'Initial Documents',
        ));
        TemplateGroup::create(array(
            'group' => 'client',
            'subgroup' => 'Letters',
        ));
        
        TemplateGroup::create(array(
            'group' => 'vendor',
            'subgroup' => 'Contracts',
        ));
        TemplateGroup::create(array(
            'group' => 'vendor',
            'subgroup' => 'Letters',
        ));
        TemplateGroup::create(array(
            'group' => 'vendor',
            'subgroup' => 'Orders',
        ));
      
        TemplateGroup::create(array(
            'group' => 'employee',
            'subgroup' => 'Contracts',
        ));
        TemplateGroup::create(array(
            'group' => 'employee',
            'subgroup' => 'Expense Reports',
        ));
        
        TemplateGroup::create(array(
            'group' => 'general',
            'subgroup' => 'Balance Sheets',
        ));
        TemplateGroup::create(array(
            'group' => 'general',
            'subgroup' => 'TPS Reports',
        ));
    }
}
