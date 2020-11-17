@extends('master')

@section('content')

    <style>
        .delete{
            color: red;
            font-size: 29px;
        }
    </style>

    <h3 style="margin-top: 10px;">{{ TextHelper::GetText("Quote") }}</h3>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-4">
            <button style="width: 100%;" class="btn OS-Button" id="quote-save" name="quote-save" type="button" disabled>Save {{ TextHelper::GetText("Quote") }}</button>
        </div>

        <div class="col-md-4">
            <button style="width: 100%;" class="btn OS-Button btn" type="button" data-toggle="modal" data-target="#ProductModal">
                Add Item
            </button>
        </div>

        <div class="col-md-4">
            <button style="width: 100%;" class="btn OS-Button" id="quote-progress" name="quote-progress" type="button" disabled>Save as Invoice</button>
        </div>
    </div>

    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-addon" for="search"><div style="width: 7em;">To:</div></span>
                <select id="contact-select" class="form-control">
                    @foreach($client->contacts as $contact)
                        <option value="{{ $contact->id }}">{{ $contact->firstname }} {{ $contact->lastname }} - {{ $contact->address->AddressString() }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-addon" for="search"><div style="width: 7em;">From:</div></span>
                <select id="branch-select" class="form-control">
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->AddressString() }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>


    <table id="quote-table" class="table">
        <thead>
            <tr>
                <th style="width: 4px;"></th>
                <th class="col-md-1">SKU</th>
                <th>Description</th>
                <th class="col-md-1">Units</th>
                <th class="col-md-1">Unit Price ($)</th>
                <th class="col-md-1">Price ($)</th>
                <th class="col-md-1">Tax (%)</th>
                <th class="col-md-1">Tax ($)</th>
                <th class="col-md-1">CT (%)</th>
                <th class="col-md-1">CT ($)</th>
                <th class="col-md-1">Total ($)</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

    <div class="col-md-10">

        <textarea id="quote-comment" class='form-control' style="width: 100%; resize: none;" Rows="5" PLACEHOLDER="Comments"
        >@if(isset($quote)){{ $quote->comments }}@else{{ SettingHelper::GetSetting('quotecomment') }}@endif</textarea>

    </div>

    <div class="col-md-2">
        <table id="quote-table-totals" class="table" style="text-align: right;">
            <tbody>
                <tr>
                    <td class="col-md-1"><b>Subtotal</b></td>
                    <td id=""><input id="quote-total-subtotal" class='form-control' value='$0.00' readonly></td>
                </tr>
                <tr>
                    <td style="font-size: small;">Sales Tax</td>
                    <td><input id="quote-total-tax" class='form-control' value='$0.00' readonly></td>
                </tr>
                <tr>
                    <td style="font-size: small;">City Tax</td>
                    <td><input id="quote-total-citytax" class='form-control' value='$0.00' readonly></td>
                </tr>
                <tr>
                    <td><b>Total</b></td>
                    <td><input id="quote-total-total" class='form-control' value='$0.00' readonly></td>
                </tr>
            </tbody>
        </table>
    </div>

    <input id="quote-id" style="display: none;">
    <input id="citytax-percent" style="display: none;">


    <div class="modal fade" id="ShowPdfModel" tabindex="-1" role="dialog" aria-labelledby="ShowPdfModel" aria-hidden="true">
        <div style="margin: 2.5vh auto; width: 95vw" class="modal-dialog" role="document">
            <div style="height: 95vh; width: 95vw;" class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="ShowPdfModel">View Quote</h4>
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
        SetupPage();

        $('#ShowPdfModel').on('show.bs.modal', function (event) {
            //var button = $(event.relatedTarget); // Button that triggered the modal

            var url = "/Quote/PDF/";
            var id = $('#quote-id').val();

            $('#ShowPdfFrame').attr("src", url + id);
        });

        $('#client-select').select2({
            theme: "bootstrap"
        });

        $('#branch-select').select2({
            theme: "bootstrap"
        });

        $('#ShowPdfModel').on('hide.bs.modal', function (event) {
            $('#ShowPdfFrame').attr("src", "{{ url('images/loading4.gif') }}");
        });

        $('.calculate').keyup(function () {
            TableMath();
        });

        $('.total-modifyable').focusout(function(){
            if($(this).val() === ""){
                $(this).val("$0.00");
            }else{
                $string = "$" + parseFloat($(this).val()).toFixed(2);
                $(this).val($string);
            }
        });
        $('.numonly').on('keypress', function(e) {
            keys = ['0','1','2','3','4','5','6','7','8','9','.'];
            return keys.indexOf(event.key) > -1;
        });

        $('#quote-save').click(function () {
            SaveQuote(false);
        });

        $('#quote-progress').click(function () {
            SaveQuote(true);
        });
    });

    function TableData(){

        $table = [];
        $('#quote-table > tbody  > tr').each(function() {
            $row = {};
            $row['productid'] = $(this).data('productid');
            $row['type'] = $(this).data('type');
            $row['id'] = $(this).data('itemid');
            $row['sku'] = $(this).find('.sku').val();
            $row['description'] = $(this).find('.description').val();
            $row['units'] = $(this).find('.units').val();
            $row['costperunit'] = $(this).find('.unitcost').val();
            $row['tax-percent'] = $(this).find('.taxpercent').val();
            $row['cityTax'] = $(this).find('.citytaxpercent').val();

            $table.push($row);
        });

        return $table;
    }

    function AssignIDsToRows($items) {

        $('#quote-table > tbody  > tr').each(function($index, $value) {
            $(this).data('itemid', $items[$index]['id']);
        });

    }

    function AddRow($productid, $type, $itemid, $sku, $description, $units, $unitcost, $taxpercent, $citytaxpercent){


        $found = false;
        $('#quote-table > tbody  > tr').each(function() {


            if($(this).data('productid').toString() === $productid.toString() && $(this).data('type').toString() === $type.toString()){

                $unitsinput = $(this).find('.units');

                $currentunits = $unitsinput.val();

                $newunits =  parseFloat($currentunits) + parseFloat($units);

                $unitsinput.val($newunits);

                $found = true;
            }
        });

        if($found === false){
            $row = "<tr data-productid='"+$productid+"' data-itemid='"+$itemid+"' data-type='"+$type+"'>"+
                        "<td style='padding-right: 0px;'><span class='glyphicon glyphicon-remove delete' aria-hidden='true'></span></td>"+
                        "<td><input class='form-control sku' value='"+ $sku +"' readonly></td>"+
                        "<td><input class='form-control description' value='"+ $description +"'></td>"+
                        "<td><input class='form-control units' value='"+ $units +"'></td>"+
                        "<td><input class='form-control unitcost' value='"+ parseFloat($unitcost).toFixed(2) +"'></td>"+
                        "<td><input class='form-control totalpretax' value='' readonly></td>"+
                        "<td><input class='form-control taxpercent' value='" + $taxpercent + "' ></td>"+
                        "<td><input class='form-control tax' value='' readonly></td>"+
                        "<td><input class='form-control citytaxpercent' value='" + $citytaxpercent + "' ></td>"+
                        "<td><input class='form-control citytax' value='' readonly></td>"+
                        "<td><input class='form-control total' value='' readonly></td>"+
                    "</tr>";

            $('#quote-table').append($row);
        }

        TableMath();

        $('#quote-save').prop('disabled', false);
        $('#quote-progress').prop('disabled', false);

        $('.delete').click(function () {
           $row = $(this).parent().parent();
           $row.remove();

           //$('#quote-table tr').length;
        });

        $('.units').keyup(function () {
            TableMath();
        });
        $('.units').focusout(function(){
            if($(this).val() === ""){
                $(this).val(0)
            }
        });
        $('.units').on('keypress', function(e) {
            keys = ['0','1','2','3','4','5','6','7','8','9','.'];
            return keys.indexOf(event.key) > -1;
        });
        $('.unitcost').keyup(function () {
            TableMath();
        });
        $('.unitcost').focusout(function(){
            if($(this).val() === ""){
                $(this).val(0)
            }
        });
        $('.unitcost').on('keypress', function(e) {
            keys = ['0','1','2','3','4','5','6','7','8','9','.'];
            return keys.indexOf(event.key) > -1;
        });
        $('.taxpercent').keyup(function () {
            TableMath();
        });
        $('.taxpercent').focusout(function(){
            if($(this).val() === ""){
                $(this).val(0)
            }
        });
        $('.taxpercent').on('keypress', function(e) {
            keys = ['0','1','2','3','4','5','6','7','8','9','.'];
            return keys.indexOf(event.key) > -1;
        });
        $('.citytaxpercent').keyup(function () {
            TableMath();
        });
        $('.citytaxpercent').focusout(function(){
            if($(this).val() === ""){
                $(this).val(0)
            }
        });
        $('.citytaxpercent').on('keypress', function(e) {
            keys = ['0','1','2','3','4','5','6','7','8','9','.'];
            return keys.indexOf(event.key) > -1;
        });
    }

    function TableMath(){

        //subtotal
        $subtotal = 0;
        $taxtotal = 0;
        $citytax = 0;
        $total = 0;

        $('#quote-table > tbody  > tr').each(function() {
            $units = $(this).find('.units').val();
            $unitcost = $(this).find('.unitcost').val();
            $pretax = $units * $unitcost;
            $(this).find('.totalpretax').val($pretax.toFixed(2));

            $subtotal = $subtotal + $pretax;

            $tax = ($pretax / 100) * $(this).find('.taxpercent').val();

            $citytax = ($pretax / 100) * $(this).find('.citytaxpercent').val();;

            $(this).find('.tax').val($tax.toFixed(2));
            $(this).find('.citytax').val($citytax.toFixed(2));

            $taxtotal = $taxtotal + $tax;

            $rowtotal = $pretax + $tax + $citytax;

            $(this).find('.total').val($rowtotal.toFixed(2));
            $total = $total + $rowtotal ;
        });

        $('#quote-total-subtotal').val("$" + $subtotal.toFixed(2));

        $('#quote-total-citytax').val("$" + $citytax.toFixed(2));

        $('#quote-total-tax').val("$" + $taxtotal.toFixed(2));

        $('#quote-total-total').val("$" + $total.toFixed(2));
    }

    function SaveQuote($progress) {

        $("body").addClass("loading");
        ResetServerValidationErrors();

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['id'] = $('#quote-id').val();
        $data['comment'] = $('#quote-comment').val();
        $data['client_id'] = {{ $sourceclient }};
        $data['contact_id'] = $('#contact-select').val();
        $data['branch_id'] = $('#branch-select').val();
        $data['items'] = TableData();

        $post = $.post("/Quote/Save", $data);

        $post.done(function (data) {
            console.log(data);
            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":
                    $('#quote-id').val(data['Quote']['id']);
                    AssignIDsToRows(data['Quote']['quoteitem']);
                    if($progress){
                        GoToPage('/Quote/Final/' + data['Quote']['id']);
                    }else{
                        $('#ShowPdfModel').modal('show');
                    }
                    break;
                case "notfound":
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Resquotense from server. Please refresh the page and try again.'
                    });
                    break;
                case "validation":
                    ServerValidationErrors(data['errors']);
                    break;
                default:
                    console.log(data);
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Resquotense from server. Please refresh the page and try again.'
                    });
            }
        });

        $post.fail(function () {
            NoReplyFromServer();
        });

    }


    @if(isset($quote))
        function SetupPage() {
            $('#quote-id').val({{ $quote->id }});
            $('#contact-select').val({{ $quote->contact_id }});
            $('#branch-select').val({{ $quote->branch_id }});


            @foreach($quote->items as $item)
            AddRow({{ $item->LinkId() }} , "{{ $item->LinkType() }}", {{ $item->id }}, "{{ $item->SKU }}", "{{ $item->description }}", {{ $item->units }}, {{ $item->costperunit }}, {{ $item->tax }}, {{ $item->citytax }});
            @endforeach

        }
    @else
        function SetupPage() {
        $('#quote-id').val(0);
        $('#client-select').val({{ $sourceclient }});
        $('#quote-products-clients').val({{ $sourceclient }});
        $('#branch-select').val({{ Auth::user()->branch_id }});

    }
    @endif
</script>

@include('OS.Quotes.modals.addproduct')

@stop

