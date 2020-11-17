@extends('pdf.Reports.master')

@section('content')


    @include('pdf.Reports.Inserts.header')
<style>
    .table td{
        border-top: none !important;
    }
</style>


    <div style="width: 100%;">



        <table class="table" style="text-align: left; width: 49%; margin-right: 1%;  float: left;">
            <tr style="background-color: lightblue;" >
                <td colspan="2"><b>Assets</b></td>
            </tr>
            @foreach($assets as $catagory => $subcatagorys)
                @if($catagory != "Depreciation")
                    @php
                        $totalcatagory = 0;
                    @endphp


                    <tr style="background-color: lightgrey;" >
                        <td class="col-md-10"><b>{{$catagory}}</b></td>
                        <td class="col-md-2"></th>
                    </tr>

                    @foreach($subcatagorys as $subcatagory => $value)
                        <tr >
                            <td class="col-md-10">{{ $subcatagory }}</td>
                            <td class="col-md-2">${{ number_format($value, 2) }}</td>
                        </tr>

                        @php
                            $totalcatagory = $totalcatagory + floatval($value);
                        @endphp
                    @endforeach

                    <tr style="background-color: white;" >
                        <td><b>Total {{ $catagory }}</b></td>
                        <td>${{ number_format($totalcatagory, 2) }}</td>
                    </tr>

                    <tr style="background-color: white;" >
                        <td class="col-md-10"> </td>
                        <td class="col-md-2"></th>
                    </tr>
                @endif
            @endforeach

                <tr style="background-color: lightgrey;" >
                    <td><b>Less Total Accumulated Depreciation</b></td>
                    <td>$-{{ number_format($totaldepriciation, 2) }}</td>
                </tr>

                <tr style="background-color: lightblue;" >
                    <td class="col-md-10"><b>Assets Total</b></td>
                    <td class="col-md-2">${{ number_format($totalassets, 2) }}</td>
                </tr>
        </table>



        <table class="table" style="text-align: left; width: 49%; margin-left: 1%; float: left; border-top: none;">
            <tr>
                <td colspan="2" style="background-color: lightblue;"><b>Liabilities</b></td>
            </tr>
            @foreach($liabilitys as $catagory => $subcatagorys)

                @php
                    $totalcatagory = 0;
                @endphp


                <tr style="background-color: lightgrey;" >
                    <td class="col-md-10" ><b>{{$catagory}}</b></td>
                    <td class="col-md-2"></th>

                </tr>

                @foreach($subcatagorys as $subcatagory => $value)
                    <tr >
                        <td class="col-md-10">{{ $subcatagory }}</td>
                        <td class="col-md-2">${{ number_format($value, 2) }}</td>

                    </tr>

                    @php
                        $totalcatagory = $totalcatagory + floatval($value);
                    @endphp
                @endforeach

                <tr style="background-color: white;" >
                    <td class="col-md-10"><b>Total {{ $catagory }}</b></td>
                    <td class="col-md-2">${{ number_format($totalcatagory, 2) }}</td>

                </tr>

                <tr style="background-color: white;" >
                    <td class="col-md-10"> </td>
                    <td class="col-md-2"> </td>

                </tr>


            @endforeach
            <tr style="background-color: lightblue;" >
                <td class="col-md-10"><b>Liabilities Total</b></td>
                <td class="col-md-2">${{ number_format($totalliabilitys, 2) }}</td>

            </tr>

                <tr style="background-color: white;" >
                    <td class="col-md-10"> </td>
                    <td class="col-md-2"> </td>

                </tr>

            <tr style="background-color: lightblue;">
                <td colspan="2"><b>Equity</b></td>
            </tr>
            

            <tr >
                <td class="col-md-10">Retained Earnings</td>
                <td class="col-md-2">${{ number_format($retainedearnings, 2) }}</td>

            </tr>

            @foreach($equitys as $catagory => $value)
            <tr  >
                <td class="col-md-10">{{ $catagory }}</td>
                <td class="col-md-2">${{ number_format($value, 2) }}</td>

            </tr>
            @endforeach



            <tr style="background-color: lightblue;" >
                <td class="col-md-10"><b>Total Equity</b></td>
                <td class="col-md-2">${{ number_format($totalequity, 2) }}</td>

            </tr>

            <tr style="background-color: white;" >
                <td class="col-md-10"> </td>
                <td class="col-md-2"> </td>

            </tr>

            <tr style="background-color: lightblue;" >
                <td class="col-md-10"><b>Total Liabilities and Equity</b></td>
                <td class="col-md-2">${{ number_format($totalequity + $totalliabilitys, 2) }}</td>

            </tr>
        </table>

    </div>
    {{--
    <div style="width: 100%;">
        <div id="title">
            <b>Assets</b>
        </div>
        <table class="table">
            <thead>
                <tr style="background-color: lightblue;">
                    <th class="col-md-10"><b>Current Assets</b></th>
                    <th class="col-md-2">$</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Cash</td>
                    <td>$0.00</td>
                </tr>
                <tr>
                    <td>Accounts Receivable</td>
                    <td>$0.00</td>
                </tr>
                <tr>
                    <td>Prepaid Rent</td>
                    <td>$0.00</td>
                </tr>
                <tr>
                    <td>Inventory</td>
                    <td>$0.00</td>
                </tr>
                @foreach($assets as $key => $value)
                <tr>
                    <td>{{ $key }}</td>
                    <td>${{ number_format($value, 2) }}</td>
                </tr>
                @endforeach
                <tr style="background-color: lightblue;">
                    <td><b>Total Current Assets</b></td>
                    <td>$0.00</td>
                </tr>
            </tbody>
        </table>
    </div>


    <div style="width: 100%;">
        <div id="title">
            <b>Liabilities</b>
        </div>
        <table class="table">
            <thead>
                <tr style="background-color: lightblue;">
                    <th class="col-md-10"><b>Current Liabilities</b></th>
                    <th class="col-md-2">$</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Accounts Payable</td>
                    <td>$0.00</td>
                </tr>
                <tr>
                    <td>Accrued Expenses</td>
                    <td>$0.00</td>
                </tr>
                <tr>
                    <td>Unearned Revenue</td>
                    <td>$0.00</td>
                </tr>

                @foreach($liabilitys as $key => $value)
                    <tr>
                        <td>{{ $key }}</td>
                        <td>${{ number_format($value, 2) }}</td>
                    </tr>
                @endforeach

                <tr style="background-color: lightblue;">
                    <td><b>Total Current Liabilities</b></td>
                    <td>${{ number_format($liabilitystotal, 2) }}</td>
                </tr>

                <tr style="background-color: lightblue;">
                    <td><b>Total Long Term Liabilities</b></td>
                    <td>$0.00</td>
                </tr>

                <tr style="background-color: lightblue;">
                    <td><b>Total Liabilities</b></td>
                    <td>${{ number_format($liabilitystotal, 2) }}</td>
                </tr>

            </tbody>
        </table>
    </div>
    <div style="width: 100%;">
        <div id="title">
            <b>Owner's Equity</b>
        </div>
        <table class="table">
            <thead>
                <tr style="background-color: lightblue;">
                    <th class="col-md-10"><b>Owner's Equity</b></th>
                    <th class="col-md-2">$</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Retained Earnings</td>
                    <td>$0.00</td>
                </tr>
                <tr>
                    <td>Common Stock</td>
                    <td>$0.00</td>
                </tr>

                <tr style="background-color: lightblue;">
                    <td><b>Total Owner's Equity</b></td>
                    <td>$0.00</td>
                </tr>


                <tr style="background-color: lightblue;">
                    <td><b>Total Liabilities and Owner's Equity</b></td>
                    <td>$0.00</td>
                </tr>

            </tbody>
        </table>
    </div>

            --}}
@stop

