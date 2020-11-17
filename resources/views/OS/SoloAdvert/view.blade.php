@extends('OS.SoloAdvert.master')

@section('content')

        <div style="width: calc(100% - 22px); height: calc(100% - 100px); margin: 10px; border: solid; border-radius: 5px; border-width: 1px;">
            @desktop
            <img src="{{ url('/images/Adverts/Advert1.PNG') }}" alt="Advert 1" width="auto" height="100%" style="display: block; margin-left: auto; margin-right: auto;">
            @elsedesktop
            <img src="{{ url('/images/Adverts/Advert1.PNG') }}" alt="Advert 1" width="auto" height="auto" style="max-width: 100%; max-height: 100%; display: block; margin-left: auto; margin-right: auto;">
            @enddesktop
        </div>

        <div style="width: 100%; text-align: center;">
            <button style="background-color: white; border: solid; border-color: blue; padding: 5px; border-radius: 5px; border-width: 2px; font-size: x-large; margin-right: 5px;" class="btn" id="plans" ><b>Plan Options</b></button>
            <button style="background-color: white; color: darkslategray; border: solid; border-color: black; padding: 5px; border-radius: 5px; border-width: 2px; font-size: large; margin-left: 5px;" class="btn" id="go" >No, Thanks</button>
        </div>

    <script>
        $(document).ready(function() {
            $('#plans').click(function () {
                var link = document.createElement('a');
                link.href = "/Subscription/Signup";
                link.id = "link";
                document.body.appendChild(link);
                link.click();
            });

            $('#go').click(function () {
                var link = document.createElement('a');
                link.href = "{{ $url }}";
                link.id = "link";
                document.body.appendChild(link);
                link.click();
            });

        });

    </script>
@stop