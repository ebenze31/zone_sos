<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Zone_SOS') }}</title>

    <link rel="shortcut icon" href="{{ asset('/img/logo/logo_x-icon.png') }}" type="image/x-icon" />
    <link href="https://kit-pro.fontawesome.com/releases/v6.4.2/css/pro.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('video_call_theme/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>
    <style>
        html,
        body,
        .full-height,
        .page-content,
        .wrapper {
            height: calc(100%);
            min-height: calc(100% ) !important;
            padding-bottom: 0;
            /* padding-top: 0; */
            /* margin-top: 0; */
            margin-bottom: 0;
            max-width: 100%;
            position: relative;
        }
    </style>
    <div id="app">
        <main class="">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
