<!-- Button trigger modal -->
<div class="modal fade" id="addbranchmodal" tabindex="-1" role="dialog" aria-labelledby="addbranchmodal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="addbranchmodal">Branch Info</h4>
        </div>
        <div class="modal-body">
            {!! Form::OSselect("branch-status", "Status", ["Active" => "Active","Disabled" => "Disabled","Main" => "Main"], "", 0, "false", "") !!}
            
            <legend>Address</legend>
            {!! Form::OSinput("number", "House Name\Number", "", "", "true", "") !!}
            {!! Form::OSinput("address1", "Street", "", "", "true", "") !!}
            {!! Form::OSinput("address2", "Address Line 2", "", "", "true", "") !!}
            {!! Form::OSinput("city", "City", "", "", "true", "") !!}
            {!! Form::OSinput("region", "Region", "", "", "true", "") !!}
            {!! Form::OSinput("state", "State/Province", "", "", "true", "") !!}
            {!! Form::OSinput("zip", "Postal Code", "", "", "true", "", "text", true, "branch-lookup", "Lookup Address") !!}
            <legend>Phone Numbers</legend>
            {!! Form::OSinput("phonenumber", "Phone Number", "", "", "true", "") !!}
            {!! Form::OSinput("faxnumber", "Fax Number", "", "", "true", "") !!}
            <legend>Other Info</legend>
            {!! Form::OSinput("citytax", "City Tax %", "", "", "true", "") !!}
            
            <input id="branch-id" style="display: none;" name="branch-id" type="text" value="0" >
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button id="branch-save" name="save" type="button" class="btn OS-Button">Save</button>
        </div>
    </div>
  </div>
</div>   


<div class="modal fade" id="disablebranchmodal" tabindex="-1" role="dialog" aria-labelledby="disablebranchmodal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="disablebranchmodal">Disable Branch</h4>
        </div>
        <div class="modal-body">
            <select id="disable-select" name="disable-select" class="form-control">
                @foreach($branches as $branch)
                @if($branch->isDisabled() === false)
                    <option value="{{ $branch->id }}">{{ $branch->number }} - {{ $branch->address1 }} - {{ $branch->address2 }} - {{ $branch->city }} - {{ $branch->region }} - {{ $branch->state }} - {{ $branch->zip }} - {{ $branch->citytax }} -</option>
                @endif
                @endforeach
            </select>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button id="branch-disable" name="save" type="button" class="btn OS-Button">Save</button>
        </div>
    </div>
  </div>
</div>      

<div class="modal fade" id="reassignbranchmodal" tabindex="-1" role="dialog" aria-labelledby="reassignbranchmodal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="reassignbranchmodal">Reassign</h4>
        </div>
        <div class="modal-body">
            <div id="reassignbranchmodaltext"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
 </div>
    
</div> 
<script>    
$(document).ready(function() {
    
    $('#branch-status').change(function()
    {
       if(this.value === "Main"){
           $.dialog({
                title: 'Warning!',
                content: 'You can only have one main branch, By setting this branch as your main branch it will replace your current main branch.'
            });
       } 
    });
    
    $('#addbranchmodal').on('show.bs.modal', function (event) {
        $('#branch-status').popover('destroy');
        
        var button = $(event.relatedTarget);
        if(button.data("function") === "edit"){
            $row = $('#EnabledBranches').DataTable().row('.selected').data();
            
            if($row === undefined){
                   $.dialog({
                    title: 'Oops...',
                    content: 'Please select a branch from the table first.'
                });
            }
            
            $('#branch-id').val($row[11]);
            
            $('#number').prop('disabled', false);
            $('#number').val($row[4]);
            
            $('#address1').prop('disabled', false);
            $('#address1').val($row[5]);
            
            $('#address2').prop('disabled', false);
            $('#address2').val($row[6]);
            
            $('#city').prop('disabled', false);
            $('#city').val($row[7]);
            
            $('#state').prop('disabled', false);
            $('#state').val($row[8]);
            
            $('#region').prop('disabled', false);
            $('#region').val($row[9]);

            $('#zip').val($row[10]);

            $('#phonenumber').val($row[1]);
            $('#faxnumber').val($row[2]);

            $("#branch-status").val($row[3]);

            $('#citytax').val($row[13]);
           
            if($row[12] === "0"){
                //$('#branch-status').prop('disabled', false);
                $("#branch-status option[value=Disabled]").prop('disabled', false);
                $("#branch-status option[value=Disabled]").css('backgroundColor','white');
            }else{
                //$('#branch-status').prop('disabled', true);
                $("#branch-status option[value=Disabled]").prop('disabled', true);
                $("#branch-status option[value=Disabled]").css('backgroundColor','red');
                
                AddPopup($('#branch-status'), "left", "Unable to disable this branch as it currently has " + $row[12] + " employees assigned to it.");
            }
        }
        if(button.data("function") === "add"){
            //$('#branch-status').prop('disabled', true);
            $("#branch-status option[value=Disabled]").prop('disabled', true);
            $("#branch-status option[value=Disabled]").css('backgroundColor','red');
            
            $("#branch-status").val("Active");
            
            $('#branch-id').val("0");
            
            $('#number').prop('disabled', true);
            $('#number').val("");
            
            $('#address1').prop('disabled', true);
            $('#address1').val("");
            
            $('#address2').prop('disabled', true);
            $('#address2').val("");
            
            $('#city').prop('disabled', true);
            $('#city').val("");
            
            $('#state').prop('disabled', true);
            $('#state').val("");
            
            $('#region').prop('disabled', true);
            $('#region').val("");
            
            $('#phonenumber').val("");
            $('#faxnumber').val("");

            $('#citytax').val("");

        }
        
    }); 

    $("#branch-lookup").click(function () {
        $done = function(){
            $('#branch-save').prop('disabled', false);
        };

        AddressLookup($('#zip').val());
    });

    $("#branch-save").click(function()
    {
        $("body").addClass("loading");
        
        var $branchid = $('#branch-id').val();
        var $number = $('#number').val();
        var $address1 = $('#address1').val();
        var $address2 = $('#address2').val();
        var $city = $('#city').val();
        var $region = $('#region').val();
        var $state = $('#state').val();
        var $zip = $('#zip').val();
        
        var $phonenumber = $('#phonenumber').val();
        var $faxnumber = $('#faxnumber').val();

        var $citytax = $('#citytax').val();
        
        var $status = $("#branch-status").val();
        //validate data
        
        //post address data.
        posting = $.post("/ACP/Branches/Save",
        {
            _token: "{{ csrf_token() }}",
            id: $branchid,
            number: $number,
            address1: $address1,
            address2: $address2,
            city: $city,
            region: $region,
            state: $state,
            zip: $zip,
            phonenumber: $phonenumber,
            faxnumber: $faxnumber,
            status: $status,
            citytax: $citytax
        });
        
        posting.done(function( data )
        {
            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":
                    SavedSuccess('Branch saved.');
                    GoToTab("CompanyInfo", "companyinfo-branch");
                    break;
                case "notlogedin":
                    NotLogedIN();
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
        
        posting.fail(function() 
        {
            $("body").removeClass("loading");
            $.dialog({
                title: 'Error!',
                content: 'Failed to save branch.'
            });
        });
    });
});
</script>
