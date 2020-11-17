<?php

use Illuminate\Database\Seeder;

use App\Models\ClientContact;

class ClientContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
    
        ClientContact::create(array(

            'firstname' => 'John',
            'middlename' => '',
            'lastname' => 'Stagl',
            'address_id' => '1',
            'ssn' => '',
            'driverslicense' => 'DL123-234',
            'email' => 'jstagl@nomoredictation.com',
            'client_id' => '1',
            'contacttype' => 'Owner',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Richard',
            'middlename' => '',
            'lastname' => 'Burgess',
            'address_id' => '1',
            'ssn' => '',
            'driverslicense' => 'DL123-234',
            'email' => 'rburgess@nomoredictation.com',
            'client_id' => '1',
            'contacttype' => 'Primary Tech',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Sam',
            'middlename' => 'Keith',
            'lastname' => 'Taylor',
            'address_id' => '1',
            'ssn' => '',
            'driverslicense' => 'DL123-234',
            'email' => 'staylor@nomoredictation.com',
            'client_id' => '1',
            'contacttype' => 'Secondary Tech',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Bob',
            'middlename' => '',
            'lastname' => 'Belcher',
            'address_id' => '8',
            'ssn' => '123456789',
            'driverslicense' => 'DL123-234',
            'email' => 'bob@bobsburgers.com',
            'client_id' => '2',
            'contacttype' => 'Father',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Linda',
            'middlename' => '',
            'lastname' => 'Belcher',
            'address_id' => '8',
            'ssn' => '123456789',
            'driverslicense' => 'DL123-234',
            'email' => 'linda@bobsburgers.com',
            'client_id' => '2',
            'contacttype' => 'Mother',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Tina',
            'middlename' => '',
            'lastname' => 'Belcher',
            'address_id' => '8',
            'ssn' => '123456789',
            'driverslicense' => '',
            'email' => 'tina@bobsburgers.com',
            'client_id' => '2',
            'contacttype' => 'Oldest Child',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Gene',
            'middlename' => '',
            'lastname' => 'Belcher',
            'address_id' => '8',
            'ssn' => '123456789',
            'driverslicense' => 'DL123-234',
            'email' => 'gene@bobsburgers.com',
            'client_id' => '2',
            'contacttype' => 'Middle Child',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Louise',
            'middlename' => '',
            'lastname' => 'Belcher',
            'address_id' => '8',
            'ssn' => '123456789',
            'driverslicense' => 'DL123-234',
            'email' => 'Louise@bobsburgers.com',
            'client_id' => '2',
            'contacttype' => 'Youngest Child',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Sterling',
            'middlename' => '',
            'lastname' => 'Archer',
            'address_id' => '2',
            'ssn' => '123456789',
            'driverslicense' => 'DL9213874e',
            'email' => 'archer@thefiggisagency.com',
            'client_id' => '3',
            'contacttype' => 'Secret Agent',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Cheryl',
            'middlename' => '',
            'lastname' => 'Tunt',
            'address_id' => '2',
            'ssn' => '123456789',
            'driverslicense' => 'DL9213874e',
            'email' => 'cheryl@thefiggisagency.com',
            'client_id' => '3',
            'contacttype' => 'Receptionist',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Pam',
            'middlename' => '',
            'lastname' => 'Poovey',
            'address_id' => '2',
            'ssn' => '123456789',
            'driverslicense' => 'DL9213874e',
            'email' => 'pam@thefiggisagency.com',
            'client_id' => '3',
            'contacttype' => 'Head of HR',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Cyril',
            'middlename' => '',
            'lastname' => 'Figgis',
            'address_id' => '2',
            'ssn' => '123456789',
            'driverslicense' => 'DL9213874e',
            'email' => 'cyril@thefiggisagency.com',
            'client_id' => '3',
            'contacttype' => 'Owner',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Lana',
            'middlename' => '',
            'lastname' => 'Kane',
            'address_id' => '2',
            'ssn' => '123456789',
            'driverslicense' => 'DL9213874e',
            'email' => 'lana@thefiggisagency.com',
            'client_id' => '3',
            'contacttype' => 'Owner',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => 'Lana!!!!!!!!!!!!!!!!!!!!!!!!!',

        ));

        ClientContact::create(array(

            'firstname' => 'Algernop',
            'middlename' => '',
            'lastname' => 'Krieger',
            'address_id' => '2',
            'ssn' => '123456789',
            'driverslicense' => 'DL9213874e',
            'email' => 'algernop@thefiggisagency.com',
            'client_id' => '3',
            'contacttype' => 'R & D',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Matt',
            'middlename' => '',
            'lastname' => 'Murdock',
            'address_id' => '4',
            'ssn' => '123456789',
            'driverslicense' => '',
            'email' => 'matt@nelsonandmurcock.org',
            'client_id' => '4',
            'contacttype' => 'Lawyer',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Foggy',
            'middlename' => '',
            'lastname' => 'Nelson',
            'address_id' => '4',
            'ssn' => '123456789',
            'driverslicense' => 'DL9213874e',
            'email' => 'foggy@nelsonandmurcock.org',
            'client_id' => '4',
            'contacttype' => 'Lawyer',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Karen',
            'middlename' => '',
            'lastname' => 'Page',
            'address_id' => '4',
            'ssn' => '123456789',
            'driverslicense' => 'DL9213874e',
            'email' => 'karen@nelsonandmurcock.org',
            'client_id' => '4',
            'contacttype' => 'Receptionist',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Kathryn',
            'middlename' => '',
            'lastname' => 'Janeway',
            'address_id' => '5',
            'ssn' => '123456',
            'driverslicense' => 'DL9213874e',
            'email' => 'janeway@sfvoyager.com',
            'client_id' => '5',
            'contacttype' => 'Captain',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Chakotay',
            'middlename' => '',
            'lastname' => '',
            'address_id' => '5',
            'ssn' => '123456',
            'driverslicense' => 'DL9213874e',
            'email' => 'chakotay@sfvoyager.com',
            'client_id' => '5',
            'contacttype' => 'First Officer',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => ' BElanna',
            'middlename' => '',
            'lastname' => 'Torres',
            'address_id' => '5',
            'ssn' => '123456',
            'driverslicense' => 'DL9213874e',
            'email' => 'belanna@sfvoyager.com',
            'client_id' => '5',
            'contacttype' => 'Chief Engineer',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Tuvok',
            'middlename' => '',
            'lastname' => '',
            'address_id' => '5',
            'ssn' => '123456',
            'driverslicense' => 'DL9213874e',
            'email' => 'tuvok@sfvoyager.com',
            'client_id' => '5',
            'contacttype' => 'Lt. Commander',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Harry',
            'middlename' => '',
            'lastname' => 'Kim',
            'address_id' => '5',
            'ssn' => '123456',
            'driverslicense' => 'DL9213874e',
            'email' => 'harry@sfvoyager.com',
            'client_id' => '5',
            'contacttype' => 'Ensign',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Benjamin',
            'middlename' => '',
            'lastname' => 'Sisko',
            'address_id' => '6',
            'ssn' => '123456',
            'driverslicense' => 'DL9213874e',
            'email' => 'captainbenjaminsisko@ds9.com',
            'client_id' => '6',
            'contacttype' => 'Captain',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Julian',
            'middlename' => '',
            'lastname' => 'Bashir',
            'address_id' => '6',
            'ssn' => '123456',
            'driverslicense' => 'DL9213874e',
            'email' => 'doctorjulianbashir@ds9.com',
            'client_id' => '6',
            'contacttype' => 'Doctor',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Miles',
            'middlename' => 'Edward',
            'lastname' => 'OBrien',
            'address_id' => '6',
            'ssn' => '123456',
            'driverslicense' => 'DL9213874e',
            'email' => 'chiefobrien@ds9.com',
            'client_id' => '6',
            'contacttype' => 'Chief',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Kira',
            'middlename' => '',
            'lastname' => 'Nerys',
            'address_id' => '6',
            'ssn' => '123456',
            'driverslicense' => 'DL9213874e',
            'email' => 'majornerys@ds9.com',
            'client_id' => '6',
            'contacttype' => 'Major',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Jean-Luc',
            'middlename' => '',
            'lastname' => 'Picard',
            'address_id' => '7',
            'ssn' => '123456',
            'driverslicense' => 'DL912384e',
            'email' => 'picard@starfleet.com',
            'client_id' => '7',
            'contacttype' => 'Captain',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'William',
            'middlename' => 'T.',
            'lastname' => 'Riker',
            'address_id' => '7',
            'ssn' => '123456',
            'driverslicense' => 'DL912384e',
            'email' => 'riker@starfleet.com',
            'client_id' => '7',
            'contacttype' => 'First Officer',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Geordi',
            'middlename' => 'La',
            'lastname' => 'Forge',
            'address_id' => '7',
            'ssn' => '123456',
            'driverslicense' => 'DL912384e',
            'email' => 'Geordi@starfleet.com',
            'client_id' => '7',
            'contacttype' => 'Lt. Commander',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Deanna',
            'middlename' => '',
            'lastname' => 'Troi',
            'address_id' => '7',
            'ssn' => '123456',
            'driverslicense' => 'DL912384e',
            'email' => 'deannatroi@starfleet.com',
            'client_id' => '7',
            'contacttype' => 'Counselor',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Beverly',
            'middlename' => '',
            'lastname' => 'Crusher',
            'address_id' => '7',
            'ssn' => '123456',
            'driverslicense' => 'DL912384e',
            'email' => 'doctorcrusher@starfleet.com',
            'client_id' => '7',
            'contacttype' => 'Doctor',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));

        ClientContact::create(array(

            'firstname' => 'Wesley',
            'middlename' => '',
            'lastname' => 'Crusher',
            'address_id' => '7',
            'ssn' => '123456',
            'driverslicense' => 'DL912384e',
            'email' => 'wesleycrusher@starfleet.com',
            'client_id' => '7',
            'contacttype' => 'Starfleet Cadet',
            'ref1' => '',
            'ref2' => '',
            'ref3' => '',
            'comments' => '',

        ));
    }
}
