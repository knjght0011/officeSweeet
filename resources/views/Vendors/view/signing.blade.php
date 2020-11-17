<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="signing-search" name="signing-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('signing') !!}
    </div>
    <div class="col-md-3">

    </div>
</div>

<table id="signingsearch" class="table">
    <thead>
        <tr id="head">
            <th>Name</th>
            <th>Created By</th>
            <th>Status</th>
            <th>Last Sent</th>
            <th>Sent To</th>
            <th></th>
        </tr>
    </thead>
    <tfoot style="visibility: hidden;">
        <tr>
            <th>Name</th>
            <th>Created By</th>
            <th>Status</th>
            <th>Last Sent</th>
            <th>Sent To</th>
            <th></th>
        </tr>
    </tfoot>
    <tbody>
        @foreach($vendor->signing as $sign)
            <tr>
                <td>{{ $sign->name }}</td>
                <td>{{ $sign->GetUserSentBy() }}</td>
                <td>{!! $sign->Status() !!}</td>
                <td>{{ $sign->updated_at }}</td>
                <td>{{ $sign->email }}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Options
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" style="margin-top: 0px;">
                            <li><a data-toggle="modal" data-target="#ShowPdfModel" data-title="View Document" data-url="/Signing/PDF/{{ $sign->id }}">View PDF</a></li>
                            <!--<li><a target="_blank">sdfsg</a></li>-->
                            <li><a data-toggle="modal" data-target="#doc-email-modal" data-reportid="{{ $sign->id }}" data-mode="resend">Resend</a></li>
                            @if($sign->sign === 1)
                                <li><a href="/Signing/Approve/{{ $sign->id }}">Approve Signatures</a></li>
                            @endif
                        </ul>
                    </div>
                </td>
            </tr>

        @endforeach
    </tbody>
</table>


@foreach($vendor->signing as $sign)
    @if($sign->sign === 1)
        <div id="signing-status-{{ $sign->id }}" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">
                            Signature Status
                        </h4>
                    </div>
                    <div class="modal-body">
                        <table id="" class="table">
                            <thead>
                            <tr id="head">
                                <th>Signature</th>
                                <th>Seen</th>
                                <th>Signed</th>
                                <th>Signee</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $pagenumber = 1;
                            @endphp
                            @foreach($sign->pages as $page)
                                @php
                                    $signaturenumber = 1;
                                @endphp
                                @foreach($page->signatures as $signature)
                                    <tr>
                                        <td>Page {{ $pagenumber }} Signature {{ $signaturenumber }}</td>
                                        <td>
                                            @if($signature->seen === null)
                                                Not Seen
                                            @else
                                                {{ $signature->seen }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($signature->signature === null)
                                                Not Signed
                                            @else
                                                Signed
                                            @endif
                                        </td>
                                        <td>
                                            @if($signature->Signee() === null)
                                                Unassigned
                                            @else
                                                {{ $signature->Signee()->firstname }} {{ $signature->Signee()->lastname }}
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                        $signaturenumber++;
                                    @endphp
                                @endforeach
                                @php
                                    $pagenumber++;
                                @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

<script>
$(document).ready(function() {


    // DataTable
    var signingtable = $('#signingsearch').DataTable({
        "order": [[ 2, "desc" ]]
    });

    $( "#signing-previous-page" ).click(function() {
        signingtable.page( "previous" ).draw('page');
        PageinateUpdate(signingtable.page.info(), $('#signing-next-page'), $('#signing-previous-page'),$('#signing-tableInfo'));
    });

    $( "#signing-next-page" ).click(function() {
        signingtable.page( "next" ).draw('page');
        PageinateUpdate(signingtable.page.info(), $('#signing-next-page'), $('#signing-previous-page'),$('#signing-tableInfo'));
    });

    $('#signing-search').on( 'keyup change', function () {
        signingtable.search( this.value ).draw();
        PageinateUpdate(signingtable.page.info(), $('#signing-next-page'), $('#signing-previous-page'),$('#signing-tableInfo'));
    });

    PageinateUpdate(signingtable.page.info(), $('#signing-next-page'), $('#signing-previous-page'),$('#signing-tableInfo'));

    $( "#signing" ).children().find(".dataTables_filter").css('display', 'none');
    $( "#signing" ).children().find(".dataTables_length").css('display', 'none');
    $( "#signing" ).children().find(".dataTables_paginate").css('display', 'none');
    $( "#signing" ).children().find(".dataTables_info").css('display', 'none');
    $('#signingsearch').css('width' , "100%");
} ); 
</script>