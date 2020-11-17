@extends('master')

@section('content')  


    
    <form method="POST" action="/Clients/Add" class="form-horizontal">
        <fieldset>
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
            <!-- Form Name -->
            <legend>Add {{ TextHelper::GetText("Client") }}</legend>

            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="name">Name</label>  
                <div class="col-md-5">
                <input id="name" name="name" type="text" placeholder="Name" class="form-control input-md" required="">

                </div>
            </div>
            
            <table id="search" class="table">
                <thead>
                    <tr id="head">
                        <th>Selected</th>
                        <th>Address Line 1</th>
                        <th>Address Line 2</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Zip</th>
                    </tr>
                </thead>
                <tfoot style="display: table-header-group;">
                    <tr>
                        <th>Selected</th>
                        <th>Address Line 1</th>
                        <th>Address Line 2</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Zip</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($address as $add)
                    <tr>
                        <th><input type="radio" name="address_id" id="address_id_{{ $add->id }}" value="{{ $add->id }}"></th>
                        <th>{{ $add->address1 }}</th>
                        <th>{{ $add->address2 }}</th>
                        <th>{{ $add->city }}</th>
                        <th>{{ $add->state }}</th>
                        <th>{{ $add->zip }}</th>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <button id="OpenAddressModel" type="button" class="btn OS-Button btn-md">    
                Add Address
            </button>
            
            <!-- Select Basic 
            <div class="form-group">
                <label class="col-md-4 control-label" for="address_id">Address</label>
                <div class="col-md-5">
                    <select id="address_id" name="address_id" class="form-control">
                        @foreach ($address as $add)
                            <option value="{{ $add->id }}">{{ $add->address1 }} - {{ $add->address2 }} - {{ $add->city }} - {{ $add->state }} - {{ $add->zip }}</option>
                        @endforeach
                    </select>
                    <!--<button type="button" class="btn OS-Button btn-md" data-toggle="modal" data-target="#AddAddress">

                </div>
            </div>-->

            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="submit"></label>
                <div class="col-md-4">
                  <button id="submit" name="submit" class="btn OS-Button">Save</button>
                </div>
            </div>
        </fieldset>
    </form>

    <!--add address model-->
    <div class="modal fade" id="AddAddress" data-backdrop="">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="form-horizontal">
                    <fieldset>
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title">Add Address</h4>
                        </div>

                        <div class="modal-body">

                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="address1	">Address Line 1</label>  
                                <div class="col-md-8">
                                <input id="address1" name="address1" type="text" placeholder="Address Line 1" class="form-control input-md" required="">

                                </div>
                            </div>

                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="address2">Address Line 2</label>  
                                <div class="col-md-8">
                                <input id="address2" name="address2" type="text" placeholder="Address Line 2" class="form-control input-md">

                                </div>
                            </div>

                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="city">City</label>  
                                <div class="col-md-8">
                                <input id="city" name="city" type="text" placeholder="City" class="form-control input-md" required="">

                                </div>
                            </div>

                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="state">State</label>  
                                <div class="col-md-8">
                                <input id="state" name="state" type="text" placeholder="State" class="form-control input-md" required="">

                                </div>
                            </div>

                            <!-- Text input-->
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="zip">Zip</label>  
                                <div class="col-md-8">
                                <input id="zip" name="zip" type="text" placeholder="Zip" class="form-control input-md" required="">

                                </div>
                            </div>

                            <!-- Text input
                            <div class="form-group">
                              <label class="col-md-4 control-label" for="type">Type</label>  
                              <div class="col-md-4">
                              <input id="type" name="type" type="text" placeholder="Type" class="form-control input-md" required="">
                              <span class="help-block">Home/Work/Ect..</span>  
                              </div>
                            </div>-->

                        </div>
                        <div class="modal-footer">
                          <button id="addressclose" type="button" class="btn btn-secondary" data-backdrop="false" data-dismiss="modal">Close</button>
                          <button id="addresssave" type="button" class="btn OS-Button">Save changes</button>
                        </div>

                    </fieldset>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
 
    
    
<script>    
$(document).ready(function() {
    $("#OpenAddressModel").click(function(){
        $('#AddAddress').modal('toggle');
    });
    
    $("#addresssave").click(function(){
        var $address1 = $('#address1').val();
        var $address2 = $('#address2').val();
        var $city = $('#city').val();
        var $state = $('#state').val();
        var $zip = $('#zip').val();
        
        $.post("/Address/Add",
        {
            _token: "{{ csrf_token() }}",
            address1: $address1,
            address2: $address2,
            city: $city,
            state: $state,
            zip: $zip,

        },
        
        function(data, status){
            if ($.isNumeric(data)) {
                //$('#address_id').append($('<option/>', { 
                //value: data,
                //    text :  $address1 + " - " + $address2 + " - " + $city + " - " + $state + " - " + $zip
                //}));
                var $selected = "<input type=\"radio\" name=\"address_id\" id=\"address_id_" + data + "\" value=\"" + data + "\">";
                var row = table.row.add( [
                    $selected,
                    $address1,
                    $address2,
                    $city,
                    $state,
                    $zip
                ] ).draw();
                
                table.page( 'last' ).draw( 'page' );
                row.nodes().to$().addClass('highlight');
                $('#AddAddress').modal('toggle');

            } else {
                ServerValidationErrors(data);
                
            }
        });
    });
    
    // Setup - add a text input to each footer cell
    $('#search tfoot th').each( function () {
        var title = $(this).text();
        $(this).html( '<input class="form-control" type="text" placeholder="Search '+title+'" />' );
    } );
 
    // DataTable
    var table = $('#search').DataTable();
 
    // Apply the search
    table.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    } );
});
</script>

    <!--

    

    -->
</div>
@stop