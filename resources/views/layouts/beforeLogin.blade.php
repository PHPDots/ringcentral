<!DOCTYPE html>
<html>
    <head>
        <title>Log into your account</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/responsive.css') }}" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="login-section">
            @yield('content')
        </div>
        <div id="AjaxLoaderDiv">
            <div id="blank_part">
            </div>
            <div id="img_part">
                <img src="{{ asset('images/ajax-loader.gif') }}" alt="Ajax Loader" />
            </div>
        </div>        
        <script src="{{ asset('js/jquery-1.12.4.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript"></script>        
        <script src="{{ asset('js/jquery.bootstrap-growl.min.js') }}" type="text/javascript"></script>        
        <script src="{{ asset('js/login.js') }}" type="text/javascript"></script>
    </body>
</html>