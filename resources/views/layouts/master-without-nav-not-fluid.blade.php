<!doctype html>
<html dir="{{config('app.available_locales')[app()->getLocale()]['direction']}}"
      lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-topbar="light">
<head>
    <meta charset="utf-8"/>
    <title>@yield('title') | 2Earn.cash</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="2earn.cash" name="description"/>
    <meta content="Themesbrand" name="author"/>
    <link rel="shortcut icon" href="{{ Vite::asset('resources/images/favicon.ico')}}">
    @livewireStyles
</head>
<body>
<div class="container">
    @yield('content')
</div>
@include('layouts.footer-static', ['pageName' => 'static'])
@livewireScripts
</body>
</html>
