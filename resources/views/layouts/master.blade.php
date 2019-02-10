<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">

        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/animate.css') }}" rel="stylesheet" type="text/css"/>

        <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/daterangepicker.min.js') }}"></script>

        <link rel="stylesheet" type="text/css" href="{{ asset('css/daterangepicker.css') }}" />
        <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet">

        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2019.1.115/styles/kendo.common-material.min.css" />
        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2019.1.115/styles/kendo.material.min.css" />
        <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2019.1.115/styles/kendo.material.mobile.min.css" />

        <script src="https://kendo.cdn.telerik.com/2019.1.115/js/kendo.all.min.js"></script>

        <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/responsive.css') }}" rel="stylesheet" type="text/css"/> 
        <style>
            canvas {
                -moz-user-select: none;
                -webkit-user-select: none;
                -ms-user-select: none;
            }
        </style>
        @yield('styles')
    </head>
    <body>

        @include('includes.header')
        @yield('content')
        <div id="AjaxLoaderDiv">
            <div id="blank_part">
            </div>
            <div id="img_part">
                <img src="{{ asset('images/ajax-loader.gif') }}" alt="Ajax Loader" />
            </div>
        </div>        
        <script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/jquery-ui.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/jquery.bootstrap-growl.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/custom.js') }}" type="text/javascript"></script>        
        <script src="{{ asset('js/submitForm.js') }}" type="text/javascript"></script>        
        @yield('scripts')
    </body>
</html>
