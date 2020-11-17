@extends('master')

@section('content')  
    <form action="/Templates/Upload" method="post" enctype="multipart/form-data">
        <input name="_token" type="hidden" value="{{ csrf_token() }}">
        <div style="float:left; width: 25em; margin-right: 5px;">
            <label class="form-control-label" for="firstname">Please select one of your documents to upload:</label>
        </div>    
        <div style="float:left; width: 20em; margin-right: 5px;">
            <input style="max-width: 100%;" type="file" class="btn OS-Button btn-sm" name="fileToUpload" id="fileToUpload">
        </div>
        <div style="float:left; width: 10em;">
            <input style="max-width: 100%;" type="submit" class="btn OS-Button btn-sm" value="Upload Document" name="submit">
        </div>
    </form>
@stop