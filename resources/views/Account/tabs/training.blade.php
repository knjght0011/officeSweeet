<div style="position: relative; width: 50%; height: 90%; border: groove; border-radius: 5px; padding: 6px; float: left;">
    <div id='calendar' style="height: 100%; width: 100%;"></div>
    <div class="calload"></div>
</div>

<div id="info" style="width: 50%; height: 90%; border: groove; border-radius: 5px; padding: 6px; float: left;">
    <h1 style="text-align: center;">Click an event for details</h1>
</div>

<div class="modal fade" id="ShowLinkModel" tabindex="-1" role="dialog" aria-labelledby="ShowLinkModel" aria-hidden="true">
    <div style="margin: 2.5vh auto; width: 95vw" class="modal-dialog" role="document">
        <div style="height: 95vh; width: 95vw;" class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">View Link</h4>
            </div>
            <div style="height: calc(95vh - 120px);" class="modal-body">
                <iframe style="width: 100%; height: 100%;" id="ShowLinkFrame" src="{{ url('images/loading4.gif') }}"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>

    $('#ShowLinkModel').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        url = button.data('url');
        $('#ShowLinkFrame').attr("src", url);
    });


    $('#ShowLinkModel').on('hide.bs.modal', function (event) {
        $('#ShowLinkFrame').attr("src", "{{ url('images/loading4.gif') }}");
    });

    /* initialize the calendar
    -----------------------------------------------------------------*/

    $('#calendar').fullCalendar({
        schedulerLicenseKey: '0766617147-fcs-1499112075',
        timezone: false,
        now: Date(),
        editable: true, // enable draggable events
        droppable: true, // this allows things to be dropped onto the calendar
        height: "parent",
        scrollTime: '07:00', // undo default 6am scrollTime
        titleFormat: 'dddd MMMM D, YYYY',
        drop: function (date, jsEvent, ui, resourceId) {

        },
        eventRender: function (event1, element) {

        },
        eventReceive: function (event) { // called when a proper external event is dropped

        },
        eventDrop: function (event) { // called when an event (already on the calendar) is moved

        },
        eventResize: function (event) { // called when a proper external event is dropped

        },
        eventClick: function(calEvent, jsEvent, view) {
            console.log(calEvent);
            if(calEvent['linktype'] === "client") {
                //client/vendor event
                $('#info').html(SummeryHTMLWithLink(calEvent['start'].format('YYYY-MM-DD HH:mm:ss'), calEvent['end'].format('YYYY-MM-DD HH:mm:ss'), calEvent['title'], calEvent['note'], calEvent['linkname'], calEvent['linktype'], calEvent['linkid']));
            }else if(calEvent['linktype'] === "vendor"){
                //client/vendor event
                $('#info').html(SummeryHTMLWithLink(calEvent['start'].format('YYYY-MM-DD HH:mm:ss'), calEvent['end'].format('YYYY-MM-DD HH:mm:ss'), calEvent['title'], calEvent['note'], calEvent['linkname'], calEvent['linktype'], calEvent['linkid']));
            }else if(calEvent['linktype'] === "training"){
                //reserveed for training event
                $('#info').html(SummeryHTMLWithTraining(calEvent['start'].format('YYYY-MM-DD HH:mm:ss'), calEvent['title'], calEvent['note'], calEvent['linkname'], calEvent['traininglink'], calEvent['trainingquiz'], calEvent['trainingcomments']));
            }else{
                //normal event not linked to anything
                $('#info').html(SummeryHTMLNoLink(calEvent['start'].format('YYYY-MM-DD HH:mm:ss'), calEvent['end'].format('YYYY-MM-DD HH:mm:ss'), calEvent['title'], calEvent['note']));
            }
        },
        defaultTimedEventDuration: '01:00:00',
        header: {
            left: 'prev',
            center:'today',
            right: 'next'
        },
        buttonText: {
            next: 'Next',
            prev: 'Previous',
        },
        defaultView: 'listYear',
        resourceLabelText: 'Staff',
        resourceGroupField: 'Department',
        resourcesInitiallyExpanded: false,
        resources:[
            {
                id: '{{ Auth::user()->id }}',
                title: '{{ Auth::user()->getName() }}'
            }
        ],
        eventSources: [
            {
                url: '/Scheduling/TrainingFeed', // use the `url` property
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

    });

    function SummeryHTMLNoLink($start, $end, $title, $notes) {

        return '<legend>Event Details:</legend>'+
                '<P>Start: ' + $start  +'</P>'+
                '<P>End: ' + $end + '</P>'+
                '<P>Title: ' + $title + '</P>'+
                '<P>Notes: ' + $notes + '</P>';
    }

    function SummeryHTMLWithLink($start, $end, $title, $notes, $linkname, $linktype, $linkid) {

        return '<legend>Event Details:</legend>'+
                '<P>Start: ' + $start  +'</P>'+
                '<P>End: ' + $end + '</P>'+
                '<P>Title: ' + $title + '</P>'+
                '<P>Notes: ' + $notes + '</P>'+
                '<legend>Linked Account:</legend>'+
                '<P>Name: ' + $linkname + '</P>'+
                '<P>Type: ' + $linktype.charAt(0).toUpperCase() + $linktype.slice(1) + '</p>'+
                '<P>Link: <a href="/'+ $linktype.charAt(0).toUpperCase() + $linktype.slice(1) +'s/View/'+ $linkid + '">here</a></p>';
    }

    function SummeryHTMLWithTraining($start, $title, $notes, $linkname, $traininglink, $trainingquiz, $trainingcomments) {

        return '<legend>Event Details:</legend>'+
            '<P>Start: ' + $start  +'</P>'+
            '<P>Title: ' + $title + '</P>'+
            '<P>Notes: ' + $notes + '</P>'+
            '<legend>Linked Training:</legend>'+
            '<P>Name: ' + $linkname + '</P>'+
            '<P>Comments: ' + $trainingcomments + '</p>'+
            '<P>Training Link: <a target="_blank" href="'+ $traininglink + '">'+ $traininglink + '</a></p>'+
            '<P>Quiz Link: <a target="_blank" href="'+ $trainingquiz + '">'+ $trainingquiz + '</a></p>';
            //'<P>Link: <a ="#ShowLinkModel" data-toggle="modal" data-target="#ShowLinkModel" class="button" data-url="'+ $traininglink + '">'+ $traininglink + '</a></p>';
    }

</script>