
<div class="modal fade" id="create-conversation-model">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">New Conversation</h4>
            </div>
            <div class="modal-body">
                <div class="input-group ">
                    <span class="input-group-addon" for="chat-newconvo-subject"><div style="width: 7em;">Subject:</div></span>
                    <input id="chat-newconvo-subject" name="chat-newconvo-subject" type="text" placeholder="Subject" value="" class="form-control">
                </div>
                <div class="input-group ">
                    <span class="input-group-addon" for="chat-newconvo-user-list-new"><div style="width: 7em;">Users:</div></span>
                    <select style="width: 100%;" id="chat-newconvo-user-list-new" multiple="multiple" class="form-control">
                        @foreach(UserHelper::GetAllUsers() as $user)
                            @if($user->id != Auth::user()->id)
                                <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}
                                    @if($user->department != "")
                                        - {{ $user->department }}
                                    @endif</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="chat-new-convo-submit" type="button" class="btn OS-Button">Begin Chat</button>
            </div>    <!-- Submit Form Input -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    $(document).ready(function () {

        $('#create-conversation-model').on('show.bs.modal', function (event) {
            window._chatSystem.hideChat();
        });

        $('#create-conversation-model').on('hidden.bs.modal', function (event) {
            window._chatSystem.showChat();
        });

        $('#chat-newconvo-user-list-new').select2({
            theme: "bootstrap"
        });

        $('#chat-new-convo-submit').click(function () {

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['name'] = $('#chat-newconvo-subject').val();
            $data['users'] = $("#chat-newconvo-user-list-new").val();

            $valid = true;
            $string = "";
            if($data['users'].length === 0){
                $valid = false;
                $string = $string + "Please select atleast one user <br>";
            }

            if($data['name'] === ""){
                $valid = false;
                $string = $string + "Please set a subject <br>";
            }

            if($valid){
                PostNewThread($data);
            }else{
                $.dialog({
                    title: 'Oops...',
                    content: $string
                });
            }

        });

    });

    function PostNewThread($data) {

        $("body").addClass("loading");
        $post = $.post("/Chat/thread", $data);

        $post.done(function (data) {
            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":
                    $('.thread').each(function ($index, $value) {

                       if($(this).data('id').toString() === data['thread'].toString()){
                            $(this).click();
                       }
                    });
                    $('#create-conversation-model').modal('hide');
                    $('#chat-newconvo-subject').val("");
                    $('#chat-newconvo-user-list-new').val("");
                    $('#chat-newconvo-user-list-new').trigger('change');
                    break;
                case "notfound":
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Response from server. Please refresh the page and try again.'
                    });
                    break;
                case "notlogedin":
                    NotLogedIN();
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
    }
</script>
