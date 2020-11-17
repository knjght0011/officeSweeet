@extends('master')

@section('content')
<div class="row">
    <div style="float:left; width: 10em;  margin-left: 20px;">
        <button id="goback" style="width: 100%;" class="btn OS-Button" type="button">
            @if($doc->GetType() == "client")
                Back to {{ TextHelper::GetText("Client") }}
            @endif
            @if($doc->GetType() == "vendor"))
                Back to Vendor
            @endif
            @if($doc->GetType() == "employee"))
                Back to Employee
            @endif
        </button>
    </div> 
    
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button id="Save-Button" style="width: 100%;" class="btn OS-Button" type="button">
            @if($doc->GetType() == "client")
                Save to {{ TextHelper::GetText("Client") }}'s File
            @endif
            @if($doc->GetType() == "vendor"))
                Save to Vendor's File
            @endif
            @if($doc->GetType() == "employee"))
                Save to Employee's File
            @endif
        </button>
    </div>
</div>  

<div class="row" style="margin-top: 10px; padding-left: 10px; padding-right: 10px;">
    <div class="input-group ">
       <span class="input-group-addon" for="name"><div style="width: 7em;">Report Name:</div></span>
        <input type="text" class="form-control" id="name" value="{{ $doc->name }}"></input>
    </div>
</div>

<div class="document-editor">
    <div class="document-editor__toolbar"></div>
    <div class="document-editor__editable-container">
        <div class="document-editor__editable">
            {!! $doc->reportdata !!}
        </div>
    </div>
</div>
@include('OS.Templates.editor')


<script>

    $("#Save-Button").click(function(){
        SaveReport();
    });

    function SaveReport(){
        $("body").addClass("loading");

        $ReportData = editor.getData();
        var $ReportName = $('#name').val();

        posting = $.post("/Documents/Save",
        {
            id: {{ $OriginalID }},
            _token: "{{ csrf_token() }}",
            name: $ReportName,
            Type: "{{ $doc->GetType() }}",
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
        @if($doc->GetType() == "client")
            GoToPage("/Clients/View/{{ $dataID }}");
        @endif
        @if($doc->GetType() == "vendor")
            GoToPage("/Vendors/View/{{ $dataID }}");
        @endif
        @if($doc->GetType() == "employee")
            GoToPage("/Employees/View/{{ $dataID }}");
        @endif
    });
</script>

@stop