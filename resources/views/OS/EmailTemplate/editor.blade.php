@extends('master')

@section('content')
    <!-- Main Quill library -->
    <script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script src="/includes/image-resize.min.js"></script>
    <script src="/includes/image-drop.min.js"></script>

    <!-- Theme included stylesheets -->
    <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="//cdn.quilljs.com/1.3.6/quill.bubble.css" rel="stylesheet">

    <!--fonts-->
    <link href="https://fonts.googleapis.com/css?family=Alegreya|Alegreya+Sans|BioRhyme|Black+Ops+One|Bungee|Bungee+Shade|Cabin|Calligraffitti|Charmonman|Creepster|Dancing+Script|Ewert|Fredericka+the+Great|Fruktur|Gravitas+One|Homemade+Apple|IBM+Plex+Mono:400,400i|IBM+Plex+Sans+Condensed:400,400i|IBM+Plex+Sans:100,100i,400,400i,700,700i|IBM+Plex+Serif:400,400i|Inconsolata|Indie+Flower|Italianno|Loved+by+the+King|Merriweather|Merriweather+Sans|Monoton|Nanum+Brush+Script|Nanum+Pen+Script|Nunito|Nunito+Sans|Pacifico|Quattrocento|Quattrocento+Sans|Quicksand|Roboto|Roboto+Mono|Roboto+Slab|Rubik:400,900|Satisfy|Ubuntu|VT323" rel="stylesheet">
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
        .ql-snow .ql-picker.ql-font .ql-picker-label[data-value='{{ $value }}']::before,
        .ql-snow .ql-picker.ql-font .ql-picker-item[data-value='{{ $value }}']::before
        {
            content: '{{ $key }}';
            font-family: '{{ $key }}';
        }

        .ql-font-{{ $value }} {
            font-family: '{{ $key }}';
        }
        @endforeach
    </style>




    <div class="row">
        <div class="col-md-6">
            <div class="input-group ">
                <span class="input-group-addon" for="template-name"><div style="width: 7em;">Name:</div></span>
                <input id="template-name" name="template-name" type="text" class="form-control">
            </div>
        </div>
        <div class="col-md-3">
            <button class="btn OS-Button" id="save-button" style="width: 100%;">Save</button>
        </div>
    </div>


    <div id="toolbar-container" class="filter-wrapper f-header">
        <div class="nav-bar-filter left">
            <span class="ql-formats" group="1">
                <select class="ql-header"></select>
                <select class="ql-font" style="width: 20em;">
                    @foreach($fontarray as $key => $value)
                        <option value="{{ $value }}">{{ $key }}</option>
                    @endforeach
                </select>
                <select class="ql-size"></select>
            </span>
            <span class="ql-formats" group="2">
                <button class="ql-bold"></button>
                <button class="ql-italic"></button>
                <button class="ql-underline"></button>
                <button class="ql-strike"></button>
            </span>
            <span class="ql-formats" group="3">
                <select class="ql-color"></select>
                <select class="ql-background"></select>
            </span>
            <span class="ql-formats" group="4">
                <button class="ql-script" value="sub"></button>
                <button class="ql-script" value="super"></button>
            </span>
            <span class="ql-formats" group="5">
                <button class="ql-blockquote"></button>
                <button class="ql-code-block"></button>
            </span>
            <span class="ql-formats" group="6">
                <button class="ql-list" value="ordered"></button>
                <button class="ql-list" value="bullet"></button>
                <select class="ql-align"></select>
            </span>
            <span class="ql-formats" group="7">
                <button class="ql-link"></button>
                <button class="ql-image"></button>
                <button class="ql-formula"></button>
            </span>
            <span class="ql-formats" group="8">
                <button class="ql-indent" value="-1"></button>
                <button class="ql-indent" value="+1"></button>
                <button class="ql-direction" value="rtl"></button>
                <button class="ql-clean"></button>
            </span>
        </div>
    </div>


    <div id="quill" style="height: calc(100% - 150px);">

    </div>

    <input id="template-id" value="0" style="display: none;">

    <script>
        $(document).ready(function () {

            // Add fonts to whitelist
            var Font = window.Quill.import('formats/font');
            // We do not add Aref Ruqaa since it is the default
            Font.whitelist = [
                @foreach($fontarray as $key => $value)
                    '{{ $value }}',
                @endforeach
                ];
            window.Quill.register(Font, true);

            var toolbarOptions = [
                ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                ['blockquote', 'code-block'],

                [{'header': 1}, {'header': 2}],               // custom button values
                [{'list': 'ordered'}, {'list': 'bullet'}],
                [{'script': 'sub'}, {'script': 'super'}],      // superscript/subscript
                [{'indent': '-1'}, {'indent': '+1'}],          // outdent/indent
                [{'direction': 'rtl'}],                         // text direction

                [{'size': ['small', false, 'large', 'huge']}],  // custom dropdown
                [{'header': [1, 2, 3, 4, 5, 6, false]}],

                [{'color': []}, {'background': []}],          // dropdown with defaults from theme
                [{'font': ['', 'times-new-roman', 'arial']}],
                [{'align': []}],

                ['clean']                                         // remove formatting button
            ];

            var options = {
                modules: {
                    toolbar: '#toolbar-container',
                    imageResize: {
                        modules: ['Resize', 'DisplaySize', 'Toolbar']
                    },
                    imageDrop: true,
                },
                placeholder: 'Compose an epic...',
                theme: 'snow'
            };

            window.templateeditor = new Quill('#quill', options);

            $('#save-button').click(function () {
                //console.log(editor.root.innerHTML);
                if ($('#template-name').val() === "") {
                    $.dialog({
                        title: 'Oops...',
                        content: 'Please enter a name.'
                    });
                } else {
                    Save($('#template-id').val(), $('#template-name').val(), window.templateeditor.root.innerHTML);
                }
            });
        });

        function Save($id, $name, $content) {

            $data = {};
            $data['_token'] = "{{ csrf_token() }}";
            $data['id'] = $id;
            $data['name'] = $name;
            $data['content'] = $content;

            $("body").addClass("loading");
            $post = $.post("/Email/Template/Save", $data);

            $post.done(function (data) {
                $("body").removeClass("loading");
                switch (data['status']) {
                    case "OK":
                        $('#template-id').val(data['id']);
                        break;
                    case "notlogedin":
                        NotLogedIN();
                        break;
                    default:
                        console.log(data);
                        $.dialog({
                            title: 'Oops...',
                            content: 'Unknown Response from server. Please refresh the page and try again.'
                        });
                }
            });

            $post.fail(function () {
                NoReplyFromServer();
            });


        }
    </script>
@stop