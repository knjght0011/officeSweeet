


<div class="row" style="margin-top: 15px;">
    <div class="col-md-3">
        <div class="input-group ">
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="takestock-search" name="takestock-search" type="text" placeholder="" value="" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        {!! PageElement::TableControl('takestock') !!}
    </div>

</div>

    <table id="takestocktable" class="table">
        <thead>
            <tr id="head">
                <th>SKU</th>
                <th>Product/Service</th>
                <th></th>
                <th style="text-align: center;">Stock</th>
                <th></th>
            </tr>
        </thead>
        <tfoot>
            <tr id="head">
                <th>SKU</th>
                <th>Product/Service</th>
                <th></th>
                <th style="text-align: center;">Stock</th>
                <th></th>
            </tr>
        </tfoot>
        <tbody>
            @foreach($products as $product)
            @if($product->companyuse === 1)
            <tr>
                <td class="col-md-1">{{ $product->SKU }}</td>
                <td>{{ $product->productname }}</td>
                <td class="col-md-1">
                    <button style="width: 100%;" type="button" class="btn OS-Button DecrementStock" data-id="{{$product->id}}">
                        <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                    </button>
                </td>
                <td class="col-md-1" style="text-align: center;" id="takestock-stock-{{$product->id}}">{{ $product->stockiftracked()  }}</td>
                <td class="col-md-1">
                    <button style="width: 100%;" type="button" class="btn OS-Button IncrementStock" data-id="{{$product->id}}">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </button>
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>


<script>
$(document).ready(function() {
    
    // DataTable
    var takestocktable = $('#takestocktable').DataTable({});


    $( "#takestock-previous-page" ).click(function() {
        takestocktable.page( "previous" ).draw('page');
        PageinateUpdate(takestocktable.page.info(), $('#takestock-next-page'), $('#takestock-previous-page'),$('#takestock-tableInfo'));
    });

    $( "#takestock-next-page" ).click(function() {
        takestocktable.page( "next" ).draw('page');
        PageinateUpdate(takestocktable.page.info(), $('#takestock-next-page'), $('#takestock-previous-page'),$('#takestock-tableInfo'));
    });

    $('#takestock-search').on( 'keyup change', function () {
        takestocktable.search( this.value ).draw();
        PageinateUpdate(takestocktable.page.info(), $('#takestock-next-page'), $('#takestock-previous-page'),$('#takestock-tableInfo'));
    });

    PageinateUpdate(takestocktable.page.info(), $('#takestock-next-page'), $('#takestock-previous-page'),$('#takestock-tableInfo'));

    $( "#generaltakestock" ).children().find(".dataTables_filter").css('display', 'none');
    $( "#generaltakestock" ).children().find(".dataTables_length").css('display', 'none');
    $( "#generaltakestock" ).children().find(".dataTables_paginate").css('display', 'none');
    $( "#generaltakestock" ).children().find(".dataTables_info").css('display', 'none');
    $('#takestocktable').css('width' , "100%");

    $('.IncrementStock').click(function () {
        $("body").addClass("loading");

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['id'] = $(this).data('id');

        $post = $.post("/Products/IncrementStock", $data);

        $post.done(function (data) {
            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":
                    $('#takestock-stock-' + data['id']).html(data['stock']);


                    break;
                case "notfound":
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Response from server. Please refresh the page and try again.'
                    });
                    break;
                default:
                    console.log(data);
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Response from server. Please refresh the page and try again.'
                    });
            }
        });

        $post.fail(function () {
            NoReplyFromServer();
        });
    });

    $('.DecrementStock').click(function () {
        $("body").addClass("loading");

        $data = {};
        $data['_token'] = "{{ csrf_token() }}";
        $data['id'] = $(this).data('id');

        $post = $.post("/Products/DecrementStock", $data);

        $post.done(function (data) {
            $("body").removeClass("loading");
            switch(data['status']) {
                case "OK":
                    $('#takestock-stock-' + data['id']).html(data['stock']);
                    @if(Auth::User()->hasPermissionMulti('multi_assets_permission', 1))
                    UpdateStock(data['id'], data['stock']);
                    @endif
                    break;
                case "notfound":
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Response from server. Please refresh the page and try again.'
                    });
                    break;
                default:
                    console.log(data);
                    $.dialog({
                        title: 'Oops...',
                        content: 'Unknown Response from server. Please refresh the page and try again.'
                    });
            }
        });

        $post.fail(function () {
            NoReplyFromServer();
        });
    });

});
</script>