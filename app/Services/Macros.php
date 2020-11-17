<?php namespace App\Services;
 
use App\Helpers\OS\Users\UserHelper;
use Collective\Html\FormBuilder;
use App\Models\User;
 
class Macros extends \Collective\Html\FormBuilder {

    public function OSinput($id, $name, $placeholder = "", $value = "", $required = "false", $validationtype = "", $type = "text", $includebutton = false, $buttonid = "", $buttonlable = "")
    {
        $top = '<div class="input-group ">   
            <span class="input-group-addon" for="'.$id.'"><div style="width: 15em;">'.$name.':</div></span>
            <input id="'.$id.'" name="'.$id.'" type="'.$type.'" placeholder="'.$placeholder.'" value="'.$value.'" class="form-control" data-validation-label="'.$name.'" data-validation-required="'.$required.'" data-validation-type="'.$validationtype.'">';

        if($includebutton === true){
            $middle = '<span class="input-group-btn"><button id="'.$buttonid.'" class="btn btn-default" type="button">'.$buttonlable.'</button></span>';
        }else{
            $middle = '';
        }

        $bottom = '</div>';

        return $top . $middle . $bottom;
    }    
    
    public function OSinputWideLabel($id, $name, $placeholder = "", $value = "", $required = "false", $validationtype = "", $type = "text", $includebutton = false, $buttonid = "", $buttonlable = "")
    {
        $top = '<div class="input-group ">   
            <span class="input-group-addon" for="'.$id.'"><div style="width: 25em;">'.$name.':</div></span>
            <input id="'.$id.'" name="'.$id.'" type="'.$type.'" placeholder="'.$placeholder.'" value="'.$value.'" class="form-control" data-validation-label="'.$name.'" data-validation-required="'.$required.'" data-validation-type="'.$validationtype.'">';

        if($includebutton === true){
            $middle = '<span class="input-group-btn"><button id="'.$buttonid.'" class="btn btn-default" type="button">'.$buttonlable.'</button></span>';
        }else{
            $middle = '';
        }

        $bottom = '</div>';

        return $top . $middle . $bottom;
    } 
    
    public function OSLockedInput($id, $name, $placeholder = "", $value = "", $required = "false", $validationtype = "", $type = "text")
    {
        $top = '<div class="input-group ">   
            <span class="input-group-addon" for="'.$id.'"><div style="width: 15em;">'.$name.':</div></span>
            <input id="'.$id.'" name="'.$id.'" type="'.$type.'" placeholder="'.$placeholder.'" value="'.$value.'" class="form-control" data-validation-label="'.$name.'" data-validation-required="'.$required.'" data-validation-type="'.$validationtype.'">';

        $middle = '<span class="input-group-btn"><button id="'.$id.'-unlock" class="btn btn-default" type="button"><span class="glyphicon glyphicon-lock"></span> Unlock</button></span>';
  

        $bottom = '</div>'
                . '<script>'
                . '$("#'.$id.'").prop("disabled", true);'
                . '$("#'.$id.'-unlock").click(function(e) {'
                . '$("#'.$id.'").prop("disabled", false);'
                . '});'
                . '</script>';

        return $top . $middle . $bottom;
    } 

    public function OSselect($id, $name, $options = array(), $placeholder = "", $value = "", $required = "false", $validationtype = "")
    {
        $top = '<div class="input-group ">   
            <span class="input-group-addon" for="'.$id.'"><div style="width: 15em;">'.$name.':</div></span>
            <select id="'.$id.'" name="'.$id.'" type="text" placeholder="'.$placeholder.'" class="form-control" data-validation-label="'.$name.'" data-validation-required="'.$required.'" data-validation-type="'.$validationtype.'">';


        switch ($options) {
        case "status":
            $middle = '<option style="background-color: #d9edf7;" value="Important">Important</option>'
                . '<option style="background-color: #fcf8e3;" value="Urgent">Urgent</option>'
                . '<option style="background-color: #f2dede;" value="Critical">Critical</option>'
                . '<option style="background-color: #dff0d8;" value="Complete">Complete</option>';
            break;
        case "users":
            $users = UserHelper::GetAllUsers();
            $middle = "";
            foreach($users as $user){
                $middle = $middle . '<option value="'.$user->id.'">'. $user->getShortName() .'</option>';
            }
            break;
        default:
            $middle = "";
            foreach($options as $key => $name){
                if($key === $value){
                    $middle = $middle . '<option value="'.$key.'" selected="selected">'. $name .'</option>';
                }else{
                    $middle = $middle . '<option value="'.$key.'">'. $name .'</option>';
                }

            }
            break;
        }

        $bottom = '</select></div>';

        return $top . $middle . $bottom;
    }    

    public function OStextarea($id, $name, $placeholder = "", $value = "", $required = "false", $validationtype = "", $rows = "5")
    {
        return '<div class="input-group ">   
            <span class="input-group-addon" for="'.$id.'"><div style="width: 15em;">'.$name.':</div></span>
            <textarea style="resize: none;" rows="'.$rows.'" id="'.$id.'" name="'.$id.'" placeholder="'.$placeholder.'" class="form-control" data-validation-label="'.$name.'" data-validation-required="'.$required.'" data-validation-type="'.$validationtype.'">'.$value.'</textarea>
        </div>';

    } 
    
    public function OSdatepicker($id, $name, $value = "Click here to select a date", $placeholder = "", $required = "false", $validationtype = "" )
    {
        if($value == null){
            $value = "Click here to select a date";
        }
        $top = '<div class="input-group ">   
            <span class="input-group-addon" for="'.$id.'"><div style="width: 15em;">'.$name.':</div></span>
            <input id="'.$id.'" name="'.$id.'" type="text" placeholder="'.$placeholder.'" value="'.$value.'" class="form-control" data-validation-label="'.$name.'" data-validation-required="'.$required.'" data-validation-type="'.$validationtype.'" readonly></div>';

        $bottom = '<script>$("#'.$id.'").val("' . $value . '");
                $("#'.$id.'").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    inline: true,
                    dateFormat: "yy-mm-dd",
                });</script>';

        return $top . $bottom;
    } 
}
/*

*/