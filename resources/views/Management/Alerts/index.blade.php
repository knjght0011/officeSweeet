@extends('master')

@section('content')

<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="alert-search" name="alert-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('alert') !!}
    </div>

    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="alert-length"><div style="width: 7em;">Show:</div></span>
            <select id="alert-length" name="length" type="text" placeholder="choice" class="form-control">
                <option value="10">10 entries</option>
                <option value="25">25 entries</option>
                <option value="50">50 entries</option>
                <option value="100">100 entries</option>
            </select>
        </div>
    </div>
</div>

<table id="alertstable" class="table">
    <thead>
        <tr id="head">
            <th>Date</th>
            <th>Account</th>
            <th>LLS Client</th>
            <th>Type</th>
            <th>Details</th>
            <th>Action</th>
            
        </tr>
    </thead>
    <tbody>
        @foreach($alerts as $alert)
        <tr>
            <td>
                {{ $alert->created_at }}
            </td> 
            <td>
                @if($alert->account != null)
                <a id="link" href="/Accounts/{{ $alert->account_id }}">
                    {{ $alert->GetAccount()->subdomain }}
                </a>
                @else
                    Unknown
                @endif
            </td>
            <td>
                {!! $alert->GetAccount()->LLSclientLink() !!}
            </td>
            <td>
                {{ $alert->type }}
            </td>
            <td>
                <a id="link" href="/Alerts/{{ $alert->id }}">
                    Details
                </a>                
            </td>            
            <td>
                {{ $alert->ActionStageWord() }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
$(document).ready(function() {

    var alertsearch = $('#alertstable').DataTable(
        {
            "language": {
                "emptyTable": "No Data"
            },
            "order": [[ 0, "desc" ]]
        }
    );

    $( "#alert-previous-page" ).click(function() {
        alertsearch.page( "previous" ).draw('page');
        PageinateUpdate(alertsearch.page.info(), $('#alert-next-page'), $('#alert-previous-page'),$('#alert-tableInfo'));
    });

    $( "#alert-next-page" ).click(function() {
        alertsearch.page( "next" ).draw('page');
        PageinateUpdate(alertsearch.page.info(), $('#alert-next-page'), $('#alert-previous-page'),$('#alert-tableInfo'));
    });

    $('#alert-search').on( 'keyup change', function () {
        alertsearch.search( this.value ).draw();
        PageinateUpdate(alertsearch.page.info(), $('#alert-next-page'), $('#alert-previous-page'),$('#alert-tableInfo'));
    });

    $('#alert-length').on( 'change', function () {
        alertsearch.page.len( this.value ).draw();
        PageinateUpdate(alertsearch.page.info());
    });

    PageinateUpdate(alertsearch.page.info(), $('#alert-next-page'), $('#alert-previous-page'),$('#alert-tableInfo'));

    $(".dataTables_filter").css('display', 'none');
    $(".dataTables_length").css('display', 'none');
    $(".dataTables_paginate").css('display', 'none');
    $(".dataTables_info").css('display', 'none');
    $('#alertstable').css('width' , "100%");

});
</script>
@stop