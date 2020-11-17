@extends('master')

@section('content')
<link rel='stylesheet' type='text/css' href='/includes/InvoiceTest/style.css' />
<link rel='stylesheet' type='text/css' href='/includes/InvoiceTest/print.css' media="print" />
@desktop
<div class="row" style="margin-top: 5px;">
    <div style="float:left; width: 18em; margin-left: 20px;">
        <button style="width: 100%;" class="col-md-12 btn OS-Button" id="save" name="save" type="button">Save as Quote</button>
    </div> 
    
    <div style="float:left; width: 18em;  margin-left: 20px;">
        <button style="width: 100%;" class="col-md-12 btn OS-Button btn" type="button" data-toggle="modal" data-target="#ProductModal">
            Add Product
        </button>
    </div>   
    
    <div style="float:left; width: 18em; margin-left: 20px;">
        <button style="width: 100%;" class="col-md-12 btn OS-Button btn" type="button" data-toggle="modal" data-target="#ExpenseModal">
            Include an Expense
        </button>
    </div>

    <div style="float:left; width: 18em; margin-left: 20px;">
        <button style="width: 100%;" class="col-md-12 btn OS-Button btn" type="button" data-toggle="modal" data-target="#HoursModal">
            Include Billable Hours
        </button>
    </div>

    <div style="float:left; width: 18em; margin-left: 20px;">
        <button style="width: 100%;" class="col-md-12 btn OS-Button" id="invoice" name="invoice" type="button">Save as Invoice</button>
    </div>
</div>
@elsedesktop
<div class="row" style="margin-top: 5px;">
    <div class="col-md-6">
        <button style="width: 100%;" class="col-md-12 btn OS-Button" id="save" name="save" type="button">Save as Quote</button>
    </div>

    <div class="col-md-6">
        <button style="width: 100%;" class="col-md-12 btn OS-Button btn" type="button" data-toggle="modal" data-target="#ProductModal">
            Add Product
        </button>
    </div>

    <div class="col-md-6">
        <button style="width: 100%;" class="col-md-12 btn OS-Button btn" type="button" data-toggle="modal" data-target="#ExpenseModal">
            Include an Expense
        </button>
    </div>

    <div class="col-md-6">
        <button style="width: 100%;" class="col-md-12 btn OS-Button btn" type="button" data-toggle="modal" data-target="#HoursModal">
            Include Billable Hours
        </button>
    </div>

    <div class="col-md-6">
        <button style="width: 100%;" class="col-md-12 btn OS-Button" id="invoice" name="invoice" type="button">Save as Invoice</button>
    </div>
</div>
@enddesktop

<!--
    <div class="row controls" style="margin-top: 20px;">
        <div class="col-md-4">

        </div>
        <div class="col-md-5">
            <button class="col-md-12 btn OS-Button btn" type="button" data-toggle="modal" data-target="#ProductModal">
                Add a product
            </button>
        </div>
        <div class="col-md-1">
            <button class="col-md-12 btn OS-Button" id="save" name="save" type="button">Save</button>
        </div>  
        <div class="col-md-2">
            <button class="col-md-12 btn OS-Button" id="invoice" name="invoice" type="button">Invoice</button>
        </div> 
    </div>
   --> 

    <div class="row" style="margin-top: 25px;">
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-addon" for="branchselect"><div style="width: 7em;">From Address:</div></span>
                <select id="branchselect" name="branchselect" class="form-control">
                    @foreach($branches as $branch)
                        @if($branch->default ===  1)
                        <option selected value="{{ $branch->id }}">{{ $branch->number }} {{ $branch->address1 }} {{ $branch->address2 }} {{ $branch->city }} {{ $branch->region }} {{ $branch->state }} {{ $branch->zip }}</option>
                        @else
                        <option value="{{ $branch->id }}">{{ $branch->number }} {{ $branch->address1 }} {{ $branch->address2 }} {{ $branch->city }} {{ $branch->region }} {{ $branch->state }} {{ $branch->zip }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="input-group" style="margin-top: 5px;">
                <span class="input-group-addon" for="branchselect"><div style="width: 7em;">To Address:</div></span>
                <select id="toselect" name="toselect" class="form-control">
                    @if($client->address_id != null)
                    <option value="{{ $client->id }}">{{ $client->getName() }} - {{ $client->address->number }} {{ $client->address->address1 }} {{ $client->address->address2 }} {{ $client->address->city }} {{ $client->address->state }} {{ $client->address->zip }}</option>
                    @endif
                </select>
            </div>

        </div>
        <div class="col-md-4">
            <div id="customer" class="col-md-12">
                <table id="meta" class="col-md-12">
                    <tr>
                        <td class="meta-head">Quote #</td>
                        <td><input id="quoteid" class="noborder form-control input-md quoteid" name="quoteid" type="text" disabled="true"></td>
                    </tr>
                    <tr>
                        <td class="meta-head">Date</td>
                        <td><textarea id="date"></textarea></td>
                    </tr>
                </table>
            </div> 
        </div>
    </div>

    <div class="row">
    <div class="col-md-12">
        <table id="items" style="table-layout: fixed;">

            <THEAD>
                <tr>
                    <th class="col-md-1">SKU</th>
                    <th>Description</th>
                    <th class="col-md-1">Unit Cost ({{ $currency }})</th>
                    <th style="width: 80px;">Qty</th>
                    <th class="col-md-1">Price ({{ $currency }})</th>
                    <th style="width: 80px;">Tax (%)</th>
                    <th class="col-md-1">Tax ($)</th>
                    <th class="col-md-1">Total</th>
                    <th style="border: none; background: none; width: 46px;"></th>
                </tr>
            </THEAD>
            <tbody>
            @if(isset($quote))
                @foreach($quote->quoteitem as $item)
                    <tr class="item-row">
                        <td name="sku" class="item-name"><input class="noborder form-control input-md sku" name="sku" type="text" disabled="true" value="{{ $item->SKU }}"></td>
                        <td name="description" class="description"><input class="noborder form-control input-md description" name="description" type="text" value="{{ $item->description }}"></td>
                        <td name="costperunit" ><input class="noborder form-control input-md cost" name="cost" type="number" value="{{ number_format($item->costperunit, 2, '.', '') }}"></td>
                        <td name="units" style="width: 65px;"><input style="width: 100%; float: left;" class="noborder form-control input-md qty" name="qty" type="number" value="{{ floatval($item->units) }}"></td>
                        <td name="total"><input class="noborder form-control input-md total" name="total" type="number" value="" disabled="true"></td>
                        <td name="tax-percent" style="width: 65px;"><input style="width: 100%; float: left;" class="noborder form-control input-md tax-percent" name="tax-percent" type="number" value="{{ floatval($item->tax) }}"></td>
                        <td name="tax-total" id="tax-total" ><input class="noborder form-control input-md tax-total" name="tax-total" type="number" value="0" disabled="true"></td>
                        <td name="line-total" id="line-total" ><input class="noborder form-control input-md line-total" name="line-total" type="number" value="0" disabled="true"></td>
                        <td name="id" hidden="true"><input class="form-control input-md id" name="id" type="number" value="{{ $item->id }}" disabled="true"></td>
                        <td name="delete" style="border-right: none; border-top: none; border-bottom: none;"><button id="delete" name="delete" type="button" class="btn OS-Button btn-sm" value="0"><span class="glyphicon glyphicon-trash"></span></button></td>
                    </tr>
                @endforeach
            @else
                <tr class="item-row">
                    <td name="sku" class="item-name"><input class="noborder form-control input-md sku" name="sku" type="text" disabled="true" value=""></td>
                    <td name="description" class="description"><input class="noborder form-control input-md description" name="description" type="text" value=""></td>
                    <td name="costperunit" ><input class="noborder form-control input-md cost" name="cost" type="number" value="0.00"></td>
                    <td name="units" style="width: 65px;"><input style="width: 100%; float: left;" class="noborder form-control input-md qty" name="qty" type="number" value="0"></td>
                    <td name="total"><input class="noborder form-control input-md total" name="total" type="number" value="" disabled="true" value="0.00"></td>
                    <td name="tax-percent" style="width: 65px;"><input style="width: 100%; float: left;" class="noborder form-control input-md tax-percent" name="tax-percent" type="number" value="{{ SettingHelper::GetSalesTax() }}"></td>
                    <td name="tax-total" id="tax-total" ><input class="noborder form-control input-md tax-total" name="tax-total" type="number" value="0" disabled="true"></td>
                    <td name="line-total" id="line-total" ><input class="noborder form-control input-md line-total" name="line-total" type="number" value="0" disabled="true"></td>
                    <td name="id" hidden="true"><input class="form-control input-md id" name="id" type="number" value="0" disabled="true"></td>
                    <td name="delete" style="border-right: none; border-top: none; border-bottom: none;"><button id="delete" name="delete" type="button" class="btn OS-Button btn-sm" value="0"><span class="glyphicon glyphicon-trash"></span></button></td>
                </tr>
            @endif
            </tbody>
                <tfoot>
                <tr>
                    <td colspan="6" class="blank"> </td>
                    <td colspan="1" class="total-line">Subtotal</td>
                    <td class="total-value"><div id="subtotal">{{ $currency }}0.00</div></td>

                </tr>
                <tr>
                    <td colspan="6" class="blank"> </td>
                    <td colspan="1" class="total-line">Tax</td>
                    <td class="total-value"><div id="total-tax">{{ $currency }}0.00</div></td>
                </tr>
                <tr>

                    <td colspan="6" class="blank"> </td>
                    <td colspan="1" class="total-line">Total</td>
                    <td class="total-value"><div id="total">{{ $currency }}0.00</div></td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <textarea class="form-control" rows="5" id="comment" placeholder="Comments"></textarea>
    </div>
</div>
<input id="quote-id" style="display: none;">


@include('OS.QuotesOld.modals.stockmodal')
@include('OS.QuotesOld.modals.addproduct')
@include('OS.QuotesOld.modals.addexpense')
@include('OS.QuotesOld.modals.addhours')

<script>
$(document).ready(function() {

    $('#quote-id').val('0');
    $quotenumber = 0;
    $contact_id = {{ $client->contacts[0]->id }};

    @if($client->address_id === null)
    $.confirm({
        title: 'Address!',
        content: 'There is no address set for this client, you may want to set this before you generate them an Quote.',
        buttons: {
            "Continue Anyway": function () {

            },
            "Set Address": function () {
                GoToPage('/Clients/Edit/{{ $client->id }}')
            }
        }
    });
    @endif

    @if(isset($quote))

        $('#quote-id').val('{{ $quote->id }}');
        $quotenumber = {{ $quote->getQuoteNumber() }};
        $('#quoteid').val($quotenumber);
        $('#branchselect').val("{{ $quote->branch_id }}").change();


        $(".item-row").each(function(i) {
            update_price($(this));
        });
        update_total();

        $('#comment').val("{{ $quote->comments }}");
    @else
        @if(Auth::user()->branch_id != null)
            $('#branchselect').val("{{ Auth::user()->branch_id }}").change();
        @else

        @endif
    @endif
    

    
    $('#branchselect').change(function(){
        switch($(this).val()) {
            @foreach($branches as $branch)
            case '{{ $branch->id }}':
                $('#returnaddress').html('<h6>Return Address:</h6><textarea rows="4" name="returnaddress" id="returnaddress" disabled="true">{{ $branch->number }} {{ $branch->address1 }}&#13;&#10;{{ $branch->address2 }}&#13;&#10;{{ $branch->city }}, {{ $branch->region }}, {{ $branch->state }}, {{ $branch->zip }}</textarea>');
                break;
            @endforeach    
            default:
                
        };
    });


    $('input').click(function(){
      $(this).select();
    });

    $("#paid").blur(update_balance);
    
    $(".delete").on('click', 'a', function(){
        alert("test");

    });

    $("#cancel-logo").click(function(){
      $("#logo").removeClass('edit');
    });

    $("#delete-logo").click(function(){
      $("#logo").remove();
    });
    
    $("#change-logo").click(function(){
      $("#logo").addClass('edit');
      $("#imageloc").val($("#image").attr('src'));
      $("#image").select();
    });
    
    $("#save-logo").click(function(){
      $("#image").attr('src',$("#imageloc").val());
      $("#logo").removeClass('edit');
    });

    $("#date").val(print_today());
  
    $("#invoice").click(function()
    {
        $.confirm({
            title: "Are you sure you want to finalize this quote? You will be unable to make changes to it and the client will be billed",
            buttons: {
                confirm: function() {
                    $("body").addClass("loading");
                    $table = CreateTableArray();

                    PostQuote($('#quote-id').val(), "{{ $client->id }}", $contact_id, $quotenumber, $('#comment').val(), $('#branchselect').val(), $table, true);
                },
                cancel: function() {
                    // nothing to do

                }
            }
        });

        
    });
    
    $("#save").click(function()
    {
        $table = CreateTableArray();
        if($table.length === 0){
            if($('#quote-id').val() === "0"){

            }else{
                $.confirm({
                    title: 'Delete Quote!',
                    content: 'There are no items on this quote, would you like to delete it or continue modifying it?',
                    buttons: {
                        Delete: function () {
                            DeleteQuote($('#quote-id').val());
                        },
                        Modify: function () {

                        }
                    }
                });
            }
        }else{
            PostQuote($('#quote-id').val(), "{{ $client->id }}", $contact_id, $quotenumber, $('#comment').val(), $('#branchselect').val(), $table, false);
        }
    });
} );

function DeleteQuote($id){

    $("body").addClass("loading");

    $data = {};
    $data['_token'] = "{{ csrf_token() }}";
    $data['id'] = $id;

    $post = $.post("/Clients/Quote/Delete", $data);

    $post.done(function (data) {

        if(data === "done"){
            GoToPage('/Clients/View/' + "{{ $client->id }}");
        }
        if(data === "fail"){
            $("body").removeClass("loading");
            $.dialog({
                title: 'Oops...',
                content: 'Failed to find quote, Please refresh the page and try again.'
            });
        }
    });

    $post.fail(function () {
        NoReplyFromServer();
    });
}

function AddTableRow($sku, $description, $costperunit, $units, $taxpercent) {

    var newHtml = '<tr class="item-row">\\n\\\n' +
        '<td name="sku" class="item-name"><input class="noborder form-control input-md sku" name="sku" type="text" disabled="true" value=""></td>' +
        '<td name="description" class="description"><input class="noborder form-control input-md description" name="description" type="text" value=""></td>' +
        '<td name="costperunit" ><input class="noborder form-control input-md cost" name="costperunit" type="number" value=""></td>' +
        '<td name="units" style="width: 65px;"><input style="width: 100%; float: left;" class="noborder form-control input-md qty" name="units" type="number" value=""></td>' +
        '<td name="total"><input class="noborder form-control input-md total" name="total" type="number" value="" disabled="true"></td>' +
        '<td name="tax-percent" style="width: 65px;"><input style="width: 100%; float: left;" class="noborder form-control input-md tax-percent" name="tax-percent" type="number" value=""></td>' +
        '<td name="tax-total" id="tax-total" ><input class="noborder form-control input-md tax-total" name="tax-total" type="number" value="0" disabled="true"></td>' +
        '<td name="line-total" id="line-total" ><input class="noborder form-control input-md line-total" name="line-total" type="number" value="0" disabled="true"></td>' +
        '<td name="id" style="display: none;"><input class="form-control input-md id" name="id" type="number" value="0" disabled="true"></td>' +
        '<td name="delete" style="border-right: none; border-top: none; border-bottom: none;"><button id="delete" name="delete" type="button" class="btn OS-Button btn-sm" value="0"><span class="glyphicon glyphicon-trash"></span></button></td>' +
        '</tr>';

    var newElementsAppended = $(newHtml).appendTo("#items tbody");

    $(newElementsAppended.find('[name="sku"]')[1]).val($sku);
    $(newElementsAppended.find('[name="description"]')[1]).val($description);
    $(newElementsAppended.find('[name="costperunit"]')[1]).val(parseFloat($costperunit).toFixed(2));
    $(newElementsAppended.find('[name="units"]')[1]).val($units);
    $(newElementsAppended.find('[name="tax-percent"]')[1]).val($taxpercent);

/*
    $("#items tbody").append('<tr class="item-row">\n\
            <td name="sku" class="item-name"><input class="noborder form-control input-md sku" name="sku" type="text" disabled="true" value="'+ $sku +'"></td>\n\
            <td name="description" class="description"><input class="noborder form-control input-md description" name="description" type="text" value=""></td>\n\
            <td name="costperunit" ><input class="noborder form-control input-md cost" name="cost" type="number" value="'+ $costperunit +'"></td>\n\
            <td name="units" style="width: 65px;"><input style="width: 100%; float: left;" class="noborder form-control input-md qty" name="qty" type="number" value="' + $units + '"></td>\n\
            <td name="total"><input class="noborder form-control input-md total" name="total" type="number" value="" disabled="true"></td>\n\
            <td name="tax-percent" style="width: 65px;"><input style="width: 100%; float: left;" class="noborder form-control input-md tax-percent" name="tax-percent" type="number" value="'+ $taxpercent +'"></td>\n\
            <td name="tax-total" id="tax-total" ><input class="noborder form-control input-md tax-total" name="tax-total" type="number" value="0" disabled="true"></td>\n\
            <td name="line-total" id="line-total" ><input class="noborder form-control input-md line-total" name="line-total" type="number" value="0" disabled="true"></td>\n\
            <td name="id" style="display: none;"><input class="form-control input-md id" name="id" type="number" value="0" disabled="true"></td>\n\
            <td name="delete" style="border-right: none; border-top: none; border-bottom: none;"><button id="delete" name="delete" type="button" class="btn OS-Button btn-sm" value="0"><span class="glyphicon glyphicon-trash"></span></button></td>\n\
        </tr>').children(' input.description').val($HTMLdescription);
*/


    if ($(".delete").length > 0) $(".delete").show();

    $lastrow = $(".item-row:last");
    update_price($lastrow);
}

// Hack for quotes, not needed for other save methods
function CreateTableArray() {

    var $table = [];
    $(".item-row").each(function(i) {

        var $rowdata = {};

        $(this).children('td').each(function(i) {
            $rowdata[$(this).attr('name')] = $(this).children('input').val();
        });

        if($rowdata['units'] != "0"){
            $table.push($rowdata);
        }
    });
    return $table;
}
function PostQuote($id, $client_id, $contact_id, $quotenumber, $commments, $branch_id, $tabledata, $finalize) {
    console.log($commments);
    $("body").addClass("loading");

    $post = $.post("/Clients/Quote/Save",
    {
        _token: "{{ csrf_token() }}",
        id: $id,
        client_id: $client_id,
        contact_id: $contact_id,
        quotenumber: $quotenumber,
        comments: $commments,
        branch_id: $branch_id,
        tabledata: $tabledata,
    });

    $post.done(function(data) {
        $("body").removeClass("loading");
        PostQuoteDone(data, $finalize);
    });

    $post.fail(function() {
        $("body").removeClass("loading");
        bootstrap_alert.warning("Failed to contact server", 'danger', 4000);
    });

}
function PostQuoteDone(data, $finalize){

    ResetServerValidationErrors();
    if(data[0] === "ok") {
        $id = AssignIDsToRows(data[1]);
        delete data[0];
        delete data[1];
        
        if($finalize == false){
            $.confirm({
                title: 'Quote Saved!',
                content: 'Would you like to view the stock report for this quote?',
                buttons: {
                    YES: function () {
                        StockAlert(data);
                    },
                    NO: function () {

                    }
                }
            });
        }else{
            GoToPage('/Clients/Quote/Finalize/' + $id);
        }
    }else{
        $("body").removeClass("loading");
        ServerValidationErrors(data);
    }
    //bootstrap_alert.warning("Quote Saved", 'success', 4000);
}

function AssignIDsToRows($data){

    var arr = $data.split('/');
    $id = arr[0];
    $('#quoteid').val(parseInt($id) + parseInt("100000"));
    $('#quote-id').val($id);

    $c = 1;
    $(".item-row").each(function(i) {
        $(this).find('.id').val(arr[$c]);
        $c++;
    });
    return $id;
}
   
function StockAlert($data){
    $('#StockModal').data('data' , $data);
    $('#StockModal').modal('show');
}   
   

function PostFinalize($id) {

    $.post("/Clients/Quote/Finalize",
    {
        _token: "{{ csrf_token() }}",
        id: $id
    });
    
    $post.done(function( data ) 
    {
        $("body").removeClass("loading");
        bootstrap_alert.warning("", 'success', 4000);
        window.location.href = "/Clients/Invoice/View/" + $id;
    });

    $post.fail(function() {
        $("body").removeClass("loading");
        bootstrap_alert.warning("Failed to contact server", 'danger', 4000);
    });
}

function print_today() {
    // ***********************************************
    // AUTHOR: WWW.CGISCRIPT.NET, LLC
    // URL: http://www.cgiscript.net
    // Use the script, just leave this message intact.
    // Download your FREE CGI/Perl Scripts today!
    // ( http://www.cgiscript.net/scripts.htm )
    // ***********************************************
    var now = new Date();
    var months = new Array('January','February','March','April','May','June','July','August','September','October','November','December');
    var date = ((now.getDate()<10) ? "0" : "")+ now.getDate();
    function fourdigits(number) {
        return (number < 1000) ? number + 1900 : number;
    }
    var today =  months[now.getMonth()] + " " + date + ", " + (fourdigits(now.getYear()));
    return today;
}

// from http://www.mediacollege.com/internet/javascript/number/round.html
function roundNumber(number,decimals) {
    var newString;// The new rounded number
    decimals = Number(decimals);
    if (decimals < 1) {
        newString = (Math.round(number)).toString();
    } else {
        var numString = number.toString();
        if (numString.lastIndexOf(".") == -1) {// If there is no decimal point
            numString += ".";// give it one at the end
        }
        var cutoff = numString.lastIndexOf(".") + decimals;// The point at which to truncate the number
        var d1 = Number(numString.substring(cutoff,cutoff+1));// The value of the last decimal place that we'll end up with
        var d2 = Number(numString.substring(cutoff+1,cutoff+2));// The next decimal, after the last one we want
        if (d2 >= 5) {// Do we need to round up at all? If not, the string will just be truncated
            if (d1 == 9 && cutoff > 0) {// If the last digit is 9, find a new cutoff point
                while (cutoff > 0 && (d1 == 9 || isNaN(d1))) {
                    if (d1 != ".") {
                        cutoff -= 1;
                        d1 = Number(numString.substring(cutoff,cutoff+1));
                    } else {
                        cutoff -= 1;
                    }
                }
            }
            d1 += 1;
        }
        if (d1 == 10) {
            numString = numString.substring(0, numString.lastIndexOf("."));
            var roundedNum = Number(numString) + 1;
            newString = roundedNum.toString() + '.';
        } else {
            newString = numString.substring(0,cutoff) + d1.toString();
        }
    }
    if (newString.lastIndexOf(".") == -1) {// Do this again, to the new string
        newString += ".";
    }
    var decs = (newString.substring(newString.lastIndexOf(".")+1)).length;
    for(var i=0;i<decimals-decs;i++) newString += "0";
    //var newNumber = Number(newString);// make it a number if you like
    return newString; // Output the result to the form field (change for your purposes)
}

function update_total() {
    var subtotal = 0;
    $('.total').each(function(i){
        price = $(this).val().replace("$","");
        if (!isNaN(price)) subtotal += Number(price);
    });

    var taxtotal = 0;
    $('.tax-total').each(function(i){
        price = $(this).val().replace("$","");
        if (!isNaN(price)) taxtotal += Number(price);
    });

    total = taxtotal + subtotal;
    total = roundNumber(total,2);
    subtotal = roundNumber(subtotal,2);
    taxtotal = roundNumber(taxtotal,2);

    $('#subtotal').html("$"+subtotal);
    $('#total-tax').html("$"+taxtotal);
    $('#total').html("$"+total);

}

function update_balance() {
    var due = $("#total").html().replace("$","") - $("#paid").val().replace("$","");
    due = roundNumber(due,2);

    $('.due').html("$"+due);
}

function update_price(row) {

    var cost = row.find('.cost').val();
    var qty = row.find('.qty').val();
    var tax = row.find('.tax-percent').val();

    var price = cost * qty;
    taxmath = (price / 100) * tax;
    var total = price + taxmath;

    row.find('.tax-total').val(taxmath.toFixed(2));
    row.find('.total').val(price.toFixed(2));
    row.find('.line-total').val(total.toFixed(2));

    update_total();
}

$(document).on('click', '#delete', function() {
    $(this).parents('.item-row').remove();
    update_total();
});

$(document).on('change', ".cost", function() {
    update_price($(this).parents('.item-row'));
});

$(document).on('change', ".qty", function() {
    update_price($(this).parents('.item-row'));
});

$(document).on('change', ".tax-percent", function() {
    update_price($(this).parents('.item-row'));
});
</script>
@stop
