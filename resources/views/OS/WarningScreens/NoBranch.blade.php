@extends('master')

@section('content')

    <div class="container" style="text-align: center;">
        <h2>You currently have not setup any branches within your OfficeSweeet system, this is required to create Quotes and Invoices.</h2>

        @if(Auth::user()->hasPermission('acp_company_info_permission'))
        <h2>Please click <a href="/ACP/CompanyInfo/companyinfo-branch">HERE</a> to add a new branch.</h2>
        @else
        <h2>Please ask a system adminstrator to set this up.</h2>
        @endif

    </div>

@stop
