@extends('master')
@section('content')

    <div class="row" style="margin-top: 55px;">
        <div class="col-md-3">
            <div class="input-group ">
                <span class="input-group-addon" for="overview-search"><div style="width: 7em;">Search:</div></span>
                <input id="overview-search" name="overview-search" type="text" placeholder="" value=""
                       class="form-control">
            </div>
        </div>

        <div class="col-md-3">
            <select id="overview-filter1" name="overview-filter1" type="text" placeholder="" class="form-control">
                <option value="all">All</option>
                <option value=""></option>
            </select>
        </div>
        <div class="col-md-3">
            <select id="overview-filter2" name="overview-filter2" type="text" placeholder="" class="form-control">
                <option value="all">All</option>
                <option value=""></option>
            </select>
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
    <div class="row" style="margin-top: 10px;">
        <div class="col-md-12">
            {!! PageElement::TableControl('overview') !!}
        </div>
    </div>

    <table id="overview-table" class="table">
        <thead>
        <tr>
            <th>id</th>
            <th>name</th>
            <th>date</th>
            <th>amount</th>
            <th>catagorys</th>
            <th>type</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>id</th>
            <th>name</th>
            <th>date</th>
            <th>amount</th>
            <th>catagorys</th>
            <th>type</th>
        </tr>
        </tfoot>
        <tbody>
        <tr>
            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
            <td>5</td>
            <td>6</td>
        </tr>
        @foreach($assets as $asset)
        <tr>
            <td>{{ $asset->id }}</td>
            <td>{{ $asset->name }}</td>
            <td>{{ $asset->date }}</td>
            <td>{{ $asset->amount }}</td>
            <td></td>
            <td>{{ $asset->type }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    </div>




    <script>
        $(document).ready(function () {

            // DataTable
            var overviewtable = $('#overview-table').DataTable({
                "columnDefs": [
                    {"targets": [], "visible": false}
                ],
            });

            $('#overview-table tbody').on('click', 'tr', function () {
                $row = $(this);
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                }
                else {
                    overviewtable.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
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

        });
    </script>
@stop
