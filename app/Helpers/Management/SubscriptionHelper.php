<?php
namespace App\Helpers\Management;

use App\Models\OS\Financial\TransnationalTransaction;

class SubscriptionHelper
{

    public static function GetTransaction($id, $TNdata){

        $t = new TransnationalTransaction;
        $t->setConnection('deployment');
        $transaction = $t->where('transactionid' , $id)->first();

        if(count($transaction) === 1){

            if($transaction->responsetext != $TNdata['condition']){
                $transaction->response = $TNdata['action']['response_code'];
                $transaction->responsetext  = $TNdata['condition'];
                $transaction->authcode = $TNdata['authorization_code'];
                $transaction->avsresponse = $TNdata['avs_response'];
                $transaction->response_code = $TNdata['action']['response_code'];
                $transaction->emv_auth_response_data = $TNdata['action']['response_code'];
                $transaction->save();
            }

            return $transaction;
        }else{
            $newtransaction = new TransnationalTransaction;
            $newtransaction->setConnection('deployment');
            $newtransaction->response = $TNdata['action']['response_code'];
            $newtransaction->responsetext  = $TNdata['condition'];
            $newtransaction->authcode = $TNdata['authorization_code'];
            $newtransaction->transactionid = $TNdata['transaction_id'];
            $newtransaction->avsresponse = $TNdata['avs_response'];
            $newtransaction->cvvresponse = "";
            $newtransaction->orderid = $TNdata['order_id'];
            $newtransaction->response_code = $TNdata['action']['response_code'];
            $newtransaction->emv_auth_response_data = $TNdata['action']['response_code'];
            $newtransaction->deposit_id = null;
            $newtransaction->save();

            return $newtransaction;
        }
    }
}
