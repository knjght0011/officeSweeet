
<div class="modal fade" id="signing-setup-summery-modal" tabindex="-1" role="dialog" aria-labelledby="SignModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Summary:</h4>
            </div>
            <div class="modal-body">
                <div id="signing-setup-summery-modal-noneassigned">
                    None of these signatures have been assigned to contacts, Who would you like to sign this document?
                    @foreach($contacts as $contact)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="signing-setup-marker-summary-radios" id="signing-setup-marker-summary-radios" data-contactid="{{ $contact->id }}" data-contactemail="{{ $contact->email }}">
                            <label class="form-check-label" for="signing-setup-marker-summary-radios">
                                {{ $contact->firstname }} {{ $contact->lastname }} ({{ $contact->email }})
                            </label>
                        </div>
                    @endforeach
                </div>
                <div id="signing-setup-summery-modal-assigned">

                </div>
            </div>
            <div class="modal-footer">
                <button id="signing-setup-save" name="signing-setup-save" type="button" class="btn OS-Button" value="">Save & Send</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#signing-setup-summery-modal').on('show.bs.modal', function (event) {

        $numberofsignatures = $('.sigining-signature-marker').length;

        $signees = {};

        $('.sigining-signature-marker').each( function () {

            if($(this).data('contactid') === undefined){
                if($signees['Unassigned'] === undefined){
                    $signees['Unassigned'] = 1;
                }else{
                    $signees['Unassigned'] = $signees['Unassigned'] + 1;
                }
            }else{
                if($signees[$(this).data('contactemail')] === undefined){
                    $signees[$(this).data('contactemail')] = 1;
                }else{
                    $signees[$(this).data('contactemail')] = $signees[$(this).data('contactemail')] + 1;
                }
            }

        });

        if($signees['Unassigned'] === $numberofsignatures){
            $('#signing-setup-summery-modal-noneassigned').css('display', 'block');
            $('#signing-setup-summery-modal-assigned').css('display', 'none');

            $('#signing-setup-summery-modal').data('mode', 'unassigned');


        }else{
            $('#signing-setup-summery-modal-noneassigned').css('display', 'none');
            $('#signing-setup-summery-modal-assigned').css('display', 'block');
            $('#signing-setup-summery-modal-assigned').empty();

            $('#signing-setup-summery-modal').data('mode', 'assigned');

            $('#signing-setup-summery-modal-assigned').append('<p>' + $numberofsignatures + ' Total Signatures</p>');

            $.each($signees, function ($key, $value) {

                $('#signing-setup-summery-modal-assigned').append('<p>' + $value + ' Signatures for ' + $key + '</p>');

            });

            $('#signing-setup-summery-modal-assigned').append('<p>Unassigned Signatures can be signed by anybody.</p>');

        }

    });

    $('#signing-setup-save').click(function () {

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['report-id'] = $('#report-id').val();

        $data['pages'] = [];

        $('canvas').each(function () {

            $data['pages'].push(this.toDataURL());

        });

        $data['markers'] = [];

        $containerwidth = $('#signing-page').width();
        $containerheight = $('#signing-page').height();

        $('.sigining-signature-marker').each( function () {

            $temp = {};
            $leftstr = $(this).css('left');
            $topstr = $(this).css('top');
            $temp['left'] = parseFloat($leftstr.substring(0, $leftstr.length - 2)) / $containerwidth;
            $temp['top'] = parseFloat($topstr.substring(0, $topstr.length - 2)) / $containerheight;
            $temp['width'] = $(this).width() / $containerwidth;
            $temp['height'] = $(this).height()  / $containerheight;

            $temp['page'] = $(this).data('page');

            if($('#signing-setup-summery-modal').data('mode') === 'unassigned'){
                $temp['type'] = "{{ $report->GetType() }}";
                $temp['contactid'] = $('input[name=signing-setup-marker-summary-radios]:checked').data('contactid');
            }else{
                $temp['type'] = $(this).data('type');
                $temp['contactid'] = $(this).data('contactid');
            }

            $data['markers'].push($temp)

        });

        $("body").addClass("loading");
        $post = $.post("/Signing/SavePositions", $data);

        $post.done(function (data) {

            switch(data['status']) {
                case "OK":
                    switch("{{ $report->GetType() }}") {
                        case "client":
                            GoToPage('/Clients/View/{{ $report->client_id }}/file');
                            break;
                        case "vendor":
                            GoToPage('/Vendors/View/{{ $report->vendor_id }}/file');
                            break;
                        case "employee":
                            GoToPage('/Employees/View/{{ $report->user_id }}/file');
                            break;
                        default:
                            GoToPage('/');
                    }
                    break;
                case "noemails":
                    $("body").removeClass("loading");
                    $.dialog({
                        title: 'Oops...',
                        content: 'Please Select at least one email.'
                    });
                    break;
                case "notfound":
                    $("body").removeClass("loading");
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

});

function UpdateMarker($marker){



    $data = {};
    $data['_token'] = "{{ csrf_token() }}";
    $data['id'] = $marker.data('id');
    $data['signing_id'] = $('#signing-id').val();



    $("body").addClass("loading");
    $post = $.post("/Signing/SavePosition", $data);

    $post.done(function (data) {
        $("body").removeClass("loading");
        switch(data['status']) {
            case "OK":
                $marker.data('id', data['id']);
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
</script>