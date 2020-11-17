<div class="row"><div class="col-md-12"><br></div></div>
<legend>Return Address Box Positioning:</legend>
<div class="form-group row"> 
    <label class="col-sm-3 form-control-label" for="returntop">Top</label>
    <div class="col-sm-6">
        <input id="returntop" name="returntop" type="text"  class="form-control" required="">
    </div>
</div>

<div class="form-group row"> 
    <label class="col-sm-3 form-control-label" for="returnleft">Left</label>
    <div class="col-sm-6">
        <input id="returnleft" name="returnleft" type="text"  class="form-control" required="">
    </div>
</div>

<div class="form-group row"> 
    <label class="col-sm-3 form-control-label" for="returnwidth">Width</label>
    <div class="col-sm-6">
        <input id="returnwidth" name="returnwidth" type="text"  class="form-control" required="">
    </div>
</div>

<div class="form-group row"> 
    <label class="col-sm-3 form-control-label" for="returnheight">Height</label>
    <div class="col-sm-6">
        <input id="returnheight" name="returnheight" type="text"  class="form-control" required="">
    </div>
</div>

<legend>To Address Box Positioning:</legend>

<div class="form-group row"> 
    <label class="col-sm-3 form-control-label" for="totop">Top</label>
    <div class="col-sm-6">
        <input id="totop" name="totop" type="text"  class="form-control" required="">
    </div>
</div>

<div class="form-group row"> 
    <label class="col-sm-3 form-control-label" for="toleft">Left</label>
    <div class="col-sm-6">
        <input id="toleft" name="toleft" type="text"  class="form-control" required="">
    </div>
</div>

<div class="form-group row"> 
    <label class="col-sm-3 form-control-label" for="towidth">Width</label>
    <div class="col-sm-6">
        <input id="towidth" name="towidth" type="text"  class="form-control" required="">
    </div>
</div>

<div class="form-group row"> 
    <label class="col-sm-3 form-control-label" for="toheight">Height</label>
    <div class="col-sm-6">
        <input id="toheight" name="toheight" type="text"  class="form-control" required="">
    </div>
</div>
   

    <div class="row">
        <div class="col-md-2">
            <button id="envelope-save" name="envelope-save" type="button" class="btn OS-Button">Save</button>
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
       
    $("#envelope-save").click(function()
    {
        $ToTop = $('#ToTop').val();
        $ToLeft = $('#ToLeft').val();
        $ToWidth = $('#ToWidth').val();
        $ToHeight = $('#ToHeight').val();
        
        $DateTop = $('#DateTop').val();
        $DateLeft = $('#DateLeft').val();
        $DateWidth = $('#DateWidth ').val();

        $PayToTop = $('#PayToTop').val();
        $PayToLeft = $('#PayToLeft').val();
        $PayToWidth = $('#PayToWidth').val();
        
        $AmountNumTop = $('#AmountNumTop').val();
        $AmountNumLeft = $('#AmountNumLeft').val();
        $AmountNumWidth = $('#AmountNumWidth').val();
        
        $AmountTextTop = $('#AmountTextTop').val();
        $AmountTextLeft = $('#AmountTextLeft').val();
        $AmountTextWidth = $('#AmountTextWidth').val();
        
        $memoTop = $('#memoTop').val();
        $memoLeft = $('#memoLeft').val();
        $memoWidth = $('#memoWidth').val();
        
        envpost = $.post("/ACP/Envelope/Save",
        {
            _token: "{{ csrf_token() }}",
            totop: $totop,
            toleft: $toleft,
            toheight: $toheight,
            towidth: $towidth,
            
            datetop: $datetop,
            dateleft: $dateleft,
            datewidth: $datewidth,
            
            paytotop: $paytotop,
            paytoleft: $paytoleft,
            paytowidth: $paytowidth,
            
            amountnumtop: $amountnumtop,
            amountnumleft: $amountnumleft,
            amountnumwidth: $amountnumwidth,
            
            amounttexttop: $amounttexttop,
            amounttextleft: $amounttextleft,
            amounttextwidth: $amounttextwidth,
            
            memotop: $memotop,
            memoleft: $memoleft,
            memowidth: $memowidth

            
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