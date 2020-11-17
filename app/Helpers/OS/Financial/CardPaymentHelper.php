<?php
namespace App\Helpers\OS\Financial;

use Inacho\CreditCard;

#use Carbon\Carbon;

use App\Models\OS\Financial\TransnationalTransaction;
#use App\Models\OS\Financial\PayrollUser;

use App\Classes\tngwapi;

class CardPaymentHelper
{
    public static function ValidateCardData($data){
        $card = CreditCard::validCreditCard($data['cardNumber']);
        if($card['valid']){
            //valid
            if(CreditCard::validCvc($data['cardCVC'], $card['type'])){
                if(CreditCard::validDate($data['cardExpiryYear'], $data['cardExpiryMonth'])){
                    return "valid";
                }else{
                    //invalid date
                    return "4:invaliddate";
                }
            }else{
                //invalid cvc
                return "4:invalidcvc";
            }
        }else{
            //invalid card number
            return "4:invalidcardnumber";
        }
    }

    public static function ValidateCardSwipeData($data){
        $card = CreditCard::validCreditCard($data['cardNumber']);
        if($card['valid']){
            //valid
            if(CreditCard::validDate($data['cardExpiryYear'], $data['cardExpiryMonth'])){
                return "valid";
            }else{
                //invalid date
                return "Invalid Date";
            }
        }else{
            //invalid card number
            return "Invalid Card Number";
        }
    }

    public static function ProcessPayment($data)
    {
        $gw = new tngwapi;
        if($gw->checkLogin()){
            
            $gw->setOrder($data['orderid'],$data['orderdescription'],$data['tax'],$data['ipaddress']);
            
            $gw->setBilling($data['firstname'],$data['lastname'], "None",$data['address1'],$data['address2'],$data['city'],$data['state'],$data['zip'],$data['country'], "none", $data['email']);
            
            $r = $gw->doSale($data['amount'] , $data['cardNumber'] , $data['cardExpiryMonth']. "/" . $data['cardExpiryYear'] , $data['cardCVC']);
     
            return self::StoreResponce($gw->responses);
        }else{
            return false;
        }
    }
    
    public static function StoreResponce($responce){
        $transaction = new TransnationalTransaction;
        #$transaction->update($responce);
        $transaction->response = $responce['response'];
        $transaction->responsetext = $responce['responsetext'];
        $transaction->authcode = $responce['authcode'];
        $transaction->transactionid = $responce['transactionid'];
        $transaction->avsresponse = $responce['avsresponse'];
        $transaction->cvvresponse = $responce['cvvresponse'];
        $transaction->orderid = $responce['orderid'];
        $transaction->response_code = $responce['response_code'];
        $transaction->save();
        return $transaction;
    }

    public static function FakeTransaction($data){
        $transaction = TransnationalTransaction::where('id', '37')->first();
        return $transaction;
    }

}
