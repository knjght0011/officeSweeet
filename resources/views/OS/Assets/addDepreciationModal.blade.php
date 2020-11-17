    <div class="modal fade" id="depreciation-details-modal" tabindex="-1" role="dialog" aria-labelledby="depreciation-entry-modal"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="">Depreciation Details</h4>
                </div>
                <div class="modal-body" >
                    <table class="table" id="depreciation-details-modal-table">


                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn OS-Button" data-toggle="modal" data-target="#depreciation-entry-modal">Add New Depreciation</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .delete-depreciation{
            width: 100%;
        }
    </style>

    <script>
        $(document).ready(function() {
            $('#depreciation-details-modal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Button that triggered the modal

                var data = button.data('depreciation');

                $('#depreciation-details-modal').data('assetid', button.data('assetid'));

                if(typeof data === "string"){
                    if(data === "none") {
                        //event.preventDefault();
                    }else{
                        $('#depreciation-details-modal-table').empty();
                        $.each(JSON.parse(data), function ($key, $value) {
                            $('#depreciation-details-modal-table').append('<tr><td>'+$value["date"]+'</td><td>$'+$value["amount"]+'</td><td><button data-id="'+$value["id"]+'" class="btn OS-Button delete-depreciation">Delete</button></td></tr>');
                        });
                    }
                }else{
                    $('#depreciation-details-modal-table').empty();
                    $.each(data, function ($key, $value) {
                        $('#depreciation-details-modal-table').append('<tr><td>'+$value["date"]+'</td><td>$'+$value["amount"]+'</td><td><button data-id="'+$value["id"]+'" class="btn OS-Button delete-depreciation">Delete</button></td></tr>');
                    });
                }

                AddDeleteToButtons();
            });
        });
    </script>

    <div class="modal fade" id="depreciation-entry-modal" tabindex="-1" role="dialog" aria-labelledby="depreciation-entry-modal"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="">Add Depreciation</h4>
                </div>
                <div class="modal-body">

                      <div class="input-group">
                        <span class="input-group-addon" for="depreciation-date"><div style="width: 164px;">Date:</div></span>
                        <input style="z-index: 100000;" id="depreciation-date" name="depreciation-date" class="form-control input-md" value="{{ date('Y-m-d') }}" readonly>
                    </div>

                    <div class="input-group">
                        <span width="10em" class="input-group-addon" for="depreciation-amount"><div
                                    style="width: 164px;">Amount/Value: $</div></span>
                        <input id="depreciation-amount" name="depreciation-amount" type="number" class="form-control input-md numonly" value="0.00">
                    </div>

                </div>
                <div class="modal-footer">
                    <button id="depreciation-entry-save" name="depreciation-entry-save" type="button" class="btn OS-Button" value="">
                        Save
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('#depreciation-entry-modal').on('show.bs.modal', function (event) {
                $('#depreciation-entry-modal').data('assetid', $('#depreciation-details-modal').data('assetid'));
            });


            $('.numonly').on('keypress', function(e) {
                keys = ['0','1','2','3','4','5','6','7','8','9','.'];
                return keys.indexOf(event.key) > -1;
            });

            $('#depreciation-asset').select2({
                theme: "bootstrap"
            });

            $('#depreciation-entry-save').click(function(){

                $data = {};
                $data['_token'] = "{{ csrf_token() }}";
                $data['id'] = 0;
                $data['assetid'] = $('#depreciation-entry-modal').data('assetid');
                $data['amount'] = $("#depreciation-amount ").val();
                $data['date'] = $("#depreciation-date").val();

                $("body").addClass("loading");
                ResetServerValidationErrors();

                $post = $.post("/AssetLiability/SaveDepreciation", $data);

                $post.done(function (data) {
                    $("body").removeClass("loading");
                    switch(data['status']) {
                        case "OK":

                            $('#depreciation-details-modal-table').append('<tr><td>'+data["date"]+'</td><td>$'+data["amount"]+'</td><td><button data-id="'+data["id"]+'" class="btn OS-Button delete-depreciation">Delete</button></td></tr>');
                            $('#depreciation-details-button').data('depreciation', data['json']);

                            $row = $('#overview-table').DataTable().row('.selected').data();
                            $row.depreciation = data['json'];
                            $row.amountlessdepreciation = data['assetlessdepriciation'];
                            $('#overview-table').DataTable().row('.selected').data($row);

                            $('#depreciation-entry-modal').modal('hide');

                            AddDeleteToButtons();
                            break;
                        case "notfound":
                            $.dialog({
                                title: 'Oops...',
                                content: 'Unknown Response from server. Please refresh the page and try again.'
                            });
                            break;
                        case "validation":
                            ServerValidationErrors(data['errors']);
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

            $('#depreciation-date').datepicker({
                changeMonth: true,
                changeYear: true,
                controlType: 'select',
                parse: "loose",
                dateFormat: "yy-mm-dd",
            });
        });

        function AddDeleteToButtons() {

            $('.delete-depreciation').click(function () {

                $button = $(this);

                $data = {};
                $data['_token'] = "{{ csrf_token() }}";
                $data['id'] = $button.data('id');

                $("body").addClass("loading");
                ResetServerValidationErrors();

                $post = $.post("/AssetLiability/DeleteDepreciation", $data);

                $post.done(function (data) {
                    $("body").removeClass("loading");
                    switch(data['status']) {
                        case "OK":

                            $button.parent().parent().remove();
                            $('#depreciation-details-button').data('depreciation', data['json']);

                            $row = $('#overview-table').DataTable().row('.selected').data();
                            $row.depreciation = data['json'];
                            $row.amountlessdepreciation = data['assetlessdepriciation'];
                            $('#overview-table').DataTable().row('.selected').data($row);


                            break;
                        case "notfound":
                            $.dialog({
                                title: 'Oops...',
                                content: 'Unknown Response from server. Please refresh the page and try again.'
                            });
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

        }
    </script>

