@include('pdf.Checks.header')

<body>
<style>
    * {
      font-family: Courier;
    }
</style>
@yield('content')

@include('pdf.Checks.footer')
