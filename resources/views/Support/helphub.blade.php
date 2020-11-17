<div class="modal fade" id="ShowHelpHub" tabindex="-1" role="dialog" aria-labelledby="ShowHelpHub" aria-hidden="true">
    <div class="modal-dialog large-custom-modal-dialog" role="document">
        <div class="modal-content large-custom-modal-content">


            <div style="height: 99%;" class="modal-body">     
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>  
                 <ul class="nav nav-tabs">
                    <li class="active">
                        <a id="videoclick" href="#video" data-toggle="tab" data-target="#video">Video Tutorials</a>
                    </li>
                    <li>
                        <a id="wikiclick" href="#wiki" data-toggle="tab" data-target="#wiki">Wiki</a>
                    </li>
                    <li>
                        <a id="supportclick" href="#support" data-toggle="tab"  data-target="#support">Contact Support</a>
                    </li>
                    <li>
                        <a id="actionclick" href="#action" data-toggle="tab"  data-target="#action">Actions</a>
                    </li>
                </ul>
                <div class="tab-content" style="height: 100%">

                    <div class="tab-pane active" id="video" style="height: 100%">
                        @include('Support.Tabs.videotab')
                    </div>
                
                    <div class="tab-pane" id="wiki" style="height: 100%">
                        @include('Support.Tabs.wikitab')
                    </div>
                    
                    <div class="tab-pane" id="support">
                        @include('Support.Tabs.support')
                    </div>
                    
                    <div class="tab-pane" id="action">
                        @include('Support.Tabs.action')
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#ShowHelpHub').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget); // Button that triggered the modal
        if(button.length === 1){
            var tab = button.data('tab');
            var url = button.data('url');
        }else{
            var tab = $('#ShowHelpHub').data('tab');
            var url = $('#ShowHelpHub').data('url');
        }

        switch (tab) {
            case "video":
                $('#videoclick').click();
                var url = "https://www.youtube.com/embed/" + url + "?rel=0&autoplay=1";
                $('#ShowYoutubeFrame').attr("src", url);
                break;
            case "wiki": 
                $('#wikiclick').click();

                break;
            case "support": 
                $('#supportclick').click();

                break;
            case "action": 
                $('#actionclick').click();

                break;
            default: 

                break;
        }
    }); 

    $('#ShowHelpHub').on('hide.bs.modal', function (event) {
       //$('#ShowYoutubeFrame').attr("src", "{{ url('videoselect.html') }}");
    });

    $('.youtubebutton').click(function(){
        var button = $(this);
        var url = "https://www.youtube.com/embed/" + button.data('url') + "?rel=0&autoplay=1";
        @desktop
        $('#ShowYoutubeFrame').attr("src", url);
        @elsedesktop
        window.open(url);
        @enddesktop
    });

    $('.wikibutton').click(function(){
        var button = $(this);
        var url = "https://wiki.officesweeet.com/index.php?title=" + button.data('url');
        @desktop
        $('#ShowWikiFrame').attr("src", url);
        @elsedesktop
        window.open(url);
        @enddesktop

    });

    $('#support-wiki-openpage').click(function(){
        window.open($('#ShowWikiFrame').attr("src"));

    });
});
</script>