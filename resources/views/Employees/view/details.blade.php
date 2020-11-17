<div style="padding: 10px;">
    <table class="table">
        <tr>
            <td>ID:</td><td>{{ $employee->employeeid }}</td>
        </tr>
        <tr>
            <td>Name:</td><td>{{ $employee->firstname }} {{ $employee->middlename }} {{ $employee->lastname }}</td>
        </tr>
        <tr>
            <td>Address:</td><td>{!! PageElement::GoogleAddressLink($employee->address) !!}</td>
        </tr>
        <tr>
            <td>E-Mail:</td><td>{{ $employee->email }}</td>
        </tr>        
        <tr>
            <td>Phone Number:</td><td>{{ $employee->phonenumber }}</td>
        </tr>
        <tr>
            <td>Drivers License:</td><td>{{ $employee->driverslicense }}</td>
        </tr>
        <tr>
            <td>Branch:</td>
            @if($employee->branch_id !== null)
            <td>{{ $employee->branch->number }} {{ $employee->branch->address1 }} {{ $employee->branch->address2 }} {{ $employee->branch->city }} {{ $employee->branch->state }} {{ $employee->branch->zip }}</td>
            @else
            <td>None</td>
            @endif
        </tr>
        <tr>
            <td>Start Date:</td>
            @if($employee->start_date === null)
                <td>Not Set</td>
            @else
                <td>{{ $employee->start_date }}</td>
            @endif
        </tr>
        <tr>
            <td>End Date:</td>
            @if($employee->end_date === null)
                <td>Not Set</td>
            @else
                <td>{{ $employee->end_date }}</td>
            @endif
        </tr>

        <tr>
            <td>Emergency Contact Name:</td><td>{{ $employee->emergency_contact_name }}</td>
        </tr>

        <tr>
            <td>Emergency Contact Relationship:</td><td>{{ $employee->emergency_contact_relationship }}</td>
        </tr>

        <tr>
            <td>Emergency Contact Phone Number:</td><td>{{ $employee->emergency_contact_phone_number }}</td>
        </tr>
    </table>
</div>

<script>
$(document).ready(function() {

});
</script>