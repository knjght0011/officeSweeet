@extends('master')

@section('content')

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

<nav style="margin-top: 20px;" class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a id="save" href="#"><span class="glyphicon glyphicon-floppy-disk"></span> Add to Print Queue</a></li>
                <li><a href="/Checks/Queue">Goto Print Queue</a></li>
                <li><a href="#" data-toggle="modal" data-target="#SwitchPayee">Change Payee</a></li>
            </ul>
        </div>
    </div>
</nav>
<!--
<div class="row" style="margin-top: 20px;">
    <div style="float:left; width: 10em;  margin-left: 20px;"> 
        <button style="width: 100%;" class="btn OS-Button" id="save" name="save" type="button">Add to Print Queue</button>
    </div>
</div> 
-->
<input id="check-id" style="display: none;" name="branch-id" type="text" value="0" >

<div id="check"> 
    <div id="fromaddress">
        {{$data->name}}<br/>
        @if($data->address_id !== null)
        {{$data->address->number}} {{$data->address->address1}}<br/>
        {{$data->address->address2}}<br/>
        {{$data->address->city}}, {{$data->address->state}} {{$data->address->zip}}
        @endif
    </div>
    <div id="date">
        <Input id="date-input" type="text" class="noborder form-control" readonly='true'>
    </div>
    <div id="payto">
        <input id="payto-input" class="noborder form-control" type="text" value="{{ $data->getName() }}">
    </div>
    <div id="amounttext">
        <input id="amounttext-input" class="noborder form-control" name="amounttext-input" type="text" >
    </div>
    <div id="amountnumber">
        <input id="amountnumber-input" class="noborder form-control" name="amountnumber-input" type="text" onblur="UpdateWords();">
    </div>
    <div id="memo">
        <input id="memo-input" class="noborder form-control" name="memo-input" type="text" >
    </div>
</div>

<div class="input-group col-md-4">   
    <label class="input-group-addon" for="name"><div style="width: 10em;">Expense Category:</div></label> 
    <select multiple id="catagorys"  class="form-control input-md" >
        @if(isset($check))
        @foreach($check->catagorys as $key => $value)
            <option value="{{ $value }}">{{ $key }}</option>
        @endforeach
        @endif
    </select>
    <span style="height: 100%;" class="input-group-btn">
        <button style="height: 100%;" class="btn btn-default" type="button" data-toggle="modal" data-target="#SplitAmountModal">Select</button>
    </span>
</div>

@include('Checks.modals.SplitAmountModel')
@include('Checks.modals.PayeeSwitch')

<script>
$(document).ready(function() {
    @if(isset($check))
        
        $('#check-id').val("{{ $check->id }}");
        
        $("#date-input").val(moment("{{ $check->date }}").format("YYYY-MM-DD"));
        
        $("#payto-input").val("{{ $check->payto }}");
        $("#memo-input").val("{{ $check->memo }}");
        $("#amountnumber-input").val("{{ $check->GetAmount() }}");
        
        @foreach($check->catagorys as $cat)

            $('#catagorys option[value="{{ $cat }}"]').attr('selected', true);
        @endforeach
        
        UpdateWords();
        
        $type = "{{ $check->type() }}";
        
    @else
        $('#check-id').val("0");
        $checknumber = 1000;
        $type = "{{ $type }}";



    @endif



    @if($data->address_id === null)
        $.confirm({
            title: 'Alert!',
            content: 'This vendor has no address associated with it. Would you like to add an address before proceeding?',
            buttons: {
                No: function () {
                    
                },
                Yes: function () {
                    GoToPage("/Vendors/Edit/{{ $data->id }}");
                }
            }
        });
    @endif
    
    $('#date-input').val($.datepicker.formatDate('MM d, yy', new Date()));
   
    $('#date-input').datepicker({
        changeMonth: true,
        changeYear: true,
        inline: true,
        onSelect: function(dateText, inst) {
            $val = moment(dateText).format("MMMM DD, YYYY");
            $('#date-input').val($val);
        }
    });
    
    $("#save").click(function()
    {

        $id = $('#check-id').val();
        
        $date = moment($("#date-input").val()).format("YYYY-MM-DD");
        $payto = $("#payto-input").val();
        $memo = $("#memo-input").val();
        $amountnumber = $("#amountnumber-input").val();
        $catagorys = $('#catagorys option');
        
        if($catagorys.length === 0){
            $('#SplitAmountModal').modal('show');
        }else{
            $array = BuildSplitArray($amount, $catagorys);
            if($array === "error"){
                $('#SplitAmountModal').modal('show');
            }else{
                SaveCheck($id, "{{ $data->id }}", $type, $date, $memo, $amountnumber, $array, $payto);
            }
        }        
    });
});

function BuildSplitArray($total, $catagorys) {

    $array = {};
    
    $runningtotal = parseFloat(0);
    $catagorys.each( function( index, element ){
        $array[$(this).text()] = $(this).val();
        $runningtotal = parseFloat($runningtotal) + parseFloat($(this).val());
    });
    $total = parseFloat($total.toFixed(2));
    if(parseFloat($total) === parseFloat($runningtotal)){
        return $array;
    }else{
        return "error";
    }
}

function SaveCheck($id, $data_id, $type, $checkdate, $memo, $amount, $catagorys, $payto) {


    $("body").addClass("loading");
    ResetServerValidationErrors();

    $post = $.post("/Checks/Queue/Save",
    {
        _token: "{{ csrf_token() }}",
        id: $id,
        data_id: $data_id,
        type: $type,
        date: $checkdate,
        memo: $memo,
        amount: $amount,
        catagorys: $catagorys,
        payto: $payto
    });

    $post.done(function(data) {
        console.log(data);
        $("body").removeClass("loading");
        if ($.isNumeric(data)) 
        {
            $('#check-id').val(data);
            $.dialog({
                title: 'Success!',
                content: 'Check has been saved to check queue!'
            });  
        }else{
            ServerValidationErrors(data);
        }
    });

    $post.fail(function() {
        $("body").removeClass("loading");
        bootstrap_alert.warning("Failed to contact server", 'danger', 4000);
    });
}


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
     Words += 'and ';
    if (s.indexOf('.' > 0)) {
        if (n[1] == 00 )
        {
            Words += 'no cents';
        }else
        {
            Words += toWords(n[1]);
            Words += '/100  ********';
        }
    }else{
        Words += 'no cents';
    }
    return Words;
}

function UpdateWords()
{
  $Number = document.getElementById('amountnumber-input').value;
  if ($Number.indexOf(".") == -1)
  {
      $Number += ".00";
      document.getElementById('amountnumber-input').value = $Number;
  }
  $NumberText =  CheckNumbersToWords($Number);
  $FinalNumber = $NumberText.substr(0,1).toUpperCase() 
  $FinalNumber += $NumberText.substring(1, $NumberText.length);
  $('#amounttext-input').val($FinalNumber);
}
</script>
@stop