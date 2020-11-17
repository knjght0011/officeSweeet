@extends('master')

@section('content')  
    
<div class="col-md-12 well">
    <form method="POST" action="/Address/Add" class="form-horizontal">
<fieldset>

<!-- Form Name -->
<legend>Add Address</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="address1	">Address Line 1</label>  
  <div class="col-md-4">
  <input id="address1" name="address1" type="text" placeholder="Address Line 1" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="address2">Address Line 2</label>  
  <div class="col-md-4">
  <input id="address2" name="address2" type="text" placeholder="Address Line 2" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="city">City</label>  
  <div class="col-md-4">
  <input id="city" name="city" type="text" placeholder="City" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="state">State</label>  
  <div class="col-md-4">
  <input id="state" name="state" type="text" placeholder="State" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="zip">Zip</label>  
  <div class="col-md-4">
  <input id="zip" name="zip" type="text" placeholder="Zip" class="form-control input-md" required="">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="type">Type</label>  
  <div class="col-md-4">
  <input id="type" name="type" type="text" placeholder="Type" class="form-control input-md" required="">
  <span class="help-block">Home/Work/Ect..</span>  
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="submit"></label>
  <div class="col-md-4">
    <button id="submit" name="submit" class="btn OS-Button">Save</button>
  </div>
</div>

</fieldset>
</form>

    @if (isset($success))
        <div class="alert alert-success">
            {{ $success }}
        </div>
    @endif
    
    <!-- if there are errors, show them here -->
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    @endforeach
    
</div>    

@stop