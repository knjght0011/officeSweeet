@extends('master')

@section('content')


<div class="row">
    <div class="col-md-4" style="padding-right: 0px;">
        <div class="input-group ">
            <span class="input-group-addon" for="name"><div style="width: 7em;">Report Name:</div></span>
            <input id="name" name="name" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-3" style="padding: 0px;">
        <div class="input-group">
            <span class="input-group-addon" for="selectbasic"><div style="width: 7em;">Group:</div></span>
            <select id="type" name="type" class="form-control" >
                <option value="client">{{ TextHelper::GetText("Client") }}</option>
                <option value="vendor">Vendor</option>
                <option value="employee">Employee</option>
            </select>
        </div>
    </div>
    <div class="col-md-3" style="padding: 0px;">
        <div class="input-group">
            <span class="input-group-addon" for="selectbasic"><div style="width: 7em;">Sub-Group:</div></span>
            <input id="subgroups" type="text" name="subgroups" list="subgroups-list" class="form-control" data-validation-label="Sub-Group" data-validation-required="true" data-validation-type="">
            <datalist  id="subgroups-list" name="subgroups-list">
            @foreach($templategroup as $tg)
                @if($tg->group == "client")
                    <option value="{{ $tg->subgroup }}">{{ $tg->subgroup }}</option>
                @endif
            @endforeach
            </datalist>
        </div>
    </div>
    <div class="col-md-2" style="padding-left: 0px;">
        <button id="save" type="button" class="btn OS-Button" style="width: 100%">Save</button>
    </div>
</div>


<div class="row" style="margin-top: 10px;">
    <div class="col-md-9">
        <div class="document-editor">
            <div class="document-editor__toolbar"></div>
            <div class="document-editor__editable-container">
                <div class="document-editor__editable">
                    @if(isset($DocData))
                        {!! $DocData !!}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div id="companyinfo"></div>
        <div id="generaltree"></div>
        <div id="clienttree"></div>
        <div id="vendortree"></div>
        <div id="employeetree"></div>
        <div id="generaltree"></div>
    </div>
</div>

<input id="template-id" style="display: none;">

@include('OS.Templates.editor')

<script src="/includes/bootstrap-treeview.js"></script>
<script>
$(document).ready(function() {
    @if(isset($filename))
        $('#name').val("{{ $filename }}");
    @endif
    @if(isset($template))
        $('#name').val("{{ $template->name }}");
        $('#template-id').val("{{$template->id}}");
    @else
        $('#template-id').val("0");
    @endif


    $('#save').click(function () {

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['id'] = $('#template-id').val();
        $data['name'] = $('#name').val();
        $data['content'] = editor.getData();
        $data['type'] = $('#type').val();
        $data['subgroup'] = ValidateInput($("#subgroups"));

        $("body").addClass("loading");
        ResetServerValidationErrors();

        $post = $.post("/Templates/Save", $data);

        $post.done(function (data) {

            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":
                    SavedSuccess();
                    $('#template-id').val(data['id']);
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

});
$('#type').on('change', function() {
    switch(this.value) {
    case 'client':
        $('#clienttree').treeview('remove');
        $('#vendortree').treeview('remove');
        $('#employeetree').treeview('remove');
        //$('#generaltree').treeview('remove');
        $('#clienttree').treeview({
            data: clienttree,
            enableLinks: true,
            highlightSelected: false
        });
        $('#subgroups').val("");
        $('#subgroups-list').empty();
        @foreach($templategroup as $tg)
            @if($tg->group == "client")
                $('#subgroups-list').append($("<option></option>").attr("value","{{ $tg->subgroup }}").text("{{ $tg->subgroup }}")); 
            @endif
        @endforeach
        
        break;
    case 'vendor':
        $('#clienttree').treeview('remove');
        $('#vendortree').treeview('remove');
        $('#employeetree').treeview('remove');
        //$('#generaltree').treeview('remove');
        $('#vendortree').treeview({
            data: vendortree,
            enableLinks: true,
            highlightSelected: false
        });
        $('#subgroups').val("");
        $('#subgroups-list').empty();
        @foreach($templategroup as $tg)
            @if($tg->group == "vendor")
                $('#subgroups-list').append($("<option></option>").attr("value","{{ $tg->subgroup }}").text("{{ $tg->subgroup }}"));
            @endif
        @endforeach
            
        break;
    case 'employee':
        $('#clienttree').treeview('remove');
        $('#vendortree').treeview('remove');
        $('#employeetree').treeview('remove');
        //$('#generaltree').treeview('remove');        
        $('#employeetree').treeview({
            data: employeetree,
            enableLinks: true,
            highlightSelected: false
        });
        $('#subgroups').val("");
        $('#subgroups-list').empty();
        @foreach($templategroup as $tg)
            @if($tg->group == "employee")
                $('#subgroups-list').append($("<option></option>").attr("value","{{ $tg->subgroup }}").text("{{ $tg->subgroup }}"));
            @endif
        @endforeach        
        
        break;
//    case 'general':
//        $('#clienttree').treeview('remove');
//        $('#vendortree').treeview('remove');
//        $('#employeetree').treeview('remove');
//        $('#generaltree').treeview('remove');        
//        $('#generaltree').treeview({
//            data: generaltree,
//            enableLinks: true,
//            highlightSelected: false
//        });
//        $('#subgroups').empty();
//        @foreach($templategroup as $tg)
//            @if($tg->group == "general")
//                $('#subgroups').append($("<option></option>").attr("value","{{ $tg->subgroup }}").text("{{ $tg->subgroup }}"));
//            @endif
//        @endforeach          
//        
//        break;
    default:
        
    }
});

{!! TreeViewHelper::ClientTree() !!}

{!! TreeViewHelper::VendorTree() !!}

{!! TreeViewHelper::EmployeeTree() !!}

{!! TreeViewHelper::GeneralTree() !!}

{!! TreeViewHelper::CompanyInfoTree() !!}

$('#companyinfo').treeview({
    data: companytree,
    enableLinks: true,
    highlightSelected: false

});

$('#generaltree').treeview({
    data: generaltree,
    enableLinks: true,
    highlightSelected: false

});

$('#clienttree').treeview({
    data: clienttree,
    enableLinks: true,
    highlightSelected: false

});


$( document ).on( "click", "a", function(event) {
    if ($(this).attr('id') === 'ddl') {
        event.preventDefault();

        editor.model.change( writer => {

            const insertPosition = editor.model.document.selection.getFirstPosition();
            var text = '@{{'+$(this).attr('href')+'}} ';
            writer.insertText(text, insertPosition );
        } );
    }
});

@if(isset($template))

$('#type').val("{{ $template->type }}");
$("#type").change();
$('#type').prop('disabled', true);


$subgroup = "{{ $template->subgroup }}";
if ( $("#subgroups option[value='{{ $template->subgroup }}']").length > 0 ){
    $('#subgroups').append($("<option></option>").attr("value",'{{ $template->subgroup }}').text('{{ $template->subgroup }}'));
    $('#subgroups').val('{{ $template->subgroup }}');
}else{
    $('#subgroups').val('{{ $template->subgroup }}');
}

@endif

</script>


@stop
