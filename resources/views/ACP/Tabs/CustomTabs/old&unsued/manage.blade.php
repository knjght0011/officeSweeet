    <div class="row">
        <div class="col-md-5">
            <div id="newSearchPlaceBranch-customtabs-manage"></div>
        </div>
        <div class="col-md-2">
            <div id="newPaginateBranch-customtabs-manage"></div>
        </div>
        <div class="col-md-5">
            <div id="newSearchLengthBranch-customtabs-manage" style="float: right"></div>
        </div> 
    </div>
    <div id="newInfoBranch-customtabs-manage"></div>
    
    <table id="tabletable" class="table">
        <thead>
            <tr id="head">

                <th>Name</th>
                <th></th>
                <th>Deactivated on</th>
            </tr>
        </thead>
        <tfoot style="visibility: hidden;">
            <tr>

                <th>Name</th>
                <th></th>
                <th>Deactivated on</th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($alltables as $table)
            <tr>
                <td>
                    {{ $table->displayname }}
                </td>
                <td>
                    <label class="col-md-4 control-label tablabel" for="inactive" style="padding-top: 10px">Inactive:</label>  
                    <div class="col-md-5 " style="padding-top: 10px">
                        <input class="tab-enable-toggle" name="tab-enable-toggle" type="checkbox" data-id="{{ $table->id }}" 
                               @if(!empty($table->deleted_at)) 
                               checked 
                               @endif 
                               >
                    </div>

                </td>
                <td>
                    {{ $table->deleted_at }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    
<div class="modal fade" id="rename-tab-model" tabindex="-1" role="dialog" aria-labelledby="rename-tab-model" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="rename-tab-model">Rename Tab</h4>
        </div>
        <div class="modal-body">
            <input id="tab-rename-input" name="tab-rename-input" class="form-control"></input>
            <input style="visibility: hidden;" id="tab-rename-id" name="tab-rename-id" class="form-control"></input>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button id="tab-rename-save" name="save" type="button" class="btn OS-Button">Save</button>
        </div>
    </div>
  </div>
</div>  

<script>

$(document).ready(function() {
    
    $( "#tab-rename-save" ).click(function() {
        //var input = $(this);
        $displayname = $("#tab-rename-input").val();
        $id = $("#tab-rename-id").val();
        
        $("body").addClass("loading");
        posting = $.post("/CustomTables/Rename",
        {
            _token: "{{ csrf_token() }}",
            displayname: $displayname,
            id: $id

        });

        posting.done(function( data ) {
            $("body").removeClass("loading");
            if ($.isNumeric(data)) 
            {
                bootstrap_alert.warning("message", 'success', 4000);
                location.href='/ACP/CustomTabs/customtabs-manage';
            }else{
                //server validation errors
                ServerValidationErrors(data);
            }
            
            
        });

        posting.fail(function() {
            $("body").removeClass("loading");
            bootstrap_alert.warning("Failed to post", 'danger', 4000);
        });
        
    });
    
    $('#rename-tab-model').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id'); // Extract info from data-* attributes
        var displayname = button.data('displayname'); // Extract info from data-* attributes

        $("#tab-rename-input").val(displayname);
        $("#tab-rename-id").val(id);
    }); 

   ;

    
    // Setup - add a text input to each footer cell
    $('#tabletable tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input class="form-control" type="text" placeholder="Search '+title+'" />' );
    } );
 
    // DataTable
    var tabletable = $('#tabletable').DataTable({
        "pageLength": 10,
        "order": [[ 3, "asc" ]]
      });
 
    // Apply the search
    tabletable.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );
    
    $("#newSearchPlaceBranch-customtabs-manage").html($( "#customtabs-manage" ).children().find(".dataTables_filter"));
    $("#newSearchLengthBranch-customtabs-manage").html($( "#customtabs-manage" ).children().find(".dataTables_length"));
    $("#newPaginateBranch-customtabs-manage").html($( "#customtabs-manage" ).children().find(".dataTables_paginate"));
    $("#newInfoBranch-customtabs-manage").html($( "#customtabs-manage" ).children().find(".dataTables_info"));
});
</script> 
