<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="email-search" name="email-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('email') !!}
    </div>
    <div class="col-md-3">

    </div>
</div>

<table id="emailsearch" class="table">
    <thead>
        <tr id="head">
            <th>Type</th>
            <th>Status</th>
            <th>Last Sent</th>
            <th>Sent To</th>
            <th></th>
        </tr>
    </thead>
    <tfoot style="visibility: hidden;">
        <tr>
            <th>Type</th>
            <th>Status</th>
            <th>Last Sent</th>
            <th>Sent To</th>
            <th>            </th>
        </tr>
    </tfoot>
    <tbody>
    @php
        $email = $client->email?$client->email:$client->primarycontact->email
    @endphp
        @foreach($client->getEmails($email) as $email)
            <tr>
                <td>{{ preg_replace('/(?<!\ )[A-Z]/', ' $0', $email->type) }}</td>
                <td>{{ $email->Status() }}</td>
                <td>{{ $email->created_at }}</td>
                <td>{{ $email->email }}</td>
                <td>
                    <div class="dropdown" style="width: 100%;">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Options
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" style="margin-top: 0px;">
                            <li><a class="email-resend" data-id="{{ $email->id }}">Resend</a></li>
                            <li><a data-toggle="modal" data-target="#ShowPdfModel" data-title="View Email" data-url="/Email/Preview/{{ $email->id }}">View Email</a></li>
                            @if($email->attachment != null)
                            <li><a data-toggle="modal" data-target="#ShowPdfModel" data-title="View Document" data-url="/Email/Attachment/{{ $email->id }}">View Attachment</a></li>
                            @endif
                        </ul>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>


<script>
$(document).ready(function() {

    $('.email-resend').click(function (event) {

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['id'] = $(this).data('id');

        $("body").addClass("loading");

        $post = $.post("/Email/Resend", $data);

        $post.done(function (data) {
            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":
                    SavedSuccess('Email Sent');
                    break;
                case "notfound":
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Response from server. Please refresh the page and try again.'
                    });
                    break;
                case "notlogedin":
                    NotLogedIN();
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

    // DataTable
    var emailtable = $('#emailsearch').DataTable({
        "order": [[ 2, "desc" ]]
    });

    $( "#email-previous-page" ).click(function() {
        emailtable.page( "previous" ).draw('page');
        PageinateUpdate(emailtable.page.info(), $('#email-next-page'), $('#email-previous-page'),$('#email-tableInfo'));
    });

    $( "#email-next-page" ).click(function() {
        emailtable.page( "next" ).draw('page');
        PageinateUpdate(emailtable.page.info(), $('#email-next-page'), $('#email-previous-page'),$('#email-tableInfo'));
    });

    $('#email-search').on( 'keyup change', function () {
        emailtable.search( this.value ).draw();
        PageinateUpdate(emailtable.page.info(), $('#email-next-page'), $('#email-previous-page'),$('#email-tableInfo'));
    });

    PageinateUpdate(emailtable.page.info(), $('#email-next-page'), $('#email-previous-page'),$('#email-tableInfo'));

    $( "#emails" ).children().find(".dataTables_filter").css('display', 'none');
    $( "#emails" ).children().find(".dataTables_length").css('display', 'none');
    $( "#emails" ).children().find(".dataTables_paginate").css('display', 'none');
    $( "#emails" ).children().find(".dataTables_info").css('display', 'none');
    $('#emailsearch').css('width' , "100%");
} ); 
</script>