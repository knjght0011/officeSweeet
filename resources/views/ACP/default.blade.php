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
        <li role="presentation" class="active"><a href="#general" aria-controls="home" role="tab" data-toggle="tab">General Settings</a></li>
        <li role="presentation"><a href="#companyinfo" aria-controls="home" role="tab" data-toggle="tab">Company Info</a></li>
        <li role="presentation"><a href="#customtabs" aria-controls="profile" role="tab" data-toggle="tab">Custom Tabs</a></li>
        
        <li role="presentation"><a href="#email" aria-controls="profile" role="tab" data-toggle="tab">System E-Mail Settings</a></li>
        <li role="presentation"><a href="#transnational" aria-controls="profile" role="tab" data-toggle="tab">TransNational Payment Gateway</a></li>

        @if(Auth::user()->hasPermission('subscription_permission'))
        <li role="presentation"><a href="#subscription" aria-controls="profile" role="tab" data-toggle="tab">Manage Subscription</a></li>
        @endif
    </ul>
    <div class="tab-content" style="height: calc(100% - 42px); background-color: white;">
        
        <div role="tabpanel" class="tab-pane active" id="general" style="height: 100%;"> 
            @include('ACP.Tabs.General.default')
        </div>
        
        <div role="tabpanel" class="tab-pane" id="companyinfo" style="height: 100%;"> 
            @include('ACP.Tabs.CompanyInfo.default')
        </div>
        
        <div role="tabpanel" class="tab-pane" id="customtabs" style="height: 100%;">
            @include('ACP.Tabs.CustomTabs.default')
        </div>
        
        <div role="tabpanel" class="tab-pane" id="email" style="height: 100%;">
            @include('ACP.Tabs.Email.default')
        </div>

        <div role="tabpanel" class="tab-pane" id="transnational" style="height: 100%;">
            @include('ACP.Tabs.Transnational.default')
        </div>

        @if(Auth::user()->hasPermission('subscription_permission'))
            <div role="tabpanel" class="tab-pane" id="subscription" style="height: 100%;">
                @include('ACP.Tabs.Subscription.default')
            </div>
        @endif

    </div>
</div>   

<div class="modalload"></div>
<script>
$(document).ready(function() {
    @if(isset($tab1))
        $tab1 = "{{ $tab1 }}";
    @else
        $tab1 = "";        
    @endif    
    
    @if(isset($tab2))
        $tab2 = "{{ $tab2 }}";
    @else
        $tab2 = "";
    @endif  
    
    $tab1element = $('a[href$="'+$tab1+'"]');
    $tab1element.click();
    
    $tab2element = $('a[href$="'+$tab2+'"]');
    $tab2element.click();
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
    link.href = "/ACP/Tab/"+$tab1+"/"+$tab2;
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
</script> 
@include('footer')

