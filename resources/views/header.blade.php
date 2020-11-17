<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <meta name="description" content="">
    <meta name="Keywords" content="">
    <meta name="robots" content="all"/>

    <!--favicons-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ url('/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ url('/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ url('/favicon-16x16.png') }}" sizes="16x16">
    <link rel="manifest" href="{{ url('/manifest.json') }}">
    <link rel="mask-icon" href="{{ url('/safari-pinned-tab.svg') }}" color="#faff00">
    <meta name="theme-color" content="#faff00">

    <title>Office Sweeet - {{ $subdomain }}</title>

    <!--css-->
    <link rel="stylesheet" href="{{ url('/includes/bootstrap/css/bootstrap.min.css') }}">
<!--<link rel="stylesheet" href="{{ url('/includes/bootstrap-4.0/css/bootstrap.min.css') }}"> Bootstap 4.0 will require allot of rewrites -->
    <link rel="stylesheet" href="{{ url('/includes/custom.css') }}">
    <link rel="stylesheet" href="{{ url('/includes/jquery.confirm/jquery-confirm.css') }}">
    <link rel="stylesheet" href="{{ url('/includes/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ url('/includes/jquery-ui-timepicker-addon.min.css') }}">

    <script src="{{ url('/includes/jquery-3.1.0.min.js') }}"></script>
    <script src="{{ url('/includes/jquery-ui.js') }}"></script>
    <script src="{{ url('/includes/jquery-ui-timepicker-addon.min.js') }}"></script>
    <script src="{{ url('/includes/jquery.ui.touch-punch.min.js') }}"></script>
    <script src="{{ url('/includes/bootstrap/js/bootstrap.min.js') }}"></script>
<!--<script src="{{ url('/includes/bootstrap-4.0/js/bootstrap.min.js') }}"></script> Bootstap 4.0 will require allot of rewrites-->

    <link href="{{ url('/includes/BootstrapToggle/bootstrap-toggle.min.css') }}" rel="stylesheet">
    <script src="{{ url('/includes/BootstrapToggle/bootstrap-toggle.min.js') }}"></script>

    <link href="{{ url('/includes/clockpicker/bootstrap-clockpicker.min.css') }}" rel="stylesheet">
    <script src="{{ url('/includes/clockpicker/bootstrap-clockpicker.min.js') }}"></script>


    <!--datatables https://datatables.net/-->
    <script type="text/javascript" src="{{ url('/includes/datatables/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('/includes/datatables/dataTables.bootstrap.min.js') }}"></script>

    <!--ckeditor-->
<!--<script type="text/javascript" src="{{ url('/includes/ckeditor/ckeditor.js') }}"></script>-->
    <script type="text/javascript" src="{{ url('/includes/CKEditor5/ckeditor.js') }}"></script>

    <link rel='stylesheet' href="{{ url('/includes/fullcalendar-scheduler-1.9.4/lib/fullcalendar.min.css') }}"/>
    <link rel='stylesheet' media='print'
          href="{{ url('/includes/fullcalendar-scheduler-1.9.4/lib/fullcalendar.print.min.css') }}"/>
    <link rel='stylesheet' href="{{ url('/includes/fullcalendar-scheduler-1.9.4/scheduler.min.css') }}"/>

    <script type="text/javascript" src="{{ url('/includes/moment.js') }}"></script>
    <script type="text/javascript" src="{{ url('/includes/fullcalendar-scheduler-1.9.4/lib/fullcalendar.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('/includes/fullcalendar-scheduler-1.9.4/scheduler.js') }}"></script>
<!--<script type="text/javascript" src="{{ url('/includes/fullcalendar-scheduler-1.9.4/lib/jquery-ui.min.js') }}"></script>-->

<!--bootstrap-slider https://github.com/seiyria/bootstrap-slider
    <script type="text/javascript" src="{{ url('/includes/bootstrap-slider/bootstrap-slider.js') }}"></script>
    <script type="stylesheet" src="{{ url('/includes/bootstrap-slider/css/bootstrap-slider.css') }}"></script>-->


    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


    <!-- Test for form builder -->

    <link rel="shortcut icon" href="{{ url('/images/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ url('/images/apple-touch-icon.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ url('/images/apple-touch-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ url('/images/apple-touch-icon-114x114.png') }}">

    <!--jquery confirm https://myclabs.github.io/jquery.confirm/ -->
    <script type="text/javascript" src="{{ url('/includes/jquery.confirm/jquery-confirm.js') }}"></script>
    <script language="javascript" type="text/javascript" src="{{ url('/includes/xhtml.js') }}"></script>

    <!--<script language="javascript" type="text/javascript" src="{{ url('/includes/listjs/list.min.js') }}"></script>-->
    <script language="javascript" type="text/javascript" src="{{ url('/includes/listjs/list.js') }}"></script>


    <script src="https://browser.sentry-cdn.com/4.2.3/bundle.min.js"></script>
    @desktop
    <style>
        .large-custom-modal-dialog {
            margin: 2.5vh auto;
            width: 95vw
        }

        .large-custom-modal-content {
            height: 95vh;
            width: 95vw;
        }
        .inputdiv{
            width: 15em;
        }
    </style>
    @elsedesktop
    <style>
        .navbar-header {
            float: none;
        }

        .navbar-toggle {
            display: block;
        }

        .navbar-collapse.collapse {
            display: none !important;
        }

        .navbar-brand {
            display: block !important;
        }

        .navbar-nav {
            float: none !important;
        }

        .navbar-nav > li {
            float: none;
        }
        .navbar-collapse.collapse.in {
            display: block !important;
        }
        .close {
            height: 15px;
            width: 15px;
            font-size: large;
        }
        html * {
            font-size: x-small !important;
        }
        body {
            height: auto;
        }
        .inputdiv{
            font-size: xx-small;
        }
    </style>
    @enddesktop

</head>
