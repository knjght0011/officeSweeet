@extends('master')

@section('content')
<div class="row">
    <div style="float:left; width: 10em;  margin-left: 20px;">
        <button style="width: 100%;" id="save" name="save" type="button" class="btn OS-Button">Save</button>
    </div>
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button style="width: 100%;" id="backbutton" name="backbutton" type="button" class="btn OS-Button">Back to Vendor</button>
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
            <span class="input-group-addon" for="email"><div style="width: 15em;">E-Mail*</div></span>
            <input id="email" name="email" type="text" value="" class="form-control" required="">
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

@include('Vendors.view.modals.existingaddress')
@include('Vendors.view.modals.newaddress')

<div class="modalload"><!-- Place at bottom of page --></div>

<script>    
$(document).ready(function() {
    @if(isset($contact))
        $('#firstname').val("{{ $contact->firstname }}");
        $('#middlename').val("{{ $contact->middlename }}");
        $('#lastname').val("{{ $contact->lastname }}");
        
        $('#ssn').val("{{ $contact->ssn }}");
        $('#driverslicense').val("{{ $contact->driverslicencse }}");
        $('#email').val("{{ $contact->email }}");
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

        $vendor_id = {{ $contact->vendor_id }};
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
        $vendor_id = {{ $vendor_id }};

        $('#address_id').val("null");
        $('#address-display').html("{{ $vendor->address->AddressString() }}");
        $('#address-client').attr('checked', true);

    @endif

    $('#address-client').click(function(e) {
        UpdateAddress("null", "{{ $vendor->address->AddressString() }}");
    });
    
    $('#backbutton').click(function(e) {
        var link = document.createElement('a');
        link.href = "/Vendors/View/" + $vendor_id;
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
                        posting = $.post("/Vendors/Contact/Disable",
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
                        posting = $.post("/Vendors/Contact/Disable",
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
        ResetServerValidationErrors();

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['id'] = $id;

        $data['firstname'] = $('#firstname').val();
        $data['middlename'] = $('#middlename').val();
        $data['lastname'] = $('#lastname').val();
        $data['address_id'] = $('#address_id').val();
        $data['ssn'] = $('#ssn').val();
        $data['driverslicense'] = $('#driverslicense').val();
        $data['email'] = $('#email').val();
        $data['vendor_id'] = $vendor_id;
        $data['contacttype'] = $('#contacttype').val();
        $data['officenumber'] = $('#officephone').val();
        $data['mobilenumber'] =  $('#mobilephone').val();
        $data['homenumber'] = $('#homephone').val();
        $data['primaryphonenumber'] = $('input[name=primaryphonenumber]:checked').val();

        contactpost =  $.post("/Vendors/Contact/Save", $data);

        contactpost.done(function( data )
        {
            switch(data['status']) {
                case "OK":
                    var link = document.createElement('a');
                    link.href = "/Vendors/View/" + $vendor_id;
                    link.id = "link";
                    document.body.appendChild(link);
                    link.click();
                    break;
                case "validation":
                    $("body").removeClass("loading");
                    ServerValidationErrors(data['errors']);
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

        contactpost.fail(function () {
            NoReplyFromServer();
        });

    });


});  

function UpdateAddress($id, $address){
    $('#address_id').val($id);
    $('#address-display').html($address);
}
</script>
@stop
