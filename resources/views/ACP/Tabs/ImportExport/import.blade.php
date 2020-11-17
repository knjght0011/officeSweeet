<div class="row" style="padding-top: 10px;">
    <div class="col-md-6">
    {!! Form::OSinput("ExcelUpload", "Excel File Upload", "", "", "true", "", "file", true, "ExcelFileImport", "Import") !!}
        <div class="input-group">   
            <span class="input-group-addon" for="Instructions"><div style="width: 15em;">Please select an excel file with your client list to upload into Office Sweeet.</div></span>
        </div>
    </div>
</div>       

<script>
$(document).ready(function() {
    
    $("#ExcelUpload").change(function()
    {

        input = document.getElementById('ExcelUpload');
        $ImportFile = input.files[0];
        $size = file.size / 1024;
        if($size > 20480){
            $.dialog({
                title: 'Oops...',
                content: 'File to large(limit 20mb)'
            });
        }

            $.dialog({
                title: 'Success',
                content: 'Success'
            });
        
    });
        
    $("#ExcelFileImport").click(function()
    {
        $.dialog({
                title: 'Check',
                content: 'Starting upload'
            });
        $("body").addClass("loading");
        
        console.log("Excel File Import");
                
        post = $.post("/ACP/ImportExport/Import",
        {
            _token: "{{ csrf_token() }}",
            ExcelFile: $ImportFile
            
        });
        
        
        post.done(function( data ) {   
        $("body").removeClass("loading");
             alert(data);
        });
        
        post.fail(function() {
            $("body").removeClass("loading");
             alert( "Failed to Import File" );
             //bootstrap_alert.warning("Unable to post data", 'danger', 4000);
        });  
    });
} );
    </script> 