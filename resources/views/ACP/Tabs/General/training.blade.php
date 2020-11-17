<div class="row" style="margin-top: 20px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="training-search"><div>Search:</div></span>
            <input id="training-search" name="training-search" type="text" class="form-control" >
        </div>
    </div>

    <div class="col-md-6">
        {!! PageElement::TableControl('training') !!}
    </div>

    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="training-length"><div>Show:</div></span>
            <select id="training-length" name="training-length" type="text" placeholder="choice" class="form-control">
                <option value="10">10 entries</option>
                <option value="15">15 entries</option>
                <option value="20">20 entries</option>
                <option value="25">25 entries</option>
                <option value="50">50 entries</option>
                <option value="100">100 entries</option>
            </select>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 20px;">
    <div class="col-md-3">
        <button data-toggle="modal" data-target="#AddTrainingModal" type="button" class="btn OS-Button" style="width: 100%;">Add New Training</button>
    </div>
    <div class="col-md-3">
        <button data-toggle="modal" data-target="#EditTrainingModal" type="button" class="btn OS-Button" style="width: 100%;">Edit Selected Training</button>
    </div>
    <div class="col-md-3">
        <button id="training-delete" type="button" class="btn OS-Button" style="width: 100%;">Delete Selected Training</button>
    </div>
</div>

<table class="table" id="trainingtable">
    <thead>
        <tr>
            <th>id</th>
            <th>Title</th>
            <th>Comments</th>
            <th>Link</th>
            <th>Quiz</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>id</th>
            <th>Title</th>
            <th>Comments</th>
            <th>Link</th>
            <th>Quiz</th>
        </tr>
    </tfoot>
    <tbody>
    @foreach($TrainingModules as $module)
        <tr>
            <td>{{ $module->id }}</td>
            <td>{{ $module->title }}</td>
            <td>{{ $module->comments }}</td>
            <td>{{ $module->link }}</td>
            <td>{{ $module->quiz }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="modal fade" id="AddTrainingModal" tabindex="-1" role="dialog" aria-labelledby="#AddInputModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Add Training:</h4>
            </div>
            <div class="modal-body">
                <div class='input-group'>
                    <span class='input-group-addon' for='input-label'><div style='min-width: 7em;'>Title:</div></span>
                    <input id="training-title-add"  name="input-label" class="form-control">
                </div>
                <div class='input-group'>
                    <span class='input-group-addon' for='input-label'><div style='min-width: 7em;'>Comments:</div></span>
                    <textarea id="training-comments-add" class="form-control" rows="2" style="resize: none;"></textarea>
                </div>
                <div class='input-group'>
                    <span class='input-group-addon' for='input-label'><div style='min-width: 7em;'>Link:</div></span>
                    <input id="training-link-add"  name="input-label" class="form-control">
                </div>
                <div class='input-group'>
                    <span class='input-group-addon' for='input-label'><div style='min-width: 7em;'>Quiz:</div></span>
                    <input id="training-quiz-add"  name="input-label" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button id="training-add" name="training-add" type="button" class="btn OS-Button" value="">Add</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="EditTrainingModal" tabindex="-1" role="dialog" aria-labelledby="#AddInputModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Edit Training:</h4>
            </div>
            <div class="modal-body">
                <input id="training-id-edit"  name="input-label" class="form-control" style="display: none;">
                <div class='input-group'>
                    <span class='input-group-addon' for='input-label'><div style='min-width: 7em;'>Title:</div></span>
                    <input id="training-title-edit"  name="input-label" class="form-control">
                </div>
                <div class='input-group'>
                    <span class='input-group-addon' for='input-label'><div style='min-width: 7em;'>Comments:</div></span>
                    <textarea id="training-comments-edit" class="form-control" rows="2" style="resize: none;"></textarea>
                </div>
                <div class='input-group'>
                    <span class='input-group-addon' for='input-label'><div style='min-width: 7em;'>Link:</div></span>
                    <input id="training-link-edit"  name="input-label" class="form-control">
                </div>
                <div class='input-group'>
                    <span class='input-group-addon' for='input-label'><div style='min-width: 7em;'>Quiz:</div></span>
                    <input id="training-quiz-edit"  name="input-label" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button id="training-edit" name="training-edit" type="button" class="btn OS-Button" value="">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('#EditTrainingModal').on('show.bs.modal', function (event) {
            $row = trainingtable.row('.selected').data();

            $('#training-id-edit').val($row[0]);
            $('#training-title-edit').val($row[1]);
            $('#training-comments-edit').val($row[2]);
            $('#training-link-edit').val($row[3]);

        });


        var trainingtable = $('#trainingtable').DataTable({
            "pageLength": 10,
            "columnDefs": [
                {
                    "targets": [0],
                    "visible": false
                }
            ]
        });

        $('#trainingtable tbody').on( 'click', 'tr', function () {
            $row = $(this);
            if ( $(this).hasClass('selected') ) {
                $(this).removeClass('selected');
            }
            else {
                trainingtable.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
            }
        } );


        $( "#training-previous-page" ).click(function() {
            trainingtable.page( "previous" ).draw('page');
            PageinateUpdate(trainingtable.page.info(), $('#training-next-page'), $('#training-previous-page'),$('#training-tableInfo'));
        });

        $( "#training-next-page" ).click(function() {
            trainingtable.page( "next" ).draw('page');
            PageinateUpdate(trainingtable.page.info(), $('#training-next-page'), $('#training-previous-page'),$('#training-tableInfo'));
        });

        $('#training-search').on( 'keyup change', function () {
            trainingtable.search( this.value ).draw();
            PageinateUpdate(trainingtable.page.info(), $('#training-next-page'), $('#training-previous-page'),$('#training-tableInfo'));
        });

        $('#training-length').on( 'change', function () {
            trainingtable.page.len( this.value ).draw();
            PageinateUpdate(trainingtable.page.info(), $('#training-next-page'), $('#training-previous-page'),$('#training-tableInfo'));
        });

        PageinateUpdate(trainingtable.page.info(), $('#training-next-page'), $('#training-previous-page'),$('#training-tableInfo'));

        $( "#generaltraining" ).children().find(".dataTables_filter").css('display', 'none');
        $( "#generaltraining" ).children().find(".dataTables_length").css('display', 'none');
        $( "#generaltraining" ).children().find(".dataTables_paginate").css('display', 'none');
        $( "#generaltraining" ).children().find(".dataTables_info").css('display', 'none');
        $('#trainingtable').css('width' , "100%");

        $('#training-add').click(function(){

            $("body").addClass("loading");

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['id'] = "0";
            $data['title'] = $('#training-title-add').val();
            $data['comments'] = $('#training-comments-add').val();
            $data['link'] = $('#training-link-add').val();
            $data['quiz'] = $('#training-quiz-add').val();

            $post = $.post("/ACP/Training/Save", $data);

            $post.done(function( data ) {

                console.log(data);
                $("body").removeClass("loading");
                if(data['status'] === 'OK'){
                    $('#trainingtable').DataTable().row.add( [
                        data['training']['id'],
                        data['training']['title'],
                        data['training']['comments'],
                        data['training']['link'],
                        data['training']['quiz'],
                    ] ).draw( false );
                    $('#AddTrainingModal').modal('hide');
                    //$('#trainingtable').DataTable().search( data['training']['title'] ).draw();
                }else{
                    alert("Error");
                }
            });

            $post.fail(function()
            {
                $("body").removeClass("loading");
                alert("Error");
            });
        });

        $('#training-edit').click(function(){

            $("body").addClass("loading");

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['id'] = $('#training-id-edit').val();;
            $data['title'] = $('#training-title-edit').val();
            $data['comments'] = $('#training-comments-edit').val();
            $data['link'] = $('#training-link-edit').val();
            $data['quiz'] = $('#training-quiz-edit').val();

            $post = $.post("/ACP/Training/Save", $data);

            $post.done(function( data ) {

                console.log(data);
                $("body").removeClass("loading");
                if(data['status'] === 'OK'){
                    $('#trainingtable').DataTable().row('.selected').data([
                        data['training']['id'],
                        data['training']['title'],
                        data['training']['comments'],
                        data['training']['link'],
                        data['training']['quiz'],
                    ] ).draw( false );
                    $('#EditTrainingModal').modal('hide');
                }else{
                    alert("Error");
                }
            });

            $post.fail(function()
            {
                $("body").removeClass("loading");
                alert("Error");
            });
        });

        $('#training-delete').click(function () {
            $.confirm({
                title: 'Are you sure you want to delete this?',
                buttons: {
                    confirm: function () {
                        $row = trainingtable.row('.selected').data();

                        $data = {};
                        $data['_token'] = "{{ csrf_token() }}";
                        $data['id'] = $row[0];

                        $("body").addClass("loading");

                        $post = $.post("/ACP/Training/Delete", $data);

                        $post.done(function( data ) {
                            console.log(data);
                            $("body").removeClass("loading");
                            if(data['status'] === 'OK'){
                                $('#trainingtable').DataTable().row('.selected').remove().draw( false );
                            }else{
                                alert("Error");
                            }
                        });

                        $post.fail(function()
                        {
                            $("body").removeClass("loading");
                            alert("Error");
                        });
                    },
                    cancel: function () {

                    },
                }
            });
        });
    });

</script>
