@if(Auth::user()->hasPermission('deposits_permission'))

{{--
Modal for adding payments or adjustments to invoices
required data attributes:
    data-quoteid="" 
    data-clientid=""

include with:
     @include('Modals.addinvoicedeposit') 
--}}


<div class="modal fade" id="AddInvoiceDeposit" tabindex="-1" role="dialog" aria-labelledby="AddInvoiceDeposit" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Add deposit to invoice:</h4>
            </div>
            <div class="modal-body">
                
                <input style="display: none;" type="text" class="form-control" name="deposit-invoice-quoteid" id="deposit-invoice-quoteid" disabled>
                <input style="display: none;" type="text" class="form-control" name="deposit-invoice-clientid" id="deposit-invoice-clientid" disabled>
                
                <div class="input-group">
                    <span class="input-group-addon" for="deposit-invoice-date"><div style="width: 10em;">Date:</div></span>  
                    <input id="deposit-invoice-date" type="text" name="deposit-invoice-date" class="form-control input-md" value="{{ date('Y-m-d') }}" readonly style="z-index: 10000;">
                </div>  
                
                <div class="input-group">
                    <span width="10em" class="input-group-addon" for="deposit-invoice-amount"><div style="width: 10em;">Amount:</div></span>
                    <input id="deposit-invoice-amount" name="deposit-invoice-amount" type="text" class="form-control input-md" value="0.00" data-validation-label="Amount" data-validation-required="true" data-validation-type="amount">
                </div>
                
                <div class="input-group">
                    <span width="10em" class="input-group-addon" for="deposit-invoice-method"><div style="width: 10em;">Method:</div></span>
                    <select type="text" class="form-control input-md" name="deposit-invoice-method" id="deposit-invoice-method">
                        <option value="Cash">Cash</option>
                        <option value="Check">Check</option>
                        <option value="Debit/Credit Card">Debit/Credit Card</option>
                        <option value="ACH/ETF & Wire">ACH/ETF & Wire</option>
                    </select>    
                </div>
                
                <div class="input-group">
                    <span width="10em" class="input-group-addon" for="deposit-invoice-comments"><div style="width: 10em;">Comments:</div></span>  
                    <input id="deposit-invoice-comments" name="deposit-invoice-comments" type="text" class="form-control input-md">
                </div>

                <div class="input-group ">
                    <span class="input-group-addon" for="deposit-invoice-fileupload-file"><div style="width: 10em;">Upload Image:</div></span>
                    <input id="deposit-invoice-fileupload-file" name="deposit-invoice-fileupload-file" type="file" class="form-control">
                </div>

                <div id="deposit-invoice-fileupload-preview-container" style="height: 0px; width: 100%;">
                    <embed style="height: 420px; width: 100%;" id="deposit-invoice-fileupload-preview" ></embed>
                </div>

                <div id="deposit-invoice-cardpayment" style="display: none; ">

                    <legend>Card Details:</legend>
                    <div class="input-group">
                        <span width="10em" class="input-group-addon" for="deposit-invoice-nameoncard"><div style="width: 10em;">Firstname on Card:</div></span>
                        <input id="deposit-invoice-firstnamecard" name="deposit-invoice-firstnamecard" type="text" class="form-control input-md">
                    </div>

                    <div class="input-group">
                        <span width="10em" class="input-group-addon" for="deposit-invoice-nameoncard"><div style="width: 10em;">Lastname on Card:</div></span>
                        <input id="deposit-invoice-lastnamecard" name="deposit-invoice-lastnamecard" type="text" class="form-control input-md">
                    </div>

                    <div class="input-group">
                        <span width="10em" class="input-group-addon" for="deposit-invoice-cardnumber"><div style="width: 10em;">Card Number:</div></span>
                        <input id="deposit-invoice-cardnumber" name="deposit-invoice-cardnumber" type="text" class="form-control input-md">
                    </div>

                    <div class="input-group">
                        <span width="10em" class="input-group-addon" for="deposit-invoice-cc-cardCVC"><div style="width: 10em;">CV Code:</div></span>
                        <input
                                id="deposit-invoice-cc-cardCVC"
                                type="tel"
                                class="form-control"
                                name="cardCVC"
                                placeholder="CVC"
                                autocomplete="cc-csc"
                                required
                        />
                    </div>


                    <div class="input-group">
                        <span width="10em" class="input-group-addon" for="deposit-invoice-cc-cardExpiry-month"><div style="width: 10em;">Expiration Date:</div></span>
                        <select
                                style="width: 49%; display: inline-block;"
                                id="deposit-invoice-cc-cardExpiry-month"
                                class="form-control"
                        >
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">09</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                        <select
                                style="width: 49%; display: inline-block;"
                                id="deposit-invoice-cc-cardExpiry-year"
                                class="form-control"
                        >
                            @foreach(PageElement::Years() as $year)
                                <option value="{{$year}}">{{$year}}</option>
                            @endforeach
                        </select>
                    </div>



                </div>
            </div>
            <div class="modal-footer">
                <button id="deposit-invoice-save" name="save" type="button" class="btn OS-Button">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $("#deposit-invoice-cardnumber").keydown(function(e) {
        if(e.keyCode === 13){
            if($(this).val().length > 16) {
                $("body").addClass("loading");

                $data = {};
                $data['_token'] = "{{ csrf_token() }}";
                $data['swipestring'] = $(this).val();

                posting = $.post("/POS/SwipeDecode", $data);

                posting.done(function (data) {
                    $("body").removeClass("loading");
                    switch (data['status']) {
                        case "OK":
                            $('#deposit-invoice-cardnumber').val(data['swipe']['track1']['CardNumber']);
                            $('#deposit-invoice-cc-cardExpiry-month').val(data['swipe']['track1']['ExpireyMonth']);
                            $('#deposit-invoice-firstnamecard').val(data['swipe']['track1']['FirstName']);
                            $('#deposit-invoice-lastnamecard').val(data['swipe']['track1']['LastName']);
                            $('#deposit-invoice-cc-cardExpiry-year').val("20" + data['swipe']['track1']['ExpireyYear']);
                            break;
                        default:
                            console.log(data);
                            $.dialog({
                                title: 'Oops...',
                                content: 'Unknown Response from server. Please refresh the page and try again.'
                            });
                    }
                });

                posting.fail(function () {
                    NoReplyFromServer();
                });
            }
        }
    });


    $('#AddInvoiceDeposit').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal

        var quoteid = button.data('quoteid'); // Extract info from data-* attributes
        var clientid = button.data('clientid');
        
        var modal = $(this);
        modal.find('.modal-body input[name*="deposit-invoice-quoteid"]').val(quoteid);
        modal.find('.modal-body input[name*="deposit-invoice-clientid"]').val(clientid);
    });

    $('#deposit-invoice-date').datepicker({
        changeMonth: true,
        changeYear: true,
        inline: true,
        dateFormat: "yy-mm-dd",
    });

    @if(SettingHelper::GetSetting('transnational-username') != null)
    $('#deposit-invoice-method').change(function () {
        if($(this).val() === "Debit/Credit Card"){
            $('#deposit-invoice-cardpayment').css('display', 'block');
        }else{
            $('#deposit-invoice-cardpayment').css('display', 'none');
        }
    });
    @endif

    $("#deposit-invoice-save").click(function()
    {

        $("body").addClass("loading");
        ResetServerValidationErrors();

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['date'] = $('#deposit-invoice-date').val();
        $data['client_id'] = $('#deposit-invoice-clientid').val();
        $data['quote_id'] = $('#deposit-invoice-quoteid').val();
        $data['type'] = "";
        $data['method'] = $('#deposit-invoice-method').val();
        $data['comments'] = $('#deposit-invoice-comments').val();
        $data['file'] = $('#deposit-invoice-fileupload-preview').attr('src');
        $data['amount'] = ValidateInput($('#deposit-invoice-amount'));

        @if(SettingHelper::GetSetting('transnational-username') != null)
        if($('#deposit-invoice-method').val() === "Debit/Credit Card"){
            $data['cardNumber'] = $('#deposit-invoice-cardnumber').val();
            $data['cardExpiryMonth'] = $('#deposit-invoice-cc-cardExpiry-month').val();
            $data['cardExpiryYear'] = $('#deposit-invoice-cc-cardExpiry-year').val();
            $data['cardCVC'] = $('#deposit-invoice-cc-cardCVC').val();
            $data['firstname'] = $('#deposit-invoice-firstnamecard').val();
            $data['lastname'] = $('#deposit-invoice-lastnamecard').val();
        }
        @endif

        posting = $.post("/Deposit/Add/Invoice", $data);
        //posting = PostPaymentData($date, $client_id, $quote_id, $amount, $type, $method, $comments, $file);
            
        posting.done(function( data ) {
            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":
                    if($('#deposit-invoice-method').val() === "Debit/Credit Card"){
                        if (data['TNresponse'] === "1") {
                            $.dialog({
                                title: 'Success!',
                                content: '',
                            });
                            GoToPage('/Clients/View/' + $data['client_id'] + '/transactions')
                        } else {
                            $.dialog({
                                title: 'Card Error:',
                                content: data['TNresponsetext'],
                            });
                        }
                    }else{
                        $.dialog({
                            title: 'Success!',
                            content: '',
                        });
                    }
                    break;
                case "notfound":
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Response from server. Please refresh the page and try again.'
                    });
                    break;
                case "monthend":
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Response from server. Please refresh the page and try again.'
                    });
                    break;
                case "amounttohigh":
                    $.dialog({
                        title: 'Oops...',
                        content: 'Invoice balance is less than the amount you have entered.'
                    });
                    break;
                case "validation":
                    ServerValidationErrors(data['errors']);
                    break;
                default:
                    console.log(data);
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Response from server. Please refresh the page and try again.'
                    });
            }
        });
        
        posting.fail(function() {
            $("body").removeClass("loading");
            bootstrap_alert.warning('Failed to post data', 'danger', 4000);
        });
        
    });

    $("#deposit-invoice-fileupload-file").change(function()
    {
        if (this.files[0].name != "") {
            $('#deposit-invoice-fileupload-preview').remove();
            $('#deposit-invoice-fileupload-preview-container').css('height', "420px");
            $('#deposit-invoice-fileupload-preview-container').append('<embed style="height: 420px; width: 100%;" id="deposit-invoice-fileupload-preview" ></embed>');
            DepositInvoiceReadURL(this);
        }
    });
});

var srcContent;
function DepositInvoiceReadURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            srcContent=  e.target.result;
            $('#deposit-invoice-fileupload-preview').attr('src', srcContent);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function PostPaymentData($date, $client_id, $quote_id, $amount, $type, $method, $comments, $file) {

    return $.post("",
    {

    });
}
</script>

@endif