@extends('master')

@section('content')   
 
 
 <table class="table">
    <tbody>
        <tr>
            <td>
                ID
            </td>
            <td>
                {{ $log->id }}
            </td>
        </tr>    
        <tr>
            <td>
                instance
            </td>
            <td>
                {{ $log->instance }}
            </td>
        </tr>           
        <tr>
            <td>
                channel
            </td>
            <td>
                {{ $log->channel }}
            </td>
        </tr>   
        <tr>
            <td>
                level
            </td>
            <td>
                {{ $log->level }}
            </td>
        </tr>   
        <tr>
            <td>
               	level_name
            </td>
            <td>
                {{ $log->level_name }}
            </td>
        </tr>      
        <tr>
            <td>
               	message
            </td>
            <td>
                {!! nl2br(e($log->message)) !!}
            </td>
        </tr>    
        <tr>
            <td>
               	exception
            </td>
            <td>
                {{ $log->context['exception']['xdebug_message'] }}
            </td>
        </tr>
        <tr>
            <td>
               	remote_addr
            </td>
            <td>
                {{ $log->remote_addr }}
            </td>
        </tr>
        <tr>
            <td>
               	user_agent
            </td>
            <td>
                {{ $log->user_agent }}
            </td>
        </tr>        
        <tr>
            <td>
               	created_by
            </td>
            <td>
                @if($log->getUser() !== null)
                {{ $log->getUser()->email }}
                @else
                {{ $log->created_by }}
                @endif
            </td>
        </tr>    
        <tr>
            <td>
               	created_at
            </td>
            <td>
                {{ $log->created_at }}
            </td>
        </tr>        
    </tbody>
 </table>

@stop