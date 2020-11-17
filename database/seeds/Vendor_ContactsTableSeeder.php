<?php

use Illuminate\Database\Seeder;

use App\Models\VendorContact;

class Vendor_ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
public function run()
{
    Eloquent::unguard();
    
    VendorContact::create(array(

        'firstname' => 'John-V',
        'middlename' => '',
        'lastname' => 'Stagl',
        'address_id' => '1',
        'ssn' => '',
        'driverslicense' => 'DL123-234',
        'email' => 'jstagl@nomoredictation.com',
        'vendor_id' => '1',
        'contacttype' => 'Owner',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Richard-V',
        'middlename' => '',
        'lastname' => 'Burgess',
        'address_id' => '1',
        'ssn' => '',
        'driverslicense' => 'DL123-234',
        'email' => 'rburgess@nomoredictation.com',
        'vendor_id' => '1',
        'contacttype' => 'Primary Tech',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Sam-V',
        'middlename' => 'Keith',
        'lastname' => 'Taylor',
        'address_id' => '1',
        'ssn' => '',
        'driverslicense' => 'DL123-234',
        'email' => 'staylor@nomoredictation.com',
        'vendor_id' => '1',
        'contacttype' => 'Secondary Tech',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Bob-V',
        'middlename' => '',
        'lastname' => 'Belcher',
        'address_id' => '8',
        'ssn' => '123456789',
        'driverslicense' => 'DL123-234',
        'email' => 'bob@bobsburgers.com',
        'vendor_id' => '2',
        'contacttype' => 'Father',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Linda-V',
        'middlename' => '',
        'lastname' => 'Belcher',
        'address_id' => '8',
        'ssn' => '123456789',
        'driverslicense' => 'DL123-234',
        'email' => 'linda@bobsburgers.com',
        'vendor_id' => '2',
        'contacttype' => 'Mother',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Tina-V',
        'middlename' => '',
        'lastname' => 'Belcher',
        'address_id' => '8',
        'ssn' => '123456789',
        'driverslicense' => '',
        'email' => 'tina@bobsburgers.com',
        'vendor_id' => '2',
        'contacttype' => 'Oldest Child',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Gene-V',
        'middlename' => '',
        'lastname' => 'Belcher',
        'address_id' => '8',
        'ssn' => '123456789',
        'driverslicense' => 'DL123-234',
        'email' => 'gene@bobsburgers.com',
        'vendor_id' => '2',
        'contacttype' => 'Middle Child',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Louise-V',
        'middlename' => '',
        'lastname' => 'Belcher',
        'address_id' => '8',
        'ssn' => '123456789',
        'driverslicense' => 'DL123-234',
        'email' => 'Louise@bobsburgers.com',
        'vendor_id' => '2',
        'contacttype' => 'Youngest Child',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Sterling-V',
        'middlename' => '',
        'lastname' => 'Archer',
        'address_id' => '2',
        'ssn' => '123456789',
        'driverslicense' => 'DL9213874e',
        'email' => 'archer@thefiggisagency.com',
        'vendor_id' => '3',
        'contacttype' => 'Secret Agent',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Cheryl-V',
        'middlename' => '',
        'lastname' => 'Tunt',
        'address_id' => '2',
        'ssn' => '123456789',
        'driverslicense' => 'DL9213874e',
        'email' => 'cheryl@thefiggisagency.com',
        'vendor_id' => '3',
        'contacttype' => 'Receptionist',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Pam-V',
        'middlename' => '',
        'lastname' => 'Poovey',
        'address_id' => '2',
        'ssn' => '123456789',
        'driverslicense' => 'DL9213874e',
        'email' => 'pam@thefiggisagency.com',
        'vendor_id' => '3',
        'contacttype' => 'Head of HR',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Cyril-V',
        'middlename' => '',
        'lastname' => 'Figgis',
        'address_id' => '2',
        'ssn' => '123456789',
        'driverslicense' => 'DL9213874e',
        'email' => 'cyril@thefiggisagency.com',
        'vendor_id' => '3',
        'contacttype' => 'Owner',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Lana-V',
        'middlename' => '',
        'lastname' => 'Kane',
        'address_id' => '2',
        'ssn' => '123456789',
        'driverslicense' => 'DL9213874e',
        'email' => 'lana@thefiggisagency.com',
        'vendor_id' => '3',
        'contacttype' => 'Owner',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => 'Lana!!!!!!!!!!!!!!!!!!!!!!!!!',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Algernop-V',
        'middlename' => '',
        'lastname' => 'Krieger',
        'address_id' => '2',
        'ssn' => '123456789',
        'driverslicense' => 'DL9213874e',
        'email' => 'algernop@thefiggisagency.com',
        'vendor_id' => '3',
        'contacttype' => 'R & D',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Matt-V',
        'middlename' => '',
        'lastname' => 'Murdock',
        'address_id' => '4',
        'ssn' => '123456789',
        'driverslicense' => '',
        'email' => 'matt@nelsonandmurcock.org',
        'vendor_id' => '4',
        'contacttype' => 'Lawyer',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Foggy-V',
        'middlename' => '',
        'lastname' => 'Nelson',
        'address_id' => '4',
        'ssn' => '123456789',
        'driverslicense' => 'DL9213874e',
        'email' => 'foggy@nelsonandmurcock.org',
        'vendor_id' => '4',
        'contacttype' => 'Lawyer',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Karen-V',
        'middlename' => '',
        'lastname' => 'Page',
        'address_id' => '4',
        'ssn' => '123456789',
        'driverslicense' => 'DL9213874e',
        'email' => 'karen@nelsonandmurcock.org',
        'vendor_id' => '4',
        'contacttype' => 'Receptionist',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Kathryn-V',
        'middlename' => '',
        'lastname' => 'Janeway',
        'address_id' => '5',
        'ssn' => '123456',
        'driverslicense' => 'DL9213874e',
        'email' => 'janeway@sfvoyager.com',
        'vendor_id' => '5',
        'contacttype' => 'Captain',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Chakotay-V',
        'middlename' => '',
        'lastname' => '',
        'address_id' => '5',
        'ssn' => '123456',
        'driverslicense' => 'DL9213874e',
        'email' => 'chakotay@sfvoyager.com',
        'vendor_id' => '5',
        'contacttype' => 'First Officer',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => ' BElanna-V',
        'middlename' => '',
        'lastname' => 'Torres',
        'address_id' => '5',
        'ssn' => '123456',
        'driverslicense' => 'DL9213874e',
        'email' => 'belanna@sfvoyager.com',
        'vendor_id' => '5',
        'contacttype' => 'Chief Engineer',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Tuvok-V',
        'middlename' => '',
        'lastname' => '',
        'address_id' => '5',
        'ssn' => '123456',
        'driverslicense' => 'DL9213874e',
        'email' => 'tuvok@sfvoyager.com',
        'vendor_id' => '5',
        'contacttype' => 'Lt. Commander',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Harry-V',
        'middlename' => '',
        'lastname' => 'Kim',
        'address_id' => '5',
        'ssn' => '123456',
        'driverslicense' => 'DL9213874e',
        'email' => 'harry@sfvoyager.com',
        'vendor_id' => '5',
        'contacttype' => 'Ensign',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Benjamin-V',
        'middlename' => '',
        'lastname' => 'Sisko',
        'address_id' => '6',
        'ssn' => '123456',
        'driverslicense' => 'DL9213874e',
        'email' => 'captainbenjaminsisko@ds9.com',
        'vendor_id' => '6',
        'contacttype' => 'Captain',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Julian-V',
        'middlename' => '',
        'lastname' => 'Bashir',
        'address_id' => '6',
        'ssn' => '123456',
        'driverslicense' => 'DL9213874e',
        'email' => 'doctorjulianbashir@ds9.com',
        'vendor_id' => '6',
        'contacttype' => 'Doctor',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
        
    VendorContact::create(array(

        'firstname' => 'Miles-V',
        'middlename' => 'Edward',
        'lastname' => 'OBrien',
        'address_id' => '6',
        'ssn' => '123456',
        'driverslicense' => 'DL9213874e',
        'email' => 'chiefobrien@ds9.com',
        'vendor_id' => '6',
        'contacttype' => 'Chief',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
            
    VendorContact::create(array(

        'firstname' => 'Kira-V',
        'middlename' => '',
        'lastname' => 'Nerys',
        'address_id' => '6',
        'ssn' => '123456',
        'driverslicense' => 'DL9213874e',
        'email' => 'majornerys@ds9.com',
        'vendor_id' => '6',
        'contacttype' => 'Major',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
        
    VendorContact::create(array(

        'firstname' => 'Jean-Luc-V',
        'middlename' => '',
        'lastname' => 'Picard',
        'address_id' => '7',
        'ssn' => '123456',
        'driverslicense' => 'DL912384e',
        'email' => 'picard@starfleet.com',
        'vendor_id' => '7',
        'contacttype' => 'Captain',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'William-V',
        'middlename' => 'T.',
        'lastname' => 'Riker',
        'address_id' => '7',
        'ssn' => '123456',
        'driverslicense' => 'DL912384e',
        'email' => 'riker@starfleet.com',
        'vendor_id' => '7',
        'contacttype' => 'First Officer',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Geordi-V',
        'middlename' => 'La',
        'lastname' => 'Forge',
        'address_id' => '7',
        'ssn' => '123456',
        'driverslicense' => 'DL912384e',
        'email' => 'Geordi@starfleet.com',
        'vendor_id' => '7',
        'contacttype' => 'Lt. Commander',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
        
    VendorContact::create(array(

        'firstname' => 'Deanna-V',
        'middlename' => '',
        'lastname' => 'Troi',
        'address_id' => '7',
        'ssn' => '123456',
        'driverslicense' => 'DL912384e',
        'email' => 'deannatroi@starfleet.com',
        'vendor_id' => '7',
        'contacttype' => 'Counselor',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));

    VendorContact::create(array(

        'firstname' => 'Beverly-V',
        'middlename' => '',
        'lastname' => 'Crusher',
        'address_id' => '7',
        'ssn' => '123456',
        'driverslicense' => 'DL912384e',
        'email' => 'doctorcrusher@starfleet.com',
        'vendor_id' => '7',
        'contacttype' => 'Doctor',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
    
    VendorContact::create(array(

        'firstname' => 'Wesley-V',
        'middlename' => '',
        'lastname' => 'Crusher',
        'address_id' => '7',
        'ssn' => '123456',
        'driverslicense' => 'DL912384e',
        'email' => 'wesleycrusher@starfleet.com',
        'vendor_id' => '7',
        'contacttype' => 'Starfleet Cadet',
        'ref1' => '',
        'ref2' => '',
        'ref3' => '',
        'comments' => '',

    ));
}
}