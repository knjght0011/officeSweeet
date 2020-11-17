@extends('master')

@section('content')



<div class="row">
    <div style="float:left; width: 10em;  margin-left: 20px;">
        <button id="goback" style="width: 100%;" class="btn OS-Button" type="button">{{ $backtext }}</button>
    </div>    
    
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button id="Save-Button" style="width: 100%;" class="btn OS-Button" type="button">{{ $savetext }}</button>
    </div>
</div>

<div class="row" style="margin-top: 10px; padding-left: 10px; padding-right: 10px;">
    <div class="input-group">
        <span class="input-group-addon" for="Group"><div style="width: 7em;">Report Name:</div></span>
        <input type="text" class="form-control" id="name" value="{{ $template->name }}"></input>
    </div>
</div>

<div class="document-editor">
    <div class="document-editor__toolbar"></div>
    <div class="document-editor__editable-container">
        <div class="document-editor__editable">
            @yield('template')
        </div>
    </div>
</div>

@include('OS.Templates.editor')

<script>

    $id =  0;


    $("#Save-Button").click(function(){
        SaveReport();
    });

    function SaveReport(){
        $("body").addClass("loading");


        $ReportData = editor.getData();
        var $ReportName = $('#name').val();

        posting = $.post("/Documents/Save",
        {
            id: $id,
            _token: "{{ csrf_token() }}",
            name: $ReportName,
            Type: "{{ $template->type }}",
            ReportData: $ReportData,
            dataID: "{{ $dataID }}",
        });

        posting.done(function( data ) {
            $("body").removeClass("loading");
            $id = data;
            SavedSuccess();
        });

        posting.fail(function() {
            NoReplyFromServer();
        });
    }

    $("#goback").click(function(){
        $("body").addClass("loading");
        GoToPage("{{ $backlink }}");
    });
</script>

@stop
