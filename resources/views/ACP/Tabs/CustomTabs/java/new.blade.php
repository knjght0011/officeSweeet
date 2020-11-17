<script type="text/javascript"> 
$( document ).ready(function() { 
    
    $col = 1;

    $('#edit-tabselect').change(function() {
        
        $id = $('#edit-tabselect').val();
        
        GetTableData($id);

        $('#tab-name').val($("#edit-tabselect option:selected").text());
        
    });
    
    $('#edit-tabselect').change();


        //save function
    $("#tab-basic-save").click(function()
    {       
        $.confirm({
            title: "Are you sure you want to save?",
            buttons: {
                confirm: function() {
                    
                    $("body").addClass("loading");
                    $name = $('#tab-name').val();
                    $fields = BuildTabData();
                    $type = $('#type-display').val();
                    post = PostTab($id, $name, $fields, $type);

                    post.done(function( data ) 
                    {
                        $("body").removeClass("loading");
                        if ($.isNumeric(data))
                        {
                            $("#edit-tabselect option:selected").text($('#tab-name').val());
                            $.confirm({
                                autoClose: 'Close|' + "2000",
                                title: "Success!",
                                content: 'Data Saved',
                                buttons: {
                                    Close: function () {

                                    }
                                }
                            });
                        }else{
                            //server validation errors
                            ServerValidationErrors(data);
                        }
                    });

                    post.fail(function() {
                        $("body").removeClass("loading");
                        alert( "Failed to post" );
                    });
                },
                cancel: function() {
                    // nothing to do

                }
            }
        }); 
 

    });

    $( "#tab-enable-toggle" ).change(function() {


        $.confirm({
            title: "Are you sure you want to enable/disable this tab?",
            buttons: {
                confirm: function() {
                    $("body").addClass("loading");

                    var $disabledID = $('#edit-tabselect').val();

                    post = $.post("/CustomTables/Deactivate",
                    {
                        _token: "{{ csrf_token() }}",
                        id: $disabledID
                    });

                    post.done(function( data )
                    {
                        $("body").removeClass("loading");
                        switch(data['status']) {
                            case "OK":
                                if (data['action'] === "disabled"){
                                    $( "#tab-enable-toggle" ).prop('checked', true);
                                }else{
                                    $( "#tab-enable-toggle" ).prop('checked', false);
                                }
                                break;
                            case "notfound":
                                $.dialog({
                                    title: 'Oops...',
                                    content: 'Unknown Response from server. Please refresh the page and try again.'
                                });
                                break;
                            default:
                                console.log(data);
                                $.dialog({
                                    title: 'Oops...',
                                    content: 'Unknown Response from server. Please refresh the page and try again.'
                                });
                        }
                    });

                    post.fail(function() {
                        $("body").removeClass("loading");
                        alert( "Failed to post" );
                    });
                },
                cancel: function() {
                    if($( "#tab-enable-toggle" ).prop('checked')){
                        $( "#tab-enable-toggle" ).prop('checked', false);
                    }else{
                        $( "#tab-enable-toggle" ).prop('checked', true);
                    }

                }
            }
        });
    });

    $('#tab-rename-enable').click(function () {
        $.confirm({
            title: 'Caution!',
            content: 'Renaming this tab will break any templates you have that use it. Are you sure you want to proceed?',
            buttons: {
                confirm: function () {
                    $('#tab-name').prop('readonly', false);
                },
                cancel: function () {

                }
            }
        });
    });

});

function BuildTabData(){
    $data = {};
    $data['col1'] = BuildColObject($('#col1'));
    $data['col2'] = BuildColObject($('#col2'));
    $data['col3'] = BuildColObject($('#col3'));
    return $data;
}

function BuildColObject($col){
    $object = {};

    $col.children().each(function () {
        if($(this).has('input').length > 0){
            $input = $(this).find('input')[0];
            if($($input).hasClass('date')){
                $object[$($input).prop('name')] = "date";
            }else{
                $object[$($input).prop('name')] = "input";
            }
        }
        if($(this).has('select').length > 0){
            $select = $(this).find('select')[0];
            var $options = $.map($select, function(elt, i) { return $(elt).val();});
            $object[$($select).prop('name')] = $options;
        }
        if($(this).has('textarea').length > 0){
            $input = $(this).find('textarea')[0];
            $rows = $($input).prop('rows');
            $object[$($input).prop('name')] = "textarea," + $rows;
        }
    });

    return $object;
}

function PostTab($id, $name, $fields, $type) {
    return $.post("/CustomTables/Save",
    {
        _token: "{{ csrf_token() }}",
        id: $id,
        name: $name,
        fields: $fields,
        type: $type
    });
}
function CheckExists($label) {
    
    $match = false;
    $( "#design" ).find('input').each(function(j, element)
    {          
        if($label.toLowerCase() === $(element).attr('name').toLowerCase())
        {
            $match = true;
        }
    });

    $( "#design" ).find('select').each(function(j, element)
    {          
        if($label.toLowerCase() === $(element).attr('name').toLowerCase())
        {
            $match = true;
        }
    });
    
   return $match;
}

function ValidateLabel($label) 
{
    
    if ($label == null || $label == "") {
        alert("Label must be filled out");
        return false;
    }
    
    if ($label.indexOf('_') !== -1) {
        alert("Label must not have underscores");
        return false;
    }

    if ($label.indexOf('"') !== -1) {
        alert('Label must not have "');
        return false;
    }

    if ($label.indexOf("'") !== -1) {
        alert("Label must not have '");
        return false;
    }

    if ($label.indexOf('&') !== -1) {
        alert("Label must not have & ");
        return false;
    }

    if ($label.indexOf('<') !== -1) {
        alert("Label must not have <");
        return false;
    }

    if ($label.indexOf('>') !== -1) {
        alert("Label must not have >");
        return false;
    }
    //if(/^[a-zA-Z0-9- ]*$/.test($label) === false) {
    //    alert('Label must only contain alphanumeric charicters');
     //   return false;
    //}
    
    if(/^[a-zA-Z]*$/.test($label.charAt(0)) === false) {
        alert('First charicter of a label must be a letter');
        return false;
    }
    
    return true;
}
function ValidateOption($label) 
{
     
    if ($label == null || $label == "") {
        alert("Options must be filled out");
        return false;
    } 
    /*
    if ($label.indexOf(' ') !== -1) {
        alert("Options must not have spaces");
        return false;
    } 
    */
    if(/^[a-zA-Z0-9- ]*$/.test($label) === false) {
       alert('Your Options contains illegal characters');
        return false;
    }
    
    return true;
}
function GetTableData($id) 
{    
    $("body").addClass("loading");

    var get = $.get( "/CustomTables/Get/" + $id, function() { });

    get.done(function( data ) {

        $("body").removeClass("loading");

        if(data === "table not found"){

        }else{
            //$("#tab-new").html(data["displayname"]);
            $('#type-display').val(data["type"]);
            $('#design').html(data["html"]);

            if(data["enabled"] === 1){
                $( "#tab-enable-toggle" ).bootstrapToggle('destroy');
                $( "#tab-enable-toggle" ).prop('checked', true);
                $( "#tab-enable-toggle" ).bootstrapToggle();
            }else{
                $( "#tab-enable-toggle" ).bootstrapToggle('destroy');
                $( "#tab-enable-toggle" ).prop('checked', false);
                $( "#tab-enable-toggle" ).bootstrapToggle();
            }

            $( "#design" ).find('input').each(function()
            {
                $(this).prop('readonly', true);
            });

            $( "#design" ).find('textarea').each(function()
            {
                $(this).prop('readonly', true);
            });

            $('#tab-name').prop('readonly', true);

            $('.datebutton').prop('disabled', 'disabled');
        }


    });
}

</script>