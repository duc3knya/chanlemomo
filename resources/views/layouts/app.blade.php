<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="CMSNT.CO">
    <title>{{ $GetSetting->title }} | {{ $GetSetting->namepage }}</title>
    <meta name="description" content="{{ $GetSetting->description }}" />
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $GetSetting->description }}">
    <meta property="og:description" content="{{ $GetSetting->description }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ asset('image/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery-ui-1.9.2.custom.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.1.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-social.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/katex.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/monokai-sublime.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('css/quill.bubble.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    .navbar {
        position: relative;
        z-index: 501;
        min-height: 60px;
        margin-bottom: 0;
        background-color: {{ $GetSetting->color_header }};
        border: none;
        border-top-right-radius: 0;
        border-top-left-radius: 0;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }
    
    .panel-primary>.panel-heading {
        color: #fff;
        background-color: {{ $GetSetting->color_table }};
        border-color: {{ $GetSetting->color_table }};
    }

    .panel-primary {
        border-color: {{ $GetSetting->color_table }};
    }

    .panel-primary>.panel-heading+.panel-collapse .panel-body {
        border-top-color: {{ $GetSetting->color_table }};
    }

    .panel-primary>.panel-footer+.panel-collapse .panel-body {
        border-bottom-color: {{ $GetSetting->color_table }};
    }

    .footer {
        padding: 20px 0;
        margin-top: 2em;
        font-size: 12px;
        background: {{ $GetSetting->color_footer }};
        border-top: 7px solid {{ $GetSetting->color_footer }};
    }

    .bg-primary2 {
        color: #fff ;
        background-color: {{ $GetSetting->color_table2 }} !important;
    }

    </style>
    @yield('style')
</head>
<body>
@include("layouts.elements.header")
<div id="main">
    @yield('content')
</div>
@include("layouts.elements.footer")
<!--/Footer-->
<script src="{{ asset('js/jquery-1.10.1.min.js') }}"></script>
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/jquery-ui-1.9.2.custom.min.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/bootbox.js') }}"></script>
<script src="{{ asset('js/tip.js') }}"></script>
<script src="{{ asset('js/alert.js?abcd') }}"></script>
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
@yield('script')
{!! $GetSetting->script !!}
</body>
</html>
