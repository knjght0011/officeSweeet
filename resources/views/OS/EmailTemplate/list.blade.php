

    <div class="row" >
        <div class="col-md-3">
            <div class="input-group ">
                <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
                <input id="search" name="search" type="text" placeholder="" value="" class="form-control" data-validation-label="Search" data-validation-required="false" data-validation-type="">
            </div>
        </div>

        <div class="col-md-3">
            {{--
                        <div class="input-group">
                            <span class="input-group-addon" for="Group"><div style="width: 7em;">Group:</div></span>
                            <select id="Group" name="Group" type="text" placeholder="Group" class="form-control">
                                <option value="all">All</option>
                                <option value="client">Client\Prospect</option>
                                <option value="vendor">Vendor</option>
                                <option value="employee">Team/Staff</option>
                                <option value="general">General</option>
                            </select>
                        </div>--}}
        </div>

        <div class="col-md-3">
            {{--
            <div class="input-group ">
                <span class="input-group-addon" for="user-filter"><div style="width: 7em;">Created By:</div></span>
                <select id="user-filter" name="user-filter" type="text" placeholder="user-filter" class="form-control">
                    <option value="all">All</option>
                    @foreach($users as $user)
                        <option value="{{ $user->getShortName() }}">{{ $user->getShortName() }}</option>
                    @endforeach
                </select>
            </div>--}}
        </div>

        <div class="col-md-3">
            <div class="input-group ">
                <span class="input-group-addon" for="length"><div style="width: 7em;">Show:</div></span>
                <select id="length" name="length" type="text" placeholder="choice" class="form-control">
                    <option value="10">10 entries</option>
                    <option value="25">25 entries</option>
                    <option value="50">50 entries</option>
                    <option value="100">100 entries</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 15px;">
        <div class="col-md-3">
            <button data-toggle="modal" class="btn OS-Button btn-sm" style="width: 100%;" onclick="GoToPage('/Email/Template/New')">New Template
            </button>
        </div>
        <div class="col-md-3">
            <button data-toggle="modal" data-target="#UploadTemplate" class="btn OS-Button btn-sm" type="button"
                    style="width: 100%;">Upload Template
            </button> <!--<a id="link" href="/Templates/Upload">-->
        </div>
        <div class="col-md-3">
            <button id="preview-selected" class="btn OS-Button btn-sm" type="button" style="width: 100%;">Preview Selected</button>
        </div>
        <div class="col-md-3">
            <button id="delete-selected" class="btn OS-Button btn-sm" type="button" style="width: 100%;">
                Delete Selected
            </button>
        </div>
    </div>

    <form action="/Email/Template/Upload" method="post" enctype="multipart/form-data">
        <div class="modal fade" id="UploadTemplate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Upload</h4>
                    </div>
                    <div class="modal-body">
                        <p>Please select a Image, Word Document or HTML file to upload.</p>
                        <p>HTML should use inline styles and base64 encoded images</p>

                        <input name="_token" type="hidden" value="{{ csrf_token() }}">
                        <input style="width: 100%;" type="file" class="btn OS-Button btn-sm" name="fileToUpload" id="fileToUpload">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="row" style="margin-top: 15px;">
        <div class="col-md-2">
            <button id="previous-page" name="previous-page" type="button" class="btn OS-Button" style="width: 100%;">Previous</button>
        </div>
        <div class="col-md-8" id="tableInfo" style="text-align: center;">

        </div>
        <div class="col-md-2">
            <button id="next-page" name="next-page" type="button" class="btn OS-Button" style="width: 100%;">Next</button>
        </div>
    </div>

    <table id="template-list" class="table">
        <thead>
        <tr id="head">
            <th>Name</th>
            <th></th>
        </tr>
        </thead>
        <tfoot style="visibility: hidden;">
        <tr>
            <th>Name</th>
            <th></th>
        </tr>
        </tfoot>
        <tbody>

        @foreach($templates as $template)
            <tr>
                <td>{{ $template->name }}</td>
                <td>{{ $template->id }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="modal fade" id="TemplatePreviewModel" tabindex="-1" role="dialog" aria-labelledby="TemplatePreviewModel" aria-hidden="true">
        <div style="margin: 2.5vh auto; width: 95vw" class="modal-dialog" role="document">
            <div style="height: 95vh; width: 95vw;" class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="TemplatePreviewTitle">View Template</h4>
                </div>
                <div style="height: calc(95vh - 120px);" class="modal-body">
                    <iframe style="width: 100%; height: 100%;" id="TemplatePreviewFrame" src="{{ url('images/loading4.gif') }}"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {

            @if (session('newid'))
            $('#TemplatePreviewModel').data('id', "{{ session('newid') }}");
            $('#TemplatePreviewFrame').attr("src", "/Email/Template/Preview/{{ session('newid') }}");
            $('#TemplatePreviewModel').modal('show');
            @endif

            $('#TemplatePreviewModel').on('show.bs.modal', function (event) {
                $id = $(this).data('id');
                $('#TemplatePreviewFrame').attr("src", "/Email/Template/Preview/" + $id);
            });

            $('#TemplatePreviewModel').on('hide.bs.modal', function (event) {
                $('#TemplatePreviewFrame').attr("src", "{{ url('images/loading4.gif') }}");
            });

            var table = $('#template-list').DataTable({
                "columnDefs": [
                    { "targets": [1],"visible": false}
                ],
            });

            $('#template-list tbody').on( 'click', 'tr', function () {
                $row = $(this);
                if ( $(this).hasClass('selected') ) {
                    $(this).removeClass('selected');
                }
                else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            } );

            $( "#previous-page" ).click(function() {
                table.page( "previous" ).draw('page');
                PageinateUpdate(table.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
            });

            $( "#next-page" ).click(function() {
                table.page( "next" ).draw('page');
                PageinateUpdate(table.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
            });

            $('#search').on( 'keyup change', function () {
                table.search( this.value ).draw();
                PageinateUpdate(table.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
            });

            $('#length').on( 'change', function () {
                table.page.len( this.value ).draw();
                PageinateUpdate(table.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));
            });

            PageinateUpdate(table.page.info(), $('#next-page'), $('#previous-page'),$('#tableInfo'));

            $('#template-list').css('width', '100%');
            $( "#EmailTemplateManager" ).children().find(".dataTables_filter").css('display', 'none');
            $( "#EmailTemplateManager" ).children().find(".dataTables_length").css('display', 'none');
            $( "#EmailTemplateManager" ).children().find(".dataTables_paginate").css('display', 'none');
            $( "#EmailTemplateManager" ).children().find(".dataTables_info").css('display', 'none');

            $('#preview-selected').click(function () {
                $row = table.row('.selected').data();

                if ($row === undefined || $row === null) {
                    $.dialog({
                        title: 'Oops..',
                        content: 'Please Select a Row.'
                    });
                }else{
                    $('#TemplatePreviewModel').data('id', $row[1]);
                    $('#TemplatePreviewModel').modal('show');
                }

            });

            $('#delete-selected').click(function () {
                $row = table.row('.selected').data();
                if($row != undefined){
                    $.confirm({
                        title: 'You are about to delete a template.',
                        content: '',
                        buttons: {
                            confirm: function () {
                                $row = table.row('.selected').data();

                                $data = {};
                                $data['_token'] = "{{ csrf_token() }}";
                                $data['id'] = $row[1];

                                $("body").addClass("loading");

                                $post = $.post("/Email/Template/Delete", $data);

                                $post.done(function( data ) {
                                    console.log(data);
                                    $("body").removeClass("loading");
                                    switch(data['status']) {
                                        case "OK":
                                            table.row('.selected').remove().draw( false );
                                            break;
                                        case "notfound":
                                            $.dialog({
                                                title: 'Oops...',
                                                content: 'Unknown Response from server. Please refresh the page and try again.'
                                            });
                                            break;
                                        default:
                                            console.log(data);
                                            $.dialog({
                                                title: 'Oops...',
                                                content: 'Unknown Response from server. Please refresh the page and try again.'
                                            });
                                    }
                                });

                                $post.fail(function()
                                {
                                    NoReplyFromServer();
                                });
                            },
                            cancel: function () {

                            },
                        }
                    });
                }else{
                    $.dialog({
                        title: 'Opps...',
                        content: 'Nothing Selected...'
                    });
                }

            });
        });
    </script>
