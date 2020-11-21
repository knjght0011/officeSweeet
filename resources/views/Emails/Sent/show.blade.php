@extends('master')

@section('content') 

<div id="header" class="row">
    <table class="table">
        <tr>
            <td>
                From:
            </td>
            <td>
                {{ $mail->sender }}
            </td>            
        </tr>
        <tr>
            <td>
                Subject:
            </td>
            <td>
                {{ $mail->subject }}
            </td>            
        </tr>
        <tr>
            <td>
                To: 
            </td>
            <td>
                {{ $mail->email }}
            </td>            
        </tr>
        <tr>
            <td>
                Date:
            </td>
            <td>
                {{ $mail->created_at }}
            </td>
        </tr>
        <tr>
            <td>
                Body:
            </td>
            <td>
                {!!$mail->body!!}
            </td>
        </tr>
    </table>
</div>
<button type="button" onclick="alert('this feature not available at the present')" class="btn btn-outline-primary btn-lg btn-block">Forward</button>

{{--<iframe id="bodyframe" style="width: 100%; min-height: 100%;" src="{{ url("/Mail/Body/" . $mail->id) }}"></iframe>--}}
<script>
    $windowheight = $('#content').height();
    $headerheight = $('#header').height();

    $('#bodyframe').height($windowheight - $headerheight -20);

</script>
@stop


