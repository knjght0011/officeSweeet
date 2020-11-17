@extends('pdf.Checks.master')

@section('content')

<?php 
if(!empty(Session::get('settings'))){ 
    $settings = Session::get('settings');

}
?>
<body>
@foreach($checks as $check)
<div class="page" @if($check !== $checks->last())style="page-break-after: always;" @endif>
    <div id="payee">
        {{ $check->payto }}<br>
    </div>
    <div id="toaddess">
        {{ $check->LinkedAccountName() }}<br>
        {{ $check->data()->address->number }} {{ $check->data()->address->address1 }}<br>
        {{ $check->data()->address->city }}, {{ $check->data()->address->state }} {{ $check->data()->address->zip }}
    </div>
    <div id="payee">
        {{ $check->payto }}<br>
    </div>
    <div id="amount">
        **{{ $check->GetAmount() }} <br/>
    </div>
    <div id="date">
        {{ $check->CheckDate() }}<br>
    </div>
    <div id="amountwords">
        {{ $check->AmountInWords() }} <br/>
    </div>
    <div id="memo">
        {{ $check->memo }} <br/>
    </div>
    <div id="StubHeader">
        Vendor:{{ $check->payto }} | Check Date:{{ $check->CheckDate() }} | Amount: **${{ $check->GetAmount() }} <br/>
        For / Memo: {{ $check->memo }}
    </div>
    <div id="StubDetail">
        Account: @foreach($check->catagorys as $key => $value)
            {{ $key }}->${{ $value }}|
        @endforeach
    <br/>
        Invoice Date: <br />
        Invoice Number: <br/>
    </div>
    <div id="StubHeader2">
        Vendor:{{ $check->payto }} | Check Date:{{ $check->CheckDate() }} | Amount: **${{ $check->GetAmount() }} <br/>
        For / Memo: {{ $check->memo }}
    </div>
    <div id="StubDetail2">
        Account: @foreach($check->catagorys as $key => $value)
            {{ $key }}->${{ $value }}|
        @endforeach
        <br/>
        Invoice Date: <br />
        Invoice Number: <br/>
    </div>
</div>

@endforeach 
@stop