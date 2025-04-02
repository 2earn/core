<!doctype html>
<html dir="{{config('app.available_locales')[app()->getLocale()]['direction']}}"
      lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-topbar="light">
<head>
    <meta charset="utf-8"/>
    <title>@yield('title') | 2Earn.cash</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="2earn.cash" name="description"/>
    <meta content="2earn" name="author"/>
    <link rel="shortcut icon" href="{{ Vite::asset('resources/images/favicon.ico')}}">
    <livewire:styles/>
    <script src="https://www.google.com/recaptcha/api.js?render={{config('services.recaptcha.key')}}"></script>
    @if(config('app.available_locales')[app()->getLocale()]['direction'] === 'rtl')
        @vite(['resources/css/bootstrap-rtl.css','resources/css/icons-rtl.css','resources/css/app-rtl.css','resources/css/custom-rtl.css'])
    @else
        @vite(['resources/css/bootstrap.min.css','resources/css/icons.css','resources/css/app.css','resources/css/custom.css'])
    @endif
    @vite([ 'resources/css/intlTelInput.min.css','resources/js/sweetalert2@11.js','resources/js/appWithoutNav.js','resources/js/intlTelInput.js'])
</head>
<body>
<div class="container-fluid">
    @yield('content')
</div>
<livewire:scripts/>
</body>
</html>
