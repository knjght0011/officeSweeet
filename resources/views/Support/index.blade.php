@extends('master')

@section('content')
    <ul class="nav nav-tabs">
        <li class="active">
            <a id="videoclick-page" href="#video-page" data-toggle="tab" data-target="#video-page">Video Tutorials</a>
        </li>
        <li>
            <a id="wikiclick-page" href="#wiki-page" data-toggle="tab" data-target="#wiki-page">Wiki</a>
        </li>
        <li>
            <a id="supportclick-page" href="#support-page" data-toggle="tab"  data-target="#support-page">Contact Support</a>
        </li>
        <!--
        <li>
            <a id="actionclick-page" href="#action-page" data-toggle="tab"  data-target="#action-page">Actions</a>
        </li>
        -->
    </ul>
    <div class="tab-content" style="height: 100%">

        <div class="tab-pane active" id="video-page" style="height: 100%">
            @include('Support.Tabs.videotab')
        </div>

        <div class="tab-pane" id="wiki-page" style="height: 100%">
            @include('Support.Tabs.wikitab')
        </div>

        <div class="tab-pane" id="support-page">
            @include('Support.Tabs.support')
        </div>

        {{--
        <div class="tab-pane" id="action-page">
            @include('Support.Tabs.action')
        </div>
        --}}
    </div>
@stop
