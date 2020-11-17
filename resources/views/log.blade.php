@extends('master')

@section('content')
<div class="panel-group" id="accordion">
    <?php
    $c = 1;
    ?>
    @foreach($log as $key => $value)
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $c }}">{{ $key }}</a>
                </h4>
            </div>
            <div id="collapse{{ $c }}" class="panel-collapse collapse">
                <div class="panel-body">
                    {{ $value }}
                </div>
            </div>
        </div>
        <?php
        $c++ ;
        ?>
    
    @endforeach
</div>




@stop
