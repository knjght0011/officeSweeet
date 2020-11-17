<?php
namespace App\Helpers\OS;

class CustomTabHelper
{

    public static function GenerateHTML($fields){
        if(isset($fields['col1'])){
            $col1 = self::GenerateHTMLCol("col1", $fields['col1']);
        }else{
            $col1 = "<div id='col1' class='col-md-4'></div>";
        }
        if(isset($fields['col2'])){
            $col2 = self::GenerateHTMLCol("col2", $fields['col2']);
        }else{
            $col2 = "<div id='col2' class='col-md-4'></div>";
        }
        if(isset($fields['col3'])){
            $col3 = self::GenerateHTMLCol("col3", $fields['col3']);
        }else{
            $col3 = "<div id='col3' class='col-md-4'></div>";
        }
        return  $col1 . $col2 . $col3;
    }

    public static function GenerateHTMLCol($id, $col){

        $html = "<div id='".$id."' class='col-md-4'>";

        foreach($col as $key => $val){
            if(is_array($val)){
                $html .= self::GenerateHTMLSelect($key, $val);
            }else{
                $text = explode ( "," , $val );

                switch ($text[0]) {
                    case "input":
                        $html .= self::GenerateHTMLInput($key);
                        break;
                    case "date":
                        $html .= self::GenerateHTMLDate($key);
                        break;
                    case "textarea":
                        $html .= self::GenerateHTMLTextArea($key, $text[1]);
                        break;
                    case "":

                        break;
                    default:

                }
            }
        }

        return $html . "</div>";
    }

    public static function GenerateHTMLInput($name){
        $label = str_replace("_"," ",$name);

        return "<div class='element input-group'><span class='input-group-addon' for='customtab-input-".$name."'><div style='min-width: 7em;'>".$label.":</div></span><input class='form-control' id='customtab-input-".$name."'  name='".$name."'></div>";
    }

    public static function GenerateHTMLDate($name){
        $label = str_replace("_"," ",$name);

        return "<div class='element input-group'>
                    <span class='input-group-addon' for='customtab-input-".$name."'>
                        <div style='min-width: 7em;'>".$label.":</div>
                    </span>
                    <input class='form-control date' id='customtab-input-".$name."'  name='".$name."' readonly>        
                    <span class='input-group-btn'>
                        <button id='' class='btn btn-default datebutton' type='button'>Add To Schedule</button>
                    </span>
                </div>";
    }

    public static function GenerateHTMLTextArea($name, $rows){
        $label = str_replace("_"," ",$name);

        return "<div class='element input-group'><span class='input-group-addon' for='customtab-input-".$name."'><div style='min-width: 7em;'>".$label."</div></span><textarea style='resize: none' class='form-control' id='customtab-input-".$name."'  name='".$name."'  rows='".$rows."'> </textarea></div>";

    }

    public static function GenerateHTMLSelect($name, $options){
        $label = str_replace("_"," ",$name);

        $element = "<div class='element input-group'><span class='input-group-addon' for='customtab-input-".$name."'><div style='min-width: 7em;'>".$label.":</div></span><select id='customtab-input-".$name."' name='".$name."' class='form-control'>";

        foreach($options as $option){
            $element .= "<option value='".$option."'>".$option."</option>";
        }

        $element .= "</select></div>";

        return $element;
    }

}