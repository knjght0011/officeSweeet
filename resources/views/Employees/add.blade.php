@extends('master')

@section('content')
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
    
    <form method="POST" action="/Employees/Add" class="form-horizontal">
    <fieldset>
    <input name="_token" type="hidden" value="{{ csrf_token() }}">
    <!-- Form Name -->
    <legend>Add Employee</legend>

    <!-- Text input-->
    <div class="input-group" >
        <span class="input-group-addon" for="firstname"><div class="inputdiv">ID</div></span>
        <input id="name" name="employeeid" type="text" placeholder="Employee ID" class="form-control " required="">
    </div>    
    
    <!-- Text input-->
    <div class="input-group" >
        <span class="input-group-addon" for="firstname"><div  class="inputdiv">First Name</div></span>  
        <input id="name" name="firstname" type="text" placeholder="First Name" class="form-control " >
    </div>
    
        <!-- Text input-->
    <div class="input-group" >
        <span class="input-group-addon" for="firstname"><div class="inputdiv">Middle Name</div></span>
        <input id="name" name="middlename" type="text" placeholder="Middle Name" class="form-control " required="">
    </div>
        
    <!-- Text input-->
    <div class="input-group" >
        <span class="input-group-addon" for="firstname"><div  class="inputdiv">Last Name</div></span>  
        <input id="name" name="lastname" type="text" placeholder="Last Name" class="form-control " required="">
    </div>

    <!-- Select Basic -->
    <div class="input-group" >
        <label class="col-md-4 control-label" for="address_id">Address</div></span>
        <select id="address_id" name="address_id" class="form-control">
            @foreach ($address as $add)
                <option value="{{ $add->id }}">{{ $add->address1 }} - {{ $add->address2 }} - {{ $add->city }} - {{ $add->state }} - {{ $add->zip }}</option>
            @endforeach
        </select>
    </div>
    
    <!-- Text input-->
    <div class="input-group" >
        <span class="input-group-addon" for="firstname"><div  class="inputdiv">Social Security Number</div></span>  
        <input id="name" name="ssn" type="text" placeholder="Social Security Number" class="form-control " required="">
    </div>
    
    <!-- Text input-->
    <div class="input-group" >
        <span class="input-group-addon" for="firstname"><div  class="inputdiv">Drivers License</div></span>
        <input id="name" name="driverslicense" type="text" placeholder="Drivers License" class="form-control " >
    </div>
    
    <!-- Text input-->
    <div class="input-group" >
        <label class="col-md-4 control-label" for="email">E-Mail</div></span>
        <input id="name" name="email" type="text" placeholder="E-Mail" class="form-control " required="">
    </div>
    
        <!-- Text input-->
    <div class="input-group" >
        <span class="input-group-addon" for="firstname"><div  class="inputdiv">Department</div></span>
        <input id="name" name="department" type="text" placeholder="Department" class="form-control ">
    </div>

    <!-- Button -->
    <div class="input-group" >
      <label class="col-md-4 control-label" for="submit"></label>
      <div class="col-md-4">
        <button id="submit" name="submit" class="btn OS-Button">Save</button>
      </div>
    </div>

    </fieldset>
    </form>


@stop