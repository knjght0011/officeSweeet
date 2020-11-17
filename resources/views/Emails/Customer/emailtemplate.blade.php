<!--fonts-->
<link href="https://fonts.googleapis.com/css?family=Alegreya|Alegreya+Sans|BioRhyme|Black+Ops+One|Bungee|Bungee+Shade|Cabin|Calligraffitti|Charmonman|Creepster|Dancing+Script|Ewert|Fredericka+the+Great|Fruktur|Gravitas+One|Homemade+Apple|IBM+Plex+Mono:400,400i|IBM+Plex+Sans+Condensed:400,400i|IBM+Plex+Sans:100,100i,400,400i,700,700i|IBM+Plex+Serif:400,400i|Inconsolata|Indie+Flower|Italianno|Loved+by+the+King|Merriweather|Merriweather+Sans|Monoton|Nanum+Brush+Script|Nanum+Pen+Script|Nunito|Nunito+Sans|Pacifico|Quattrocento|Quattrocento+Sans|Quicksand|Roboto|Roboto+Mono|Roboto+Slab|Rubik:400,900|Satisfy|Ubuntu|VT323" rel="stylesheet">
{{--
@php
$fontarray = array(
'Alegreya Sans' => 'alegreya-sans',
'Alegreya' => 'alegreya',
'Ariel' => 'ariel',
'BioRhyme' => 'bioRhyme',
'Black Ops One' => 'black-ops-one',
'Bungee Shade' => 'bungee-shade',
'Bungee' => 'bungee',
'Cabin' => 'cabin',
'Calligraffitti' => 'calligraffitti',
'Charmonman' => 'charmonman',
'Courier New' => 'courier-new',
'Creepster' => 'creepster',
'Dancing Script' => 'dancing-script',
'Ewert' => 'ewert',
'Fredericka the Great' => 'fredericka-the-great',
'Fruktur' => 'fruktur',
'Georgia' => 'georgia',
'Gravitas One' => 'gravitas-one',
'Homemade Apple' => 'homemade-apple',
'IBM Plex Mono' => 'ibm-plex-mono',
'IBM Plex Sans Condensed' => 'ibm-plex-sans-condensed',
'IBM Plex Sans' => 'ibm-plex-sans',
'IBM Plex Serif' => 'ibm-plex-serif',
'Inconsolata' => 'inconsolata',
'Indie Flower' => 'indie-flower',
'Italianno' => 'italianno',
'Loved by the King' => 'loved-by-the-king',
'Lucida Sans Unicode' => 'lucida-sans-unicode',
'Merriweather Sans' => 'merriweather-sans',
'Merriweather' => 'merriweather',
'Monoton' => 'monoton',
'Nanum Brush Script' => 'nanum-brush-script',
'Nanum Pen Script' => 'nanum-pen-script',
'Nunito Sans' => 'nunito-sans',
'Nunito' => 'nunito',
'Pacifico' => 'pacifico',
'Quattrocento Sans' => 'quattrocento-sans',
'Quattrocento' => 'quattrocento',
'Quicksand' => 'quicksand',
'Roboto Mono' => 'roboto-mono',
'Roboto Slab' => 'roboto-slab',
'Roboto' => 'roboto',
'Rubik' => 'rubik',
'Satisfy' => 'satisfy',
'Tahoma' => 'tahoma',
'Times New Roman' => 'times-new-roman',
'Trebuchet MS' => 'trebuchet-ms',
'Ubuntu' => 'vbuntu',
'Verdana' => 'verdana',
'VT323' => 'vt323',
);
@endphp

<style>
    @foreach($fontarray as $key => $value)

    .ql-font-{{ $value }} {
        font-family: '{{ $key }}';
    }

    @endforeach
</style>
--}}
{!! $content !!}