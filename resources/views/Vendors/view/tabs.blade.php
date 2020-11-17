
<div class="tabholder" style="height: 100%;">
    <ul class="nav nav-tabs" role="tablist">
        <?php $i = 0; ?>
        @foreach($tables as $table)
            <li role="presentation" @if($i == 0) class="active" @endif ><a href="#{{ $table->name }}" aria-controls="home" role="tab" data-toggle="tab">{{ $table->displayname }}</a></li>
            <?php $i = 1; ?>
        @endforeach

    </ul>


    <div id="customtabs-holder" class="tab-content" style="height: calc(100% - 50px)">
        <?php $i = 0; ?>
        @foreach($tables as $table)
            <div role="tabpanel" class="tab-pane @if($i == 0) active @endif" id="{{ $table->name }}" style="height: 100%;">
                <iframe id="tabframe" style="width: 100%; height: 100%;" src="{{ url("/CustomTables/Tab/" . $table->id  . "/" .  $vendor->id) }}">

                </iframe>
            </div>
            <?php $i = 1; ?>
        @endforeach
    </div>
</div>

<div class="modalload"><!-- Place at bottom of page --></div>
{{--
<script>
    
$(document).ready(function() {

    $vendor_id = {{ $vendor->id }};
    $type = "vendor";
    
    @foreach($tables as $table)
        GetAllTableData("{{ $table->id }}", $vendor_id, "{{ $table->name }}");
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
            
            $textarea = $(this).find("textarea");
            $.each($textarea, function( index, value ) {
                $thistabdata[$(this).attr("name")] = $(this).val();
            });
            
            
            $selects = $(this).find("select");
            $.each($selects, function( index, value ) {
                $thistabdata[$(this).attr("name")] = $(this).val();
            });
            $alltabdata[$tablename] = $thistabdata;
        });
        
        posting = PostTabData($vendor_id, $alltabdata, $type);
            
        posting.done(function( data ) {
            $("body").removeClass("loading");
            bootstrap_alert.warning('Data Saved', 'success', 4000);
        });
        
        posting.fail(function() {
            $("body").removeClass("loading");
            bootstrap_alert.warning('Failed to save tab data!', 'danger', 4000);
        });
    });
});

function PostTabData($vendor_id, $tabdata, $type) {

    return $.post("/CustomTables/Contents/Save",
    {
        _token: "{{ csrf_token() }}",
        id: $vendor_id,
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
            $("body").removeClass("loading");
        }else{
            $inputs = $container.find("input");
            $.each($inputs, function( index, value ) {
                $name = $(this).attr("name");
                $(this).val($data[$name]);
            });
            
            $textarea = $container.find("textarea");
            $.each($textarea, function( index, value ) {
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
--}}
