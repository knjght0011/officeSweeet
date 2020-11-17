@extends('master')

@section('content')
    <h3 style="margin-top: 10px;">Check Writing</h3>


<div class="row">
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button style="width: 100%;" id="save" name="save" type="button" class="btn OS-Button">
        @if(isset($check))
            Update Check
        @else
            Add to Print Queue
        @endif
        </button>
    </div>
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button style="width: 100%;" id="gotoprintqueue" name="gotoprintqueue" type="button" class="btn OS-Button">Goto Print Queue</button>
    </div>
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button style="width: 100%" id="File" class="btn btn-default OS-Button" type="button" data-toggle="modal" data-target="#fileupload-modal" data-outputid="#file-id">Attach Image/PDF</button>
    </div>
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button id="filestore-show-file-button" style="width: 100%;" type="button"
                class="btn OS-Button" data-toggle="modal" data-target="#filestore-display-model"
                data-fileid="0">Show Current Attachment</button>
    </div>

    <div style="float:left; width: 30em;  margin-left: 20px;">
        <div class="input-group">
            <span class="input-group-addon" for="payee"><div style="width: 10em;">Payee:</div></span>
            <input id="payee" type="text" name="payee" list="payee-list" class="form-control">
            <datalist  id="payee-list" name="subgroups-list">
                @foreach($clients as $client)
                    <option data-id="{{ $client->id }}" data-type="client" value="{{ $client->getName() }}">{{ TextHelper::GetText("Client") }}</option>
                @endforeach
                @foreach($vendors as $vendor)
                    <option data-id="{{ $vendor->id }}" data-type="vendor" value="{{ $vendor->getName() }}">Vendor</option>
                @endforeach
                @foreach($employees as $employee)
                    <option data-id="{{ $employee->id }}" data-type="employee" value="{{ $employee->firstname }} {{ $employee->middlename }} {{ $employee->lastname }}">Employee</option>
                @endforeach
            </datalist>
        </div>
    </div>
</div>

<!--
<div class="row">
    <div class="input-group col-md-3">
        <span class="input-group-addon" for="payee"><div style="width: 10em;">Payee:</div></span>
        <input id="payee" type="text" name="payee" list="payee-list" class="form-control">
        <datalist  id="payee-list" name="subgroups-list">
            @foreach($clients as $client)
                <option data-id="{{ $client->id }}" data-type="client" value="{{ $client->getName() }}">Client</option>
            @endforeach
            @foreach($vendors as $vendor)
                <option data-id="{{ $vendor->id }}" data-type="vendor" value="{{ $vendor->getName() }}">Vendor</option>
            @endforeach
            @foreach($employees as $employee)
                <option data-id="{{ $employee->id }}" data-type="employee" value="{{ $employee->firstname }} {{ $employee->middlename }} {{ $employee->lastname }}">Employee</option>
            @endforeach
        </datalist>
    </div>
</div>
-->

<div style="margin-left: 5%; margin-right: 5%; margin-top: 15px; padding-top: 25%; position: relative;  height: 0; overflow: hidden; font-size: 1.2em;">
    <div style="position: absolute; top: 0; left: 0; width: 100%;  height: 100%;">
        <div style="height: 100%; width: 70%; border: solid; float: left;">
            <div style="height: 33%; width: 100%; border-bottom: solid red 2px;">
                <div style="width: 50%; height: 100%; padding: 2%; float: left;">
                    <img style="max-width: 100%; max-height: 100%;" src="{{ \App\Helpers\OS\FileStoreHelper::CompanyLogo() }}">
                </div>
                <div style="width: 50%; height: 100%; float: left;">
                    <div style="width: 100%; height: 100%; padding-top: 18%; padding-left: 55%; padding-right: 1.5%; padding-bottom: 1.5%;">
                        <input id="input-date" placeholder="Date" style="height: 100%; width: 100%; border: solid 1px red; float: left; padding-left: 4px;" readonly>
                    </div>
                </div>
            </div>
            <div style="height: 48%; width: 100%; background-color: lightgrey;">
                <div  style="height: 100%; width: 65%; float: left;">
                    <div id="" style="height: 25%; width: 100%; float: left; padding: 1.5%">
                        <div style="height: 100%; width: 10%; float: left; text-align: center;">
                            Pay
                        </div>
                        <input id="input-pay" placeholder="Payee" style="height: 100%; width: 90%; border: solid 1px red; float: right; padding-left: 10px;">
                    </div>
                    <div id="cheque-pay" style="height: 50%; width: 100%; float: left; padding: 10px; line-height: 40px;">

                    </div>
                    <div id="cheque-pay" style="height: 25%; width: 100%; float: left; padding: 1.5%;">
                        <input id="input-memo" placeholder="Memo" style="height: 100%; width: 100%; border: solid 1px red; float: left; padding-left: 10px;">
                    </div>
                </div>

                <div style="height: 100%; width: 35%; float: left;">
                    <div style="width: 100%; height: 100%; padding-top: 20%; padding-bottom: 26%; padding-left: 29%; padding-right: 1.5%;">
                    <div style="height: 100%; width: 10%; text-align: center; float: left;">$ </div><input id="amountnumber-input" style="height: 100%; width: 90%; border: solid 1px red; float: left;" onblur="UpdateWords($(this))" >
                    </div>
                </div>

            </div>
            <div style="height: 19%; width: 100%; border-top: solid red 2px;">

            </div>
        </div>

        <div style="float: left; width: 30%; height: 50%;">
            <div style="width: 70%; height: 40px; float: left; text-align: center; background-color: #eee; padding-top: 7px; border: solid; border-width: 1px;">
                <label  for="name">Expense Category</label>
            </div>
            <div style="width: 30%; height: 40px; float: left;">
                <button style="height: 100%; width: 100%" id="catagory-select-button" class="btn btn-default" type="button" data-toggle="modal" data-target="#SplitAmountModal" data-amount="amountnumber-input" data-output="catagorys" data-type="expense" >Select</button>
            </div>

            <select multiple id="catagorys"  class="form-control" style="height: calc(100% - 40px);">
                @if(isset($check))
                    @foreach($check->catagorys as $key => $value)
                        <option value="{{ $value }}">{{ $key }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div style="float: left; width: 30%; height: 50%;">
            <div style="width: 100%; height: 40px; float: left; text-align: center; background-color: #eee; padding-top: 7px; border: solid; border-width: 1px;">
                <label  for="name">Comments</label>
            </div>
            <textarea id="input-comment" class="form-control input-md"  style="height: calc(100% - 40px); width: 100%; resize: none;">@if(isset($check)){!! $check->comments !!}@endif</textarea>
        </div>
    </div>
</div>

<input id="selected-id" style="display: none;" disabled="disabled">
<input id="selected-type" style="display: none;" disabled="disabled">
<input id="check-id" style="display: none;" disabled="disabled">

<input style="display: none;" id="file-id" name="file-id" class="form-control" value="0">


<script>
$(document).ready(function() {
    @if(isset($check))
        @foreach($check->catagorys as $cat)

            $('#catagorys option[value="{{ $cat }}"]').attr('selected', true);
        @endforeach

        $('#check-id').val("{{ $check->id }}");

        @if($check->filestore_id != null)
            $('#file-id').val({{ $check->filestore_id }});
            $('#filestore-show-file-button').data('fileid', {{ $check->filestore_id }});
        @else
                alert('filestore id = null');
            $('#filestore-show-file-button').prop('disabled', 'disabled');
        @endif

        @if($check->client_id != null)
            $('#selected-id').val('{{ $check->client_id }}');
            $('#selected-type').val('client');
            $payeelistid = $('#payee-list').find("[data-id='{{ $check->client_id }}']");
            $.each($payeelistid, function( key, value ) {
                if($(value).data('type') === "client"){
                    $("#payee").val($(value).val());

                }
            });
        @endif
        @if($check->vendor_id != null)
            $('#selected-id').val('{{ $check->vendor_id }}');
            $('#selected-type').val('vendor');
            $payeelistid = $('#payee-list').find("[data-id='{{ $check->vendor_id }}']");
            $.each($payeelistid, function( key, value ) {
                if($(value).data('type') === "vendor"){
                    $("#payee").val($(value).val());

                }
            });
        @endif
        @if($check->employee_id != null)
            $('#selected-id').val('{{ $check->employee_id }}');
            $('#selected-type').val('employee');
            $payeelistid = $('#payee-list').find("[data-id='{{ $check->employee_id }}']");
            $.each($payeelistid, function( key, value ) {
                if($(value).data('type') === "employee"){
                    $("#payee").val($(value).val());
                }
            });
        @endif

        $("#input-date").val(moment("{{ $check->date }}").format('Do MMMM YYYY'));
        $("#input-pay").val("{{ $check->payto }}");
        $("#input-memo").val("{{ $check->memo }}");
        $("#amountnumber-input").val("{{ $check->GetAmount() }}");

        @if($check->printed != null)
            $('#input-date').attr('disabled','disabled');
            $('#input-pay').attr('disabled','disabled');
            $('#input-memo').attr('disabled','disabled');
            $('#amountnumber-input').attr('disabled','disabled');
            //$('#catagory-select-button').attr('disabled','disabled');
        @endif

        $('#payee').attr('disabled','disabled');


        UpdateWords($("#amountnumber-input"));

    @else
        $('#check-id').val("0");
        $('#selected-id').val('0');
        $('#selected-type').val('none');
        $('#input-pay').attr('disabled','disabled');

        //$('#filestore-show-file-button').prop('disabled', 'disabled');

        @if($type != null)
            $('#selected-type').val('{{$type}}');
            @if($id != null)
                $('#selected-id').val('{{$id}}');

                $payeelistid = $('#payee-list').find("[data-id='{{ $id }}']");
                $.each($payeelistid, function( key, value ) {
                    if($(value).data('type') === "{{$type}}"){
                        $("#payee").val($(value).val());
                        $("#input-pay").val($(value).val());
                        $('#input-pay').removeAttr('disabled');
                    }
                });
            @endif
        @endif
    @endif

    $("#amountnumber-input").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl/cmd+A
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: Ctrl/cmd+C
            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: Ctrl/cmd+X
            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    $('#payee').change(function(){
        $id = $('#payee-list option[value="' + $(this).val() + '"]').attr('data-id');
        $type = $('#payee-list option[value="' + $(this).val() + '"]').attr('data-type');

        if($id == null){
            $('#input-pay').val('');
            $('#input-pay').attr('disabled','disabled');

            $('#selected-id').val('0');
            $('#selected-type').val('none');
        }else{
            //alert($id);
            $('#input-pay').val($(this).val());
            $('#input-pay').removeAttr('disabled');

            $('#selected-id').val($id);
            $('#selected-type').val($type);
        }
    });

    $('#input-date').datepicker({
        changeMonth: true,
        changeYear: true,
        inline: true,
        onSelect: function(dateText, inst) {
            $datetest =  moment(dateText,'MM/DD/YYYY').format('Do MMMM YYYY');
            $(this).val($datetest);
        }
    });

    $("#gotoprintqueue").click(function()
    {
        GoToPage('/Checks/Queue')
    });

    $("#save").click(function()
    {
        $error = false;

        $chequedata = {};

        $chequedata['_token'] = "{{ csrf_token() }}";
        $chequedata['id'] = $('#check-id').val();
        $chequedata['data_id'] = $('#selected-id').val();
        $chequedata['linktype'] = $('#selected-type').val();

        if($chequedata['data_id'] === "0"){
            $.dialog({
                title: 'Oops..',
                content: 'Please select a payee.'
            });

            $error = true;
        }

        $chequedata['date'] = $("#input-date").val();
        if($chequedata['date'] === ""){
            $.dialog({
                title: 'Oops..',
                content: 'Please select a date.'
            });

            $error = true;
        }

        $chequedata['amount'] = $("#amountnumber-input").val();

        $chequedata['amount'] = $chequedata['amount'].replace(',','');

        if($chequedata['amount'] == ""){
            $.dialog({
                title: 'Oops..',
                content: 'Please enter an amount.'
            });

            $error = true;
        }

        if($error){
            throw new Error('validation error');
        }

        $chequedata['payto'] = $("#input-pay").val();
        $chequedata['memo'] = $("#input-memo").val();
        $chequedata['comment'] =  $("#input-comment").val();


        $chequedata['fileid'] =  $("#file-id").val();


        if($('#catagorys option').length === 0){
            $('#catagory-select-button').click();
        }else{

            $chequedata['catagorys'] = BuildSplitArray($chequedata['amount'], $('#catagorys option'));
            if($chequedata['catagorys'] === "error"){
                $('#catagory-select-button').click();
            }else{
                SaveCheck($chequedata);
            }
        }
    });


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

    function SaveCheck($chequedata) {
        $("body").addClass("loading");
        $post = $.post("/Checks/Queue/Save", $chequedata);

        $post.done(function(data) {
            console.log(data);
            $("body").removeClass("loading");
            if ($.isNumeric(data))
            {
                $('#check-id').val(data);

                $('#payee').attr('disabled','disabled');

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

    function CheckNumbersToWords(s){

        s = s.toString();
        var n = s.split('.');
        var Words = toWords(n[0]);
        Words += 'and ';
        if (s.indexOf('.' > 0)) {
            if (n[1] == 00 )
            {
                Words += 'no cents';
            }else{
                Words += toWords(n[1]);
                Words += '/100  ********';
            }
        }else{
            Words += 'no cents';
        }
        return Words;
    }

    function UpdateWords($input)
    {
        $Number = $input.val();

        if ($Number.indexOf(".") == -1){
            $Number += ".00";
            $input.val($Number);
        }

        $NumberText =  CheckNumbersToWords($Number);
        $FinalNumber = $NumberText.substr(0,1).toUpperCase()
        $FinalNumber += $NumberText.substring(1, $NumberText.length);

        $('#cheque-pay').text($FinalNumber);
    }

</script>

    @include('OS.FileStore.fileuploadmodel')
    @include('OS.FileStore.displayfile')
@stop