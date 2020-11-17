<script>

    /* initialize the calendar
    -----------------------------------------------------------------*/
    function GetJsonURL()
    {
        date = new Date();
        $offset = date.getTimezoneOffset();

        return '/PublicSchedule/JsonFeed?timezone='+$offset;
    }

    $('#calendar').fullCalendar({

        schedulerLicenseKey: '0766617147-fcs-1499112075',
        timezone: false,
        now: Date(),
        editable: false, // enable draggable events
        droppable: false, // this allows things to be dropped onto the calendar
        aspectRatio: 1.8,
        scrollTime: '07:00', // undo default 6am scrollTime
        titleFormat: 'dddd MMMM D, YYYY',
        eventSources: [
            {
                url: GetJsonURL(), // use the `url` property
                beforeSend: function (data) {
                    $('.calload').css('display','block');
                },
                error: function (data) {
                    $('.calload').css('display','none');
                    console.log("Eerror: ");
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
            if(event1['linkname'] != "None" & event1['linkname'] != "patient"){
                element.find('div.fc-title').html(event1['title'] + '<br />- ' + event1['linkname']);
            }

        },
        defaultTimedEventDuration: '01:00:00',

        customButtons: {
            NewEventButton: {
                text: 'New Event',
                click: function() {
                    $("#NoteModal").data("data-event", "new");
                    $('#NoteModal').modal('toggle');
                }
            },
        },

        header: {
            left: 'today prev,next',
            center:'title',
            right: ''
        },

        @if(isset($view))
        defaultView: '{{ $view }}',
        @else
        defaultView: 'agendaDay',
        @endif

        views:
        {
            timelineDay: {
                type: 'timelineDay',
                resourceAreaWidth: '18%',
            },
            timelineThreeDays: {
            type: 'timeline',
            resourceAreaWidth: '18%',
            duration:
                {
                    days: 3
                }
            }
        },
        resourceLabelText: 'Staff',
        resourceGroupField: 'Department',
        resourcesInitiallyExpanded: false,
        resources:[

        ]

    });



    $(".fc-next-button").attr('style', 'font-size: medium !important;');
    $(".fc-prev-button").attr('style', 'font-size: medium !important;');
    $(".fc-today-button").attr('style', 'font-size: medium !important;');
    $(".fc-center h2").attr('style', 'font-size: medium !important;');

    $(".fc-timelineDay-button").attr('style', 'font-size: medium !important;');
    $(".fc-agendaWeek-button").attr('style', 'font-size: medium !important;');
    $(".fc-month-button ").attr('style', 'font-size: medium !important;');




</script>