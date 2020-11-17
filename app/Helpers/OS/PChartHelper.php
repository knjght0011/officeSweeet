<?php

namespace App\Helpers\OS;

use Illuminate\Support\Facades\Auth;

use CpChart\Data;
use CpChart\Chart\Pie;
use CpChart\Image;

class PChartHelper
{

    public static function ConvertToBase64($image)
    {
        ob_start();
        imagepng($image->Picture);
        $contents = ob_get_contents();
        ob_end_clean();

        $b64 = base64_encode($contents);

        return $b64;
    }

    public static function Palette($number){

        if($number < 22){
            $number = 22;
        }

        $total = hexdec("ffffff") / $number;

        $array = array();

        $start = 0 + $total;

        for ($x = 0; $x <= $number; $x++) {
            $hex = dechex($start);
            $hex = str_pad($hex, 6, "0", STR_PAD_LEFT);

            $r = substr($hex,0,2);
            $g = substr($hex,2,2);
            $b = substr($hex,4,2);

            $color = array("R"=> hexdec($r),"G"=> hexdec($g),"B"=> hexdec($b),"Alpha"=>100);

            $array[] = $color;

            $start = $start + $total;
        }

        return array_splice($array, 0, 1);
    }

    public static function PieChart($chartdata, $title = "Pie Chart")
    {

        $MyData = new Data();

        $array1 = array();
        $array2 = array();
        foreach ($chartdata as $key => $value) {
            array_push($array1, $key);
            array_push($array2, $value);
            #$MyData->addPoints($value, $key);
        }

        $MyData->addPoints($array2, "Value");

        $MyData->addPoints($array1, "Legend");

        $MyData->loadPalette(base_path() . "/vendor/szymach/c-pchart/resources/palettes/blind.color", TRUE);

        $MyData->setAbscissa("Legend");

        $myPicture = new Image(1000, 500, $MyData);

        $myPicture->drawGradientArea(0, 0, 1000, 500, DIRECTION_HORIZONTAL, array("StartR" => 220, "StartG" => 220, "StartB" => 220, "EndR" => 180, "EndG" => 180, "EndB" => 180, "Alpha" => 100));

        $myPicture->drawRectangle(0, 0, 999, 499, array("R" => 0, "G" => 0, "B" => 0));

        /* Write the chart title */
        $myPicture->setFontProperties(array("FontName" => "../fonts/Forgotte.ttf", "FontSize" => 11));
        $myPicture->drawText(500, 40, $title, array("FontSize" => 20, "Align" => TEXT_ALIGN_BOTTOMMIDDLE));

        $PieChart = new Pie($myPicture, $MyData);

        $myPicture->setShadow(true);

        $myPicture->setFontProperties(array("FontName" => "../fonts/Forgotte.ttf", "FontSize" => 16, "R" => 80, "G" => 80, "B" => 80));

        $PieChart->draw3DPie(600, 250, array("Radius" => 300, "DataGapAngle" => 3, "DataGapRadius" => 3, "Border" => TRUE));

        $myPicture->setShadow(FALSE);

        $PieChart->drawPieLegend(15, 40, array("Alpha" => 20));

        return self::ConvertToBase64($myPicture);
        #$myPicture->autoOutput("pie.png");
    }

    public static function BarChart($chartdata, $axisname = "Axis", $title = "Pie Chart")
    {

        /* Create and populate the pData object */
        $MyData = new Data();
        $array1 = array();
        $array2 = array();
        foreach ($chartdata as $key => $value) {
            array_push($array1, $key);
            array_push($array2, $value);
            #$MyData->addPoints($value, $key);
        }

        $MyData->addPoints($array2, "Value");

        $MyData->addPoints($array1, "Labels");

        $MyData->loadPalette(base_path() . "/vendor/szymach/c-pchart/resources/palettes/blind.color", TRUE);

        #$MyData->setSerieTicks("Probe 2",4);
        $MyData->setAxisName(0, $axisname);

        #$MyData->addPoints(array("Jan","Feb","Mar","Apr","May","Jun"),"Labels");
        #$MyData->setSerieDescription("Labels","Months");
        $MyData->setAbscissa("Labels");

        /* Create the pChart object */
        $myPicture = new Image(1000, 500, $MyData);

        /* Draw the background */
        $Settings = array("R" => 170, "G" => 183, "B" => 87, "Dash" => 1, "DashR" => 190, "DashG" => 203, "DashB" => 107);
        $myPicture->drawFilledRectangle(0, 0, 700, 230, $Settings);

        /* Overlay with a gradient */
        $Settings = array("StartR" => 219, "StartG" => 231, "StartB" => 139, "EndR" => 1, "EndG" => 138, "EndB" => 68, "Alpha" => 50);
        $myPicture->drawGradientArea(0, 0, 1000, 500, DIRECTION_HORIZONTAL, array("StartR" => 220, "StartG" => 220, "StartB" => 220, "EndR" => 180, "EndG" => 180, "EndB" => 180, "Alpha" => 100));

        $myPicture->drawRectangle(0, 0, 999, 499, array("R" => 0, "G" => 0, "B" => 0));

        /* Write the chart title */
        $myPicture->setFontProperties(array("FontName" => "../fonts/Forgotte.ttf", "FontSize" => 11));
        $myPicture->drawText(500, 40, $title, array("FontSize" => 20, "Align" => TEXT_ALIGN_BOTTOMMIDDLE));

        /* Draw the scale and the 2nd chart */
        $myPicture->setFontProperties(array("FontName" => "../fonts/Forgotte.ttf", "FontSize" => 16, "R" => 80, "G" => 80, "B" => 80));
        $myPicture->setGraphArea(200, 40, 950, 450);
        $myPicture->drawScale(["Pos" => SCALE_POS_TOPBOTTOM, "mode" => SCALE_MODE_START0, "RemoveYAxis" => TRUE]);

        $myPicture->setShadow(TRUE, array("X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));

        $myPicture->drawBarChart(array("DisplayPos" => LABEL_POS_INSIDE, "DisplayValues" => TRUE, "Rounded" => false, "Surrounding" => 30));
        $myPicture->setShadow(FALSE);

        /* Write the chart legend */
        #$myPicture->drawLegend(510,205,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

        /* Render the picture (choose the best way) */
        return self::ConvertToBase64($myPicture);
        #$myPicture->autoOutput("pictures/example.drawBarChart.png");
    }

    public static function LineChart($chartdata, $axisname = "Axis", $title = "Pie Chart")
    {

        /* Create and populate the pData object */
        $MyData = new Data();
        $array1 = array();
        $array2 = array();
        foreach ($chartdata as $key => $value) {
            array_push($array1, $key);
            array_push($array2, $value);
            #$MyData->addPoints($value, $key);
        }

        $MyData->addPoints($array2, "Value");

        $MyData->addPoints($array1, "Labels");

        $MyData->loadPalette(base_path() . "/vendor/szymach/c-pchart/resources/palettes/blind.color", TRUE);

        #$MyData->setSerieTicks("Probe 2",4);
        $MyData->setAxisName(0, $axisname);

        #$MyData->addPoints(array("Jan","Feb","Mar","Apr","May","Jun"),"Labels");
        #$MyData->setSerieDescription("Labels","Months");
        $MyData->setAbscissa("Labels");

        /* Create the pChart object */
        $myPicture = new Image(1000, 500, $MyData);

        /* Draw the background */
        $Settings = array("R" => 170, "G" => 183, "B" => 87, "Dash" => 1, "DashR" => 190, "DashG" => 203, "DashB" => 107);
        $myPicture->drawFilledRectangle(0, 0, 700, 230, $Settings);

        /* Overlay with a gradient */
        $Settings = array("StartR" => 219, "StartG" => 231, "StartB" => 139, "EndR" => 1, "EndG" => 138, "EndB" => 68, "Alpha" => 50);
        $myPicture->drawGradientArea(0, 0, 1000, 500, DIRECTION_HORIZONTAL, array("StartR" => 220, "StartG" => 220, "StartB" => 220, "EndR" => 180, "EndG" => 180, "EndB" => 180, "Alpha" => 100));

        $myPicture->drawRectangle(0, 0, 999, 499, array("R" => 0, "G" => 0, "B" => 0));

        /* Write the chart title */
        $myPicture->setFontProperties(array("FontName" => "../fonts/Forgotte.ttf", "FontSize" => 11));
        $myPicture->drawText(500, 40, $title, array("FontSize" => 20, "Align" => TEXT_ALIGN_BOTTOMMIDDLE));

        /* Draw the scale and the 2nd chart */
        $myPicture->setFontProperties(array("FontName" => "../fonts/Forgotte.ttf", "FontSize" => 10, "R" => 80, "G" => 80, "B" => 80));
        $myPicture->setGraphArea(75, 40, 950, 450);
        $myPicture->drawScale(array("DrawSubTicks" => TRUE));
        $myPicture->setShadow(TRUE, array("X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));
        $myPicture->setFontProperties(array("FontName" => "../fonts/Forgotte.ttf", "FontSize" => 6));
        $myPicture->drawLineChart(array("DisplayValues" => TRUE, "DisplayColor" => DISPLAY_AUTO));
        $myPicture->setShadow(FALSE);

        /* Write the chart legend */
        #$myPicture->drawLegend(510,205,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

        /* Render the picture (choose the best way) */
        return self::ConvertToBase64($myPicture);
        #$myPicture->autoOutput("pictures/example.drawBarChart.png");
    }

    public static function ExpensePage($chartdata, $title)
    {

        //$title = "Expenses";

        $sizeX = 2100;
        $sizey = 2970;

        $MyData = new Data();
        $array1 = array();
        $array2 = array();
        foreach ($chartdata as $key => $value) {
            if(strlen($key) > 25){
                $array = explode("-",$key);
                if(count($array) === 2){
                    if(strlen($array[1]) > 25){
                        array_push($array1, substr($array[1],0, 25) . "..." );
                    }else{
                        array_push($array1, $array[1]);
                    }
                }else{
                    array_push($array1,  substr($key,0, 25) . "..." );
                }
            }else{
                array_push($array1, $key);
            }
            array_push($array2, $value);
        }
        $MyData->addPoints($array2, "Value");
        $MyData->addPoints($array1, "Labels");
        $MyData->setAxisName(0, "");
        $MyData->setAbscissa("Labels");

        /* Create the per bar palette */
        $Palette = self::Palette(count($array1));

        $myPicture = new Image($sizeX, $sizey, $MyData);
        $myPicture->drawGradientArea(0, 0, 2100, 2970, DIRECTION_HORIZONTAL, array("StartR" => 220, "StartG" => 220, "StartB" => 220, "EndR" => 180, "EndG" => 180, "EndB" => 180, "Alpha" => 100));
        $myPicture->setFontProperties(array("FontName" => "../fonts/Forgotte.ttf", "FontSize" => 11));
        $myPicture->drawText(1100, 150, $title, array("FontSize" => 50, "Align" => TEXT_ALIGN_BOTTOMMIDDLE));

        //Draw Pie Chart
        $PieChart = new Pie($myPicture, $MyData);

        foreach($Palette as $key => $value){
            $PieChart->setSliceColor($key,$value);
        }

        $myPicture->setShadow(true);
        $myPicture->setFontProperties(array("FontName" => "../fonts/Forgotte.ttf", "FontSize" => 36, "R" => 80, "G" => 80, "B" => 80));
        $PieChart->draw3DPie(1200, 742, array("Radius" => 600, "DataGapAngle" => 3, "DataGapRadius" => 3, "Border" => TRUE));
        $myPicture->setShadow(FALSE);
        $myPicture->setShadow(TRUE, array("X" => 2, "Y" => 2, "R" => 150, "G" => 150, "B" => 150, "Alpha" => 100));
        $PieChart->drawPieLegend(60, 300, array("Alpha" => 20, "BoxSize" => 25, "FontSize" => 40, "Style" => LEGEND_ROUND, "BorderR" => 0, "BorderG" => 0, "BorderB" => 0, "Margin" => 15,"Mode"=>LEGEND_VERTICAL, "Family" => LEGEND_FAMILY_LINE));

        //Draw Bar Graph
        $myPicture->setFontProperties(array("FontName" => "../fonts/Forgotte.ttf", "FontSize" => 36, "R" => 80, "G" => 80, "B" => 80));
        $myPicture->setGraphArea(500, 1485, 2100, 2900);
        $myPicture->drawScale(["Pos" => SCALE_POS_TOPBOTTOM, "mode" => SCALE_MODE_START0, "RemoveYAxis" => TRUE]);
        $myPicture->setShadow(TRUE, array("X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10));
        $myPicture->drawBarChart(array("DisplayPos" => LABEL_POS_INSIDE, "DisplayValues" => TRUE, "Rounded" => false, "Surrounding" => 30, "OverrideColors"=>$Palette));
        $myPicture->setShadow(FALSE);

        return self::ConvertToBase64($myPicture);
    }

    public static function PieChartold($chartdata)
    {

        $data = new Data();
        foreach ($chartdata as $key => $value) {
            $data->addPoints($value, $key);
        }


        $data->setSerieTicks("Probe 2", 4);
        $data->setAxisName(0, "Temperatures");

        // Create the Image object
        $image = new Image(700, 230, $data);

        /* Turn off Antialiasing */
        $image->Antialias = false;

        /* Add a border to the picture */
        $image->drawRectangle(0, 0, 699, 229, ["R" => 0, "G" => 0, "B" => 0]);

        /* Write the chart title */
        $image->setFontProperties(["FontName" => "Forgotte.ttf", "FontSize" => 11]);
        $image->drawText(150, 35, "Average temperature", ["FontSize" => 20, "Align" => TEXT_ALIGN_BOTTOMMIDDLE]);

        /* Set the default font */
        $image->setFontProperties(["FontName" => "pf_arma_five.ttf", "FontSize" => 6]);

        /* Define the chart area */
        $image->setGraphArea(60, 40, 650, 200);

        /* Draw the scale */
        $scaleSettings = [
            "XMargin" => 10,
            "YMargin" => 10,
            "Floating" => true,
            "GridR" => 200,
            "GridG" => 200,
            "GridB" => 200,
            "DrawSubTicks" => true,
            "CycleBackground" => true
        ];
        $image->drawScale($scaleSettings);

        /* Write the chart legend */
        $image->drawLegend(600, 20, ["Style" => LEGEND_NOBORDER, "Mode" => LEGEND_HORIZONTAL]);

        /* Turn on Antialiasing */
        $image->Antialias = true;

        /* Enable shadow computing */
        $image->setShadow(true, ["X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10]);

        /* Draw the area chart */
        $Threshold = [];
        $Threshold[] = ["Min" => 0, "Max" => 5, "R" => 207, "G" => 240, "B" => 20, "Alpha" => 70];
        $Threshold[] = ["Min" => 5, "Max" => 10, "R" => 240, "G" => 232, "B" => 20, "Alpha" => 70];
        $Threshold[] = ["Min" => 10, "Max" => 20, "R" => 240, "G" => 191, "B" => 20, "Alpha" => 70];

        $image->draw3DPie(150, 100, array("Radius" => 80, "DrawLabels" => TRUE, "DataGapAngle" => 10, "DataGapRadius" => 6, "Border" => TRUE));
        #$image->drawAreaChart(["Threshold" => $Threshold]);

        /* Write the thresholds */
        #$image->drawThreshold(5, ["WriteCaption" => true, "Caption" => "Warn Zone", "Alpha" => 70, "Ticks" => 2, "R" => 0, "G" => 0, "B" => 255]);
        #$image->drawThreshold(10, ["WriteCaption" => true, "Caption" => "Error Zone", "Alpha" => 70, "Ticks" => 2, "R" => 0, "G" => 0, "B" => 255]);

        return self::ConvertToBase64($image);

    }


}