@extends('master')

@section('content')
{{--
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
--}}
<div class="row">
    <div class="col-md-4">
        <legend style="font-size: 27px;">Labor and Materials List</legend>
    </div>
    <div class="col-md-4">
        <table class="table">
            <tr>
                <td>Invoice Total:</td>
                <td>${{ $quote->getTotal() }}</td>
            </tr>
            <tr>
                <td>Job Cost:</td>
                <td>${{ $quote->getCost() }}</td>
            </tr>
            <tr>
                <td style="border-top-width: 5px;">Profit:</td>
                <td style="border-top-width: 5px;">${{ $quote->getProfit() }}</td>
            </tr>
        </table>

    </div>
    <div class="col-md-4">
        <button style="width: 100%;" class="btn OS-Button" id="save" name="save" type="button">Process</button>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th class="col-md-1">SKU</th>
            <th class="col-md-1">Type</th>
            <th>Description</th>
            <th class="col-md-1">Units</th>
            <th class="col-md-1">Cost</th>
            <th class="col-md-1">Stock Status</th>
            <th class="col-md-1">Vendor</th>
            <th class="col-md-2">Action</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="8" style="text-align: center; background-color: lightblue;">Products</td>
        </tr>
        @php
            $productstotal = 0;
        @endphp
        @foreach($quote->GetProductItems() as $item)
            @if($item->getVendor() != "No Vendor Set")
                <tr>
                    <td name="sku" class="item-name">{{ $item->SKU }}</td>
                    <td name="type">{{ $item->LinkType() }}</td>
                    <td name="description" class="description">{{ $item->description }}</td>
                    <td name="units" >{{ number_format($item->units , 0) }}</td>
                    <td>${{ number_format($item->getCost(), 2, ".", "") }}</td>
                    @php
                        $productstotal = $productstotal + $item->getCost();
                    @endphp
                    <td>{{ $item->getStock() }}</td>
                    <td>{{ $item->getVendor() }}</td>
                    <td>
                        @if($item->getStock() === "Product Not held in Inventory")
                            <input type="checkbox" name="checkboxes" class="create-po" data-on="Create PO for {{ number_format($item->units , 0) }} Items" data-off="Don't Create PO" data-toggle="toggle" data-width="100%" data-itemid="{{ $item->id }}" checked>
                        @else
                            @if($item->getStock() - $item->units >= 0)
                                <input type="checkbox" name="checkboxes" class="alter-stock" data-on="Remove {{ number_format($item->units , 0) }} Item from stock count" data-off="Don't change stock" data-toggle="toggle" data-width="100%" data-itemid="{{ $item->id }}" checked>
                            @else
                                <input type="checkbox" name="checkboxes" class="create-po" data-on="Create PO for {{ number_format($item->units , 0) }} Items" data-off="Don't Create PO" data-toggle="toggle" data-width="100%" data-itemid="{{ $item->id }}" checked>
                            @endif
                        @endif
                    </td>
                </tr>
            @endif
        @endforeach
        <tr style="background-color: lightgrey">
            <td colspan="3"></td>
            <td>Total Materials</td>
            <td>${{ number_format($productstotal, 2, ".", "") }}</td>
            <td colspan="3"></td>
        </tr>
        <tr>
            <td colspan="8" style="text-align: center; background-color: lightblue;">Services and Expenses</td>
        </tr>
        @php
            $expencestotal = 0;
        @endphp
        @foreach($quote->GetProductItems() as $item)
            @if($item->getVendor() === "No Vendor Set")
                <tr>
                    <td name="sku" class="item-name">{{ $item->SKU }}</td>
                    <td name="type">{{ $item->LinkType() }}</td>
                    <td name="description" class="description">{{ $item->description }}</td>
                    <td name="units" >{{ number_format($item->units , 0) }}</td>
                    <td>${{ number_format($item->getCost(), 2, ".", "") }}</td>
                    @php
                        $expencestotal = $expencestotal + $item->getCost();
                    @endphp
                    <td>{{ $item->getStock() }}</td>
                    <td>{{ $item->getVendor() }}</td>
                    <td></td>
                </tr>
            @endif
        @endforeach
        @foreach($quote->GetServiceItems() as $item)
            <tr>
                <td name="sku" class="item-name">{{ $item->SKU }}</td>
                <td name="type">{{ $item->LinkType() }}</td>
                <td name="description" class="description">{{ $item->description }}</td>
                <td name="units" >{{ number_format($item->units , 0) }}</td>
                <td>${{ number_format($item->getCost(), 2, ".", "") }}</td>
                @php
                    $expencestotal = $expencestotal + $item->getCost();
                @endphp
                <td colspan="3"></td>
            </tr>
        @endforeach
        @foreach($quote->GetHoursItems() as $item)
            <tr>
                <td name="sku" class="item-name">{{ $item->SKU }}</td>
                <td name="type">{{ $item->LinkType() }}</td>
                <td name="description" class="description">{{ $item->description }}</td>
                <td name="units" >{{ number_format($item->units , 0) }}</td>
                <td>${{ number_format($item->getCost(), 2, ".", "") }}</td>
                @php
                    $expencestotal = $expencestotal + $item->getCost();
                @endphp
                <td colspan="3"></td>
            </tr>
        @endforeach
        @foreach($quote->GetExpenceItems() as $item)
            <tr>
                <td name="sku" class="item-name">{{ $item->SKU }}</td>
                <td name="type">{{ $item->LinkType() }}</td>
                <td name="description" class="description">{{ $item->description }}</td>
                <td name="units" >{{ number_format($item->units , 0) }}</td>
                <td>${{ number_format($item->getCost(), 2, ".", "") }}</td>
                @php
                    $expencestotal = $expencestotal + $item->getCost();
                @endphp
                <td colspan="3"></td>
            </tr>
        @endforeach
        @foreach($quote->GetNoLinkItems() as $item)
            <tr>
                <td name="sku" class="item-name">{{ $item->SKU }}</td>
                <td name="type">{{ $item->LinkType() }}</td>
                <td name="description" class="description">{{ $item->description }}</td>
                <td name="units" >{{ number_format($item->units , 0) }}</td>
                <td>${{ number_format($item->getCost(), 2, ".", "") }}</td>
                @php
                    $expencestotal = $expencestotal + $item->getCost();
                @endphp
                <td colspan="3"></td>
            </tr>
        @endforeach
        <tr  style="background-color: lightgrey">
            <td colspan="3"></td>
            <td>Total</td>
            <td>${{ number_format($expencestotal, 2, ".", "") }}</td>
            <td colspan="3"></td>
        </tr>
    </tbody>
</table>

<script>
$( document ).ready(function() {
    $("#save").click(function() {

        $("body").addClass("loading");

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['quoteid'] = "{{ $quote->id }}";
        $data['po'] = {};

        $('.create-po').each(function () {
            if($(this).prop('checked') === true){
                $data['po'][$(this).data('itemid')] = 1;
            }else{
                $data['po'][$(this).data('itemid')] = 0;
            }
        });

        $data['inventory'] = {};

        $('.alter-stock').each(function () {
            if($(this).prop('checked') === true){
                $data['inventory'][$(this).data('itemid')] = 1;
            }else{
                $data['inventory'][$(this).data('itemid')] = 0;
            }
        });

        $post = $.post("/Quote/Final", $data);

        $post.done(function (data) {
            switch(data['status']) {
                case "OK":
                    window.location.reload();
                    break;
                case "notfound":
                    $("body").removeClass("loading");
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Response from server. Please refresh the page and try again.'
                    });
                    break;
                default:
                    $("body").removeClass("loading");
                    console.log(data);
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Response from server. Please refresh the page and try again.'
                    });
            }
        });

        $post.fail(function () {
            NoReplyFromServer();
        });

    });
    {{--
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
            {{--GoToPage('/Clients/View/{{ $client->id }}/transactions');--}}
    {{--
        });

        posting.fail(function() {
            $("body").removeClass("loading");
            bootstrap_alert.warning("Failed to contact server", 'danger', 4000);
        });
    });
    --}}
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
