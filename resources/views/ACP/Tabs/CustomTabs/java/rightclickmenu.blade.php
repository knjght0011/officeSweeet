<div id="TabMenu" class="dropdown clearfix">
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;">
        <li><a id="delete" tabindex="-1" href="#">Delete</a></li>
        <li><a id="EditDropDown" tabindex="-1" href="#">Edit Dropdown</a></li>
        <li class="divider"></li>
        <li><a id="LinkMoveUp" tabindex="-1" href="#">Move Up</a></li>
        <li><a id="LinkMoveDown" tabindex="-1" href="#">Move Down</a></li>
        <li><a id="LinkMoveLeft" tabindex="-1" href="#">Move Left</a></li>
        <li><a id="LinkMoveRight" tabindex="-1" href="#">Move Right</a></li>
    </ul>
</div>

<script>
//right click menu functions
$(document).click(function() {
    $("#TabMenu").css({
        display: "none",
        left: 0,
        top: 0
    });
});

$(document).on('contextmenu', '.element', function(){
    event.preventDefault();

    console.log($(this).parent().attr('id'));
    $( "#LinkMoveLeft" ).css({
        display: "block"
    });
    $( "#LinkMoveRight" ).css({
        display: "block"
    });
    $( "#LinkMoveUp" ).css({
        display: "block"
    });
    $( "#LinkMoveDown" ).css({
        display: "block"
    });

    $( "#EditDropDown" ).css({
        display: "none"
    });

    if($(this).parent().attr('id') === "col1"){
        $( "#LinkMoveLeft" ).css({
            display: "none"
        });
    }

    if($(this).parent().attr('id') === "col3"){
        $( "#LinkMoveRight" ).css({
            display: "none"
        });
    }

    if($(this).index() === 0){
        $( "#LinkMoveUp" ).css({
            display: "none"
        });
    }

    $parent = $(this).parent();
    $childern = $parent.children();
    $size = $childern.length - 1;

    if($(this).index() === $size){
        $( "#LinkMoveDown" ).css({
            display: "none"
        });
    }


    $hasselect = $(this).has( "select" );
    if($hasselect.length > 0){
        $( "#EditDropDown" ).css({
            display: "block"
        });
    }

    var offset = $("#customtabs-new").offset();
    $("#TabMenu").css({
        display: "block",
        left: event.clientX - offset.left,
        top: event.clientY - offset.top
    });

    window.selectedelement = $(this);
});

$( document ).ready(function() {


    $( "#delete" ).click(function() {
        event.preventDefault();
        $.confirm({
            title: "Are you sure you want to delete this?",
            buttons: {
                confirm: function() {
                    window.selectedelement.remove();
                },
                cancel: function() {
                    // nothing to do

                }
            }
        });
    });


    $( "#LinkMoveLeft" ).click(function() {
        event.preventDefault();
        $currentcol = window.selectedelement.parent().attr('id');
        $element = window.selectedelement[0].outerHTML;
        window.selectedelement.remove();
        if($currentcol === "col3"){
            $( "#col2" ).append( $element );
        }

        if($currentcol === "col2"){
            $( "#col1" ).append( $element );
        }
    });

    $( "#LinkMoveRight" ).click(function() {
        event.preventDefault();
        $currentcol = window.selectedelement.parent().attr('id');
        $element = window.selectedelement[0].outerHTML;
        window.selectedelement.remove();
        if($currentcol === "col1"){
            $( "#col2" ).append( $element );
        }

        if($currentcol === "col2"){
            $( "#col3" ).append( $element );
        }
    });

    $( "#LinkMoveUp" ).click(function() {
        event.preventDefault();
        window.selectedelement.prev().insertAfter(window.selectedelement);
    });

    $( "#LinkMoveDown" ).click(function() {
        event.preventDefault();
        window.selectedelement.next().insertBefore(window.selectedelement);
    });

});
</script>