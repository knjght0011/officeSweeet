@extends('master')

@section('content') 

<button id="savetabs" name="savetabs" type="button" class="btn OS-Button" >Save</button>

<div class="tabholder">
    <ul class="nav nav-tabs" role="tablist">
        <?php $i = 0; ?>
        @foreach($tables as $table)
        <li role="presentation" @if($i == 0) class="active" @endif ><a href="#{{ $table->name }}" aria-controls="home" role="tab" data-toggle="tab">{{ $table->displayname }}</a></li>
        <?php $i = 1; ?>
        @endforeach
     </ul>


    <div id="customtabs-holder" class="tab-content">
        <?php $i = 0; ?>
        @foreach($tables as $table)

        <div role="tabpanel" class="tab-pane @if($i == 0) active @endif" id="{{ $table->name }}">
            <?php 
            echo $table->content;
            $i = 1;
            ?>
        </div>
        @endforeach
    </div>
</div>

<div class="modalload"><!-- Place at bottom of page --></div>

<script>
    
$(document).ready(function() {

    $client_id = 1; //set this later
    $type = "client";
    
    @foreach($tables as $table)
        GetAllTableData("{{ $table->id }}", $client_id, "{{ $table->name }}");
    @endforeach
    
    
    $("#savetabs").click(function()
    {
        $("body").addClass("loading");
        var $alltabdata = {};
        $("#customtabs-holder").children().each(function() {
            $tablename = $(this).attr('id');
            $thistabdata = {};
            
            $inputs = $(this).find("input");
            $.each($inputs, function( index, value ) {
                $thistabdata[$(this).attr("name")] = $(this).val();
            });
            
            $selects = $(this).find("select");
            $.each($selects, function( index, value ) {
                $thistabdata[$(this).attr("name")] = $(this).val();
            });
            $alltabdata[$tablename] = $thistabdata;
        });
        console.log($alltabdata);
        
        posting = PostTabData($client_id, $alltabdata, $type);
            
        posting.done(function( data ) {
            $("body").removeClass("loading");
            console.log(data);
            alert(data);
        });
        
        posting.fail(function() {
            $("body").removeClass("loading");
            alert( "Failed to post tab info" );
        });
    });
});

function PostTabData($client_id, $tabdata, $type) {
    console.log($tabdata);

    return $.post("/CustomTables/Contents/Save",
    {
        _token: "{{ csrf_token() }}",
        id: $client_id,
        alltabdata: $tabdata,
        type: $type,
    });
}

function GetAllTableData($tableid , $dataid, $tablename) 
{
    $("body").addClass("loading");
    var get = $.get( "/CustomTables/Contents/" + $tableid + "/" + $dataid, function(  ) { });

    get.done(function( data ) {
        //$container = $("#customtabs-holder").children();
        $("#customtabs-holder").children().each(function() {
            if($(this).attr('id') === $tablename){
                $container = $(this);
                
            }
        });
        
        $data = data[0];
        if($data === undefined){
            
        }else{
            $inputs = $container.find("input");
            $.each($inputs, function( index, value ) {
                $name = $(this).attr("name");
                $(this).val($data[$name]);
            });

            $selects = $container.find("select");
            $.each($selects, function( index, value ) {
                $name = $(this).attr("name");
                $(this).val($data[$name]);
            });
            $("body").removeClass("loading");
        }
    });   
}
</script>

@stop
