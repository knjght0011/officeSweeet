<style>
    #thread_container{
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

    .member_list .thread-body {
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

    .thread-img img {
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

    .thread_area {
        float: left;
        height: 303px;
        overflow-x: hidden;
        overflow-y: auto;
        width: 100%;
    }

    .thread_area li {
        padding: 14px 14px 0;
    }

    .thread_area li .thread-img1 img {
        height: 40px;
        width: 40px;
    }

    .thread_area .thread-body1 {
        margin-left: 50px;
    }

    .thread-body1 p {
        background: #fbf9fa none repeat scroll 0 0;
        padding: 10px;
    }

    .thread_area .admin_thread .thread-body1 {
        margin-left: 0;
        margin-right: 50px;
    }

    .thread_area li:last-child {
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


    .sub_menu_ > li a, .sub_menu_ > li {
        float: left;
        width: 100%;
    }

    .member_list li:hover {
        background: #428bca none repeat scroll 0 0;
        color: #fff;
        cursor: pointer;
    }
</style>

<div id="thread_container" class="thread_container" style="background-color: white;">
    <div class="col-sm-12 message_section">
        <div class="row">
            <div class="new_message_head">
                <div class="pull-left" id="thread-message-subject">
                    None Selected
                </div>
                <div class="pull-right">
                    @if(app()->make('account')->subdomain === "lls" or app()->make('account')->subdomain === "local")
                        <select id="ticket-status-select" class="form-control" onchange="window._ticketSystem.updateStatus($(this).val())">
                            <option value="Open" >Open</option>
                            <option value="On Hold" >On Hold</option>
                            <option value="Closed" >Closed</option>
                        </select>
                    @else
                        <div id="ticket-status-display">

                        </div>
                    @endif
                </div>
            </div><!--new_message_head-->

            <div class="thread_area">
                <ul id="thread-area" class="list-unstyled">
                </ul>
            </div><!--thread_area-->
            <div class="message_write">
                <div class="input-group">
                    <textarea placeholder="Type your message here and click send." rows="3" style="resize: none; height: 100%;" id="thread-message-input" name="thread-message-input" class="form-control"></textarea>
                    <span style="height: 100%;" class="input-group-btn">
                        <button style="height: 82px;" class="btn btn-default" type="button" onclick="window._ticketSystem.postMessage($('#thread-message-input'))">Send</button>
                    </span>
                </div>
                <!--
                <input id="message-input" class="form-control" placeholder="type a message" />
                <div class="clearfix"></div>
                <div class="thread_bottom"><a href="#" class="pull-left upload_btn"><i class="fa fa-cloud-upload" aria-hidden="true"></i>Add Files</a>
                    <a href="#" class="pull-right btn btn-success" onclick="sendthreadMessage($('#message-input').val())">Send</a></div>
                    -->
            </div>
        </div>
    </div> <!--message_section-->
</div>


<script>
    $(document).ready(function () {

        $('#thread_container').click(function (e) {
            e.stopPropagation();
        });
    });

    function ticketSystem($threadmodal) {

        this.threads = {};

        this.activeThread = 0;

        if($('#thread-table').length){
            this.threadtable = $('#thread-table').DataTable({
                "columns": [
                    { "data": "id" },
                    { "data": "subject" },
                    { "data": "subdomain" },
                    { "data": "status" },
                    { "data": "last_message_created_at" },
                    { "data": "created_at" }
                ],
                "order": [[ 4, "desc" ]],
                "pageLength": 20,
                @if(app()->make('account')->subdomain === "lls" or app()->make('account')->subdomain === "local")
                "columnDefs": [
                    {
                        "targets": [0],
                        "visible": false
                    }
                ]
                @else
                "columnDefs": [
                    {
                        "targets": [0,2],
                        "visible": false
                    }
                ]
                @endif

            });

            PageinateUpdate(this.threadtable.page.info(), $('#ticket-status-next-page'), $('#ticket-status-previous-page'),$('#ticket-status-tableInfo'));

            $( "body" ).children().find(".dataTables_filter").css('display', 'none');
            $( "body" ).children().find(".dataTables_length").css('display', 'none');
            $( "body" ).children().find(".dataTables_paginate").css('display', 'none');
            $( "body" ).children().find(".dataTables_info").css('display', 'none');
        }else{
            this.threadtable = false;
        }

        this.threadmodal = $threadmodal;

        this.messagelist = this.threadmodal.find('#thread-area');


        this.receiveMessage = (newmessage) => {
            $thread = this.threads[newmessage['ticket_threads_id']];

            $thread['ticketmessage'].push(newmessage);

            if(parseInt(newmessage['ticket_threads_id']) === this.activeThread){
                this.drawMessage(newmessage);
            }
        };

        this.receiveThread = (newthread) => {
            this.newThread(newthread);
        };

        this.notify = ($titel, $message) => {
            if (Notification.permission !== "granted")
                Notification.requestPermission();
            else {
                var notification = new Notification($titel, {
                    icon: '{{ url("/favicon-32x32.png") }}',
                    body: $message,
                });

                notification.onclick = function () {
                    //window.open($url);
                };
            }
        };

        this.drawMessages = ($thread) => {

            $.each($thread['ticketmessage'], ($index, $message) => {
                //add to object
                this.drawMessage($message);
            });
        };

        this.drawMessage = ($message) => {

            @if(app()->make('account')->subdomain === "lls")
            if ($message['user'] === "OfficeSweeet Support") {
                this.messagelist.prepend(TicketMessageElementMe($message['user'], $message['message'], $message['created_at']));
            } else {
                this.messagelist.prepend(TicketMessageElement($message['user'], $message['message'], $message['created_at']));
            }
            @else
            if ($message['user'] === "{{ Auth::user()->name }}") {
                this.messagelist.prepend(TicketMessageElementMe($message['user'], $message['message'], $message['created_at']));
            } else {
                this.messagelist.prepend(TicketMessageElement($message['user'], $message['message'], $message['created_at']));
            }
            @endif
        };

        this.postMessage = ($textbox) => {
            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['message'] = $textbox.val();
            $data['thread'] = this.activeThread;

            $post = $.post("/Tickets/sendMessage", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        $textbox.val('');
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
        };

        this.updateStatus = ($status) => {
            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['status'] = $status;
            $data['thread'] = this.activeThread;

            $post = $.post("/Tickets/updateStatus", $data);

            $post.done((data) => {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":

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
        };

        this.receiveStatus = (status) => {
            this.threads[parseInt(status['id'])]['status'] = status['status'];

            if(this.threadtable != false) {

                $row = this.threadtable.row( this.threads[parseInt(status['id'])]['rowindex'] ).data();
                $row['status'] = status['status'];
                $row = this.threadtable.row( this.threads[parseInt(status['id'])]['rowindex'] ).data($row).draw() ;

                PageinateUpdate(this.threadtable.page.info(), $('#ticket-status-next-page'), $('#ticket-status-previous-page'),$('#ticket-status-tableInfo'));

            }

            if(this.activeThread === parseInt(status['id'])){
                this.messagelist.prepend(TicketMessageElement("System", "Ticket is now " + status['status'] + ".", ""));
                @if(app()->make('account')->subdomain === "lls" or app()->make('account')->subdomain === "local")
                    this.threadmodal.find('#ticket-status-select').val(status['status']);
                @else
                    this.threadmodal.find('#ticket-status-display').html("Status: " . status['status']);
                @endif
            }
        };


        this.newThread = ($thread) => {
            this.threads[$thread['id']] = $thread;

            if(this.threadtable != false) {

                $rowdata = {};
                $rowdata["id"] = $thread['id'];
                $rowdata["subject"] = $thread['subject'];
                $rowdata["subdomain"] = $thread['subdomain'];
                $rowdata["status"] = $thread['status'];
                $rowdata["last_message_created_at"] = $thread['ticketmessage'][$thread['ticketmessage'].length - 1]['created_at'];
                $rowdata["created_at"] = $thread['created_at'];


                var newRow = this.threadtable.row.add($rowdata).draw();

                this.threads[$thread['id']]['rowindex'] = newRow.index();

                PageinateUpdate(this.threadtable.page.info(), $('#ticket-status-next-page'), $('#ticket-status-previous-page'),$('#ticket-status-tableInfo'));

                window._ticketSystem.threadtable
                    .columns( 3 )
                    .search( "Open" , true)
                    .draw();

            }
        };

        this.tableClick = ($row) => {
            if(this.threadtable != false){
                var data = this.threadtable.row($row).data();

                this.showMessages(data['id'])
            }
        };

        this.showMessages = ($threadid) => {

            this.activeThread = parseInt($threadid);

            this.messagelist.empty();

            this.drawMessages(this.threads[this.activeThread]);

            $status = this.threads[this.activeThread]['status'];

            @if(app()->make('account')->subdomain === "lls" or app()->make('account')->subdomain === "local")
                this.threadmodal.find('#ticket-status-select').val($status);
                this.threadmodal.find('#thread-message-subject').html(this.threads[this.activeThread]['subdomain'] + " - " + this.threads[this.activeThread]['subject']);
            @else
                this.threadmodal.find('#ticket-status-display').html("Status: " + $status);
                this.threadmodal.find('#thread-message-subject').html(this.threads[this.activeThread]['subject']);
            @endif

            this.threadmodal.css('display', 'block');

        };

        this.searchTable = ($value) => {
            this.threadtable.search($value).draw();
        };

        this.init = () => {

            $.get("/Tickets/threads", (data) => {

                $.each(data, ($threadindex, $thread) => {
                    //add to object
                    this.newThread($thread);
                });

            });


        };

        this.init();

    }

    function TicketMessageElement($username, $message, $timestamp) {

        $element = '<li class="left clearfix">'+
            '<span class="thread-img1 pull-left">'+
            '<img src="{{ url('/images/usersilhouete.jpg') }}"alt="User Avatar" class="img-circle">'+
            '</span>'+
            '<div class="thread-body1 clearfix" >'+
            '<p style="background-color: #f2dede; word-wrap: break-word; white-space: pre-wrap;"><b>'+$username+':</b><br>'+$message+'</p>'+
            '<div class="thread_time pull-right">'+$timestamp+'</div>'+
            '</div>'+
            '</li>';

        return $element
    }

    function TicketMessageElementMe($username, $message, $timestamp) {

        $element = '<li class="left clearfix admin_thread">'+
            '<span class="thread-img1 pull-right">'+
            '<img src="{{ url('/images/usersilhouete.jpg') }}"alt="User Avatar" class="img-circle">'+
            '</span>'+
            '<div class="thread-body1 clearfix" style="text-align: right;">'+
            '<p style="background-color: #dff0d8; word-wrap: break-word; white-space: pre-wrap;"><b>'+$username+':</b><br>'+$message+'</p>'+
            '<div class="thread_time pull-left">'+$timestamp+'</div>'+
            '</div>'+
            '</li>';

        return $element
    }
</script>