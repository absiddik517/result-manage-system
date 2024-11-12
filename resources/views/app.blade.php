<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">

        <!-- Styles -->
        <!--
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" />
        -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <link rel="stylesheet" href="{{ mix('css/admin.css') }}">
        <!-- Scripts -->
        @routes
        <script src="{{ mix('js/admin.js') }}"></script>
        <script src="{{ mix('js/app.js') }}" defer></script>
        {{--
        <script src="{{ asset('dist/overlayscrollbars/OverlayScrollbars.min.js') }}"></script>
        --}}
    </head>
    <body class="sidebar-mini hold-transition layout-fixed">
        @inertia
    </body>
</html>
