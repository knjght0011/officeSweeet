<?php
namespace App\Helpers\OS\QuickBooks;

include(app_path() . '\Classes\QuickBooks\src\config.php');

use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\Facades\Invoice;

use QuickBooksOnline\API\Facades\Customer;
        
class QuickBooksHelper
{

    private static function GetDataService(){
        $dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => "Q0vnEILShROMed8sadgGKA0CrE86iNldJnCHxj9Hia4MSe44GV",
            'ClientSecret' => "0L51mJgEj1r3jqCcR0UEM7tHtsG9Pr7YpLruGMdW",

            'accessTokenKey' =>
                'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..RFui3nT8WebHBIJZgB2QPQ.YiA31ltd5o4jTvUJ8Gc1prclxwxV2rDwJunG7pHDhXvC-M62hwOZMI7tG9wkcYYCYo7b9A80p3fweEgR2EvDPzRYO2Jg6z5_nVySQYuHLGa_Pk3HiI-hOw2Ctjkxleg_kGFvQ8m_DPeIqze-tUhqJmzibTrLOVKN6oezLPZFhFFcRk38Zm2vwSp0D4k4emLSgG4IB2tW6PaE-MlgZmX_qmBFlGFQIfyF54VcUof35hN5880tOSwOu2LhycZugw1iEN38Wv1BCYvxcPRSatH5TwO3CBPGKHerSQ6DUvjlsHw8JZTmx_E7UygIV-AMhoJ-zu0dpo5i08_CzxBRosqdNA9K808aQCU1U2vLEDyhpkQo-o3myfvwD_xt46KbsY4NU3juoHrSNRaAd2CZoZXIbbEwZsyolcO6qlOjd48EwzdAdI_zUR2Bg_DH17L7s_28u3roVs5lOyzy6tMzvSEH9pmdQu4NdMRqZkfLJhAmpIZpNVQNlnr_iyEyX0IhasJyAWBTDAf88dUyqBZ8w4PQiwJ5YSdZE4MddUlHGj-335v4YgpINXR381ZP3ojZEJCo_jdS7zxJl2iCjJFdjK7qjLG5a6rlCVRiMjVLH9wnrcSW1ri58xVrtpt0hKwOx65tklPyIcUmjknRHWC2tqPH1K9N45PZvOOl4DW1fmyx9Ci1pHp6Jt5T_vioCtDvYpQgd-5y0ZIzfJTWvUPaCz1hEnz8bzJLhqxTOhmk9glRW0sqpn-HigdFMphqHT4lnBDCU2CuPw4ZSl65x0dtLVv5xr04rrfhs4DdbiwgPmswjj8R3qRnFGe9b9iMEshzVEKFGbWkgr5oYgOxLDhP86029Q.bam6_7U08MUKhu_9stn7ZA',
            'refreshTokenKey' => "L0115580383377mplkrHN0t0yARXO0MEAcmKBT29ulpIyRsq5P",
            'QBORealmID' => "193514844718939",

            'RedirectURI' => "https://developer.intuit.com/v2/OAuth2Playground/RedirectUrl",
            'scope' => "com.intuit.quickbooks.accounting", //or com.intuit.quickbooks.payment
            'baseUrl' => "Development" //Production
        ));

        if(true){
            $dataService->disableLog();
        }else{
            $dataService->setLogLocation("/Your/Path/ForLog");
        }

        return $dataService;
    }

    public static function CreateInvoice(){

        $invoiceToCreate = Invoice::create([
            "DocNumber" => "101",
            "Line" => [
                [
                    "Description" => "Johns Dancing Lesson",
                    "Amount" => 55.99,
                    "DetailType" => "SalesItemLineDetail",
                    "SalesItemLineDetail" => [
                        "ItemRef" => [
                            "value" => 1,
                            "name" => "Services"
                        ]
                    ]
                ]
            ],
            "CustomerRef" => [
                "value" => "1",
                "name" => "Alex"
            ]
        ]);

        $dataService = self::GetDataService();

        $resultObj = $dataService->Add($invoiceToCreate);

        $error = $dataService->getLastError();
        if ($error) {
            echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
            echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
            echo "The Response message is: " . $error->getResponseBody() . "\n";
        }else {
            echo "Created Id={$resultObj->Id}. Reconstructed response body:\n\n";
            $xmlBody = XmlObjectSerializer::getPostXmlFromArbitraryEntity($resultObj, $urlResource);
            echo $xmlBody . "\n";
        }

    }

    public static function CreateCustomer(){

        $Customer = Customer::create([
            "PrimaryEmailAddr"=> ["Address"=> "staylor@OfficeSweeet.com"],
            "CompanyName"=> "CompanyName",
            "DisplayName"=> "DisplayName",

            "FullyQualifiedName"=> "FullyQualifiedName",

            "GivenName"=> "Firstname",
            "FamilyName"=> "Lastname",

            "PrimaryPhone"=> ["FreeFormNumber"=> "(415) 444-6538"],
            "Active"=> true,

            "Job"=> false,
            "BalanceWithJobs"=> 85.0,

            "BillAddr"=> [
                "City"=> "Half Moon Bay",
                "Line1"=> "12 Ocean Dr.",
                "PostalCode"=> "94213",
                "Lat"=> "37.4307072",
                "Long"=> "-122.4295234",
                "CountrySubDivisionCode"=> "CA",
                "Id"=> "3"
            ],
            //"PreferredDeliveryMethod"=> "Print",
            //"Taxable"=> false,
            //"PrintOnCheckName"=> "OfficeSweeet",
            //"Balance"=> 85.0,
            //"MetaData"=> [
            //    "CreateTime"=> "2014-09-11T16:49:28-07:00",
            //    "LastUpdatedTime"=> "2014-09-18T12:56:01-07:00"
            //]
        ]);

        $dataService = self::GetDataService();

        $resultObj = $dataService->Add($Customer);

        $error = $dataService->getLastError();
        if ($error) {
            echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
            echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
            echo "The Response message is: " . $error->getResponseBody() . "\n";
        }else {
            echo "Created Id={$resultObj->Id}. Reconstructed response body:\n\n";
            $xmlBody = XmlObjectSerializer::getPostXmlFromArbitraryEntity($resultObj, $urlResource);
            echo $xmlBody . "\n";
        }

    }

    public static function GetCustomerById(){
        $Customer = Customer::create([
            "Id" => '1'
        ]);

        $dataService = self::GetDataService();

        $resultObj = $dataService->FindById($Customer);

        $error = $dataService->getLastError();
        if ($error) {
            echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
            echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
            echo "The Response message is: " . $error->getResponseBody() . "\n";
        }else {
            echo "Created Id={$resultObj->Id}. Reconstructed response body:\n\n";
            $xmlBody = XmlObjectSerializer::getPostXmlFromArbitraryEntity($resultObj, $urlResource);
            echo $xmlBody . "\n";
        }
    }


}