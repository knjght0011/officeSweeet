<style>
    #signing-setup-marker-options{
        position: absolute;
        display: none;
        left: 0;
        top: 0;
    }
</style>

<div class="modal fade" id="signing-setup-marker-set-signee-modal" tabindex="-1" role="dialog" aria-labelledby="SignModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Set Signee:</h4>
            </div>
            <div class="modal-body">
                <div id="view-tabs">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#signing-setup-marker-set-signee-modal-contacts" aria-controls="profile" role="tab" data-toggle="tab">Contacts</a></li>
                        <li role="presentation"><a href="#signing-setup-marker-set-signee-modal-employees" aria-controls="profile" role="tab" data-toggle="tab">Team/Staff</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="signing-setup-marker-set-signee-modal-contacts">
                            @foreach($contacts as $contact)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="signing-setup-marker-set-signee-radios" id="signing-setup-marker-set-signee-radios" data-contactid="{{ $contact->id }}" data-contactemail="{{ $contact->email }}" data-type="{{ $report-> GetType() }}">
                                    <label class="form-check-label" for="signing-setup-marker-set-signee-radios">
                                        {{ $contact->firstname }} {{ $contact->lastname }} ({{ $contact->email }})
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div role="tabpanel" class="tab-pane" id="signing-setup-marker-set-signee-modal-employees">
                            @foreach(UserHelper::GetAllUsers() as $user)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="signing-setup-marker-set-signee-radios" id="signing-setup-marker-set-signee-radios" data-contactid="{{ $user->id }}" data-contactemail="{{ $user->email }}" data-type="employee">
                                    <label class="form-check-label" for="signing-setup-marker-set-signee-radios">
                                        {{ $user->firstname }} {{ $user->lastname }} ({{ $user->email }})
                                    </label>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button id="signing-setup-marker-set-signee-save" name="SignModal-sign" type="button" class="btn OS-Button" value="">Set</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).click(function() {
    CloseRightClickMenu();
});

$(document).ready(function() {

    CloseRightClickMenu();

    $('#signing-setup-marker-set-signee-modal').on('show.bs.modal', function (event) {
       $('input[name=signing-setup-marker-set-signee-radios]').each(function () {
           if($(this).data('contactid') === window.selectedelement.data('contactid')){
               $(this).prop("checked", true);
           }else{
               $(this).prop("checked", false);
           }
       });
    });

    $(document).on('contextmenu', '.sigining-signature-marker', function(){
        event.preventDefault();
        $("#signing-setup-marker-options").css({
            display: "block",
            left: event.clientX,
            top: event.clientY
        });

        window.selectedelement = $(this);
    });

    /*
    $(document).on("dblclick", ".sigining-signature-marker", function(e) {
        event.preventDefault();
        $("#signing-setup-marker-options").css({
            display: "block",
            left: event.clientX,
            top: event.clientY
        });

        window.selectedelement = $(this);
    });*/

    $('#signing-setup-marker-options-delete').click(function () {
        window.selectedelement.remove();
    });

    $('#signing-setup-marker-set-signee-save').click(function () {

        $checked = $('input[name=signing-setup-marker-set-signee-radios]:checked');

        if($checked.length === 1){

            window.selectedelement.data('contactid', $checked.data('contactid'));
            window.selectedelement.data('contactemail', $checked.data('contactemail'));
            window.selectedelement.data('type', $checked.data('type'));

            $('#signing-setup-marker-set-signee-modal').modal('hide');

        }else{
            $.dialog({
                title: 'Oops..',
                content: 'Please select a contact.'
            });
        }


    });
});

function CloseRightClickMenu() {
    $("#signing-setup-marker-options").css({
        display: "none",
        left: 0,
        top: 0
    });
}
</script>