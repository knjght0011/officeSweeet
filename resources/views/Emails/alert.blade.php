@extends('Emails.Templates.system')

@section('content')
<table class="table">
    <thead>
    <tr>
        <td>Type:</td><td>{{ $alert->type }}</td>
    </tr>
    <tr>
        <td>Description:</td><td>{{ $alert->description }}</td>
    </tr>
    <tr>
        <td>Subdomain:</td><td>{{ $alert->account->subdomain }}</td>
    </tr>
    <tr>
        <td>LLS Client:</td><td><a href="https://lls.officesweeet.com/Clients/View/{{ $alert->account->client_id }}">Here</a></td>
    </tr>
    <tr>
        <td>Link To Alert:</td><td><a href="https://lls.officesweeet.com/Alerts/{{ $alert->id }}">Here</a></td>
    </tr>
    <tr>
        <td>Need Action:</td>

    @if($alert->action_stage === null)
            <td>No</td>
    @else
            <td>Yes</td>
    @endif
    </tr>
    </thead>
</table>
@stop