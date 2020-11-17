@extends('master')

@section('content')
<div class="row">
    <div style="float:left; width: 10em;  margin-left: 20px;">
        <button style="width: 100%;" id="save" name="save" type="button" class="btn OS-Button">Save</button>
    </div>
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button style="width: 100%;" id="backbutton" name="backbutton" type="button" class="btn OS-Button">Back to {{ TextHelper::GetText("Client") }}</button>
    </div>

    @if(isset($contact))
    <div style="float:left; width: 20em;  margin-left: 20px;">
        <label class="col-md-4 control-label tablabel" for="inactive" style="padding-top: 10px">Inactive:</label>  
        <div class="col-md-5 " style="padding-top: 10px">
            <input type="checkbox" name="inactive" id="inactive">
        </div>
    </div>
    @endif
</div> 

<legend>Contact</legend>

<div id="editpane" class="row">
    <div class="col-md-12">

        <legend>Name</legend>
        <div class="input-group"> 
            <span class="input-group-addon" for="firstname"><div style="width: 10em;">First Name*</div></span>
            <input id="firstname" name="firstname" type="text" value="" class="form-control" required="">
                
            <span class="input-group-addon" for="middlename"><div style="width: 10em;">Middle Name</div></span>
            <input id="middlename" name="middlename" type="text" value="" class="form-control">
                
            <span class="input-group-addon" for="lastname"><div style="width: 10em;">Last Name*</div></span>
            <input id="lastname" name="lastname" type="text" value="" class="form-control" required="">
        </div>

    </div>


    <div class="col-md-6">

        <legend>Details</legend>       

        <div class="input-group">   
            <span class="input-group-addon" for="ssn"><div style="width: 15em;">SSN</div></span>
            <input id="ssn" name="ssn" type="text" value="" class="form-control">
        </div>

        <div class="input-group">    
           <span class="input-group-addon" for="driverslicense"><div style="width: 15em;">Drivers License No.</div></span>
           <input id="driverslicense" name="driverslicense" type="text" value="" class="form-control">
        </div>

        <div class="input-group">   
            <span class="input-group-addon" for="contactemail"><div style="width: 15em;">E-Mail*</div></span>
            <input id="contactemail" name="contactemail" type="text" value="" class="form-control" required="">
        </div>


        <div class="input-group">
            <span class="input-group-addon" for="contacttype"><div style="width: 15em;">Business Role:</div></span>
            <input id="contacttype" name="contacttype" type="text" value="" class="form-control">
        </div>

        <div class="input-group">
            <span class="input-group-addon" for="officephone"><div style="width: 15em;">Office Phone Number:</div></span>
            <input id="officephone" name="officephone" type="text" value="" class="form-control">
            <span class="input-group-addon">
                <input type="radio" name="primaryphonenumber" aria-label="primary phone number" value="1" checked>
            </span>
        </div>

        <div class="input-group">
            <span class="input-group-addon" for="mobilephone"><div style="width: 15em;">Mobile Phone Number:</div></span>
            <input id="mobilephone" name="mobilephone" type="text" value="" class="form-control">
            <span class="input-group-addon">
                <input type="radio" name="primaryphonenumber" aria-label="primary phone number" value="2">
            </span>
        </div>

        <div class="input-group">
            <span class="input-group-addon" for="homephone"><div style="width: 15em;">Home Phone Number:</div></span>
            <input id="homephone" name="homephone" type="text" value="" class="form-control">
            <span class="input-group-addon">
                <input type="radio" name="primaryphonenumber" aria-label="primary phone number" value="3">
            </span>
        </div>
    </div>

    <div class="col-md-6">

        <legend>Address</legend>

        <div class="col-md-12" id="address-display" style="font-size: 16px; font-weight: bold;">

        </div>

        <div class="col-md-4">
            <div class="radio">
                <label><input type="radio" id="address-client" name="address-select" value="1">Same as Company</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="radio">
                <label><input type="radio" id="address-existing" name="address-select" data-toggle="modal" data-target="#address-existing-modal" value="2">Previously Saved Address</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="radio">
                <label><input type="radio" id="address-new" name="address-select" data-toggle="modal" data-target="#address-new-modal" value="3">New Address</label>
            </div>
        </div>

        <input id="address_id" style="display: none;" readonly>
    </div>     
</div>

@include('Clients.view.modals.existingaddress')
@include('Clients.view.modals.newaddress')

<script>    
$(document).ready(function() {
    @if(isset($contact))
        $('#firstname').val("{{ $contact->firstname }}");
        $('#middlename').val("{{ $contact->middlename }}");
        $('#lastname').val("{{ $contact->lastname }}");
        
        $('#ssn').val("{{ $contact->ssn }}");
        $('#driverslicense').val("{{ $contact->driverslicencse }}");
        $('#contactemail').val("{{ $contact->email }}");
        $('#contacttype').val("{{ $contact->contacttype }}");
        
        $('#officephone').val("{{ $contact->officenumber }}");
        $('#mobilephone').val("{{ $contact->mobilenumber }}");
        $('#homephone').val("{{ $contact->homenumber }}");

        @if($contact->address_id === null)
            $('#address_id').val("null");
            $('#address-client').attr('checked', true);
        @else
            $('#address_id').val("{{ $contact->address_id }}");
            $('#address-existing').attr('checked', true);
        @endif

        $('#address-display').html("{{ $contact->address->AddressString() }}");

        $('input:radio[name="primaryphonenumber"]').filter('[value="{{ $contact->primaryphonenumber }}"]').attr('checked', true);

        $client_id = {{ $contact->client_id }};
        $id = {{ $contact->id }};
        
        @if( $contact->deleted_at == null)
            $('#inactive').prop('checked', false);
            $('#save').prop('disabled', false);
        @else
            $('#inactive').prop('checked', true);
            $('#save').prop('disabled', true);
            $('#editpane').addClass("disabledbutton");
        @endif
        
    @else
        $id = 0;
        $client_id = {{ $client_id }};

        $('#address_id').val("null");
        $('#address-display').html("{{ $client->address->AddressString() }}");
        $('#address-client').attr('checked', true);

    @endif

    $('#address-client').click(function(e) {
        UpdateAddress("null", "{{ $client->address->AddressString() }}");
    });


    $('#backbutton').click(function(e) {
        var link = document.createElement('a');
        link.href = "/Clients/View/" + $client_id;
        link.id = "link";
        document.body.appendChild(link);
        link.click(); 
    });
    
    $('#inactive').change(function(){
        if($("#inactive").is(":checked")) {
            $.confirm({
                title: "Are you sure you want to disable this Contact?",
                buttons: {
                    confirm: function() {
                        $("body").addClass("loading");
                        posting = $.post("/Clients/Contact/Disable",
                        {
                            _token: "{{ csrf_token() }}",
                            ContactID: $id,
                            Action: "disable"
                        });

                        posting.done(function( data ) {
                            $("body").removeClass("loading");
                            bootstrap_alert.warning("Contact Inactive", 'success', 4000);
                            $('#save').prop('disabled', true);
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
                title: "Are you sure you want to enable this Contact?",
                buttons: {
                    confirm: function() {
                        $("body").addClass("loading");
                        posting = $.post("/Clients/Contact/Disable",
                        {
                            _token: "{{ csrf_token() }}",
                            ContactID: $id,
                            Action: "enable"
                        });

                        posting.done(function( data ) {
                            $("body").removeClass("loading");
                            bootstrap_alert.warning("Contact Enabled", 'success', 4000);
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


        $primaryphonenumber = $('input[name=primaryphonenumber]:checked').val();

        contactpost = PostContact($id, $('#firstname').val(), $('#middlename').val(), $('#lastname').val(), $('#address_id').val(), $('#ssn').val(), $('#driverslicense').val(), $('#contactemail').val(), $client_id, $('#contacttype').val(), $('#officephone').val(), $('#mobilephone').val(), $('#homephone').val(), $primaryphonenumber)

        contactpost.done(function( data )
        {
            if ($.isNumeric(data))
            {
                $("body").removeClass("loading");
                $id = data;
                var link = document.createElement('a');
                link.href = "/Clients/View/" + $client_id;
                link.id = "link";
                document.body.appendChild(link);
                link.click();
            }else{
                //server validation errors
                $("body").removeClass("loading");
                ServerValidationErrors(data);
            }
        });

        contactpost.fail(function() {
            $("body").removeClass("loading");
            bootstrap_alert.warning("Failed to post contact details", 'danger', 4000);
        });
    });
});



function PostContact($id, $firstname, $middlename, $lastname, $address_id, $ssn, $driverslicense, $contactemail, $client_id, $contacttype, $officephone, $mobilephone, $homephone, primaryphonenumber) {

    return $.post("/Clients/Contact/Save",
    {
        _token: "{{ csrf_token() }}",
        id: $id ,
        firstname: $firstname,
        middlename: $middlename,
        lastname: $lastname,
        address_id: $address_id,
        ssn: $ssn,
        driverslicense: $driverslicense,
        contactemail: $contactemail,
        client_id: $client_id,
        contacttype: $contacttype,
        officenumber: $officephone,
        mobilenumber: $mobilephone,
        homenumber: $homephone,
        primaryphonenumber: $primaryphonenumber
    });
}
function UpdateAddress($id, $address){
    $('#address_id').val($id);
    $('#address-display').html($address);
}
</script>


@stop
