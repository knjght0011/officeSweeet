<style>
#external-events {
        float: left;
        width: 100%;
        padding: 0 10px;
        border: 1px solid #ccc;
        background: #eee;
        text-align: left;
}

#external-events h4 {
        font-size: 16px;
        margin-top: 0;
        padding-top: 1em;
}

#external-events .fc-event {
        margin: 10px 0;
        cursor: pointer;
}

#external-events p {
        margin: 1.5em 0;
        font-size: 11px;
        color: #666;
}

#external-events p input {
        margin: 0;
        vertical-align: middle;
}
</style>
<script>
$( document ).ready(function() {

    $('#external-events .fc-event').each(function() {

        // store data so the calendar knows to render an event upon drop
        $(this).data('event', {
                title: $.trim($(this).text()), // use the element's text as the event title
                stick: true // maintain when user navigates (see docs on the renderEvent method)
        });

        // make the event draggable using jQuery UI
         $(this).draggable({
                zIndex: 999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
        });
    });

    $('#datepicker').datepicker({
        changeMonth: true,
        changeYear: true,
        inline: true,
        onSelect: function(dateText, inst) {
            var d = new Date(dateText);
            $('#calendar').fullCalendar('gotoDate', d);
            $datetest = formatDate(d);
            $("#datepicker").val($datetest);
        },
        beforeShow: function(){
            @desktop
            @elsedesktop
            $(".ui-datepicker").attr('style', 'font-size: small !important;');
            @enddesktop
        }
    });

    @if(isset($view))
        $('#schedule-view').val('{{ $view }}');
    @endif

    $('#schedule-view').change(function(){
        $('#calendar').fullCalendar('changeView', $(this).val());

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['schedule-view'] = $(this).val();

    });
    
    $('#datepicker').val("Click here to select a date");

    @if(isset($date))
        var date = new Date({{ $date }}*1000);
        $momentdate = moment(date);
        $('#calendar').fullCalendar( 'gotoDate', $momentdate );
    @endif

    $('#new-event').click(function(){
        $("#NoteModal").data("data-event", "new");
        $('#NoteModal').modal('toggle');
    });

});

function BuildEvent(event){

    $newevent = {};

    if("id" in event){
        $newevent['id'] = event['id'];
    }else{
        $newevent['id'] = 0;
    }

    $newevent['title'] = event['title'];
    $newevent['start'] = event['start'].format('YYYY-MM-DD HH:mm:ss');
    $newevent['userid'] = event['resourceId'];

    if(event['end'] === null){
        $newevent['end'] = event['start'].add(2, 'hours').format('YYYY-MM-DD HH:mm:ss');
    }else{
        $newevent['end'] = event['end'].format('YYYY-MM-DD HH:mm:ss');
    }

    if("note" in event){
        $newevent['note'] = event['note'];
    }else{
        $newevent['note'] = "";
    }



    if("linkid" in event){
        $newevent['linkedid'] = event['linkid'];
        $newevent['linkedtype'] = event['linktype'];
        if(event['linktype'] == "patient"){
            $newevent['client_id'] = event['client_id'];
        }
    }else{
        $newevent['linkedid'] = 0;
        $newevent['linkedtype'] = 0;
    }


    if("reminderemails" in event){
        $newevent['reminderemails'] = event['reminderemails']
    }else{
        $newevent['reminderemails'] = [];
    }

    if("reminderdate" in event){
        $newevent['reminderdate'] = event['reminderdate']
    }else{
        $newevent['reminderdate'] = "Click here to set date.";
    }

    if("repeats" in event){
        $newevent['repeats'] = event['repeats'];
    }else{
        $newevent['repeats'] = 0;
    }

    if("repeat_freq" in event){
        $newevent['repeat_freq'] = event['repeat_freq'];
    }else{
        $newevent['repeat_freq'] = 0;
    }

    if("repeat_till" in event){
        $newevent['repeat_till'] = event['repeat_till'];
    }

    return $newevent;
}

function formatDate(date) {
        var monthNames = [
          "January", "February", "March",
          "April", "May", "June", "July",
          "August", "September", "October",
          "November", "December"
        ];

        var day = date.getDate();
        var monthIndex = date.getMonth();
        var year = date.getFullYear();

        return day + ' ' + monthNames[monthIndex] + ' ' + year;
  }
</script>
