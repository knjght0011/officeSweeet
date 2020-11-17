<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="filestore-search" name="filestore-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>

    <button id="filestore-display-button" type="button" class="btn OS-Button" data-toggle="modal">Show File</button>
    <button  id="filestore-description-button" type="button" class="btn OS-Button" data-toggle="modal">Edit Description</button>
    <button  id="filestore-delete-button" type="button" class="btn OS-Button" data-toggle="modal" disabled>Delete File</button>
    <button  type="button" class="btn OS-Button"data-toggle="modal" data-target="#fileupload-modal">Upload Image/PDF</button>

</div>
<div class="row" style="margin-top: 5px;">
    <div class="col-md-12">
        {!! PageElement::TableControl('filestore') !!}
    </div>
</div>

<table id="filestoresearch" class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>candelete</th>
            <th>Description</th>
            <th>Date</th>
            <th>Uploaded By</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>ID</th>
            <th>candelete</th>
            <th>Description</th>
            <th>Date</th>
            <th>Uploaded By</th>
        </tr>
    </tfoot>
    <tbody>
        @foreach($vendor->files as $file)
            <tr>
                <td>{{ $file->id }}</td>
                <td>
                    @if($file->hasLinks())
                        0
                    @else
                        1
                    @endif
                </td>
                <td>{{ $file->description }}</td>
                <td>{{ $file->created_at  }}</td>
                <td>{{ $file->uploader->getName() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="modal fade" id="filestore-description-model" tabindex="-1" role="dialog" aria-labelledby="FileUpload" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="">Edit Description:</h4>
            </div>
            <div class="modal-body">
                <textarea id="filestore-description-description" class="form-control input-md" rows="15" style="width: 100%; resize: none;"></textarea>
                <input id="filestore-description-id" style="visibility: hidden;">
            </div>
            <div class="modal-footer">
                <button id="filestore-description-save" name="filestore-description-save" type="button" class="btn OS-Button" value="">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#filestore-description-model').on('show.bs.modal', function (event) {
        //var button = $(event.relatedTarget); // Button that triggered the modal
        //var mode = button.data('mode');
        $('#filestore-description-description').val($(this).data('description'));
        $('#filestore-description-id').val($(this).data('fileid'));

    });

    $('#filestore-description-save').click(function (evemt) {
        $("body").addClass("loading");

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['id'] = $('#filestore-description-id').val();
        $data['description'] = $('#filestore-description-description').val();

        $post = $.post("/FileStore/Description", $data);

        $post.done(function (data) {
            $("body").removeClass("loading");
            if ($.isNumeric(data)) {

                $row = filestoresearch.row('.selected').data();

                $row['2'] = $data['description'];

                filestoresearch.row('.selected').data($row);

                filestoresearch.draw();

                $('#filestore-description-model').modal('hide');
                SavedSuccess();
            }else{
                ServerValidationErrors(data);
            }
        });

        $post.fail(function () {
            NoReplyFromServer();
        });

    });

    // DataTable
    var filestoresearch = $('#filestoresearch').DataTable({
        "columnDefs": [
            { "targets": [0,1],"visible": false}
        ],
        "order": [[ 3, "desc" ]]
    });

    $('#filestoresearch tbody').on( 'click', 'tr', function () {
        $row = $(this);
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            filestoresearch.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        $row = filestoresearch.row('.selected').data();
        if(typeof $row === 'undefined'){
            $('#filestore-delete-button').attr("disabled", "disabled");
        }else{

            if($row[1] === "1"){
                $('#filestore-delete-button').removeAttr("disabled");
            }else{
                $('#filestore-delete-button').attr("disabled", "disabled");
            }
        }
    } );

    $( "#filestore-previous-page" ).click(function() {
        filestoresearch.page( "previous" ).draw('page');
        PageinateUpdate(filestoresearch.page.info(), $('#filestore-next-page'), $('#filestore-previous-page'),$('#filestore-tableInfo'));
    });

    $( "#filestore-next-page" ).click(function() {
        filestoresearch.page( "next" ).draw('page');
        PageinateUpdate(filestoresearch.page.info(), $('#filestore-next-page'), $('#filestore-previous-page'),$('#filestore-tableInfo'));
    });

    $('#filestore-search').on( 'keyup change', function () {
        filestoresearch.search( this.value ).draw();
        PageinateUpdate(filestoresearch.page.info(), $('#filestore-next-page'), $('#filestore-previous-page'),$('#filestore-tableInfo'));
    });

    PageinateUpdate(filestoresearch.page.info(), $('#filestore-next-page'), $('#filestore-previous-page'),$('#filestore-tableInfo'));

    $( "#filestore" ).children().find(".dataTables_filter").css('display', 'none');
    $( "#filestore" ).children().find(".dataTables_length").css('display', 'none');
    $( "#filestore" ).children().find(".dataTables_paginate").css('display', 'none');
    $( "#filestore" ).children().find(".dataTables_info").css('display', 'none');
    $('#filestoresearch').css('width' , "100%");

    $('#filestore-display-button').click(function(){
        $row = filestoresearch.row('.selected').data();
        if(typeof $row === 'undefined'){

            $.dialog({
                title: 'Oops...',
                content: 'Please select a table entry.'
            });

        }else {
            $('#filestore-display-model').data('fileid', $row[0]);
            $('#filestore-display-model').modal('show');
        }
    });

    $('#filestore-description-button').click(function(){
        $row = filestoresearch.row('.selected').data();
        if(typeof $row === 'undefined'){

            $.dialog({
                title: 'Oops...',
                content: 'Please select a table entry.'
            });

        }else{
            $('#filestore-description-model').data('fileid' , $row[0]);
            $('#filestore-description-model').data('description' , $row[2]);
            $('#filestore-description-model').modal('show');
        }
    });

    $('#filestore-delete-button').click(function() {
        $row = filestoresearch.row('.selected').data();
        if (typeof $row === 'undefined') {

            $.dialog({
                title: 'Oops...',
                content: 'Please select a table entry.'
            });

        } else {
            $("body").addClass("loading");

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['id'] = $row['0'];

            $post = $.post("/FileStore/Delete", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                console.log(data);
                if(data = "done"){
                    SavedSuccess("File Deleted.");
                    filestoresearch.row('.selected').remove().draw();
                }else{
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unable to find file. Please refresh the page and try again.'
                    });
                }

            });

            $post.fail(function () {
                NoReplyFromServer();
            });
        }
    });
});
</script>
