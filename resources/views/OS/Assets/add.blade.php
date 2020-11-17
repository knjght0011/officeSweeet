@extends('master')

@section('content')
@desktop
<style>
    .labeldiv{
        width: 15em;
    }
</style>
@elsedesktop

@enddesktop


@desktop
<div class="row" style="margin-top: 10px; margin-bottom: 10px;">
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button style="width: 100%;" id="save" name="save" type="button" class="btn OS-Button">Save</button>
    </div>
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button style="width: 100%;" id="backbutton" name="backbutton" type="button" class="btn OS-Button">Back</button>
    </div>
</div>
@elsedesktop
<div class="row" style="margin-top: 10px; margin-bottom: 10px;">
    <div class="col-md-6">
        <button style="width: 100%;" id="save" name="save" type="button" class="btn OS-Button">Save</button>
    </div>
    <div class="col-md-6">
        <button style="width: 100%;" id="backbutton" name="backbutton" type="button" class="btn OS-Button">Back</button>
    </div>
</div>
@enddesktop


<div class="col-md-6">

    <div class="input-group ">
        <label class="input-group-addon" for="type"><div class="labeldiv">Type:</div></label>
        <select id="type"  class="form-control input-md" >
            <option value="a">Asset</option>
            <option value="l">Liability</option>
            <option value="e">Equity</option>
        </select>
    </div>

    <div class="input-group">
        <span width="10em" class="input-group-addon" for="name"><div class="labeldiv">Name/Description:</div></span>
        <input id="name" name="name" type="text" class="form-control input-md" required="">
    </div>

    <div class="input-group">
        <span class="input-group-addon" for="date"><div class="labeldiv">Date Purchased/Acquired:</div></span>
        <input id="date" name="date" type="date" class="form-control input-md" value="{{ date('Y-m-d') }}" required="">
    </div>

    <div class="input-group">
        <span width="10em" class="input-group-addon" for="amount"><div class="labeldiv">Amount/Value: $</div></span>
        <input id="amount" name="amount" type="number" class="form-control input-md" required="" value="0.00">
    </div>

    <div class="input-group ">
        <label class="input-group-addon" for="catagorys"><div class="labeldiv">Catagories:</div></label>
        <select multiple id="catagorys"  class="form-control input-md" >
            @if(isset($asset))
                @foreach($asset->getCatagorys() as $key => $value)
                    <option value="{{ $value }}">{{ $key }}</option>
                @endforeach
            @endif
        </select>
        <span style="height: 100%; padding: 0px;" class="input-group-btn">
            <button style="height: 100%;" id="SplitAmountModalButton" class="btn btn-default" type="button" data-toggle="modal" data-target="#SplitAmountModal" data-amount="amount" data-output="catagorys" data-type="asset">Select</button>
        </span>
    </div>

    <div class="input-group">
        <span width="10em" class="input-group-addon" for="comments"><div class="labeldiv">Comments/Notes:</div></span>
        <textarea style="resize: none" id="comments" name="comments" type="text" class="form-control input-md" rows="4"></textarea>
    </div>

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

        
<input style="visibility: hidden;" id="asset-id" name="asset-id" class="form-control">


<script>
$(document).ready(function() {
    @if(isset($asset))            
        $("#asset-id").val("{{ $asset->id }}");
        $("#name").val("{{ $asset->name }}");

        $('#date').val("{{ $asset->DateString() }}");
        $('#amount').val("{{ $asset->getAmount() }}");
        $('#description').val("{{ $asset->description }}");


        $('#fileupload-preview').attr( 'src', "{{ $asset->getFile() }}");
    @else
        $("#asset-id").val("0");
        $('#delete').attr('disabled', true);
    @endif

    $('#fileupload-changed').val('0');
    
    $("#backbutton").click(function()
    {
        GoToPage('/AssetLiability/');
    });
         
    $("#save").click(function()
    {
        Save();
    });   
    
    $("#type").change(function()
    {
        $('#catagorys').find('option').remove();
        if(this.value === "a"){
            $('#SplitAmountModalButton').data('type', 'asset');
        }else{
            $('#SplitAmountModalButton').data('type', 'liability');
        }

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
function Save(){

    if($('#catagorys option').length === 0){
        $('#SplitAmountModalButton').click();
    }else{
        $data = {};
        $data['amount'] = $("#amount ").val();
        $data['catagorys']  = BuildSplitArray($data['amount'], $('#catagorys option'));

        if($array === "error"){
            $('#SplitAmountModalButton').click();
        }else{

            $data['_token'] = "{{ csrf_token() }}";
            $data['id'] = $("#asset-id").val();
            $data['name'] = $("#name").val();
            $data['date'] = $("#date").val();
            $data['comments'] = $("#comments").val();
            $data['type'] = $("#type").val();

            if($('#fileupload-changed').val() === "1"){
                $data['image'] = $("#fileupload-preview").attr('src');
            }else{
                $data['image'] = "";
            }

            SaveExpense($data);
        }
    }
}

function SaveExpense($data) {

    $("body").addClass("loading");
    ResetServerValidationErrors();

    posting = $.post("/AssetLiability/Save", $data);

    posting.done(function( data ) {

        $("body").removeClass("loading");
        if ($.isNumeric(data))
        {
            $("#asset-id").val(data);
            $.confirm({
                autoClose: 'Close|2000',
                title: 'Success!',
                content: 'Data Saved',
                buttons: {
                    Close: function () {

                    }
                }
            });
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
