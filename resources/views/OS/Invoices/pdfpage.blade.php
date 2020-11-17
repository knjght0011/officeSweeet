@extends('master')

@section('content')
<style>
    #content{
        height: 100%;
    }
</style>

    <iframe style="width: 100%; height: calc(100% - 60px); border: none;" src="/Clients/Invoice/PDF/{{ $invoice->id }}"></iframe>

    <button style="float: right; margin-left: 5px; margin-right: 5px;" class="btn OS-Button btn" type="button" data-toggle="modal" data-target="#EmailQuoteModal" data-type="invoice" data-quoteid="{{ $invoice->id }}" data-quotenumber="{{ $invoice->getQuoteNumber() }}">
        Email To {{ TextHelper::GetText("Client") }}
    </button>

    <button style="float: right; margin-left: 5px; margin-right: 5px;" class="btn OS-Button btn" type="button" onclick="GoToPage('/Clients/View/{{ $invoice->client_id }}/transactions');">
       Back To {{ TextHelper::GetText("Client") }}
    </button>


@include('Clients.modals.emailquoteinvoice')
@stop
