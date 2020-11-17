<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="filestore-search" name="filestore-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('filestore') !!}
    </div>
    <div class="col-md-3">
        <button style="width: 100%;" type="button" class="btn OS-Button"data-toggle="modal" data-target="#fileupload-modal">Upload File</button>
    </div>
</div>

<table id="filestoresearch" class="table">
    <thead>
        <tr>
            <th>Description</th>
            <th>Date</th>
            <th>Uploaded By</th>
            <th>Show</th>
        </tr>
    </thead>
    <tfoot style="visibility: hidden;">
        <tr>
            <th>Description</th>
            <th>Date</th>
            <th>Uploaded By</th>
            <th>Show</th>
        </tr>
    </tfoot>
    <tbody>
        @foreach($client->files as $file)
            <tr>
                <td>{{ $file->description }}</td>
                <td>{{ $file->created_at  }}</td>
                <td>{{ $file->user->getName() }}</td>
                <td><button style="width: 100%;" type="button" class="btn OS-Button" data-toggle="modal" data-fileid="{{ $file->id }}" data-target="#filestore-display-model">Show</button></td>
            </tr>
        @endforeach
    </tbody>
</table>



<script>
$(document).ready(function() {

    // DataTable
    var filestoresearch = $('#filestoresearch').DataTable(

    );

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

    $( "#fileupload" ).children().find(".dataTables_filter").css('display', 'none');
    $( "#fileupload" ).children().find(".dataTables_length").css('display', 'none');
    $( "#fileupload" ).children().find(".dataTables_paginate").css('display', 'none');
    $( "#fileupload" ).children().find(".dataTables_info").css('display', 'none');
    $('#filestoresearch').css('width' , "100%");

});
</script>
