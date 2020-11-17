<div id="title"> 
    <div style="font-size: 18pt;">{{ $companyinfo['name'] }}</div>
    @if($companyaddress !== null)
        {{ $companyaddress->number }} {{ $companyaddress->address1 }}, {{ $companyaddress->city }} {{ $companyaddress->state }} {{ $companyaddress->zip }}<br>
        @if($companyaddress->phonenumber != "")
        Phone {{ $companyaddress->phonenumber }},
        @endif
        @if($companyaddress->faxnumber != "")
        Fax {{ $companyaddress->faxnumber }}
        @endif
    @endif
    <br>
    {{ $companyinfo['email'] }}<br><br>
    <div style="font-size: 18pt;">{{ $reportname }}</div>
    @if(isset($startdate))
        @if($startdate === "")
        for the period ending: {{ $enddate }}<br>
        @else
        for the period: {{ $startdate }} - {{ $enddate }}<br>
        @endif
    @endif
</div>