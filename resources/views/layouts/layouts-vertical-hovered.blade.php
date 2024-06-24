<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-layout-style="default"
      data-layout-position="fixed" data-topbar="light" data-sidebar="dark" data-sidebar-size="sm-hover"
      data-layout-width="fluid">
<head>
    <meta charset="utf-8"/>
    <title>@yield('title') | Velzon - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="2earn.cash" name="description"/>
    <meta content="Themesbrand" name="author"/>
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico')}}">
    @include('layouts.head-css')
    @livewireStyles
</head>
<body>
<div id="layout-wrapper">
    @include('layouts.topbar')
    @include('layouts.sidebar')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
        @include('layouts.footer', ['pageName' => 'layouts-v'])
    </div>
</div>
@include('layouts.customizer')
@include('layouts.vendor-scripts')
@livewireScripts
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js"
        data-turbolinks-eval="false" data-turbo-eval="false"></script>
<script src="{{ URL::asset('/assets/js/app.min.js') }}" defer></script>
</body>
</html>
