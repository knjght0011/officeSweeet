@extends('pdf.Reports.master')

@section('content')
    @include('pdf.Reports.Inserts.header')

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Primary Contact</th>
                <th>Phone Number</th>
                <th>Email Address</th>
            </tr>
        </thead>
        <tbody>      
            @foreach($items as $item)
            <tr>
                <td>{{ $item->getName() }}</td>
                <td>
                    @if(is_null($item->primarycontact_id))
                        No Primary Contact Set
                    @else
                        {{ $item->getPrimaryContactName() }}
                    @endif
                </td>
                <td>
                    @if(is_null($item->primarycontact_id))
                        No Primary Contact Set
                    @else
                        {{ $item->primarycontact->Getprimaryphonenumber() }}
                    @endif
                </td>
                <td>
                    @if(is_null($item->primarycontact_id))
                        No Primary Contact Set
                    @else
                        {{ $item->primarycontact->email }}
                    @endif
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>

@stop