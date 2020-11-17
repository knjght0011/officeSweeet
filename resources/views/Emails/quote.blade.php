@extends('Emails.Templates.customer')

@section('content')
    
    <textarea id="header">Quote</textarea>
    <div class="row">

        <div  class="col-md-2" id="returnaddress">
            <h6>Return Address:</h6>

        </div>
          
        <div id="identity">
            <h6>To:</h6>

            {{ $client->name }}
            {{ $client->address->address1 }}
            {{ $client->address->city }}, {{ $client->address->state }} {{ $client->address->zip }}

        </div>

        <table id="meta">
            <tr>
                <td class="meta-head">Quote #</td>
                <td>{{ $quote->getQuoteNumber() }}</td>
            </tr>
            <tr>
                <td class="meta-head">Date</td>
                <td>{{ $quote->finalizeddate }}</td>
            </tr>
        </table>

    </div>
		
        <table id="items">

            <tr>
                <th>Item</th>
                <th>Description</th>
                <th class="col-md-1">Unit Cost ({{ $currency }})</th>
                <th class="col-md-1">Quantity</th>
                <th class="col-md-1">Price ({{ $currency }})</th>

            </tr>
            
            @foreach($quote->quoteitem as $item)
            <tr class="item-row">
                <td class="item-name">{{ $item->description }}</td>
                <td class="description">{{ $item->comments }}</td>
                <td>{{ number_format($item->costperunit , 2, '.', '') }}</td>
                <td>{{ number_format($item->units , 0 , '.', '') }}</td>
                <td></td>
            </tr>

            @endforeach

            <tr>
                <td colspan="2" class="blank"> </td>
                <td colspan="2" class="total-line">Subtotal</td>
                <td class="total-value"><div id="subtotal">{{ $currency }}0.00</div></td>
            </tr>
            <tr>
                <td colspan="2" class="blank"> </td>
                <td colspan="2" class="total-line">Tax</td>
                <td class="total-value"><div id="subtotal">{{ $currency }}0.00</div></td>
            </tr>
            <tr>

                <td colspan="2" class="blank"> </td>
                <td colspan="2" class="total-line">Total</td>
                <td class="total-value"><div id="total">{{ $currency }}0.00</div></td>
            </tr>

        </table>

        <div id="terms">
            <h5>Terms</h5>
            <textarea>Quote valid for 30 days from Quote Date.</textarea>
        </div>
      
@stop