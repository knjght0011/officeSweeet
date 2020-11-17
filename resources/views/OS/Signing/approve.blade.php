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

    .signing-page-container{
        width: 100%;
        height: calc(100% - 96px - 36px);
        background-color: black;
        padding: 3cm;
        overflow: hidden;
        overflow-y: scroll;
    }
    .signing-page{
        width: 100%;
        height: 100%;
        background-color: white;
        background-image: url('data:image/jpeg;base64,{{ $signing->file }}');
        background-repeat: no-repeat;
        background-size: contain;
        position: relative;
    }

    .sigining-signature-marker{
        width: 200px;
        height: 100px;
        position: absolute;
        top: 100px;
        left: 100px;
        border: black;
        border-style: solid;
        border-width: 1px;
        text-align: center;
        font-size: 1.8em;
        background-color: transparent;
        font-family: 'Herr Von Muellerhoff', cursive;
        font-weight: 600;
    }

    .sigining-signature-marker-signature{

        float: left;
    }
    .sigining-signature-marker-date{
        width: 30%;
        height: 100%;
        float: left;
    }

    /* latin-ext */
    @font-face {
        font-family: 'Herr Von Muellerhoff';
        font-style: normal;
        font-weight: 400;
        src: local('Herr Von Muellerhoff Regular'), local('HerrVonMuellerhoff-Regular'), url(https://fonts.gstatic.com/s/herrvonmuellerhoff/v7/WBL6rFjRZkREW8WqmCWYLgCkQKXb4CAft0cz9KNo3Q.woff2) format('woff2');
        unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
    }
    /* latin */
    @font-face {
        font-family: 'Herr Von Muellerhoff';
        font-style: normal;
        font-weight: 400;
        src: local('Herr Von Muellerhoff Regular'), local('HerrVonMuellerhoff-Regular'), url(https://fonts.gstatic.com/s/herrvonmuellerhoff/v7/WBL6rFjRZkREW8WqmCWYLgCkQKXb4CAft0c99KM.woff2) format('woff2');
        unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
</style>

<div class="signing-editor">
    <div class="signing-toolbar">
        <button class="OS-Button btn" id="signing-approve-document" >Save & Send this document to all Signatories</button>
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

<input id="signing-id" style="display: none;">

<script src="{{ url('\includes\dom-to-image.min.js') }}"></script>
<script src="{{ url('\includes\textFit.min.js') }}"></script>
<script>

    var __PAGES = [],
        __TOTAL_PAGES = 0,
        __PAGE_WIDTH = 0,
        __PAGE_HEIGHT = 0,
        __PAGE_ASPECTRATIO = 0,
        __BROWSING_PAGE = 1;

    $(document).ready(function() {

        SetupPage();

        $('#signing-approve-document').click(function () {

            $("body").addClass("loading");

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['id'] = $('#signing-id').val();

            $post = $.post("/Signing/Approve", $data);

            $post.done(function (data) {
                switch(data['status']) {
                    case "OK":
                        GoToPage('{{ $signing->LinkToLink() }}');
                        break;
                    case "notready":
                        $("body").removeClass("loading");
                        $.dialog({
                            title: 'Oops...',
                            content: 'Please Approve all Signatures first.'
                        });
                        break;
                        $("body").removeClass("loading");
                    case "notfound":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
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
        });


        $('#signing-page-next').click(function (){

            __BROWSING_PAGE = __BROWSING_PAGE + 1;

            UpdatePage();
        });

        $('#signing-page-back').click(function (){

            __BROWSING_PAGE = __BROWSING_PAGE - 1;

            UpdatePage();
        });


    });

    function ApproveSignature($marker) {

        $marker.css('color', 'black');
        $marker.data('approved', true);

        $count = 0;
        $('.sigining-signature-marker').each(function ()
        {
            if($(this).data('approved') != true){
                $count = $count + 1;
            }
        });

        $marker.css("position", "static");
        $marker.css("border", "none");

        var node = document.getElementById('sigining-signature-marker-' + $marker.data('id'));

        domtoimage.toPng(node).then((dataUrl) => {

            $marker.css('color', 'green');
            $marker.css("position", "absolute");
            $marker.css("border", "black");
            $marker.css("border-style", "solid");
            $marker.css("border-width", "1px");

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['id'] = $marker.data('id');
            $data['image'] = dataUrl;

            $("body").addClass("loading");

            $post = $.post("/Signing/SignatureImage", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        $marker.data("approved", true);
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


        }).catch(function (error) {
            console.error('oops, something went wrong!', error);
        });

        if($count === 0){
            $('#signing-approve-document').prop("disabled", false);
        }else{
            $('#signing-approve-document').prop("disabled", true);
        }

    }

    function UpdatePage() {
        $('#signing-page').css('background-image', 'url('+ __PAGES[__BROWSING_PAGE - 1] +')' );

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
        $('.sigining-signature-marker-notme').each(function () {
            if($(this).data('page') ===  __BROWSING_PAGE){
                $(this).css('display' , 'block');
            }else{
                $(this).css('display' , 'none');
            }
        });
    }


    function SetupPage() {

        $('#signing-id').val("{{ $signing->id }}");

        __TOTAL_PAGES = {{ count($signing->pages) }};

        var I = new Image();

        I.onload = () => {

            __PAGE_ASPECTRATIO = I.height / I.width;
            __PAGE_WIDTH = $('#signing-page').width();
            __PAGE_HEIGHT = __PAGE_WIDTH * __PAGE_ASPECTRATIO;
            $('#signing-page').height(__PAGE_HEIGHT);

            @foreach($signing->pages as $page)
                __PAGES[{{ $page->pageindex }}] = "{{ $page->file }}";
                @foreach($page->signatures as $signature)
                    @if($signature->signature === null)
                    AddMarker({{ $signature->id }}, {{ $signature->width }}, {{ $signature->height }}, {{ $signature->top }}, {{ $signature->left }}, {{ $page->pageindex + 1 }}, "{{ $signature->SigneeID() }}");
                    @else
                    AddMarkerSigned({{ $signature->id }}, {{ $signature->width }}, {{ $signature->height }}, {{ $signature->top }}, {{ $signature->left }}, {{ $page->pageindex + 1 }}, "{{ $signature->Signature() }}", "{{ $signature->signeddate->toFormattedDateString()  }}", {{ var_export($signature->Approved()) }}, {{ $signature->digits }}, {{ $signature->digittype }});
                    @endif
                @endforeach
            @endforeach

            UpdatePage();

            $('.sigining-signature-marker').click(function () {

                if($(this).data('signed') === true) {
                    if($(this).data('approved') != true){

                        switch($(this).data('digittype')) {
                            case 1:
                                $text = "Last 4 digits of Social Security: " + $(this).data('digits');
                                break;
                            case 2:
                                $text = "Last 4 digits of Passport: " + $(this).data('digits');
                                break;
                            case 3:
                                $text = "Last 4 digits of Drivers License: " + $(this).data('digits');
                                break;
                        }

                        $.confirm({
                            title: 'Confirm Signature?',
                            content: $text,
                            buttons: {
                                confirm: () => {
                                    ApproveSignature($(this));
                                },
                                cancel: () => {

                                },
                            }
                        });
                    }
                }
            });
        };

        I.src = "{{ $signing->pages->first()->file }}";

    }

    function AddMarker($id, $width, $height, $top, $left, $page, $contact) {

        switch($contact) {
            @foreach($signing->AllSignees() as $signee)
            case "{{ $signee->id }}":
                $object = $('<div class="sigining-signature-marker" id="sigining-signature-marker-'+$id+'">Waiting for signature from {{ $signee->firstname }} {{ $signee->lastname }}</div>').appendTo($('#signing-page'));
                break;
            @endforeach
            default:
                $object = $('<div class="sigining-signature-marker" id="sigining-signature-marker-'+$id+'" data-toggle="modal" data-target="#SignModal">Waiting for signature</div>').appendTo($('#signing-page'));
        }

        $object.data('id', $id);
        $object.data('page', $page);
        $object.width($width * __PAGE_WIDTH);
        $object.height($height * __PAGE_HEIGHT);
        $object.css({top: $top * __PAGE_HEIGHT, left: $left * __PAGE_WIDTH});

        textFit($object[0],  {alignHoriz: true, alignVert: true});
        $('.textFitAlignVert').css('position', 'relative');

        return $object;

    }

    function AddMarkerSigned($id, $width, $height, $top, $left, $page, $signature, $date, $approved, $digits, $digittype) {

        $object = $('<div class="sigining-signature-marker" id="sigining-signature-marker-'+$id+'">&nbsp;' + $signature + '&nbsp; &nbsp;'+ $date +'</div>').appendTo($('#signing-page'));

        $object.data('id', $id);
        $object.data('page', $page);
        $object.data('signed', true);
        $object.data('digits', $digits);
        $object.data('digittype', $digittype);

        $object.width($width * __PAGE_WIDTH);
        $object.height($height * __PAGE_HEIGHT);
        $object.css({top: $top * __PAGE_HEIGHT, left: $left * __PAGE_WIDTH});
        $object.css('line-height', $object.height() + "px");

        if($approved){
            $object.css('color', 'green');
            $object.data("approved", true);
        }

        textFit($object[0],  {alignHoriz: true, alignVert: true});
        $('.textFitAlignVert').css('position', 'relative');

        return $object;

    }

</script>
@stop