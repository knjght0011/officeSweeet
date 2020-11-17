@extends('master')

@section('content')

    <div class="row">
        <div class="col-md-3">
            <div class="input-group ">
                <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
                <input id="file-search" name="file-search" type="text" placeholder="" value="" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <button class="btn btn-default OS-Button" type="button" style="width: 100%;" data-toggle="modal" data-target="#fileupload-modal">Upload Image/PDF</button>
        </div>
    </div>

    <div class="row" style="margin-top: 15px;">
        <div class="col-md-12">
            {!! PageElement::TableControl('file') !!}
        </div>
    </div>
    
    <table class="table" id="filesearch">
        <thead>
            <tr>
                <th>Description</th>
                <th>Linked To</th>
                <th>Date Uploaded</th>
                <th class="col-md-1">File</th>
            </tr>
        </thead>
        <tbody>
            @foreach($files as $file)
            <tr>
                <td>{{ $file->description }}</td>
                <td>{{ $file->LinkedTo() }}</td>
                <td>{{ $file->created_at }}</td>
                <td style="padding-top: 3px; padding-bottom: 3px;">
                    <button style="width: 100%; padding-top: 2px; padding-bottom: 2px;" type="button" class="btn OS-Button" data-toggle="modal" data-target="#filestore-display-model" data-fileid="{{ $file->id }}">Show File</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            var filesearch = $('#filesearch').DataTable(
                {
                    "pageLength": 20,
                    "order": [[2, "desc"]],
                }
            );
    
            $("#file-previous-page").click(function () {
                filesearch.page("previous").draw('page');
                PageinateUpdate(filesearch.page.info(), $('#file-next-page'), $('#file-previous-page'), $('#file-tableInfo'));
            });
    
            $("#file-next-page").click(function () {
                filesearch.page("next").draw('page');
                PageinateUpdate(filesearch.page.info(), $('#file-next-page'), $('#file-previous-page'), $('#file-tableInfo'));
            });
    
            $('#file-search').on('keyup change', function () {
                filesearch.search(this.value).draw();
                PageinateUpdate(filesearch.page.info(), $('#file-next-page'), $('#file-previous-page'), $('#file-tableInfo'));
            });

            PageinateUpdate(filesearch.page.info(), $('#file-next-page'), $('#file-previous-page'), $('#file-tableInfo'));
        });
    </script>

@include('OS.FileStore.displayfile')
@include('OS.FileStore.fileuploadmodel')
@stop