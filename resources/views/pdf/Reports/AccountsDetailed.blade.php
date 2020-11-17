@php ini_set('max_execution_time', 180); @endphp
@extends('pdf.Reports.portraitemaster')
@section('content')
@include('pdf.Reports.Inserts.header')


    @foreach($array as $element)
        <div style="color:lightblue"><b>Account/Asset</b></div>
        <div>Category: {{ $element['category'] }} | Type: {{ $element['type'] }}</div>
        <div>--------------------------------------- </div>
        <div style="color:lightgreen"><b>Received</b></div>

        @php($RecievedTotal = 0)
        @foreach($array2 as $element2)

            @foreach($element2['catagorys'] as $key => $value)
                @if( $element['category'] == $key)
                    <div style="width: 100%">
                        <table style="width: 100%">
                            <tr>
                                <td style="width: 15%;border-style: solid;border-color: lightgreen">Date: {{ $element2['date'] }}</td>
                                <td style="width: 19%;border-style: solid;border-color: lightgreen">Amount: ${{ $value }} </td>
                                @php($RecievedTotal += $value)
                                <td style="width: 22%;border-style: solid;border-color: lightgreen">Method: {{ $element2['method'] }} </td>
                                <td style="width: 22%;border-style: solid;border-color: lightgreen">Comments: {{ $element2['comments'] }} </td>
                                <td style="width: 22%;border-style: solid;border-color: lightgreen">Accounts: {{ $key  }}
                            </tr>
                        </table>
                    </div>
                @endif
            @endforeach
        @endforeach
        <div>Recieved Total: ${{ number_format($RecievedTotal, 2)}}</div>

        @php($SentTotal = 0)
        <div style="color:lightpink"><b>Sent</b></div>
        @foreach($array3 as $element3)
            @foreach($element3['catagorys'] as $key => $value)
                @if( $element['category'] == $key)
                    <div style="width: 100%">
                        <table style="width: 100%">
                            <tr>
                                <td style="width: 15%;border-style: solid;border-color: lightpink">Date: {{ $element3['date'] }} </td>
                                <td style="width: 19%;border-style: solid;border-color: lightpink">Amount: ${{ $value }} </td>
                                @php($SentTotal += $value)
                                <td style="width: 22%;border-style: solid;border-color: lightpink">Payee: {{ $element3['payto'] }}</td>
                                <td style="width: 22%;border-style: solid;border-color: lightpink">Comments: {{ $element3['comments'] }}</td>
                                <td style="width: 22%;border-style: solid;border-color: lightpink">Accounts: {{ $key  }}</td>
                            </tr>
                        </table>
                    </div>
                @endif
            @endforeach
        @endforeach
        <div>Sent Total: ${{ number_format($SentTotal, 2) }}</div>
        @php($AccountTotal = $RecievedTotal - $SentTotal)
        <div>--------------------------------------- </div>
        <Div>Account Change: ${{ number_format($AccountTotal, 2) }}</Div>
        <div style="background-color: lightblue; width:100%;align-content: center">--------------------------------------- </div>

    @endforeach
@stop
