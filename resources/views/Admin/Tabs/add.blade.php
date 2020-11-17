@extends('master')

@section('content')  


    <form method="POST" action="/Admin/Tabs/Add" class="form-horizontal">
        <fieldset>

        <!-- Form Name -->
            <legend>Add Tab</legend>
            
            <input name="_token" type="hidden" value="{{ csrf_token() }}">
            
            <!-- Text input-->
            <div class="form-group">
                <label class="col-md-1 control-label" for="name">Name</label>  
                <div class="col-md-4">
                    <input id="name" name="name" type="text" placeholder="Name" class="form-control input-md" required="">
                </div>

                <label class="col-md-1 control-label" for="displayname">Display Name</label>  
                <div class="col-md-4">
                    <input id="displayname" name="displayname" type="text" placeholder="Display Name" class="form-control input-md" required="">
                </div>
            </div>

            <!-- Textarea -->
            <div class="form-group">
                <label class="col-md-1 control-label" for="content">HTML</label>
                <div class="col-md-9">                     
                    <textarea class="form-control" id="content" name="content"></textarea>
                </div>
            </div>

            <!-- Select Multiple -->
            <div class="form-group">
                <label class="col-md-1 control-label" for="feilds">Fields</label>
                <div class="col-md-9">
                    <select id="feilds" name="feilds" class="form-control" multiple="multiple">
                        <option value="1">Option one</option>
                        <option value="2">Option two</option>
                        <option value="3">Option three</option>
                        <option value="4">Option four</option>
                        <option value="5">Option five</option>
                    </select>
                </div>
            </div>

            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for=""></label>
                <div class="col-md-4">
                    <button id="" name="" class="btn OS-Button">Submit</button>
                </div>
            </div>

        </fieldset>
    </form>   


@if (isset($success))
    <div class="alert alert-success">
        {{ $success }}
    </div>
@endif

@foreach ($errors->all() as $error)
    <div class="alert alert-danger">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        {{ $error }}
    </div>
@endforeach

@stop
