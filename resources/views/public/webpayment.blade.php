@include('header')
<body >
@desktop
<div class="container">
    <div class="row">
        <div class="Absolute-Center is-Responsive">
            <div style="margin: auto; margin-bottom: 10px;"><img width="100%" src="{{ \App\Helpers\OS\FileStoreHelper::CompanyLogoWithRedundancy() }}"></div>
            <div id="login-companyname">{{ $companyname }}</div>
            <div class="modal-body">
                <div class="input-group">
                    <span width="10em" class="input-group-addon" for="cc-PaymentAmount"><div style="width: 10em;">Payment Amount | $</div></span>
                    <input id="cc-PaymentAmount" name="cc-PaymentAmount" type="number" min="0.01" step="0.01" max="2500" value="10.00" class="form-control input-md">
                </div>
                <Br/>
                <div class="input-group">
                    <span width="10em" class="input-group-addon" for="cc-firstnameoncard"><div style="width: 10em;">Firstname on Card:</div></span>
                    <input id="cc-firstnameoncard" name="cc-firstnameoncard" type="text" class="form-control input-md">
                </div>

                <div class="input-group">
                    <span width="10em" class="input-group-addon" for="cc-lastnameoncard"><div style="width: 10em;">Lastname on Card:</div></span>
                    <input id="cc-lastnameoncard" name="cc-lastnameoncard" type="text" class="form-control input-md">
                </div>

                <div class="input-group">
                    <span width="10em" class="input-group-addon" for="cc-cardnumber"><div style="width: 10em;">Card Number:</div></span>
                    <input id="cc-cardnumber" name="cc-cardnumber" type="text" class="form-control input-md">
                </div>


                <div class="input-group">
                    <span width="10em" class="input-group-addon" for="cc-cardCVC"><div style="width: 10em;">CV Code:</div></span>
                    <input
                            id="cc-cardCVC"
                            type="tel"
                            class="form-control"
                            name="cardCVC"
                            placeholder="CVC"
                            autocomplete="cc-csc"
                            required
                    />
                </div>


                <div class="input-group">
                    <span width="10em" class="input-group-addon" for="cc-cardExpiry-month"><div style="width: 10em;">Expiration Date:</div></span>
                    <select
                            style="width: 49%; display: inline-block;"
                            id="cc-cardExpiry-month"
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
                            id="cc-cardExpiry-year"
                            class="form-control"
                    >
                        @foreach(PageElement::Years() as $year)
                            <option value="{{$year}}">{{$year}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div style="text-align: center">
                Powered by Office<font color="red">Sweeet</font>
            </div>
            <div class="modal-footer">
                <button id="CC-Process-Payment" type="button" class="btn btn-primary">Send Payment</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#CC-Process-Payment').click(function () {
            $data = {};
            $data['mode'] = "manual";
            $data['_token'] = "{{ csrf_token() }}";

            $data['method'] = "Card";
            $data['depositcomments'] = "";
            $data['quotecomments'] = "";


            $data['amount'] = $('#pos-total-total').val();

            $data['cardNumber'] = $('#cc-cardnumber').val();
            $data['cardExpiryMonth'] = $('#cc-cardExpiry-month').val();
            $year = $('#cc-cardExpiry-year').val();
            $data['cardExpiryYear'] = $year.slice(-2);
            $data['cardCVC'] = $('#cc-cardCVC').val();
            $data['firstname'] = $('#cc-firstnameoncard').val();
            $data['lastname'] = $('#cc-lastnameoncard').val();

            console.log($data);
            CardPayment( $data );
        });


        function CardPayment($data){
            $("body").addClass("loading");
            $post = $.post("/WebPayment/Send", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                console.log(data);
                switch(data['status']) {
                    case "OK":
                        if(data['TNresponse'] === "1"){
                            $.dialog({
                                title: 'Payment Complete',
                                content: 'responcecode: ' + data['TNresponse'] + ' responcetext: ' + data['TNresponsetext'],
                            });
                        }else{
                            $.dialog({
                                title: 'Oops...',
                                content: 'responcecode: ' + data['TNresponse'] + ' responcetext: ' + data['TNresponsetext'],
                            });
                        }
                        break;
                    case "notfound":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                        break;
                    case "cardinvalid":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Card Details invalid. Reason: ' + data['reason'],
                        });
                        break;
                    case "notninfo":
                        $.dialog({
                            title: 'Oops...',
                            content: 'TN not set up, Please setup Transnational Login Info in ACP.'
                        });
                        break;
                    default:
                        console.log(data);
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again. ' + data['status']
                        });
                }
            });

            $post.fail(function () {
                NoReplyFromServer();
            });
        }
    });
</script>
@enddesktop
@include('footer')