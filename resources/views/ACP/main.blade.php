@include('header')
<body style="height: 100vh; background-color: #eee; background-size:100% 100%;">
    
<link rel="stylesheet" href="/includes/bootstrap.vertical-tabs.css">
<div id="APC-header">
    <div style="height: 100%; float: left;">
        <a href="/"><img style="height: 100%;" src="{{ TextHelper::GetLogo() }}"></a>
    </div>
    
    <div style="height: 100%; float: left;">
        <legend>Admin Control Panel</legend>
    </div> 
</div>
<div id="APC-body">
    <ul id="tabnavmaster" class="nav nav-tabs" role="tablist">
        <li role="presentation" @if($tab === "General")class="active"@endif><a href="/ACP/General" >General Settings</a></li>

        @if(Auth::user()->hasPermission('acp_company_info_permission'))
        <li role="presentation" @if($tab === "CompanyInfo")class="active"@endif><a href="/ACP/CompanyInfo" >Company Info</a></li>
        @endif

        @if(Auth::user()->hasPermission('acp_manage_custom_tables_permission'))
        <li role="presentation" @if($tab === "CustomTabs")class="active"@endif><a href="/ACP/CustomTabs" >Custom Tabs</a></li>
        @endif

        <li role="presentation" @if($tab === "Messages")class="active"@endif><a href="/ACP/Messages" >Default Messages</a></li>

        @if(Auth::user()->hasPermission('acp_import_export_permission'))
        <li role="presentation" @if($tab === "ImportExport")class="active"@endif><a href="/ACP/ImportExport" >Bulk Data Import/Export</a></li>
        @endif

        <li role="presentation" @if($tab === "Integration")class="active"@endif><a href="/ACP/Integration" >Integration</a></li>
    </ul>
    <div class="tab-content" style="height: calc(100% - 42px); background-color: white;">

        @yield('content')

    </div>
</div>

<div class="modalload"><!-- Place at bottom of page --></div>



<script>
$(document).ready(function() {
    @if($subtab != null)
        $tab2element = $('a[href$="{{ $subtab }}"]');
        $tab2element.click();
    @endif

});

bootstrap_alert = function () {}
bootstrap_alert.warning = function (message, alert, timeout) {
    $('<div id="floating_alert" class="alert alert-' + alert + ' fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>' + message + '&nbsp;&nbsp;</div>').appendTo('body');

    setTimeout(function () {
        $(".alert").alert('close');
    }, timeout);

}

function ServerValidationErrors($array) {
    $text = "";
    $.each($array, function( index, value ) {
        $text = $text + value + "<br>";
    });
    $.dialog({
        title: 'Validation Error!',
        content: $text
    });
}

function ValidateInput($input) {

    
    $input.removeClass('invalid');
    
    $error = false;

    $value = $input.val();
    
    $type = $input.data('validation-type');
    if(typeof $type === 'undefined'){
        $type = "";
    }
    
    $label = $input.data('validation-label');
    if(typeof $label === 'undefined'){
        $label = "Highlighted input";
    }
    
    $required = $input.data('validation-required');
    
    
    switch($type) {
        case "string":
            
            break;
        case "amount":
            if(!$.isNumeric($value)){
                $.dialog({
                    title: 'Error!',
                    content: $label + ' must be a number'
                });
                $error = true;
            }
            break;
        default:
            
    }

    if($required === true){
        if($value === ""){
            $.dialog({
                title: 'Error!',
                content: $label + ' is Required'
            });
            $error = true;
        }        
    }
    
    if($error === true){
        $input.addClass('invalid');
        throw new Error("Validation Error");
    }

    return $value;
}

function GoToTab($tab1,$tab2) {
    var link = document.createElement('a');
    link.href = "/ACP/"+$tab1+"/"+$tab2;
    link.id = "link";
    document.body.appendChild(link);
    link.click();    
}

function GoToPage($link) {
    var link = document.createElement('a');
    link.href = $link;
    link.id = "link";
    document.body.appendChild(link);
    link.click();    
}

function AddPopup($element, $position, $text) {
    $element.data( "toggle", "popover" );
    $element.data( "placement", $position );
    $element.data( "trigger", "hover" );
    $element.data( "content", $text );
    
    $element.popover();
}

function PageinateUpdate(info, $nextbutton, $prevbutton, $textlocation){

    $prevbutton.prop('disabled', false);
    $nextbutton.prop('disabled', false);

    $textlocation.html(
        'Currently showing page '+(info.page+1)+' of '+info.pages+' pages.'
    );

    if(info.page === 0){
        $prevbutton.prop('disabled', true);
    }

    if((info.page+1) === info.pages){
        $nextbutton.prop('disabled', true);
    }
}

function SavedSuccess($content = 'Data Saved', $title = "Success!", $timeout = "2000"){
    $.confirm({
        autoClose: 'Close|' + $timeout,
        title: $title,
        content: $content,
        buttons: {
            Close: function () {

            }
        }
    });
}
</script> 
@include('footer')

