<div id="primarycontact-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4>Change Primary Contact</h4>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tbody>
                    @foreach ($vendor->contacts as $contact)
                        <tr>
                            <td>{{ $contact->firstname }} {{ $contact->lastname }}</td>
                            <td><button id="primarycontact-set" name="primarycontact-set" type="button" class="btn OS-Button primarycontact-set" value="{{ $contact->id }}">Save</button></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>                           
        </div>
    </div>
</div> 

<script>    
$(document).ready(function() {
    $(".primarycontact-set").click(function()
    {
        $("body").addClass("loading");
        $vendor_id = {{ $vendor->id }};
        $contact_id = $(this).attr("value");
        
        post = $.post("/Vendors/Contact/ChangePrimary",
        {
            _token: "{{ csrf_token() }}",
            vendor_id: $vendor_id,
            contact_id: $contact_id,
        });
        
        
        post.done(function( data ) {
            $("body").removeClass("loading");
            if(data === "success"){
                location.reload();
            }else{
                ServerValidationErrors(data);
            }
        });
        
        post.fail(function() {
            $("body").removeClass("loading");
            bootstrap_alert.warning("Failed to save", 'danger', 4000);
        });        
    
    });
});
</script>