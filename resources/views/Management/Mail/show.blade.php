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
                {{ $mail->getrecipientAsString() }}
            </td>            
        </tr>
        <tr>
            <td>
                Date:
            </td>
            <td>
                {{ $mail->date }}
            </td>
        </tr>
    </table>
</div>

<iframe id="bodyframe" style="width: 100%; min-height: 100%;" src="{{ url("/Mail/Body/" . $mail->id) }}"></iframe>
<script>
    $windowheight = $('#content').height();
    $headerheight = $('#header').height();

    $('#bodyframe').height($windowheight - $headerheight -20);

</script>
@stop


