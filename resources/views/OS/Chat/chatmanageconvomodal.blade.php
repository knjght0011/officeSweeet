<div class="modal fade" id="manage-conversation-model">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Manage Conversation</h4>
            </div>
            <div class="modal-body">

                <div class="input-group ">
                    <span class="input-group-addon" for="chat-manage-subject"><div style="width: 7em;">Subject:</div></span>
                    <input id="chat-manage-subject" name="chat-manage-subject" type="text" placeholder="Subject" value="" class="form-control">
                </div>
                <div class="input-group ">
                    <span class="input-group-addon" for="chat-manage-user-list"><div style="width: 7em;">Users:</div></span>
                    <select style="width: 100%;" id="chat-manage-user-list" multiple="multiple" class="form-control">
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
                <button id="chat-update-convo-submit" type="button" class="btn OS-Button">Save Chat</button>
            </div>    <!-- Submit Form Input -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    $(document).ready(function () {

        $('#chat-manage-user-list').select2({
            theme: "bootstrap"
        });


        $('#manage-conversation-model').on('show.bs.modal', function (event) {

            window._chatSystem.hideChat();

            if($('#thread_list').data('currentthread') != undefined) {
                $thread = _chatSystem.threads[$('#thread_list').data('currentthread')];

                $('#manage-conversation-model').data('thread', $thread);

                $('#chat-manage-subject').val($thread['subject']);

                $users = [];
                $.each($thread['chatparticipants'], function ($index, $chatpart) {
                    $users.push($chatpart['user']['id']);

                });

                $('#chat-manage-user-list').val($users);
                $('#chat-manage-user-list').trigger('change');

            }else{
                event.stopPropagation();
            }
        });

        $('#manage-conversation-model').on('hidden.bs.modal', function (event) {
            window._chatSystem.showChat();
        });

        $('#chat-update-convo-submit').click(function () {



            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['thread'] = $('#manage-conversation-model').data('thread')['id'];

            $data['name'] = $('#chat-manage-subject').val();
            $data['users'] = $("#chat-manage-user-list").val();


            $("body").addClass("loading");
            $post = $.post("/Chat/thread/update", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        $('#manage-conversation-model').modal('hide');
                        break;
                    case "allreadyinthread":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                        break;
                    case "notfound":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
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


        /*
        $('.chat-manage-convo-user-add').click(function () {

            $("body").addClass("loading");

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['thread'] = $('#manage-conversation-model').data('thread')['id'];
            $data['user'] = $(this).data('id');

            $post = $.post("/Chat/thread/adduser", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        $('#chat-manage-user-display-' + data['userid']).css('display', 'table-row');
                        $('#chat-manage-user-list-' + data['userid']).css('display', 'none');
                        break;
                    case "allreadyinthread":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                        break;
                    case "notfound":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
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

        $('.chat-new-manage-user-remove').click(function () {

            $("body").addClass("loading");

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['thread'] = $('#manage-conversation-model').data('thread')['id'];
            $data['user'] = $(this).data('id');

            $post = $.post("/Chat/thread/removeuser", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":

                        $('#chat-manage-user-display-' + data['userid']).css('display', 'none');
                        $('#chat-manage-user-list-' + data['userid']).css('display', 'table-row');
                        if(data['userid'] === {{ Auth::user()->id }}){
                            $('#manage-conversation-model').modal('hide');
                            $('#chat_container').css('display', 'block');
                        }
                        break;
                    case "allreadyinthread":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                        break;
                    case "notfound":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
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

        $('#chat-manage-convo-subject-save').click(function () {
            $("body").addClass("loading");



            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['thread'] = $('#manage-conversation-model').data('thread')['id'];
            $data['subject'] = $("#chat-manage-convo-subject").val();

            $post = $.post("/Chat/thread/rename", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":

                        break;
                    case "allreadyinthread":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                        break;
                    case "notfound":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
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
        */
    });

</script>
