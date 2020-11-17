@extends('master')

@section('content')

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA5KSV5I5BXr1WlNDVmh0uv6awtHkTbSsQ&libraries=places"></script>
<div class="row">
    @desktop
    <div style="float:left; width: 10em;  margin-left: 20px;">
    @elsedesktop
    <div class="col-md-12">
    @enddesktop
        <button style="width: 100%;" id="save" name="save" type="button" class="btn OS-Button">Save</button>
    </div>
    @desktop
    <div style="float:left; width: 15em;  margin-left: 20px;">
    @elsedesktop
    <div class="col-md-12">
    @enddesktop

        <button style="width: 100%;" id="backbutton" name="backbutton" type="button" class="btn OS-Button">Back to Contact Home</button>
    </div>

    @if(app()->make('account')->subdomain === "lls" or app()->make('account')->subdomain === "local" )
    @desktop
    <div style="float:left; width: 15em;  margin-left: 20px;">
    @elsedesktop
    <div class="col-md-12">
    @enddesktop
        <input class="form-control" id="broker-check" type="checkbox" name="broker-check" aria-label="Exstiing Client" data-on="Broker" data-off="Normal" data-toggle="toggle" data-width="100%">
    </div>
    @endif
</div>

<div class="row">

    <legend style="padding-left: 15px;">New Client</legend>

    <div class="col-md-6">
        <div class="input-group" >
            <span class="input-group-addon" for="name"><div class="inputdiv">Company Name (optional):</div></span>
            <input id="name" name="name" type="text" placeholder="Name" class="form-control">
        </div>
    </div>

    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-addon" for="category"><div class="inputdiv">Category:</div></span>
            <input id="category" type="text" name="category" list="category-list" class="form-control">
            <datalist  id="category-list" name="category-list">
                @foreach(\App\Helpers\OS\Client\ClientHelper::AllCategorys() as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </datalist>
        </div>
    </div>

    <div class="col-md-6">
        <div class="input-group">
            <span class="input-group-addon"><div class="inputdiv">Existing Client</div></span>
            <input class="form-control" id="existingclient" type="checkbox" name="existingclient" aria-label="Existing Client" data-on="Yes" data-off="No" data-toggle="toggle" data-width="100%">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
    
        <legend>Primary Contact</legend>

        <div class="input-group ">
            <span class="input-group-addon" for="firstname"><div  class="inputdiv">First Name:*</div></span>
            <input id="firstname" name="firstname" type="text" value="" class="form-control" required="">
        </div>

        <div class="input-group ">
            <span class="input-group-addon" for="middlename"><div class="inputdiv">Middle Name:</div></span>
            <input id="middlename" name="middlename" type="text" value="" class="form-control">
        </div>

        <div class="input-group ">
            <span class="input-group-addon" for="lastname"><div class="inputdiv">Last Name:*</div></span>
            <input id="lastname" name="lastname" type="text" value="" class="form-control" required="">
        </div>

        <div class="input-group ">
            <span class="input-group-addon" for="ssn"><div class="inputdiv">SSN:</div></span>
            <input id="ssn" name="ssn" type="text" value="" class="form-control">
        </div>

        <div class="input-group ">
           <span class="input-group-addon" for="driverslicense"><div class="inputdiv">Drivers License No.:</div></span>
           <input id="driverslicense" name="driverslicense" type="text" value="" class="form-control">
        </div>

        <div class="input-group ">
            <span class="input-group-addon" for="email"><div class="inputdiv">E-Mail:*</div></span>
            <input id="email" name="email" type="text" value="" class="form-control" >
        </div>


        <div class="input-group ">
            <span class="input-group-addon" for="contacttype"><div class="inputdiv">Business Role:</div></span>
            <input id="contacttype" name="contacttype" type="text" value="" class="form-control">
        </div>

        <div class="input-group ">
            <span class="input-group-addon" for="officenumber"><div class="inputdiv">Office Phone Number:</div></span>
            <input id="officenumber" name="officenumber" type="text" value="" class="form-control" >
            <span class="input-group-addon">
                <input type="radio" name="primaryphonenumber" aria-label="primary phone number" value="1" checked>
            </span>
        </div>

        <div class="input-group ">
            <span class="input-group-addon" for="mobilenumber"><div class="inputdiv">Mobile Phone Number:</div></span>
            <input id="mobilenumber" name="mobilenumber" type="text" value="" class="form-control" >
            <span class="input-group-addon">
                <input type="radio" name="primaryphonenumber" aria-label="primary phone number" value="2">
            </span>
        </div>

        <div class="input-group ">
            <span class="input-group-addon" for="homenumber"><div class="inputdiv">Home Phone Number:</div></span>
            <input id="homenumber" name="homenumber" type="text" value="" class="form-control" >
            <span class="input-group-addon">
                <input type="radio" name="primaryphonenumber" aria-label="primary phone number" value="3">
            </span>
        </div>
    </div>

    <div class="col-md-6">

        <legend>Address</legend>

        <input type="checkbox" name="checkboxes" id="enable_address" data-on="Include Address" data-off="No Address" data-toggle="toggle" data-width="100%" checked>

        <div id="address_contaner">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-8 col-sm-12 col-12 m-auto">
                        <div class="card shadow">
                            <div class="card-header bg-primary">
                                <h5 class="card-title text-white">Address Search</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <input type="text" name="autocomplete" id="autocomplete" class="form-control" placeholder="Select Location">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="input-group ">
                <span class="input-group-addon" for="address1"><div class="inputdiv">House Name\Number:</div></span>
                <input id="number" name="number" type="text" placeholder="" class="form-control" required="" disabled>
            </div>

            <div class="input-group ">
                <span class="input-group-addon" for="address1"><div class="inputdiv">Street:</div></span>
                <input id="address1" name="address1" type="text" placeholder="Address Line 1" class="form-control" required="" disabled>
            </div>

            <!-- Text input-->
            <div class="input-group ">
                <span class="input-group-addon" for="address2"><div class="inputdiv">Address Line 2:</div></span>
                <input id="address2" name="address2" type="text" placeholder="Address Line 2" class="form-control" disabled>
            </div>

            <!-- Text input-->
            <div class="input-group ">
                <span class="input-group-addon" for="city"><div class="inputdiv">City:</div></span>
                <input id="city" name="city" type="text" placeholder="City" class="form-control" required="" disabled>
            </div>

            <div class="input-group ">
                <span class="input-group-addon" for="region"><div class="inputdiv">Region:</div></span>
                <input id="region" name="region" type="text" placeholder="Region" class="form-control" required="" disabled>
            </div>

            <!-- Text input-->
            <div class="input-group ">
                <span class="input-group-addon" for="state"><div class="inputdiv">State/Province:</div></span>
                <input id="state" name="state" type="text" placeholder="State" class="form-control" required="" disabled>
            </div>

            <!-- Text input-->
            <div class="input-group ">
                <span class="input-group-addon" for="zip"><div class="inputdiv">Postal Code:</div></span>
                <input id="zip" name="zip" type="text" placeholder="Zip" class="form-control" required="">
                <span class="input-group-btn">
                    <button id="lookup" name="lookup" type="button" class="btn btn-default">Lookup Address</button>
                </span>
            </div>
        </div>
    </div>
</div>
<script>    
$(document).ready(function() {

    AddPopup($('#name'), "left", "Popup Test");
    
    AddPopup($('#existingclient'), "left", "Toggle this on if you want this entry to show up as a Client rather than a Prospect");

    $('#enable_address').change(function () {
        if($(this).prop('checked') === true){
            $('#address_contaner').css('display', 'block');
        }else{
            $('#address_contaner').css('display', 'none');
        }
    });
    
    $('#backbutton').click(function(e) {
        var link = document.createElement('a');
        link.href = "/";
        link.id = "link";
        document.body.appendChild(link);
        link.click(); 
    });

    $("#lookup").click(function () {
        $done = function(){
            $('#save').prop('disabled', false);
        };

        AddressLookup($('#zip').val());
    });

    $("#save").click(function()
    {

        ResetServerValidationErrors();

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";

        $data['name']  = $('#name').val();
        $data['category'] =$('#category').val();

        if($('#enable_address').prop('checked') === true){
            $data['number'] = $('#number').val();
            $data['address1'] = $('#address1').val();
            $data['address2'] = $('#address2').val();
            $data['city'] = $('#city').val();
            $data['region'] = $('#region').val();
            $data['state'] = $('#state').val();
            $data['zip'] = $('#zip').val();
        }else{
            $data['address1'] = "NOADDRESS";
        }

        $data['firstname'] = $('#firstname').val();
        $data['middlename'] = $('#middlename').val();
        $data['lastname'] = $('#lastname').val();
        $data['ssn'] = $('#ssn').val();
        $data['driverslicense'] = $('#driverslicense').val();
        $data['email'] = $('#email').val();
        $data['contacttype'] = $('#contacttype').val();
        $data['officenumber'] = $('#officenumber').val();
        $data['mobilenumber'] = $('#mobilenumber').val();
        $data['homenumber'] = $('#homenumber').val();
        $data['primaryphonenumber'] = $('input[name=primaryphonenumber]:checked').val();
            
        if ( $('#existingclient').is(':checked') ){
            $data['existingclient'] = 1;
        }else{
            $data['existingclient'] = 0;
        }

        @if(app()->make('account')->subdomain === "lls" or app()->make('account')->subdomain === "local" )
        if ( $('#broker-check').is(':checked') ){
            $data['broker'] = 1;
        }else{
            $data['broker'] = 0;
        }
        @endif

        $("body").addClass("loading");

        postclient = $.post("/Clients/AddClient", $data);

        postclient.done(function( data )
        {
            console.log(data);
            switch(data['status']) {
                case "OK":
                    GoToPage("/Clients/View/" + data['clientid']);
                    break;
                case "validation":
                    $("body").removeClass("loading");
                    ServerValidationErrors(data['errors']);
                    break;
                case "clientname":
                    $("body").removeClass("loading");
                    $.dialog({
                        title: 'Opps...',
                        content: 'Company Name required when creating a broker.'
                    });
                    break;
                case "subdomainerror":
                    $("body").removeClass("loading");
                    $.dialog({
                        title: 'Opps...',
                        content: 'Subdomain allready taken.'
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

        postclient.fail(function() {
            console.log("Client Post Fail");
            $("body").removeClass("loading");
            $.dialog({
                title: 'Error!',
                content: "Failed to post client details"
            });
        });

    });
});

</script>
            <script>
                google.maps.event.addDomListener(window, 'load', initialize);

                function initialize() {
                    var options = {
                        componentRestrictions: {country: "US"}
                    };
                    var input = document.getElementById('autocomplete');
                    autocomplete = new google.maps.places.Autocomplete(input);

                    autocomplete.addListener('place_changed', function() {
                        var place = autocomplete.getPlace();

                        var componentForm = {
                            street_number: 'short_name',
                            route: 'long_name',
                            locality: 'long_name',
                            administrative_area_level_1: 'short_name',
                            postal_code: 'short_name'
                        };

                        document.getElementById('number').disabled = false;
                        document.getElementById('address1').disabled = false;
                        document.getElementById('address2').disabled = false;
                        document.getElementById('city').disabled = false;
                        document.getElementById('state').disabled = false;
                        document.getElementById('zip').disabled = false;
                        document.getElementById('region').disabled = false;

                        for (var i = 0; i < place.address_components.length; i++) {
                            var addressType = place.address_components[i].types[0];

                            if (addressType == 'street_number') {
                                var streetNumber = place.address_components[i][componentForm[addressType]];
                                document.getElementById('number').value = streetNumber;
                            }

                            if (addressType == 'route') {
                                var route = place.address_components[i][componentForm[addressType]];
                                document.getElementById('address1').value = route;
                            }

                            if (addressType == 'locality') {
                                var locality = place.address_components[i][componentForm[addressType]];
                                document.getElementById('city').value = locality;
                            }

                            if (addressType == 'administrative_area_level_1') {
                                var state = place.address_components[i][componentForm[addressType]];
                                document.getElementById('state').value = state;
                            }

                            if (addressType == 'postal_code') {
                                var zipcode = place.address_components[i][componentForm[addressType]];
                                document.getElementById('zip').value = zipcode;
                            }
                        }
                    });
                }

            </script>
@stop