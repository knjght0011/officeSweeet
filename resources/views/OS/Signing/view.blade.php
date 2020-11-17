@include('OS.Public.header')
<style>
    body{
        padding-left: 10%;
        padding-right: 10%;
        padding-top: 50px;
        padding-bottom: 50px;
        height: 100vh;
    }
    #PageContainer{
        width: 100%;
        height: 100%;
        background-color: black;
        padding: 3cm;
        overflow: hidden;
        overflow-y: scroll;
    }
    #ImageFrame{
        width: 100%;
        height: 100%;
        background-color: white;
        background-image: url('data:image/jpeg;base64,');
        background-repeat: no-repeat;
        background-size: contain;
    }
    #myCanvas{
        position: relative;
        top: 100px;
        left: 100px;
        background-color: white;
    }
</style>

<div id="PageContainer">
    <div id="ImageFrame" >
        <img id="image" src="" width="100%" height="100%">
    </div>
</div>


<canvas id="canvas" width="2000" style="display: none;">

</canvas>

<script src="{{ url('/includes/pdfjs/pdf.js') }}"></script>
<script src="{{ url('/includes/pdfjs/pdf.worker.js') }}"></script>



<script>

    $width = $('#ImageFrame').width();
    $height = $width * 1.4142;
    $('#ImageFrame').height($height);

    var __PDF_DOC,
        __CURRENT_PAGE,
        __TOTAL_PAGES,
        __PAGE_RENDERING_IN_PROGRESS = 0,
        __CANVAS = $('#canvas').get(0),
        __CANVAS_CTX = __CANVAS.getContext('2d');

    $(document).ready(function() {

        //var pdfAsDataUri = "data:application/pdf;base64,{{ $signing->file }}"; // shortened
        var pdfAsArray = convertDataURIToBinary();

        PDFJS.getDocument(pdfAsArray).then(function(pdf_doc) {
            // pdf_doc is a PDFDocumentProxy object

            pdf_doc.getPage(1).then(function(page) {

                var scale_required = __CANVAS.width / page.getViewport(1).width;

                // Get viewport of the page at required scale
                var viewport = page.getViewport(scale_required);

                // Set canvas height
                __CANVAS.height = viewport.height;

                var renderContext = {
                    canvasContext: __CANVAS_CTX,
                    viewport: viewport
                };

                page.render(renderContext).then(function() {
                    // page has rendered
                    canvas = document.getElementById('canvas');
                    $('#image').prop('src' , canvas.toDataURL());



                });

            });

        });


    });


    function convertDataURIToBinary() {
        //var base64Index = dataURI.indexOf(BASE64_MARKER) + BASE64_MARKER.length;
        //var base64 = dataURI.substring(base64Index);
        var base64 = "{{ $signing->file }}";
        var raw = window.atob(base64);
        var rawLength = raw.length;
        var array = new Uint8Array(new ArrayBuffer(rawLength));

        for(var i = 0; i < rawLength; i++) {
            array[i] = raw.charCodeAt(i);
        }
        return array;
    }

</script>
@include('OS.Public.footer')