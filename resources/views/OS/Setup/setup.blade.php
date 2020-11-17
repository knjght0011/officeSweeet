@extends('OS.Setup.master')

@section('content')

    <div class="container step">
        <div id="logo-background" style="width: 20%; margin-left: 40%;"><a href="/"><img width="100%" src="/images/oslogo.png"></a></div>

        <h1 style="text-align: center; ">Setup in 3 Steps</h1>
    </div>

    <div class="container step" id="step1">

        <h3>Step 1 of 3</h3>

        <h4>Thank you for choosing Office Sweeet. There are just a few steps to QUICK START your system set up. </h4>

        <h4>For security, please create a new unique password only you can remember. Your password should be at least 8 letters long with at least one special character (!@#$%&).</h4>

        <div class="row" >
            <div class="col-md-offset-3 col-md-6">
                <div class="input-group">
                    <span class="input-group-addon" for="password"><div style="width: 10em;">Change Password:</div></span>
                    <input id="password" name="password" type="password" placeholder="" class="form-control input-md" autocomplete="off" >
                </div>

                <div class="input-group">
                    <span class="input-group-addon" for="confirmpassword"><div style="width: 10em;">Confirm Password:</div></span>
                    <input  id="confirmpassword" name="confirmpassword" type="password" placeholder="" class="form-control" autocomplete="off" >
                </div>
            </div>
        </div>

        <button id="step1next" name="step1next" class="btn OS-Button nextbutton">Next</button>

    </div>


    <div class="container step" id="step2">

        <h3>Step 2 of 3</h3>

        <ul style="font-size: 18px;" type="square">
            <li>Now, please verify your company name.  This is how it will appear on any documents produced by Office Sweeet.</li>
        </ul>

        <div class="row" >
            <div class="col-md-offset-3 col-md-6">
                <div class="input-group">
                    <span class="input-group-addon" for="companyname"><div style="width: 10em;">Company Name:</div></span>
                    <input id="companyname" name="companyname" type="text" placeholder="" class="form-control input-md" autocomplete="off" value="{{ SettingHelper::GetSetting('companyname') }}">
                </div>
            </div>
        </div>


        <ul style="font-size: 18px; padding-top: 15px;" type="square">
            <li>For TYPE OF BUSINESS, first choose the most applicable from the list; if OTHER, simply enter in the space provided. This will help us configure your system.</li>
        </ul>

        <div class="row" >
            <div class="col-md-offset-3 col-md-6">
                <div class="input-group ">
                    <span class="input-group-addon" for="companytype"><div style="width: 10em;">Type of Business:</div></span>
                    <select id="companytype" name="companytype" type="text" placeholder="" class="form-control" data-validation-label="Template" data-validation-required="false" data-validation-type="">
                        <option value="Builder" selected="selected">Builder</option>
                        <option value="Plumber">Plumber</option>
                        <option value="Mover">Mover</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="input-group" style="display: none;" id="companytype-other-div">
                    <span class="input-group-addon" for="companytype-other"><div style="width: 10em;">Other:</div></span>
                    <input id="companytype-other" name="companytype-other" type="text" placeholder="" class="form-control" data-validation-label="Sales Tax Rate" data-validation-required="true" data-validation-type="">
                </div>
            </div>
        </div>

        <ul style="font-size: 18px; padding-top: 15px;" type="square">
            <li>If you know your local sales tax rate (percentage), you can enter it here.</li>
        </ul>

        <div class="row">
            <div class="col-md-offset-3 col-md-6">
                <div class="input-group ">
                    <span class="input-group-addon" for="sales-tax"><div style="width: 10em;">Sales Tax Rate:</div></span>
                    <input id="sales-tax" name="sales-tax" type="number" placeholder="" value="{{ SettingHelper::GetSalesTax() }}" class="form-control" data-validation-label="Sales Tax Rate" data-validation-required="true" data-validation-type="">
                    <span class="input-group-addon">%</span>
                </div>
            </div>
        </div>

        <button id="step2back" name="step2back" class="btn OS-Button backbutton">Back</button>
        <button id="step2next" name="step2next" class="btn OS-Button nextbutton">Next</button>

    </div>

    <div class="container step" id="step3">

        <h3>Step 3 of 3</h3>

        <ul style="font-size: 18px;" type="square">
            <li>Last step; please enter the main location for your business.</li>
            <li>You can add additional branches or locations later in the Admin Control Center.</li>
        </ul>

        <div class="row" >
            <div class="col-md-offset-3 col-md-6">
                {!! Form::OSinput("zip", "Postal Code", "", "", "true", "", "text") !!}
                {!! Form::OSinput("number", "Street Number", "", "", "true", "") !!}
                {!! Form::OSinput("address1", "Street Name", "", "", "true", "") !!}
                {!! Form::OSinput("address2", "Address Line 2", "", "", "true", "") !!}
                {!! Form::OSinput("city", "City", "", "", "true", "") !!}
                {!! Form::OSinput("region", "County", "", "", "true", "") !!}
                {!! Form::OSinput("state", "State/Province", "", "", "true", "") !!}
                {!! Form::OSinput("phonenumber", "Phone Number", "", "", "true", "") !!}
                {!! Form::OSinput("faxnumber", "Fax Number", "", "", "true", "") !!}
            </div>
        </div>

        <button id="step3back" name="step3back" class="btn OS-Button backbutton">Back</button>
        <button id="step3next" name="step3button" class="btn OS-Button nextbutton">Next</button>

    </div>

    <div class="container step" id="step4">

        <h4>Well done! Now that your basic configuration is complete, we suggest that you watch a few short videos to familiarize you with your new system. Click <a href="/VideoPopup">here</a> for a complete list of video tutorials.  We suggest starting with the first two, and then just pick and choose the area you want to delve into next.</h4>
        <h4>We are always here for you, so feel free to send us a quick message within Support / Feedback if you need a little help.</h4>
        <h4>Let’s get started.</h4>

        <button id="step4next" name="step4next" class="btn OS-Button nextbutton">Office Sweeet Home</button>

    </div>

    <script>
        $(document).ready(function() {

            $setupdata = {};
            $setupdata['_token'] = "{{ csrf_token() }}";

            $('#step2').css('display', 'none');
            $('#step3').css('display', 'none');
            $('#step4').css('display', 'none');

            $("#step1next").click(function()
            {

                if ($('#password').val().length >= 8) {
                    if ($('#password').val() === $('#confirmpassword').val()) {

                        $setupdata['password'] = $('#password').val();
                        $setupdata['confirmpassword'] = $('#confirmpassword').val();


                        $('#step1').fadeOut('fast',"linear",function(){
                            $('#step2').fadeIn('fast',"linear");
                        });

                    }else{
                        $.dialog({
                            title: 'Oops...',
                            content: "passwords don't match."
                        });
                    }
                }else{
                    $.dialog({
                        title: 'Oops...',
                        content: 'Password needs to be at least 8 characters long.'
                    });
                }

            });


            $("#step2back").click(function()
            {
                $('#step2').fadeOut('fast',"linear",function(){
                    $('#step1').fadeIn('fast',"linear");
                });
            });

            $("#step2next").click(function()
            {

                $setupdata['companyname'] = $('#companyname').val();
                $setupdata['companytype'] = $('#companytype').val();
                $setupdata['companytype-other'] = $('#companytype-other').val();
                $setupdata['sales-tax'] = $('#sales-tax').val();

                $('#step2').fadeOut('fast',"linear",function(){
                    $('#step3').fadeIn('fast',"linear");
                });
            });

            $("#step3back").click(function()
            {
                $('#step3').fadeOut('fast',"linear",function(){
                    $('#step2').fadeIn('fast',"linear");
                });
            });

            $("#step3next").click(function()
            {
                $setupdata['number'] = $('#number').val();
                $setupdata['address1'] = $('#address1').val();
                $setupdata['address2'] = $('#address2').val();
                $setupdata['city'] = $('#city').val();
                $setupdata['region'] = $('#region').val();
                $setupdata['state'] = $('#state').val();
                $setupdata['zip'] = $('#zip').val();
                $setupdata['phonenumber'] = $('#phonenumber').val();
                $setupdata['faxnumber'] = $('#faxnumber').val();

                console.log($setupdata);
                $("body").addClass("loading");
                ResetServerValidationErrors();

                post = $.post("/Setup", $setupdata);

                post.done(function( data ) {

                    if (data === "success"){

                        var link = document.createElement('a');
                        link.href = '/';
                        link.id = "link";
                        document.body.appendChild(link);
                        link.click();

                        //$('#step3').fadeOut('fast',"linear",function(){
                        //    $('#step4').fadeIn('fast',"linear");
                        //});

                    }else{
                        $("body").removeClass("loading");
                        ServerValidationErrors(data);
                    }
                });

                post.fail(function(data) {
                    console.log(data);
                    $("body").removeClass("loading");
                    $.dialog({
                        title: 'Oops...',
                        content: 'Lost Contact With the Server'
                    });
                });
            });

            $('#step4next').click(function () {

                var link = document.createElement('a');
                link.href = '/';
                link.id = "link";
                document.body.appendChild(link);
                link.click();
            });

            $('#companytype').change(function () {
               if($(this).val() === "Other"){
                   $('#companytype-other-div').css('display' , 'table');
               }else{
                   $('#companytype-other-div').css('display' , 'none');
               }
            });

            $("#branch-lookup").click(function()
            {
                AddressLookup($('#zip').val());
            });

        });

        function ResetServerValidationErrors(){
            $('.invalid').removeClass('invalid');
        }

        function ServerValidationErrors($array) {


            $.each($array, function (index, value) {
                $('#' + index).addClass('invalid');
            });

            $text = "";
            $.each($array, function( index, value ) {
                $text = $text + value + "<br>";
            });
            $.dialog({
                title: 'Oops...',
                content: $text
            });
        }

        function AddressLookup($zip, $done) {

            $("body").addClass("loading");

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['zip'] = $zip;

            $post = $.post("/Address/Lookup", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        PopulateAddressFields(data['data']);
                        if($done != null){
                            $done();
                        }
                        break;
                    case "error":
                        $.dialog({
                            title: 'Oops..',
                            content: data['reason']
                        });
                        break;
                    default:
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                }
            });

            $post.fail(function () {
                NoReplyFromServer();
            });

            function PopulateAddressFields($data){
                $.each($data, function (index, value) {
                    $('#number').prop('disabled', false);
                    switch (index) {
                        case "postal_code":
                            $('#zip').val(value);
                        case "state_province":
                            if (value == "") {
                                $('#state').prop('disabled', false);
                                $('#state').val(value);
                            } else {
                                $('#state').prop('disabled', true);
                                $('#state').val(value);
                            }
                            break;
                        case "region":
                            if (value == "") {
                                $('#region').prop('disabled', false);
                                $('#region').val(value);
                            } else {
                                $('#region').prop('disabled', true);
                                $('#region').val(value);
                            }
                            break;
                        case "city":
                            if (value == "") {
                                $('#city').prop('disabled', false);
                                $('#city').val(value);
                            } else {
                                $('#city').prop('disabled', true);
                                $('#city').val(value);
                            }
                            break;
                        case "address2":
                            if (value == "") {
                                $('#address2').prop('disabled', false);
                                $('#address2').val(value);
                            } else {
                                $('#address2').prop('disabled', true);
                                $('#address2').val(value);
                            }
                            break;
                        case "address1":
                            if (value == "") {
                                $('#address1').prop('disabled', false);
                                $('#address1').val(value);
                            } else {
                                $('#address1').prop('disabled', true);
                                $('#address1').val(value);
                            }
                            break;
                    }
                });
            }
        }
    </script>
@stop