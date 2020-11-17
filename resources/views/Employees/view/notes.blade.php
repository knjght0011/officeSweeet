<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="notes-search" name="notes-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('notes') !!}
    </div>
    <div class="col-md-3">
        <button type="button" class="btn OS-Button" style="width: 100%;" onclick="load_add_note_modal()">Add Note</button>
    </div>
</div>

<table id="notesearch" class="table">
    <thead>
        <tr id="head">
            <th>Note</th>
            <th>Created By</th>
            <th>Date Created</th>
        </tr>
    </thead>
    <tfoot style="visibility: hidden;">
        <tr>
            <th>Note</th>
            <th>Created By</th>
            <th>Date Created</th>
        </tr>
    </tfoot>
    <tbody>
        @foreach($employee->notes1 as $note)

            <tr>
                <td><a data-toggle="modal" href="#ShowNote" data-note="{{ $note->note }}" class="button">{{ $note->getNote() }}</a></td>
                <td>{{ $note->getUser() }}</td>
                <td>{{ $note->formatDateTime_created_at_iso() }}</td>
            </tr>

        @endforeach
    </tbody>
</table>



<div class="modal fade" id="ShowNote" tabindex="-1" role="dialog" aria-labelledby="ShowNote" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Note:</h4>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    $('#ShowNote').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var note = button.data('note'); // Extract info from data-* attributes
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this);
    modal.find('.modal-body').html(note);
});
 
    // DataTable
    notestable = $('#notesearch').DataTable({
        "order": [[ 2, "desc" ]]
    });


    $( "#notes-previous-page" ).click(function() {
        notestable.page( "previous" ).draw('page');
        PageinateUpdate(notestable.page.info(), $('#notes-next-page'), $('#notes-previous-page'),$('#notes-tableInfo'));
    });

    $( "#notes-next-page" ).click(function() {
        notestable.page( "next" ).draw('page');
        PageinateUpdate(notestable.page.info(), $('#notes-next-page'), $('#notes-previous-page'),$('#notes-tableInfo'));
    });

    $('#notes-search').on( 'keyup change', function () {
        notestable.search( this.value ).draw();
        PageinateUpdate(notestable.page.info(), $('#notes-next-page'), $('#notes-previous-page'),$('#notes-tableInfo'));
    });

    PageinateUpdate(notestable.page.info(), $('#notes-next-page'), $('#notes-previous-page'),$('#notes-tableInfo'));

    $( "#notes-tab" ).children().find(".dataTables_filter").css('display', 'none');
    $( "#notes-tab" ).children().find(".dataTables_length").css('display', 'none');
    $( "#notes-tab" ).children().find(".dataTables_paginate").css('display', 'none');
    $( "#notes-tab" ).children().find(".dataTables_info").css('display', 'none');
    $('#notesearch').css('width' , "100%");
} ); 


</script>
