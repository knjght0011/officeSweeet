@extends('master')

@section('content')

    <h3 style="margin-top: 10px;">Reporting</h3>

<div class="col-md-4">
    <legend>Options</legend>

    {!! Form::OSdatepicker("start-date", "Start Date", $startdate) !!}
    {!! Form::OSdatepicker("end-date", "End Date", $enddate) !!}
    {!! Form::OSselect("user", "User", FormatingHelper::UsersNamesArray(), "", 0, "false", "") !!}
    {!! Form::OSselect("date-selection", "Period Reports", ['Pick One' => 'Choose One', 'End of Day' => 'End of Day', 'Current YTD' => 'Current YTD', 'Current MTD' => 'Current MTD', 'Prior Month' => 'Last Month', 'Jan-Dec Last Year' => 'Jan-Dec Last Year'], "", 0, "false", "") !!}

</div>

<div class="col-md-4">
    <legend>PDF Reports</legend>
    <div class="panel-group" id="accordion">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a  data-toggle="collapse" data-parent="#accordion" href="#collapseTransactions-PDF">Transactions</a>
                </h4>
            </div>
            <div id="collapseTransactions-PDF" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="list-group">
                        <!--<a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="PaymentsAndAdjustments" data-option="all">Payments And Adjustments Report(P&A)</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="PaymentsAndAdjustments" data-option="onlycashandcheque">P&A(only cash and cheque)</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="PaymentsAndAdjustments" data-option="nocashandcheque">P&A(no cash and cheque)</a>-->
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="Invoices" data-option="all">All Invoices</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="Invoices" data-option="user">Invoices by Employee</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="CityTax" data-option="all">City Tax</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="SalesTax" data-option="all">Sales Tax</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="AccountTransactions" data-option="all">Account Transactions</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="CheckRegister" data-option="user">Check Register</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapsenpBusinessFinancials-PDF">Business Financials</a>
                </h4>
            </div>
            <div id="collapsenpBusinessFinancials-PDF" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="Ageing" data-option="all">Aging Receivables</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="ProfitLoss" data-option="all" >{{ TextHelper::GetText("Profit and Loss") }} Statement</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="ProfitLoss" data-option="graphs" >{{ TextHelper::GetText("Profit and Loss") }} Statement With Graphs</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="BalanceSheet" data-option="all" >Balance Sheet</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="GeneralLedger" data-option="all" >General Ledger</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="IncomeOverTimeGraph" data-option="user">Income Over Time Graph</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="AccountOverview" data-option="user">Account(Asset) Overview</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="AccountsDetailed" data-option="user">Accounts Detailed</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="AccountsYTD" data-option="user">Analysis of Revenue & Expenses</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="AccountsRestricted" data-option="user">Summary of Restricted Accounts</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseClientsVendors-PDF">{{ TextHelper::GetText("Clients") }}/Vendors</a>
                </h4>
            </div>
            <div id="collapseClientsVendors-PDF" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="ProspectList" data-option="all">Prospect List</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="ProspectActivityList" data-option="all">Prospect Activity List</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="ClientList" data-option="all">Client List</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="ClientActivityList" data-option="all">Client Activity List</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="VendorList" data-option="all">Vendor List</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="ExpensesByVendor" data-option="all">Expenses by Vendor</a>
                        <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="1099" data-option="all">1099</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseEmployeesContractors-PDF">Employees/Contractors</a>
            </h4>
            </div>
            <div id="collapseEmployeesContractors-PDF" class="panel-collapse collapse">
                <div class="panel-body">
                    <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="Timesheets" data-option="all">Timesheets</a>
                    <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="Timesheets" data-option="user">Timesheets for Employee</a>
                    <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="ProspectConversions" data-option="user">Prospect Conversions</a>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a  data-toggle="collapse" data-parent="#accordion" href="#collapseProducts-PDF">Product Reports</a>
                </h4>
            </div>
            <div id="collapseProducts-PDF" class="panel-collapse collapse">
                <div class="panel-body">
                    <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="Inventory" data-option="all">Inventory Report</a>
                    <a href="#" class="list-group-item" data-toggle="modal" data-target="#ShowPdfModel" data-report="InventoryRestock" data-option="all">Inventory Restock Report</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-4">
    <legend>Interactive Reports</legend>
    <div class="panel-group" id="accordion2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a  data-toggle="collapse" data-parent="#accordion" href="#collapseReports5">Product Reports</a>
                </h4>
            </div>
            <div id="collapseReports5" class="panel-collapse collapse">
                <div class="panel-body">

                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapsenpBusinessFinancials-INTERACTIVE">Business Financials</a>
                </h4>
            </div>
            <div id="collapsenpBusinessFinancials-INTERACTIVE" class="panel-collapse collapse">
                <div class="panel-body">
                    <div class="list-group">
                        <a href="/Reporting/Interactive/Quotes" class="list-group-item">Open Quotes</a>
                        <a href="/Reporting/Interactive/Invoices" class="list-group-item">Outstanding Invoices</a>
                        <a href="#" class="list-group-item interactivereport" data-report="ProfitAndLoss">Profit And Loss</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="ShowPdfModel" tabindex="-1" role="dialog" aria-labelledby="ShowPdfModel" aria-hidden="true">
    <div style="margin: 2.5vh auto; width: 95vw" class="modal-dialog" role="document">
        <div style="height: 95vh; width: 95vw;" class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="ShowPdfModel">View Report</h4>
            </div>
            <div style="height: calc(95vh - 120px);" class="modal-body">
                <iframe style="width: 100%; height: 100%;"id="ShowPdfFrame" src="{{ url('images/loading4.gif') }}"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<script>
$('#ShowPdfModel').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var $report = button.data('report'); // Extract info from data-* attributes
    var $option = button.data('option'); // Extract info from data-* attributes

    var $start = $('#start-date').val();
    var $end = $('#end-date').val();
    
    if ($option === "user"){
        $option = $('#user').val();
    }
    
    
    ///Report/{reporttype}/{date}/{timeframe}/{option}/{output}
    var url = "/Reporting/Report/" + $report + "/" + $start + "/" + $end + "/" + $option + "/pdf";
    
    console.log(url);
    
    $('#ShowPdfFrame').attr("src", url);  
}); 

$('#ShowPdfModel').on('hide.bs.modal', function (event) {
    $('#ShowPdfFrame').attr("src", "{{ url('images/loading4.gif') }}");
});

$('#date-selection').change(function(){

    switch($(this).val()) {
        case "End of Day":
            $('#start-date').val("{{ \Carbon\Carbon::now()->addDay(-1)->format('Y-m-d') }}");
            $('#end-date').val("{{ \Carbon\Carbon::now()->addDay(1)->format('Y-m-d') }}");
            break;
        case "Current YTD":
            $('#start-date').val("{{ \Carbon\Carbon::now()->month(1)->firstOfMonth()->format('Y-m-d') }}");
            $('#end-date').val("{{ \Carbon\Carbon::now()->addDay(1)->format('Y-m-d') }}");
            break;
        case 'Current MTD':
            $('#start-date').val("{{ \Carbon\Carbon::now()->firstOfMonth()->format('Y-m-d') }}");
            $('#end-date').val("{{ \Carbon\Carbon::now()->addDay(1)->format('Y-m-d') }}");
            break;
        case 'Prior Month':
            $('#start-date').val("{{ \Carbon\Carbon::now()->addMonth(-1)->firstOfMonth()->format('Y-m-d') }}");
            $('#end-date').val("{{ \Carbon\Carbon::now()->addMonth(-1)->lastOfMonth()->format('Y-m-d') }}");
            break;
        case 'Jan-Dec Last Year':
            $('#start-date').val("{{ \Carbon\Carbon::now()->addYear(-1)->month(1)->firstOfMonth()->format('Y-m-d') }}");
            $('#end-date').val("{{ \Carbon\Carbon::now()->addYear(-1)->month(12)->lastOfMonth()->format('Y-m-d') }}");
            break;

    }
});

$('.interactivereport').click(function(event){

    var $report = $(this).data('report');
    var $start = $('#start-date').val();
    var $end = $('#end-date').val();

    $url = "/Reporting/Interactive/" + $report + "/" + $start + "/" + $end

    GoToPage($url);
});
</script>
@stop