<div class="modal fade" id="filestore-display-model" tabindex="-1" role="dialog" aria-labelledby="filestore-display-model" aria-hidden="true">
    <div style="margin: 2.5vh auto; width: 95vw" class="modal-dialog" role="document">
        <div style="height: 95vh; width: 95vw;" class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">View File</h4>
            </div>
            <div style="height: calc(95vh - 120px);" class="modal-body">
                <iframe style="width: 100%; height: 100%;"id="filestore-display-frame" src="{{ url('images/loading4.gif') }}"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#filestore-display-model').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('fileid');

        if(typeof id === 'undefined'){
            id = $(this).data('fileid');
        }

        var url = "/FileStore/ShowFile/"; // Extract info from data-* attributes
        $('#filestore-display-frame').attr("src", url + id);
    });

    $('#filestore-display-model').on('hide.bs.modal', function (event) {
        $('#filestore-display-frame').attr("src", "{{ url('images/loading4.gif') }}");
    });
});
</script>