@include('header')

<body style="background-color: #eee;">
<div id="wrapper" style="height: 100%;">
    <div id="sidebar">
        @include('Management.sidebar')
    </div>
    <div id="content" class="content" style="min-height: 100%;">
        @yield('content') 
    </div>
</div>
    
    @include('javafunctions')

    <div class="modalload"><!-- Place at bottom of page --></div>  
@include('footer')
