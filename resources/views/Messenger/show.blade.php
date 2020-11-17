@extends('master')

@section('content')
<div class="row">
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button style="width: 100%;" type="button" class="btn OS-Button" data-toggle="modal" data-target="#respondmodal">Respond Now</button>
    </div>
    <div style="float:left; width: 15em;  margin-left: 20px;">
        <button style="width: 100%;" id="backbutton" name="backbutton" type="button" class="btn OS-Button">Back to Messages</button>
    </div>
</div> 

<h1>{{ $thread->subject }}</h1>

@foreach($thread->messages->reverse() as $message)
<div class="message-container">
@if($message->user->id === Auth::user()->id)
    <div class="message-me">
@else

    <div class="message-you">
    <h5 class="media-heading">{{ $message->user->email }}:</h5>
@endif

        <p>{!! nl2br(e($message->body)) !!}</p>
        <div class="text"><small>Posted {{ $message->created_at->diffForHumans() }}</small></div>
    </div>
</div>
@endforeach

{!! Form::open(['route' => ['messages.update', Request::route()->account, $thread->id], 'method' => 'PUT']) !!}
    <!-- Modal -->
    <div class="modal fade" id="respondmodal" tabindex="-1" role="dialog" aria-labelledby="respondmodal">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Send a Response</h4>
          </div>
          <div class="modal-body">

            <!-- Message Form Input -->
            <div class="form-group">
                {!! Form::textarea('message', null, ['class' => 'form-control']) !!}
            </div>

            @if(UserHelper::GetAllUsers()->count() > 0)
            <div class="checkbox">
                @foreach($userstoadd as $user) 
                    <label title="{{ $user->email }}"><input type="checkbox" name="recipients[]" value="{{ $user->id }}">{{ $user->getShortName() }}</label>
                @endforeach
            </div>
            @endif
          </div>
          <div class="modal-footer">
            {!! Form::submit('Submit', ['class' => 'btn OS-Button form-control']) !!}
          </div>
        </div>
      </div>
    </div>
{!! Form::close() !!}


<script>
$(document).ready(function() {
        
    $('#backbutton').click(function(e) {
        GoToPage("/messages");
    });
    
    
@if($thread->isUnread(Auth::user()->id))    
    $( window ).on('beforeunload',function(){
        //alert("Goodbye!");
        return 'You have not responded to this message, would you like to respond befor you leave?';
    });
@endif
});
</script>

@stop