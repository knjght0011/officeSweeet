<div id="address-new-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    New Address
                </h4>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <span class="input-group-addon" for="number"><div style="width: 15em;">House Name\Number:*</div></span>
                    <input id="number" name="number" type="text" placeholder="" class="form-control" required="" disabled>
                </div>

                <div class="input-group">
                    <span class="input-group-addon" for="address1"><div style="width: 15em;">Street:*</div></span>
                    <input id="address1" name="address1" type="text" placeholder="Address Line 1" class="form-control" required="" disabled>
                </div>

                <!-- Text input-->
                <div class="input-group">
                    <span class="input-group-addon" for="address2"><div style="width: 15em;">Address Line 2:</div></span>
                    <input id="address2" name="address2" type="text" placeholder="Address Line 2" class="form-control" disabled>
                </div>

                <!-- Text input-->
                <div class="input-group">
                    <span class="input-group-addon" for="city"><div style="width: 15em;">City:*</div></span>
                    <input id="city" name="city" type="text" placeholder="City" class="form-control" required="" disabled>
                </div>

                <div class="input-group">
                    <span class="input-group-addon" for="region"><div style="width: 15em;">Region:*</div></span>
                    <input id="region" name="region" type="text" placeholder="Region" class="form-control" required="" disabled>
                </div>

                <!-- Text input-->
                <div class="input-group">
                    <span class="input-group-addon" for="state"><div style="width: 15em;">State/Province:*</div></span>
                    <input id="state" name="state" type="text" placeholder="State" class="form-control" required="" disabled>
                </div>

                <!-- Text input-->
                <div class="input-group">
                    <span class="input-group-addon" for="zip"><div style="width: 15em;">Postal Code:*</div></span>
                    <input id="zip" name="zip" type="text" placeholder="Zip" class="form-control" required="">
                    <span class="input-group-btn">
                        <button id="lookup" name="lookup" type="button" class="btn btn-default">Lookup Address</button>
                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button id="address-new-save" type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    $saved = false;

    $('#address-new-modal').on('show.bs.modal', function (event) {
        $saved = false;
    });

    $('#address-new-modal').on('hide.bs.modal', function (event) {

        if($saved === false){
            //they didnt click anything might need to move radio button back, not sure how
            if($('#address_id').val() === "null"){
                $('#address-client').prop('checked', true);
            }else{
                $('#address-existing').prop('checked', true);
            }
        }
    });

    $("#lookup").click(function () {
        $done = function () {
            $('#save').prop('disabled', false);
        };

        AddressLookup($('#zip').val());
    });

    $("#address-new-save").click(function()
    {

        $("body").addClass("loading");

        posting = PostAddress($('#number').val(), $('#address1').val(), $('#address2').val(), $('#city').val(), $('#region').val(), $('#state').val(), $('#zip').val());

        posting.done(function( data ) {
            $saved = true;

            $address = $('#number').val() + " ";
            $address = $address + $('#address1').val() + " ";
            $address = $address + $('#address2').val() + " ";
            $address = $address + $('#city').val() + " ";
            $address = $address + $('#region').val() + " ";
            $address = $address + $('#state').val() + " ";
            $address = $address + $('#zip').val();

            UpdateAddress(data, $address);

            $("body").removeClass("loading");

            $('#address-new-modal').modal('hide');

            $('#number').val("");
            $('#address1').val("");
            $('#address2').val("");
            $('#city').val("");
            $('#region').val("");
            $('#state').val("");
            $('#zip').val("");

            $('#number').prop('disabled', true);
            $('#address1').prop('disabled', true);
            $('#address2').prop('disabled', true);
            $('#city').prop('disabled', true);
            $('#region').prop('disabled', true);
            $('#state').prop('disabled', true);
        });

        posting.fail(function() {
            $("body").removeClass("loading");
            bootstrap_alert.warning("Failed to post address details", 'danger', 4000);
        });
    });


});
function PostAddress($number, $address1, $address2, $city, $region, $state, $zip) {
    return $.post("/Address/Add",
        {
            _token: "{{ csrf_token() }}",
            number: $number,
            address1: $address1,
            address2: $address2,
            city: $city,
            region: $region,
            state: $state,
            zip: $zip
        });
}
</script>