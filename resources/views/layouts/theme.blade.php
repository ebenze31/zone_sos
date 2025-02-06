<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--favicon-->
        <link rel="icon" href="{{ url('/images/logos/default_logo.png') }}" type="image/png" />

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login</title>

        <!-- Styles -->
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- fontawesome icon -->
        <link rel="stylesheet" href="{{ asset('/partner/fonts/fontawesome/css/fontawesome-all.min.css') }}">
        <link href="https://kit-pro.fontawesome.com/releases/v6.7.2/css/pro.min.css" rel="stylesheet">

        <!-- font -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=K2D:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    </head>
    
    @php
        $btn_color = '#000';
        $text_btn_color = '#fff';
        $header_color = '#000';
    @endphp

    <style>

    :root
    {
    --btn-color : {{ $btn_color }};
    --text-btn-color: {{ $text_btn_color }};
    --header-color: {{ $header_color }};
    }

    .btn-color{
        background-color: var(--btn-color) !important;
        color: var(--text-btn-color) !important;
    }
    .header-text{
        color: var(--header-color) !important;
    }
    *:not(i) {
        font-family: "K2D", serif;
    }

    .nav-menu {
        position: relative;
        min-width: 70px;
    }

    .menu-active {
        color: #DB2D2E !important;

    }

    .menu-active::before {
        content: "";
        position: absolute;
        top: 108%;
        right: 0px;
        width: 100%;
        height: 15px;
        background-color: #DB2D2E;
        border-radius: 5px;
    }


    .dropdown {
        position: relative;
        display: inline-block;
    }

    .content-wrap {
        display: none;
        display: flex;
        position: absolute;
        top: 75px;
        right: 20px;
        background-color: white;
        width: max-content;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
        border-radius: 10px;
        font-size: 16px;
        border: 2px solid #FF8E8E;
        text-align: left;
    }

    .cont {
        display: none;
    }

    .dropdown-content a {
        color: #db2d2e;
        text-decoration: none;
        display: block;
        font-size: 19px;
        margin: 10px;
        font-weight: bold;
        position: relative;
    }

    .dropdown-content {
        margin: 10px;
        padding: 5px;
    }

    .dropdown-content a:hover::before {
        content: "";
        position: absolute;
        top: 30px;
        right: 0px;
        width: 100%;
        height: 3px;
        background-color: #db2d2e;
        border-radius: 5px;
    }


    .wrap {
        border: 2px solid #191919;
        padding: 10px 0;
        border-radius: 10px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        display: flex;
    }

    .dropbtn span {
        margin-left: 5px;
    }

    .active {
        display: block;
    }

    .bar {
        display: none;
        margin-left: auto;
        margin-right: 10px;
    }
</style>

<body class="bg-secondary">

    <!-- Content -->
    <main class="bg-gray-300">
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
