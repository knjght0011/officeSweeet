@extends('master')

@section('content')
    <div class="tabholder">
        <ul class="nav nav-tabs" role="tablist">
            <?php $i = 0; ?>
            @foreach($tables as $table)
            <li role="presentation" @if($i == 0) class="active" @endif ><a href="#{{ $table->name }}" aria-controls="home" role="tab" data-toggle="tab">{{ $table->displayname }}</a></li>
            <?php $i = 1; ?>
            @endforeach
         </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <?php $i = 0; ?>
            @foreach($tables as $table)

            <div role="tabpanel" class="tab-pane @if($i == 0) active @endif" id="{{ $table->name }}">
                <?php 
                echo $table->content;
                $i = 1;
                ?>
            </div>
            @endforeach
        </div>
    </div>


<script>
$('#myTabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})

@foreach($tables as $table)
$('#myTabs a[href="#{{ $table->name }}"]').tab('show')
@endforeach
</script>

@stop