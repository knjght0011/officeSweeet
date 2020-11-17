<div class="" ><h4 style="display: inline; margin-right: 10px;">Primary Contact</h4></div>
@if (is_null($vendor->primarycontact_id))
    <br><br>No Primary Contact Set
@else
    @desktop
    <table class="table">
        <tr>
            <td>Name:</td>
            <td>{{ $vendor->primarycontact->firstname }} {{ $vendor->primarycontact->lastname }}</td>
            <td>Office Phone Number:</td>
            <td><a href="tel:{{ $vendor->primarycontact->officenumberRAW() }}">{{ $vendor->primarycontact->officenumber }}</a></td>
        </tr>
        <tr>
            <td>Address:</td>
            <td>{!! PageElement::GoogleAddressLink($vendor->primarycontact->address) !!}</td>
            <td>Mobile Phone Number:</td>
            <td><a href="tel:{{ $vendor->primarycontact->officenumberRAW() }}">{{ $vendor->primarycontact->officenumber }}</a></td>
        </tr>
        <tr>
            <td>Email:</td>
            <td>{!! PageElement::EmailLink($vendor->primarycontact->email) !!}</td>
            <td>Home Phone Number:</td>
            <td><a href="tel:{{ $vendor->primarycontact->homenumberRAW() }}">{{ $vendor->primarycontact->homenumber }}</a></td>
        </tr>
    </table>
    @elsedesktop
    <table class="table">
        <tr>
            <td>Name:</td>
            <td>{{ $vendor->primarycontact->firstname }} {{ $vendor->primarycontact->lastname }}</td>
        </tr>
        <tr>
            <td>Address:</td>
            <td>{!! PageElement::GoogleAddressLink($vendor->primarycontact->address) !!}</td>
        </tr>
        <tr>
            <td>Email:</td>
            <td>{!! PageElement::EmailLink($vendor->primarycontact->email) !!}</td>
        </tr>
        <tr>
            <td>Office Phone Number:</td>
            <td><a href="tel:{{ $vendor->primarycontact->officenumberRAW() }}">{{ $vendor->primarycontact->officenumber }}</a></td>
        </tr>
        <tr>
            <td>Mobile Phone Number:</td>
            <td><a href="tel:{{ $vendor->primarycontact->officenumberRAW() }}">{{ $vendor->primarycontact->officenumber }}</a></td>
        </tr>
        <tr>
            <td>Home Phone Number:</td>
            <td><a href="tel:{{ $vendor->primarycontact->homenumberRAW() }}">{{ $vendor->primarycontact->homenumber }}</a></td>
        </tr>
    </table>
    @enddesktop
@endif

@include('Vendors.view.modals.primarycontact')