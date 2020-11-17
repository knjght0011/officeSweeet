@include('header')
<body style="background-color: white;">

@include('Support.helphub')

@desktop
<div id="wrapper" style="height: 100%;">

    <div id="sidebar" style="background-color: #EEE;">
        @include('sidebar')
    </div>

    <div id="content" class="content">
        @include('calculator')
        @yield('content')
    </div>

</div>
@elsedesktop
@if(Session::exists('app'))
    @if(Session::get('app') === 'yes')


    @else
        <div style="width: 100%; padding: 0px 10px 0px 10px;">
            @include('sidebar')
        </div>
    @endif
@else
<div style="width: 100%; padding: 0px 10px 0px 10px;">
    @include('sidebar')
</div>
@endif
<div style="width: 100%; padding: 0px 10px 0px 10px;">
    @yield('content')
</div>

@include('calculator')

@enddesktop


@include('javafunctions')

@desktop
@include('OS.TaskList.ViewTasksModel')
@enddesktop

@include('OS.Modals.livedemoinfo')

@include('Modals.addmiscdeposit')


@include('OS.Modals.SplitAmountModel')


@if(SettingHelper::GetSetting('gmail-per-user') != null || SettingHelper::GetSetting('gmail-system') != null)
    @include('OS.Modals.sendgmail')
@else
    @if(SettingHelper::GetSetting('email-dont-show-gmail-popup') != null)

    @else
        @include('OS.Modals.usegmail')
    @endif
@endif

<div class="modalload"><!-- Place at bottom of page --></div>

@php
    $value = Session::pull('helphub');
@endphp
@if($value[0] === "video")
    <!--here-->
    <script>

        $(document).ready(function() {
            $('#ShowHelpHub').modal('show');
            $('#videoclick').click();
        });
    </script>
@endif

@desktop

@include('OS.Chat.chat')

@enddesktop

<script>
    Sentry.init({ dsn: 'https://24467537b1fc4445bd8f8d1596052458@sentry.io/286040' });
</script>

@include('footer')