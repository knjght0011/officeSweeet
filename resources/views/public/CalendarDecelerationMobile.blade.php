<script>


    /* initialize the calendar
    -----------------------------------------------------------------*/

    $('#calendar').fullCalendar({
        schedulerLicenseKey: '0766617147-fcs-1499112075',
        timezone: false,
        now: Date(),
        editable: true, // enable draggable events
        droppable: true, // this allows things to be dropped onto the calendar
        aspectRatio: 1.8,
        scrollTime: '07:00', // undo default 6am scrollTime
        titleFormat: 'dddd MMMM D, YYYY',
        eventSources: [
            {
                url: '/PublicSchedule/JsonFeed', // use the `url` property
                beforeSend: function (data) {
                    $('.calload').css('display','block');
                },
                error: function (data) {
                    $('.calload').css('display','none');
                },
                complete: function (data) {
                    $('.calload').css('display','none');
                    console.log("Complete: " + data.responseText);
                },
            },
        ],
        drop:

            function (date, jsEvent, ui, resourceId) {
                console.log('drop', date.format(), resourceId);

                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove();
                }


            }

        ,
        eventRender: function (event1, element) {
            element.bind('contextmenu', function (e) {
                $("#NoteModal").data("data-event", event1);
                $('#NoteModal').modal('toggle');
            });

            element.bind('dblclick', function () {
                $("#NoteModal").data("data-event", event1);
                $('#NoteModal').modal('toggle');
            });
            console.log(event1);
            if(event1['linkname'] != "None"){
                element.find('div.fc-title').html(event1['title'] + '<br />- ' + event1['linkname']);
            }
        }
        ,
        eventReceive: function (event) { // called when a proper external event is dropped
            if (event['title'] === "Custom Name") {
                event['title'] = prompt('Enter text here');

            }

            $('#calendar').fullCalendar('rerenderEvents');

            $newevent = BuildEvent(event);
            SaveEventData($newevent, event, false);
        }
        ,
        eventDrop: function (event) { // called when an event (already on the calendar) is moved
            $newevent = BuildEvent(event);
            SaveEventData($newevent, event, false);
        }
        ,
        eventResize: function (event) { // called when a proper external event is dropped
            $newevent = BuildEvent(event);
            SaveEventData($newevent, event, false);
        },

        defaultTimedEventDuration: '01:00:00',

        defaultView: '{{ $view }}',
            @if($view === "timelineDay")
                header: {
                    left: 'today prev,next',
                        center:'title',
                        right: 'timelineDay,timelineThreeDays,agendaWeek,month'
                },
            @endif
        views:{
        agendaFourDay: {
            type: 'agenda',
                duration:
                {
                    days: 1
                }
            }
        },
        resourceLabelText: 'Staff',
        resources:[
            @if($view === "agendaDay")
                @if(SettingHelper::GetSetting('companyname') != null)
            {
                id: '0', title: '{{ SettingHelper::GetSetting("companyname") }}'
            },
                @endif
            @else
            {id: '{{ $mobileresourceid }}', title: '{{ $mobileresourcetitle }}'},
            @endif
        ],

    });

    $(".fc-next-button").attr('style', 'font-size: medium !important;');
    $(".fc-prev-button").attr('style', 'font-size: medium !important;');
    $(".fc-today-button").attr('style', 'font-size: medium !important;');
    $(".fc-center h2").attr('style', 'font-size: medium !important;');

    $(".fc-timelineDay-button").attr('style', 'font-size: medium !important;');
    $(".fc-agendaWeek-button").attr('style', 'font-size: medium !important;');
    $(".fc-month-button ").attr('style', 'font-size: medium !important;');

</script>