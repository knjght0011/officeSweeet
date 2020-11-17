@extends('master')
@section('content')

    <div class="row" style="margin-top: 55px;">
        <div class="col-md-3">
            <div class="input-group ">
                <span class="input-group-addon" for="changethis-search"><div style="width: 7em;">Search:</div></span>
                <input id="changethis-search" name="changethis-search" type="text" placeholder="" value=""
                       class="form-control">
            </div>
        </div>

        <div class="col-md-3">
            <select id="changethis-filter1" name="changethis-filter1" type="text" placeholder="" class="form-control">
                <option value="all">All</option>
                <option value=""></option>
            </select>
        </div>

        <div class="col-md-3">
            <select id="changethis-filter2" name="changethis-filter2" type="text" placeholder="" class="form-control">
                <option value="all">All</option>
                <option value=""></option>
            </select>
        </div>

        <div class="col-md-3">
            <div class="input-group ">
                <span class="input-group-addon" for="changethis-length"><div style="width: 7em;">Show:</div></span>
                <select id="changethis-length" name="changethis-length" type="text" placeholder="choice"
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
            {!! PageElement::TableControl('changethis') !!}
        </div>
    </div>

    <table id="changethis-table" class="table">
        <thead>
        <tr>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
        </tr>
        </tfoot>
        <tbody>
        <tr>
            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
        </tr>
        @foreach(array() as $changethis) <!--changethis-->>
        <tr>
            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
        </tr>
        @endforeach
        </tbody>
    </table>
    </div>




    <script>
        $(document).ready(function () {



            // DataTable
            var changethistable = $('#changethis-table').DataTable({
                "columnDefs": [
                    {"targets": [], "visible": false}
                ],
            });

            $('#changethis-table tbody').on('click', 'tr', function () {
                $row = $(this);
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                }
                else {
                    changethistable.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });

            $("#changethis-previous-page").click(function () {
                changethistable.page("previous").draw('page');
                PageinateUpdate(changethistable.page.info(), $('#changethis-next-page'), $('#changethis-previous-page'), $('#changethis-tableInfo'));
            });

            $("#changethis-next-page").click(function () {
                changethistable.page("next").draw('page');
                PageinateUpdate(changethistable.page.info(), $('#changethis-next-page'), $('#changethis-previous-page'), $('#changethis-tableInfo'));
            });

            $('#changethis-search').on('keyup change', function () {
                changethistable.search(this.value).draw();
                PageinateUpdate(changethistable.page.info(), $('#changethis-next-page'), $('#changethis-previous-page'), $('#changethis-tableInfo'));
            });

            $('#changethis-length').on('change', function () {
                changethistable.page.len(this.value).draw();
                PageinateUpdate(changethistable.page.info(), $('#changethis-next-page'), $('#changethis-previous-page'), $('#changethis-tableInfo'));
            });


            $('#changethis-filter2').on('change', function () {

                if (this.value === "all") {
                    changethistable
                        .columns(6) //changethis
                        .search("", true)
                        .draw();
                } else {

                    changethistable
                        .columns(6) //changethis
                        .search(this.value, true)
                        .draw();

                }

                PageinateUpdate(changethistable.page.info(), $('#changethis-next-page'), $('#changethis-previous-page'), $('#changethis-tableInfo'));

            });

            $('#changethis-filter2').change();

            $('#changethis-filter1').on('change', function () {

                if (this.value === "all") {
                    changethistable
                        .columns(6) //changethis
                        .search("", true)
                        .draw();
                } else {

                    changethistable
                        .columns(6) //changethis
                        .search(this.value, true)
                        .draw();

                }

                PageinateUpdate(changethistable.page.info(), $('#changethis-next-page'), $('#changethis-previous-page'), $('#changethis-tableInfo'));

            });

            $('#changethis-filter1').change();

            PageinateUpdate(changethistable.page.info(), $('#changethis-next-page'), $('#changethis-previous-page'), $('#changethis-tableInfo'));

            $(".dataTables_filter").css('display', 'none');
            $(".dataTables_length").css('display', 'none');
            $(".dataTables_paginate").css('display', 'none');
            $(".dataTables_info").css('display', 'none');
            $("#changethistable").css("width", "100%");

        });
    </script>
@stop
