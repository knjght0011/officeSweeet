<div name="calcwindow" class="calcwindow">
    <div class="calcbar">
        Drag here to Move<div class="calcexit"></div>
    </div>
<form name="calculator" >
<input id="calcoutput" class="calcoutput" type="textfield" name="ans" value="">
<br>
<input type="button" class="calcbutton" value="1" onClick="document.calculator.ans.value+='1'"><input type="button" class="calcbutton" value="2" onClick="document.calculator.ans.value+='2'"><input type="button" class="calcbutton" value="3" onClick="document.calculator.ans.value+='3'"><input type="button" class="calcbutton" value="+" onClick="document.calculator.ans.value+='+'">
<br>
<input type="button" class="calcbutton" value="4" onClick="document.calculator.ans.value+='4'"><input type="button" class="calcbutton" value="5" onClick="document.calculator.ans.value+='5'"><input type="button" class="calcbutton" value="6" onClick="document.calculator.ans.value+='6'"><input type="button" class="calcbutton" value="-" onClick="document.calculator.ans.value+='-'">
<br>
<input type="button" class="calcbutton" value="7" onClick="document.calculator.ans.value+='7'"><input type="button" class="calcbutton" value="8" onClick="document.calculator.ans.value+='8'"><input type="button" class="calcbutton" value="9" onClick="document.calculator.ans.value+='9'"><input type="button" class="calcbutton" value="*" onClick="document.calculator.ans.value+='*'">
<br>
<input type="button" class="calcbutton" value="0" onClick="document.calculator.ans.value+='0'"><input type="button" class="calcbutton" value="." onClick="document.calculator.ans.value+='.'"><input type="reset" class="calcbutton" value="Reset"><input type="button" class="calcbutton" value="=" onClick="document.calculator.ans.value=eval(document.calculator.ans.value)">
</form>
</div>
<script>
$( ".calcwindow" ).draggable(); 
$( ".calcexit" ).click(function() {
    $( ".calcwindow" ).css("display", "none");
    
});
</script>