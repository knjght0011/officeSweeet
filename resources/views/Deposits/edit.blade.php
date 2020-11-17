@extends('master')

@section('content')

    <h3 style="margin-top: 10px;">Edit Deposit</h3>
@desktop
<div class="row">
    <div style="float:left; width: 10em;  margin-left: 20px;">
        <button style="width: 100%;" id="save" name="save" type="button" class="btn OS-Button">Save</button>
    </div>
    <div style="float:left; width: 10em;  margin-left: 20px;">
        <button style="width: 100%;" id="delete" name="save" type="button" class="btn OS-Button">Delete</button>
    </div>
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button style="width: 100%;" id="backbutton" name="backbutton" type="button" class="btn OS-Button">Back to Journal</button>
    </div>
</div>
@elsedesktop
<div class="row">
    <div class="col-md-6">
        <button style="width: 100%;" id="save" name="save" type="button" class="btn OS-Button">Save</button>
    </div>
    <div class="col-md-6">
        <button style="width: 100%;" id="delete" name="save" type="button" class="btn OS-Button">Delete</button>
    </div>
    <div class="col-md-6">
        <button style="width: 100%;" id="backbutton" name="backbutton" type="button" class="btn OS-Button">Back to Journal</button>
    </div>
</div>
@enddesktop


<legend></legend>
@if(isset($deposit))
    @if($deposit->CantEdit() === true)
    <div class="alert alert-danger">
        <p>This deposit has already been included in a month end: the date and amount cannot be changed without undoing the end of month.</p>
        <p>To do this, undo the end of month, make your corrections, then finalize that month end again. You can go back as many months as you need to make your changes.</p>
    </div>
    @endif
@endif      

<div class="col-md-6">
{!! Form::OSinput("amount", "Amount", "", $deposit->getAmount(), "true", "amount") !!}
{!! Form::OSinput("date", "Date", "", $deposit->DateString(), "true", "date", "date") !!}
{!! Form::OSselect("method", "Method", ['Cash' => 'Cash', 'Check' => 'Check', 'Credit Card' => 'Credit Card', 'Debit Card' => 'Debit Card'], "", $deposit->method, "false", "") !!}
{!! Form::OStextarea("comments", "Comments", "", $deposit->comments, "false", "", "5") !!}
@if(count($deposit->depositlinks) > 0)
{!! Form::OSinput("client", "Linked Client", "", $deposit->getFrom(), "false", "", "text", true, "visitclient", "View Client") !!}
@endif

    <div class="input-group" style="height: 82px;">
        <label class="input-group-addon" for="name"><div style="width: 15em;">Income Category:</div></label>
        <select multiple id="catagorys"  class="form-control input-md" style="height: 100%;">
            @if($deposit->catagorys != null)
                @foreach($deposit->catagorys as $key => $value)
                    <option value="{{ $value }}">{{ $key }}</option>
                @endforeach
            @endif
        </select>
        <span style="height: 100%;" class="input-group-btn">
            <button id="SplitAmountModalButton" style="height: 82px;" class="btn btn-default" type="button" data-toggle="modal" data-target="#SplitAmountModal" data-amount="amount" data-output="catagorys" data-type="income">Select</button>
        </span>
    </div>

</div>
<div class="col-md-6">
@if(count($deposit->depositlinks) > 0)
<table class="table">
    <thead>
        <tr>
            <th>Amount</th>
            <th>Invoice Number</th>
        </tr>
    </thead>
    <tbody>
@foreach( $deposit->depositlinks as $depositlink)
        <tr>
            <td>${{ $depositlink->getAmount() }}</td>
            <td><a href="#" data-toggle="modal" data-target="#ShowPdfModel" data-url="{{ $depositlink->quote_id }}" data-mode="details">{{ $depositlink->getQuoteNumber() }}</a></td>
        </tr>
@endforeach
    </tbody>
</table>
@endif
</div>
<div class="col-md-6">
    <div class="input-group ">
        <span class="input-group-addon" for="fileupload-file"><div style="width: 15em;">Upload Image:</div></span>
        <input id="fileupload-file" name="fileupload-file" type="file" placeholder="" value="" class="form-control" data-validation-label="Company Logo" data-validation-required="true" data-validation-type="">
    </div>
    <div class="col-md-12" id="fileupload-preview-container">
        <embed style="height: 420px; width: 100%;" id="fileupload-preview" ></embed>
    </div>
    <input id="fileupload-changed" hidden>
</div>

<div class="modal fade" id="ShowPdfModel" tabindex="-1" role="dialog" aria-labelledby="ShowPdfModel" aria-hidden="true">
    <div style="margin: 2.5vh auto; width: 95vw" class="modal-dialog" role="document">
        <div style="height: 95vh; width: 95vw;" class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="ShowPdfModel">View Invoice</h4>
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
$(document).ready(function() {
    $('#client').attr('disabled', true);

    $('#fileupload-changed').val('0');
    $('#fileupload-preview').attr( 'src', "{{ $deposit->getFile() }}");

@if(count($deposit->depositlinks) > 0)
    $('#amount').attr('disabled', true);
@endif    
@if($deposit->CantEdit())
    $('#amount').attr('disabled', true);
    $('#date').attr('disabled', true);
    $('#delete').attr('disabled', true);
@endif
    
    $('#ShowPdfModel').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var mode = button.data('mode');
        switch(mode) {
            case "pdf":
                var url = "/Clients/Invoice/PDF/";
             break;
            case "details":
                var url = "/Clients/Invoice/View/";
             break;
            default:
                var url = "/Clients/Invoice/PDF/";
           } 

        var id = button.data('url'); // Extract info from data-* attributes

        $('#ShowPdfFrame').attr("src", url + id);  
    }); 

    $('#ShowPdfModel').on('hide.bs.modal', function (event) {
        $('#ShowPdfFrame').attr("src", "{{ url('images/loading4.gif') }}");
    });
    
    $("#save").click(function()
    {
        var $data = new Object();
        
        $data.id = "{{ $deposit->id }}";
        $data._token =  "{{ csrf_token() }}";
        
        //if($('#amount').is(':disabled')){

        //}else{
            $data.amount = ValidateInput($('#amount'));
        //}
        if($('#date').is(':disabled')){
            
        }else{
            $data.date = ValidateInput($('#date'));
        }
        $data.method = ValidateInput($('#method'));
        $data.comments = ValidateInput($('#comments'));

        if($('#fileupload-changed').val() === "1"){
            $data.image = $("#fileupload-preview").attr('src');
        }else{
            $data.image = "";
        }



        $catagorys = $('#catagorys option');

        if($catagorys.length === 0){
            $('#SplitAmountModalButton').click();
        }else{
            $array = BuildSplitArray($data.amount, $catagorys);
            if($array === "error"){
                $('#SplitAmountModalButton').click();
            }else{
                $data.catagorys = $array
                SaveDeposit($data);
            }
        }

    });
    
    $("#delete").click(function()
    {
        $.confirm({
            title: 'Are you sure you want to delete this deposit?',
            content: '<div class="form-group">' +
                        '<label>Reason for deleting:</label>' +
                        '<input id="reason" type="text" placeholder="" class="form-control"/>' +
                    '</div>',
            buttons: {
                confirm: function () {

                    $reason = $('#reason').val();
                    DeleteDeposit("{{ $deposit->id }}", $reason);
                },
                cancel: function () {
                    
                }
            }
        });
    });
    
    $("#backbutton").click(function()
    {
        GoToPage("/Journal/View");       
    });
    
    $("#visitclient").click(function()
    {
        GoToPage("/Clients/View/{{ $deposit->getClientID() }}");       
    });

    $("#fileupload-file").change(function()
    {
        if (this.files[0].name != "") {
            $('#fileupload-preview').remove();
            $('#fileupload-preview-container').append('<embed style="height: 420px; width: 100%;" id="fileupload-preview" ></embed>');
            readURL(this);
        }
    });
});

var srcContent;
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            srcContent=  e.target.result;
            $('#fileupload-preview').attr('src', srcContent);
            $('#fileupload-changed').val('1');
        };
        reader.readAsDataURL(input.files[0]);
    }
}


function SaveDeposit($data) {

    $("body").addClass("loading");
    $post = $.post("/Deposit/Save",$data);
    
    $post.done(function( data )
    {
        console.log(data);
        
        switch(data) {
            case "success":
                $("body").removeClass("loading");
                $.dialog({
                    title: 'Saved',
                    content: "Saved"
                });                
                break;
            case "norecordfound":
                $("body").removeClass("loading");
                $.dialog({
                    title: 'Error!',
                    content: "No Record Found"
                });
                break;
            default:
                $("body").removeClass("loading");
                ServerValidationErrors(data);
                break;
        }        
    });

    $post.fail(function() {
        console.log("Post Fail");
        $("body").removeClass("loading");
        $.dialog({
            title: 'Error!',
            content: "Failed to post data, try again later"
        });
    });

}

function DeleteDeposit($id, $reason) {
    $("body").addClass("loading");
    $post = $.post("/Deposit/Delete",
    {
        _token: "{{ csrf_token() }}",
        id: $id,
        reason: $reason
    });
    
    $post.done(function( data )
    {
        console.log(data);
        
        switch(data) {
            case "success":
                GoToPage("/Journal/View");
                break;
            case "monthend":
                $("body").removeClass("loading");
                $.dialog({
                    title: 'Error!',
                    content: "This deposit has all ready been included in a month end: Cannot be deleted"
                });
                break;
            case "norecordfound":
                $("body").removeClass("loading");
                $.dialog({
                    title: 'Error!',
                    content: "No Record Found"
                });
                break;
            default:
                $("body").removeClass("loading");
                $.dialog({
                    title: 'Error!',
                    content: "Unknown Error"
                });
        }
    });

    $post.fail(function() {
        console.log("Post Fail");
        $("body").removeClass("loading");
        $.dialog({
            title: 'Error!',
            content: "Failed to post data, try again later"
        });
    });

}

function BuildSplitArray($total, $catagorys) {

    $array = {};

    $runningtotal = parseFloat(0);
    $catagorys.each( function( index, element ){
        $array[$(this).text()] = $(this).val();
        $runningtotal = parseFloat($runningtotal) + parseFloat($(this).val());
    });

    $total = parseFloat($total).toFixed(2);

    if(parseFloat($total) === parseFloat($runningtotal)){
        return $array;
    }else{
        return "error";
    }
}

</script>
@stop