<!-- Button trigger modal -->
<button type="button" class="btn OS-Button btn-lg" data-toggle="modal" data-target="#addbranchmodal">
  Add Branch
</button>

<!-- Modal -->
<div class="modal fade" id="addbranchmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
            <h4 class="modal-title" id="myModalLabel">Add Branch</h4>
        </div>
        <div class="modal-body">
            <label class="col-md-4 control-label" for="address1">House Name\Number:</label>  
            <div class="col-md-5">
            <input id="number" name="number" type="text" placeholder="" class="form-control input-md" required="" disabled>
            </div>

            <label class="col-md-4 control-label" for="address1">Street:</label>  
            <div class="col-md-5">
            <input id="address1" name="address1" type="text" placeholder="Address Line 1" class="form-control input-md" required="" disabled>
            </div>

            <label class="col-md-4 control-label" for="address2">Address Line 2:</label>  
            <div class="col-md-5">
            <input id="address2" name="address2" type="text" placeholder="Address Line 2" class="form-control input-md" disabled>
            </div>

            <label class="col-md-4 control-label" for="city">City:</label>  
            <div class="col-md-5">
            <input id="city" name="city" type="text" placeholder="City" class="form-control input-md" required="" disabled>
            </div>

            <label class="col-md-4 control-label" for="region">Region:</label>  
            <div class="col-md-5">
            <input id="region" name="region" type="text" placeholder="Region" class="form-control input-md" required="" disabled>
            </div>

            <label class="col-md-4 control-label" for="state">State/Province:</label>  
            <div class="col-md-5">
            <input id="state" name="state" type="text" placeholder="State" class="form-control input-md" required="" disabled>
            </div>

            <label class="col-md-4 control-label" for="zip">Postal Code:</label>  
            <div class="col-md-5">
                <input id="zip" name="zip" type="text" placeholder="Zip" class="form-control input-md" required="">
            </div>
            <div class="col-md-3">
                <button id="branch-lookup" name="lookup" type="button" class="btn OS-Button">Lookup Address</button>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button id="branch-save" name="save" type="button" class="btn OS-Button" disabled>Save</button>
        </div>
    </div>
  </div>
</div>   
    
<script>    
$(document).ready(function() {
     $id = 0;

    $("#branch-lookup").click(function () {
        $done = function(){
            $('#save').prop('disabled', false);
        };

        AddressLookup($('#zip').val());
    });

    $("#branch-save").click(function()
    {        
        var $number = $('#number').val();
        var $address1 = $('#address1').val();
        var $address2 = $('#address2').val();
        var $city = $('#city').val();
        var $region = $('#region').val();
        var $state = $('#state').val();
        var $zip = $('#zip').val();
        
        //validate data
        
        //post address data.
        $.post("ACP/Branches/Save",
        {
            _token: "{{ csrf_token() }}",
            id: $id,
            number: $number,
            address1: $address1,
            address2: $address2,
            city: $city,
            region: $region,
            state: $state,
            zip: $zip
        },
        
        function(data, status)
        {
            if ($.isNumeric(data)) 
            {
                $id = data;
                alert(data);
                var link = document.createElement('a');
                link.href = "/Settings/Branches/Search";
                link.id = "link";
                document.body.appendChild(link);
                link.click(); 
            } 
            else 
            {
                //server validation errors
                ServerValidationErrors(data);
            }
        });
    });
});
</script>
