
<?php    
if(!empty(Session::get('settings'))){ 
    $settings = Session::get('settings');
    $setting = Session::get('setting');
}
?>
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
    <link rel="stylesheet" href="{{ url('/includes/pdf.css') }}">
    <style>

    
    #payee {
        position: absolute;
        left: {{ $checksettings['PayToLeft'] }}mm;   
        top: {{ $checksettings['PayToTop'] }}mm;
        width: {{ $checksettings['PayToWidth'] }}mm;
        height: 30mm;
        font-weight: bold;
        font-size: 75%;        
        padding: 5px; 
    }
    
    #date {
        position: absolute;
        left: {{ $checksettings['DateLeft'] }}mm;   
        top: {{ $checksettings['DateTop'] }}mm;
        width: {{ $checksettings['DateWidth'] }}mm;
        height: 20mm;
        font-weight: bold;
        font-size: 75%;        
        padding: 5px; 
    }
    
    #amountwords {
        position: absolute;
        left: {{ $checksettings['AmountTextLeft'] }}mm;   
        top: {{ $checksettings['AmountTextTop'] }}mm;
        width: {{ $checksettings['AmountTextWidth'] }}mm;
        height: 20mm;
        font-weight: bold;
        font-size: 75%;        
        padding: 5px; 
    }
    
    #memo {
        position: absolute;
        left: {{ $checksettings['memoLeft'] }}mm;   
        top: {{ $checksettings['memoTop'] }}mm;
        width: {{ $checksettings['memoWidth'] }}mm;
        height: 20mm;
        font-weight: bold;
        font-size: 75%;        
        padding: 5px; 
    }
    
    #amount {
        position: absolute;
        left: {{ $checksettings['AmountNumLeft'] }}mm;   
        top: {{ $checksettings['AmountNumTop'] }}mm;
        width: {{ $checksettings['AmountNumWidth'] }}mm;
        height: 20mm;
        font-weight: bold;
        font-size: 75%;        
        padding: 5px; 
    }
    
    #toaddess {
         position: absolute;
         left: {{ $checksettings['ToLeft'] }}mm;
         top: {{ $checksettings['ToTop'] }}mm;
         width: {{ $checksettings['ToWidth'] }}mm;
         height: 30mm;
         font-weight: bold;
         font-size: 75%;
         padding: 5px;
     }

    #StubHeader {
        position: absolute;
        left: 20mm;
        top: 120mm;
        width: 200mm;
        height: 30mm;
        font-weight: bold;
        font-size: 75%;
        padding: 5px;
    }
    #StubDetail {
        position: absolute;
        left: 20mm;
        top: 135mm;
        width: 200mm;
        height: 30mm;
        font-weight: bold;
        font-size: 75%;
        padding: 5px;
    }

    #StubHeader2 {
        position: absolute;
        left: 20mm;
        top: 220mm;
        width: 200mm;
        height: 30mm;
        font-weight: bold;
        font-size: 75%;
        padding: 5px;
    }
    #StubDetail2 {
        position: absolute;
        left: 20mm;
        top: 235mm;
        width: 200mm;
        height: 30mm;
        font-weight: bold;
        font-size: 75%;
        padding: 5px;
    }
    </style>
</head>
