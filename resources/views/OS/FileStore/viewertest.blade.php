@extends('master')

@section('content')

    <div id="loaded-layout" style="width:100%;height:800px;"></div>
    <script type="text/javascript" src="https://www.docxjs.com/js/build/latest.docxjs.min.js"></script>
<script>
$(document).ready(function(){

    var docxJS = new DocxJS();


    var get = $.get( "/FileStore/ShowFile/38", function(  ) { });

    get.done(function( data ) {
        console.log(data);


        //File Parsing
        docxJS.parse(
            data,
            function () {
                //After Rendering
                docxJS.render($('#loaded-layout')[0], function (result) {
                    if (result.isError) {
                        console.log(result.msg);
                    } else {
                        console.log("Success Render");
                    }
                });
            }, function (e) {
                console.log("Error!", e);
            }
        );
    });

});
</script>

@stop
