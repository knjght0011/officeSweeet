<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <meta name="description" content="">
    <meta name="Keywords" content="">
    <meta name="robots" content="all" />
    
    <title>Office Sweeet</title>
    
    <!--css-->
    <link rel="stylesheet" href="{{ url('/includes/bootstrap/css/bootstrap.min.css') }}">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            font: 10pt "Tahoma";
        }

        html { margin: 0px}

        .page {
            width: 205mm;
            height: 264mm;
            border: 1px #D3D3D3 solid;
            position: relative;
            padding: 5mm;
            margin: 0;
        }

        #title {
            /*height: 7mm; */
            width: 100%;
            text-align: center;
            font: bold 15px Helvetica, Sans-Serif;
            margin-bottom: 5mm;
        }

        #header {
            height: 7mm;
            width: 100%;
            background: #222;
            text-align: center;
            color: white;
            font: bold 15px Helvetica, Sans-Serif;
            letter-spacing: 20px;
        }

        .contents{
            width: 100%;
            margin: 30px 0 0 0;
            position: relative;
            top: 70mm;
        }

        #items {
            width: 100%;

        }
        #items th { border: 1px solid black; padding: 5px; background: #eee; }
        #items textarea { width: 80px; height: 50px; }
        #items tr.item-row td { border: 1px solid black; padding: 5px; vertical-align: top; }
        #items td.description { border: 1px solid black; padding: 5px; width: 300px; }
        #items td.item-name { border: 1px solid black; padding: 5px; width: 3em; }
        #items td.description textarea, #items td.item-name textarea { width: 100%; }
        #items td.total-line { border: 1px solid black; padding: 5px; border-right: 0; text-align: right; }
        #items td.total-value { border: 1px solid black; padding: 5px; border-left: 0; padding: 10px; }
        #items td.total-value textarea { height: 20px; background: none; }
        #items td.balance { border: 1px solid black; padding: 5px; background: #eee; }
        #items td.blank { border: 0; }

        #items td.units { border: 1px solid black; padding: 5px; width: 1em; }
        #items td.costper { border: 1px solid black; padding: 5px; width: 4em; }
        #items td.total { border: 1px solid black; padding: 5px; width: 1em; }

        .rdTable {
            width: 100%;

        }
        .rdTable th { border: 1px solid black; padding: 5px; background: #eee; }
        .rdTable tr { border-style:solid; border-color:black; border-width:1px 0 0 2px; }
        .rdTable td { padding:2px 4px; border-style:solid; border-color:black; border-width:0 1px 1px 0; }
        .rdTable tr:first-child { border-top-width:2px; }
        .rdTable tr:last-child td { border-bottom-width:2px; }
        .rdTable tr td:last-child { border-right-width:2px; }

        #meta{
            position: absolute;
            left: 150mm;
            top: 20mm;
            width: 50mm;
            height: 20mm;

        }
        #meta1 td { border: 1px solid black; padding: 5px; text-align: right; }
        #meta1 td.meta-head { border: 1px solid black; padding: 5px; text-align: left; background: #eee; }
        #meta1 td textarea { width: 100%; height: 20px; text-align: right; }

    </style>
        
</head>
