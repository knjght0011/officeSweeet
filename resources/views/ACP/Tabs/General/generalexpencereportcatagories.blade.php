<div class="row" style="margin-top: 20px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="catagory-search"><div>Search:</div></span>
            <input id="catagory-search" name="catagory-search" type="text" class="form-control" >
        </div>
    </div>

    <div class="col-md-6">
        {!! PageElement::TableControl('catagory') !!}
    </div>

    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="catagory-length"><div>Show:</div></span>
            <select id="catagory-length" name="catagory-length" type="text" placeholder="choice" class="form-control">
                <option value="10">10 entries</option>
                <option value="15">15 entries</option>
                <option value="20">20 entries</option>
                <option value="25">25 entries</option>
                <option value="50">50 entries</option>
                <option value="100">100 entries</option>
            </select>
        </div>
    </div>
</div>

<div class="row" style="margin-top: 20px;">
    <div class="col-md-3">
        <button data-toggle="modal" data-target="#add-category-modal" type="button" class="btn OS-Button" style="width: 100%;">Add Category</button>
    </div>
</div>

<table class="table" id="categorytable">
    <thead>
        <tr id="head">
            <th>id</th>
            <th>category_id</th>
            <th>Category</th>
            <th></th>
            <th>Type</th>
            <th>Disabled</th>
            <th>Monthly Budget</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>id</th>
            <th>category_id</th>
            <th>Category</th>
            <th></th>
            <th>Type</th>
            <th>Disabled</th>
            <th>Monthly Budget</th>
        </tr>
    </tfoot>
    <tbody>
    @foreach($ExpenseAccountCategorys as $category)
        <tr>
            <td>{{ $category->id }}</td>
            <td>
            </td>
            <td>{{ $category->category }}</td>
            <td>
                <button class="btn OS-Button" data-toggle="modal" data-target="#subcatagoryModal" data-id="{{ $category->id }}" data-subs="{{ $category->subcatagoryJson() }}" >Subcategories</button>
            </td>
            <td>
                <select style="width: 100%;" class="form-control catagory-type" data-id="{{ $category->id }}">
                    <option value="income"  @if($category->type === "income")
                                            selected
                                            @endif>Income</option>
                    <option value="expense" @if($category->type === "expense")
                                            selected
                                            @endif>Expense</option>
                    <option value="both"    @if($category->type === "both")
                                            selected
                                            @endif>Both</option>
                </select>
            </td>
            <td>
                <input class="form-check-input catagory-disabled" type="checkbox" data-id="{{ $category->id }}" @if($category->deleted_at != null)
                checked
                @endif
                >
            </td>
            <td>
                $ <input class="Budget-Monthly" data-id="{{ $category->id }}" name="txtMonthlyBudget" type="text"  class="form-control" value="{{ $category->MonthlyBudgetFormated() }}">
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="modal fade" id="add-category-modal" tabindex="-1" role="dialog" aria-labelledby="add-category-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="">Add Category</h4>
            </div>
            <div class="modal-body">
                <div class="input-group">
                        <span width="10em" class="input-group-addon" for="depreciation-amount"><div style="width: 164px;">Name:</div></span>
                    <input id="add-category-name" name="add-category-name" type="text" class="form-control input-md">
                </div>

                <div class="input-group">
                        <span width="10em" class="input-group-addon" for="add-category-type"><div style="width: 164px;">Type:</div></span>
                    <select id="add-category-type" name="add-category-type" type="text" class="form-control input-md">
                        <option value="income">Income</option>
                        <option value="expense" selected="">Expense</option>
                        <option value="both">Both</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button id="catagory-add" name="catagory-add" type="button" class="btn OS-Button" value="">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    var categorytable = $('#categorytable').DataTable({
        "pageLength": 10,
        "columnDefs": [
            {
                "targets": [0,1],
                "visible": false
            }
        ]
    });

    $( "#catagory-previous-page" ).click(function() {
        categorytable.page( "previous" ).draw('page');
        PageinateUpdate(categorytable.page.info(), $('#catagory-next-page'), $('#catagory-previous-page'),$('#catagory-tableInfo'));
        $('.catagory-type').change(function () {
            ChangeType($(this));
        });
        $('.catagory-disabled').change(function () {
            DisableCatagory($(this));
        });
    });

    $( "#catagory-next-page" ).click(function() {
        categorytable.page( "next" ).draw('page');
        PageinateUpdate(categorytable.page.info(), $('#catagory-next-page'), $('#catagory-previous-page'),$('#catagory-tableInfo'));
        $('.catagory-type').change(function () {
            ChangeType($(this));
        });
        $('.catagory-disabled').change(function () {
            DisableCatagory($(this));
        });
    });

    $('#catagory-search').on( 'keyup change', function () {
        categorytable.search( this.value ).draw();
        PageinateUpdate(categorytable.page.info(), $('#catagory-next-page'), $('#catagory-previous-page'),$('#catagory-tableInfo'));
        $('.catagory-type').change(function () {
            ChangeType($(this));
        });
        $('.catagory-disabled').change(function () {
            DisableCatagory($(this));
        });
    });

    $('#catagory-length').on( 'change', function () {
        categorytable.page.len( this.value ).draw();
        PageinateUpdate(categorytable.page.info(), $('#catagory-next-page'), $('#catagory-previous-page'),$('#catagory-tableInfo'));
        $('.catagory-type').change(function () {
            ChangeType($(this));
        });
        $('.catagory-disabled').change(function () {
            DisableCatagory($(this));
        });
    });

    PageinateUpdate(categorytable.page.info(), $('#catagory-next-page'), $('#catagory-previous-page'),$('#catagory-tableInfo'));

    $( "#generalexpencereportcatagories" ).children().find(".dataTables_filter").css('display', 'none');
    $( "#generalexpencereportcatagories" ).children().find(".dataTables_length").css('display', 'none');
    $( "#generalexpencereportcatagories" ).children().find(".dataTables_paginate").css('display', 'none');
    $( "#generalexpencereportcatagories" ).children().find(".dataTables_info").css('display', 'none');
    $('#categorytable').css('width' , "100%");

    $('#subcatagoryModal').on('show.bs.modal', function (event) {

        var button = $(event.relatedTarget); // Button that triggered the modal
        $('#subcatagoryModal').data('id' ,  button.data('id'));
        $('#subcatagoryModal').data('button' ,  button);
        $('#subcatagoryModalBody').html('');

         $.each(button.data('subs'), function( index, value ) {
            $('#subcatagoryModalBody').append('<input type="text" class="form-control subcatagory-input" value="'+index+'" data-id="'+value+'">');
        });

    });

    $('#subcatagory-add').click(function () {
        $('#subcatagoryModalBody').append('<input type="text" class="form-control subcatagory-input" value="" data-id="0">');
    });

    $('#subcatagory-save').click(function () {

        $("body").addClass("loading");

        var values = {};
        values["_token"] = "{{ csrf_token() }}";
        values["catagoryid"] = $('#subcatagoryModal').data('id');

        $( ".subcatagory-input" ).each(function(){
            values[$(this).val()] = $(this).data('id');
        });

        $post = $.post("/ACP/Expense/Subcategories/Save", values);

        $post.done(function( data )
        {
            $("body").removeClass("loading");
            var button = $('#subcatagoryModal').data('button');
            button.data('subs', $.parseJSON(data));
            $('#subcatagoryModal').modal('hide');
        });

        $post.fail(function()
        {
            $("body").removeClass("loading");
            alert("Error");
        });

    });

    $('.catagory-type').change(function () {
        ChangeType($(this));
    });

    $('.catagory-disabled').change(function () {
        DisableCatagory($(this));
    });

    $('.Budget-Monthly').on( 'blur', function () {
        UpdateMonthlyBudget($(this));
    })

    $('#catagory-add').click(function(){

        AddNewCatagory($('#add-category-name').val(), $('#add-category-type').val(), "generalexpencereportcatagories");

    });
});

function DisableCatagory($checkbox){


    $("body").addClass("loading");
    $id = $checkbox.data('id');

    $post = $.post("/ACP/Expense/Categories/Delete",
        {
            _token: "{{ csrf_token() }}",
            categoryID: $id
        });

    $post.done(function( data )
    {
        console.log(data);
        $("body").removeClass("loading");
        if ($.isNumeric(data))
        {
            if(data === "disabled"){
                $checkbox.prop('checked', true);
            }
            if(data === "enabled"){
                $checkbox.prop('checked', false);
            }
        }
        else
        {
            if($checkbox.is(':checked')){
                $checkbox.prop('checked', true);
            }else{
                $$checkbox.prop('checked', false);
            }
            //server validation errors
            ServerValidationErrors(data);
        }

    });

    $post.fail(function()
    {
        $("body").removeClass("loading");
        if($checkbox.is(':checked')){
            $checkbox.prop('checked', true);
        }else{
            $checkbox.prop('checked', false);
        }
        alert("Error");
    });
}

function ChangeType($select){
    $("body").addClass("loading");


    var values = {};
    values["_token"] = "{{ csrf_token() }}";
    values["id"] = $select.data('id');
    values["type"] = $select.val();

    $post = $.post("/ACP/Expense/Categories/Type", values);

    $post.done(function( data )
    {
        $("body").removeClass("loading");
    });

    $post.fail(function()
    {
        $("body").removeClass("loading");
        alert("Error");
    });
}

function UpdateMonthlyBudget($select){
    $("body").addClass("loading");


    var values = {};
    values["_token"] = "{{ csrf_token() }}";
    values["id"] = $select.data('id');
    values["MonthlyBudget"] = parseFloat($select.val()) * 100;

    $post = $.post("/ACP/Expense/Budget/Update", values);

    $post.done(function( data )
    {
        $("body").removeClass("loading");
    });

    $post.fail(function()
    {
        $("body").removeClass("loading");
        alert("Error");
    });
}

function AddNewCatagory($name, $type, $location){

    $("body").addClass("loading");
    $post = $.post("/ACP/Expense/Categories/Save",
        {
            _token: "{{ csrf_token() }}",
            category: $name,
            type: $type,
        });

    $post.done(function( data )
    {
        console.log(data);
        if ($.isNumeric(data))
        {
            GoToPage('/ACP/General/' + $location);
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
}
</script>
