<div class="" ><h4 style="display: inline; margin-right: 10px;">Primary Contact</h4></div>
@if (is_null($client->primarycontact_id))
    <br><br>No Primary Contact Set
@else
    @desktop
    <table class="table">
        <tr>
            <td>Name:</td>
            <td>{{ $client->primarycontact->firstname }} {{ $client->primarycontact->lastname }}</td>
            <td>Office Phone Number:</td>
            <td><a href="tel:{{ $client->primarycontact->officenumberRAW() }}">{{ $client->primarycontact->officenumber }}</a></td>
        </tr>
        <tr>
            <td>Address:</td>
            <td>{!! PageElement::GoogleAddressLink($client->primarycontact->address) !!}</td>
            <td>Mobile Phone Number:</td>
            <td><a href="tel:{{ $client->primarycontact->mobilenumberRAW() }}">{{ $client->primarycontact->mobilenumber }}</a></td>
        </tr>
        <tr>
            <td>Email:</td>
            <td>{!! PageElement::EmailLink($client->primarycontact->email) !!}</td>
            <td>Home Phone Number:</td>
            <td><a href="tel:{{ $client->primarycontact->homenumberRAW() }}">{{ $client->primarycontact->homenumber }}</a></td>
        </tr>
    </table>
    @elsedesktop
    <table class="table">
        <tr>
            <td>Name:</td>
            <td>{{ $client->primarycontact->firstname }} {{ $client->primarycontact->lastname }}</td>
        </tr>
        <tr>
            <td>Address:</td>
            <td>{!! PageElement::GoogleAddressLink($client->primarycontact->address) !!}</td>
        </tr>
        <tr>
            <td>Email:</td>
            <td>{!! PageElement::EmailLink($client->primarycontact->email) !!}</td>
        </tr>
        <tr>
            <td>Office Phone Number:</td>
            <td><a href="tel:{{ $client->primarycontact->officenumberRAW() }}">{{ $client->primarycontact->officenumber }}</a></td>
        </tr>
        <tr>
            <td>Mobile Phone Number:</td>
            <td><a href="tel:{{ $client->primarycontact->mobilenumberRAW() }}">{{ $client->primarycontact->mobilenumber }}</a></td>
        </tr>
        <tr>
            <td>Home Phone Number:</td>
            <td><a href="tel:{{ $client->primarycontact->homenumberRAW() }}">{{ $client->primarycontact->homenumber }}</a></td>
        </tr>
    </table>
    @enddesktop
@endif

@include('Clients.view.modals.primarycontact')