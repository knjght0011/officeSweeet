<?php

namespace App\Models\OS\Email;

use Illuminate\Database\Eloquent\Model;

class MailGunEvent extends Model
{
    protected $casts = [
        'data' => 'array',
    ];

    public function getDescription(){

        switch ($this->event) {
            case "delivered":
                return "Delivered";

            case "failed":
                if(isset($this->data['severity'])){
                    switch ($this->data['severity']) {
                        case "temporary":
                            if(isset($this->data['delivery-status']['message'])){
                                return "Failed - Temporary - " . $this->data['delivery-status']['message'];
                            }else{
                                return "Failed - Temporary - Unknown";
                            }
                        case "permanent":
                            if(isset($this->data['delivery-status']['message'])){
                                return "Failed - Permanent - " . $this->data['delivery-status']['message'];
                            }else{
                                return "Failed - Permanent - Unknown";
                            }
                    }
                }else{
                    return "Failed - Unknown";
                }

            case "":

                break;
            default:
                return "Unknown";
        }

    }

}
