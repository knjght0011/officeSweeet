@extends('Emails.Templates.system')

@section('content')

<table class="table">
    <tbody>
        <tr>
            <td>Corporate Name</td>
            <td>{{ $data['tna-corporate-name'] }}</td>
        </tr> 
        <tr>
            <td>DBA Name </td>
            <td>{{ $data['tna-dba-name'] }}</td>
        </tr> 
        <tr>
            <td>Physical Business Address</td>
            <td>{{ $data['tna-physical-business-address'] }}</td>
        </tr> 
        <tr>
            <td>Business Phone</td>
            <td>{{ $data['tna-business-phone'] }}</td>
        </tr> 
        <tr>
            <td>Owner/Officer Name</td>
            <td>{{ $data['tna-owner-officer-name'] }}</td>
        </tr> 
        <tr>
            <td>Owner/Officer Email</td>
            <td>{{ $data['tna-owner-officer-email'] }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr> 
        <tr>
            <td>Federal Tax ID (EIN) Number</td>
            <td>{{ $data['tna-federal-tax-id-number'] }}</td>
        </tr> 
        <tr>
            <td>Business Type</td>
            <td>{{ $data['tna-business-type'] }}</td>
        </tr> 
        <tr>
            <td>Bank Name</td>
            <td>{{ $data['tna-bank-name'] }}</td>
        </tr> 
        <tr>
            <td>Bank Account Number</td>
            <td>{{ $data['tna-bank-account-number'] }}</td>
        </tr> 
        <tr>
            <td>Bank Routing Number</td>
            <td>{{ $data['tna-bank-routing-number'] }}</td>
        </tr>
        <tr>
            <td>Owner Home Address</td>
            <td>{{ $data['tna-owner-home-address'] }}</td>
        </tr> 
        <tr>
            <td>Owner Date of Birth</td>
            <td>{{ $data['tna-owner-date-of-birth'] }}</td>
        </tr> 
        <tr>
            <td>Owner Last 4 of Social Security Number</td>
            <td>{{ $data['tna-owner-last-4-of-social-security-number'] }}</td>
        </tr> 
        <tr>
            <td>Estimated Monthly Credit Card Sales ($)</td>
            <td>{{ $data['tna-estimated-monthly-credit-card-sales'] }}</td>
        </tr> 
        <tr>
            <td>Typical Average Transaction Amount ($)</td>
            <td>{{ $data['tna-typical-average-transaction-amount'] }}</td>
        </tr> 
        <tr>
            <td>Estimated Largest Single Transaction Amount ($)</td>
            <td>{{ $data['tna-estimated-largest-single-transaction-amount​'] }}</td>
        </tr>     
    </tbody>
</table>


@stop