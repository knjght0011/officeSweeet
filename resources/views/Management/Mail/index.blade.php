@extends('master')

@section('content') 

<style>
.dataTables_filter {
    display: none; 
}
.dataTables_length {
    display: none; 
}
.dataTables_info {
    display: none; 
}

</style>

<legend>Email for:{{ $email }}</legend>

<div class="row" style="margin-top: 20px;">
    
    <div style="float:left; width: 18em;  margin-left: 20px;">
        <button style="width: 100%;" id="send" name="send" type="button" class="btn OS-Button">Send Mail</button>
    </div>
    
    <div style="float:left; width: 18em;  margin-left: 20px;">
        <div class="input-group ">   
            <span class="input-group-addon" for="search"><div style="width: 7em;">Search:</div></span>
            <input id="search" name="search" type="text" placeholder="" value="" class="form-control" data-validation-label="Search" data-validation-required="false" data-validation-type="">
        </div>
    </div>
    

   
    <!--
    <div style="float:left; width: 18em; margin-left: 20px;">
        <div class="input-group ">   
            <span class="input-group-addon" for="status"><div style="width: 7em;">Status:</div></span>
            <select id="status" name="status" type="text" placeholder="choice" class="form-control">
                <option value="all">All</option>
                <option value="Active" selected>Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
    </div> 
     -->
    <div style="float:left; width: 18em; margin-left: 20px;">
        <div class="input-group ">   
            <span class="input-group-addon" for="length"><div style="width: 7em;">Show:</div></span>
            <select id="length" name="length" type="text" placeholder="choice" class="form-control">
                <option value="10">10 entries</option>
                <option value="25">25 entries</option>
                <option value="50">50 entries</option>
                <option value="100">100 entries</option>
            </select>
        </div>
    </div>
</div>

<table class="table" id="email-search">
    <thead>
        <tr>
            <td>Subject</td>
            <td>Sender</td>
            <td>To</td>
            <td>Date</td>
        </tr>
    </thead>
    <tbody>
        @foreach($mails as $mail)
        
            @if($mail->isReadByMe() === false)
                <tr style="font-weight: bold;">
            @else
                <tr>
            @endif
            
            <td><a href="/Mail/{{ $mail->id }}">{{ $mail->subject }}</a></td>
            <td>{{ $mail->sender }}</td>
            <td>{{ $mail->getToAsString() }}</td>
            <td>{{ $mail->date }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


<script>
$(document).ready(function() {
    
    $("#send").click(function()
    {
        GoToPage("/Mail/Send");
    });    
    
    $('#search').on( 'keyup change', function () {
        
        table.search( this.value ).draw();

        //UpdatePageinate(maintable.page.info());
       
        
    });
    
    $('#length').on( 'change', function () {
        
        table.page.len( this.value ).draw();

        //UpdatePageinate(maintable.page.info());
    });
    
    var table = $('#email-search').DataTable({
        "order": [[ 3, "desc" ]]
    });
    
    $('#email-search tbody').on( 'click', 'tr', function () {
        $row = $(this);
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });
});
</script>
@stop