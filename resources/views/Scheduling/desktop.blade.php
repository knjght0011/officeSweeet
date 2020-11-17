@extends('master')

@section('content')
<style>
#calendar {
        padding: 10px;
        float: left;
        min-height: 100%;
        width: calc(100% - 200px);
        height: 100%;
        display: inline-block;
}
</style>

<div id="view-tabs">
    <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active" style="padding-top: 5px;"><a href="#schedule-tab" id="schedule-tab-click" aria-controls="profile" role="tab" data-toggle="tab">Schedule</a></li>
            <li role="presentation" style="padding-top: 5px;"><a href="#search-tab" id="search-tab-click" aria-controls="profile" role="tab" data-toggle="tab">Search</a></li>
    </ul>

    <div class="tab-content" style="height: calc(100% - 50px);">
            <div role="tabpanel" class="tab-pane active" id="schedule-tab">
                @include('Scheduling.schedule')
            </div>
            <div role="tabpanel" class="tab-pane" id="search-tab">
                @include('Scheduling.search')
            </div>

    </div>
</div>
<script>
    $(document).ready(function() {

        $('.col-filter-check').change(function () {

            $type = $(this).data('type');
            $col = $(this).data('col');

            switch($type) {
                case "search":
                    $tablecol = window.searchtable.column( $col + ":name" );
                    break;
            }

            if($(this).prop('checked') === true){
                $tablecol.visible(true);
                $status = 1;
            }else{
                $tablecol.visible(false);
                $status = 0;
            }

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['type'] = $type;
            $data['col'] = $col;
            $data['status'] = $status;

            $post = $.post("/Home/ColSave", $data);

            $post.done(function (data) {
                switch(data['status']) {
                    case "OK":

                        break;
                    case "notlogedin":
                        NotLogedIN();
                        break;
                    default:
                        console.log(data);
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                }
            });

            $post.fail(function () {
                NoReplyFromServer();
            });

        });

        $('#ShowNote').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var note = button.data('note'); // Extract info from data-* attributes
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this);
            modal.find('.modal-body').html(note);
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

            var target = $(e.target).attr("href"); // activated tab
        });

    });

</script>

@stop
