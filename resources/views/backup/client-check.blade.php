<!---
this page is no longer used and will be removed soon. please se /Checks/check.blade.php
-->

@extends('master')

@section('content')

<div class="row">
    <div class="col-md-8">
        <label class="col-md-2 control-label" for="toselect">To Address:</label>
        <select id="toselect" name="toselect" class="form-control">
            <option value="{{ $client->address->id }}">{{ $client->address->number }} {{ $client->address->address1 }} {{ $client->address->address2 }} {{ $client->address->city }} {{ $client->address->state }} {{ $client->address->zip }}</option>
        </select>
    </div>
</div>

<style>
#check { 
    position: relative;

    border:1px solid black; 
    width:290mm; 
    height:140mm;

    background-image: url("/images/samplecheck.jpg");
    background-size: 100% 100%;
    background-repeat: no-repeat;
}   

#fromaddress { 
    position: absolute;

    left: 63mm;
    top: 78mm;
    width: 70mm;
    height: 30mm;
    padding: 5px; 

}
#checknumber { 
    position: absolute;

    left: 240mm;
    top: 12mm;
    width: 40mm;
    padding: 5px; 
    text-align: right;
} 
#date { 
    position: absolute;

    left: 184mm;
    top: 29mm;
    width: 55mm;

    padding: 5px; 

} 

#payto { 
    position: absolute;

    left: 40mm;
    top: 50mm;
    width: 175mm;
    padding: 5px; 

}
#amountnumber { 
    position: absolute;

    left: 224mm;
    top: 50mm;
    width: 50mm;
    padding: 5px; 

}
#amounttext { 
    position: absolute;

    left: 15mm;
    top: 66mm;
    width: 200mm;
    padding: 5px; 

}
#memo { 
    position: absolute;

    left: 30mm;
    top: 105mm;
    width: 82mm;
    padding: 5px; 

}

#checknum { 
    position: absolute;

    left: 240mm;
    top: 10mm;
    width: 30mm;
    padding: 5px; 

}
</style>

<div id="check"> 
    <div id="fromaddress">
        {{$client->name}}<br/>
        {{$client->address->number}} {{$client->address->address1}}<br/>
        {{$client->address->address2}}<br/>
        {{$client->address->city}}, {{$client->address->state}} {{$client->address->zip}}
    </div>
    <div id="checknum">
        <Input type="text" class="noborder form-control input-md" Value="1000">
    </div>
    <div id="date">
        <Input type="date" class="noborder form-control input-md" value="<?php echo date('Y-m-d'); ?>">
    </div>
    <div id="payto">
        <input id="paytoinput" class="noborder form-control input-md" type="text" value="{{ $client->name }}">
    </div>
    <div id="amounttext">
        <input id="amounttext-input" class="noborder form-control input-md" name="amounttext-input" type="text" >
    </div>
    <div id="amountnumber">
        <input id="amountnumber-input" class="noborder form-control input-md" name="amountnumber-input" type="text" onblur="UpdateWords();">
    </div>
    <div id="memo">
        <input id="memo-input" class="noborder form-control input-md" name="memo-input" type="text" >
    </div>
</div>

<script>

// American Numbering System
var th = ['', 'thousand', 'million', 'billion', 'trillion'];

var dg = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];

var tn = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];

var tw = ['twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

function toWords(s) {
    s = s.toString();
    s = s.replace(/[\, ]/g, '');
    if (s != parseFloat(s)) return 'not a number';
    var x = s.indexOf('.');
    if (x == -1) x = s.length;
    if (x > 15) return 'too big';
    var n = s.split('');
    var str = '';
    var sk = 0;
    for (var i = 0; i < x; i++) {
        if ((x - i) % 3 == 2) {
            if (n[i] == '1') {
                str += tn[Number(n[i + 1])] + ' ';
                i++;
                sk = 1;
            } else if (n[i] != 0) {
                str += tw[n[i] - 2] + ' ';
                sk = 1;
            }
        } else if (n[i] != 0) {
            str += dg[n[i]] + ' ';
            if ((x - i) % 3 == 0) str += 'hundred ';
            sk = 1;
        }
        if ((x - i) % 3 == 1) {
            if (sk) str += th[(x - i - 1) / 3] + ' ';
            sk = 0;
        }
    }
    return str.replace(/\s+/g, ' ');

}

function CheckNumbersToWords(s)
{
    s = s.toString();
    var n = s.split('.');
    var Words = toWords(n[0]);
     Words += ' and ';
    if (s.indexOf('.' > 0)) {
        if (n[1] == 00 )
        {
            Words += 'and no cents';
        }else
        {
            Words += toWords(n[1]);
            Words += '/100  ********';
        }
    }else{
        Words += 'and no cents';
    }
    return Words;
}

function UpdateWords()
{
  $NumberText =  CheckNumbersToWords(document.getElementById('amountnumber-input').value);
  $FinalNumber = $NumberText.substr(0,1).toUpperCase() 
  $FinalNumber += $NumberText.substring(1, $NumberText.length);
  $('#amounttext-input').val($FinalNumber);
}

$('#branchselect').change(function(){
    switch($(this).val()) {
        
        @foreach($branches as $branch)
        case '{{ $branch->id }}':
            $('#fromaddress').html('{{$client->name}}<br>{{$branch->number}} {{$branch->address1}}<br>{{$branch->address2}}<br>{{$branch->city}}, {{$branch->state}} {{$branch->zip}}');
            break;
        @endforeach    
        default:

    };
});
$('#branchselect').val("{{ Auth::user()->branch_id }}").change();

</script>

<br/>
<br/>
<Select multiple style="width:300px; height:200px;" class="form-control">
   @foreach($ExpenseAccountCategorys as $ExpenseAccountCategory)
        <option value="{{ $ExpenseAccountCategory->category }}">{{ $ExpenseAccountCategory->category }}</option>
        @foreach($ExpenseAccountCategory->subcategories as $SubCategory)
            <option value="{{ $SubCategory->subcategory }}">&emsp;--{{ $SubCategory->subcategory }}</option>
        @endforeach
   @endforeach
</Select>
@stop
