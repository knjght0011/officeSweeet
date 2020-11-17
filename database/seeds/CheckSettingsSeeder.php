<?php

use Illuminate\Database\Seeder;
use App\Models\CheckSettings;

class CheckSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Eloquent::unguard();
        //To Address
        CheckSettings::create(array(
        'Name' => 'ToTop',
        'Value' => '82',
        ));
        CheckSettings::create(array(
        'Name' => 'ToLeft',
        'Value' => '50',
        ));
        CheckSettings::create(array(
        'Name' => 'ToWidth',
        'Value' => '70',
        ));
        CheckSettings::create(array(
        'Name' => 'ToHeight',
        'Value' => '30',
        ));
        
        //Date
        CheckSettings::create(array(
        'Name' => 'DateTop',
        'Value' => '22',
        ));
        CheckSettings::create(array(
        'Name' => 'DateLeft',
        'Value' => '213',
        ));
        CheckSettings::create(array(
        'Name' => 'DateWidth',
        'Value' => '55',
        ));
        
        //PayTo
        CheckSettings::create(array(
        'Name' => 'PayToTop',
        'Value' => '30',
        ));
        CheckSettings::create(array(
        'Name' => 'PayToLeft',
        'Value' => '27',
        ));
        CheckSettings::create(array(
        'Name' => 'PayToWidth',
        'Value' => '175',
        ));
        
        //AmountNumber
        CheckSettings::create(array(
        'Name' => 'AmountNumTop',
        'Value' => '50',
        ));
        CheckSettings::create(array(
        'Name' => 'AmountNumLeft',
        'Value' => '254',
        ));
        CheckSettings::create(array(
        'Name' => 'AmountNumWidth',
        'Value' => '50',
        ));
        
        //AmountText
        CheckSettings::create(array(
        'Name' => 'AmountTextTop',
        'Value' => '66',
        ));
        CheckSettings::create(array(
        'Name' => 'AmountTextLeft',
        'Value' => '15',
        ));
        CheckSettings::create(array(
        'Name' => 'AmountTextWidth',
        'Value' => '200',
        ));
        
         //memo
        CheckSettings::create(array(
        'Name' => 'memoTop',
        'Value' => '105',
        ));
        CheckSettings::create(array(
        'Name' => 'memoLeft',
        'Value' => '30',
        ));
        CheckSettings::create(array(
        'Name' => 'memoWidth',
        'Value' => '82',
        ));
    }
}
