@include('OS.Public.header')
<style>
    body{
        padding-left: 10%;
        padding-right: 10%;
        padding-top: 50px;
        padding-bottom: 50px;
        height: 100vh;
    }
</style>

<iframe style="width: 100%; height: 100%;" id="PdfFrame" src="/Public/Email/PDF/{{ $token }}"></iframe>

<script>
$(document).ready(function() {

});
</script>

@include('OS.Public.footer')