

    <script src="https://js.pusher.com/4.2/pusher.min.js"></script>

    <style>

        #circlecontainer{
            position: fixed;
            margin:0 auto;
            bottom: 0px;
            left: 50%;
            margin-left: -60px;
            z-index: 1049;
        }

        .actioncircle{

            background-color: white;
            height: 50px;
            width: 50px;
            border-radius: 25px;
            border-color: lightgrey;
            border-style: double;
            border-width: 5px;

            float: left;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .actioncircleicon{
            width: 100%;
            height: 100%;
            font-size: 2em;
            text-align: center;
            padding-top: 5px;
            color: gray;
        }

        .actioncircleunread{
            background-color: red;
            color: white;
            width: 22px;
            height: 22px;
            border-radius: 11px;
            position: relative;
            top: -50px;
            right: -30px;
            text-align: center;
            display: none;

        }
         #notearrow{
             top: -125px;
             left: -8px;
             font-size: 50px;
             animation: blinker 3s linear infinite;
             color: red;
             display: none;
         }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }

        #notearrowtext{
            position: relative;
            top: -225px;
            left: -129px;
            font-size: 27px;
            color: red;
            width: 400px;
            animation: blinker 3s linear infinite;
            display: none;
        }
    </style>



    @include('OS.Chat.notifications')
    @include('OS.Chat.chatwindow')
    @include('OS.Chat.tickets')

    <div id="circlecontainer" >

        @if(app()->make('account')->plan_name != "SOLO")
        <div id="chatcircle" class="actioncircle">
            <span id="chatcircleicon" class="glyphicon glyphicon-comment actioncircleicon"></span>
            <div id="chatunread" class="actioncircleunread">1</div>
        </div>
        @endif

        <div id="notecircle" class="actioncircle">
            <span id="notecircleicon" class="glyphicon glyphicon-bell actioncircleicon"></span>
            <div id="noteunread" class="actioncircleunread">1</div>
            <span id="notearrow" class="glyphicon glyphicon-arrow-down"></span>
            <div id="notearrowtext">You have a new notification</div>
        </div>

        <div id="actioncircle" class="actioncircle" data-toggle="modal" data-target="#ShowHelpHub" data-tab="action">
            <span id="actioncircleicon" class="glyphicon glyphicon-plus actioncircleicon" style="padding-left: 3px; color: red;"></span>
        </div>
    </div>


<script src="{{ url('/includes/chat.js') }}"></script>
<script>
    $('body').click(function() {
        $('#note_container').css('display', 'none');
        $('#chat_container').css('display', 'none');
        $('#thread_container').css('display', 'none');
    });

    $(document).ready(function () {

        window.csrftoken = "{{ csrf_token() }}";

        window._ticketSystem = new ticketSystem($('#thread_container'));
        window._noteSystem = new noteSystem();
        window._chatSystem = new chatSystem("{{ Auth::user()->id }}");

        window._pusherHandler = new pusherHandler();

    });

    function pusherHandler(){

        this.threads = {};

        this.socket = new Pusher('433d9d443bf08a679fc9', {cluster: 'mt1',});

        this.channel = this.socket.subscribe('OfficeSweeetMessenger');

        this.channel.bind('{{ $subdomain }}-{{ Auth::user()->email }}', (push) => {

            console.log(push);

            switch(push['type']) {
                case "TicketMessage":
                    $.get("/Tickets/message/" + push['data'],  (newTicketMessage) => {
                        
                        window._ticketSystem.receiveMessage(JSON.parse(newTicketMessage));
                    });
                    break;

                case "TicketThread":
                    $.get("/Tickets/thread/" + push['data'],  (newTicketThread) => {
                        
                        window._ticketSystem.receiveThread(JSON.parse(newTicketThread));
                    });
                    break;

                case "TicketStatus":
                    window._ticketSystem.receiveStatus(push['data']);
                    break;

                case "Notification":
                    $.get("/Notifications/notification/" + push['data'],  (newnotification) => {
                        
                        window._noteSystem.receiveNotification(newnotification);
                    });
                    break;

                case "ChatMessage":
                    $.get("/Chat/message/" + push['data'],  (newmessage) => {
                        
                        window._chatSystem.receiveMessage(newmessage);
                    });
                    break;

                case "ChatThread":
                    $.get("/Chat/thread/" + push['data'],  (newthread) => {
                        
                        window._chatSystem.receiveThread(newthread);
                    });
                    break;

                case "ChatThreadDelete":
                    window._chatSystem.removeThread(push['data']);
                    break;

                default:
                    console.log('Opps?');
            }

        });

        this.init = () => {


        };

        this.init();

    }


</script>

