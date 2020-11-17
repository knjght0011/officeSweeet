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
                    @foreach ($client->contacts as $contact)
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
        client_id = {{ $client->id }};
        contact_id = $(this).attr("value");

        ResetServerValidationErrors();
        
        post = $.post("/Clients/Contact/ChangePrimary",
        {
            _token: "{{ csrf_token() }}",
            client_id: client_id,
            contact_id: contact_id,
        });
        
        
        post.done(function( data ) {
            if(data === "success"){
                location.reload();
            }else{
                ServerValidationErrors(data);
            }
        });
        
        post.fail(function() {

        });        
    
    });
});
</script>