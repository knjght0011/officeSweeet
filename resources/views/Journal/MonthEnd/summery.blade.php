@extends('master')

@section('content')  

<legend>Month End Summery</legend> 

<div class="row" style="margin-top: 20px; margin-bottom: 20px;">
    <div style="float:left; width: 20em;  margin-left: 20px;">
        <button id="performmonthend" style="width: 100%; margin-top: 5px;" type="button" class="btn OS-Button">
        Complete month end {oldest first}
        </button>
    </div>
    
    <div style="float:left; width: 20em;  margin-left: 20px;">
        <button id="undomonthend" style="width: 100%; margin-top: 5px;" type="button" class="btn OS-Button">
        Undo last month end
        </button>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Date</th>
            <th>Ending Balance</th>
        </tr>
    </thead>
    <tbody>
@foreach(array_reverse($ends) as $key => $value) 
    @if($value === "next")
        <tr class="info">
            <td>{{ $key }}</td>
            <td>Next</td>
        </tr>
    @elseif($value === "pending")
        <tr class="warning">
            <td>{{ $key }}</td>
            <td>Pending</td>
        </tr>
    @elseif($value === "overdue")
        <tr class="danger">
            <td>{{ $key }}</td>
            <td>Overdue</td>
        </tr>
    @else
        <tr class="success">
            <td>{{ $key }}</td>
            <td>{{ $value->formatedEndingBalence() }}</td>
        </tr>
    @endif
@endforeach     
    </tbody>
</table>

<script>   
$(document).ready(function(){
   $('#performmonthend').click(function(e) {
        var link = document.createElement('a');
        link.href = "/Journal/MonthEnd/Next";
        link.id = "link";
        document.body.appendChild(link);
        link.click();


    }); 
    
    $('#undomonthend').click(function(e) {
        $.confirm({
            title: 'WARNING!',
            content: 'Are you sure you would like to roll back the most recent month end?',
            buttons: {
                confirm: function () {
                    $("body").addClass("loading");

                    var get = $.get( "/Journal/MonthEnd/UndoLast", function(  ) { });

                    get.done(function( data ) {
                        if(data === "error"){
                            $("body").removeClass("loading");
                            $.dialog({
                                title: 'Error!',
                                content: 'Unable to roll back last month end, are you sure you have any month ends to roll back?'
                            });
                        }
                        if(data === "rolledback"){
                            location.reload();
                        }

                    });
                },
                cancel: function () {
                    
                }
            }
        });
    }); 
});
</script>
@stop
