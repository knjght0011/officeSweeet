@extends('master')

@section('content')
{{--
No Longer used see /views/OS/Quotes
--}}

<link rel='stylesheet' type='text/css' href='/includes/InvoiceTest/style.css' />
<link rel='stylesheet' type='text/css' href='/includes/InvoiceTest/print.css' media="print" />

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
    
    
    <div class="row">
        <div class="col-md-8">
            <label class="col-md-2 control-label" for="branchselect">From Address:</label>
            <select id="branchselect" name="branchselect" class="form-control">
                @foreach($branches as $branch)
                <option value="{{ $branch->id }}">{{ $branch->number }} {{ $branch->address1 }} {{ $branch->address2 }} {{ $branch->city }} {{ $branch->region }} {{ $branch->state }} {{ $branch->zip }}</option>
                @endforeach
            </select>

            <label class="col-md-2 control-label" for="toselect">To Address:</label>
            <select id="toselect" name="toselect" class="form-control">
                <option value="{{ $branch->id }}">{{ $client->address->number }} {{ $client->address->address1 }} {{ $client->address->address2 }} {{ $client->address->city }} {{ $client->address->state }} {{ $client->address->zip }}</option>
            </select>
        </div>
        <div class="col-md-4">
            <div id="customer" style="margin-top: 25px;" class="col-md-12">
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
        
        <table id="items">

            <tr>
                <th class="col-md-1">SKU</th>
                <th>Description</th>
                <th class="col-md-1">Unit Cost ({{ $currency }})</th>
                <th class="col-md-1">Qty</th>
                <th class="col-md-1">Price ({{ $currency }})</th>
                <th class="col-md-1" style="border: none; background: none;"></th>
            </tr>

            <tr class="item-row">
                <td class="item-name"><input class="noborder form-control input-md sku" name="sku" type="text"></td>
                <td class="description"><input class="noborder form-control input-md description" name="description" type="text"></td>
                <td ><input class="noborder form-control input-md cost" name="cost" type="number" value="0"></td>
                <td class="qty" ><input class="noborder form-control input-md qty" name="qty" type="number" value="0"></td>
                <td hidden="true"><input class="form-control input-md id" name="id" type="number" value="0" disabled="true"></td>
                <td style="width: 12px !important;"><button id="delete" name="delete" type="button" class="btn OS-Button btn-sm">Delete Item</button></td>
            </tr>

            <tr>
                <td colspan="2" class="blank"> </td>
                <td colspan="2" class="total-line">Subtotal</td>
                <td class="total-value"><div id="subtotal">{{ $currency }}0.00</div></td>
            </tr>
            <tr>
                <td colspan="2" class="blank"> </td>
                <td colspan="2" class="total-line">Sales Tax</td>
                <td class="total-value"><div id="subtotal">{{ $currency }}0.00</div></td>
            </tr>
            <tr>
                <td colspan="2" class="blank"> </td>
                <td colspan="2" class="total-line">City Tax</td>
                <td class="total-value"><div id="subtotal">{{ $currency }}0.00</div></td>
            </tr>
            <tr>

                <td colspan="2" class="blank"> </td>
                <td colspan="2" class="total-line">Total</td>
                <td class="total-value"><div id="total">{{ $currency }}0.00</div></td>
            </tr>

        </table>

    </div>    
</div>

@include('Clients.modals.addproduct')

<script>
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
  var total = 0;
  $('.total').each(function(i){
    price = $(this).val().replace("$","");
    if (!isNaN(price)) total += Number(price);
  });

  total = roundNumber(total,2);

  $('#subtotal').html("$"+total);
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
    var price = cost * qty;
    row.find('.total').val(price.toFixed(2))
    
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



$(document).ready(function() {

    $id = 0;
    $quotenumber = 0;
    $contact_id = {{ $client->contacts[0]->id }};

    @if(isset($quote))
        $id = {{ $quote->id }};
        $quotenumber = {{ $quote->getQuoteNumber() }};
        $('#quoteid').val($quotenumber);
        $('#branchselect').val("{{ $quote->branch_id }}").change();
        
        @foreach($quote->quoteitem as $item)
            $(".item-row:last").after('<tr class="item-row">\n\
                                            <td name="sku" class="item-name"><input class="noborder form-control input-md sku" name="sku" type="text" disabled="true" value="{{ $item->SKU }}"></td>\n\
                                            <td name="description" class="description"><input class="noborder form-control input-md description" name="description" type="text" value="{{ $item->description }}"></td>\n\
                                            <td name="costperunit" ><input class="noborder form-control input-md cost" name="cost" type="number" value="{{ number_format($item->costperunit , 2) }}"></td>\n\
                                            <td name="units" ><input class="noborder form-control input-md qty" name="qty" type="number" value="{{ number_format($item->units , 0) }}"></td>\n\
                                            <td name="total" ><input class="noborder form-control input-md total" name="total" type="number" value="" disabled="true"></td>\n\
                                            <td name="id" hidden="true"><input class="form-control input-md id" name="id" type="number" value="{{ $item->id }}" disabled="true"></td>\n\
                                            <td name="delete" style="border-right: none; border-top: none; border-bottom: none;"><button id="delete" name="delete" type="button" class="btn OS-Button btn-sm" value="0"><span class="glyphicon glyphicon-trash"></span></button></td>\n\
                                        </tr>');
            update_price($(".item-row:last"));
            update_total();
        @endforeach
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
    $('#branchselect').val("{{ Auth::user()->branch_id }}").change();

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

                    $post = PostQuote($id, "{{ $client->id }}", $contact_id, $quotenumber, "", $('#branchselect').val(), $table);

                    $post.done(function(data) {
                        PostQuoteDone(data, true);
                    });

                    $post.fail(function() {
                        $("body").removeClass("loading");
                        bootstrap_alert.warning("Failed to contact server", 'danger', 4000);
                    });
                },
                cancel: function() {
                    // nothing to do

                }
            }
        });

        
    });
    
$("#save").click(function()
    {
        
        $("body").addClass("loading");

        $table = CreateTableArray();

        $post = PostQuote($id, "{{ $client->id }}", $contact_id, $quotenumber, "", $('#branchselect').val(), $table);
        
        $post.done(function(data) {
            $("body").removeClass("loading");
            PostQuoteDone(data, false);
        });
        
        $post.fail(function() {
            $("body").removeClass("loading");
            bootstrap_alert.warning("Failed to contact server", 'danger', 4000);
        });
        
    });
} );

// Hack for quotes, not needed for other save methods
function CreateTableArray() {
    $row = -1;
    var $table = [];
    $(".item-row").each(function(i) {

        if($row > -1) {
            var $rowdata = {};

            $(this).children('td').each(function(i) {
                $rowdata[$(this).attr('name')] = $(this).children('input').val();
            });
            $table[$row] = $rowdata;
        }
        $row = $row + 1;
    });
    return $table;
}
function PostQuote($id, $client_id, $contact_id, $quotenumber, $commments, $branch_id, $tabledata) {

    return $.post("/Clients/Quote/Save",
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
}
function PostQuoteDone(data, $finalize){
    if( Object.prototype.toString.call(data) == '[object String]' ) {
        var arr = data.split('/');
        $id = arr[0];
        $('#quoteid').val(parseInt($id) + parseInt("100000"));
        $c = 0;
        $(".item-row").each(function(i) {
            $(this).find('.id').val(arr[$c])
            $c++;
        });
        if($finalize == true){
            PostFinalize($id);
        }
    }else{
        ServerValidationErrors(data);
    }
    bootstrap_alert.warning("Quote Saved", 'success', 4000);
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
</script>
@stop
