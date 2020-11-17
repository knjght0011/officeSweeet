@extends('master')

@section('content')

    <h3 style="margin-top: 10px;">Check Print Queue</h3>

    <div class="container">
               <h4 style="text-align: center;">Journal/Checking Ending Balance: ${{ number_format($endingbalance, 2, '.', '') }}</h4>
    </div>

<nav style="margin-top: 20px;" class="navbar navbar-default navbar-static-top">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="#" data-toggle="modal" data-target="#ShowPdfModel" data-mode="all">Print All</a></li>
                <li style="padding-top: 15px;">
                    <label class="radio-inline"><input class="order" name="order" type="radio" value="top" checked>Low to High</label>
                    <label class="radio-inline"><input class="order" name="order" type="radio" value="bottom" >High to Low</label>
                </li>
            </ul>
        </div>
    </div>
</nav>

<table class="table">
    <thead>
        <tr>
            <th style="width: 10px;">Incl</th>
            <th style="width: 80px;">Check #</th>
            <th>Pay to</th>
            <th>Amount</th>
            <th>Date</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody id="table-body">
        @foreach($checks as $check)
        <tr name="{{ $check->id }}">
            <td><input type="checkbox" class="check-no-auto" checked></td>
            <td><Input type="text" class="check-no form-control" value="{{ $check->checknumber }}"></td>
            <td>{{ $check->payto }}</td>
            <td>${{ $check->GetAmount() }}</td>
            <td>{{ $check->formatDate() }}</td>
            <td><button class="col-md-12 btn OS-Button print" data-toggle="modal" data-target="#ShowPdfModel" id="Print" name="Print" type="button" data-mode="single" data-id="{{ $check->id }}">Print Only This Check</button></a></td>
            <td><button class="col-md-12 btn OS-Button mark" id="mark" data-id="{{ $check->id }}">Mark as printed</button></a></td>
            <td><a href="/Checks/Edit/{{ $check->id }}"><button class="col-md-12 btn OS-Button editcheck" id="editcheck" data-id="{{ $check->id }}">Edit</button></a></td>
            <td><button class="col-md-12 btn OS-Button deletecheck" id="deletecheck" data-id="{{ $check->id }}">Delete</button></a></td>
        </tr>
        @endforeach
        <tr style="background-color: lightblue; font-weight: bold;">
            <td></td>
            <td></td>
            <td>Total</td>
            <td>${{ number_format($total, 2, '.', '') }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

    </tbody>
</table>

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
    if(button.data('mode') === 'single'){
        var id = button.data('id');
        var url = "/Checks/PDF/" + id;
    }else{
        var id = "";
        $('#table-body').children().each( function( key, value ) {
            if($(this).find('.check-no-auto').is(":checked")){
                id = id + $(this).find('.print').data('id') + "_";
            }
        });
        
        var url = "/Checks/PDF/" + id + "/" + $("input[name='order']:checked").val();
    }
    
    $('#ShowPdfFrame').attr("src", url);  
}); 

$('#ShowPdfModel').on('hide.bs.modal', function (event) {
    $('#ShowPdfFrame').attr("src", "{{ url('images/loading4.gif') }}");
});

$('.check-no').focusout(function() {
    
    if (/^\d+$/.test($(this).val())) {
        if($(this).parent().parent().find('.check-no-auto').is(":checked")){
            AutoNumber($(this).parent().parent(), $(this).val());
        }
    } else {
        var tb = this;
        
        $.dialog({
            title: 'Oops...',
            content: 'Please include a check number before proceeding (numbers only)',
            onDestroy: function () {
                $(tb).focus();
            }
        });
    }
});


$(".mark").click(function()
{
    button = $(this);
    $checkno = button.parent().parent().find('.check-no').val();
    $.confirm({
        title: 'Check Number:' + $checkno,
        content: 'Are you sure you want to mark the check as printed with check number ' + $checkno + "?",
        buttons: {
            confirm: function () {
                MarkPrinted(button, $checkno);
            },
            cancel: function () {
                $.alert('Canceled!');
            }
        }
    });    
});

$('.editcheck').click(function(e) {
    var id = button.data('id');
    GoToPage('/Checks/Edit/' + id)
});

$('.deletecheck').click(function(e) {

    var button = $(this);
    var id = button.data('id');
    
    $("body").addClass("loading");

    posting = $.post("/Checks/Delete",
    {
        _token: "{{ csrf_token() }}",
        id: id

    });

    posting.done(function( data ) {
        
        console.log(data);
        if( data  === "saved")
        {
            location.reload();
        }else{
            $("body").removeClass("loading");
            $.dialog({
                title: 'Oops...',
                content: data,
            });
       }
    });

    posting.fail(function() {
        $("body").removeClass("loading");
        bootstrap_alert.warning("Failed to contact server", 'danger', 4000);
    });
});

function MarkPrinted(button, $checkno){
    
    $("body").addClass("loading");
    
    $url = "/Checks/Printed/" + button.data('id') + "/" + $checkno;
    var get = $.get($url , function(  ) { });

    get.done(function( data ) {
        
        $("body").removeClass("loading");
        if(data === "done"){
            button.parent().parent().find('.check-no').attr("disabled", true);
            button.parent().parent().find('.check-no-auto').attr("checked", false);
            button.parent().parent().find('.check-no-auto').attr("disabled", true);
            button.parent().parent().find('.print').attr("disabled", true);
            
            
            $.dialog({
                title: 'Success!',
                content: "Check has been marked as printed, It will now appear in the journal",
            });
            button.attr("disabled", true);
        }else{
            $.dialog({
                title: 'Error!',
                content: data,
            });
        }
    });    
}

function AutoNumber($row, $value){

    $data = $row.parent();
    
    $found = false; 
    $data.children().each( function( key, value ) {
        if($found === true){
            if($(this).find('.check-no-auto').is(":checked")){
                $value++;
                $(this).find('.check-no').val($value);
            }
        }
        if($(this).attr("name") === $row.attr("name")){
            $found = true;
        }
    });
}
</script>
@stop