@extends('pdf.Reports.master')

@section('content')
    <link href="https://fonts.googleapis.com/css?family=Alegreya|Alegreya+Sans|BioRhyme|Black+Ops+One|Bungee|Bungee+Shade|Cabin|Calligraffitti|Charmonman|Creepster|Dancing+Script|Ewert|Fredericka+the+Great|Fruktur|Gravitas+One|Homemade+Apple|IBM+Plex+Mono:400,400i|IBM+Plex+Sans+Condensed:400,400i|IBM+Plex+Sans:100,100i,400,400i,700,700i|IBM+Plex+Serif:400,400i|Inconsolata|Indie+Flower|Italianno|Loved+by+the+King|Merriweather|Merriweather+Sans|Monoton|Nanum+Brush+Script|Nanum+Pen+Script|Nunito|Nunito+Sans|Pacifico|Quattrocento|Quattrocento+Sans|Quicksand|Roboto|Roboto+Mono|Roboto+Slab|Rubik:400,900|Satisfy|Ubuntu|VT323" rel="stylesheet">
    <style>
        body{
            /* Set the dimensions of the "page". */
            width: 15.8cm;
            min-height: 21cm;

            /* Keep the "page" off the boundaries of the container. */
            padding: 1cm 2cm 2cm;

            border: 1px hsl( 0,0%,82.7% ) solid;
            background: white;

            /* The "page" should cast a slight shadow (3D illusion). */
            box-shadow: 0 0 5px hsla( 0,0%,0%,.1 );

            /* Center the "page". */
            margin: 0 auto;
        }
        h1{
            font-size: 36px;
        }
        p{
            font-size: 16px;
            line-height: 1.63em;
            padding-top: .5em;
            margin-top: 1.5em;
            margin-bottom: 1.5em;
        }

        .image-style-align-left {
            max-width: 50%;
            float: left;
        }
        .image-style-align-right {
            max-width: 50%;
            float: right;
        }
    </style>

    {!! $data !!}

@stop

