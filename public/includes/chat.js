function chatSystem($myuserid){

    this.userid = $myuserid;

    this.threads = {};

    this.showChat = () => {
        $('#note_container').css('display', 'none');
        $('#thread_container').css('display', 'none');
        $('#chat_container').css('display', 'block');
    };

    this.hideChat = () => {
        $('#chat_container').css('display', 'none');
    };

    this.toggleChat = () => {
        if($('#chat_container').css('display') === "none"){
            this.showChat();
        }else{
            this.hideChat();
        }
    };

    this.receiveMessage = (newmessage) => {
        if(this.threads[newmessage['chat_threads_id']] === undefined){
            //new thread, get thread
            $.get("/Chat/thread/" + newmessage['chat_threads_id'],  (newthread) => {
                this.newThread(newthread);
            });

        }else{
            //thread exists, dump message into thread

            $thread = this.threads[newmessage['chat_threads_id']];

            $thread['chatmessage'].push(newmessage);

            var item = this.ThreadList.get('id', newmessage['chat_threads_id'])[0];

            if($('#thread_list').data('currentthread') != undefined) {
                if ($('#thread_list').data('currentthread').toString() === newmessage['chat_threads_id'].toString()) {
                    this.drawMessage(newmessage);
                    $('#chat_container_div').animate({scrollTop: $('#chat_container_div').prop("scrollHeight")}, 500);
                    //this.markThreadRead($thread);
                }else{
                    this.threads[$thread['id']]['unread'] = this.threads[$thread['id']]['unread'] + 1;
                    item.values({ThreadUnread: this.threads[$thread['id']]['unread']});
                }
            }else{
                this.threads[$thread['id']]['unread'] = this.threads[$thread['id']]['unread'] + 1;
                item.values({ThreadUnread: this.threads[$thread['id']]['unread']});
            }

            item.values({most_recent_message_date: newmessage['created_at']});

            this.ThreadList.sort('most_recent_message_date', { order: "desc" });

        }

        if (newmessage['user']['id'] != this.userid) {
            this.notify("New Chat Message From: " + newmessage['user']['name'], newmessage['message']);
        }

        this.updateIcon();

    };


    this.removeThread = (threadid) => {
        if(this.threads[threadid] === undefined){
            //no thread do nothing
        }else{
            this.deleteThread(threadid);
        }
    };

    this.receiveThread = (newthread) => {
        if(this.threads[newthread['id']] === undefined){
            this.newThread(newthread);
        }else{
            this.updateThread(newthread);
        }
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

    this.drawMessages = ($threadid, $listitem) => {

        
        $('#chat-area').empty();

        $('.message-selected').removeClass('message-selected');
        $($listitem).addClass('message-selected');

        if ($threadid != 0) {
            $thread = this.threads[$threadid];
            $.each($thread['chatmessage'], (index, value) => {
                this.drawMessage(value);
            });
        }

        $('#chat_container_div').animate({scrollTop: $('#chat_container_div').prop("scrollHeight")}, 500);

        $('#chat-message-subject').html($thread['subject']);

        this.markThreadRead($thread);

        $('#thread_list').data('currentthread', $threadid);
        $('#thread_list').data('currentuser', $($listitem).data('userid'));

    };

    this.drawMessage = ($message) => {
        if ($message['user']['id'].toString() === this.userid) {
            $('#chat-area').append(messageElementMe($message['user']['firstname'] + " " + $message['user']['lastname'], $message['message'], $message['created_at']));
        } else {
            $('#chat-area').append(messageElement($message['user']['firstname'] + " " + $message['user']['lastname'], $message['message'], $message['created_at']));
        }


    };

    this.newThread = ($thread) =>{

        this.threads[$thread['id']] = $thread;

        $unread = 0;

        $.each($thread['chatmessage'],($index, $message) => {
            if($message['readby'] === null){
                $unread = $unread + 1;
            }else{
                if($message['readby'][this.userid] != 1){
                    $unread = $unread + 1;
                }
            }
        });

        this.threads[$thread['id']]['unread'] = $unread;

        if($thread['chatparticipants'].length === 2 ){
            //normal convo

            if($thread['chatparticipants'][1]['user']['id'] === this.userid){
                $user = $thread['chatparticipants'][0]['user'];
            }else{
                $user = $thread['chatparticipants'][1]['user'];
            }

            this.ThreadList.add({
                ThreadPeople: $user['name'],
                ThreadName: $thread['subject'],
                id: $thread['id'],
                ThreadUnread: $unread,
                most_recent_message_date: $thread['most_recent_message_date']
            });


        }else{
            //multi user convisation

            $name = "";
            $.each( $thread['chatparticipants'], (index, $participant) => {
                if($participant['user']['is_me'].toString() === "0"){
                    $name += $participant['user']['firstname'] + ", ";
                }
            });

            $name = $name.substring(0, $name.length - 2);

            this.ThreadList.add({
                ThreadPeople: $name,
                ThreadName: $thread['subject'],
                id: $thread['id'],
                ThreadUnread: $unread,
                most_recent_message_date: $thread['most_recent_message_date']
            });

        }

        $('.thread').click( (e) => {
            $button =  e.currentTarget;
            this.drawMessages($($button).data('id'), $button);

        });

        this.ThreadList.sort('most_recent_message_date', { order: "desc" });
        this.updateIcon();
    };

    this.updateThread = ($thread) =>{

        this.threads[$thread['id']] = $thread;

        var item = this.ThreadList.get('id', $thread['id'])[0];

        $unread = 0;

        $.each($thread['chatmessage'], ($index, $message) => {
            if($message['readby'] === null){
                $unread = $unread + 1;
            }else{
                if($message['readby'][this.userid] != 1){
                    $unread = $unread + 1;
                }
            }
        });

        this.threads[$thread['id']]['unread'] = $unread;

        if($thread['chatparticipants'].length === 2 ){
            //normal convo

            if($thread['chatparticipants'][1]['user']['id'].toString() === this.userid){
                $user = $thread['chatparticipants'][0]['user'];
            }else{
                $user = $thread['chatparticipants'][1]['user'];
            }

            item.values({
                ThreadPeople: $user['name'],
                ThreadName: $thread['subject'],
                id: $thread['id'],
                ThreadUnread: $unread,
                most_recent_message_date: $thread['most_recent_message_date']['date']
            });


        }else{
            //multi user convisation

            $name = "";
            $.each( $thread['chatparticipants'], (index, $participant) => {
                if($participant['user']['is_me'].toString() === "0"){
                    $name += $participant['user']['firstname'] + ", ";
                }
            });

            $name = $name.substring(0, $name.length - 2);

            item.values({
                ThreadPeople: $name,
                ThreadName: $thread['subject'],
                id: $thread['id'],
                ThreadUnread: $unread,
                most_recent_message_date: $thread['most_recent_message_date']
            });
        }

        $('.thread').click( (e) => {
            $button =  e.currentTarget;
            this.drawMessages($($button).data('id'), $button);
        });

        this.ThreadList.sort('most_recent_message_date', { order: "desc" });
        this.updateIcon();
    };

    this.deleteThread = ($threadid) => {

        delete this.threads[$threadid];

        this.ThreadList.remove('id', $threadid);

        this.updateIcon();

    };

    this.markThreadRead = ($thread) =>{

        
        console.log("marking Read");
        $data = {};
        $data['_token'] = window.csrftoken;
        $data['thread'] = $thread['id'];

        $post = $.post("/Chat/thread/markread", $data);

        $post.done(function (data) {
            console.log("Done");
        });

        $post.fail(function () {
            console.log("error");
            NoReplyFromServer();
        });

        this.threads[$thread['id']]['unread'] = 0;

        var item = this.ThreadList.get('id', $thread['id'])[0];

        item.values({
            ThreadUnread: 0,
        });

        this.updateIcon();
    };

    this.updateIcon = () => {

        $totalunread = 0;
        $.each(this.threads, function ($index, $thread) {
            $totalunread = $totalunread + $thread['unread']
        });

        if($totalunread === 0){
            $('#chatcircleicon').css('color', 'gray');
            $('#chatunread').css('display', 'none');
        }else{
            $('#chatcircleicon').css('color', 'red');
            $('#chatunread').css('display', 'block');
        }
        $('#chatunread').html($totalunread);

    };

    this.init = () => {

        var ThreadListoptions = {
            valueNames: [
                'ThreadName',
                'ThreadPeople',
                'ThreadUnread' ,
                { data: ['id', 'most_recent_message_date' ] },
            ],

            item:   '<li class="left clearfix thread">' +
            '<div class="chat-body clearfix">' +
            '<div class="header_sec">' +
            '<strong class="ThreadName" ></strong>' +
            '<strong class="pull-right"></strong>' +
            '</div>' +
            '<div class="contact_sec">' +
            '<strong class="ThreadPeople" style="font-weight: normal;"></strong>' +
            '<span class="badge pull-right ThreadUnread"></span>' +
            '</div>' +
            '</div>' +
            '</li>'
        };

        this.ThreadList = new List('thread_list', ThreadListoptions);

        $.get("/Chat/threads",  (data) => {
            $.each(data, ($threadindex, $thread) => {
                //add to object
                this.newThread($thread);
            });

            this.updateIcon();
        });
    };

    this.init();

}

function messageElement($username, $message, $timestamp) {

    $element = '<li class="left clearfix">'+
        '<span class="chat-img1 pull-left">'+
        '<img src="/images/usersilhouete.jpg"alt="User Avatar" class="img-circle">'+
        '</span>'+
        '<div class="chat-body1 clearfix" >'+
        '<p style="background-color: lightgray; word-wrap: break-word; white-space: pre-wrap;"><b>'+$username+':</b><br>'+$message+'</p>'+
        '<div class="chat_time pull-right">'+$timestamp+'</div>'+
        '</div>'+
        '</li>';

    return $element
}

function messageElementMe($username, $message, $timestamp) {

    $element = '<li class="left clearfix admin_chat">'+
        '<span class="chat-img1 pull-right">'+
        '<img src="/images/usersilhouete.jpg"alt="User Avatar" class="img-circle">'+
        '</span>'+
        '<div class="chat-body1 clearfix" style="text-align: right;">'+
        '<p style="background-color: lightblue; word-wrap: break-word; white-space: pre-wrap;"><b>'+$username+':</b><br>'+$message+'</p>'+
        '<div class="chat_time pull-left">'+$timestamp+'</div>'+
        '</div>'+
        '</li>';

    return $element
}