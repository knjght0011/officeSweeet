<!-- Old Dynamic Loading System removed fore now as causing more problems than good
<script>
 
//$(function(){
//   var body_width =  $('body').width();
//    var sidebar_width = $('#sidebar').outerWidth();
//    var content_width = body_width - sidebar_width;
//    
//    $('#content').css({width: content_width})
//
//    $(window).resize(function(){
//        var body_width =  $('body').width();
//        var sidebar_width = $('#sidebar').outerWidth();
//        var content_width = body_width - sidebar_width;
//
//        $('#content').css({width: content_width})
//    })
//})

@if(isset($page))
    $(document).ready(function(){
        //Initial Load
        history.pushState({url:{{ $page }}}, null, {{ $page }});
        $('#content').load('{{ $page }}');
    });
@endif

$( document ).on( "click", "a", function(event) {
    if ($(this).attr('id') === 'linkdisabled') {
        
        event.preventDefault();
        
        $url = $(this).attr('href');
        
        $page = window.location.pathname.split("/");
        $page2 = $page[1] + $page[2];
        
        switch ($page2) {
            case "TemplatesNew":
            case "TemplatesEdit":
            case "TemplatesGenerate":
                $.confirm({
                    text: "You will lose any unsaved changes, Are your sure you want to change page?",
                    confirm: function() {
                        history.pushState({url:$url}, null, $url);
                        $("#content").load($url);
                    },
                    cancel: function() {
                        // nothing to do
                        
                    }
                });
                break;
            default: 
                history.pushState({url:$url}, null, $url);
                $("#content").load($url);
                break;
        }
        
    }
});

window.onpopstate = function (evt) {
    
    /** event.state contains the stored JS object, so we can pass it back **/
    $("#content").load(evt.state.url);
};


</script> 
-->    