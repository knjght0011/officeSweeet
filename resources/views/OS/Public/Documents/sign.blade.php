@include('OS.Public.header')
<style>
    body{
        padding-left: 10%;
        padding-right: 10%;
        padding-top: 50px;
        padding-bottom: 50px;
        height: 100vh;
    }

    .modalload {
        display:    none;
        position:   fixed;
        z-index:    999999;
        top:        0;
        left:       0;
        height:     100%;
        width:      100%;
        background: rgba( 255, 255, 255, .8 )
        url('/images/loading4.gif')
        50% 50%
        no-repeat;
    }

    /* When the body has the loading class, we turn
   the scrollbar off with overflow:hidden */
    body.loading {
        overflow: hidden;
    }

    /* Anytime the body has the loading class, our
       modal element will be visible */
    body.loading .modalload {
        display: block;
    }

    .signing-page-container{
        width: calc(100% - 200px);
        height: calc(100% - 36px - 36px);
        background-color: black;
        padding: 3cm;
        overflow: hidden;
        overflow-y: scroll;
    }
    .signing-page{
        position: relative;
        width: 100%;
        height: 100%;
        background-color: white;
        background-repeat: no-repeat;
        background-size: contain;
    }

    #signing-list{
        height: calc(100% - 36px - 36px);
        width: 200px;
        background-color: white;
        overflow: hidden;
        overflow-y: auto;
        z-index: 10000;
        float: left;
    }

    #signing-list-list{
        list-style-type: none;
        padding: 5px;
    }

    .signing-list-item{
        margin: 5px;
        border: groove;
        border-radius: 5px;
        padding: 5px;
        text-align: center;
    }

    .signing-list-item:hover {
        background-color: lightblue;
    }

    .sigining-signature-marker{
        width: 200px;
        height: 100px;
        position: absolute;
        top: 100px;
        left: 100px;
        background-color: white;
        border: black;
        border-style: solid;
        border-width: 1px;
        text-align: center;
        font-size: 1.8em;
        float: left;
    }

    .sigining-signature-marker-notme{
        width: 200px;
        height: 100px;
        position: relative;
        top: 100px;
        left: 100px;
        background-color: white;
        border: black;
        border-style: solid;
        border-width: 1px;
        text-align: center;
        font-size: 1.8em;
    }

    .signing-toolbar{
        width: 100%;
        height: 36px;
    }

    #SignModal-text-display{
        border:1px solid #000000;
        width: 100%;
        height: calc(285px - 34px);
        line-height: calc(285px - 34px);
    }
    #SignModal-text-display{
        text-align: center;
        font-family: 'Herr Von Muellerhoff', cursive;
        font-size: 6em;
    }
</style>
<link href="https://fonts.googleapis.com/css?family=Herr+Von+Muellerhoff" rel="stylesheet">

<div class="signing-toolbar">
    <button class="btn OS-Button" id="signing-save" style="width: 100%;">Save & Return</button>
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

<div id="signing-list">
    <ul id="signing-list-list">
        @php
            $pagenumber = 1;
        @endphp
        @foreach($signing->pages as $page)
            @php
                $signaturenumber = 1;
            @endphp
            @foreach($page->signatures as $signature)
                <li class="signing-list-item" data-page="{{ $pagenumber }}" data-height="{{ $signature->top }}">
                    <p>Page {{ $pagenumber }} Signature {{ $signaturenumber }}</p>
                    <P>Signee: {{ $signature->Signee()->firstname }} {{ $signature->Signee()->lastname }}</P>
                </li>
                @php
                    $signaturenumber++;
                @endphp
            @endforeach
            @php
                $pagenumber++;
            @endphp
        @endforeach
    </ul>
</div>

<div class="signing-page-container" id="signing-page-container">
    <div class="signing-page" id="signing-page">

    </div>
</div>

<div class="modal fade" id="SignModal" tabindex="-1" role="dialog" aria-labelledby="SignModal" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Sign: Type Your Name Below</h4>
            </div>
            <div class="modal-body">
                <div class="input-group ">
                    <span class="input-group-addon" for="search"><div style="width: 35em;">
                        Last 4 digits of your:
                        <label class="radio-inline">
                            <input type="radio" name="SignModal-number-radio" value="1" checked>Social Security
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="SignModal-number-radio" value="2">Passport
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="SignModal-number-radio" value="3">Drivers License
                        </label>
                        </div>
                    </span>
                    <input id="SignModal-number-input" class="form-control">
                </div>
                <div class="input-group ">
                    <span class="input-group-addon" for="search"><div style="width: 13em;">Please type your signature:</div></span>
                    <input id="SignModal-text-input" class="form-control">
                </div>

                <div id="SignModal-text-display"></div>
            </div>
            <div class="modal-footer">
                <button id="SignModal-sign" name="SignModal-sign" type="button" class="btn OS-Button" value="">Sign & Date</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<input id="signing-id" style="display: none;">

<div class="modalload"><!-- Place at bottom of page --></div>

<script src="{{ url('\includes\textFit.min.js') }}"></script>
<script>

    var __PAGES = [],
        __TOTAL_PAGES = 0,
        __PAGE_WIDTH = 0,
        __PAGE_HEIGHT = 0,
        __PAGE_ASPECTRATIO = 0,
        __BROWSING_PAGE = 1
        __SAVED = false;

    window.onbeforeunload = function() {

        if (!__SAVED) {
            return false;
        }
    };


    $(document).ready(function() {

        $('.signing-list-item').click(function () {

            __BROWSING_PAGE = $(this).data('page');

            UpdatePage();

            $scrollTop = $(this).data('height') * __PAGE_HEIGHT;

            $('#signing-page-container')[0].scrollTop = $scrollTop;

        });

        SetupPage();

        $('#signing-page-next').click(function (){

            __BROWSING_PAGE = __BROWSING_PAGE + 1;

            UpdatePage();
        });

        $('#signing-page-back').click(function (){

            __BROWSING_PAGE = __BROWSING_PAGE - 1;

            UpdatePage();
        });

        $count = 0;
        $('.sigining-signature-marker').each(function ()
        {
            if($(this).data('signed') != true){
                $count = $count + 1;
            }
        });
        console.log($count);
        if($count === 0){
            $('#signing-save').prop("disabled", false);
        }else{
            $('#signing-save').prop("disabled", true);
        }


        $('#SignModal-text-input').keyup(function () {

            $('#SignModal-text-display').html($(this).val());

        });

        $('#SignModal').on('show.bs.modal', function (event) {
            var div = $(event.relatedTarget); // Button that triggered the modal

            $('#SignModal').data('sing-target', div);


        });

        $('#SignModal-sign').click(function () {

            $digits = $('#SignModal-number-input').val();
            $signature = $('#SignModal-text-input').val();

            if($digits.length != 4 || $signature.length === 0){
                $.dialog({
                    title: 'Oops...',
                    content: 'Please Fill in all the fields.'
                });
                throw new Error("Validation Error");
            }

            $digittype = $('input[name=SignModal-number-radio]:checked').val();

            $div = $('#SignModal').data('sing-target');

            $signature = $('#SignModal-text-input').val();

            $date = moment();

            $div.html($signature + '&nbsp; &nbsp;' + $date.format('MMM[,] DD YYYY'));

            $div.css('font-family', "'Herr Von Muellerhoff', cursive");
            $div.css('font-weight', "600");

            textFit($div[0],  {alignHoriz: true, alignVert: true});

            $div.data('signed', true);
            $div.data('signature', $signature);
            $div.data('date', $date.format('YYYY/MM/DD'));
            $div.data('digittype', $digittype);
            $div.data('digits', $digits);


            $('#SignModal').modal('hide');

            $count = 0;
            $('.sigining-signature-marker').each(function ()
            {
                if($(this).data('signed') != true){
                    $count = $count + 1;
                }
            });
            console.log($count);
            if($count === 0){
                $('#signing-save').prop("disabled", false);
            }

        });

        $('#signing-save').click(function () {


            $signatures = [];
            $count = 0;
            $('.sigining-signature-marker').each(function ()
            {
                if($(this).data('signed') != true){
                    $count = $count + 1;
                }else{
                    $signature = {};

                    $signature['id'] = $(this).data('id');
                    $signature['signed'] = $(this).data('signed');
                    $signature['signature'] = $(this).data('signature');
                    $signature['date'] = $(this).data('date');
                    $signature['digittype'] = $(this).data('digittype');
                    $signature['digits'] = $(this).data('digits');

                    $signatures.push($signature);
                }
            });

            if($count === 0 ){
                $("body").addClass("loading");

                $data = {};
                $data['_token'] = "{{ csrf_token() }}";
                $data['id'] = $('#signing-id').val();
                $data['signatures'] = $signatures;
                $data['signtoken'] = "{{ $signing->token }}";
                $data['contacttoken'] = "{{ $contact->token }}";

                $post = $.post("/Public/Document/Signing/SubmitSignatures", $data);

                $post.done(function (data) {
                    $("body").removeClass("loading");
                    switch(data['status']) {
                        case "OK":
                            __SAVED = true;
                            $('#signing-save').prop('disabled', 'true');
                            $.dialog({
                                title: 'Saved.',
                                content: 'Thank you for signing, the document creator will be in contact. (You can now close this page)'
                            });
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
                    $("body").removeClass("loading");
                });
            }else{
                //error
                $.dialog({
                    title: 'Oops...',
                    content: 'Please Sign all locations first.'
                });
            }

        });

    });

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

        I.onload = function(){
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
                        AddMarkerSigned({{ $signature->id }}, {{ $signature->width }}, {{ $signature->height }}, {{ $signature->top }}, {{ $signature->left }}, {{ $page->pageindex + 1 }}, "{{ $signature->Signature() }}", "{{ $signature->signeddate->toFormattedDateString() }}");
                    @endif
                @endforeach
            @endforeach


            if($('.sigining-signature-marker').length === 0){
                $('#signing-save').prop('disabled', 'true');
                __SAVED = true;
            }


            UpdatePage();
        };

        I.src = "{{ $signing->pages->first()->file }}";

    }

    function AddMarker($id, $width, $height, $top, $left, $page, $contact) {

        switch($contact) {
            @foreach($signing->AllSignees() as $signee)
                @if($signee->token === $contact->token)
            case "{{ $signee->id }}":
                $object = $('<div class="sigining-signature-marker" data-toggle="modal" data-target="#SignModal">CLICK HERE TO SIGN</div>').appendTo($('#signing-page'));
                break;
                @else
            case "{{ $signee->id }}":
                    $object = $('<div class="sigining-signature-marker-notme">Waiting for signature from {{ $signee->firstname }} {{ $signee->lastname }}</div>').appendTo($('#signing-page'));
                break;
                @endif
            @endforeach
            default:
                $object = $('<div class="sigining-signature-marker" data-toggle="modal" data-target="#SignModal">CLICK HERE TO SIGN</div>').appendTo($('#signing-page'));
        }

        $object.data('id', $id);
        $object.data('page', $page);
        $object.width($width * __PAGE_WIDTH);
        $object.height($height * __PAGE_HEIGHT);
        $object.css({top: $top * __PAGE_HEIGHT, left: $left * __PAGE_WIDTH});

        textFit($object[0],  {alignHoriz: true, alignVert: true});

        return $object;

    }

    function AddMarkerSigned($id, $width, $height, $top, $left, $page, $signature, $date) {

        $object = $('<div class="sigining-signature-marker-notme">' + $signature+ '&nbsp; &nbsp;' + $date + '</div>').appendTo($('#signing-page'));

        $object.css('font-family', "'Herr Von Muellerhoff', cursive");
        $object.css('font-weight', "600");

        $object.data('id', $id);
        $object.data('page', $page);

        $object.width($width * __PAGE_WIDTH);
        $object.height($height * __PAGE_HEIGHT);
        $object.css({top: $top * __PAGE_HEIGHT, left: $left * __PAGE_WIDTH});

        textFit($object[0],  {alignHoriz: true, alignVert: true});

        return $object;

    }
</script>
@include('OS.Public.footer')