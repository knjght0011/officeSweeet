@extends('master')

@section('content')  
    
	<div class="form-group">
		<input id="searchinput" class="form-control" type="search" placeholder="Search...">
	</div>
	<div id="searchlist" class="list-group">            
            @foreach($address as $add)
            <div class="list-group-item row">

                    <div class="col-md-4" style="padding: 5px 5px 5px 5px;"><span>{{ $add->address1 }}</span> - {{ $add->address2 }} - {{ $add->city }} - {{ $add->state }} - {{ $add->zip }}</div>
                    <!--<div class="pull-right">
                        
                        <form name="" action="User_Management" method="post">
                        <input type="hidden" name="action" value="manage">    
                        <input type="hidden" name="id" value="">
                        <input class="btn btn-default" type="submit" value="Manage User">
                        </form>

                    </div>-->
                
            </div>
            @endforeach
	</div>

    
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="/includes/bootstrap-list-filter.src.js"></script>   
<script>
	$('#searchlist').btsListFilter('#searchinput', {itemChild: 'span'});
</script> 

@stop