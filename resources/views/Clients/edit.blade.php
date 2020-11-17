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
        <button style="width: 100%;" id="backbutton" name="backbutton" type="button" class="btn OS-Button">Back to Client</button>
    </div>

    @desktop
    <div style="float:left; width: 20em;  margin-left: 20px;">
        <label class="col-md-4 control-label tablabel" for="inactive" style="padding-top: 10px">Inactive:</label>
        <div class="col-md-5 " style="padding-top: 10px">
            <input type="checkbox" name="inactive" id="inactive">
        </div>
    </div>
    @elsedesktop
    <div class="col-md-12">
        <label class="control-label tablabel" for="inactive" style="padding-top: 10px">Inactive:</label>
        <input type="checkbox" name="inactive" id="inactive">
    </div>
    @enddesktop
</div> 

<div id="editpane" class="row">
    <div class="col-md-6">

        <legend>Name</legend>
        <div class="input-group"> 
            <span class="input-group-addon" for="firstname"><div class="inputdiv">Company Name (optional):</div></span>
            <input id="name" name="name" type="text" value="" class="form-control">

            @if(app()->make('account')->plan_name != "SOLO")
            <span class="input-group-addon">Existing {{ TextHelper::GetText("Client") }}
                <input id="existingclient" type="checkbox" name="existingclient" aria-label="Existing Client" value="1">
            </span>
            @endif
        </div>
        
        <div class="input-group">
            <span class="input-group-addon" for="firstname"><div class="inputdiv">Primary Contact:</div></span>
            <input id="primarycontact" type="text" class="form-control" disabled>
            <span class="input-group-btn">
                <button id="gotoprimarycontact" name="gotoprimarycontact" type="button" class="btn btn-default">Edit</button>
            </span>
        </div>

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

        <legend>Address</legend>

        <input type="checkbox" name="checkboxes" id="enable_address" data-on="Include Address" data-off="No Address" data-toggle="toggle" data-width="100%"
               @if($client->address_id != null)
               checked
               @endif
        >

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
            <div class="input-group">
                <span class="input-group-addon" for="number"><div class="inputdiv">House Name\Number:*</div></span>
                <input id="number" name="number" type="text" placeholder="" class="form-control" required="" disabled>
            </div>

            <div class="input-group">
                <span class="input-group-addon" for="address1"><div class="inputdiv">Street:*</div></span>
                <input id="address1" name="address1" type="text" placeholder="Address Line 1" class="form-control" required="" disabled>
            </div>

            <!-- Text input-->
            <div class="input-group">
                <span class="input-group-addon" for="address2"><div class="inputdiv">Address Line 2:</div></span>
                <input id="address2" name="address2" type="text" placeholder="Address Line 2" class="form-control" disabled>
            </div>

            <!-- Text input-->
            <div class="input-group">
                <span class="input-group-addon" for="city"><div class="inputdiv">City:*</div></span>
                <input id="city" name="city" type="text" placeholder="City" class="form-control" required="" disabled>
            </div>

            <div class="input-group">
                <span class="input-group-addon" for="region"><div class="inputdiv">Region:*</div></span>
                <input id="region" name="region" type="text" placeholder="Region" class="form-control" required="" disabled>
            </div>

            <!-- Text input-->
            <div class="input-group">
                <span class="input-group-addon" for="state"><div class="inputdiv">State/Province:*</div></span>
                <input id="state" name="state" type="text" placeholder="State" class="form-control" required="" disabled>
            </div>

            <!-- Text input-->
            <div class="input-group">
                <span class="input-group-addon" for="zip"><div class="inputdiv">Postal Code:*</div></span>
                <input id="zip" name="zip" type="text" placeholder="Zip" class="form-control" required="" >
                <span class="input-group-btn">
                    <button id="lookup" name="lookup" type="button" class="btn btn-default" >Lookup Address</button>
                </span>
            </div>
        </div>
    </div>     
</div>

@include('Clients.view.modals.primarycontact')



<script>    
$(document).ready(function() {

    AddPopup($('#existingclient'), "left", "Toggle this on if you want this entry to show up as a Client rather than a Prospect");
    
    @if($client->name != null)
        $('#name').val("{{ $client->name }}");
    @endif    
    $('#number').val("{{ $client->address->number }}");
    $('#address1').val("{{ $client->address->address1 }}");
    $('#address2').val("{{ $client->address->address2 }}");
    $('#city').val("{{ $client->address->city }}");
    $('#region').val("{{ $client->address->region }}");
    $('#state').val("{{ $client->address->state }}");
    $('#zip').val("{{ $client->address->zip }}");
    
    @if( $client->deleted_at == null)
        $('#inactive').prop('checked', false);
        $('#save').prop('disabled', false);
        $('#backbutton').html("Back to {{ TextHelper::GetText('Client') }}");
    @else
        $('#inactive').prop('checked', true);
        $('#save').prop('disabled', true);
        $('#backbutton').html("Back to Client Search");
        $('#editpane').addClass("disabledbutton");
    @endif
    
    @if( $client->existingclient === 1)
        $('#existingclient').prop('checked', true);
    @endif

    $id = "{{ $client->id }}";

    $('#category').val("{{ $client->category }}");

    $('#enable_address').change(function () {
        if($(this).prop('checked') === true){
            $('#address_contaner').css('display', 'block');
        }else{
            $('#address_contaner').css('display', 'none');
        }
    });
    @if($client->address_id === null)
    $('#address_contaner').css('display', 'none');
    @endif
    
    $('#backbutton').click(function(e) {
        if($('#inactive').prop('checked') == false){
            GoToPage("/Clients/View/" + $id);
        }else{
            GoToPage("/");
        }
    });
    
    @if(is_null($client->primarycontact_id))
        $('#primarycontact').val("No Primary Contact Set.");
        
        @if(count($client->contacts) === 0)
            $('#gotoprimarycontact').click(function(e) {
                $.confirm({
                    title: 'This client has no contacts accociated with it, would you like to make one now?',
                    buttons: {
                        confirm: function() {
                            GoToPage("/Clients/Contact/New/{{ $client->id }}")
                        },
                        cancel: function() {
                            
                        }
                    }
                });
            });
        @else
            $('#gotoprimarycontact').click(function(e) {
                $('#primarycontact-modal').modal('show');
            });
        @endif
    @else
    $('#primarycontact').val("{{ $client->getPrimaryContactName() }}");
    
    $('#gotoprimarycontact').click(function(e) {
        GoToPage("/Clients/Contact/{{ $client->primarycontact->id }}")
    });
    @endif


    
    $('#inactive').change(function(){
        if($("#inactive").is(":checked")) {
            $.confirm({
                title: "Are you sure you want to disable this client?",
                buttons: {
                    confirm: function() {
                        $("body").addClass("loading");
                        posting = $.post("/Clients/Disable",
                        {
                            _token: "{{ csrf_token() }}",
                            ClientID: {{ $client->id }},
                            Action: "disable"
                        });

                        posting.done(function( data ) {
                            $("body").removeClass("loading");
                            bootstrap_alert.warning("Client Inactive", 'success', 4000);
                            $('#save').prop('disabled', true);
                            $('#backbutton').html("Back to Client Search");
                            $('#editpane').addClass("disabledbutton");
                        });

                        posting.fail(function() {
                            $("body").removeClass("loading");
                            bootstrap_alert.warning("Failed to post", 'danger', 4000);
                            $('#inactive').prop('checked', false);
                        });
                    },
                    cancel: function() {
                        $('#inactive').prop('checked', false);
                    }
                }
            });            
        } else {
            $.confirm({
                title: "Are you sure you want to enable this client?",
                buttons: {
                    confirm: function() {
                        $("body").addClass("loading");
                        posting = $.post("/Clients/Disable",
                        {
                            _token: "{{ csrf_token() }}",
                            ClientID: {{ $client->id }},
                            Action: "enable"
                        });

                        posting.done(function( data ) {
                            $("body").removeClass("loading");
                            bootstrap_alert.warning("Client Enabled", 'success', 4000);
                            $('#backbutton').html("Back to Client");
                            $('#save').prop('disabled', false);
                            $('#editpane').removeClass("disabledbutton");
                        });

                        posting.fail(function() {
                            $("body").removeClass("loading");
                            bootstrap_alert.warning("Failed to post", 'danger', 4000);
                            $('#inactive').prop('checked', true);
                        });
                    },
                    cancel: function() {
                        $('#inactive').prop('checked', true);
                    }
                }
            });  
        }
    });
    
    $("#save").click(function()
    {
        $("body").addClass("loading");
        ResetServerValidationErrors();

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['id'] = $id;
        $data['name']  = $('#name').val();
        $data['category'] = $('#category').val();

        if ( $('#existingclient').is(':checked') ){
            $data['existingclient'] = 1;
        }else{
            $data['existingclient'] = 0;
        }

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

        clientpost = $.post("/Clients/Save", $data);

        clientpost.done(function( data )
        {
            if ($.isNumeric(data))
            {
                $("body").removeClass("loading");
                var link = document.createElement('a');
                link.href = "/Clients/View/" + $id;
                link.id = "link";
                document.body.appendChild(link);
                link.click();
            }else{
                //server validation errors
                $("body").removeClass("loading");
                ServerValidationErrors(data);
            }
        });

        clientpost.fail(function() {
            $("body").removeClass("loading");
            alert( "Failed to post cleint details" );
            bootstrap_alert.warning(value, 'danger', 4000);
        });

    });


    $("#lookup").click(function () {
        $done = function(){
            $('#save').prop('disabled', false);
        };

        AddressLookup($('#zip').val());
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