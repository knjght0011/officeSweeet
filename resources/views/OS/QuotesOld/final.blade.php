@extends('master')

@section('content')
<div class="row" style="margin-top: 5px;">
    
    <div style="float:left; width: 18em; margin-left: 20px;">
        <button style="width: 100%;" class="col-md-12 btn OS-Button" id="save" name="save" type="button">Save</button>
    </div> 
    
    <!--
    <div style="float:left; width: 18em;  margin-left: 20px;">
        <button style="width: 100%;" class="col-md-12 btn OS-Button btn" type="button" data-toggle="modal" data-target="#ProductModal">
            Add Product
        </button>
    </div>   
    
    <div style="float:left; width: 18em; margin-left: 20px;">
        <button style="width: 100%;" class="col-md-12 btn OS-Button btn" type="button" data-toggle="modal" data-target="#ExpenseModal">
            Add Expense
        </button>
    </div> 
      
    <div style="float:left; width: 18em; margin-left: 20px;">
        <button style="width: 100%;" class="col-md-12 btn OS-Button" id="invoice" name="invoice" type="button">Invoice</button>
    </div>
    -->
</div>
<label>Repeat</label>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <div class="checkbox">
                <label data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                    <input id="recurring-check" type="checkbox"/> Make Recurring?
                </label>
            </div>
        </div>
        <div id="collapseOne" aria-expanded="false" class="collapse">
            <div class="well" style="display: inline-block;">
                <div class="col-md-6">
                    <div class="input-group ">
                        <span class="input-group-addon" for="repeat"><div style="width: 15em;">Repeat:</div></span>
                        <select id="repeat" name="repeat" class="form-control">
                            <option value="1">Daily - Every Day</option>
                            <option value="2">Daily - Weekday Only</option>
                            <option value="3">Weekly</option>
                            <option value="4">Monthly</option>
                            <option value="5">Last day of Month</option>
                            <option value="6">Yearly</option>
                            <option value="7">Every X Days</option>
                        </select>
                    </div>
                    <div class="input-group " id="repeat-days-container">
                        <span class="input-group-addon" for="repeat-days"><div style="width: 15em;">Days:</div></span>
                        <input id="repeat-days" name="repeat-days" class="form-control" value="14" type="number">
                    </div>
                    <div class="input-group ">
                        <span class="input-group-addon" for="repeatemail"><div style="width: 15em;">E-Mail to contact:</div></span>
                        <select id="repeatemail" name="repeatemail" class="form-control">
                            <option value="0">No</option>
                            @foreach($client->contacts as $contact)
                            <option value="{{ $contact->id }}">{{ $contact->firstname }} {{ $contact->lastname }} - {{ $contact->email }}</option>>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group ">
                        <span class="input-group-addon" for="repeat-start"><div style="width: 15em;">First Invoice Date::</div></span>
                        <input id="repeat-start" type="text" name="repeat-start" class="btn btn-default" style=" width:100%;" readonly>
                    </div>
                    <div class="input-group ">
                        <span class="input-group-addon" for="repeat-till"><div style="width: 15em;">Repeat Until:</div></span>
                        <input id="repeat-till" type="text" name="repeat-till" class="btn btn-default" style=" width:100%;" readonly>
                        <span class="input-group-addon">
                            <input id="repeatcheck" type="checkbox" checked>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<label>Stock</label>
<table class="table">
    <thead>
        <tr>
            <th class="col-md-1">SKU</th>
            <th>Description</th>
            <th>Stock Status</th>
            <th>Action</th>
            <th class="col-md-1">In Stock</th>
            <th class="col-md-1">Qty</th>
            <th class="col-md-1">Unit Cost ($)</th>
            <th class="col-md-1">Price ($)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($quote->quoteitem as $item)
            <tr>
                <td name="sku" class="item-name">{{ $item->SKU }}</td>
                <td name="description" class="description">{{ $item->description }}</td>
                <td name="status">{{ $item->getProductStatus() }}</td>
                <td name="action">{{ $item->getProductAction() }}</td>
                <td name="stock" >{{ $item->getStock() }}</td>
                <td name="units" >{{ number_format($item->units , 0) }}</td>
                <td name="costperunit" >{{ number_format($item->costperunit , 2) }}</td>
                <td name="total" >{{ number_format($item->costperunit * $item->units , 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
$( document ).ready(function() {
    $('#repeat-till').datepicker({
        changeMonth: true,
        changeYear: true,
        inline: true,
        onSelect: function(dateText, inst) {

            var date = moment(dateText); //Get the current date
            $(this).val(date.format("YYYY-MM-DD"));
        }

    });

    $('#repeat-start').datepicker({
        changeMonth: true,
        changeYear: true,
        inline: true,
        onSelect: function(dateText, inst) {
            var date = moment(dateText); //Get the current date
            $(this).val(date.format("YYYY-MM-DD"));
        },
        beforeShowDay: DisableSpecificDates
    });
    $('#repeat-start').val(moment().format("YYYY-MM-DD"));

    $("#repeat-till").val("Forever.");
    $("#repeat-till").prop('disabled', true);

    $("#repeat-days-container").css('display', 'none');

    $('#repeatcheck').click(function(){
        if($(this).is(":checked")){
            $("#repeat-till").val("Forever.");
            $("#repeat-till").prop('disabled', true);
        }else{
            $("#repeat-till").val("");
            $("#repeat-till").prop('disabled', false);
        }
    });

    $('#repeat').change(function(){

        if($(this).val() === "7"){
            $("#repeat-days-container").css('display', 'table');
        }else{
            $("#repeat-days-container").css('display', 'none');
        }
        $('#repeat-start').val(GetValidDate())
    });

    $("#save").click(function()
    {
        $valid = true;
        $('#repeat-days').removeClass('invalid');

        var data = {};
        data["_token"] = "{{ csrf_token() }}";
        data["id"] = "{{ $quote->id }}";

        if ($('#recurring-check').is(':checked')) {
            data["repeat"] = "yes";
            data["startdate"] = $('#repeat-start').val();
            data["enddate"] = $('#repeat-till').val();
            data["repeatschedule"] = $('#repeat').val();
            data["repeatemail"] = $('#repeatemail').val();


            if($('#repeat-days').val() === ""){
                $valid = false;
                $('#repeat-days').addClass('invalid');

                $.dialog({
                    title: 'Oops...',
                    content: 'Please enter a number of days.'
                });
                throw new Error("Validation Error");
            }
            data["repeatdays"] = $('#repeat-days').val();
        }else{
            data["repeat"] = "no";
        }

        $("body").addClass("loading");
        posting = $.post("/Clients/Quote/Finalize", data);

        posting.done(function( data ) {

            //$("body").removeClass("loading");
            {{--
            @if(SettingHelper::GetSetting("inventorymanagerid") != null)
            SendNotification("Inventory Issue!", "There is an issue on the stock level of some products, Please see the Invenrory Report in the Reporting section for more details.", "{{  SettingHelper::GetSetting("inventorymanagerid") }}");
            @endif
            --}}
            GoToPage('/Clients/View/{{ $client->id }}/transactions');
        });

        posting.fail(function() {
            $("body").removeClass("loading");
            bootstrap_alert.warning("Failed to contact server", 'danger', 4000);
        });
    });
});

function DisableSpecificDates(date) {

    var now = new Date();
    now.setDate(now.getDate() - 1);
    if (date < now) {
        return [false];
    } else {
        switch($('#repeat').val()) {
            case "1":
                return DisableSpecificDatesDailyEveryDay(date);
            case "2":
                return DisableSpecificDatesDailyWeekdayOnly(date);
            case "3":
                return DisableSpecificDatesWeekly(date);
            case "4":
                return DisableSpecificDatesMonthly(date);
            case "5":
                return DisableSpecificDatesLastdayofMonth(date);
            case "6":
                return DisableSpecificDatesYearly(date);
            case "7":
                return DisableSpecificDatesEveryXDays(date);
        }
    }
}

function DisableSpecificDatesDailyEveryDay(date){
    return [true];
}
function DisableSpecificDatesDailyWeekdayOnly(date){
    $day = date.getDay();

    if ($day === 0) {
        return [false];
    }else{
        if($day === 6){
            return [false];
        }else{
            return [true];
        }
    }
}
function DisableSpecificDatesWeekly(date){
    return [true];
}
function DisableSpecificDatesMonthly(date){
    $day = date.getDate();

    if ($day > 28) {
        return [false];
    }else{
        return [true];
    }
}
function DisableSpecificDatesLastdayofMonth(date){
    var d = new Date(date.getFullYear(), date.getMonth() + 1, 0);

    if(date.getTime() === d.getTime()){
        return [true];
    }else{
        return [false];
    }
}
function DisableSpecificDatesYearly(date){
    return [true];
}
function DisableSpecificDatesEveryXDays(date){
    return [true];
}

function GetValidDate() {

    switch($('#repeat').val()) {
        case "1":
            $date = GetValidDateDailyEveryDay();
            break;
        case "2":
            $date = GetValidDateDailyWeekdayOnly();
            break;
        case "3":
            $date = GetValidDateWeekly();
            break;
        case "4":
            $date = GetValidDateMonthly();
            break;
        case "5":
            $date = GetValidDateLastdayofMonth();
            break;
        case "6":
            $date = GetValidDateYearly();
            break;
        case "7":
            $date = GetValidDateEveryXDays();
            break;
    }

    return moment($date).format("YYYY-MM-DD");
}

function GetValidDateDailyEveryDay(){
    return new Date();
}
function GetValidDateDailyWeekdayOnly(){
    var $currentdate = new Date();
    $day = $currentdate.getDay();

    if ($day === 0) {
        return new Date($currentdate.getFullYear(), $currentdate.getMonth(), $currentdate.getDate() + 1);
    }else{
        if($day === 6){
            return new Date($currentdate.getFullYear(), $currentdate.getMonth(), $currentdate.getDate() + 2);
        }else{
            return $currentdate;
        }
    }
}
function GetValidDateWeekly(){
    return new Date();
}
function GetValidDateMonthly(){
    var $currentdate = new Date();
    $day = $currentdate.getDate();

    if ($day > 28) {
        return new Date($currentdate.getFullYear(), $currentdate.getMonth() + 1, 0);
    }else{
        return $currentdate;
    }
}
function GetValidDateLastdayofMonth(){
    var $currentdate = new Date();
    var d = new Date($currentdate.getFullYear(), $currentdate.getMonth() + 1, 0);
    return d;
}
function GetValidDateYearly(){
    return new Date();
}
function GetValidDateEveryXDays(){
    return new Date();
}

</script>
@stop
