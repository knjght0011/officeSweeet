
<table class="table">
    <tr>
        <td>Prospect:</td>
        <td>{{ $client->getName() }}</td>
    </tr>
    <tr>
        <td>Address:</td>
        <td>{{ $client->address->AddressString() }}</td>
    </tr>
</table>
<legend>System Details:</legend>
<div class="input-group">
    <span width="10em" class="input-group-addon" for="subdomain"><div style="width: 10em;">Subdomain:</div></span>
    <input id="subdomain" name="subdomain" type="text" class="form-control input-md" value="{{ $sub }}">
</div>
<div class="input-group">
    <span width="10em" class="input-group-addon" for="plan"><div style="width: 10em;">Plan:</div></span>
    <select
            id="plan"
            class="form-control"
    >
        <option value="Sept2018_Upto3">Standard 1 (Up to 3 Users @ $47 per month)</option>
        <option value="Sept2018_Upto9">Standard 2 (Up to 9 Users @ $97 per month)</option>
        <option value="Sept2018_Unlimited" selected>Standard 3 (Unlimted Users @ $197 per month)</option>
        @foreach($promotions as $promotion)
            <option value="{{ $promotion->tn_plan_name }}">{{ $promotion->name }} ({{ $promotion->DealSummery() }})</option>
        @endforeach
    </select>
</div>

<legend>Card Details:</legend>
<div class="input-group">
    <span width="10em" class="input-group-addon" for="nameoncard"><div style="width: 10em;">Firstname on Card:</div></span>
    <input id="firstnamecard" name="firstnamecard" type="text" class="form-control input-md">
</div>

<div class="input-group">
    <span width="10em" class="input-group-addon" for="nameoncard"><div style="width: 10em;">Lastname on Card:</div></span>
    <input id="lastnamecard" name="lastnamecard" type="text" class="form-control input-md">
</div>

<div class="input-group">
    <span width="10em" class="input-group-addon" for="cc-cardNumber"><div style="width: 10em;">Card Number:</div></span>
    <input id="cc-cardNumber" name="cc-cardNumber" type="text" class="form-control input-md">
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

<button id="submit" class="OS-Button btn" style="width: 100%;">Submit</button>


<script>
    $(document).ready(function() {
        $("#cc-cardNumber").keydown(function(e) {
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
                                $('#cc-cardNumber').val(data['swipe']['track1']['CardNumber']);
                                $('#cc-cardExpiry-month').val(data['swipe']['track1']['ExpireyMonth']);
                                $('#firstnamecard').val(data['swipe']['track1']['FirstName']);
                                $('#lastnamecard').val(data['swipe']['track1']['LastName']);
                                $('#cc-cardExpiry-year').val("20" + data['swipe']['track1']['ExpireyYear']);
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

        $('#subdomain').focusout(function () {

            $("body").addClass("loading");

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['subdomain'] = $('#subdomain').val();

            $post = $.post("/Signup/SubdomainCheck", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        $('#subdomain').val(data['subdomain']);
                        break;
                    default:
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


        $('#submit').click(function () {


            $("body").addClass("loading");
            ResetServerValidationErrors();

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['clientid'] = "{{ $client->id }}";
            $data['_token'] = "{{ csrf_token() }}";

            $data['cardNumber'] = $('#cc-cardNumber').val();
            $data['cardExpiryMonth'] = $('#cc-cardExpiry-month').val();
            $data['cardExpiryYear'] = $('#cc-cardExpiry-year').val();
            $data['cardCVC'] = $('#cc-cardCVC').val();
            $data['firstname'] = $('#firstnamecard').val();
            $data['lastname'] = $('#lastnamecard').val();

            $data['plan_name'] = $('#plan').val();
            $data['subdomain'] = $('#subdomain').val();

            $post = $.post("/LLSNEWSignup/Subscribe", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        $.dialog({
                            title: 'Success',
                            content: "Subscription setup, system building",
                        });
                        GoToPage('/Clients/View/{{ $client->id }}');
                        break;
                    case "Failed":
                        $.dialog({
                            title: 'Oops...',
                            content: data['responsetext']
                        });
                    case "SubdomainTaken":
                        $.dialog({
                            title: 'Oops...',
                            content: 'That subdomain is allready taken, please try another one.'
                        });
                    case "UnRecognisedPlan":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Plan not found. Please refresh the page and try again.'
                        });
                        break;
                    case "notfound":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
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

            $post.fail(function () {
                NoReplyFromServer();
            });

        });

    });
</script>