<div class="modal fade" id="send-gmail">
    <div style="margin: 2.5vh auto; width: 95vw" class="modal-dialog" role="document">
        <div style="height: 95vh; width: 95vw;" class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Send G-Mail</h4>
            </div>
            <div style="height: calc(95vh - 120px);" class="modal-body gmail-modal-body" id="gmail-modal-body">
                @if(Auth::user()->CanSendGmail() && SettingHelper::GetSetting('gmail-system') != null)
                    <p>Which account would you like to send from:</p>
                    <label class="radio-inline">
                        <input type="radio" name="account-radio" value="0" checked="checked">Company Wide Account({{ json_decode(SettingHelper::GetSetting('gmail-system'))->email }})
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="account-radio" value="1">Personal Account({{ Auth::user()->GoogleAccessToken['email'] }})
                    </label>
                @endif
                {!! Form::OSinput("gmail-to", "To") !!}
                {!! Form::OSinput("gmail-subject", "Subject") !!}
                <div name="gmail-body" id="gmail-body" style="height: calc(100% - 68px);"></div>
            </div>
            <div class="modal-footer">
                <button id="gmail-send" type="button" class="btn btn-secondary">Send</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>

</style>
<script>
$(document).ready(function() {

    $('#gmail-to').attr('readonly' , true);

    ClassicEditor.create( document.querySelector( '#gmail-body' ))
        .then( editor => {
            window.gmaileditor = editor;

            $height = $('#gmail-modal-body').height() - 98;
            gmaileditor.ui.view.editable.editableElement.style.height = $height + 'px';

        } )
        .catch( err => {
            console.error( err.stack );
        } );





    $('#send-gmail').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        $('#gmail-to').val(button.data('toaddress'));
    }); 

    $('#send-gmail').on('hide.bs.modal', function (event) {

    });

    $('#gmail-send').click(function(){
        $("body").addClass("loading");
        ResetServerValidationErrors();


        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['to'] = $('#gmail-to').val();
        $data['subject'] = $('#gmail-subject').val();
        $data['body'] = gmaileditor.getData();

        @if(Auth::user()->CanSendGmail() && SettingHelper::GetSetting('gmail-system') != null)
        $data['account'] = $('input[name=account-radio]:checked').val();
        @else
        $data['account'] = "unknown";
        @endif



        $post = $.post("/Google/Gmail/Send", $data);

        $post.done(function (data) {
            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":
                    SavedSuccess();
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

        $post.fail(function () {
            NoReplyFromServer();
        });
    });

});
</script>