<style>
    #note_container{
        position: fixed;
        padding: 0px 15px 0px 15px;
        width: 500px;
        bottom: 85px;
        right: 25px;
        display: none;
        border-radius: 5px;
        border-color: lightgray;
        border-width: 5px;
        border-style: double;
        z-index: 1000;
    }

    .unread{
        background-color: lightgrey;
    }
    .read{
        background-color: white;
    }

    #note-custom-search-input {
        background: #e8e6e7 none repeat scroll 0 0;
        margin: 0;
        padding: 10px;
    }

    #custom-search-input .note-search-query {
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

    .notification_list {
        margin-top: 3px;
        background: white;
    }

    .notification_list ul {
        border: 1px solid #dddddd;
    }

    .notification_list li {
        border-bottom: 1px solid #dddddd;
        padding: 6px;
    }

    .notification_list li:last-child {
        border-bottom: none;
    }

    .notification_list {
        height: 380px;
        overflow-x: hidden;
        overflow-y: auto;
    }

    .notification_list li:hover {
        background: #428bca none repeat scroll 0 0;
        color: #fff;
        cursor: pointer;
    }

</style>

<div id="note_container">
    <div class="chat_sidebar">
        <div class="row">
            <div id="note-custom-search-input">
                <button class="btn btn-link" id="note-mark-all-read">Mark All Read</button>
            </div>
            <div id="notification_list" class="notification_list">
                <ul class="list-unstyled list">

                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $('#note_container').click(function (e) {
            e.stopPropagation();
        });

        $('#notecircle').click(function (e) {
            e.stopPropagation();
            $('#chat_container').css('display', 'none');

            if($('#note_container').css('display') === "none"){
                $('#note_container').css('display', 'block');
            }else{
                $('#note_container').css('display', 'none');
            }
        });

        $('#notebar').click(function () {
            $('#chat_container').css('display', 'none');
            $('#thread_container').css('display', 'none');

            if($('#note_container').css('display') === "none"){
                $('#note_container').css('display', 'block');
            }else{
                $('#note_container').css('display', 'none');
            }
        });
    });

    $('#note-mark-all-read').click(function () {
        window._noteSystem.markallread();
    });

    function noteSystem(){

        this.notifications = {};

        this.receiveNotification = (notification) => {

            $data = this.NoteList.add({
                NotificationTitle: notification['title'],
                NotificationText: notification['short_text'],
                NotificationTime: notification['created_at'],
                id: notification['id']
            });

            if(notification['read_by_me'] === "unread"){
                $($data[0]["elm"]).addClass('unread');
            }

            this.NoteList.sort('NotificationTime', { order: "desc" });

            this.notifications[notification['id']] = notification;

            this.countunread();
        };

        this.markallread = () => {
            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['id'] = 'all';

            $post = $.post("/Notifications/MarkRead", $data);

            $post.done((data) => {
                if(data === "alldone"){
                    $.each(this.notifications, (index, value) => {
                        this.notifications[index]['read_by_me'] = "";
                    });

                    $('.unread').removeClass('unread');

                    this.countunread();
                }
            });
        };

        this.noteclick = ($element) => {

            $id = $element.data('id');

            if($element.hasClass('unread')){

                $data = {};
                $data['_token'] = "{{ csrf_token() }}";
                $data['id'] = $id;

                $post = $.post("/Notifications/MarkRead", $data);

                $post.done( (data) => {
                    if(data === "done"){
                        this.notifications[$id]['read_by_me'] = "";

                        $element.removeClass('unread');

                        this.countunread();
                    }
                });

            }

            $note = this.notifications[$id];

            //newcases here need to be added to android app too
            switch($note['code']) {

                case "popuplarge":
                    $.dialog({
                        title: $note['title'],
                        columnClass: 'col-md-12',
                        content: $note['text'],
                    });
                    break;
                case "tasklist":
                    @desktop
                    $('#ViewTasksModal').modal('show');
                    @elsedesktop
                    GoToPage('Tasklist');
                    @enddesktop
                    break;
                case "scheduler":
                    GoToPage('/Scheduling/View/timelineDay/' + $note['link']);
                    break;
                case "inventory":
                    GoToPage('/Reporting');
                    break;
                case "ticket":
                    window._ticketSystem.showMessages($note['link']);
                    break;
                case "docsigned":
                    GoToPage('/Signing/Approve/' + $note['link']);
                    break;
                case "link":
                    GoToPage($note['link']);
                    break;
                default:
                    $.dialog({
                        title: $note['title'],
                        content: $note['text'],
                    });
                    break;
            }
        };


        this.countunread = () => {
            $unread = 0;
            $.each(this.notifications, (index, value) => {
                if(value['read_by_me'] === "unread"){
                    $unread = $unread + 1;
                }
            });

            this.unread = $unread;
            this.updateicon();
        };

        this.updateicon = () => {

            if(this.unread === 0){
                $('#notecircleicon').css('color', 'gray');
                $('#noteunread').css('display', 'none');
            }else{
                $('#notecircleicon').css('color', 'red');
                $('#noteunread').css('display', 'block');
            }
            $('#noteunread').html(this.unread);

            if(Object.keys(this.notifications).length === 1){
                if(this.unread === 1){
                    $('#notearrow').css('display', 'inline-block');
                    $('#notearrowtext').css('display', 'block');
                }else{
                    $('#notearrow').css('display', 'none');
                    $('#notearrowtext').css('display', 'none');
                }
            }else{
                $('#notearrow').css('display', 'none');
                $('#notearrowtext').css('display', 'none');
            }
        };

        this.init = () => {

            var NoteListoptions = {
                valueNames: [
                    'NotificationTitle',
                    'NotificationText',
                    'NotificationTime',
                    { data: ['id'] },
                ],

                item:   '<li class="left clearfix notification" onclick="window._noteSystem.noteclick($(this));">'+
                            '<div class="chat-body clearfix">'+
                                '<div class="header_sec">'+
                                '<strong class="primary-font NotificationTitle"></strong> <strong class="pull-right NotificationTime"></strong></div>'+
                                '<div class="contact_sec">'+
                                '<strong class="primary-font NotificationText"></strong>'+
                                '</div>'+
                            '</div>'+
                        '</li>'
            };

            this.NoteList = new List('notification_list', NoteListoptions);

            $.get("/Notifications/data", (data) => {

                $.each(data, (index, value) => {

                    $data = this.NoteList.add({
                        NotificationTitle: value['title'],
                        NotificationText: value['short_text'],
                        NotificationTime: value['created_at'],
                        id: value['id']
                    });

                    if(value['read_by_me'] === "unread"){
                        $($data[0]["elm"]).addClass('unread');
                    }

                    this.notifications[value['id']] = value;
                });

                this.NoteList.sort('NotificationTime', { order: "desc" });

                this.countunread();

                if(this.notifications.length === 1){
                    if(this.unread === 1){
                        $('#notearrow').css('display', 'inline-block');
                        $('#notearrowtext').css('display', 'block');
                    }
                }
            });


        };

        this.init();

    }


</script>