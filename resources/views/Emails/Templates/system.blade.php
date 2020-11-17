@include('Emails.Templates.header')
<body style="background-image: url('http://overlords.officesweeet.com/images/banner.jpg'); background-size:cover;">
    <div id="container" style="margin:0 auto;
             font-family:Arial, Helvetica, sans-serif;
             font-size:12px;
             color:#000;
             border: solid 3px #696969;
             box-shadow: 3px 3px 15px rgba(0, 0, 0, 0.21);
             width: 600px;
             background:white; ">
        <div id="logo" style="width: calc(100% - 30px);
            height: 100px;
            color:#2d2d2a;
            font-size:22px;
            padding:5px 0 0px 15px;
            background:white;
            text-align:center;
            border-bottom: solid 3px #696969;
            padding-left: 15px;
            padding-right: 15px;
            padding-bottom: 0px;
            padding-top: 0px;">
             <img src="http://overlords.officesweeet.com/images/oslogo.png" style="height: 100%;">
        </div>
        <div id="content" style="width: calc(100% - 30px);
            padding: 15px;">
        @yield('content')
        </div>
    </div>
</body>
@include('Emails.Templates.footer')
