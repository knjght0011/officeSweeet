@extends('pdf.Reports.master')
@section('content')

    @include('pdf.Reports.Inserts.header')

    @foreach($vendors as $vendor)
        <div class="page_break"></div>
        <div class="row"><h3 style="text-align: center;">{{ $vendor->getName() }}</h3></div>

        <table class="table">
            <tr>
                <td>PAYER'S name, street address, city or town, state or province, country, ZIP or foreign postal code and telephone no.</td>
                <td>{{ $vendor->getName() }}<br>{{ $vendor->address->AddressString() }}<br>{{ $vendor->phonenumber }}</td>
            </tr>
            <tr>
                <td>PAYER'S TIN</td>
                <td>{{ $vendor->tax_id_number }}</td>
            </tr>
            <tr>
                <td>RECIPIENT'S TIN</td>
                <td>{{ $vendor->tax_id_number }}</td>
            </tr>
            <tr>
                <td>RECIPIENT's name</td>
                <td></td>
            </tr>
            <tr>
                <td>Street Address (including apt. no.)</td>
                <td>{{ $vendor->address->number }} {{ $vendor->address->address1 }}</td>
            </tr>
            <tr>
                <td>City or town, state or povince, country, and zip or foreign postal code</td>
                <td>{{ $vendor->address->city }} {{ $vendor->address->state }} {{ $vendor->address->region }} {{ $vendor->address->zip }}</td>
            </tr>
            <tr>
                <td>Account number (see instructions)</td>
                <td></td>
            </tr>
            <tr>
                <td>FATCA filing requirement</td>
                <td></td>
            </tr>
            <tr>
                <td>2nd TIN no.</td>
                <td></td>
            </tr>
            <tr>
                <td>1 Rents</td>
                <td></td>
            </tr>
            <tr>
                <td>2 Royalties</td>
                <td></td>
            </tr>
            <tr>
                <td>3 Other income</td>
                <td></td>
            </tr>
            <tr>
                <td>4 Federal income tax withheld</td>
                <td></td>
            </tr>
            <tr>
                <td>5 Fishing boat proceeds</td>
                <td></td>
            </tr>
            <tr>
                <td>6 Medical and health care payments</td>
                <td></td>
            </tr>
            <tr>
                <td>7 Nonemployee compensation</td>
                <td>${{ number_format($expencetotals[$vendor->id], 2, ".", "") }}</td>
            </tr>
            <tr>
                <td>8 Substitute payments in lieu of dividends or interest</td>
                <td></td>
            </tr>
            <tr>
                <td>9 Payer made direct sales of $5,000 or more consumer products to a buyer (recipient) for resale</td>
                <td></td>
            </tr>
            <tr>
                <td>10 Crop insurance proceeds</td>
                <td></td>
            </tr>
            <tr>
                <td>11</td>
                <td></td>
            </tr>
            <tr>
                <td>12</td>
                <td></td>
            </tr>
            <tr>
                <td>13 Excess golden parachute payments</td>
                <td></td>
            </tr>
            <tr>
                <td>14 Gross proceeds paid to an attorney</td>
                <td></td>
            </tr>
            <tr>
                <td>15a Section 409A deferrals</td>
                <td></td>
            </tr>
            <tr>
                <td>15b Section 409A income</td>
                <td></td>
            </tr>
            <tr>
                <td>16 State tax withheld</td>
                <td></td>
            </tr>
            <tr>
                <td>17 State/Payer's state no.</td>
                <td></td>
            </tr>
            <tr>
                <td>18 State income</td>
                <td></td>
            </tr>
        </table>
    @endforeach

@stop
