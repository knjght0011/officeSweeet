@extends('master')

@section('content')
    <h3 style="margin-top: 10px;">Expense Entry</h3>

@desktop
<div class="row" style="margin-bottom: 10px;">
    <div style="float:left; width: 10em;  margin-left: 20px;">
        <button style="width: 100%;" id="save" name="save" type="button" class="btn OS-Button">Save</button>
    </div>
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button style="width: 100%;" id="nextexpense" name="nextexpense" type="button" class="btn OS-Button">Save & Next Expense</button>
    </div>    
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button style="width: 100%;" id="delete" name="delete" type="button" class="btn OS-Button">Delete Expense</button>
    </div> 
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button style="width: 100%;" id="backbutton" name="backbutton" type="button" class="btn OS-Button">Back</button>
    </div>
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button style="width: 100%;" id="viewjournal" name="nextexpense" type="button" class="btn OS-Button">Journal</button>
    </div>
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button style="width: 100%;" class="OS-Button btn" data-toggle="modal" data-target="#AddMiscDepositModel">Add Misc Deposit</button>
    </div>
</div>
@elsedesktop
<div class="row" style="margin-bottom: 10px;">
    <div class="col-md-6">
        <button style="width: 100%;" id="save" name="save" type="button" class="btn OS-Button">Save</button>
    </div>
    <div class="col-md-6">
        <button style="width: 100%;" id="nextexpense" name="nextexpense" type="button" class="btn OS-Button">Save & Next Expense</button>
    </div>
    <div class="col-md-6">
        <button style="width: 100%;" id="delete" name="delete" type="button" class="btn OS-Button">Delete Expense</button>
    </div>
    <div class="col-md-6">
        <button style="width: 100%;" id="backbutton" name="backbutton" type="button" class="btn OS-Button">Back</button>
    </div>
    <div class="col-md-6">
        <button style="width: 100%;" id="viewjournal" name="nextexpense" type="button" class="btn OS-Button">Journal</button>
    </div>
</div>
@enddesktop

@if(isset($receipt))
    @if($receipt->CantEdit() === true)
    <div class="alert alert-danger">
        This Expense has already been included in a month end, therefore the date and amount cannot be changed.
    </div>
    @endif
@endif  
<div class="col-md-6">

    <div class="input-group">
        <span class="input-group-addon" for="name"><div style="width: 10em;">Date:</div></span>  
        <input id="date" name="date" class="form-control input-md" value="{{ date('Y-m-d') }}" required="" readonly>
    </div>  

    <div class="input-group">
        <span width="10em" class="input-group-addon" for="name"><div style="width: 10em;">Amount: $</div></span>  
        <input id="amount" name="amount" type="number" class="form-control input-md" required="" value="0.00">
    </div> 

    <div class="input-group">
        <span width="10em" class="input-group-addon" for="name"><div style="width: 10em;">Linked Account:</div></span>
        <input id="account" name="account" type="text" class="form-control" value="Miscellaneous Expense">
        <span class="input-group-btn">
            <button class="btn btn-default" type="button" data-toggle="modal" data-target="#LinkedAccountModal">Select</button>
        </span>
    </div> 

    <div class="input-group">
        <span width="10em" class="input-group-addon" for="name"><div style="width: 10em;">Description:</div></span>  
        <input id="description" name="description" type="text" class="form-control input-md" required="">
    </div>    

    <div class="input-group">   
        <label class="input-group-addon" for="name"><div style="width: 10em;">Expense Category:</div></label> 
        <select multiple id="catagorys"  class="form-control input-md" >
            @if(isset($receipt))
            @foreach($receipt->catagorys as $key => $value)
            <option value="{{ $value }}">{{ $key }}</option>
            @endforeach
            @endif
        </select>
        <span style="height: 100%;" class="input-group-btn">
            <button id="SplitAmountModalButton" style="height: 100%;" class="btn btn-default" type="button" data-toggle="modal" data-target="#SplitAmountModal" data-amount="amount" data-output="catagorys" data-type="expense">Select</button>
        </span>
   </div>
    <br>
    <legend>Related Employee(Optional)</legend>
    
    <div class="input-group">
        <span class="input-group-addon" for="name"><div style="width: 10em;">Employee:</div></span>  
        <select id="employee"  class="form-control input-md" value="0">
            <option value="0">None</option>
           @foreach($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->firstname }} {{ $employee->lastname }}</option>
           @endforeach
        </select>

    </div>

    
    <label class="radio-inline">Needs Reimbursement:<input type="checkbox" id="reimbursement" name="reimbursement" value="1"></label>
    

</div>

<div class="col-md-6">
    <div class="input-group ">
        <span class="input-group-addon" for="fileupload-file"><div>Upload Image:</div></span>
        <input id="fileupload-file" name="fileupload-file" type="file" placeholder="" value="" class="form-control" data-validation-label="Company Logo" data-validation-required="true" data-validation-type="">
    </div>
    <div class="col-md-12" id="fileupload-preview-container">
        <embed style="height: 420px; width: 100%;" id="fileupload-preview" ></embed>
    </div>
    <input id="fileupload-changed" hidden>
</div>

        
<input style="visibility: hidden;" id="expence-id" name="expence-id" class="form-control"></input>
@include('Receipts.modals.LinkedAccountModel')

<script>
$(document).ready(function() {
    @if(isset($receipt))
        $("#account").prop( "disabled", true );
            
        $("#expence-id").val("{{ $receipt->id }}");
     
        $('#date').val("{{ $receipt->DateString() }}");
        
        
        $amount = {{ $receipt->amount }};
        $('#amount').val($amount.toFixed(2));
        
        $('#account').val("{{ $receipt->LinkedAccountName() }}");
        $linkedid = "{{ $receipt->LinkedAccountID() }}";
        $linkedtype = "{{ $receipt->LinkedType() }}";
        
        $('#description').val("{{ $receipt->description }}");
        
        $('#employee').val("{{ $receipt->GetUserID() }}");
        
        @if($receipt->reimbursement === 1)
            $('#reimbursement').prop('checked', true);
        @endif
        
        $('#fileupload-preview').attr( 'src', "{{ $receipt->getFile() }}");

        @if($receipt->CantEdit() === true)
            $('#date').attr('disabled', true);
            $('#amount').attr('disabled', true);
            $('#delete').attr('disabled', true);
        @endif
        
    @else
        $("#account").prop( "disabled", true );
        $("#employee").val("{{ $user_id }}");

        $("#expence-id").val("0");
        
        $linkedid = 0;
        $linkedtype = "Miscellaneous Expense";
        
        $('#delete').attr('disabled', true);
    @endif


    $('#date').datepicker({
        changeMonth: true,
        changeYear: true,
        inline: true,
        dateFormat: "yy-mm-dd",
    });

    $('#fileupload-changed').val('0');

    $("#nextexpense").click(function()
    {
        PreSave(true);       
    });
    
    $("#backbutton").click(function()
    {
        GoToPage(document.referrer);       
    });
    
    $("#viewjournal").click(function()
    {
        GoToPage("/Journal/View/");
    });
         
    $("#save").click(function()
    {
        PreSave(false);
    });   
    
    $("#delete").click(function()
    {
        $.confirm({
            title: 'Are you sure you want to delete this record?',
            buttons: {
                confirm: function () {
                    DeleteExpense($("#expence-id").val());
                },
                cancel: function () {
                    
                }
            }
        });
    });

    $("#fileupload-file").change(function()
    {
        if (this.files[0].name != "") {
            $('#fileupload-preview').remove();
            $('#fileupload-preview-container').append('<embed style="height: 420px; width: 100%;" id="fileupload-preview" ></embed>');
            readURL(this);
        }
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

function DeleteExpense($id) {

    $("body").addClass("loading");
    posting = $.post("/Reciepts/Delete",
    {
        _token: "{{ csrf_token() }}",
        id: $id
    });

    posting.done(function( data ) {
        $("body").removeClass("loading");
        switch(data) {
            case "success":
                $.dialog({
                    title: 'Success!',
                    content: 'Entry deleted.'
                });
                GoToPage(document.referrer);
                break;
            case "error:monthend":
                $.dialog({
                    title: 'Error!',
                    content: 'This entry cannot be deleted as it has allready been included in a month end.'
                });
                break;
            case "error:norecord":
                $.dialog({
                    title: 'Error!',
                    content: 'No entry found, please refresh the page and try again.'
                });
                break;                
            default:
                console.log(data);
                $.dialog({
                    title: 'Error!',
                    content: 'Unknown Error.'
                });
        }
    });

    posting.fail(function() {

        $("body").removeClass("loading");
        $.dialog({
            title: 'Error!',
            content: "Failed to contact server"
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
        $runningtotal = parseFloat($runningtotal).toFixed(2);

        if(parseFloat($total) === parseFloat($runningtotal)){
            return $array;
        }else{
            return "error";
        }
    }

function PreSave($next){


    $date = $("#date").val();
    $amount = $("#amount").val();
    $employee = $("#employee").val();
    $description = $("#description").val();
    if($('#fileupload-changed').val() === "1"){
        $image = $("#fileupload-preview").attr('src');
    }else{
        $image = "";
    }

    //$image = $("#fileupload-preview").attr('src');
    $catagorys = $('#catagorys option');

    if($employee === "0"){
        $reimbursement = 0;
    }else{
        if($("#reimbursement").is(':checked')){
            $reimbursement = 1;
        } else {
            $reimbursement = 0;
        }
    }

    $error = false;

    if($error === true){
        throw new Error("Validation Error");
    }        

    if($catagorys.length === 0){
        $('#SplitAmountModal').modal('show');
    }else{
        $array = BuildSplitArray($amount, $catagorys);
        if($array === "error"){
            $('#SplitAmountModalButton').click();
        }else{
            SaveExpense($("#expence-id").val(), $date, $amount, $linkedid, $linkedtype, $description, $array, $image, $employee, $reimbursement, $next);
        }
    }
}
});

function SaveExpense($id, $date, $amount, $linkedid, $linkedtype, $description, $catagorys, $image, $employee, $reimbursement, $next) {


    $("body").addClass("loading");
    posting = $.post("/Reciepts/Save",
    {
        _token: "{{ csrf_token() }}",
        id: $id,
        date: $date,
        amount: $amount,
        linkedid: $linkedid,
        linkedtype: $linkedtype,
        description: $description,
        catagorys: $catagorys,
        image: $image,
        employee: $employee,
        reimbursement: $reimbursement

    });

    posting.done(function( data ) {

        $("body").removeClass("loading");
        if ($.isNumeric(data)) 
        {
            $('#delete').attr('disabled', false);
            $("#expence-id").val(data);
            $.confirm({
                autoClose: 'Close|2000',
                title: 'Success!',
                content: 'Data Saved',
                buttons: {
                    Close: function () {

                    }
                }
            });
            if($next === true){
                GoToPage("/Reciepts/New/0");
            }
        } 
        else 
        {
            //server validation errors
            ServerValidationErrors(data);
        }
    });

    posting.fail(function() {

        $("body").removeClass("loading");
        $.dialog({
            title: 'Error!',
            content: "Failed to contact server"
        });
    });
}




</script>

@stop
