<!---
this page is no longer used and will be removed soon. please se /Checks/check.blade.php
-->
@extends('master')

@section('content')

<div class="row">
    <div class="col-md-8">
        <label class="col-md-2 control-label" for="toselect">To Address:</label>
        <select id="toselect" name="toselect" class="form-control">
            <option value="{{ $vendor->address->id }}">{{ $vendor->address->number }} {{ $vendor->address->address1 }} {{ $vendor->address->address2 }} {{ $vendor->address->city }} {{ $vendor->address->state }} {{ $vendor->address->zip }}</option>
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
        {{$vendor->name}}<br/>
        {{$vendor->address->number}} {{$vendor->address->address1}}<br/>
        {{$vendor->address->address2}}<br/>
        {{$vendor->address->city}}, {{$vendor->address->state}} {{$vendor->address->zip}}
    </div>
    <div id="checknum">
        <Input id="checknum-input" type="text" class="noborder form-control input-md" Value="1000">
    </div>
    <div id="date">
        <Input id="date-input" type="date" class="noborder form-control input-md" value="<?php echo date('Y-m-d'); ?>">
    </div>
    <div id="payto">
        <input id="payto-input" class="noborder form-control input-md" type="text" value="{{ $vendor->name }}">
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
<br/>
<br/>
<div class="row" >
    <div class="col-md-8" style="width:300px;">
        <Select multiple id="catagorys"  class="form-control" style="height:300px;">
           @foreach($ExpenseAccountCategorys as $ExpenseAccountCategory)
                <option value="{{ $ExpenseAccountCategory->category }}">{{ $ExpenseAccountCategory->category }}</option>
                @foreach($ExpenseAccountCategory->subcategories as $SubCategory)
                    <option value="{{ $SubCategory->subcategory }}">&emsp;--{{ $SubCategory->subcategory }}</option>
                @endforeach
           @endforeach
        </Select>
    </div>
    <div class="col-md-8" style="width:300px;"> 
        <button class="col-md-12 btn OS-Button" id="save" name="save" type="button">Save</button>
    </div>
    <div class="col-md-8" style="width:300px;"> 
        @if(isset($check))
            <button class="col-md-12 btn OS-Button" data-toggle="modal" data-target="#ShowPdfModel" id="Print" name="Print" type="button" data-url="/Vendors/Checks/PDF/{{ $check->id }}">Finalize/Print</button>
        @endif 
    </div>
</div> 

<div class="modal fade" id="ShowPdfModel" tabindex="-1" role="dialog" aria-labelledby="ShowPdfModel" aria-hidden="true">
    <div style="margin: 2.5vh auto; width: 95vw" class="modal-dialog" role="document">
        <div style="height: 95vh; width: 95vw;" class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="ShowPdfModel">Print Preview</h4>
            </div>
            <div style="height: calc(95vh - 120px);" class="modal-body">
                <iframe style="width: 100%; height: 100%;"id="ShowPdfFrame" src="{{ url('images/loading4.gif') }}"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    
$('#ShowPdfModel').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var url = button.data('url'); // Extract info from data-* attributes
    
    $('#ShowPdfFrame').attr("src", url);  
}); 

$('#ShowPdfModel').on('hide.bs.modal', function (event) {
    $('#ShowPdfFrame').attr("src", "{{ url('images/loading4.gif') }}");
});

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

$('#branchselect').change(function(){
    switch($(this).val()) {
        
        @foreach($branches as $branch)
        case '{{ $branch->id }}':
            $('#fromaddress').html('{{$vendor->name}}<br>{{$branch->number}} {{$branch->address1}}<br>{{$branch->address2}}<br>{{$branch->city}}, {{$branch->state}} {{$branch->zip}}');
            break;
        @endforeach    
        default:

    };
});
$('#branchselect').val("{{ Auth::user()->branch_id }}").change();

$(document).ready(function() {
    @if(isset($check))
        
        $id = {{ $check->id }};
        $checknumber = {{ $check->checknumber }};
        
        $("#date-input").val(moment("{{ $check->date }}").format("YYYY-MM-DD"));
        
        $("#checknum-input").val($checknumber);
        $("#payto-input").val("{{ $check->payto }}");
        $("#memo-input").val("{{ $check->memo }}");
        $("#amountnumber-input").val("{{ $check->GetAmount() }}");
        
        @foreach($check->catagorys as $cat)

            $('#catagorys option[value="{{ $cat }}"]').attr('selected', true);
        @endforeach
        
        UpdateWords();
    @else
        $id = 0;
        $checknumber = 1000;
    @endif    

    
    $("#save").click(function()
    {
        $("body").addClass("loading");
        $date = $("#date-input").val();
        $checknumber = $("#checknum-input").val();
        $payto = $("#payto-input").val();
        $memo = $("#memo-input").val();
        $amountnumber = $("#amountnumber-input").val();
        $catagorys = $("#catagorys").val();
        
        
        
        $post = PostCheck($id, "{{ $vendor->id }}", $date, $checknumber, $memo, $amountnumber, $catagorys, $payto);

        $post.done(function(data) {
            $("body").removeClass("loading");
            if ($.isNumeric(data)) 
            {
                $id = data;
                bootstrap_alert.warning("Check Saved", 'success', 4000);   
            }else{
                ServerValidationErrors(data);
            }
           
        });

        $post.fail(function() {
            $("body").removeClass("loading");
            bootstrap_alert.warning("Failed to contact server", 'danger', 4000);
        });
    });
});

function PostCheck($id, $vendor_id, $checkdate, $checkenumber, $memo, $amount, $catagorys, $payto) {
    console.log($amount);
    return $.post("/Vendors/Checks/Save",
    {
        _token: "{{ csrf_token() }}",
        id: $id,
        vendor_id: $vendor_id,
        date: $checkdate,
        checknumber: $checknumber,
        memo: $memo,
        amount: $amount,
        catagorys: $catagorys,
        payto: $payto
    });
}
</script>


@stop
