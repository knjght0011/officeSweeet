@extends('master')

@section('content')  
    
    <div class="row">
        <div class="col-md-2 ">
            <div class="searchbox">
                <input type="text" class="form-control" id="search" >
                <button type="button" class="btn OS-Button" onclick="$('div.searchtile').parent().show();">Reset</button>
                <button type="button" class="btn OS-Button" id="text_value">Search</button>
            </div>
        </div> 
        <div class="col-md-10">
            @foreach($address as $add)
            <div class="col-md-2">
                <div class="searchtile">
                    <!--<div class="col-md-6">
                        Address Line 1:<br>
                        Address Line 2:<br>
                        City:<br>
                        State:<br>
                        Zip:
                    </div>
                    <div class="col-md-6">-->
                        {{ $add->address1 }}
                        <br>{{ $add->address2 }}
                        <br>{{ $add->city }}
                        <br>{{ $add->state }}
                        <br>{{ $add->zip }}
                </div>
            </div>
            @endforeach
        </div> 

    </div>


<!--
onkeydown="$('div.searchtile').parent().hide();" onkeyup="$('div.searchtile:contains('$('#search').val()')').parent().show()"
-->
<script>
$('#text_value').click(function(){
   $('div.searchtile').parent().hide(); 
   var txt = $('#search').val();
   $('div.searchtile:contains("'+txt+'")').parent().show();
});
</script>
@stop