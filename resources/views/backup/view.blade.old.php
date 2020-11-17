@extends('master')

@section('content')  
<?php 
$selected_vendor = Session::get('SelectedVendor');
$vendor = Session::get('Vendor');

?>

<div class="well halfiframe">
    <legend>{{ $vendor->name }}</legend>
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#details" aria-controls="home" role="tab" data-toggle="tab">Details</a></li>
        <li role="presentation"><a href="#contacts" aria-controls="profile" role="tab" data-toggle="tab">Contacts</a></li>
        <li role="presentation"><a href="#files" aria-controls="profile" role="tab" data-toggle="tab">Files</a></li>
        <li role="presentation"><a href="#invoices" aria-controls="profile" role="tab" data-toggle="tab">Invoices</a></li>
        <li role="presentation"><a href="#debug" aria-controls="profile" role="tab" data-toggle="tab">Debug</a></li>
    </ul>
    
    <div class="tab-content">
        
        <div role="tabpanel" class="tab-pane active" id="details">
            @include('Vendors.view.details')
        </div>
        
        <div role="tabpanel" class="tab-pane" id="contacts">
            @include('Vendors.view.contacts')
        </div>
        
        <div role="tabpanel" class="tab-pane" id="files">
            @include('Vendors.view.files')
        </div>
        
        <div role="tabpanel" class="tab-pane" id="invoices">
            @include('Vendors.view.invoices')
        </div>
        
        <div role="tabpanel" class="tab-pane" id="debug">
            @include('Vendors.view.debug')
        </div>
        
    </div>            
</div>



<script>
$('#myTabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})


$('#myTabs a[href="#contacts"]').tab('show')
$('#myTabs a[href="#debug"]').tab('show')
$('#myTabs a[href="#files"]').tab('show')
$('#myTabs a[href="#invoices"]').tab('show')
$('#myTabs a[href="#details"]').tab('show')

</script>

@stop