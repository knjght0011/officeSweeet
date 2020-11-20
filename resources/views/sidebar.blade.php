@desktop
@if(app()->make('account')->plan_name === "SOLO")
<div id="logo-background"><a href="/Home"><img width="100%" src="/images/oslogosolo.png"></a></div>
@else
<div id="logo-background"><a href="/Home"><img width="100%" src="{{ TextHelper::GetLogo() }}"></a></div>
@endif
@enddesktop
<style>
    .navbar-default .navbar-nav>li>a:focus { color: #DD0000; }
    .navbar-default .navbar-brand:focus { color: #DD0000; }
</style>
<div class="sidebar-nav">
    <div class="navbar navbar-default" role="navigation" style="background-color: #eee; border: none; font-weight: bold; margin-bottom: 10px;">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mastersidebarmenu">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <button type="button" class="navbar-toggle" data-toggle="modal" data-target="#ShowHelpHub" data-tab="action" style="background-image: url('../../images/tempbutton.png'); background-size: cover; height: 34px; width: 34px;">
            </button>
            <span class="visible-xs navbar-brand">Sidebar menu</span>
        </div>
        <div class="navbar-collapse collapse sidebar-navbar-collapse" id="mastersidebarmenu">
            <ul class="nav navbar-nav">
                @if(app()->make('account')->IsActive())
                    @if(app()->make('account')->subdomain === "livedemo")
                        <li><a style="color: red; text-decoration: underline;" id="link" target="_blank" href="https://www.officesweeet.com/free-trial">Click here to try out your own system - Absolutely FREE!</a></li>
                    @endif
                    @if(app()->make('account')->plan_name === "SOLO")
                        <li><a style="color: red; text-decoration: underline;" id="UPGRADE" target="_blank" href="/Subscription/Signup">UPGRADE!</a></li>
                    @endif

                    <li><a id="Home" href="/Home"><span class="glyphicon glyphicon-home"></span> Contacts/CRM </a></li>

                    @if(Auth::user()->hasPermission('patient_permission'))
                        <li><a id="Patients" href="/Patients/View"><span class="glyphicon glyphicon-heart"></span> Patients</a></li>
                    @endif

                    {{--Email--}}
                        <li id="Email" style="cursor: pointer;"><a><span class="glyphicon glyphicon-envelope"></span> Email <span id="unread" class="label label-danger">{{ \App\Helpers\MailHelper::unReadMail() }}</span><span style="padding-left: 5px" class="glyphicon glyphicon-chevron-down"></span></a></li>
                        <li style="display: none;padding-left: 25px" id="Email-Inbox"><a href="/Email/Inbox"><span class="glyphicon glyphicon-inbox"></span> Inbox <span id="unread" class="label label-danger">{{ \App\Helpers\MailHelper::unReadInboxMail() }}</span></a></li>
                        <li style="display: none;padding-left: 25px" id="Email-Sent"><a href="/Email/Sent"><span class="glyphicon glyphicon-send"></span> Sent <span id="unread" class="label label-danger">{{ \App\Helpers\MailHelper::unReadSentMail() }}</span></a></li>
                    @if(Auth::user()->hasPermission('scheduler_permission'))
                    <li><a id="Scheduling" href="/Scheduling/View"><span class="glyphicon glyphicon-calendar"></span> Scheduler/Calendar</a></li>
                    @endif

                    @if(Auth::user()->hasPermission('tasks_permission'))
                    @desktop
                    <li><a href="#" data-toggle="modal" data-target="#ViewTasksModal" data-tab="support" ><span class="glyphicon glyphicon-tasks"></span> Task Manager <span id="unread" class="label label-danger">{{ TaskListHelper::IncompleteTasks() }}</span></a></li>
                    @elsedesktop
                    <li><a id="link" href="/Tasklist"><span class="glyphicon glyphicon-tasks"></span>Task Manager <span id="unread" class="label label-danger">{{ TaskListHelper::IncompleteTasks() }}</span></a></li>
                    @enddesktop
                    @endif

                    @if(Auth::user()->hasPermission('journal_permission'))
                    <li><a id="Journal" href="/Journal/View"><span class="glyphicon glyphicon-book"></span> Accounting/Journal</a></li>
                    @endif

                    <li><a id="AssetLiability" href="/AssetLiability" ><span class="glyphicon glyphicon-th"></span> Products/Inventory</a> </li>

                    @if(Auth::user()->hasPermission('reporting_permission'))
                    <li><a id="Reporting" href="/Reporting"><span class="glyphicon glyphicon-list-alt"></span> Reporting</a></li>
                    @endif

                    @if(Auth::user()->hasPermission('payroll_permission'))
                    <li><a id="Payroll" href="/Payroll"><span class="glyphicon glyphicon-usd"></span> Payroll</a></li>
                    @endif

                    @if(Auth::user()->hasPermission('templates_permission'))
                    <li><a id="Templates" href="/Templates/List"><span class="glyphicon glyphicon-edit"></span> Templates</a></li>
                    @endif

                    @if(Auth::user()->hasPermission('acp_permission'))
                        @desktop
                    <li><a id="ACP" href="/ACP"><span class="glyphicon glyphicon-cog"></span> Admin Control Panel </a></li>
                        @enddesktop
                    @endif

                    @if(Auth::user()->hasPermission('acp_subscription_permission'))
                        <li><a id="link" href="/Subscription"><span class=""></span> My Subscription </a></li>
                    @endif

                    <li><a id="Account" href="/Account"><span class="glyphicon glyphicon-user"></span> {{ Auth::user()->getShortName() }} </a></li>

                    <li><a href="#" id="support" data-toggle="modal" data-target="#ShowHelpHub" data-tab="support" ><span class="glyphicon glyphicon-envelope"></span> Support / Feedback</a></li>
                    <li><a href="#" id="video" data-toggle="modal" data-target="#ShowHelpHub" data-tab="video" ><span class="glyphicon glyphicon-film"></span> Video Tutorials</a></li>
                    @if(app()->make('account')->subdomain === "local" || app()->make('account')->subdomain === "lls")
                        <li role="separator" class="divider"></li>

                        <li><a id="link" href="/Plans/"><span class="glyphicon glyphicon-th-list"></span> Plans</a></li>

                        <li><a id="link" href="/Alerts/"><span class="glyphicon glyphicon-alert"></span> Alerts</a></li>

                        <li><a id="link" href="/Accounts/"><span class="glyphicon glyphicon-user"></span> Accounts</a></li>
                    @endif
                        <li><a href="/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
                @else

                    @if(Auth::user()->hasPermission('acp_subscription_permission'))
                        <li><a id="Subscription" href="/Subscription"><span class=""></span> My Subscription </a></li>
                    @endif

                    <li><a href="#" data-toggle="modal" data-target="#ShowHelpHub" data-tab="support" ><span class="glyphicon glyphicon-envelope"></span> Support</a></li>

                    <li><a href="/logout"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>

                @endif
           </ul>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        //email dropdown menu
            var dropdown = document.getElementById("Email");
            var emailInbox = document.getElementById("Email-Inbox");
            var emailSent = document.getElementById("Email-Sent");
            dropdown.addEventListener("click", function() {
                if (emailInbox.style.display === "block") {
                    emailInbox.style.display = "none";
                    emailSent.style.display = "none";
                } else {
                    emailInbox.style.display = "block";
                    emailSent.style.display = "block";
                }
            });

        var url = window.location;
        $('ul.nav a[href="'+ url +'"]').parent().addClass('active');
        $('ul.nav a').filter(function() {return this.href == url;}).parent().addClass('active');
    });
</script>