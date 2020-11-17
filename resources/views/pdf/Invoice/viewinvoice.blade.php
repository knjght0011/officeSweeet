@extends('pdf.Invoice.master')

@section('content')

    <style>
        #overlay {
            position: fixed; /* Sit on top of the page content */

            width: 100%; /* Full width (cover the whole page) */
            height: 100%; /* Full height (cover the whole page) */
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        {{-- background-color: rgba(0,0,0,0.5); /* Black background with opacity */--}}
            z-index: 100; /* Specify a stack order in case you're using a different order for other elements */
            cursor: pointer; /* Add a pointer on hover */
            text-align: center;
            font-size: 50px;
            padding-top: 20%;
        }

        #void {
            color: red;
        }

        #paid {
            color: green;
        }
    </style>



    <div class="page" >

        @if($quote->deleted_at != null)
            <div id="overlay void"> VOID </div>
        @endif
        @if($quote->getStatus() === "paid")
            <div id="overlay paid"> PAID </div>
        @endif

        <div id="header">
            @if($quote->finalized === 1)
                INVOICE
            @else
                QUOTE
            @endif
        </div>

        <div  id="returnaddress">
            From:<br/>
            {{ SettingHelper::GetSetting('companyname') }}<br/>
            {{ $quote->branch->number }} {{ $quote->branch->address1 }}<br/>
            {{ $quote->branch->city }}, {{ $quote->branch->state }} {{ $quote->branch->zip }}
        </div>

        <div id="toaddess">
            To:<br/>
            {{ $client->getName() }}<br/>
            @if($client->address_id != null)
            {{ $client->address->number }} {{ $client->address->address1 }}<br/>
            {{ $client->address->city }}, {{ $client->address->state }} {{ $client->address->zip }}
            @endif
        </div>


        <div id="companylogo">
            <img style="max-width: 100%; max-height: 100%;" id="companylogopreview" src="{{ \App\Helpers\OS\FileStoreHelper::CompanyLogo() }}" alt="">
        </div>


        <div id="meta">
            <table id="meta1">
                <tr>
                    <td class="meta-head">
                        @if($quote->finalized === 1)
                            Invoice #
                        @else
                            Quote #
                        @endif
                    </td>
                    <td>{{ $quote->getQuoteNumber() }}</td>
                </tr>
                <tr>
                    <td class="meta-head">Date</td>
                    <td>
                        @if($quote->finalized === 1)
                            {{ $quote->finalizeddate->toDateString() }}
                        @else
                            {{ $quote->created_at }}
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <div class="contents">
            <table class="rdTable" style="border-left: ">

                <tr>
                    <th>SKU</th>
                    <th>Description</th>
                    <th>Unit Cost</th>
                    <th>QTY</th>
                    <th class="col-md-1">Price ({{ $currency }})</th>
                    <th class="col-md-1">Tax ({{ $currency }})</th>
                    <th class="col-md-1">City Tax ({{ $currency }})</th>
                    <th class="col-md-1">Total ({{ $currency }})</th>

                </tr>

                @foreach($quote->quoteitem as $item)
                    <tr class="item-row">
                        <td class="item-name" style="border-width: 0 1px 1px 1px;">{{ $item->SKU }}</td>
                        <td class="description">{{ $item->description }}</td>
                        <td class="costper">{{ number_format($item->costperunit , 2, '.', '') }}</td>
                        <td class="units">{{ number_format($item->units , 0, '.', '') }}</td>
                        <td name="price" >{{ $item->getSubTotal() }}</td>
                        <td name="tax" >{{ $item->getTax() }}</td>
                        <td name="city tax" >{{ $item->getCityTax() }}</td>
                        <td name="total" >{{ $item->getTotal() }}</td>
                    </tr>

                @endforeach

                <tr>
                    <td colspan="5" class="blank" style="border-width: 0 1px 1px 1px;"> </td>
                    <td colspan="1" class="total-line">Subtotal</td>
                    <td class="total-value"><div id="subtotal">{{ $currency }}{{ $quote->getSubTotal() }}</div></td>
                </tr>
                <tr>
                    <td colspan="5" class="blank" style="border-width: 0 1px 1px 1px;"> </td>
                    <td colspan="1" class="total-line">Tax</td>
                    <td class="total-value"><div id="currency">{{ $currency }}{{ $quote->getTax() }}</div></td>
                </tr>
                <tr>
                    <td colspan="5" class="blank" style="border-width: 0 1px 1px 1px;"> </td>
                    <td colspan="1" class="total-line">City Tax</td>
                    <td class="total-value"><div id="currency">{{ $currency }}{{ $quote->getCityTax() }}</div></td>
                </tr>
                <tr>

                    <td colspan="5" class="blank" style="border-width: 0 1px 1px 1px;"> </td>
                    <td colspan="1" class="total-line">Total</td>
                    <td class="total-value"><div id="total">{{ $currency }}{{ $quote->getTotal() }}</div></td>
                </tr>

            </table>

            @if($quote->getStatus() === "paid")
                <div style="width:100%; text-align: center; font-size: 20px; margin-top: 20px"> Please Pay {{ $currency }}{{ $quote->getTotal() }} </div>
            @endif

            <div style="width:100%; text-align: center; font-size: 20px; margin-top: 20px">{{ $quote->comments }} </div>
        </div>

    </div>
@stop