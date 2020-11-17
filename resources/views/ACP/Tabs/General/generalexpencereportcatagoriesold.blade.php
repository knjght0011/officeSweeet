<!--
No Longer used
-->

<div class="row">
    <div class="col-md-6">
        <label class="col-md-4 control-label" for="selectbasic">Categories</label>
        <select id="expence-cat" name="expence-cat" class="form-control" size="20">
            @foreach($ExpenseAccountCategorys as $ExpenseAccountCategory)
                @if($ExpenseAccountCategory->deleted_at == null)
                    <option value="{{ $ExpenseAccountCategory->id }}">{{ $ExpenseAccountCategory->category }}</option>
                @else
                    <option value="{{ $ExpenseAccountCategory->id }}">#{{ $ExpenseAccountCategory->category }}# - Disabled</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="col-md-6">

    <div class="row">
        <label class="col-md-12 control-label">Add Item:</label>
    </div>
    <div class="row">
        <button id="save-expence-cat" name="save-expence-cat" type="button" class="btn OS-Button col-md-2">Add</button>
        <div class="col-md-10">
            <input type="text" class="form-control" id="new-expence-cat"></input>
        </div>

    </div>
    <div class="row"><div class="col-md-12"><br></div></div>
    <div class="row">
        <button id="delete-expence-cat" name="delete-expence-cat" type="button" class="OS-Button col-md-2">Disable Selected</button>
    </div>
</div>
</div>


<div class="row">
    <div class="col-md-6">

        <label class="col-md-4 control-label" for="selectbasic">Sub-Categories</label>

        <select id="expence-subcat" name="expence-subcat" class="form-control" size="10">

        </select>
    </div>
    <div class="col-md-6">

        <div class="row">
            <label class="col-md-12 control-label">Add Item:</label>
        </div>
        <div class="row">
            <button id="save-expence-subcat" name="save-expence-subcat" type="button" class="btn OS-Button col-md-2">Add</button>
            <div class="col-md-10">
                <input type="text" class="form-control" id="new-expence-subcat"></input>
            </div>

        </div>
        <div class="row"><div class="col-md-12"><br></div></div>
        <div class="row">
            <button id="delete-expence-subcat" name="delete-expence-subcat" type="button" class="OS-Button col-md-2">Disable Selected</button>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    var $cats = {};
        @foreach($ExpenseAccountCategorys as $ExpenseAccountCategory)
        $cats["{{ $ExpenseAccountCategory->id }}"] = "{{ $ExpenseAccountCategory->category }}";
    @endforeach
    
    
    var $subs = {};
    @foreach($ExpenseAccountCategorys as $ExpenseAccountCategory)
        var temparray = [];
        @foreach($ExpenseAccountCategory->subcategories as $subcategories)
            temparray["{{ $subcategories->id }}"] = ("{{ $subcategories->subcategory }}"); 
        @endforeach
        $subs["{{ $ExpenseAccountCategory->id }}"] = temparray;
    @endforeach
    
    var $subids = {};
    @foreach($ExpenseAccountCategorys as $ExpenseAccountCategory)
        var temparray = {};
        @foreach($ExpenseAccountCategory->subcategories as $subcategories)
            temparray["{{ $subcategories->id }}"] = ("{{ $subcategories->id }}"); 
        @endforeach
        $subids["{{ $ExpenseAccountCategory->id }}"] = temparray;
    @endforeach
    
    var $subdel = {};
    @foreach($ExpenseAccountCategorys as $ExpenseAccountCategory)
        var temparray = [];
        @foreach($ExpenseAccountCategory->subcategories as $subcategories)
            @if($subcategories->deleted_at === null)
            temparray["{{ $subcategories->id }}"] = ("0");
            @else
            temparray["{{ $subcategories->id }}"] = ("1");
            @endif
        @endforeach
        $subdel["{{ $ExpenseAccountCategory->id }}"] = temparray;
    @endforeach
    
    $( "#expence-cat" ).change(function() {
        $('#expence-subcat').empty();
        $id = this.value;
        $subarray = $subs[$id];
        $subidarray = $subids[$id];
        $subdelarray = $subdel[$id];
        $subarray.forEach(function( key, value ) {
            console.log(key);
            console.log(value);
            if($subdelarray[value] === "0"){
                $('#expence-subcat').append($("<option></option>").attr("value", $subidarray[value]).text(key));
            }else{
                $('#expence-subcat').append($("<option></option>").attr("value", $subidarray[value]).text("#"+key+"# - Disabled"));
            }
            
        });
        
        $text = $("#expence-cat option:selected").text();
        if(/^[#]*$/.test($text.charAt(0)) === false) {
            $('#delete-expence-cat').text("Disable Selected");
           
        }else{
            $('#delete-expence-cat').text("Enable Selected");
            
        }   
        
    });
    
    $( "#expence-subcat" ).change(function() {
        
        $text = $("#expence-subcat option:selected").text();
        if(/^[#]*$/.test($text.charAt(0)) === false) {
            $('#delete-expence-subcat').text("Disable Selected"); 
        }else{
            $('#delete-expence-subcat').text("Enable Selected");
        }   
        
    });
    
    $("#expence-cat option:first").attr('selected','selected');
    $("#expence-cat").change();
    
    $("#save-expence-cat").click(function()
    {
        $variable = $('#new-expence-cat').val();
        $("body").addClass("loading");
        ResetServerValidationErrors();

        $post = $.post("/ACP/Expense/Categories/Save",
        {
            _token: "{{ csrf_token() }}",
            category: $variable
        });
        
        $post.done(function( data ) 
        {   
            
            console.log(data);
            if ($.isNumeric(data)) 
            {
                
                $cats[data] = $variable;
                temparray = {};
                $subs[data] = temparray;
                $subids[data] = temparray;
                $subdel[data] = temparray;
                
                $('#expence-cat').append($("<option></option>").attr("value",data).text($variable));
                $("body").removeClass("loading");
            } 
            else 
            {
                $("body").removeClass("loading");
                //server validation errors
                ServerValidationErrors(data);
            }

        });
        
        $post.fail(function() 
        {   
            $("body").removeClass("loading");
            alert("Error");
        });        
    });
    
    $("#delete-expence-cat").click(function()
    {
        $id = $('#expence-cat').val();
        $text = $("#expence-cat option:selected").text();
        if(/^[#]*$/.test($text.charAt(0)) === false) {
            $action = "deactivate";
        }else{
            $action = "reactivate";
        }
        $.confirm({
            title: "Are you sure you would like to "+ $action+" '"+$text+"'?" ,
            content: "This will also " + $action + " all subcatagories",
            buttons: {
                confirm: function() {
                    $("body").addClass("loading");
                    ResetServerValidationErrors();

                    $post = $.post("/ACP/Expense/Categories/Delete",
                    {
                        _token: "{{ csrf_token() }}",
                        categoryID: $id
                    });

                    $post.done(function( data ) 
                    {   
                        console.log(data);
                        if ($.isNumeric(data)) 
                        {
                            
                            $("body").removeClass("loading");
                            if(/^[#]*$/.test($text.charAt(0)) === false) {

                                $name = $cats[$("#expence-cat option:selected").val()];
                                $("#expence-cat option:selected").text("#"+$name+"# - Disabled");
                                $('#delete-expence-cat').text("Enable Selected");
                            }else{
                                $name = $cats[$("#expence-cat option:selected").val()];
                                $("#expence-cat option:selected").text($name);
                                $('#delete-expence-cat').text("Disable Selected");
                            }  
                        } 
                        else 
                        {
                            $("body").removeClass("loading");
                            //server validation errors
                            ServerValidationErrors(data);
                        }

                    });

                    $post.fail(function() 
                    {   
                        $("body").removeClass("loading");
                        alert("Error");
                    });
                },
                cancel: function() {
                    // nothing to do

                }
            }
        });
    });
    
    $("#save-expence-subcat").click(function()
    {
        $variable = $('#new-expence-subcat').val();
        $id = $('#expence-cat').val();
        $("body").addClass("loading");
        ResetServerValidationErrors();

        $post = $.post("/ACP/Expense/Subcategories/Save",
        {
            _token: "{{ csrf_token() }}",
            subcategory: $variable,
            categoryid: $id
        });
        
        $post.done(function( data ) 
        {   

            console.log(data);
            if ($.isNumeric(data)) 
            {
                temparray = $subs[$id];
                temparray[data] = $variable;
                $subs[$id] = temparray;
                 
                temparray = $subids[$id];
                temparray[data] = data;
                $subids[$id] = temparray;
                
                temparray = $subdel[$id];
                temparray[data] = "0";
                $subdel[$id] = temparray;
                
                
                $('#expence-subcat').append($("<option></option>").attr("value",data).text($variable));
                $("body").removeClass("loading");
            } 
            else 
            {
                $("body").removeClass("loading");
                //server validation errors
                ServerValidationErrors(data);
            }

        });
        
        $post.fail(function() 
        {   
            $("body").removeClass("loading");
            alert("Error");
        });        
    });
    
    $("#delete-expence-subcat").click(function()
    {

        $id = $('#expence-subcat').val();
        $text = $("#expence-subcat option:selected").text();
        
        if(/^[#]*$/.test($text.charAt(0)) === false) {
            $action = "deactivate";
        }else{
            $action = "reactivate";
        }
        $.confirm({
            title: "Are you sure you would like to "+ $action+" "+$text+"?" ,
            buttons: {
                confirm: function() {
                    $("body").addClass("loading");
                    $post = $.post("/ACP/Expense/Subcategories/Delete",
                    {
                        _token: "{{ csrf_token() }}",
                        subcategoryID: $id
                    });

                    $post.done(function( data ) 
                    {   
                        console.log(data);
                        if ($.isNumeric(data)) 
                        {
                            
                            $("body").removeClass("loading");
                            $catid = $("#expence-cat option:selected").val();
                            $subid = $("#expence-subcat option:selected").val()


                            if(/^[#]*$/.test($text.charAt(0)) === false) {

                                $cat = $subs[$catid];
                                $name = $cat[$subid];

                                $catdel = $subdel[$catid];
                                $catdel[$subid] = "1";
                                $subdel[$catid] = $catdel;

                                $("#expence-subcat option:selected").text("#"+$name+"# - Disabled");
                                $('#delete-expence-subcat').text("Enable Selected");
                            }else{
                                $cat = $subs[$catid];
                                $name = $cat[$subid];

                                $catdel = $subdel[$catid];
                                $catdel[$subid] = "0";
                                $subdel[$catid] = $catdel;

                                $("#expence-subcat option:selected").text($name);
                                $('#delete-expence-subcat').text("Disable Selected");
                            }  
                        } 
                        else 
                        {
                            $("body").removeClass("loading");
                            //server validation errors
                            ServerValidationErrors(data);
                        }

                    });

                    $post.fail(function() 
                    {   
                        $("body").removeClass("loading");
                        alert("Error");
                    });
                },
                cancel: function() {
                    // nothing to do

                }
            }    
        });
    });
});
</script>
