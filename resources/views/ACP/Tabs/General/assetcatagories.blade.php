<div class="row" style="margin-top: 20px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="Category-search"><div>Search:</div></span>
            <input id="asset-Category-search" name="Category-search" type="text" class="form-control" >
        </div>
    </div>

    <div class="col-md-6">
        {!! PageElement::TableControl('asset') !!}
    </div>

    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="Category-length"><div>Show:</div></span>
            <select id="asset-Category-length" name="Category-length" type="text" placeholder="choice" class="form-control">
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
        <button data-toggle="modal" data-target="#asset-add-category-modal" type="button" class="btn OS-Button" style="width: 100%;">Add Category</button>
    </div>
</div>

<table class="table" id="assetcategorytable">
    <thead>
        <tr id="asset-head">
            <th>id</th>
            <th>category_id</th>
            <th>Category</th>
            <th></th>
            <th>Type</th>
            <th>Disabled</th>
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
        </tr>
    </tfoot>
    <tbody>
    @foreach($assetCategorys as $category)
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
                    <option value="asset"  @if($category->type === "asset")
                                            selected
                                            @endif>Asset</option>
                    <option value="liability" @if($category->type === "liability")
                                            selected
                                            @endif>Liability</option>
                    <option value="equity"    @if($category->type === "equity")
                                            selected
                                            @endif>Equity</option>
                </select>
            </td>
            <td>
                <input class="form-check-input catagory-disabled" type="checkbox" data-id="{{ $category->id }}" @if($category->deleted_at != null)
                checked
                @endif
                >
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="modal fade" id="asset-add-category-modal" tabindex="-1" role="dialog" aria-labelledby="add-category-modal"
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
                    <span width="10em" class="input-group-addon" for="asset-add-category-name"><div style="width: 164px;">Name:</div></span>
                    <input id="asset-add-category-name" name="asset-add-category-name" type="text" class="form-control input-md">
                </div>

                <div class="input-group">
                    <span width="10em" class="input-group-addon" for="asset-add-category-type"><div style="width: 164px;">Type:</div></span>
                    <select id="asset-add-category-type" name="asset-add-category-type" type="text" class="form-control input-md">
                        <option value="asset" selected="">Asset</option>
                        <option value="liability">Liability</option>
                        <option value="equity">Equity</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button id="asset-Category-add" name="asset-Category-add" type="button" class="btn OS-Button" value="">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    var assetcategorytable = $('#assetcategorytable').DataTable({
        "pageLength": 10,
        "columnDefs": [
            {
                "targets": [0,1],
                "visible": false
            }
        ]
    });

    $( "#asset-previous-page" ).click(function() {
        assetcategorytable.page( "previous" ).draw('page');
        PageinateUpdate(assetcategorytable.page.info(), $('#asset-next-page'), $('#asset-previous-page'),$('#asset-tableInfo'));
        $('.catagory-type').change(function () {
            ChangeType($(this));
        });
        $('.catagory-disabled').change(function () {
            DisableCatagory($(this));
        });
    });

    $( "#asset-next-page" ).click(function() {
        assetcategorytable.page( "next" ).draw('page');
        PageinateUpdate(assetcategorytable.page.info(), $('#asset-next-page'), $('#asset-previous-page'),$('#asset-tableInfo'));
        $('.catagory-type').change(function () {
            ChangeType($(this));
        });
        $('.catagory-disabled').change(function () {
            DisableCatagory($(this));
        });
    });

    $('#assetCategory-search').on( 'keyup change', function () {
        assetcategorytable.search( this.value ).draw();
        PageinateUpdate(assetcategorytable.page.info(), $('#asset-next-page'), $('#asset-previous-page'),$('#asset-tableInfo'));
        $('.catagory-type').change(function () {
            ChangeType($(this));
        });
        $('.catagory-disabled').change(function () {
            DisableCatagory($(this));
        });
    });

    $('#assetCategory-length').on( 'change', function () {
        assetcategorytable.page.len( this.value ).draw();
        PageinateUpdate(assetcategorytable.page.info(), $('#asset-next-page'), $('#asset-previous-page'),$('#asset-tableInfo'));
        $('.catagory-type').change(function () {
            ChangeType($(this));
        });
        $('.catagory-disabled').change(function () {
            DisableCatagory($(this));
        });
    });

    PageinateUpdate(assetcategorytable.page.info(), $('#asset-next-page'), $('#asset-previous-page'),$('#asset-tableInfo'));

    $( "#generalassetcatagories" ).children().find(".dataTables_filter").css('display', 'none');
    $( "#generalassetcatagories" ).children().find(".dataTables_length").css('display', 'none');
    $( "#generalassetcatagories" ).children().find(".dataTables_paginate").css('display', 'none');
    $( "#generalassetcatagories" ).children().find(".dataTables_info").css('display', 'none');
    $('#assetcategorytable').css('width' , "100%");

    $('#asset-Category-add').click(function(){

        AddNewCatagory($('#asset-add-category-name').val(), $('#asset-add-category-type').val(), "generalassetcatagories");

    });
});
</script>
