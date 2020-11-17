@extends('master')

@section('content')
<style>
    .signing-toolbar{
        width: 100%;
        height: 36px;
    }

    .signing-editor{
        width: 100%;
        height: 100%;
    }

    @desktop
    .signing-page-container{
        width: 100%;
        height: calc(100% - 96px - 36px);
        background-color: black;
        padding: 3cm;
        overflow: hidden;
        overflow-y: scroll;
    }
    @elsedesktop
    .signing-page-container{
        width: 100%;
        height: calc(100% - 96px - 36px);
        background-color: black;
        padding: 5px;
        overflow: hidden;
        overflow-y: scroll;
    }
    @enddesktop

    .signing-page{
        width: 100%;
        height: 100%;
        background-color: white;
        background-image: url('/images/loading4.gif');
        background-repeat: no-repeat;
        background-size: contain;
        position: relative;
    }

    .sigining-signature-marker{
        width: 232px;
        height: 55px;
        position: absolute;
        top: 100px;
        left: 100px;
        background-color: transparent;

        background-repeat: no-repeat;
        background-size: contain;
        background-position: center;
        border: black;
        border-style: solid;
        border-width: 1px;
        text-align: center;
        font-size: 1.8em;
    }
    canvas{
        float: left;
    }


    .ui-resizable-handle {
        position: absolute;
        font-size: 0.1px;
        display: block;
    }
    .ui-resizable-disabled .ui-resizable-handle,
    .ui-resizable-autohide .ui-resizable-handle {
        display: none;
    }
    .ui-resizable-n {
        cursor: n-resize;
        height: 7px;
        width: 100%;
        top: -5px;
        left: 0;
    }
    .ui-resizable-s {
        cursor: s-resize;
        height: 7px;
        width: 100%;
        bottom: -5px;
        left: 0;
    }
    .ui-resizable-e {
        cursor: e-resize;
        width: 7px;
        right: -5px;
        top: 0;
        height: 100%;
    }
    .ui-resizable-w {
        cursor: w-resize;
        width: 7px;
        left: -5px;
        top: 0;
        height: 100%;
    }
    .ui-resizable-se {
        cursor: se-resize;
        width: 12px;
        height: 12px;
        right: 1px;
        bottom: 1px;
    }
    .ui-resizable-sw {
        cursor: sw-resize;
        width: 9px;
        height: 9px;
        left: -5px;
        bottom: -5px;
    }
    .ui-resizable-nw {
        cursor: nw-resize;
        width: 9px;
        height: 9px;
        left: -5px;
        top: -5px;
    }
    .ui-resizable-ne {
        cursor: ne-resize;
        width: 9px;
        height: 9px;
        right: -5px;
        top: -5px;
    }



    #resizable {top:150px;left:150px; width: 150px; height: 150px; padding: 0.5em; }
    #resizable h3 { text-align: center; margin: 0; }
</style>

<div class="signing-editor">
    <div class="signing-toolbar">
        <button class="OS-Button btn" id="signing-add-signature">Add Signature Spot</button>
        <button class="OS-Button btn" data-toggle="modal" data-target="#signing-setup-summery-modal" style="float: right;">Save & Send this document to all Signatories</button>
    </div>

    <div class="signing-toolbar">
        <div style="width: 25%; float: left;">
            <button class="OS-Button btn" id="signing-page-back" style="width: 100%;" disabled>Go To Previous Page</button>
        </div>
        <div id="signing-page-paginate" style="text-align: center; width: 50%;  float: left;">
            Loading...
        </div>
        <div style="width: 25%;  float: left;">
            <button class="OS-Button btn" id="signing-page-next" style="width: 100%;" disabled>Go To Next Page</button>
        </div>
    </div>
    <div class="signing-page-container">
        <div class="signing-page" id="signing-page">



        </div>
    </div>
</div>

<div id="signing-setup-marker-options" class="dropdown clearfix">
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;">
        <li><a id="signing-setup-marker-options-delete" tabindex="-1" href="#">Delete</a></li>
        <li><a id="signing-setup-marker-options-contact" data-toggle="modal" data-target="#signing-setup-marker-set-signee-modal" tabindex="-1" href="#">Set Signee</a></li>
    </ul>
</div>
@include('OS.Signing.elements.rightclickmenu')
@include('OS.Signing.elements.summerypopup')

<input id="report-id" val="0" style="display: none;">

<script src="{{ url('/includes/pdfjs/pdf.js') }}"></script>
<script src="{{ url('/includes/pdfjs/pdf.worker.js') }}"></script>
<script src="{{ url('\includes\textFit.min.js') }}"></script>
<script>

    var __PDF_DOC = null,
        __CANVAS = [],
        __PAGES = [],
        __CURRENT_PAGE = 1,
        __TOTAL_PAGES = 0,
        __PAGE_RENDERING_IN_PROGRESS = 0,
        __BROWSING_PAGE = 1;

    $(document).ready(function() {

        @if(Auth::user()->GetOption('Signing-Setup-Tutorial-v1') === "")
        $.confirm({
            title: 'Tutorial.',
            columnClass: 'col-md-12',
            content: '<p>We strongly suggest watching our brief video for eSigning through OficeSweeet.</p>' +
                        '<p>Use the button at the top of the page to add signature spots to a page.</p>' +
                        '<p>Click (Or Tap on mobile) and drag a signature spot to move a signature spot around and rezise.</p>' +
                        '<p>Right Click (Or Double Tap on mobile) a signature spot to delete or set the Signee.',
            buttons: {
                "Open Video Tutorial": function () {

                    $('#ShowHelpHub').data('tab', 'video');
                    $('#ShowHelpHub').data('url', 'bu4weO27LRk');

                    $('#ShowHelpHub').modal('show');
                },
                "Don't Show me this again": function () {
                    $data = {};
                    $data['_token'] = "{{ csrf_token() }}";
                    $data['key'] = 'Signing-Setup-Tutorial-v1';
                    $data['value'] = 1;

                    $("body").addClass("loading");
                    $post = $.post("/Account/Option", $data);

                    $post.done(function (data) {

                        switch(data['status']) {
                            case "OK":
                                @if($report->GetType() === "client")
                                    GoToPage("/Clients/View/{{ $report->client_id }}/file");
                                @endif
                                @if($report->GetType() === "vendor")
                                    GoToPage("/Vendor/View/{{ $report->vendor_id }}/file");
                                @endif
                                @if($report->GetType() === "employee")
                                    GoToPage("/Employees/View/{{ $report->user_id }}/email-docs");
                                @endif
                                break;
                            default:
                                $("body").removeClass("loading");
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
                },
                "Close": function () {

                }
            }
        });
        @endif

        $('#report-id').val({{ $report->id }});

        var pdfAsArray = convertDataURIToBinary();

        //This is where you start
        PDFJS.getDocument(pdfAsArray).then(function(pdf) {

            //Set PDFJS global object (so we can easily access in our page functions
            __PDF_DOC = pdf;

            //How many pages it has
            __TOTAL_PAGES = pdf.numPages;

            //Start with first page
            pdf.getPage( 1 ).then( handlePages );
        });

        $('#signing-add-signature').click(function () {
            $object = $('<div class="sigining-signature-marker">Sign And Date Here</div>').appendTo($('#signing-page'));
            $object.data('page', __BROWSING_PAGE);
            $object.draggable({
                                    containment: "#signing-page",
                                    scroll: false,
                                    start: function( event, ui ) { CloseRightClickMenu();},
                                    stop: (event, ui) => {
                                        //UpdateMarker(ui.helper);
                                    }
                                })
                                .resizable({
                                    containment: "#signing-page",
                                    create: (event, ui) => {
                                        //textFit(ui.helper[0]);
                                    },
                                    resize: (e, ui) => {
                                        CloseRightClickMenu();
                                        if(ui['element'].width() < 240){
                                            ui['element'].css('line-height', ui['element'].height() / 2 + 'px');
                                        }else{
                                            ui['element'].css('line-height', ui['element'].height() + 'px');
                                        }

                                    },
                                    stop: (event, ui) => {
                                        //UpdateMarker(ui.helper);
                                    }
                                });

            $object.css('line-height', $object.height() + 'px');
            $object.on('doubletap',function(event){
                event.preventDefault();
                $("#signing-setup-marker-options").css({
                    display: "block",
                    left: event.clientX,
                    top: event.clientY
                });

                window.selectedelement = $(this);
            });
            //textFit($object[0],  {alignHoriz: true, alignVert: true});

        });

        (function($){

            $.event.special.doubletap = {
                bindType: 'touchend',
                delegateType: 'touchend',

                handle: function(event) {
                    var handleObj   = event.handleObj,
                        targetData  = jQuery.data(event.target),
                        now         = new Date().getTime(),
                        delta       = targetData.lastTouch ? now - targetData.lastTouch : 0,
                        delay       = delay == null ? 300 : delay;

                    if (delta < delay && delta > 30) {
                        targetData.lastTouch = null;
                        event.type = handleObj.origType;
                        ['clientX', 'clientY', 'pageX', 'pageY'].forEach(function(property) {
                            event[property] = event.originalEvent.changedTouches[0][property];
                        })

                        // let jQuery handle the triggering of "doubletap" event handlers
                        handleObj.handler.apply(this, arguments);
                    } else {
                        targetData.lastTouch = now;
                    }
                }
            };

        })(jQuery);


        $('#signing-page-next').click(function (){
            $('canvas').eq(__BROWSING_PAGE -1).css('display', 'none');
            __BROWSING_PAGE = __BROWSING_PAGE + 1;
            $('canvas').eq(__BROWSING_PAGE -1).css('display', 'block');

            UpdatePage();
        });

        $('#signing-page-back').click(function (){
            $('canvas').eq(__BROWSING_PAGE -1).css('display', 'none');
            __BROWSING_PAGE = __BROWSING_PAGE - 1;
            $('canvas').eq(__BROWSING_PAGE -1).css('display', 'block');

            UpdatePage();
        });
    });

    function handlePages(page)
    {

        //This gives us the page's dimensions at full scale
        //var scale_required = $('#signing-page').width() / page.getViewport(1).width;

        var viewport = page.getViewport( 2 );

        //We'll create a canvas for each page to draw it on
        __CANVAS[__CURRENT_PAGE - 1] = document.createElement( "canvas" );
        __CANVAS[__CURRENT_PAGE - 1].style.display = "block";
        var context =  __CANVAS[__CURRENT_PAGE - 1].getContext('2d');
        __CANVAS[__CURRENT_PAGE - 1].height = viewport.height;
        __CANVAS[__CURRENT_PAGE - 1].width = viewport.width;

        __PAGE_RENDERING_IN_PROGRESS =  __PAGE_RENDERING_IN_PROGRESS + 1;
        console.log("START_RENDER_IN_PROGRESS: " +  __PAGE_RENDERING_IN_PROGRESS);
        //Draw it on the canvas
        page.render({canvasContext: context, viewport: viewport}).then(function () {
            $('canvas').each(function ($index, $value) {
                $(this).css('display','none');
            });
            $('canvas').first().css('display', 'block');

            $('#signing-page').height($('canvas').first().height());

            $('#signing-page-paginate').html('Page 1 of ' + __TOTAL_PAGES);
            if(__TOTAL_PAGES > 1){
                $('#signing-page-next').prop('disabled', false);
            }
        });

        //Add it to the web page
        $('#signing-page').append( __CANVAS[__CURRENT_PAGE - 1] );

        $(__CANVAS[__CURRENT_PAGE - 1]).css("width", "100%");
        $(__CANVAS[__CURRENT_PAGE - 1]).css("height", $(__CANVAS[__CURRENT_PAGE - 1]).width() * 1.291666666666667 + "px");

        //Move to next page
        __CURRENT_PAGE++;
        if ( __PDF_DOC !== null && __CURRENT_PAGE <= __TOTAL_PAGES )
        {
            __PDF_DOC.getPage( __CURRENT_PAGE ).then( handlePages );
        }

    }

    function convertDataURIToBinary() {
        //var base64Index = dataURI.indexOf(BASE64_MARKER) + BASE64_MARKER.length;
        //var base64 = dataURI.substring(base64Index);
        var base64 = "{{ $pdf }}";
        var raw = window.atob(base64);
        var rawLength = raw.length;
        var array = new Uint8Array(new ArrayBuffer(rawLength));

        for(var i = 0; i < rawLength; i++) {
            array[i] = raw.charCodeAt(i);
        }
        return array;
    }

    function UpdatePage() {
        $('#signing-page-paginate').html('Page ' + __BROWSING_PAGE + ' of ' + __TOTAL_PAGES);

        if(__BROWSING_PAGE === 1){
            $('#signing-page-back').prop('disabled', true);
        }else{
            $('#signing-page-back').prop('disabled', false);
        }

        if(__BROWSING_PAGE === __TOTAL_PAGES){
            $('#signing-page-next').prop('disabled', true);
        }else{
            $('#signing-page-next').prop('disabled', false);
        }

        $('.sigining-signature-marker').each(function () {
            if($(this).data('page') ===  __BROWSING_PAGE){
                $(this).css('display' , 'block');
            }else{
                $(this).css('display' , 'none');
            }
        });
    }
</script>
@stop