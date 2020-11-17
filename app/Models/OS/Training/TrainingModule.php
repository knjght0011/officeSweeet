<?php
namespace App\Models\OS\Training;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingModule extends Model
{
    use SoftDeletes;

    protected $table = 'trainingmodules';

    public function TrainingRequests()
    {
        return $this->hasMany('App\Models\OS\Training\TrainingRequest', 'training_id', 'id');
    }

    public function FormatedLink(){
        if($this->quiz != ""){
            if (!preg_match("~^(?:f|ht)tps?://~i", $this->link)) {
                $url = "http://" . $this->link;
            }else{
                $url = $this->link;
            }
            return $url;
        }else{
            return "";
        }
    }

    public function FormatedQuiz(){
        if($this->quiz != ""){
            if (!preg_match("~^(?:f|ht)tps?://~i", $this->quiz)) {
                $url = "http://" . $this->quiz;
            }else{
                $url = $this->quiz;
            }
            return $url;
        }else{
            return "";
        }
    }
}
