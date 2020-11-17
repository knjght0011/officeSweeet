<style>

    #chat_container{
        position: fixed;

        width: 1000px;
        bottom: 85px;
        right: 25px;
        display: none;
        border-radius: 5px;
        border-color: lightgray;
        border-width: 5px;
        border-style: double;
        z-index: 1000;
    }

    #custom-search-input {
        background: #e8e6e7 none repeat scroll 0 0;
        margin: 0;
        padding: 10px;
    }

    #custom-search-input .search-query {
        background: #fff none repeat scroll 0 0 !important;
        border-radius: 4px;
        height: 33px;
        margin-bottom: 0;
        padding-left: 7px;
        padding-right: 7px;
    }

    #custom-search-input button {
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
        border: 0 none;
        border-radius: 3px;
        color: #666666;
        left: auto;
        margin-bottom: 0;
        margin-top: 7px;
        padding: 2px 5px;
        position: absolute;
        right: 0;
        z-index: 9999;
    }

    .search-query:focus + button {
        z-index: 3;
    }

    .all_conversation button {
        background: #f5f3f3 none repeat scroll 0 0;
        border: 1px solid #dddddd;
        height: 38px;
        text-align: left;
        width: 100%;
    }

    .all_conversation i {
        background: #e9e7e8 none repeat scroll 0 0;
        border-radius: 100px;
        color: #636363;
        font-size: 17px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        width: 30px;
    }

    .all_conversation .caret {
        bottom: 0;
        margin: auto;
        position: absolute;
        right: 15px;
        top: 0;
    }

    .all_conversation .dropdown-menu {
        background: #f5f3f3 none repeat scroll 0 0;
        border-radius: 0;
        margin-top: 0;
        padding: 0;
        width: 100%;
    }

    .all_conversation ul li {
        border-bottom: 1px solid #dddddd;
        line-height: normal;
        width: 100%;
    }

    .all_conversation ul li a:hover {
        background: #dddddd none repeat scroll 0 0;
        color: #333;
    }

    .all_conversation ul li a {
        color: #333;
        line-height: 30px;
        padding: 3px 20px;
    }

    .member_list .chat-body {
        margin-top: 0;
    }

    .top_nav {
        overflow: visible;
    }

    .member_list .contact_sec {
        margin-top: 3px;
    }

    .member_list li {
        padding: 6px;
    }

    .member_list ul {
        border: 1px solid #dddddd;
    }

    .chat-img img {
        height: 34px;
        width: 34px;
    }

    .member_list li {
        border-bottom: 1px solid #dddddd;
        padding: 6px;
    }

    .member_list li:last-child {
        border-bottom: none;
    }

    .member_list {
        height: 380px;
        overflow-x: hidden;
        overflow-y: auto;
    }

    .sub_menu_ {
        background: #e8e6e7 none repeat scroll 0 0;
        left: 100%;
        max-width: 233px;
        position: absolute;
        width: 100%;
    }

    .sub_menu_ {
        background: #f5f3f3 none repeat scroll 0 0;
        border: 1px solid rgba(0, 0, 0, 0.15);
        display: none;
        left: 100%;
        margin-left: 0;
        max-width: 233px;
        position: absolute;
        top: 0;
        width: 100%;
    }

    .all_conversation ul li:hover .sub_menu_ {
        display: block;
    }

    .new_message_head button {
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
        border: medium none;
    }

    .new_message_head {
        background: #f5f3f3 none repeat scroll 0 0;
        float: left;
        font-size: 13px;
        font-weight: 600;
        padding: 18px 10px;
        width: 100%;
    }

    .message_section {
        border: 1px solid #dddddd;
    }

    .chat_area {
        float: left;
        height: 303px;
        overflow-x: hidden;
        overflow-y: auto;
        width: 100%;
    }

    .chat_area li {
        padding: 14px 14px 0;
    }

    .chat_area li .chat-img1 img {
        height: 40px;
        width: 40px;
    }

    .chat_area .chat-body1 {
        margin-left: 50px;
    }

    .chat-body1 p {
        background: #fbf9fa none repeat scroll 0 0;
        padding: 10px;
    }

    .chat_area .admin_chat .chat-body1 {
        margin-left: 0;
        margin-right: 50px;
    }

    .chat_area li:last-child {
        padding-bottom: 10px;
    }

    .message_write {
        background: #f5f3f3 none repeat scroll 0 0;
        float: left;
        padding: 15px;
        width: 100%;
    }

    .message_write textarea.form-control {
        height: 70px;
        padding: 10px;
    }

    .chat_bottom {
        float: left;
        margin-top: 13px;
        width: 100%;
    }

    .upload_btn {
        color: #777777;
    }

    .sub_menu_ > li a, .sub_menu_ > li {
        float: left;
        width: 100%;
    }

    .member_list li:hover {
        background: #428bca none repeat scroll 0 0;
        color: #fff;
        cursor: pointer;
    }

    .message-selected{
        background: lightgray none repeat scroll 0 0;
    }

    .new-convo-user {

        border: solid;
        border-radius: 5px;
        border-width: 1px;
        padding: 5px;
        margin-bottom: 5px;

    }

</style>

<div id="chat_container" class="chat_container" style="background-color: white;">
    <div class="col-sm-3 chat_sidebar">
        <div class="row">
            <div id="custom-search-input">
                <div class="input-group col-md-12">
                    <!--<input type="text" class="  search-query form-control" placeholder="Conversation"/>
                    <button class="btn btn-danger" type="button">
                        <span class=" glyphicon glyphicon-search"></span>
                    </button>-->
                </div>
            </div>
            <div class="dropdown all_conversation">
                <button class="dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-weixin" aria-hidden="true"></i>
                    Menu
                    <span class="caret pull-right"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">


                    <li><a id="chat-start-new-convo-link">New Conversation</a></li>

                    <li><a id="chat-manage-convo-link">Manage Conversation</a></li>
                </ul>
            </div>
            <div id="thread_list" class="member_list">
                <ul class="list-unstyled list">

                </ul>
            </div>
        </div>
    </div>
    <!--chat_sidebar-->


    <div class="col-sm-9 message_section">
        <div class="row">
            <div class="new_message_head">
                <div class="pull-left" id="chat-message-subject">
                    None Selected
                </div>
                <div class="pull-right">
                    <!--
                    <div class="dropdown">
                        <button class="dropdown-toggle" type="button" id="dropdownMenu1"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-cogs" aria-hidden="true"></i> Setting
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Profile</a></li>
                            <li><a href="#">Logout</a></li>
                        </ul>


                    </div>
                    -->
                </div>
            </div><!--new_message_head-->

            <div class="chat_area" id="chat_container_div">
                <ul id="chat-area" class="list-unstyled">
                </ul>
            </div><!--chat_area-->
            <div class="message_write">
                <div class="input-group">
                    <textarea rows="3" style="resize: none; height: 100%;" id="message-input" name="message-input" class="form-control"> </textarea>
                    <span style="height: 100%;" class="input-group-btn">
                        <button style="height: 82px;" class="btn btn-default" type="button" onclick="sendChatMessage($('#message-input').val())">Send</button>
                    </span>
                </div>
                <!--
                <input id="message-input" class="form-control" placeholder="type a message" />
                <div class="clearfix"></div>
                <div class="chat_bottom"><a href="#" class="pull-left upload_btn"><i class="fa fa-cloud-upload" aria-hidden="true"></i>Add Files</a>
                    <a href="#" class="pull-right btn btn-success" onclick="sendChatMessage($('#message-input').val())">Send</a></div>
                    -->
            </div>
        </div>
    </div> <!--message_section-->
</div>



<script>
    $(document).ready(function () {

        $('.dropdown-submenu a.test').on("click", function(e){
            $(this).next('ul').toggle();
            e.stopPropagation();
            e.preventDefault();
        });

        $('#chat-start-new-convo-link').click(function () {
            $('#chat_container').css('display', 'none');
            $('#create-conversation-model').modal('show');
        });

        $('#chat-manage-convo-link').click(function () {

            if($('#thread_list').data('currentthread') != undefined) {
                $('#chat_container').css('display', 'none');
                $('#manage-conversation-model').modal('show');
            }else{
                event.stopPropagation();
            }

        });

        $('#chat_container').click(function (e) {
            e.stopPropagation();
        });

        $('#chatcircle').click(function (e) {
            e.stopPropagation();
            window._chatSystem.toggleChat();
        });

    });

    function sendChatMessage($message) {


        ResetServerValidationErrors();

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['message'] = $message;
        $data['thread'] = $('#thread_list').data('currentthread');

        if($data['thread'] === 0){
            $data['user'] = $('#thread_list').data('currentuser');
        }

        $post = $.post("/Chat/messages", $data);

        $post.done(function (data) {
            if (data["status"] === "Message Sent!") {
                if(data['threadid'] !== undefined){
                    $('#thread_list').data('currentthread', data['threadid']);
                    $('.message-selected').data('id', data['threadid']);

                    $('#chat-area').append(messageElement(data['name'], data['message'], data['created_at']));
                }

                $('#message-input').val('');
            } else {
                ServerValidationErrors(data);
            }
        });

        $post.fail(function () {
            NoReplyFromServer();
        });


    }
</script>

@include('OS.Chat.chatnewconvomodal')
@include('OS.Chat.chatmanageconvomodal')