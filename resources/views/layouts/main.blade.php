<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @prepend('styles')
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link rel="dns-prefetch" href="//fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i&display=swap&subset=cyrillic"
              rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('admin-assets/css/admin.all.css') }}">
    @endprepend
    @stack('styles')

    <title>{{ config('app.name') }} | {{ __('Administrative Panel') }}</title>

    <script>window.deferredCallbacks = {};</script>
</head>
<body class="bg-light">

{{--body--}}
@yield('body')

@prepend('scripts')
    <script src="{{ asset('admin-assets/js/admin.all.js') }}"></script>
@endprepend
@stack('scripts')
</body>
</html>
