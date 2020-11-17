@include('header')
<body style="height: auto;">

{{--
style="background-image: url('{{ url('/images/signupbackground2.jpg') }}');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: 105% ;
        height: auto;"
        --}}

<style>
    .step{
        background: white;
        margin-top: 50px;
        border-radius: 20px;
        padding: 25px;
        padding-top: 5px;
    }
    .nextbutton{
        position: relative;
        bottom: 0px;
        float: right;
    }

    .backbutton{
        position: relative;
        bottom: 0px;
        float: left;
    }
</style>

@yield('content')

<div class="modalload"><!-- Place at bottom of page --></div>

@include('footer')