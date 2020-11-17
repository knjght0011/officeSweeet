



<div class="container" style="padding: 10px;">

    <div class="input-group ">
        <span class="input-group-addon" for="newSchedulerEvents"><div style="width: 7em;">New Event:</div></span>
        <input id="newSchedulerEvents" name="newSchedulerEvents" type="text" class="form-control">
        <span class="input-group-btn">
            <button class="btn OS-Button" type="button" id="saveSchedulerEvent" style="border-width: 1px;">Add</button>
        </span>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th>Name</th>
            <th class="col-md-2">Duration</th>
            <th class="col-md-2">Status</th>
        </tr>
        </thead>
        <tbody id="dragagbletablebody">
        @foreach($events as $event)
            <tr>
                <td>{{ $event->eventname }}</td>
                <td>
                    <div class="input-group clockpicker">
                        <input type="text" class="form-control eventdurationlist" data-id="{{ $event->id }}" value="{{ $event->duration }}" readonly data-autoclose="true"  data-placement="left" data-align="bottom" >
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div>
                </td>
                <td><button style="width: 100%;" class="btn OS-Button deleteeventbutton" type="button" data-id="{{ $event->id }}">Delete</button></td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {

        $('.eventdurationlist').clockpicker();

        $('.deleteeventbutton').click(function () {
            DragableDelete($(this));
        });

        $('.eventdurationlist').change(function () {
            DragableChangeDuration($(this));
        });


        $("#saveSchedulerEvent").click(function()
        {
            $("body").addClass("loading");
            $post = $.post("/Scheduling/DragableEvents/Save",
                {
                    _token: "{{ csrf_token() }}",
                    name: $('#newSchedulerEvents').val(),
                });

            $post.done(function( data )
            {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        $('#dragagbletablebody').append(DragableTableRow(data['id'], data['eventname'] ));

                        $('.deleteeventbutton').click(function () {
                            DragableDelete($(this));
                        });

                        $('.eventdurationlist').change(function () {
                            DragableChangeDuration($(this));
                        });

                        $('.eventdurationlist').clockpicker();
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

    function DragableDelete($button) {
        $("body").addClass("loading");

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['id'] = $button.data('id');

        $post = $.post("/Scheduling/DragableEvents/Delete", $data);

        $post.done(( data ) =>
        {
            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":
                    $p1 = $button.parent().parent().remove();

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
    }

    function DragableChangeDuration($select){
        $("body").addClass("loading");

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['id'] = $select.data('id');
        $data['duration'] = $select.val();

        $post = $.post("/Scheduling/DragableEvents/Duration", $data);

        $post.done(function (data) {
            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":

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

    function DragableTableRow($id, $name) {
        $element =  '<tr>'+
            '<td>'+ $name +'</td>'+
            '<td>'+
            '<div class="input-group clockpicker">'+
            '<input type="text" class="form-control eventdurationlist" data-id="'+$id+'" value="01:00" readonly data-autoclose="true"  data-placement="left" data-align="bottom">'+
            '<span class="input-group-addon">'+
            '<span class="glyphicon glyphicon-time"></span>'+
            '</span>'+
            '</div>'+
            '</td>'+
            '<td><button style="width: 100%;" class="btn OS-Button deleteeventbutton" type="button" data-id="'+$id+'">Delete</button></td>'+
            '</tr>';

        return $element;
    }
</script>