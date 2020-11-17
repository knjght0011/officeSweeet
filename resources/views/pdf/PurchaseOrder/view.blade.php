@extends('pdf.PurchaseOrder.master')

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
            background-color: rgba(0,0,0,0.5); /* Black background with opacity */
            z-index: 100; /* Specify a stack order in case you're using a different order for other elements */
            cursor: pointer; /* Add a pointer on hover */
            text-align: center;
            font-size: 50px;
            padding-top: 30%;
        }

        #void {
            color: red;
        }

        #paid {
            color: green;
        }

        table {
            border-left: 0.01em solid #ccc;
            border-right: 0;
            border-top: 0.01em solid #ccc;
            border-bottom: 0;
            border-collapse: collapse;
        }
        table td,
        table th {
            border-left: 0.01em solid #ccc;
            border-right: 0.01em solid #ccc;
            border-top: 0;
            border-bottom: 0.01em solid #ccc;
        }
    </style>
        @if($order->deleted_at != null)
            <div id="overlay void"> VOID </div>
        @endif

        <table class="table" style="border-collapse: collapse; width: 100%; float: left; border: none;">
            <tbody>
                <tr>
                    <td style="width: 40%; border: none;">
                        {!! SettingHelper::GetSetting('companyname') !!}<br>
                        @if(count($mainbranch) === 1)
                            @if($mainbranch->number != "")
                                {{$mainbranch->number}}
                            @endif
                            @if($mainbranch->address1 != "")
                                {{$mainbranch->address1}},
                            @endif
                            @if($mainbranch->address2 != "")
                                {{$mainbranch->address2}}
                            @endif
                                <br />
                            @if($mainbranch->city != "")
                                {{$mainbranch->city}},
                            @endif
                            @if($mainbranch->state != "")
                                {{$mainbranch->state}},
                            @endif
                            @if($mainbranch->zip != "")
                                {{$mainbranch->zip}}
                            @endif
                            @if($mainbranch->phonenumber != "")
                                <br />
                                Tel:{{$mainbranch->phonenumber}}
                            @endif
                        @endif
                    </td>
                    <td style="width: 20%; border: none;"></td>
                    <td style="width: 40%; border: none; font-size: large; text-align: right;">
                        <h2 style="margin: 0px; color: blue;">PURCHASE ORDER</h2>
                        Date: {{ $order->created_at->toDateString() }}<br>
                        PO#: {{ $order->POnumber() }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="row">

        </div>

        <table class="table" style="border-collapse: collapse; width: 100%; float: left; border: none;">
            <tbody>
                <tr>
                    <td style="width: 40%; text-align: center; background-color: lightblue; ">Vendor</td>
                    <td style="width: 20%; border: none;"></td>
                    <td style="width: 40%; text-align: center; background-color: lightblue; ">Ship To</td>
                </tr>
                <tr>
                    <td>
                        {{ $order->vendor->getName() }}<br>
                        @if($order->vendor->address->number != "")
                            {{$order->vendor->address->number}}
                        @endif
                        @if($order->vendor->address->address1 != "")
                            {{$order->vendor->address->address1}},
                        @endif
                        @if($order->vendor->address->address2 != "")
                            {{$order->vendor->address->address2}},
                        @endif
                        <br>
                        @if($order->vendor->address->city != "")
                            {{$order->vendor->address->city}},
                        @endif
                        @if($order->vendor->address->state != "")
                            {{$order->vendor->address->state}},
                        @endif
                        @if($order->vendor->address->zip != "")
                            {{$order->vendor->address->zip}}
                        @endif
                    </td>
                    <td style="border: none;"></td>
                    <td>
                        @if($order->branch_id != null)
                            @if($order->branch->number != "")
                                {{$order->branch->number}}
                            @endif
                            @if($order->branch->address1 != "")
                                {{$order->branch->address1}},
                            @endif
                            @if($order->branch->address2 != "")
                                {{$order->branch->address2}},
                            @endif
                                <br>
                            @if($order->branch->city != "")
                                {{$order->branch->city}},
                            @endif
                            @if($order->branch->state != "")
                                {{$order->branch->state}},
                            @endif
                            @if($order->branch->zip != "")
                                {{$order->branch->zip}}
                            @endif
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="row">

        </div>

        <div class="contents">
            <table class="table" >
                <thead>
                    <tr style="background-color: lightblue;">
                        <th class="col-md-1">Your Ref</th>
                        <th>Description</th>
                        <th class="col-md-1">Units</th>
                        <th class="col-md-1">Unit Cost</th>
                        <th class="col-md-1">Total</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->vendorref }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ number_format($item->units , 0) }}</td>
                        <td>${{ number_format($item->unitcost , 2) }}</td>
                        <td>${{ number_format($item->Total() , 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>


        <table class="table" style="text-align: right; border-collapse: collapse; width: 100%; float: left;">
            <tbody>
                <tr>
                    <td style="width: 60%; text-align: center; background-color: lightblue;">Comments</td>
                    <td style="width: 20%; background-color: lightblue;"><b>Subtotal:</b></td>
                    <td style="width: 20%;">${{ number_format($order->Subtotal(), 2) }}</td>
                </tr>
                <tr>
                    <td style="width: 60%; text-align: left;" rowspan="3">{!! nl2br(e($order->comments)) !!}</td>
                    <td style="width: 20%; background-color: lightblue;">Tax:</td>
                    <td>${{ number_format($order->TaxAmount(), 2) }}</td>
                </tr>
                <tr>
                    <td style="width: 20%; background-color: lightblue;">Shipping:</td>
                    <td>${{ number_format($order->shipping, 2) }}</td>
                </tr>
                <!--
                <tr>
                    <td style="width: 20%; background-color: lightblue;">Other:</td>
                    <td></td>
                </tr>
                -->
                <tr>
                    <td style="width: 20%; background-color: lightblue;"><b>Total:</b></td>
                    <td>${{ number_format($order->Total(), 2) }}</td>
                </tr>
            </tbody>
        </table>


@stop