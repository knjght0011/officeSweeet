<div class="row"><div class="col-md-12"><br></div></div>
<div class="col-md-9">
    <div style="width: 105mm; height: 148mm; border: 1px #D3D3D3 solid; background: white;" class="subpage">
        <div  id="returnaddress">
            Return Address:

        </div>

        <div id="toaddess">
            To Address
        </div>
            

        <div id="companylogo">
            Company Logo
        </div>                

            
            
        <div id="meta">
            Quote Number & Date
        </div>
    </div>
</div>
<div class="col-md-3">

    <legend>Return Address Box Positioning:</legend>
    <div class="form-group row"> 
        <label class="col-sm-3 form-control-label" for="returntop">Top</label>
        <div class="col-sm-9">
            <input id="returntop" name="returntop" type="text"  class="form-control" required="">
        </div>
    </div>

    <div class="form-group row"> 
        <label class="col-sm-3 form-control-label" for="returnleft">Left</label>
        <div class="col-sm-9">
            <input id="returnleft" name="returnleft" type="text"  class="form-control" required="">
        </div>
    </div>

    <div class="form-group row"> 
        <label class="col-sm-3 form-control-label" for="returnwidth">Width</label>
        <div class="col-sm-9">
            <input id="returnwidth" name="returnwidth" type="text"  class="form-control" required="">
        </div>
    </div>

    <div class="form-group row"> 
        <label class="col-sm-3 form-control-label" for="returnheight">Height</label>
        <div class="col-sm-9">
            <input id="returnheight" name="returnheight" type="text"  class="form-control" required="">
        </div>
    </div>

    <legend>To Address Box Positioning:</legend>

    <div class="form-group row"> 
        <label class="col-sm-3 form-control-label" for="totop">Top</label>
        <div class="col-sm-9">
            <input id="totop" name="totop" type="text"  class="form-control" required="">
        </div>
    </div>

    <div class="form-group row"> 
        <label class="col-sm-3 form-control-label" for="toleft">Left</label>
        <div class="col-sm-9">
            <input id="toleft" name="toleft" type="text"  class="form-control" required="">
        </div>
    </div>

    <div class="form-group row"> 
        <label class="col-sm-3 form-control-label" for="towidth">Width</label>
        <div class="col-sm-9">
            <input id="towidth" name="towidth" type="text"  class="form-control" required="">
        </div>
    </div>

    <div class="form-group row"> 
        <label class="col-sm-3 form-control-label" for="toheight">Height</label>
        <div class="col-sm-9">
            <input id="toheight" name="toheight" type="text"  class="form-control" required="">
        </div>
    </div>


    <div class="row">
        <div class="col-md-2">
            <button id="envelope-save" name="envelope-save" type="button" class="btn OS-Button">Save</button>
        </div>
    </div> 
</div>

<script>
$(document).ready(function() {
    
    @if(isset($settings['returntop']))
    $("#returntop").val("{{ $settings['returntop'] }}");
    @else
    $("#returntop").val("20");
    @endif
    
    @if(isset($settings['returnleft']))
    $("#returnleft").val("{{ $settings['returnleft'] }}");
    @else
    $("#returnleft").val("10");
    @endif
    
    @if(isset($settings['returnwidth']))
    $("#returnwidth").val("{{ $settings['returnwidth'] }}");
    @else
    $("#returnwidth").val("50");
    @endif
    
    @if(isset($settings['returnheight']))
    $("#returnheight").val("{{ $settings['returnheight'] }}");
    @else
    $("#returnheight").val("20");
    @endif
    
    @if(isset($settings['totop']))
    $("#totop").val("{{ $settings['totop'] }}");
    @else
    $("#totop").val("45");
    @endif
    
    @if(isset($settings['toleft']))
    $("#toleft").val("{{ $settings['toleft'] }}");
    @else
    $("#toleft").val("20");
    @endif
    
    @if(isset($settings['towidth']))
    $("#towidth").val("{{ $settings['towidth'] }}");
    @else
    $("#towidth").val("70");
    @endif
    
    @if(isset($settings['toheight']))
    $("#toheight").val("{{ $settings['toheight'] }}");
    @else
    $("#toheight").val("20");
    @endif

    $("#toaddress").css({
        position: "absolute",
        @if(isset($settings['toleft']))
        left: "{{ $settings['toleft']/2 }}mm",
        @else
        left: "10mm",
        @endif
        
        @if(isset($settings['totop']))
        top: "{{ $settings['totop']/2 }}mm",
        @else
        top: "22mm",
        @endif

        @if(isset($settings['towidth']))
        width: "{{ $settings['towidth']/2 }}mm",
        @else
        width: "35mm",
        @endif

        @if(isset($settings['toheight']))
        height: "{{ $settings['toheight']/2 }}mm",
        @else
        height: "10mm",
        @endif

        border: "1px solid black",
        padding: "5px"
    });
    
    $("#returnaddress").css({
        position: "absolute",
        @if(isset($settings['returnleft']))
        left: "{{ $settings['returnleft']/2 }}mm",
        @else
        left: "5mm",
        @endif
        
        @if(isset($settings['returntop']))
        top: "{{ $settings['returntop']/2 }}mm",
        @else
        top: "10mm",
        @endif

        @if(isset($settings['returnwidth']))
        width: "{{ $settings['returnwidth']/2 }}mm",
        @else
        width: "25mm",
        @endif

        @if(isset($settings['returnheight']))
        height: "{{ $settings['returnheight']/2 }}mm",
        @else
        height: "10mm",
        @endif
        
        border: "1px solid black",
        padding: "5px"
    });
    
    $("#companylogo").css({
        position: "absolute",
        left: "75mm",
        top: "22mm",
        width: "25mm",
        height: "10mm",
        border: "1px solid black",
        padding: "5px"
    });
    
    $("#meta").css({
        position: "absolute",
        left: "75mm",
        top: "10mm",
        width: "25mm",
        height: "10mm",
        border: "1px solid black",
        padding: "5px"
    });

        
    
    $("#envelope-save").click(function()
    {
        $returntop = $('#returntop').val();
        $returnleft = $('#returnleft').val();
        $returnwidth = $('#returnwidth').val();
        $returnheight = $('#returnheight').val();
        $totop = $('#totop').val();
        $toleft = $('#toleft').val();
        $towidth = $('#towidth').val();
        $toheight = $('#toheight').val();

        
        envpost = $.post("/ACP/Envelope/Save",
        {
            _token: "{{ csrf_token() }}",
            returntop: $returntop,
            returnleft: $returnleft,
            returnwidth: $returnwidth,
            returnheight: $returnheight,
            totop: $totop,
            toleft: $toleft,
            towidth: $towidth,
            toheight: $toheight
        });

        envpost.done(function( data )
        {
            alert(data)
        });

        envpost.fail(function() {
            alert( "Failed to post settings" );
        });
    });
});    
</script> 