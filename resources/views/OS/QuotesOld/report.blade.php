@extends('master')

@section('content')
<style>
    .dataTables_filter {
        display: none;
    }
    .dataTables_length {
        display: none;
    }
    .dataTables_info {
        display: none;
    }

</style>
<div class="row" style="margin-top: 55px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="search" name="search" type="text" placeholder="" value="" class="form-control" data-validation-label="Search" data-validation-required="false" data-validation-type="">
        </div>
    </div>

    <div class="col-md-3">
        <button style="width: 100%;" id="EditQuote" class="btn OS-Button" type="button">Edit Selected Quote</button>
    </div>

    <div class="col-md-3">
        <button style="width: 100%;" id="ClientDetails" class="btn OS-Button" type="button">Selected Client Details</button>
    </div>

    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="length"><div style="width: 7em;">Show:</div></span>
            <select id="length" name="length" type="text" placeholder="choice" class="form-control">
                <option value="10">10 entries</option>
                <option value="25">25 entries</option>
                <option value="50">50 entries</option>
                <option value="100">100 entries</option>
            </select>
        </div>
    </div>
</div>

<table id="quotesearch" class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>client_id</th>
            <th>Quote Number</th>
            <th>Client</th>
            <th>Assigned To</th>
            <th>Created By</th>
            <th>Total</th>
            <th>Last Edited</th>
        </tr>
        <tr id="filterrow">
            <th class="filterheader">ID</th>
            <th class="filterheader">client_id</th>
            <th class="filterheader"></th>
            <th class="filterheader">
                <select style="width: 100%;" id="clientfilter" name="clientfilter" type="text" placeholder="choice" class="form-control">
                    <option value="all">All</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->getName() }}">{{ $client->getName() }}</option>
                    @endforeach
                </select>
            </th>
            <th class="filterheader">
                <select style="width: 100%;" id="assignedfilter" name="assignedfilter" type="text" placeholder="choice" class="form-control">
                    <option value="all">All</option>
                    <option value="None">None</option>
                    @foreach(UserHelper::GetAllUsers() as $user)
                        <option value="{{ $user->getShortName() }}">{{ $user->getShortName() }}</option>
                    @endforeach
                </select>
            </th>
            <th class="filterheader">
                <select style="width: 100%;" id="createdfilter" name="createdfilter" type="text" placeholder="choice" class="form-control">
                    <option value="all">All</option>
                    @foreach(UserHelper::GetAllUsers() as $user)
                        <option value="{{ $user->getShortName() }}">{{ $user->getShortName() }}</option>
                    @endforeach
                </select>
            </th>
            <th class="filterheader"></th>
            <th class="filterheader"></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>ID</th>
            <th>client_id</th>
            <th>Quote Number</th>
            <th>Client</th>
            <th>Assigned To</th>
            <th>Created By</th>
            <th>Total</th>
            <th>Last Edited</th>
        </tr>
    </tfoot>
    <tbody>
    @foreach($quotes as $quote)
        @if(count($quote->quoteitem) !== 0)
        <tr>
            <td>{{ $quote->id }}</td>
            <td>{{ $quote->client_id }}</td>
            <td>{{ $quote->getQuoteNumber() }}</td>
            <td>
                <a id="link" href="/Clients/View/{{ $quote->client->id }}">
                    {{ $quote->client->getName() }}
                </a>

            </td>
            <td>{{ $quote->client->assigned_to_user_name() }}</td>
            <td>{{ $quote->user->getShortName() }}</td>
            <td>${{ $quote->getTotal()  }}</td>
            <td>{{ $quote->formatDate_updated_at_iso() }}</td>
        </tr>
        @endif
    @endforeach
    </tbody>
</table>

@foreach($clients as $client)
<div class="modal fade ClientDetails" data-clientid ="{{ $client->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div style="margin: 2.5vh auto; width: 60vw" class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Client Details</h4>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#contactinfo{{ $client->id }}" aria-controls="profile" role="tab" data-toggle="tab">Contact Info</a></li>
                    <li role="presentation"><a href="#details{{ $client->id }}" aria-controls="profile" role="tab" data-toggle="tab">Details</a></li>
                    <li role="presentation"><a href="#notes{{ $client->id }}" aria-controls="profile" role="tab" data-toggle="tab">Notes</a></li>
                </ul>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="contactinfo{{ $client->id }}">
                        @if (is_null($client->primarycontact_id))
                            <br><br>No Primary Contact Set
                        @else
                            <table class="table">
                                <tr>
                                    <td>Name:</td> <td>{{ $client->primarycontact->firstname }} {{ $client->primarycontact->lastname }}</td> <td>Office Phone Number:</td> <td><a href="tel:{{ $client->primarycontact->officenumberRAW() }}">{{ $client->primarycontact->officenumber }}</a></td>
                                </tr>
                                <tr>
                                    <td>Address:</td> <td> {{ $client->primarycontact->address->number }} {{ $client->primarycontact->address->address1 }} {{ $client->primarycontact->address->address2 }} {{ $client->primarycontact->address->city }} {{ $client->primarycontact->address->state }} {{ $client->primarycontact->address->zip }}</td> <td>Mobile Phone Number:</td> <td><a href="tel:{{ $client->primarycontact->mobilenumberRAW() }}">{{ $client->primarycontact->mobilenumber }}</a></td>
                                </tr>
                                <tr>
                                    <td>Email:</td> <td>{!! PageElement::EmailLink($client->primarycontact->email) !!}</td> <td>Home Phone Number:</td> <td><a href="tel:{{ $client->primarycontact->homenumberRAW() }}">{{ $client->primarycontact->homenumber }}</a></td>
                                </tr>
                            </table>
                        @endif
                    </div>
                    <div role="tabpanel" class="tab-pane" id="details{{ $client->id }}">
                        <table class="table">
                            <tr>
                                <td>
                                    Date of Introduction
                                </td>
                                <td>
                                    {{ $client->date_of_introduction }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Current Solution
                                </td>
                                <td>
                                    {{ $client->current_solution }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Budget
                                </td>
                                <td>
                                    {{ $client->budget }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Decision Maker
                                </td>
                                <td>
                                    {{ $client->decision_maker }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Referral Source
                                </td>
                                <td>
                                    {{ $client->referral_source }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Assigned To
                                </td>
                                <td>
                                    {{ $client->assigned_to_user_name() }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Problem Pain
                                </td>
                                <td>
                                    {{ $client->problem_pain }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Resistance to Change
                                </td>
                                <td>
                                    {{ $client->resistance_to_change }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Priorities
                                </td>
                                <td>
                                    {{ $client->priorities }}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="notes{{ $client->id }}">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        Note
                                    </th>
                                    <th>
                                        Date
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($client->RecentNotes() as $note)
                                <tr>
                                    <td>
                                        {{ $note->note }}
                                    </td>
                                    <td>
                                        {{ $note->updated_at }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Save & Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
$(document).ready(function() {

    $('#search').on( 'keyup change', function () {
        quotesearch.search( this.value )
            .draw();
    });

    $('#clientfilter').on( 'change', function ( event ) {

        if(this.value === "all"){
            quotesearch
                .columns( 3 )
                .search( "" , true)
                .draw();
        }else{
            quotesearch
                .columns( 3 )
                .search( "^" + $(this).val() + "$", true, false, true)
                .draw();
        }

    });

    $('#assignedfilter').on( 'change', function ( event ) {

        if(this.value === "all"){
            quotesearch
                .columns( 4 )
                .search( "" , true)
                .draw();
        }else{
            quotesearch
                .columns( 4 )
                .search( "^" + $(this).val() + "$", true, false, true)
                .draw();
        }

    });

    $('#createdfilter').on( 'change', function ( event ) {

        if(this.value === "all"){
            quotesearch
                .columns( 5 )
                .search( "" , true)
                .draw();
        }else{
            quotesearch
                .columns( 5 )
                .search( "^" + $(this).val() + "$", true, false, true)
                .draw();
        }

    });

    $('#length').on( 'change', function () {
        quotesearch.page.len( this.value )
            .draw();

    });

    $('#ClientDetails').click(function () {
        $row = quotesearch.row('.selected').data();
        $('.ClientDetails').each(function(){
            if($(this).data("clientid").toString() === $row[1]){
                $(this).modal('show');
            }
        });
    });


    $('#EditQuote').click(function () {
        $row = quotesearch.row('.selected').data();

        GoToPage('/Clients/Quote/Edit/' + $row[0]);
    });

    // DataTable
    var quotesearch = $('#quotesearch').DataTable({
        "order": [[ 7, "desc" ]],
        "columnDefs": [
            {"targets": [0, 1], "visible": false}
        ],

    });

    $('#quotesearch tbody').on( 'click', 'tr', function () {
        $row = $(this);
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            quotesearch.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });

    $('.filterheader').off("click.DT");

    $('.nav-tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

});
</script>
@stop
