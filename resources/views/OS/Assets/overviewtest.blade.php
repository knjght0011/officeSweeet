

    <style>
        .input-25 {
            width: 25%;
        }

        .input-50 {
            width: 50%;
            height: 82px;
        }

        .input-75 {
            width: 70%;
            height: 82px;
        }

        .input-100 {
            width: 100%;
        }

        .nopadding {
            padding: 0 !important;
            margin: 0 !important;
            display: table !important;
        }

        .spancontainer {
            width: 1% !important;
        }
    </style>

    <h3 style="margin-top: 10px;"></h3>

    <div class="row" style="margin-top: 10px;">
        <div class="col-md-3">
            <!--
            <button id="new-asset" type="button" class="btn OS-Button" data-toggle="modal"
                    data-target="#depreciation-entry-modal"
                    style="width: 100%;">Add Depreciation
            </button>
            -->
        </div>
        <div class="col-md-6">
            <button id="new-asset" type="button" class="btn OS-Button" data-toggle="modal"
                    data-target="#new-entry-modal"
                    style="width: 100%;">Add New Asset/Liability
            </button>
        </div>

        <div class="col-md-3">

            <button id="delete-entry" type="button" class="btn OS-Button"
                    style="width: 100%;" disabled>Delete Entry
            </button>

        </div>
    </div>
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-3">
            <div class="input-group ">
                <span class="input-group-addon" for="overview-search"><div style="width: 7em;">Search:</div></span>
                <input id="overview-search" name="overview-search" type="text" placeholder="" value=""
                       class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            {!! PageElement::TableControl('overview') !!}
        </div>
        <div class="col-md-3">
            <div class="input-group ">
                <span class="input-group-addon" for="overview-length"><div style="width: 7em;">Show:</div></span>
                <select id="overview-length" name="overview-length" type="text" placeholder="choice"
                        class="form-control">
                    <option value="10">10 entries</option>
                    <option value="25">25 entries</option>
                    <option value="50">50 entries</option>
                    <option value="100">100 entries</option>
                </select>
            </div>
        </div>
    </div>

    <table id="overview-table" class="table"  style="width: 100%;">
        <thead>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Date</th>
            <th>Amount($)</th>
            <th>Amount Less Depreciation($)</th>
            <th>Type</th>
            <th>File</th>
            <th>id</th>
            <th>catagorys</th>
            <th>comments</th>
            <th>file_id</th>
            <th>journaltracked</th>
            <th>can toggle journal</th>
            <th>depreciation</th>
            <th>accounts</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Date</th>
            <th>Amount($)</th>
            <th>Amount Less Depreciation($)</th>
            <th>Type</th>
            <th>File</th>
            <th>id</th>
            <th>catagorys</th>
            <th>comments</th>
            <th>file_id</th>
            <th>journaltracked</th>
            <th>can toggle journal</th>
            <th>depreciation</th>
            <th>accounts</th>
        </tr>
        </tfoot>
        <tbody>

        <tr class="">
            <td></td>
            <td>Journal Balance</td>
            <td>{{ FormatingHelper::TodayISO() }}</td>
            @if($endingbalance != null)
            <td>
                {{ number_format($endingbalance, 2) }}
            </td>
            @else
            <td>
                <span class="glyphicon glyphicon-question-sign" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Unable to Show Journal Ballence as month end not up to date"></span> Unknown
            </td>
            @endif
            <td></td>
            <td>Asset</td>
            <td></td>
            <td>journal</td>
            <td>
                @if($journalcatagorys === null)
                []
                @else
                {{ $journalcatagorys }}
                @endif
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr class="">
            <td></td>
            <td>Inventory</td>
            <td>{{ FormatingHelper::TodayISO() }}</td>
            <td>{{ number_format($inventorytotal, 2) }}</td>
            <td></td>
            <td>Liability</td>
            <td></td>
            <td>inventory</td>
            <td>
                @if($inventorycatagorys === null)
                    []
                @else
                    {{ $inventorycatagorys }}
                @endif
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        @foreach($assets as $asset)
            <tr class="acctualrow">
                <td><span id="expandindicator" class="glyphicon glyphicon-plus" aria-hidden="true"></span></td>
                <td>{{ $asset->name }}</td>
                <td>{{ $asset->formatDateISO() }}</td>
                <td>{{ number_format($asset->amount, 2, '.', '') }}</td>
                <td>
                    @if($asset->type === "a")
                        {{ number_format($asset->AmountLessDepreciation(), 2, '.', '') }}
                    @endif
                </td>
                <td>{{ $asset->TypeString() }}</td>
                <td>
                    @if($asset->filestore_id != null)
                        <button style="width: 100%; padding-top: 2px; padding-bottom: 2px;" type="button"
                                class="btn OS-Button" data-toggle="modal" data-target="#filestore-display-model"
                                data-fileid="{{ $asset->filestore_id }}">Show Attachment
                        </button>
                    @endif
                </td>
                <td>{{ $asset->id }}</td>
                <td>{{ $asset->catagoryJson() }}</td>

                <td>{{ $asset->comments }}</td>
                <td>{{ $asset->filestore_id }}</td>
                <td>@if($asset->journal === 1)
                        checked
                </td>@endif
                <td>@if($asset->CantEdit())
                        disabled
                </td>@endif
                <td>{{ $asset->DepreciationJSON() }}</td>
                <td>{{ $asset->accountJson() }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    @include('OS.Assets.addmodal')
    @include('OS.Assets.addDepreciationModal')

    @include('OS.FileStore.fileuploadmodel')
    @include('OS.FileStore.displayfile')

    <script>
        $(document).ready(function () {

            // DataTable
            var overviewtable = $('#overview-table').DataTable({
                "columns": [
                    {"data": "expand"},
                    {"data": "name"},
                    {"data": "date"},
                    {"data": "amount"},
                    {"data": "amountlessdepreciation"},
                    {"data": "type"},
                    {"data": "file"},
                    {"data": "id"},
                    {"data": "catagorys"},
                    {"data": "comments"},
                    {"data": "file_id"},
                    {"data": "journaltracked"},
                    {"data": "journal-can-toggle"},
                    {"data": "depreciation"},
                    {"data": "accounts"}
                ],
                "columnDefs": [
                    {"targets": [7, 8, 9, 10, 11, 12, 13, 14], "visible": false}
                ]
            });


            $('#overview-table tbody').on('click', 'tr.acctualrow', function () {
                $row = $(this);


                if ($('#changed').val() === "1") {
                    $.confirm({
                        title: 'Confirm!',
                        content: 'You have unsaved changes, what would you like to do?',
                        buttons: {
                            Save: function () {
                                Save($row, overviewtable);

                            },
                            "Discard Changes": function () {
                                expand($row, overviewtable);
                            }
                        }

                    });
                } else {
                    expand($row, overviewtable)
                }

            });

            $("#overview-previous-page").click(function () {
                overviewtable.page("previous").draw('page');
                PageinateUpdate(overviewtable.page.info(), $('#overview-next-page'), $('#overview-previous-page'), $('#overview-tableInfo'));
            });

            $("#overview-next-page").click(function () {
                overviewtable.page("next").draw('page');
                PageinateUpdate(overviewtable.page.info(), $('#overview-next-page'), $('#overview-previous-page'), $('#overview-tableInfo'));
            });

            $('#overview-search').on('keyup change', function () {
                overviewtable.search(this.value).draw();
                PageinateUpdate(overviewtable.page.info(), $('#overview-next-page'), $('#overview-previous-page'), $('#overview-tableInfo'));
            });

            $('#overview-length').on('change', function () {
                overviewtable.page.len(this.value).draw();
                PageinateUpdate(overviewtable.page.info(), $('#overview-next-page'), $('#overview-previous-page'), $('#overview-tableInfo'));
            });


            $('#overview-filter2').on('change', function () {

                if (this.value === "all") {
                    overviewtable
                        .columns(6) //changethis
                        .search("", true)
                        .draw();
                } else {

                    overviewtable
                        .columns(6) //changethis
                        .search(this.value, true)
                        .draw();

                }

                PageinateUpdate(overviewtable.page.info(), $('#overview-next-page'), $('#overview-previous-page'), $('#overview-tableInfo'));

            });

            $('#overview-filter2').change();

            $('#overview-filter1').on('change', function () {

                if (this.value === "all") {
                    overviewtable
                        .columns(6) //changethis
                        .search("", true)
                        .draw();
                } else {

                    overviewtable
                        .columns(6) //changethis
                        .search(this.value, true)
                        .draw();

                }

                PageinateUpdate(overviewtable.page.info(), $('#overview-next-page'), $('#overview-previous-page'), $('#overview-tableInfo'));

            });

            $('#overview-filter1').change();

            PageinateUpdate(overviewtable.page.info(), $('#overview-next-page'), $('#overview-previous-page'), $('#overview-tableInfo'));

            $(".dataTables_filter").css('display', 'none');
            $(".dataTables_length").css('display', 'none');
            $(".dataTables_paginate").css('display', 'none');
            $(".dataTables_info").css('display', 'none');
            $("#overviewtable").css("width", "100%");

            $('#delete-entry').click(function () {

                $row = overviewtable.row('.selected').data();
                if($row === undefined){
                    event.preventDefault();
                    $.dialog({
                        title: 'Oops...',
                        content: 'Please Select a Service from the table.'
                    });
                }else{
                    $.confirm({
                        title: 'Confirm!',
                        content: 'Are you sure you want to delete this entry and any linked depreciation?',
                        buttons: {
                            confirm: function () {
                                DeleteRow($row['id']);
                            },
                            cancel: function () {

                            },
                        }
                    });
                }

            });

        });

        function DeleteRow($id) {

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['id'] = $id;

            $("body").addClass("loading");
            ResetServerValidationErrors();

            $post = $.post("/AssetLiability/Delete", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        $('#overview-table').DataTable().row('.selected').remove().draw( false );
                        break;
                    case "injournal":
                        $.dialog({
                            title: 'Oops...',
                            content: 'Cannot delete an asset that is toggled to show in the Journal.'
                        });
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

        }

        function expand($row, $table) {


            var tr = $row.closest('tr');
            var row = $table.row(tr);

            if ($row.hasClass('selected')) {
                $row.removeClass('selected');
                row.child.hide();
                $span = $row.find('#expandindicator');
                $span.removeClass('glyphicon-minus');
                $span.addClass('glyphicon-plus');

            }
            else {
                if ($table.$('tr.selected').length > 0) {
                    $table.row('.selected').child.hide();

                    $span = $table.$('tr.selected').find('#expandindicator');
                    $span.removeClass('glyphicon-minus');
                    $span.addClass('glyphicon-plus');

                    $table.$('tr.selected').removeClass('selected');

                }
                $row.addClass('selected');

                $span = $row.find('#expandindicator');
                $span.removeClass('glyphicon-plus');
                $span.addClass('glyphicon-minus');

                row.child(format(row.data())).show();
                DropDownFunctions(row.data());

                if(row.data().type === "Asset"){
                    $('#depreciation-details-button').prop('disabled', false);
                }else{
                    $('#depreciation-details-button').prop('disabled', true);
                }

                debugger;
                if(row.data().journaltracked === "checked"){
                    $('#delete-entry').prop('disabled', true);
                }else{
                    $('#delete-entry').prop('disabled', false);
                }

            }
        }

        function format(d) {
            // `d` is the original data object for the row


            var obj = jQuery.parseJSON(d.catagorys);

            $catagorys = "";
            $.each(obj, function (index, value) {
                $catagorys = $catagorys + "<option value='" + value + "' >" + index + "</option>";
            });

            var obj = jQuery.parseJSON(d.accounts);

            $accounts= "";
            $.each(obj, function (index, value) {
                $accounts = $accounts + "<option value='" + value + "' >" + index + "</option>";
            });



            switch (d.type) {
                case "Asset":
                    $types = '<option value="a" selected>Asset</option><option value="l">Liability</option><option value="e">Equity</option>';
                    $buttontype = "asset";
                    break;
                case "Liability":
                    $types = '<option value="a">Asset</option><option value="l" selected>Liability</option><option value="e">Equity</option>';
                    $buttontype = "liability";
                    break;
                case "Equity":
                    $types = '<option value="a">Asset</option><option value="l">Liability</option><option value="e" selected>Equity</option>';
                    $buttontype = "equity";
                    break;
            }


            $number = d.amount.replace(',', '');
            if(isNaN($number)){
                $amount = "0";
            }else{
                $amount = $number;
            }

            if(d['journaltracked'] === "1"){
                $tracked = "checked";
            }else{
                $tracked = "";
            }

            if(d['journal-can-toggle'] === "1"){
                $tracked = "cantedit";
            }else{
                $tracked = "";
            }

            return "" +
                '<div class="row">' +
                    '<div class="input-group input-100">' +
                '<label class="input-group-addon spancontainer" for="type"><div class="labeldiv">Type:</div></label>' +
                '<select id="type"  class="form-control input-md" >' + $types + '</select>' +

                        '<span class="input-group-addon spancontainer" for="date"><div class="labeldiv">Purchased/Acquired:</div></span>' +
                        '<input id="date" name="date" class="form-control input-md" value="' + d.date + '" required="" readonly>' +

                        '<span width="10em" class="input-group-addon spancontainer" for="amount"><div class="labeldiv">Amount/Value: $</div></span>' +
                        '<input id="amount" name="amount" type="number" class="form-control input-md" required="" value="' + $amount + '">' +

                        '<span width="10em" class="input-group-addon spancontainer" for="name"><div class="labeldiv">Name/Description:</div></span>' +
                        '<input id="name" name="name" type="text" class="form-control input-md" required="" value="' + d.name + '">' +


                    '</div>' +
                '</div>' +
                '<div class="row">' +
                    '<div class="input-group input-100">' +

                        '<label class="input-group-addon spancontainer" for="catagorys"><div class="labeldiv">Categories:</div></label>' +
                        '<select multiple id="catagorys"  class="form-control input-md" >' +
                        $catagorys +
                        '</select>' +
                        '<span style="width: 1%; padding: 0px;" class="input-group-btn spancontainer">' +
                        '<button style="height: 82px;" id="SplitAmountModalButton" class="btn btn-default OS-Button" type="button" data-toggle="modal" data-target="#SplitAmountModal" data-amount="amount" data-output="catagorys" data-type="' + $buttontype + '">Select</button>' +
                        '</span>' +

                        '<label class="input-group-addon spancontainer" for="accounts"><div class="labeldiv">Account:</div></label>' +
                        '<select multiple id="accounts"  class="form-control input-md" >' +
                        $accounts +
                        '</select>' +
                        '<span style="width: 1%; padding: 0px;" class="input-group-btn spancontainer">' +
                            '<button style="height: 82px;" id="SplitAmountModalButton" class="btn btn-default OS-Button" type="button" data-toggle="modal" data-target="#SplitAmountModal" data-amount="amount" data-output="accounts" data-type="expense">Select</button>' +
                        '</span>' +

                    '</div>' +
                '</div>' +
                '<div class="row">' +
                    '<div class="input-group input-75">' +
                        '<span width="10em" class="input-group-addon spancontainer" for="comments"><div class="labeldiv">Comments/Notes:</div></span>' +
                        '<textarea style="resize: none; height:  82px;" id="comments" name="comments" type="text" class="form-control input-md" rows="3">' + d.comments + '</textarea>' +
                    '</div>' +

                    '<div class="input-25 input-group" style="display: inline-table;">' +
                        '<div style="width: 100%; height: 40px;">' +
                            '<input  type="checkbox" name="checkboxes" id="" class="journal-check" data-on="Show In Journal" data-assetid="'+d['id']+'" data-off="Don\'t Show In Journal" data-toggle="toggle" data-height="100%" data-width="50%" '+ d['journaltracked'] + ' '+ d['journal-can-toggle'] +'>' +
                            '<button id="depreciation-details-button" class="btn btn-default OS-Button" type="button" style="width: 50%; height: 100%;" data-toggle="modal" data-target="#depreciation-details-modal" data-assetid="'+ d.id +'" data-depreciation=\''+ d.depreciation +'\'">Depreciation</button>' +
                        '</div>' +
                        '<div style="width: 100%; height: 40px;">' +
                            '<button id="save-entry" class="btn btn-default OS-Button" type="button" style="width: 50%; height: 100%;">Save Entry</button>' +
                            '<button id="File" class="btn btn-default OS-Button" type="button" style="width: 50%; height: 100%;" data-toggle="modal" data-target="#fileupload-modal" data-outputid="file-id">Attach Image/PDF</button>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
                //'<input id="asset-id" name="asset-id" class="form-control" value="'+ d.id +'">'
                '<input style="display: none;" id="asset-id" name="asset-id" class="form-control" value="' + d.id + '">' +
                '<input style="display: none;" id="file-id" name="file-id" class="form-control" value="' + d.file_id + '">' +
                '<input style="display: none;" id="changed" name="file-id" class="form-control" value="0">'

        }

        function DropDownFunctions(d) {

            $('.journal-check').bootstrapToggle();

            $('.journal-check').change(function () {

                $("body").addClass("loading");

                $data = {};
                $data['_token'] = "{{ csrf_token() }}";
                $data['id'] = $(this).data('assetid');

                if($(this).prop('checked') === true){
                    $data['journal'] = 1;
                }else{
                    $data['journal'] = 0;
                }

                $post = $.post("/AssetLiability/Journal", $data);

                $post.done(function (data) {
                    $("body").removeClass("loading");
                    switch(data['status']) {
                        case "OK":

                            if($(this).prop('checked') === true){
                                $('#delete-entry').prop('disabled', true);
                            }else{
                                $('#delete-entry').prop('disabled', false);
                            }
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

                $post.fail(function () {
                    NoReplyFromServer();
                });

            });

            $("#type").change(function () {
                $('#changed').val("1");
                $('#catagorys').find('option').remove();

                switch (this.value) {
                    case "a":
                        $('#SplitAmountModalButton').data('type', 'asset');
                        break;
                    case "l":
                        $('#SplitAmountModalButton').data('type', 'liability');
                        break;
                    case "e":
                        $('#SplitAmountModalButton').data('type', 'equity');
                        break;
                }

            });

            $("#date").change(function () {
                $('#changed').val("1");
            });
            $("#amount").change(function () {
                $('#changed').val("1");
            });
            $("#name").change(function () {
                $('#changed').val("1");
            });
            $("#SplitAmountModalButton").click(function () {
                $('#changed').val("1");
            });
            $("#comments").change(function () {
                $('#changed').val("1");
            });
            $("#file-id").change(function () {
                $('#changed').val("1");
            });

            $("#save-entry").click(function () {
                Save();
            });


            if(d.id === "journal" || d.id === "inventory" ){
                $('#name').prop('readonly', 'true');
                $('#amount').prop('readonly', 'true');
                $('#comments').prop('readonly', 'true');
                $('#type').prop('readonly', 'true');
                $('#File').prop('disabled', 'true');
            }else{
                $('#date').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    inline: true,
                    onSelect: function (dateText, inst) {
                        var d = new Date(dateText);
                        $("#date").val(d.getFullYear() + '-' + ("0" + (d.getMonth() + 1)).slice(-2) + '-' + d.getDate());

                    }
                });
            }
        }

        function Save($row = null, $table = null) {

            if ($('#catagorys option').length === 0) {
                $('#SplitAmountModalButton').click();
            } else {
                $data = {};
                $data['amount'] = $("#amount ").val();
                $data['catagorys'] = BuildSplitArray($data['amount'], $('#catagorys option'));
                $data['accounts'] = BuildSplitArray($data['amount'], $('#accounts option'));

                if ($data['catagorys'] === "error") {
                    $('#SplitAmountModalButton').click();
                } else {

                    $data['_token'] = "{{ csrf_token() }}";
                    $data['id'] = $("#asset-id").val();
                    $data['name'] = $("#name").val();
                    $data['date'] = $("#date").val();
                    $data['comments'] = $("#comments").val();
                    $data['type'] = $("#type").val();
                    $data['file_id'] = $("#file-id").val();

                    SaveExpense($data, $row, $table, false);
                }
            }
        }

        function SaveExpense($data, $row, $table, $new) {

            $("body").addClass("loading");
            posting = $.post("/AssetLiability/Save", $data);

            posting.done(function (data) {

                ResetServerValidationErrors();

                $("body").removeClass("loading");
                switch(data['status']) {
                    case "OK":
                        if($new){
                            $newrow = $('#overview-table').DataTable().row.add(data['data']);
                            newRowIndex = $newrow.index();
                            $dtrow = $('#overview-table').DataTable().row( newRowIndex );
                            $node = $dtrow.node();
                            $($node).addClass('acctualrow');

                            $span = $($node).find('#expandindicator');
                            $span.removeClass('glyphicon-minus');
                            $span.addClass('glyphicon-plus');

                            $newrow.draw();

                        }else{
                            $('#overview-table').DataTable().row('.selected').data(data['data']);
                            $('#changed').val("0");
                        }


                        $("#asset-id").val(data['data']['id']);
                        $.confirm({
                            autoClose: 'Close|2000',
                            title: 'Success!',
                            content: 'Data Saved',
                            buttons: {
                                Close: function () {

                                }
                            }
                        });

                        if ($row != null) {
                            expand($row, $table)
                        }
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
                    default:
                        console.log(data);
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                }
            });

            posting.fail(function (error) {

                $("body").removeClass("loading");
                $.dialog({
                    title: 'Error!',
                    content: "Failed to contact server"
                });
            });
        }

        function BuildSplitArray($total, $catagorys) {

            $array = {};

            $runningtotal = parseFloat(0);
            $catagorys.each(function (index, element) {
                $array[$(this).text()] = $(this).val();
                $runningtotal = parseFloat($runningtotal) + parseFloat($(this).val());
            });

            $total = parseFloat($total).toFixed(2);
            if (parseFloat($total) === parseFloat($runningtotal)) {
                return $array;
            } else {
                return "error";
            }
        }

    </script>

