

<div class="col-xs-2" style="height: 100%;"> <!-- required for floating -->
    <!-- Nav tabs -->
    <ul class="nav nav-tabs tabs-left" style="height: 100%;">
        <li class="active"><a href="#user-settings" data-toggle="tab">User Settings</a></li>
        <li><a href="#user-password" data-toggle="tab">Change Password</a></li>
        <li><a href="#user-create" data-toggle="tab">Create User</a></li>
    </ul>
</div>

<div class="col-xs-10" style="height: 100%;">
    <!-- Tab panes -->
    <div class="tab-content" style="height: 100%;">
        <div class="tab-pane active" id="user-settings" style="height: 100%;">@include('ACP.Tabs.Users.manage')</div>
        <div class="tab-pane" id="user-password">@include('ACP.Tabs.Users.password')</div>
        <div class="tab-pane" id="user-create">@include('ACP.Tabs.Users.create')</div>
    </div>
</div> 